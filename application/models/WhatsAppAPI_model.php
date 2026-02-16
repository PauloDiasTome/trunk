<?php

class WhatsAppAPI_model extends TA_Model
{
    private $app_id;
    private $app_secret;
    private $base_url;
    private $access_token;

    public function __construct()
    {
        parent::__construct();
    }

    public function init($app_id, $app_secret, $base_url)
    {
        $this->app_id = $app_id;
        $this->app_secret = $app_secret;
        $this->base_url = $base_url;
    }


    function OAuth()
    {
        return "https://www.facebook.com/dialog/oauth?client_id=" . $this->app_id . "&redirect_uri=" . $this->base_url . "&scope=business_management%2Cwhatsapp_business_management&state=<STATE>";
    }


    function getPageAccessToken($page_id)
    {
        $json = json_decode(file_get_contents("https://graph.facebook.com/" . $page_id . "?fields=access_token&access_token=" . $this->access_token));
        $this->setWebhookInPage($page_id, $json->access_token);
        $this->register($page_id, $json->access_token);
    }


    function register($page_id, $access_token)
    {
        $date = new DateTime();

        $values = [
            'name' => 'Facebook',
            'type' => 8,
            'id' => $page_id,
            'id_user_group' => 1,
            'id_work_time' => 1,
            'pw' => $access_token
        ];

        $this->db->where('type', 8);
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
            'type' => 8,
            'creation' => $date->getTimestamp(),
            'id_company' => $this->session->userdata('id_company'),
            'id' => $page_id,
            'status' => 1,
            'pw' => $access_token
        ];

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
        $this->talkall_admin->where(array('type' => 8, 'id_company' => $this->session->userdata('id_company')));
        $this->talkall_admin->delete('channel');
        $this->talkall_admin->insert('channel', $values);
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


    function delWebhookInPage($page_id, $access_token)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://graph.facebook.com/$page_id/subscribed_apps?access_token=$access_token&subscribed_fields=messages%252Cfeed",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        }
    }


    function PostText($page_id, $text)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://graph.facebook.com/" . $page_id . "/photos",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array('message' => $text, 'access_token' => $this->access_token),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: multipart/form-data; boundary=--------------------------266970326762631795623551"
            ),
        ));
        curl_exec($curl);
        curl_close($curl);
    }


    function PostImage($page_id, $media_url, $legend)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://graph.facebook.com/$page_id/feed",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array('message' => $legend, 'access_token' => $this->access_token, 'source' => $media_url),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: multipart/form-data; boundary=--------------------------545116393555480053924279"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }


    function PostVideo($page_id, $media_url, $legend)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://graph.facebook.com/$page_id/feed",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array('message' => $legend, 'access_token' => $this->access_token, 'source' => $media_url),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: multipart/form-data; boundary=--------------------------545116393555480053924279"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
    }
}
