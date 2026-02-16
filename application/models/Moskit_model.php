<?php

class Moskit_model extends TA_model
{
    public $key = "";

    public function __construct()
    {
        parent::__construct();

        $this->key = $this->config->item("moskit_api_key");
    }

    /*Section User*/
    
    public function SearchUserByUsername($username)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/users/search",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([[
                "field" => "username",
                "expression" => "like",
                "values" => [
                    "{$username}"
                ]
            ]]),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: {$this->key}"
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

    /*End Section User*/

    /*Section Contact*/

    public function SearchContactsByPhone($number)
    {
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/contacts/search",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([[
                "field" => "phones",
                "expression" => "like",
                "values" => [
                    "{$number}"
                ]
            ]]),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: {$this->key}"
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

    /* Tested */
    public function CreateContact($data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/contacts",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "responsible" => [
                    "id" => $data["responsible_id"]
                ],
                "createdBy" => [
                    "id" => $data["createdby_id"]
                ],  
                "name" => $data["name"],
                "phones" => [
                    [
                        "number" => $data["phone"]
                    ]
                ],
                "emails" => [
                    [
                        "address" => $data["email"]
                    ]
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "X-Moskit-Origin: MOSKIT_API_V2",
                "apikey: {$this->key}"
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

    public function CreateNoteToContact($contact_id, $data)
    {
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/contacts/{$contact_id}/notes",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "description" => $data["description"],
                "user" => [
                    "id" => $data["user_id"]
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "X-Moskit-Origin: MOSKIT_API_V2",
                "apikey: {$this->key}"
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

    public function GetContactById($contact_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/contacts/{$contact_id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "apikey: {$this->key}"
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

    public function UpdateContact($contact_id, $data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/contacts/{$contact_id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode([
                "responsible" => [
                    "id" => $data["responsible_id"]
                ],
                "createdBy" => [
                    "id" => $data["createdby_id"]
                ],  
                "name" => $data["name"],
                "phones" => [
                    [
                        "number" => $data["phone"]
                    ]
                ],
                "emails" => [
                    [
                        "address" => $data["email"]
                    ]
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "X-Moskit-Origin: MOSKIT_API_V2",
                "apikey: {$this->key}"
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

    public function GetNoteContactById($contact_id, $note_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/contacts/{$contact_id}/notes/{$note_id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: {$this->key}"
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

    public function GetNotesFromContact($contact_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/contacts/{$contact_id}/notes",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: {$this->key}"
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

    public function GetDealsFromContact($contact_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/contacts/{$contact_id}/deals",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: {$this->key}"
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

    public function GetContacts()
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/contacts",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: {$this->key}"
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

    public function GetActivitiesFromContact($contact_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/contacts/{$contact_id}/activities",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: {$this->key}"
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

    public function GetEmployersFromContact($contact_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/contacts/{$contact_id}/employers",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: {$this->key}"
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

    public function GetDealFromContactAsParticipants($contact_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/contacts/{$contact_id}/dealParticipants",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: {$this->key}"
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

    public function GetAttachmentsContactById($contact_id, $attachment_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/contacts/{$contact_id}/attachments/{$attachment_id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: {$this->key}"
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

    public function GetAttachmentsFromContact($contact_id)
    {

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/contacts/{$contact_id}/attachments",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: {$this->key}"
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

    public function CreateAttachmentToContact($contact_id, $url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/contacts/{$contact_id}/attachments",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "url" => $url,
            ]),            
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "X-Moskit-Origin: MOSKIT_API_V2",
                "apikey: {$this->key}"
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

    /*End Section Contact*/

    /*Section Funil Deal*/

    public function CreatePipeline($data)
    {
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/pipelines",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "name" => $data["name"],
            ]),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: {$this->key}"
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

    public function UpdatePipeline($pipeline_id, $data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/pipelines/{$pipeline_id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode([
                "name" => $data["name"],
            ]),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: {$this->key}"
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

    public function GetPipelines()
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/pipelines",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: {$this->key}"
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

    public function GetPipelineById($pipeline_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/pipelines/{$pipeline_id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: {$this->key}"
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

    /*End Funil Deal*/

    /*Section Stage Deal*/

    public function CreateStage($data) 
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/stages",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "pipeline" => [
                    "id" => $data["pipeline_id"]
                ],
                "name" => $data["name"],
            ]),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: {$this->key}"
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

    public function UpdateStage($stage_id, $data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/stages/{$stage_id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode([
                "pipeline" => [
                    "id" => $data["pipeline_id"]
                ],
                "name" => $data["name"],
            ]),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: {$this->key}"
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

    public function GetStages()
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/stages",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: {$this->key}"
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

    public function GetStageById($stage_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/stages/{$stage_id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: {$this->key}"
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

    /*End Section Deal*/

    /*Section Deal*/
    
    public function CreateDeal($data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/deals",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "responsible" => [
                    "id" => $data["responsible_id"]
                ],
                "createdBy" => [
                    "id" => $data["createdby_id"]
                ],  
                "stage" => [
                    "id" => $data["stage_id"]
                ],
                "name" => $data["name"],
                "status" => $data["status"],
                "dealProducts" => [
                    [
                        "quantity" => $data["deal_product_quantity"],
                        "price" => $data["deal_product_price"],
                        "finalPrice" => $data["deal_product_final_price"],
                        "initialPrice" => $data["deal_product_initial_price"],
                        "product" => [
                            "id" => $data["product_id"],
                            "name" => $data["product_name"],
                            "active" => $data["product_active"],
                            "price" => $data["product_price"],
                            "createdBy" => [
                                "id" => $data["createdby_id"]
                            ],
                        ]
                    ]
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "X-Moskit-Origin: MOSKIT_API_V2",
                "apikey: {$this->key}"
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

    public function UpdateDeal($deal_id, $data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/deals/{$deal_id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode([
                "responsible" => [
                    "id" => $data["responsible_id"]
                ],
                "createdBy" => [
                    "id" => $data["createdby_id"]
                ],  
                "stage" => [
                    "id" => $data["stage_id"]
                ],
                "name" => $data["name"],
                "status" => $data["status"],
                "dealProducts" => [
                    [
                        "quantity" => $data["deal_product_quantity"],
                        "price" => $data["deal_product_price"],
                        "finalPrice" => $data["deal_product_final_price"],
                        "initialPrice" => $data["deal_product_initial_price"],
                        "product" => [
                            "id" => $data["product_id"],
                            "name" => $data["product_name"],
                            "active" => $data["product_active"],
                            "price" => $data["product_price"],
                            "createdBy" => [
                                "id" => $data["createdby_id"]
                            ],
                        ]
                    ]
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "X-Moskit-Origin: MOSKIT_API_V2",
                "apikey: {$this->key}"
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

    public function GetDealById($deal_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/deals/{$deal_id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: {$this->key}"
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

    public function CreateNoteToDeal($deal_id, $data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/deals/{$deal_id}/notes",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "description" => $data["description"],
                "user" => [
                    "id" => $data["user_id"]
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "X-Moskit-Origin: MOSKIT_API_V2",
                "apikey: {$this->key}"
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

    public function GetNoteDealById($deal_id, $note_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/deals/{$deal_id}/notes/{$note_id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: {$this->key}"
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

    /*End Section Deal*/

    /*Section Product*/

    public function CreateProduct($data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/products",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode([
                "name" => $data["name"],
                "active" => $data["active"],
                "price" => $data["price"],
                "createdBy" => [
                    "id" => $data["user_id"]
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: {$this->key}"
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

    public function GetProducts()
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/products",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: {$this->key}"
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

    public function UpdateProduct($product_id, $data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/products/{$product_id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "PUT",
            CURLOPT_POSTFIELDS => json_encode([
                "name" => $data["name"],
                "active" => $data["active"],
                "price" => $data["price"],
                "createdBy" => [
                    "id" => $data["user_id"]
                ]
            ]),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: {$this->key}"
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

    public function GetProductById($product_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.moskitcrm.com/v2/products/{$product_id}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "apikey: {$this->key}"
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

    /*End Section Product*/
}
