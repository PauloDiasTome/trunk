<?php

class ReportSms_model extends TA_model
{
    function Get($text, $dt_start, $dt_end, $start, $length)
    {
        $token_id = $this->getID()[0]['id'];

        if ($start == 0) {
            $start = '0';
        }
        $params = ['dt-start' => $dt_start, 'dt-end' => $dt_end, 'start' => $start, 'length' => $length];

        if ($text != '') {
            $params['text'] = $text;
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://sms-talkall.brazilsouth.cloudapp.azure.com/api/v1/report-sms",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HTTPHEADER => array(
                "access_token: {$token_id}",
                "Authorization: Bearer {{token}}"
            ),
        ));

        $data = curl_exec($curl);

        curl_close($curl);

        $res = json_decode($data, TRUE);

        return $res;
    }


    function getID()
    {
        $sql = "select replace(channel.id,'@c.us','') id\n";
        $sql .= " from channel where channel.status = 1 and channel.type = 6";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Export($dt_start, $dt_end)
    {
        $token_id = $this->getID()[0]['id'];

        $params = ['dt-start' => $dt_start, 'dt-end' => $dt_end, 'start' => '0', 'length' => '1000'];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://sms-talkall.brazilsouth.cloudapp.azure.com/api/v1/report-sms",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_HTTPHEADER => array(
                "access_token: {$token_id}",
                "Authorization: Bearer {{token}}"
            ),
        ));

        $data = curl_exec($curl);

        curl_close($curl);

        $res = json_decode($data, TRUE);

        return $res['data'];
    }
}
