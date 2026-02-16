<?php

class Instagram_model extends TA_model
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
        return "https://www.facebook.com/dialog/oauth?client_id=" . $this->app_id . "&redirect_uri=" . $this->base_url . "&scope=instagram_manage_comments%2Cinstagram_basic&state=<STATE>";
    }


    function getBearerToken($acess_token)
    {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://graph.instagram.com/access_token?grant_type=ig_exchange_token&client_secret=43ee1da71768a6e2eb4bb82e3a4b8b52&access_token=" . $acess_token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = json_decode(curl_exec($curl));

        echo $response->access_token;

        curl_close($curl);
    }


    function register($page_id, $access_token)
    {
        $date = new DateTime();

        $values = [
            'name' => 'Instagram',
            'type' => 9,
            'id' => $page_id,
            'id_user_group' => 1,
            'id_work_time' => 1,
            'pw' => $access_token
        ];

        $this->db->where('type', 9);
        $this->db->update('channel', array('status' => 2));

        $this->db->insert('channel', $values);

        $channel_id = $this->db->insert_id();

        $values = [
            'id_channel' => $channel_id,
            'timezone' => '-00:00',
            'welcome' => '',
            'office_hours_end' => '',
            'attendance_enable' => 1,
        ];

        $this->db->insert('config', $values);

        $values = [
            'type' => 9,
            'creation' => $date->getTimestamp(),
            'id_company' => $this->session->userdata('id_company'),
            'id' => $page_id,
            'status' => 1,
            'pw' => $access_token
        ];

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
        $this->talkall_admin->where(array('type' => 9, 'id_company' => $this->session->userdata('id_company')));
        $this->talkall_admin->delete('channel');
        $this->talkall_admin->insert('channel', $values);
    }


    function getPageAccessToken($page_id)
    {
        $json = json_decode(file_get_contents("https://graph.facebook.com/" . $page_id . "?fields=access_token&access_token=" . $this->access_token));
        $this->register($page_id, $json->access_token);
    }


    function getAccessToken($code)
    {
        $token_url = "https://graph.facebook.com/oauth/access_token?"
            . "client_id=" . $this->app_id . "&redirect_uri=" . urlencode($this->base_url)
            . "&client_secret=" . $this->app_secret . "&code=" . $code;

        $response = file_get_contents($token_url);
        $json = json_decode($response);

        $response = file_get_contents("https://graph.facebook.com/v5.0/me/accounts?access_token=" . $json->access_token);
        $json = json_decode($response);

        $this->access_token = $json->data[0]->access_token;

        foreach ($json->data as $row) {
            $this->getPageAccessToken($row->id);
        }
    }


    function setWebhookInPage($page_id, $access_token)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://graph.facebook.com/$page_id/subscribed_apps?access_token=$access_token&subscribed_fields=messages",
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
                "Host: graph.facebook.com",
                "Postman-Token: b7aedf3a-16cc-4b9c-a3f2-b2eda229abf7,4d110c75-6aac-4b90-b3ce-314cc9b706ec",
                "User-Agent: PostmanRuntime/7.19.0",
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        }
    }


    function getAccessTokenInstagram($code)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.instagram.com/oauth/access_token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array('client_id' => $this->app_id, 'client_secret' => '43ee1da71768a6e2eb4bb82e3a4b8b52', 'redirect_uri' => $this->base_url, 'code' => $code, 'grant_type' => 'authorization_code'),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: multipart/form-data; boundary=--------------------------200051892323348779997692"
            ),
        ));

        $response = json_decode(curl_exec($curl));
        curl_close($curl);

        if (isset($response->access_token)) {
            $this->getBearerToken($response->access_token);
        }
    }
}
