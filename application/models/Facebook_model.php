<?php

class Facebook_model extends TA_Model
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
        // URL OFICIAL  //
        // return "https://www.facebook.com/dialog/oauth?client_id=" . $this->app_id . "&redirect_uri=" . $this->base_url . "&scope=pages_messaging%2Cpages_show_list%2Cpages_read_engagement%2Cpages_manage_metadata%2Cpages_manage_posts%2Cinstagram_basic%2Cinstagram_content_publish&state=<STATE>";

        // URL DESENVOLVIMENTO  //
        return "https://www.facebook.com/dialog/oauth?client_id=" . $this->app_id . "&redirect_uri=" . $this->base_url . "&scope=pages_messaging%2Cpages_show_list%2Cpages_read_engagement%2Cpages_manage_metadata%2Cpages_manage_posts%2Cinstagram_basic%2Cinstagram_content_publish%2Cinstagram_manage_messages&state=<STATE>";
    }


    function getPageAccessToken($page_id, $access_token)
    {
        $json = json_decode(file_get_contents("https://graph.facebook.com/" . $page_id . "?fields=access_token&access_token=" . $access_token));
        $instagram = json_decode(file_get_contents("https://graph.facebook.com/" . $page_id . "?fields=instagram_business_account&access_token=" . $json->access_token));

        // instagram->id   => id da pagina do Facebook
        // instagram->instagram_business_account   => id do instagram caso instagram esteja vinculado a pagina do Facebook

        $page_id = $instagram->id;
        $instagram_id = "";

        if (isset($instagram->instagram_business_account)) {

            $instagram_id = $instagram->instagram_business_account;
            $instagram_id = $instagram_id->id;
        }

        $this->setWebhookInPage($page_id, $json->access_token);
        $this->register($page_id, $instagram_id, $json->access_token);
    }


    function register($page_id, $instagram_id, $access_token)
    {
        $inSearch = "";

        if (!empty($instagram_id)) {
            $inSearch = $page_id . "," . $instagram_id;
        } else {
            $inSearch = $page_id;
        }

        $sql = "SELECT * FROM channel WHERE channel.status = 1 AND channel.id IN($inSearch)";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {

            if ($result->result_array(["type"]) == 8) {
                $this->updateRegister($page_id, $access_token);
            }

            if ($result->result_array(["type"]) == 9) {
                $this->updateRegister($instagram_id, $access_token);
            }
        } else {

            $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);

            $sql = "SELECT company.db, channel.id_channel FROM  channel 
            inner join company on channel.id_company = company.id_company
            where  channel.id ='" . $page_id . "' AND channel.type IN(8,9)  and channel.status = 1;";

            $result = $this->talkall_admin->query($sql);
            $channelDB = $result->result_array();

            /// verifica se possui integração para o canal no talkall_adm
            if ($result->num_rows() > 0) {

                //////////// verifica se ésta integrado no banco do mesmo channel
                if ($channelDB[0]['db'] == $this->session->get_userdata()['db']) {

                    $curl = curl_init();

                    $id_to_stop = array("id" => $page_id);
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

                    $this->db->where('id', $page_id);
                    $this->db->update('channel', array('pw' => $this->access_token));

                    $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);

                    $this->talkall_admin->where('id', $page_id);
                    $this->talkall_admin->update('channel', array('pw' => $this->access_token));
                    $this->talkall_admin->update('executed', 1);

                    return "updete";
                } else {

                    return "duplicate";
                }
            } else {

                $this->newRegister($page_id, "Facebook", $access_token, 8);

                if (!empty($instagram_id)) {
                    $this->newRegister($instagram_id, "Instagram", $access_token, 9);
                }

                return "insert";
            }
        }
    }


    function newRegister($id, $name, $access_token, $type)
    {
        $name_channel = "";
        $picture = "";

        if ($name == "Facebook") {
            $name_channel =  $this->getPageName($id, $access_token);
            $picture =  $this->getPagepicture($id, $access_token);
        } else {
            $info = $this->getInstramInfo($id, $access_token);

            $name_channel = $info["name"];
            $picture = $info["picture"];
        }

        $id_work_time = 0;
        $id_user_group = 1;

        $sql = "SELECT channel.id_work_time, channel.id_user_group FROM channel WHERE channel.type IN(2,8,9,12,16) AND channel.status = 1 AND channel.id_user_group != 0 LIMIT 1";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            $id_work_time = $result->result_array()[0]["id_work_time"];
            $id_user_group = $result->result_array()[0]["id_user_group"];
        }

        if ($id_work_time == null) {

            $sql = "SELECT work_time.id_work_time FROM work_time WHERE work_time.status = 1 LIMIT 1";
            $result = $this->db->query($sql);

            $id_work_time = $result->result_array()[0]["id_work_time"];
        }

        if ($id_work_time !== 0) {

            $date = new DateTime();

            $values = [
                'name' => $name_channel,
                'type' => $type,
                'id' => $id,
                'id_user_group' => $id_user_group,
                'id_work_time' => $id_work_time,
                'pw' => $access_token
            ];

            $this->db->insert('channel', $values);

            if ($this->db->affected_rows() > 0) {

                $channel_id = $this->db->insert_id();

                $values = [
                    'id_channel' => $channel_id,
                    'timezone' => '-03:00',
                    'welcome' => '',
                    'office_hours_end' => '',
                    'attendance_enable' => 1,
                    'picture' => $picture,
                ];

                $this->db->insert('config', $values);

                if ($this->db->affected_rows() > 0) {

                    $values = [
                        'type' => $type,
                        'creation' => $date->getTimestamp(),
                        'id_company' => $this->session->userdata('id_company'),
                        'id' => $id,
                        'status' => 1,
                        'pw' => $access_token
                    ];

                    $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
                    $this->talkall_admin->where(array('type' => $type, 'id_company' => $this->session->userdata('id_company'), 'status' => 2));
                    $this->talkall_admin->delete('channel');
                    $this->talkall_admin->insert('channel', $values);
                } else {
                    echo "";
                }
            } else {
                echo "";
            }
        }
    }


    function updateRegister($id, $access_token)
    {
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
        $this->db->update('channel', array('pw' => $access_token, "executed" => 1));

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
        $this->talkall_admin->where(array('id_company' => $this->session->userdata('id_company'), 'id' => $id));
        $this->talkall_admin->update('channel', array('pw' => $access_token));
    }


    function getAccessToken($code)
    {
        $token_url = "https://graph.facebook.com/oauth/access_token?" . "client_id=" . $this->app_id . "&redirect_uri=" . urlencode($this->base_url) . "&client_secret=" . $this->app_secret . "&code=" . $code;

        $response = file_get_contents($token_url);
        $json = json_decode($response);

        $response = file_get_contents("https://graph.facebook.com/v5.0/me/accounts?summary=total_count&access_token=" . $json->access_token);
        $json = json_decode($response);

        foreach ($json->data as $row) {
            $this->getPageAccessToken($row->id, $row->access_token);
        }
    }


    function getPagepicture($id, $access_token)
    {
        $type = "png";

        $data = base64_encode(file_get_contents("https://graph.facebook.com/v2.6/" . $id . "/picture?type=large&width=200&height=200&access_token=" . $access_token));

        $picture = 'data: ' . $type . ';base64,' . $data;

        return $picture;
    }


    function getPageName($id, $access_token)
    {
        $data = json_decode(file_get_contents("https://graph.facebook.com/v2.6/" . $id . "/?access_token=" . $access_token));

        $name = $data->name;

        return  $name;
    }


    function getInstramInfo($id, $access_token)
    {
        $data = json_decode(file_get_contents("https://graph.facebook.com/v3.2/" . $id . "?fields=id%2Cusername%2Cprofile_picture_url%2Cwebsite&access_token=" . $access_token));

        $infoInsta = [
            "name" => $data->username,
            "picture" => $data->profile_picture_url
        ];

        return $infoInsta;
    }


    function getInstraFile($pw)
    {
        $sql = "SELECT * FROM channel WHERE channel.status = 1 AND channel.pw = '$pw'";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {

            $id_channel = $result->result_array()[0]["id_channel"];

            $sql = "SELECT config.picture FROM config WHERE config.id_channel = '$id_channel'";

            $resp = $this->db->query($sql);

            if ($resp->num_rows() > 0) {
                return $resp->result_array()[0]['picture'];
            } else {
                return "";
            }
        }
    }


    function setWebhookInPage($page_id, $access_token)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://graph.facebook.com/$page_id/subscribed_apps?access_token=$access_token&subscribed_fields=messages%2Cfeed",
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
