<?php

class PublicationWhatsappBroadcastNewsletter_model extends TA_model
{
    public function __construct()
    {
        parent::__construct();
        $this->SetTableName("broadcast_schedule");
        $this->SetPrimaryKey("id_broadcast_schedule");
    }

    function Get($param)
    {
        $this->db->select('
            REPLACE(channel.id, "@c.us", "") AS channel,
            broadcast_schedule.id_broadcast_schedule, 
            DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.schedule), INTERVAL config.timezone HOUR), "%d/%m/%Y %T") as schedule, 
            DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.creation), INTERVAL config.timezone HOUR), "%d/%m/%Y %T") as creation, 
            CASE WHEN broadcast_schedule.expire = "0" THEN "" ELSE DATE_FORMAT(FROM_UNIXTIME(broadcast_schedule.expire), "%d/%m/%Y %H:%i") END as expire, 
            broadcast_schedule.id_channel AS id_channel,
            broadcast_schedule.media_url,
            broadcast_schedule.media_title, 
            broadcast_schedule.media_type,
            broadcast_schedule.status AS status,
            broadcast_schedule.token AS token,
            broadcast_schedule.title AS title,
            broadcast_schedule.data AS data,
            CONCAT(newsletter.name, " (", REPLACE(newsletter.key_remote_id, "@newsletter", ")")) AS name
        ');

        $this->db->from('broadcast_schedule');
        $this->db->join('channel', 'broadcast_schedule.id_channel = channel.id_channel');
        $this->db->join('config', 'channel.id_channel = config.id_channel');
        $this->db->join('newsletter', 'newsletter.key_remote_id = broadcast_schedule.groups', 'left');

        $this->db->where('broadcast_schedule.is_wa_channel', 1);
        $this->db->where_in('broadcast_schedule.status', array(1, 2, 3, 4, 5, 6));
        $this->db->like('LOWER(CONCAT(broadcast_schedule.title, " ", channel.name, " ", channel.id))', $this->db->escape_like_str(strtolower($param['text'])));

        if (!empty($param['channel'])) {
            $this->db->where_in('newsletter.id_newsletter', $param['channel']);
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
        } else {
            $this->db->where("DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.schedule), INTERVAL config.timezone HOUR), '%Y-%m-%d') >= current_date()", null, false);
        }

        $count = $this->db->count_all_results('', false);

        switch ($param['order'][0]['column']) {
            case 2:
                $this->db->order_by('broadcast_schedule.schedule', $param['order'][0]['dir']);
                break;

            case 3:
                $this->db->order_by('newsletter.name', $param['order'][0]['dir']);
                break;

            case 4:
                $this->db->order_by('broadcast_schedule.title', $param['order'][0]['dir']);
                break;

            default:
                $this->db->order_by('broadcast_schedule.id_broadcast_schedule', 'DESC');
        }

        $this->db->limit($param['length'], $param['start']);

        return ['query' => $this->db->get()->result_array(), 'count' => $count];
    }

    function getChannels($idChannel)
    {
        $this->db->select("channel.id,channel.id_channel,channel.id_work_time,channel.name");
        $this->db->from("channel");
        $this->db->where("channel.id_channel", $idChannel);
        $this->db->where("status", 1);

        $result = $this->db->get();
        return $result->result_array()[0];
    }

    function Add($data)
    {
        $date = new DateTime();
        $array_status = [];
        $kanban_communication = [];
        $channel_info = [];

        foreach ($data["select_segmented_newsletter"] as $value) {

            foreach ($data["media_type"] as $key => $val) {

                $this->db->select('id_channel, key_remote_id, subscribers_count');
                $this->db->from('newsletter');
                $this->db->where('status', 1);
                $this->db->where('id_newsletter', $value);

                $result = $this->db->get();

                if ($result->num_rows() > 0) {

                    $row = $result->row();
                    $data['id_channel'] = $row->id_channel;
                    $newsletter_key_remote_id = $row->key_remote_id;
                    $subscribers_count = $row->subscribers_count == null ? 1 : $row->subscribers_count;
                }

                $data['schedule'] = $this->checkScheduleHour($date->getTimestamp(), $this->GetTimezoneChannel($data['date_start'], $data['time_start'], $data['id_channel']));
                $data['token'] = Token();

                $values = [
                    'creation' => $date->getTimestamp(),
                    'id_channel' => $data['id_channel'],
                    'title' => trim($data['input_title']),
                    'token' =>  $data['token'],
                    'schedule' =>  $data['schedule'],
                    'media_type' => !isset($data['media_type'][$key]) ? 1 : $data['media_type'][$key],
                    'media_caption' => !isset($data['media_caption']) ? "" : $data['media_caption'],
                    'media_title' => !isset($data['media_name'][$key]) ? null : $data['media_name'][$key],
                    'media_size' => !isset($data['byte'][$key]) ? 0 : $data['byte'][$key],
                    'media_duration' => !isset($data['media_duration']) ? 0 : $data['media_duration'],
                    'data' => $data['text'][$key],
                    'count' => $subscribers_count,
                    'media_url' => !isset($data['files'][$key]) ? null : $data['files'][$key],
                    'groups' => $newsletter_key_remote_id,
                    'is_wa_channel' => 1,
                    'status' => 3,
                    'submitted_approval' => 2,
                    'status_approval' => 1,
                ];

                $token = $values['token'];
                $return = $this->db->insert('broadcast_schedule', $values);
                $key_id = $this->db->insert_id();

                array_push($array_status, $return);

                CreateBroadcastLog($key_id, 1);

                $this->RegisterBroadcastTalkallAdmin($data);

                $channel_info = $this->getChannels($data['id_channel']);

                $kanban_communication_elm = new stdClass();
                $kanban_communication_elm->key_remote_id = $channel_info['id'];
                $kanban_communication_elm->token = $token;

                array_push($kanban_communication, $kanban_communication_elm);
            }
        }

        $status = true;

        if (in_array(false, $array_status)) {
            $status = false;
        };

        $ret = [
            "kanban_communication" => $kanban_communication,
            "channels_names" => $channel_info['name'] ?? "Unknown",
            "status" => $status
        ];

        return $ret;
    }

    function RegisterBroadcastTalkallAdmin($data)
    {
        $channel = $this->getChannels($data['id_channel']);
        $this->talkall_admin = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), TRUE);

        $date = new DateTime();
        $values = [
            'creation' => $date->getTimestamp(),
            'schedule' => $data['schedule'],
            'title' => $data['input_title'],
            'token' => $data['token'],
            'channel_name' => $channel['name'],
            'channel_id' => $channel['id'],
            'type' => 4,
            'is_trial' => $this->session->userdata('is_trial'),
            'id_company' => $this->session->userdata('id_company'),
        ];

        $this->talkall_admin->insert("broadcast_schedule", $values);
    }

    function checkScheduleHour($current_time, $schedule)
    {
        if ($current_time > $schedule) {
            return $current_time;
        } else {
            return $schedule;
        }
    }

    function CheckStatusToEdit($token)
    {
        $this->db->select('broadcast_schedule.status');
        $this->db->from('broadcast_schedule');
        $this->db->where('broadcast_schedule.token', $token);
        $status = $this->db->get()->result_array()[0]["status"];

        if ($status != 3)
            return true;

        return false;
    }

    function CheckTimeToEdit($token)
    {
        if ($this->CheckStatusToEdit($token)) return ["errors" => ["code" => "TA-024"]];

        $this->db->select('DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR), "%Y-%m-%d %H:%i") AS schedule');
        $this->db->from('broadcast_schedule');
        $this->db->join('channel', 'channel.id_channel = broadcast_schedule.id_channel');
        $this->db->join('config', 'config.id_channel = channel.id_channel', 'left');
        $this->db->where('broadcast_schedule.token', $token);

        $schedule = $this->db->get()->result_array()[0]["schedule"];

        $date = new DateTime();
        $schedule_date = new DateTime($schedule . ":00");

        $interval = $date->diff($schedule_date);
        $interval_hours = $interval->h >= 1 || ($interval->d > 0) || ($interval->h == 0 && $interval->i >= 60);

        if ($interval_hours) {
            return ["success" => ["status" => true]];
        } else {
            return ["errors" => ["code" => "TA-023"]];
        }
    }

    function GetBroadcastByToken($token)
    {
        $this->db->select('
            broadcast_schedule.id_channel,
            CONCAT(channel.name, " (", channel.id, ")") AS channel_name,
            broadcast_schedule.groups AS newsletter_group,
            newsletter.name AS newsletter_name,
            newsletter.id_newsletter,
            broadcast_schedule.title AS broadcast_title,
            broadcast_schedule.token,
            broadcast_schedule.media_type,
            broadcast_schedule.data,
            broadcast_schedule.media_url,
            broadcast_schedule.is_limited_time,
            DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR), "%d/%m/%Y %H:%i") AS schedule,
            DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.creation), INTERVAL config.timezone HOUR), "%d/%m/%Y %H:%i") AS creation
        ');

        $this->db->from('broadcast_schedule');
        $this->db->join('channel', 'channel.id_channel = broadcast_schedule.id_channel');
        $this->db->join('newsletter', 'newsletter.key_remote_id = broadcast_schedule.groups', 'left');
        $this->db->join('config', 'config.id_channel = channel.id_channel', 'left');

        $this->db->where('broadcast_schedule.token', $token);

        return $this->db->get()->result_array()[0];
    }

    function getIdBroadcastByToken($token)
    {
        $this->db->select('broadcast_schedule.id_broadcast_schedule');
        $this->db->from('broadcast_schedule');
        $this->db->where('broadcast_schedule.token', $token);

        return $this->db->get()->result_array()[0]['id_broadcast_schedule'];
    }

    function getChannelName($channels_ids)
    {
        $channels_ids = implode(",", $channels_ids);

        $this->db->select('name');
        $this->db->from('channel');
        $this->db->where_in('id_channel', explode(',', $channels_ids));
        $this->db->order_by('name', 'ASC');

        $result = $this->db->get();

        $channels_names = $result->result_array();
        $channels_names_join = [];

        foreach ($channels_names as $value) {
            array_push($channels_names_join, $value['name']);
        }

        return $channels_names_join;
    }

    function GetTimezoneChannel($date, $time, $id_channel)
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

    function getScheduleLog($token)
    {
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

    function GetChannelKeyRemoteIdByToken($token)
    {
        $this->db->select("channel.id");
        $this->db->from("channel");
        $this->db->join("broadcast_schedule", "channel.id_channel = broadcast_schedule.id_channel", "inner");
        $this->db->where("channel.status", 1);
        $this->db->where("broadcast_schedule.token", $token);

        return $this->db->get()->result_array()[0]["id"];
    }

    function View($token)
    {
        $this->db->select("
            broadcast_schedule.id_broadcast_schedule,
            REPLACE(newsletter.key_remote_id, '@newsletter', '') as newsletter,
            REPLACE(channel.id, '@c.us', '') channel,
            DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR), '%d/%m/%Y %H:%i') as schedule,
            DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.creation), INTERVAL config.timezone HOUR), '%d/%m/%Y %H:%i') as creation,
            CASE 
                WHEN broadcast_schedule.expire = '0' THEN ''
                ELSE DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.expire), INTERVAL config.timezone HOUR), '%d/%m/%Y %H:%i')
            END as expire,
            broadcast_schedule.thumb_image,
            broadcast_schedule.media_url,
            broadcast_schedule.media_type,
            broadcast_schedule.status,
            broadcast_schedule.token,
            broadcast_schedule.title,
            broadcast_schedule.data,
            channel.name,
            newsletter.name as newsletter_name,
            user.name full_name,
            user.key_remote_id,
            broadcast_schedule_log.key_remote_id
        ");

        $this->db->from('broadcast_schedule');
        $this->db->join("broadcast_schedule_log", "broadcast_schedule.id_broadcast_schedule = broadcast_schedule_log.id_broadcast_schedule", "left");
        $this->db->join('channel', 'broadcast_schedule.id_channel = channel.id_channel');
        $this->db->join('config', 'channel.id_channel = config.id_channel');
        $this->db->join('user', 'broadcast_schedule_log.key_remote_id = user.key_remote_id', 'left');
        $this->db->join('newsletter', 'newsletter.key_remote_id = broadcast_schedule.groups', 'left');
        $this->db->where('broadcast_schedule.token', $token);

        return $this->db->get()->result_array();
    }

    function checkLastDate($date_start, $last_date)
    {
        $date = new DateTime();
        $date_start_format = DateTime::createFromFormat('d/m/Y', $date_start)->format('Y-m-d');
        $last_date_format = DateTime::createFromFormat('d/m/Y H:i', $last_date)->format('Y-m-d');

        if (($last_date_format > ($date)->format('Y-m-d')) && ($date_start_format == ($date)->format('Y-m-d')))
            return false;

        return true;
    }

    function Edit($token, $data)
    {
        $date = new DateTime();
        $kanban_communication = [];
        $text = isset($data["description_data"]) ? $data["description_data"] : $data['text'][0];

        $values = [
            'title' => $data['input_title'],
            'schedule' => $this->checkScheduleHour($date->getTimestamp(), $this->GetTimezoneChannel($data['date_start'], $data['time_start'], $data['id_channel'])),
            'data' => $text
        ];

        $this->db->where('broadcast_schedule.token', $token);
        $return = $this->db->update('broadcast_schedule', $values);

        $key_id = $this->getIdBroadcastByToken($token);
        CreateBroadcastLog($key_id, 7);

        $edit_broadcast = $this->checkLastDate($data['date_start'], $data['last_date']);
        $date_start_format = (DateTime::createFromFormat('d/m/Y', $data['date_start']))->format('Y-m-d');

        $kanban_communication_elm = new stdClass();
        $kanban_communication_elm->key_remote_id = $this->getChannels($data['id_channel'])['id'];
        $kanban_communication_elm->token = $token;
        $kanban_communication_elm->key_id = $key_id;
        $kanban_communication_elm->edit_broadcast = $edit_broadcast;

        if ($edit_broadcast) {
            $kanban_communication_elm->data = new stdClass();
            $kanban_communication_elm->data->title = $data['input_title'];
            $kanban_communication_elm->data->data = $text;
            $kanban_communication_elm->data->schedule = $values['schedule'];
            $kanban_communication_elm->data->formated_schedule_date = $date_start_format;
            $kanban_communication_elm->data->formated_schedule_time = $data['time_start'];
            $kanban_communication_elm->data->is_limited_time = $data['is_limited_time'];
            $kanban_communication_elm->data->remove_from_kanban = ($date_start_format > ($date)->format('Y-m-d')) ? true : false;
        }

        array_push($kanban_communication, $kanban_communication_elm);

        $ret = [
            "kanban_communication" => $kanban_communication,
            "channels_names" => $this->getChannels($data['id_channel'])['name'],
            "status" => $return
        ];

        return $ret;
    }

    function queryLastBroadcast()
    {
        $this->db->select("DATE_FORMAT(DATE_ADD(from_unixtime(max(broadcast_schedule.schedule)), INTERVAL config.timezone HOUR), '%d/%m/%Y') AS date", false);
        $this->db->select("DATE_FORMAT(DATE_ADD(from_unixtime(max(broadcast_schedule.schedule)), INTERVAL config.timezone HOUR), '%H:%i') AS time", false);

        $this->db->from('broadcast_schedule');
        $this->db->join('channel', 'broadcast_schedule.id_channel = channel.id_channel');
        $this->db->join('config', 'channel.id_channel = config.id_channel');

        $this->db->where_in('broadcast_schedule.status', [1, 2, 3]);
        $this->db->where('broadcast_schedule.is_wa_status', 2);
        $this->db->where('broadcast_schedule.is_waba_broadcast', 2);
        $this->db->where('broadcast_schedule.is_wa_broadcast', 2);
        $this->db->where('broadcast_schedule.is_wa_channel', 1);

        $this->db->group_by('broadcast_schedule.id_broadcast_schedule');
        $this->db->order_by('broadcast_schedule.id_broadcast_schedule', 'desc');
        $this->db->limit(1);

        return $this->db->get()->result_array();
    }

    function queryChannel()
    {
        $this->db->select('type, id');
        $this->db->from('channel');
        $this->db->where('type', 255);

        return $this->db->get()->result_array();
    }

    function ListNewsletter()
    {
        $this->db->select('newsletter.*');
        $this->db->from('newsletter');
        $this->db->join('channel', 'newsletter.id_channel = channel.id_channel');
        $this->db->where('newsletter.status', 1);
        $this->db->where('channel.status', 1);
        $this->db->order_by('newsletter.name', 'ASC');

        return $this->db->get()->result_array();
    }

    function ResendBroadcast($token)
    {
        $this->db->trans_start();

        $this->db->select('status');
        $this->db->where('token', $token);
        $query = $this->db->get('broadcast_schedule');
        $row = $query->row();

        if ($row) {
            $newStatus = null;

            if ($row->status == 2) {
                $newStatus = 6;
            } elseif ($row->status == 5) {
                $newStatus = 3;
            }

            $this->db->where('broadcast_schedule.token', $token);
            $this->db->update('broadcast_schedule', ['status' => $newStatus, 'is_paused' => 2]);

            $this->db->where('broadcast_send.token', $token);
            $this->db->update('broadcast_send', ['status' => 1]);

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                return ["errors" => ["code" => "PAD-002"]];
            } else {
                $broadcast_id = $this->getIdBroadcastByToken($token);
                CreateBroadcastLog($broadcast_id, 9);
                return ["success" => ["status" => true]];
            }
        }
    }
}
