<?php

class WABA_model extends TA_model
{
    private $app_id;
    private $base_url;
    private $app_secret;
    private $access_token;

    public function __construct()
    {
        parent::__construct();
    }

    public function init($app_id, $app_secret, $base_url)
    {
        $this->app_id = $app_id;
        $this->base_url = $base_url;
        $this->app_secret = $app_secret;
    }

    function OAuth()
    {
        //return "https://api.instagram.com/oauth/authorize?client_id=".$this->app_id."&redirect_uri=" . $this->base_url . "&scope=user_profile,user_media%2Cuser_profile&response_type=code&hl=pt";
        return "https://www.facebook.com/dialog/oauth?client_id=" . $this->app_id . "&redirect_uri=" . $this->base_url . "&scope=business_management, whatsapp_business_management, whatsapp_business_messaging&state=<STATE>";
    }


    function getBearerToken($acess_token)
    {
        echo $acess_token;
    }


    function register($waba_id, $info)
    {
        $id = $this->getNumberID($waba_id);

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);

        $sql = "SELECT company.db, channel.id_channel FROM  channel 
        inner join company on channel.id_company = company.id_company
        where  channel.id ='" . $id . "' AND channel.type = 16 and channel.status = 1;";

        $result = $this->talkall_admin->query($sql);
        $channelDB = $result->result_array();

        /// verifica se possui integração para o canal no talkall_adm
        if ($result->num_rows() > 0) {

            //////////// verifica se ésta integrado no banco do mesmo channel
            if ($channelDB[0]['db'] == $this->session->get_userdata()['db']) {

                $curl = curl_init();

                $id_to_stop = array("id" => $id);
                $body = json_encode($id_to_stop);

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'http://192.168.190.45:3044/stopservice',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => $body,
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/json"
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);

                $this->db->where('id', $id);
                $this->db->update('channel', array('pw' => $this->access_token));

                $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);

                $this->talkall_admin->where('id', $id);
                $this->talkall_admin->set('pw', $this->access_token);
                $this->talkall_admin->set('executed', 1);
                $this->talkall_admin->update('channel');

                return "updete";
            } else {

                return "duplicate";
            }
        } else {

            ///// inseri novos registros
            $this->newRegisterIntegration($waba_id, $id, $info);

            return "insert";
        }
    }


    function newRegisterIntegration($waba_id, $id, $info)
    {

        $date = new DateTime();

        $phone = $this->getWabaInfo($id);
        $display_phone_number = isset($phone->display_phone_number) ? str_replace("+", "", str_replace("-", "", str_replace(" ", "", $phone->display_phone_number))) : "";

        $values = [
            'name' => 'WhatsApp Cloud API',
            'type' => 16,
            'id' => $id,
            'whatsapp_business_messaging' => $waba_id,
            'id_user_group' => 1,
            'id_work_time' => 1,
            'pw' => $this->access_token,
            'display_phone_number' => $display_phone_number
        ];

        $this->db->insert('channel', $values);

        $channel_id = $this->db->insert_id();

        $values = [
            'id_channel' => $channel_id,
            'timezone' => '-03:00',
            'welcome' => '',
            'namespace' => $info->message_template_namespace,
            'template_namespace' => $info->message_template_namespace,
            'office_hours_end' => '',
            'attendance_enable' => 1,
        ];

        $this->db->insert('config', $values);

        $values = [
            'type' => 16,
            'creation' => $date->getTimestamp(),
            'id_company' => $this->session->userdata('id_company'),
            'whatsapp_business_messaging' => $waba_id,
            'id' => $id,
            'status' => 1,
            'pw' => $this->access_token,
            'display_phone_number' => $display_phone_number
        ];

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
        $this->talkall_admin->where(array('type' => 16, 'id_company' => $this->session->userdata('id_company')));
        $this->talkall_admin->insert('channel', $values);
    }



    function getPageAccessToken($waba_id)
    {
        $json = json_decode(file_get_contents("https://graph.facebook.com/" . $waba_id . "?fields=access_token&access_token=" . $this->access_token));
        $this->register($waba_id, $json->access_token);
    }


    function getWabaInfo($waba_id)
    {
        $get = "https://graph.facebook.com/v13.0/$waba_id?access_token=" . $this->access_token;
        $response = file_get_contents($get);

        $json = json_decode($response);

        return $json;
    }


    function getNumberID($waba_id)
    {
        $get = "https://graph.facebook.com/v13.0/$waba_id/phone_numbers?access_token=" . $this->access_token;
        $response = file_get_contents($get);
        $json = json_decode($response);

        return $json->data[0]->id;
    }


    function getAccessToken($code)
    {
        $token_url = "https://graph.facebook.com/oauth/access_token?"
            . "client_id=" . $this->app_id . "&redirect_uri=" . urlencode($this->base_url)
            . "&client_secret=" . $this->app_secret . "&code=" . $code;

        $response = file_get_contents($token_url);
        $json = json_decode($response);

        $this->access_token = $json->access_token;

        $response = file_get_contents("https://graph.facebook.com/v13.0/debug_token?input_token=" . $this->access_token . "&access_token=549279743372390|a0Xn14Ik-wLgPYk-21yRq6SAXN8");

        $json = json_decode($response);

        $cont = 0;

        foreach ($json->data->granular_scopes as $row) {

            foreach ($row->target_ids as $waba) {

                if ($cont > 0) {

                    $info = $this->getWabaInfo($waba);

                    $response = $this->register($waba, $info);
                }
                $cont += 1;
            }
        }

        return $response;
    }


    function setWebhookInPage($waba_id, $access_token)
    {
    }
}
