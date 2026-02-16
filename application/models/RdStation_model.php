<?php

class RdStation_model extends TA_model
{
    public $key = "";

    public function __construct()
    {
        parent::__construct();
    }

    function AddRD($code): bool
    {

        $url_query = array(
            'client_id' => $this->config->item('rdstation_client_id'),
            'client_secret' => $this->config->item('rdstation_secret_id'),
            'code' => $code,
        );
        $url_query = http_build_query($url_query);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rd.services/auth/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $url_query,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);
        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ((int)$status_code == 200) {

            $dados = json_decode($response, true);

            $date = new DateTime("+ {$dados['expires_in']} seconds");
            $data = array(
                'name' => 'RD Station',
                'id' => $dados['refresh_token'],
                'pw' => $dados['access_token'],
                't' => $date->getTimestamp(),
                'type' => "13"
            );
            $this->db->insert('channel', $data);
            $this->key = $dados['access_token'];
            return true;
        } else {
            throw new Exception("A requisição falhou \n Status CODE: {$status_code} \n Resposta: {$response}");
        }
        return false;
    }


    function RefreshToken($refresh_token): bool
    {
        $url_query = array(
            'client_id' => $this->config->item('rdstation_client_id'),
            'client_secret' => $this->config->item('rdstation_secret_id'),
            'refresh_token' => $refresh_token,
        );
        $url_query = http_build_query($url_query);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rd.services/auth/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $url_query,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);
        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ((int)$status_code == 200) {

            $dados = json_decode($response, true);

            $date = new DateTime("+ {$dados['expires_in']} seconds");
            $this->db->set('id', $dados['refresh_token']);
            $this->db->set('pw', $dados['access_token']);
            $this->db->set('t', $date->getTimestamp());
            $this->db->where('id', $refresh_token);
            $this->db->update('channel');
            $this->key = $dados['access_token'];
            return true;
        } else {
            throw new Exception("A requisição falhou \n Status CODE: {$status_code} \n Resposta: {$response}");
        }
        return false;
    }


    public function init($DB = null)
    {

        if ($DB != null) {
            $this->db = $DB;
        }

        $sql = "SELECT * FROM channel where type = 13 limit 1";
        $query = $this->db->query($sql);

        $count = $query->num_rows();

        if ($count > 0) {
            $data = $query->result_array();
            $data = $data[0];
            $date = new DateTime();

            $start = (int)$date->getTimestamp();
            $end = (int)$data['t'];

            if ($start > $end) {
                $this->RefreshToken($data['id']);
            }

            $this->key = (string)$data['pw'];
        } else {
            throw new Exception('code not found');
        }
    }


    public function AddContact($arr, $email)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.rd.services/platform/contacts/email:{$email}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PATCH",
            CURLOPT_POSTFIELDS => json_encode($arr),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ),
        ));

        $response = curl_exec($curl);
        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ((int)$status_code == 200) {
            $dados = json_decode($response, true);
            return $dados;
        } else if ((int)$status_code == 504) {
            sleep(10);
            $this->AddContact($arr, $email);
        } else {
            throw new Exception("A requisição falhou \n Status CODE: {$status_code} \n Resposta: {$response}");
        }
    }
    

    public function SyncContacts()
    {

        $i = 0;
        while (true) {

            $sql =  "SELECT * FROM contact where is_private = 1 and is_group = 1 and email != '' and email is not null and crm_profile is null\n";
            $sql .=  "limit $i, 1000\n";

            $query = $this->db->query($sql);

            $count = $query->num_rows();

            if ($count == 0) {
                break;
            } else {
                $aContatos = $query->result_array();

                foreach ($aContatos as $row) {
                    $arr = array(
                        'name' => $row['full_name'],
                        'mobile_phone' => $row['key_remote_id'],
                    );

                    if ($row['email'] != null) {

                        $data = $this->AddContact($arr, $row['email']);

                        if ($data != null) {
                            $url_query = array(
                                'query' => $row['email'],
                            );
                            $crm_profile = 'https://app.rdstation.com.br/leads?' . http_build_query($url_query);

                            $date = new DateTime();
                            $this->db->set('crm_profile', $crm_profile);
                            $this->db->set('crm_timestamp', $date->getTimestamp());
                            $this->db->where('email', $row['email']);
                            $this->db->update('contact');
                        }
                    }


                    $i++;
                }
            }
        }
    }

    public function GetAccountInfo()
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/marketing/account_info",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function GetTrackingCode()
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/marketing/tracking_code",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function GetContactByUUID($uuid)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/contacts/{$uuid}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function GetContactByEmail($email)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/contacts/email:{$email}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function UpdateContactByUUID($uuid, $data)
    {
        $fields = [
            "email",
            "name",
            "bio",
            "job_title",
            "linkedin",
            "facebook",
            "city",
            "state",
            "country",
            "twitter",
            "personal_phone",
            "mobile_phone",
            "website",
            "tags",
            "legal_bases"
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/contacts/uuid:{$uuid}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PATCH",
            CURLOPT_POSTFIELDS => json_encode($this->FormatValues($fields, $data)),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->key}",
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function UpdateContactByEmail($email, $data)
    {
        $fields = [
            "name",
            "bio",
            "job_title",
            "linkedin",
            "facebook",
            "city",
            "state",
            "country",
            "twitter",
            "personal_phone",
            "mobile_phone",
            "website",
            "tags",
            "legal_bases"
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/contacts/email:{$email}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PATCH",
            CURLOPT_POSTFIELDS => json_encode($this->FormatValues($fields, $data)),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->key}",
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function GetFunnelByContactUUID($uuid, $funnel_name)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/contacts/{$uuid}/funnels/{$funnel_name}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function GetFunnelByContactEmail($email, $funnel_name)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/contacts/email:{$email}/funnels/{$funnel_name}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function UpdateFunnelByContactUUID($uuid, $email, $funnel_name, $data)
    {
        $fields = [
            "lifecycle_stage",
            "opportunity",
            "contact_owner_email"
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/contacts/{$uuid}|email:{$email}/funnels/{$funnel_name}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode($this->FormatValues($fields, $data)),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->key}",
                "Content-Type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function GetFields()
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/contacts/fields",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function CreateField($data)
    {
        $fields = [
            "api_identifier",
            "data_type",
            "label",
            "name",
            "presentation_type",
            "validation_rules",
            "valid_options",
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/contacts/fields",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($this->FormatValues($fields, $data)),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function UpdateField($uuid, $data)
    {
        $fields = [
            "data_type",
            "label",
            "name",
            "presentation_type",
            "validation_rules",
            "valid_options",
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/contacts/fields/{$uuid}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PATCH",
            CURLOPT_POSTFIELDS => json_encode($this->FormatValues($fields, $data)),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function DeleteField($uuid)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/contacts/fields/{$uuid}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function GetWebhooks()
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/integrations/webhooks",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function CreateWebhook($data)
    {
        $fields = [
            "event_type",
            "entity_type",
            "event_identifiers",
            "url",
            "http_method",
            "include_relations",
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/integrations/webhooks",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($this->FormatValues($fields, $data)),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function UpdateWebhook($uuid, $data)
    {
        $fields = [
            "event_type",
            "entity_type",
            "event_identifiers",
            "url",
            "http_method",
            "include_relations",
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/integrations/webhooks/{$uuid}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode($this->FormatValues($fields, $data)),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function DeleteWebhook($uuid)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/integrations/webhooks/{$uuid}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "DELETE",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function EventConversion($data)
    {
        $data["event_type"] = "CONVERSION";

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/events",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "event_type" => "CONVERSION",
                "event_family" => $data["event_family"],
                "conversion_identifier" => $data["conversion_identifier"],
                "name" => $data["name"],
                "email" => $data["email"],
                "job_title" => $data["job_title"],
                "state" => $data["state"],
                "city" => $data["city"],
                "country" => $data["country"],
                "personal_phone" => $data["personal_phone"],
                "mobile_phone" => $data["mobile_phone"],
                "twitter" => $data["twitter"],
                "facebook" => $data["facebook"],
                "linkedin" => $data["linkedin"],
                "website" => $data["website"],
                "cf_custom_field_api_identifier" => $data["cf_custom_field_api_identifier"],
                "company_name" => $data["company_name"],
                "company_site" => $data["company_site"],
                "company_address" => $data["company_address"],
                "client_tracking_id" => $data["client_tracking_id"],
                "traffic_source" => $data["traffic_source"],
                "traffic_medium" => $data["traffic_medium"],
                "traffic_campaign" => $data["traffic_campaign"],
                "traffic_value" => $data["traffic_value"],
                "tags" => $data["tags"],
                "available_for_mailing" => $data["available_for_mailing"],
                "legal_bases" => $data["legal_bases"]
            ]),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function EventOpportunity($data)
    {
        $data["event_type"] = "OPPORTUNITY";

        $fields = [
            "event_type",
            "event_family",
            "funnel_name",
            "email",
        ];
        
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/events",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($this->FormatValues($fields, $data)),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function EventOpportunityWon($data)
    {
        $data["event_type"] = "SALE";

        $fields = [
            "event_type",
            "event_family",
            "funnel_name",
            "email",
            "value",
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/events",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($this->FormatValues($fields, $data)),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function EventOpportunityLost($data)
    {
        $data["event_type"] = "OPPORTUNITY_LOST";

        $fields = [
            "event_type",
            "event_family",
            "funnel_name",
            "email",
            "reason",
        ];
        
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/events",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($this->FormatValues($fields, $data)),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function EventOrderPlaced($data)
    {
        $data["event_type"] = "ORDER_PLACED";

        $fields = [
            "event_type",
            "event_family",
            "name",
            "email",
            "cf_order_id",
            "cf_order_total_items",
            "cf_order_status",
            "cf_order_payment_method",
            "cf_order_payment_amount",
            "legal_bases",
        ];
        
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/events",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($this->FormatValues($fields, $data)),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function EventOrderPlacedItem($data)
    {
        $data["event_type"] = "ORDER_PLACED_ITEM";

        $fields = [
            "event_type",
            "event_family",
            "name",
            "email",
            "cf_order_id",
            "cf_order_product_id",
            "cf_order_product_sku",
            "legal_bases",
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/events",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($this->FormatValues($fields, $data)),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function EventCartAbandoned($data)
    {
        $data["event_type"] = "CART_ABANDONED";

        $fields = [
            "event_type",
            "event_family",
            "name",
            "email",
            "cf_cart_id",
            "cf_cart_total_items",
            "cf_cart_status",
            "legal_bases",
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/events",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($this->FormatValues($fields, $data)),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function EventCartAbandonedItem($data)
    {
        $data["event_type"] = "CART_ABANDONED_ITEM";

        $fields = [
            "event_type",
            "event_family",
            "name",
            "email",
            "cf_cart_id",
            "cf_cart_product_id",
            "cf_cart_product_sku",
            "legal_bases",
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/events",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($this->FormatValues($fields, $data)),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function EventChatStarted($data)
    {
        $data["event_type"] = "CHAT_STARTED";

        $fields = [
            "event_type",
            "event_family",
            "chat_subject",
            "cf_chat_status",
            "cf_chat_type",
            "cf_birthdate",
            "cf_gender",
            "name",
            "email",
            "job_title",
            "personal_phone",
            "mobile_phone",
            "twitter",
            "facebook",
            "linkedin",
            "website",
            "company_name",
            "company_site",
            "legal_bases",
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/events",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($this->FormatValues($fields, $data)),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function EventChatFinished($data)
    {
        $data["event_type"] = "CHAT_FINISHED";

        $fields = [
            "event_type",
            "event_family",
            "chat_subject",
            "cf_chat_status",
            "cf_chat_transcript_message",
            "cf_chat_type",
            "cf_birthdate",
            "cf_gender",
            "name",
            "email",
            "job_title",
            "personal_phone",
            "mobile_phone",
            "twitter",
            "facebook",
            "linkedin",
            "website",
            "company_name",
            "company_site",
            "legal_bases",
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/events",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($this->FormatValues($fields, $data)),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function EventCallFinished($data)
    {
        $data["event_type"] = "CALL_FINISHED";

        $fields = [
            "event_type",
            "event_family",
            "name",
            "email",
            "company_name",
            "company_site",
            "job_title",
            "personal_phone",
            "call_user_email",
            "call_from_number",
            "call_started_at",
            "call_duration",
            "call_type",
            "call_status",
            "call_status_description",
            "call_phone_type",
            "call_carrier",
            "call_record",
            "legal_bases",
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/events",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($this->FormatValues($fields, $data)),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function EventMediaPlayblackStarted($data)
    {
        $data["event_type"] = "MEDIA_PLAYBACK_STARTED";

        $fields = [
            "event_type",
            "event_family",
            "email",
            "name",
            "company_name",
            "company_site",
            "job_title",
            "personal_phone",
            "mobile_phone",
            "twitter",
            "facebook",
            "linkedin",
            "website",
            "media_type",
            "media_metadata",
            "media_recorded_content",
            "media_identifier",
            "media_category",
            "media_duration",
            "media_published_date_timestamp",
            "legal_bases",
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/events",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($this->FormatValues($fields, $data)),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function EventMediaPlayblackStopped($data)
    {
        $data["event_type"] = "MEDIA_PLAYBACK_STOPPED";

        $fields = [
            "event_type",
            "event_family",
            "email",
            "name",
            "company_name",
            "company_site",
            "job_title",
            "personal_phone",
            "mobile_phone",
            "twitter",
            "facebook",
            "linkedin",
            "website",
            "media_type",
            "media_metadata",
            "media_recorded_content",
            "media_identifier",
            "media_category",
            "media_duration",
            "media_published_date_timestamp",
            "media_finished",
            "media_user_interaction_duration",
            "media_user_interaction_percentage",
            "media_user_interaction_date_timestamp",
            "legal_bases",
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.rd.services/platform/events",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($this->FormatValues($fields, $data)),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ],
        ]);
        
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err)
            return false;
        
        return [
            "code" => $code,
            "response" => json_decode($response, true)
        ];
    }

    public function FormatValues($fields, $data)
    {
        $ret = [];

        foreach ($fields as $field) {
            if(isset($data[$field]))
                $ret[$field] = $data[$field];
        }

        return $ret;
    }
}
