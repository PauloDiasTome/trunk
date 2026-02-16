<?php

class Telegram_model extends TA_model
{
    private $bot_token;
    private $api_url;
    private $webhook_url = "";
    private $bot_id;
    private $token;

    public function __construct()
    {
        parent::__construct();
    }

    public function init($bot_token)
    {
        $this->bot_token = $bot_token;
        $this->bot_id = explode(":", $bot_token)[0];
        $this->token = explode(":", $bot_token)[1];
        $this->api_url = "https://api.telegram.org/bot" . $bot_token . "/";
        $this->webhook_url = "https://services.talkall.com.br/webhook/telegram?bot=" . $this->bot_id;
    }

    public function OAuth()
    {
        $set_webhook = $this->apiRequest('setWebhook', array('url' => $this->webhook_url));

        if ($set_webhook == false) {
            return ["response" => 'TA-042'];
        }

        $resp = $this->register();
        return $resp;
    }

    function register()
    {
        $date = new DateTime();

        $this->db->trans_start();

        $channel_exist = $this->getChannel();

        if ($channel_exist) {
            $this->db->trans_rollback();
            redirect(base_url("config/edit/" . $channel_exist['id_channel']), "refresh");
        }

        $values = [
            'name' => "Telegram",
            'type' => 10,
            'id' => $this->bot_id,
            'pw' => $this->token,
        ];

        $this->db->insert('channel', $values);

        $channel_id = $this->db->insert_id();

        $sql = "select\n";
        $sql .= "channel.id_channel\n";
        $sql .= "from channel\n";
        $sql .= "where channel.type = 10 and channel.status = 2\n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            foreach ($result->result_array() as $row) {
                $this->db->where('id_channel', $row['id_channel']);
                $this->db->update('contact', array('deleted' => 2));
            }
        }

        $values = [
            'id_channel' => $channel_id,
            'timezone' => '-03:00',
            'attendance_enable' => 1,
        ];

        $this->db->insert('config', $values);

        $channel_is_talkall_admin = $this->getChannel('talkall_admin');

        $this->talkall_admin->trans_start();

        if ($channel_is_talkall_admin) {
            $this->talkall_admin->where(array('type' => 10, 'id' => $this->bot_id));
            $this->talkall_admin->update('channel', array('status' => 2));
        }


        $values = [
            'type' => 10,
            'creation' => $date->getTimestamp(),
            'id_company' => $this->session->userdata('id_company'),
            'id' => $this->bot_id,
            'pw' => $this->token,
            'executed' => 2,
            'status' => 1,
        ];

        $this->talkall_admin->where(array('type' => 10, 'id_company' => $this->session->userdata('id_company')));
        $this->talkall_admin->insert('channel', $values);

        if ($this->db->trans_status() === FALSE || $this->talkall_admin->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->talkall_admin->trans_rollback();
            return ["response" => 'TA-041'];
        } else {
            $this->db->trans_complete();
            $this->talkall_admin->trans_complete();
            return ["response" => 'success'];
        }
    }


    public function exec_curl_request($handle)
    {
        $response = curl_exec($handle);
        $response = json_decode($response, true);

        if ($response['ok'] == false || isset($response['error_code'])) {
            $errno = curl_errno($handle);
            $error = curl_error($handle);
            error_log("Curl returned error $errno: $error\n");
            curl_close($handle);
            return false;
        }

        return true;
    }


    public function apiRequest($method, $parameters)
    {
        if (!is_string($method)) {
            error_log("Method name must be a string\n");
            return false;
        }

        if (!$parameters) {
            $parameters = array();
        } else if (!is_array($parameters)) {
            error_log("Parameters must be an array\n");
            return false;
        }

        foreach ($parameters as $key => &$val) {
            // encoding to JSON array parameters, for example reply_markup
            if (!is_numeric($val) && !is_string($val)) {
                $val = json_encode($val);
            }
        }
        $url = $this->api_url . $method . '?' . http_build_query($parameters);

        $handle = curl_init($url);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($handle, CURLOPT_TIMEOUT, 60);

        $resp = $this->exec_curl_request($handle);
        return $resp;
    }

    function getChannel($database = false)
    {
        $query = [];

        if ($database) {
            $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
            $this->talkall_admin->select('id_channel');
            $this->talkall_admin->from('channel');
            $this->talkall_admin->where('type', 10);
            $this->talkall_admin->where('id', $this->bot_id);
            $this->talkall_admin->where('status', 1);

            $query = $this->talkall_admin->get()->result_array()[0] ?? [];
        } else {
            $this->db->select('id_channel');
            $this->db->from('channel');
            $this->db->where('type', 10);
            $this->db->where('id', $this->bot_id);
            $this->db->where('status', 1);

            $query = $this->db->get()->result_array()[0] ?? [];
        }

        if ($query) {
            return $query;
        } else {
            return false;
        }
    }
}
