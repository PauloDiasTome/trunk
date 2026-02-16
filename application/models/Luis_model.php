<?php

class Luis_model extends TA_model
{
    public $Fnc; //QUAL MODEL FUNCAO EXECUTAR
    public $Parameters;  // PARAMETROS DA FUNÇÃO
    public $Erro = ""; // RETORNA O ERRO EM STRING

    private $defaultScore = 0.5; //float

    public function __construct()
    {
        parent::__construct();
    }


    function QuestionResquest($q)
    {
        if ($q == "") {
            $this->Erro = '';
            return;
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://westus.api.cognitive.microsoft.com/luis/v2.0/apps/f02dcd91-cf69-4210-a4a7-bd2a0286a2fe?verbose=false&timezoneOffset=-5&subscription-key=0186dc9706dd40ecaad6e1e64dc6b8ac&q={$q}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Host: westus.api.cognitive.microsoft.com",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            //REGISTRAR ESSE LOG NO BANCO
            $this->Erro = 'Erro Azure Service!';
        } else {

            $json = json_decode($response);
            //var_dump($json);
            return $this->DescisionTree($json);;
        }
    }


    private function DescisionTree($data)
    {
        if (isset($data->topScoringIntent)) {
            if ($data->topScoringIntent->intent != 'None' && $data->topScoringIntent->score > $this->defaultScore) {
                $this->Parameters = $this->Entities($data->entities);
                //var_dump($this->Parameters);
                $this->Fnc = $data->topScoringIntent->intent;
            } else {
                $this->Erro = 'Intenção não identificada, Por favor tente fazer uma pesquisa utilizando termos diferente.';
            }
        } else {
            $this->Erro = 'Intenção não identificada, Por favor tente fazer uma pesquisa utilizando termos diferente.';
        }
    }


    private function Entities($entities)
    {
        $myEntities = array();
        $dateInt = 0;
        $numInt = 0;
        $nameInt = 0;
        $sectorInt = 0;
        foreach ($entities as $entitie) {
            if ($entitie->type == 'Communication.ContactName' && $entitie->score > $this->defaultScore) {
                $nameInt = $nameInt + 1;
                $myEntities['name_' . $nameInt] = $entitie->entity;
            }
            if ($entitie->type == 'builtin.datetimeV2.date') {
                $dateInt = $dateInt + 1;
                $myEntities['date_' . $dateInt] = $entitie->resolution->values[0]->value;
            }
            if ($entitie->type == 'builtin.number') {
                if (strlen($entitie->resolution->value) > 7) {
                    $numInt = $numInt + 1;
                    $myEntities['number_' . $numInt] = $entitie->resolution->value;
                }
            }
            if ($entitie->type == 'builtin.datetimeV2.daterange') {
                $dateInt = $dateInt + 1;
                //var_dump($entitie);

                if (isset($entitie->resolution)) {

                    $data1 = DateTime::createFromFormat('Y-m-d', $entitie->resolution->values[0]->start);
                    $data2 = DateTime::createFromFormat('Y-m-d', $entitie->resolution->values[0]->end);
                    if ($data1 > $data2) {
                        $myEntities['date_range_' . $dateInt] = array('start' => $entitie->resolution->values[0]->end, 'end' => $entitie->resolution->values[0]->start);
                    } else {
                        $myEntities['date_range_' . $dateInt] = array('start' => $entitie->resolution->values[0]->start, 'end' => $entitie->resolution->values[0]->end);
                    }
                }
            }
            if ($entitie->type == 'Sector' && $entitie->score > $this->defaultScore) {
                $sectorInt = $sectorInt + 1;
                $myEntities['sector_' . $sectorInt] = $entitie->entity;
            }
        }
        return $myEntities;
    }


    // FAZER UM SERVIÇO PARA ESSA FUNÇÃO 
    public function UpdateAllUserName()
    {

        $sql =  "select name , last_name from user";

        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            $names = $result->result_array();

            foreach ($names as $row) {
                echo $this->AddUserName($row['name']);
                sleep(1);
                echo $this->AddUserName($row['last_name']);
            }

            echo $this->Train();
        }
    }


    private function AddUserName($name)
    {
        $json = array(
            'text' => $name,
            'intentName' => 'None',
            'entityLabels' =>
            array(
                0 =>
                array(
                    'entityName' => 'Communication.ContactName',
                    'startCharIndex' => 0,
                    'endCharIndex' => strlen($name),
                ),
            ),
        );


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://westus.api.cognitive.microsoft.com/luis/api/v2.0/apps/f02dcd91-cf69-4210-a4a7-bd2a0286a2fe/versions/0.1/example",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($json),
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Content-Type: application/json",
                "Host: westus.api.cognitive.microsoft.com",
                "Ocp-Apim-Subscription-Key: 0186dc9706dd40ecaad6e1e64dc6b8ac",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new Exception($err);
        } else {
            return $response;
        }
    }


    // FAZER UM SERVIÇO PARA ESSA FUNÇÃO 
    public function UpdateAllSectorName()
    {

        $sql =  "select name from user_group";

        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            $names = $result->result_array();

            foreach ($names as $row) {
                echo $this->AddSectorName($row['name']);
            }

            echo $this->Train();
        }
    }


    private function AddSectorName($name)
    {
        $json = array(
            'text' => $name,
            'intentName' => 'None',
            'entityLabels' =>
            array(
                0 =>
                array(
                    'entityName' => 'Sector',
                    'startCharIndex' => 0,
                    'endCharIndex' => strlen($name),
                ),
            ),
        );


        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://westus.api.cognitive.microsoft.com/luis/api/v2.0/apps/f02dcd91-cf69-4210-a4a7-bd2a0286a2fe/versions/0.1/example",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($json),
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Content-Type: application/json",
                "Host: westus.api.cognitive.microsoft.com",
                "Ocp-Apim-Subscription-Key: 0186dc9706dd40ecaad6e1e64dc6b8ac",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new Exception($err);
        } else {
            return $response;
        }
    }


    //      Treina o bot
    public function Train()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://westus.api.cognitive.microsoft.com/luis/api/v2.0/apps/f02dcd91-cf69-4210-a4a7-bd2a0286a2fe/versions/0.1/train?=",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_HTTPHEADER => array(
                "Accept: */*",
                "Accept-Encoding: gzip, deflate",
                "Cache-Control: no-cache",
                "Connection: keep-alive",
                "Content-Length: 0",
                "Host: westus.api.cognitive.microsoft.com",
                "Ocp-Apim-Subscription-Key: 0186dc9706dd40ecaad6e1e64dc6b8ac",
                "Postman-Token: 0cc2cf5b-2a32-4c10-ad65-1d50161f2daa,3b0aaf21-3795-4f31-bf9a-91f2d6ecd59b",
                "User-Agent: PostmanRuntime/7.17.1",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            throw new Exception($err);
        } else {
            return $response;
        }
    }
}
