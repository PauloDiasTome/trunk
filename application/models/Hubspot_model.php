<?php

class Hubspot_model extends TA_model
{
    public $key = "";
    public $hubSpot;

    public function __construct()
    {
        parent::__construct();
    }

    function AddHubspot($code) : bool{

        $url_query = array(
            'grant_type' => 'authorization_code',
            'client_id'=> $this->config->item('hubspot_client_id'),
            'client_secret'=> $this->config->item('hubspot_secret_id'),
            'redirect_uri'=> base_url('integration/add/hubspot'),
            'code'=> $code,
        );
        $url_query = http_build_query($url_query);

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.hubapi.com/oauth/v1/token",
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

        if((int)$status_code == 200){
            
            $dados = json_decode($response, true);

            $date = new DateTime("+ {$dados['expires_in']} seconds");
            $data = array(
                'name' => 'Hubspot',
                'id' => $dados['refresh_token'],
                'pw' => $dados['access_token'],
                't' => $date->getTimestamp(),
                'type' => "14"
            );
            $this->db->insert('channel', $data);
            $this->key = $dados['access_token'];
            return true;
        }
        else{
            throw new Exception("A requisição falhou \n Status CODE: {$status_code} \n Resposta: {$response}");
        }
        return false;
    }


    function RefreshToken($refresh_token) : bool {
        $url_query = array(
            'grant_type' => 'refresh_token',
            'client_id'=> $this->config->item('hubspot_client_id'),
            'client_secret'=> $this->config->item('hubspot_secret_id'),
            'refresh_token'=> $refresh_token,
        );
        $url_query = http_build_query($url_query);

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.hubapi.com/oauth/v1/token",
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

        if((int)$status_code == 200){
            
            $dados = json_decode($response, true);

            $date = new DateTime("+ {$dados['expires_in']} seconds");
            $this->db->set('id', $dados['refresh_token']);
            $this->db->set('pw', $dados['access_token']);
            $this->db->set('t', $date->getTimestamp());
            $this->db->where('id', $refresh_token);
            $this->db->update('channel');
            $this->key = $dados['access_token'];
            return true;
        }
        else{
            throw new Exception("A requisição falhou \n Status CODE: {$status_code} \n Resposta: {$response}");
        }
        return false;
    }

    //   https://developers.hubspot.com/apps/api_guidelines
    // 	 Limits Free & Starter
    //      Burst: 100/10 seconds
    //      Daily: 250,000
    public function init($DB = null){
        
        if($DB != null){
            $this->db = $DB;
        }
        
        $sql = "SELECT * FROM channel where type = 14 limit 1;";
        $query = $this->db->query($sql);

        $count = $query->num_rows();

        if ($count > 0) {
            $data = $query->result_array();
            $data = $data[0];
            $date = new DateTime();

            $start = (int)$date->getTimestamp();
            $end = (int)$data['t'];

            if($start > $end){
               $this->RefreshToken($data['id']);
            }

            $this->key = (string)$data['pw'];
            $this->hubSpot = \HubSpot\Factory::createWithAccessToken($this->key);
        }
        else{
            throw new Exception('code not found');
        }
    }


    public function GetContactsBatch($arr) : array{
       
        $url_query = "";
        $dados = array();
        $i = 0;
        $quant = 0;
        foreach($arr as $row){
            
            $query = array(
                'email' => $row['email'],
            );

            $url_query =  $url_query . ($i == 0 ? "" : "&") . http_build_query($query);

            $i++;
            $quant = $quant + 1;
            //SEMPRE FAZ DE 40 EM 40 , SE NÃO FAZ 1 POR 1
            if($quant == 40 || (count($arr) <= 40 && count($arr) >= 2)){
                $quant = 0;
                
                $curl = curl_init();

                curl_setopt_array($curl, array(
                  CURLOPT_URL => "https://api.hubapi.com/contacts/v1/contact/emails/batch/?{$url_query}",
                  CURLOPT_RETURNTRANSFER => true,
                  CURLOPT_ENCODING => "",
                  CURLOPT_MAXREDIRS => 10,
                  CURLOPT_TIMEOUT => 0,
                  CURLOPT_FOLLOWLOCATION => true,
                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  CURLOPT_CUSTOMREQUEST => "GET",
                  CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer {$this->key}"
                  ),
                ));
                
                $response = curl_exec($curl);
                $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);
        
                if((int)$status_code == 200){
                   
                    $json = json_decode($response, true);

                    $update = array();
                    foreach ($json as $obj){
                        $date = new DateTime();
                        $dados = array(
                            'email' => $obj['properties']['email']['value'],
                            'crm_profile'=> $obj['profile-url'],
                            'crm_timestamp' => $date->getTimestamp()
                        );
                        array_push($update, $dados);
                        
                    }
                    $this->db->update_batch('contact', $update, 'email');
                    sleep(5);
                }
                else{
                    throw new Exception("A requisição falhou \n Status CODE: {$status_code} \n Resposta: {$response}");
                }
                $url_query = "";
            }
           
        }

        return $dados;
    }


    public function AddContactsBatch($arr){

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.hubapi.com/contacts/v1/contact/batch/",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>json_encode($arr),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "Authorization: Bearer {$this->key}"
            ),
        ));
        
        $response = curl_exec($curl);
        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if((int)$status_code == 202){
            return;
        }
        else{
            throw new Exception("A requisição falhou \n Status CODE: {$status_code} \n Resposta: {$response}");
        }
    }

    
    public function SyncContacts(){
        
        $i = 0;
        while (true) {
            $aContatos = array();

            $sql =  "SELECT * FROM contact where is_private = 1 and is_group = 1 and email != '' and email is not null and crm_profile is null\n";
            $sql .=  "limit $i, 1000\n";

            $query = $this->db->query($sql);

            $count = $query->num_rows();
    
            if ($count == 0) {
                break;
            }
            else{
                $aContatos = $query->result_array();
                $contact = array();
                foreach ($aContatos as $row) {
                    $arr = array(
                        "email" => $row['email'],
                        'properties' => array(
                            array(
                                'property' => 'firstname',
                                'value' => $row['full_name']
                            ),
                            array(
                                'property' => 'phone',
                                'value' =>  $row['key_remote_id']
                            )
                        )
                    );
                    array_push($contact, $arr);
                    $i++;
                }
                $this->AddContactsBatch($contact);
                $this->GetContactsBatch($contact);
            }
        }
    }

    public function GetContacts()
    {
        $response = $this->hubSpot->crm()->contacts()->basicApi()->getPage();

        return $response;
    }

    public function GetContactById($id)
    {
        $filter = new \HubSpot\Client\Crm\Contacts\Model\Filter();
        $filter->setOperator('EQ')
                ->setPropertyName('hs_object_id')
                ->setValue($id);

        $filterGroup = new \HubSpot\Client\Crm\Contacts\Model\FilterGroup();
        $filterGroup->setFilters([$filter]);

        $searchRequest = new \HubSpot\Client\Crm\Contacts\Model\PublicObjectSearchRequest();
        $searchRequest->setFilterGroups([$filterGroup]);

        $contactsPage = $this->hubSpot->crm()->contacts()->searchApi()->doSearch($searchRequest);

        return $contactsPage;
    }

    public function GetContactByEmail($email)
    {
        $filter = new \HubSpot\Client\Crm\Contacts\Model\Filter();
        $filter->setOperator('EQ')
                ->setPropertyName('email')
                ->setValue($email);

        $filterGroup = new \HubSpot\Client\Crm\Contacts\Model\FilterGroup();
        $filterGroup->setFilters([$filter]);

        $searchRequest = new \HubSpot\Client\Crm\Contacts\Model\PublicObjectSearchRequest();
        $searchRequest->setFilterGroups([$filterGroup]);

        $contactsPage = $this->hubSpot->crm()->contacts()->searchApi()->doSearch($searchRequest);

        return $contactsPage;
    }

    public function CreateContact($data)
    {
        $contactInput = new \HubSpot\Client\Crm\Contacts\Model\SimplePublicObjectInput();
        $contactInput->setProperties($data);

        $contact = $this->hubSpot->crm()->contacts()->basicApi()->create($contactInput);

        return $contact;
    }

    public function UpdateContact($id, $data)
    {
        $newProperties = new \HubSpot\Client\Crm\Contacts\Model\SimplePublicObjectInput();
        $newProperties->setProperties($data);

        $contact = $this->hubSpot->crm()->contacts()->basicApi()->update($id, $newProperties);

        return $contact;
    }

    public function GetDeals()
    {
        $response = $this->hubSpot->crm()->deals()->basicApi()->getPage();

        return $response;
    }

    public function GetDealById($id)
    {
        $filter = new \HubSpot\Client\Crm\Deals\Model\Filter();
        $filter->setOperator('EQ')
                ->setPropertyName('hs_object_id')
                ->setValue($id);

        $filterGroup = new \HubSpot\Client\Crm\Deals\Model\FilterGroup();
        $filterGroup->setFilters([$filter]);

        $searchRequest = new \HubSpot\Client\Crm\Deals\Model\PublicObjectSearchRequest();
        $searchRequest->setFilterGroups([$filterGroup]);

        $response = $this->hubSpot->crm()->deals()->searchApi()->doSearch($searchRequest);

        return $response;
    }

    public function CreateDeal($data)
    {
        $input = new \HubSpot\Client\Crm\Deals\Model\SimplePublicObjectInput();
        $input->setProperties($data);

        $response = $this->hubSpot->crm()->deals()->basicApi()->create($input);

        return $response;
    }

    public function UpdateDeal($id, $data)
    {
        $input = new \HubSpot\Client\Crm\Deals\Model\SimplePublicObjectInput();
        $input->setProperties($data);

        $response = $this->hubSpot->crm()->deals()->basicApi()->update($id, $input);

        return $response;
    }

    public function GetCompanies()
    {
        $response = $this->hubSpot->crm()->companies()->basicApi()->getPage();

        return $response;
    }

    public function GetCompanyById($id)
    {
        $filter = new \HubSpot\Client\Crm\Companies\Model\Filter();
        $filter->setOperator('EQ')
                ->setPropertyName('hs_object_id')
                ->setValue($id);

        $filterGroup = new \HubSpot\Client\Crm\Companies\Model\FilterGroup();
        $filterGroup->setFilters([$filter]);

        $searchRequest = new \HubSpot\Client\Crm\Companies\Model\PublicObjectSearchRequest();
        $searchRequest->setFilterGroups([$filterGroup]);

        $response = $this->hubSpot->crm()->companies()->searchApi()->doSearch($searchRequest);

        return $response;
    }

    public function CreateCompany($data)
    {
        $input = new \HubSpot\Client\Crm\Companies\Model\SimplePublicObjectInput();
        $input->setProperties($data);

        $response = $this->hubSpot->crm()->companies()->basicApi()->create($input);

        return $response;
    }

    public function UpdateCompany($id, $data)
    {
        $input = new \HubSpot\Client\Crm\Companies\Model\SimplePublicObjectInput();
        $input->setProperties($data);

        $response = $this->hubSpot->crm()->companies()->basicApi()->update($id, $input);

        return $response;
    }
}