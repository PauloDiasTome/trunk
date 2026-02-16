<?php

class PublicationFacebookPost_model extends TA_model
{

    public function __construct()
    {
        parent::__construct();
        $this->SetTableName("broadcast_schedule");
        $this->SetPrimaryKey("id_broadcast_schedule");
    }

    function Get($param)
    {
        $this->db->select("
            REPLACE(channel.id, '@c.us', '') AS channel,
            DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR), '%d/%m/%Y %T') AS schedule,
            DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.creation), INTERVAL config.timezone HOUR), '%d/%m/%Y %T') AS creation,
            CASE WHEN broadcast_schedule.expire = '0' THEN '' ELSE DATE_FORMAT(FROM_UNIXTIME(broadcast_schedule.expire), '%d/%m/%Y %H:%i') END AS expire,
            broadcast_schedule.id_channel AS id_channel,
            broadcast_schedule.status AS status,
            broadcast_schedule.token AS token,
            broadcast_schedule.title AS title,
            broadcast_schedule.data AS data,
            CONCAT(channel.name, ' (', REPLACE(channel.id, '@c.us', ''), ')') AS name
        ");

        $this->db->from('broadcast_schedule');
        $this->db->join('channel', 'broadcast_schedule.id_channel = channel.id_channel');
        $this->db->join('config', 'channel.id_channel = config.id_channel');

        $this->db->where('broadcast_schedule.is_fb_publication', 1);
        $this->db->where('broadcast_schedule.status !=', 7);
        $this->db->like("LOWER(CONCAT(broadcast_schedule.title, ' ', channel.id))", $this->db->escape_like_str(strtolower($param['text'])));

        if (!empty($param['channel'])) {
            $this->db->where_in("broadcast_schedule.id_channel", $param['channel']);
        }

        if (!empty($param['status'])) {
            switch ($param['status']) {
                case 1:
                    $this->db->where("(broadcast_schedule.status = " . $param['status'] . " OR broadcast_schedule.status = 6)");
                    break;

                default:
                    $this->db->where('broadcast_schedule.status', $param['status']);
                    break;
            }
        }

        if (!empty($param['dt_start'])) {
            $this->db->where("DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.schedule), INTERVAL config.timezone HOUR),'%Y-%m-%d') BETWEEN '" . $param['dt_start'] . "' AND '" . $param['dt_end'] . "'", NULL, FALSE);
        }

        $this->db->group_by('channel, schedule, creation, id_channel, creation, data, title, token, expire, channel, config.timezone, broadcast_schedule.status');

        $count = $this->db->count_all_results('', false);

        switch ($param['order'][0]['column']) {
            case 1:
                $this->db->order_by('broadcast_schedule.schedule', $param['order'][0]['dir']);
                break;

            case 2:
                $this->db->order_by('channel.name', $param['order'][0]['dir']);
                break;

            case 3:
                $this->db->order_by('broadcast_schedule.title', $param['order'][0]['dir']);
                break;
        }

        $this->db->limit($param['length'], $param['start']);

        return ['query' => $this->db->get()->result_array(), 'count' => $count];
    }

    function Add($data)
    {
        $date = new DateTime();
        $array_status = [];

        foreach ($data["others"] as $row) {
            $token = Token();
            if (isset($data['files'])) {
                foreach ($data["files"] as $key => $value) {
                    $values = [
                        'creation' => $date->getTimestamp(),
                        'id_channel' => $row,
                        'title' => trim($data['input_title']),
                        'token' => $token,
                        'schedule' => $this->checkScheduleHour($date->getTimestamp(), $this->getTimezoneChannel($data['date_start'], $data['time_start'], $row)),
                        'media_type' => !isset($data['media_type']) ? 1 : $data['media_type'],
                        'media_caption' => !isset($data['media_caption']) ? "" : $data['media_caption'],
                        'media_size' => !isset($data['media_size']) ? 0 : $data['media_size'],
                        'media_duration' => !isset($data['media_duration']) ? 0 : $data['media_duration'],
                        'media_url' => $data['files'][$key],
                        'data' => trim($data['input_data']),
                        'groups' => "",
                        'is_wa_status' => 2,
                        'is_wa_broadcast' => 2,
                        'is_fb_publication' => 1,
                        'status' => 3,
                        'submitted_approval' => 2,
                        'status_approval' => 1
                    ];

                    $return = $this->db->insert('broadcast_schedule', $values);
                    array_push($array_status, $return);
                }
            } else {
                $values = [
                    'creation' => $date->getTimestamp(),
                    'id_channel' => $row,
                    'title' => trim($data['input_title']),
                    'token' => $token,
                    'schedule' => $this->checkScheduleHour($date->getTimestamp(), $this->getTimezoneChannel($data['date_start'], $data['time_start'], $row)),
                    'media_type' => !isset($data['media_type']) ? 1 : $data['media_type'],
                    'media_caption' => !isset($data['media_caption']) ? "" : $data['media_caption'],
                    'media_size' => !isset($data['media_size']) ? 0 : $data['media_size'],
                    'media_duration' => !isset($data['media_duration']) ? 0 : $data['media_duration'],
                    'data' => trim($data['input_data']),
                    'groups' => "",
                    'is_wa_status' => 2,
                    'is_wa_broadcast' => 2,
                    'is_fb_publication' => 1,
                    'status' => 3,
                    'submitted_approval' => 2,
                    'status_approval' => 1
                ];

                $return = $this->db->insert('broadcast_schedule', $values);
                array_push($array_status, $return);
            }
        }

        $status = true;

        if (in_array(false, $array_status)) {
            $status = false;
        };

        $channels_names = $this->getChannelName($data["others"]);

        $ret = [
            "channels_names" => $channels_names,
            "status" => $status
        ];

        return $ret;
    }

    function checkScheduleHour($current_time, $schedule)
    {
        if ($current_time > $schedule) {
            return $current_time;
        } else {
            return $schedule;
        }
    }

    function getChannelName($channels_ids)
    {
        $channels_ids = implode(",", $channels_ids);

        $this->db->select('name');

        $this->db->from('channel');
        $this->db->where_in('id_channel', explode(',', $channels_ids));

        $result = $this->db->get();
        $channels_names = $result->result_array();

        $channels_names_join = [];

        foreach ($channels_names as $value) {
            array_push($channels_names_join, $value['name']);
        }

        return $channels_names_join;
    }

    function getTimezoneChannel($date, $time, $id_channel)
    {
        $this->db->select('timezone');
        $this->db->from('config');
        $this->db->where('id_channel', $id_channel);

        $result = $this->db->get();

        if ($result->num_rows() > 0) {

            $timezone = $result->result_array()[0]["timezone"];

            if ($timezone[0] === "-")
                $hour = "+" . intval(substr($timezone, 1, -3)) . " hour";
            else
                $hour = "-" . intval(substr($timezone, 1, -3)) . " hour";

            date_default_timezone_set("UTC");

            $american_data = explode('/', $date)[2] . "/" . explode('/', $date)[1] . "/" . explode('/', $date)[0];
            $broadcast_date = new DateTime($american_data . " " . $time . ":00");
            $broadcast_date->modify($hour);

            return strtotime($broadcast_date->format('Y-m-d H:i:s'));
        }
    }

    function View($data)
    {
        $id_channel = explode("_", $data)[0];
        $token = explode("_", $data)[1];

        $this->db->select("
            REPLACE(channel.id, '@c.us', '') AS channel,
            DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.schedule), INTERVAL config.timezone HOUR), '%d/%m/%Y %T') AS schedule,
            DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.creation), INTERVAL config.timezone HOUR), '%d/%m/%Y %T') AS creation,
            CASE
                WHEN broadcast_schedule.expire = '0' THEN ''
                ELSE DATE_FORMAT(FROM_UNIXTIME(broadcast_schedule.expire), '%d/%m/%Y %H:%i')
            END AS expire,
            broadcast_schedule.media_type,
            broadcast_schedule.id_channel,
            broadcast_schedule.media_url,
            broadcast_schedule.status,
            broadcast_schedule.token,
            broadcast_schedule.title,
            broadcast_schedule.data,
            CONCAT(channel.name, ' (', REPLACE(channel.id, '@c.us', ''),'') AS name
        ");

        $this->db->from('broadcast_schedule');
        $this->db->join('channel', 'broadcast_schedule.id_channel = channel.id_channel');
        $this->db->join('config', 'channel.id_channel = config.id_channel');

        $this->db->where('broadcast_schedule.is_fb_publication', 1);
        $this->db->where('broadcast_schedule.id_channel', $id_channel);
        $this->db->where('broadcast_schedule.token', $token);

        return $this->db->get()->result_array();
    }

    function getTimeLine($data)
    {
        $token = explode("_", $data)[1];

        $this->db->select("
            broadcast_schedule_log.type as log_status, DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule_log.creation), INTERVAL config.timezone HOUR), '%d/%m/%Y %H:%i') log_creation,
            broadcast_schedule_log.creation as log_timestamp_creation,
            broadcast_schedule.token as token_schedule,
            broadcast_schedule_log.key_remote_id,
            user.name
        ");

        $this->db->from("broadcast_schedule_log");
        $this->db->join("broadcast_schedule", "broadcast_schedule_log.id_broadcast_schedule = broadcast_schedule.id_broadcast_schedule", "inner");
        $this->db->join("channel", "broadcast_schedule.id_channel = channel.id_channel", "inner");
        $this->db->join("config", "channel.id_channel = config.id_channel", "inner");
        $this->db->join("user", "broadcast_schedule_log.key_remote_id = user.key_remote_id", "left");

        $this->db->where("broadcast_schedule.token", $token);
        $this->db->order_by("broadcast_schedule_log.creation", "desc");
        $this->db->order_by("broadcast_schedule_log.id_broadcast_schedule_log", "desc");

        $data = $this->db->get()->result_array();

        $key_remote_ids = [];

        foreach ($data as $item) {
            if ($item['name'] === '' || $item['name'] === null) {
                $key_remote_ids[] = $item['key_remote_id'];
            }
        }

        if (!empty($key_remote_ids)) {

            $this->db = $this->load->database(SetdatabaseRemote("db1", "192.168.190.40"), TRUE);
            $this->db->select('name, key_remote_id');
            $this->db->from('user');
            $this->db->where_in('key_remote_id', $key_remote_ids);
            $this->db->where('status', 1);

            $users  = $this->db->get()->result_array();

            $data_map = [];
            foreach ($users as $item) {
                $data_map[$item['key_remote_id']] = $item['name'];
            }

            foreach ($data as &$item) {
                if (empty($item['name']) && isset($item['key_remote_id']) && isset($data_map[$item['key_remote_id']])) {

                    if ($this->session->userdata('key_remote_id_login_support') != NULL) {
                        $item['name'] = "Suporte - " . $data_map[$item['key_remote_id']];
                    } else {
                        $item['name'] = "Suporte";
                    }
                }
            }

            $this->db = $this->load->database(SetdatabaseRemote($this->session->userdata("db"), $this->session->userdata("host")), TRUE);
        }

        return $data;
    }

    function Cancel($token)
    {
        $this->load->helper('cancel_campaign_helper.php');
        $resp = cancelCampaing($token);

        return $resp;
    }

    function CancelGroup($data)
    {
        $this->load->helper('cancel_campaign_helper.php');
        $resp = cancelCampaing($data);

        return $resp;
    }

    function listChannel()
    {
        $this->db->select("channel.id, channel.id_channel, channel.status, config.picture, CONCAT(channel.name, ' (', REPLACE(channel.id, '@c.us', ''), ')') as name");

        $this->db->from('channel');
        $this->db->join('config', 'channel.id_channel = config.id_channel');

        $this->db->where('channel.type', 8);
        $this->db->where('channel.status !=', 2);

        return $this->db->get()->result_array();
    }

    function FindDB($token_company)
    {
        $this->db->select('db');

        $this->db->from('talkall_admin.company');
        $this->db->where('token', $token_company);

        return $this->db->get()->result_array();
    }

    function queryLastBroadcast()
    {
        $this->db->select("DATE_FORMAT(DATE_ADD(from_unixtime(max(broadcast_schedule.schedule)), INTERVAL config.timezone HOUR),'%d/%m/%Y') AS date", false);
        $this->db->select("DATE_FORMAT(DATE_ADD(from_unixtime(max(broadcast_schedule.schedule)), INTERVAL config.timezone HOUR),'%H:%i') AS time", false);

        $this->db->from('broadcast_schedule');
        $this->db->join('channel', 'broadcast_schedule.id_channel = channel.id_channel');
        $this->db->join('config', 'channel.id_channel = config.id_channel');

        $this->db->where('broadcast_schedule.status IN (1, 2, 3)');
        $this->db->where('broadcast_schedule.is_wa_status', 2);
        $this->db->where('broadcast_schedule.is_waba_broadcast', 2);
        $this->db->where('broadcast_schedule.is_wa_broadcast', 2);
        $this->db->where('broadcast_schedule.is_wa_community', 2);
        $this->db->where('broadcast_schedule.is_fb_publication', 1);
        $this->db->where('broadcast_schedule.is_Ig_publication', 2);

        $this->db->group_by('broadcast_schedule.id_broadcast_schedule');
        $this->db->order_by('broadcast_schedule.id_broadcast_schedule', 'desc');
        $this->db->limit(1);

        return $this->db->get()->result_array();
    }
}
