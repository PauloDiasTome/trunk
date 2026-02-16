<?php

class Cloud_model extends TA_model
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
            CURLOPT_URL => "https://graph.facebook.com/v18.0/oauth/access_token?client_id=$app_id&client_secret=$app_secret&code=$code",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Cookie: ps_l=0; ps_n=0'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $access_token = json_decode($response, true);

        return $access_token['access_token'];
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

    function Add($pw, $id, $waba_id, $pin_code)
    {
        $verify_integration = $this->VerifyIntegration($id);

        if ($verify_integration == "Duplicate")
            return ["errors" => ["code" => "TA-003"]];

        $sign_webhook = $this->SignWebhook($pw, $waba_id);
        if (!$sign_webhook)
            return ["errors" => ["code" => "TA-004"]];

        $register_phone = $this->RegisterPhoneNumber($pw, $id, $pin_code);
        if (!$register_phone)
            return ["errors" => ["code" => "TA-005"]];

        switch ($verify_integration) {
            case "Insert":
                return $this->Insert($pw, $id, $waba_id, $pin_code);
            case "Update":

                $id_channel = $this->GetIdChannel($id);
                return $this->Update($pw, $id, $id_channel, $pin_code);
        }
    }


    function SignWebhook($pw, $waba_id)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://graph.facebook.com/v18.0/$waba_id/subscribed_apps",
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

    function RegisterPhoneNumber($pw, $id, $pin_code)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://graph.facebook.com/v18.0/$id/register?messaging_product=whatsapp&pin=$pin_code",
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
        $this->talkall_admin->where('channel.type', 16);
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

    function Insert($pw, $id, $waba_id, $pin_code)
    {
        $date = new DateTime();
        $waba_info = $this->GetWabaInfo($pw, $id);
        $display_phone_number = isset($waba_info->display_phone_number) ? str_replace(["+", "-", " ", "(", ")"], '', $waba_info->display_phone_number) : "";

        $this->db->trans_start();

        $values = [
            'name' => "WhatsApp Cloud API - $waba_info->verified_name",
            'type' => 16,
            'id' => $id,
            'whatsapp_business_messaging' => $waba_id,
            'id_user_group' => 1,
            'id_work_time' => 1,
            'pw' => $pw,
            'display_phone_number' => $display_phone_number
        ];

        $this->db->insert('channel', $values);

        $channel_id = $this->db->insert_id();

        $values = [
            'id_channel' => $channel_id,
            'timezone' => '-03:00',
            'welcome' => '',
            'namespace' => $waba_info->message_template_namespace,
            'template_namespace' => $waba_info->message_template_namespace,
            'office_hours_end' => '',
            'attendance_enable' => 1,
            'pin' => $pin_code,
        ];

        $this->db->insert('config', $values);

        $values = [
            'type' => 16,
            'creation' => $date->getTimestamp(),
            'id_company' => $this->session->userdata('id_company'),
            'whatsapp_business_messaging' => $waba_id,
            'id' => $id,
            'status' => 1,
            'pw' => $pw,
            'display_phone_number' => $display_phone_number
        ];

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
        $this->talkall_admin->trans_start();
        $this->talkall_admin->where(array('type' => 16, 'id_company' => $this->session->userdata('id_company')));
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

    function Update($pw, $id, $id_channel, $pin_code)
    {
        $curl = curl_init();

        $id_to_stop = json_encode(array("id" => $id));

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://192.168.190.45:3044/stopservice',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $id_to_stop,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        $this->db->trans_start();
        $this->db->where('id', $id);
        $this->db->where('status', 1);
        $this->db->update('channel', array('pw' => $pw));
        
        $this->db->where('id_channel', $id_channel);
        $this->db->update('config', array('pin' => $pin_code));

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
        $this->talkall_admin->trans_start();
        $this->talkall_admin->where('id', $id);
        $this->talkall_admin->where('status', 1);
        $this->talkall_admin->set('pw', $pw);
        $this->talkall_admin->set('executed', 1);
        $this->talkall_admin->update('channel');

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
}
