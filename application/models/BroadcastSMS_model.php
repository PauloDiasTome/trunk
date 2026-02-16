<?php

class BroadcastSMS_model extends TA_model
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
            broadcast_schedule.id_broadcast_schedule,
            REPLACE(channel.id, '@c.us', '') channel,
            DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.schedule), INTERVAL config.timezone HOUR),'%d/%m/%Y - %T') schedule,
            DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.creation), INTERVAL config.timezone HOUR),'%d/%m/%Y %T') creation,
            CASE
                WHEN broadcast_schedule.expire = '0' THEN ''
                ELSE DATE_FORMAT(FROM_UNIXTIME(broadcast_schedule.expire),
                        '%d/%m/%Y %H:%i')
            END expire,
            broadcast_schedule.id_channel,
            broadcast_schedule.media_url,
            broadcast_schedule.media_title,
            broadcast_schedule.media_type,
            broadcast_schedule.status,
            broadcast_schedule.token,
            broadcast_schedule.title,
            broadcast_schedule.data,
            broadcast_schedule.is_paused,
            CONCAT(channel.name,' (',  REPLACE(channel.id, '@c.us', ''), ')') name
        ");

        $this->db->from("broadcast_schedule");
        $this->db->join("channel", "broadcast_schedule.id_channel = channel.id_channel", "inner");
        $this->db->join("config", "channel.id_channel = config.id_channel", "inner");
        $this->db->where("broadcast_schedule.is_sms_broadcast", 1);
        $this->db->where_in("broadcast_schedule.status", array(1, 2, 3, 4, 5, 6, 7));
        $this->db->like("CONCAT(LOWER(broadcast_schedule . title), ' ', channel . id)", strtolower($param['text']), false);

        if (!empty($param['status'])) {
            switch ($param['status']) {
                case 3:
                    $this->db->group_start();
                    $this->db->where("broadcast_schedule.status", $param['status']);
                    $this->db->or_where("broadcast_schedule.status", 1);
                    $this->db->group_end();
                    break;
                default:
                    $this->db->where("broadcast_schedule.status", $param['status']);
                    break;
            }
        }

        if (!empty($param['dt_start'])) {
            $this->db->where("DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.schedule), INTERVAL config.timezone HOUR),'%Y-%m-%d') BETWEEN '" . $param['dt_start'] . "' AND '" . $param['dt_end'] . "'", NULL, FALSE);
        }

        $count = $this->db->count_all_results('', false);

        switch ($param['order'][0]['column']) {
            case 0:
                $this->db->order_by("broadcast_schedule.title", $param['order'][0]['dir']);
                break;
            case 1:
                $this->db->order_by("broadcast_schedule.schedule", $param['order'][0]['dir']);
                break;
            default:
                $this->db->order_by('broadcast_schedule.schedule', 'desc');
                break;
        }

        $this->db->limit($param['length'], $param['start']);

        return ['query' => $this->db->get()->result_array(), 'count' => $count];
    }

    function Add($data)
    {
        $date = new DateTime();
        $token = Token();
        $participants = 0;
        $list = 0;

        if ($data["select_segmented_group"] !=  0) {

            $this->db->select('contact.key_remote_id');
            $this->db->from('contact_group');
            $this->db->join('contact', 'contact_group.id_contact = contact.id_contact');
            $this->db->where('contact_group.id_group_contact', $data["select_segmented_group"]);
            $this->db->where('deleted', 1);
            $this->db->where('spam', 1);
            $result = $this->db->get();

            if ($result->num_rows() > 0) {
                $list = array_column($result->result_array(), 'key_remote_id');
                $participants =  implode(',', $list);
            }
        } else {

            $query = "SET SESSION group_concat_max_len = 1000000";
            $this->db->query($query);

            $sql = "SELECT 
                        GROUP_CONCAT(DISTINCT contact.key_remote_id) participants
                    FROM
                        contact
                            INNER JOIN
                        channel ON contact.id_channel = channel.id_channel
                    WHERE
                        contact.deleted = 1 AND contact.spam = 1
                            AND channel.id_channel = '" . $data['select_channel'] . "' AND contact.is_private = 1
                                    AND contact.is_group = 1 AND channel.status = 1
                    ORDER BY contact.creation
                    LIMIT 10000";

            $result = $this->db->query($sql);

            if ($result->num_rows() > 0) {
                $participants = $result->result_array()[0]['participants'];
                $list = explode(",", $participants);
            }
        }

        $values = [
            'creation' => $date->getTimestamp(),
            'id_channel' => $data['select_channel'],
            'title' => trim($data['input_title']),
            'token' => $token,
            'schedule' => $this->checkScheduleHour($date->getTimestamp(), $this->getTimezoneChannel($data['date_start'], $data['time_start'], $data['select_channel'])),
            'media_type' => !isset($data['media_type']) ? 1 : $data['media_type'],
            'media_caption' => null,
            'media_size' => !isset($data['media_size']) ? 0 : $data['media_size'],
            'media_duration' => !isset($data['media_duration']) ? 0 : $data['media_duration'],
            'media_url' => null,
            'groups' => $data['select_segmented_group'],
            'data' => trim($data['input_data']),
            'count' => $participants == null ? 0 : count($list),
            'is_sms_broadcast' => 1,
            'status' => 3
        ];

        $this->db->insert('broadcast_schedule', $values);
        $key_id = $this->db->insert_id();

        $values = [
            'id_broadcast_schedule' => $key_id,
            'participants' => $participants == null ? "" : $participants
        ];

        $this->db->insert('broadcast_schedule_participants', $values);

        $this->registerLog($token, 1);
    }

    function checkScheduleHour($current_time, $schedule)
    {
        if ($current_time > $schedule) {
            return $current_time;
        } else {
            return $schedule;
        }
    }

    function registerLog($token, $type)
    {
        $date = new DateTime();

        $values = [
            'id_broadcast_schedule' => $this->getBroadastIdByToken($token)[0]["id_broadcast_schedule"],
            'creation' => $date->getTimestamp(),
            'key_remote_id' => $this->session->userdata('key_remote_id'),
            'type' => $type
        ];

        $this->db->insert('broadcast_schedule_log', $values);
    }

    function getTimezoneChannel($date, $time, $id_channel)
    {
        if ($date == "") return 0;

        $this->db->select("config.timezone ");
        $this->db->from("config");
        $this->db->where("config.id_channel", $id_channel);

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

    function View($token)
    {
        $this->db->select("
            user.name,
            broadcast_schedule_log.key_remote_id,
            broadcast_schedule.id_broadcast_schedule,
            REPLACE(channel.id, '@c.us', '') AS channel,
            DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR), '%d/%m/%Y %H:%i') AS schedule,
            DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.creation), INTERVAL config.timezone HOUR), '%d/%m/%Y %H:%i') AS creation,
            CASE WHEN broadcast_schedule.expire = '0' THEN '' ELSE DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.expire), INTERVAL config.timezone HOUR), '%d/%m/%Y %H:%i') END AS expire,
            broadcast_schedule.media_url,
            broadcast_schedule.media_type,
            broadcast_schedule.status,
            broadcast_schedule.token,
            broadcast_schedule.title,
            broadcast_schedule.data,
            broadcast_schedule.groups,
            group_contact.name AS group_name
        ");

        $this->db->from('broadcast_schedule_log');
        $this->db->join('broadcast_schedule', 'broadcast_schedule.id_broadcast_schedule = broadcast_schedule_log.id_broadcast_schedule', 'inner');
        $this->db->join('channel', 'broadcast_schedule.id_channel = channel.id_channel', 'inner');
        $this->db->join('config', 'channel.id_channel = config.id_channel', 'inner');
        $this->db->join('user', 'broadcast_schedule_log.key_remote_id = user.key_remote_id', 'left');
        $this->db->join('group_contact', 'broadcast_schedule.groups = group_contact.id_group_contact', 'left');
        $this->db->where('broadcast_schedule.token', $token);

        return $this->db->get()->result_array()[0];
    }

    function Cancel($token)
    {
        $this->db->select("broadcast_schedule.id_broadcast_schedule, broadcast_schedule.status");
        $this->db->from("broadcast_schedule");
        $this->db->where("broadcast_schedule.token", $token);

        $result = $this->db->get();
        $data = $result->result_array();

        if ($result->num_rows() > 0) {

            foreach ($data as $row) {

                switch ($row["status"]) {
                    case 1:
                    case 3:
                    case 6:
                    case 7:
                        $this->db->where('token', $token);
                        $this->db->update('broadcast_schedule', array('status' => 5));
                        $this->db->update('broadcast_schedule', array('is_paused' => 2));

                        $this->db->where(array('token' => $token, 'status' => 1));
                        $this->db->update('broadcast_send', array('status' => 5));

                        $this->registerLog($token, 4);

                        return ["success" => ["status" => true]];
                        break;
                    case 2:
                    case 4:
                    case 5:
                        return ["errors" => ["code" => "TA-022", "title" => "Unauthorized action", "message" => "Campaign sent/cancelled"]];
                        break;
                    default:
                        break;
                }
            }
        }
    }

    function Pause($token)
    {
        $this->db->where('broadcast_schedule.token', $token);
        $this->db->update('broadcast_schedule', ["status" => 7, "is_paused" => 1]);

        $this->registerLog($token, 2);

        return ["success" => ["status" => true]];
    }

    function Resume($token)
    {
        $this->db->where('broadcast_schedule.token', $token);
        $this->db->update('broadcast_schedule', ["status" => 6, "is_paused" => 2]);

        $this->registerLog($token, 3);

        return ["success" => ["status" => true]];
    }

    function queryLastBroadcast()
    {
        $this->db->select("
            date_format( DATE_ADD(from_unixtime(max(broadcast_schedule.schedule)), INTERVAL config.timezone HOUR),'%d/%m/%Y') date,
            date_format( DATE_ADD(from_unixtime(max(broadcast_schedule.schedule)), INTERVAL config.timezone HOUR),'%H:%i') time
         ");

        $this->db->from("broadcast_schedule");
        $this->db->join("channel", "broadcast_schedule.id_channel = channel.id_channel", "inner");
        $this->db->join("config", "channel.id_channel = config.id_channel", "inner");
        $this->db->where_in("broadcast_schedule.status", array(1, 2, 3));
        $this->db->where("broadcast_schedule.is_sms_broadcast", 1);
        $this->db->group_by("broadcast_schedule.id_broadcast_schedule");
        $this->db->order_by("broadcast_schedule.id_broadcast_schedule", "desc");
        $this->db->limit(1);

        return $this->db->get()->result_array();
    }

    function getScheduleLog($token)
    {
        $this->db->select("
            broadcast_schedule_log.type as log_status, DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule_log.creation), INTERVAL config.timezone HOUR), '%d/%m/%Y %H:%i') log_creation,
            broadcast_schedule_log.creation as log_timestamp_creation,
            broadcast_schedule.token as token_schedule,
            user.name as name
        ");

        $this->db->from("broadcast_schedule_log");
        $this->db->join("broadcast_schedule", "broadcast_schedule_log.id_broadcast_schedule = broadcast_schedule.id_broadcast_schedule", "inner");
        $this->db->join("channel", "broadcast_schedule.id_channel = channel.id_channel", "inner");
        $this->db->join("config", "channel.id_channel = config.id_channel", "inner");
        $this->db->join("user", "broadcast_schedule_log.key_remote_id = user.key_remote_id", "inner");

        $this->db->where("broadcast_schedule.token", "$token");
        $this->db->order_by("broadcast_schedule_log.creation");

        return $this->db->get()->result_array();
    }

    function ListChannelSMS()
    {
        $this->db->select('channel.id_channel, channel.id, channel.name');
        $this->db->from('channel');
        $this->db->where('channel.type', 6);
        $this->db->where('channel.status', 1);

        return $this->db->get()->result_array();
    }

    function ListPersonaSMS()
    {
        $this->db->select('
            group_contact.id_group_contact,
            group_contact.name
        ');

        $this->db->from('group_contact');
        $this->db->join('channel', 'group_contact.id_channel = channel.id_channel', 'inner');
        $this->db->where('channel.type', 6);
        $this->db->order_by('group_contact.name');

        return $this->db->get()->result_array();
    }

    function getBroadastIdByToken($token)
    {
        $this->db->select('id_broadcast_schedule');
        $this->db->from('broadcast_schedule');
        $this->db->where_in('token', $token);

        return $this->db->get()->result_array();
    }
}
