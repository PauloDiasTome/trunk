<?php

class Business_model extends TA_model
{
    public function __construct()
    {
        parent::__construct();
    }

    function GetAccessToken($code)
    {
        $app_id = $this->config->config['waba_app_id'];
        $app_secret = $this->config->config['waba_app_secret'];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://graph.facebook.com/v22.0/oauth/access_token?client_id=549279743372390&client_secret=21a9210c2246bebda964284f04a949b0&code=$code",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $access_token = json_decode($response, true);

        return $access_token['access_token'];
    }

    public function GetPhoneNumbers($access_token, $waba_id)
    {
        $curl = curl_init();

        $url = "https://graph.facebook.com/v22.0/" . $waba_id . "/phone_numbers";

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer " . $access_token,
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);
    }

    function GetWabaInfo($pw, $id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://graph.facebook.com/v18.0/$id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $pw",
                'Cookie: ps_l=0; ps_n=0'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);
    }

    function GetIdChannel($id)
    {
        $this->db->select('id_channel');
        $this->db->where('id', $id);
        $this->db->where('status', 1);
        $query = $this->db->get('channel');

        if ($query->num_rows() > 0) {
            $row = $query->row_array();
            return $row['id_channel'];
        }
    }

    function Add($data)
    {
        $verify_integration = $this->VerifyIntegration($data["id"]);
        // $sign_webhook = $this->SignWebhook($data);


        // if (!$sign_webhook)
        //     return ["errors" => ["code" => "TA-005"]];

        switch ($verify_integration) {
            case "Insert":
                return $this->Insert($data);
            case "Update":
                return $this->Update($data);
            case "Duplicate":
                return ["errors" => ["code" => "TA-004"]];
            default:
                break;
        }
    }

    function stopService($data)
    {
        // $curl = curl_init();

        // $id_to_stop = json_encode(array("id" => $data['id']));

        // curl_setopt_array($curl, array(
        //     CURLOPT_URL => 'http://192.168.190.45:3044/stopservice',
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => '',
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => 'POST',
        //     CURLOPT_POSTFIELDS => $id_to_stop,
        //     CURLOPT_HTTPHEADER => array(
        //         "Content-Type: application/json"
        //     ),
        // ));

        // $response = curl_exec($curl);
        // curl_close($curl);
    }

    function SignWebhook($data)
    {
        $waba_id = $data['waba_id'];
        $pw = $data['access_token'];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://graph.facebook.com/v22.0/$waba_id/subscribed_apps",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer $pw",
                'Cookie: ps_l=0; ps_n=0'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);
    }

    function VerifyIntegration($id)
    {
        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);

        $this->talkall_admin->select('company.db, channel.id_channel');
        $this->talkall_admin->from('channel');
        $this->talkall_admin->join('company', 'channel.id_company = company.id_company', 'inner');
        $this->talkall_admin->where('channel.id', $id);
        $this->talkall_admin->where('channel.status', 1);

        $query = $this->talkall_admin->get();
        $result = $query->result_array();

        if ($query->num_rows() > 0) {
            if ($result[0]['db'] == $this->session->get_userdata()['db'])
                return "Update";
            else
                return "Duplicate";
        }

        return "Insert";
    }

    function Insert($data)
    {
        $date = new DateTime();
        $this->db->trans_start();

        $values = [
            'name' => "WhatsApp Business API - " . $data['verified_name'],
            'type' => 12,
            'id' => $data['id'],
            'whatsapp_business_messaging' => $data['waba_id'],
            'id_user_group' => 1,
            'id_work_time' => 1,
            'pw' =>  $data['access_token'],
            'display_phone_number' => $data['display_phone_number']
        ];

        $this->db->insert('channel', $values);

        $channel_id = $this->db->insert_id();

        $values = [
            'id_channel' => $channel_id,
            'timezone' => '-03:00',
            'welcome' => '',
            'office_hours_end' => '',
            'attendance_enable' => 1,
        ];

        $this->db->insert('config', $values);

        $values = [
            'type' => 12,
            'creation' => $date->getTimestamp(),
            'id_company' => $this->session->userdata('id_company'),
            'whatsapp_business_messaging' => $data['waba_id'],
            'id' => $data['id'],
            'status' => 1,
            'pw' =>  $data['access_token'],
            'display_phone_number' => $data['display_phone_number']
        ];

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
        $this->talkall_admin->trans_start();
        $this->talkall_admin->insert('channel', $values);

        if ($this->db->trans_status() === FALSE || $this->talkall_admin->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->talkall_admin->trans_rollback();
            return ["errors" => ["code" => "TA-003"]];
        } else {
            $this->db->trans_complete();
            $this->talkall_admin->trans_complete();
            return ["success" => ["status" => true]];
        }
    }

    function Update($data)
    {
        $this->db->trans_start();
        $this->db->where('id', $data['id']);
        $this->db->where('status', 1);
        $this->db->update('channel', array('pw' => $data['access_token']));

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
        $this->talkall_admin->trans_start();
        $this->talkall_admin->where('id', $data['id']);
        $this->talkall_admin->where('status', 1);
        $this->talkall_admin->set('pw', $data['access_token']);
        $this->talkall_admin->set('executed', 1);
        $this->talkall_admin->update('channel');

        if ($this->db->trans_status() === FALSE || $this->talkall_admin->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->talkall_admin->trans_rollback();
            return ["errors" => ["code" => "TA-003"]];
        } else {
            $this->db->trans_complete();
            $this->talkall_admin->trans_complete();
            // $this->stopService($data);
            return ["success" => ["status" => true]];
        }
    }
}
