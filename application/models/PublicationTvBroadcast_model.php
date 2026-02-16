<?php

class PublicationTvBroadcast_model extends TA_model
{
    function Get($param)
    {
        $this->db->select("
            broadcast_schedule.id_broadcast_schedule,
            channel.id channel,
            DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.schedule), INTERVAL config.timezone HOUR),'%d/%m/%Y - %T') schedule,
            DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.creation), INTERVAL config.timezone HOUR),'%d/%m/%Y %T') creation,
            DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.expire), INTERVAL config.timezone HOUR),'%d/%m/%Y %T') expire,
            broadcast_schedule.submitted_approval,
            broadcast_schedule.status_approval,
            broadcast_schedule.id_channel,
            broadcast_schedule.media_url,
            broadcast_schedule.media_title,
            broadcast_schedule.media_type,
            broadcast_schedule.status,
            broadcast_schedule.token,
            broadcast_schedule.title,
            broadcast_schedule.data,
            broadcast_schedule.is_paused,
            channel.name
        ");

        $this->db->from("broadcast_schedule");
        $this->db->join("channel", "broadcast_schedule.id_channel = channel.id_channel", "inner");
        $this->db->join("config", "channel.id_channel = config.id_channel", "inner");
        $this->db->where("broadcast_schedule.is_tv_broadcast", 1);
        $this->db->where_in("broadcast_schedule.status", array(1, 2, 3, 4, 5, 6));
        $this->db->like("LOWER(CONCAT(broadcast_schedule.title, ' ', channel.name, ' '))", $this->db->escape_like_str(strtolower($param['text'])));

        if (!empty($param['channel'])) {
            $this->db->where_in("broadcast_schedule.id_channel", $param['channel']);
        }

        if (!empty($param['status'])) {

            switch ($param['status']) {
                case 6:
                    $this->db->where("broadcast_schedule.status", $param['status']);
                    $this->db->where("broadcast_schedule.is_paused", 2);
                    break;
                case 7:
                    $this->db->where("broadcast_schedule.status", 6);
                    $this->db->where("broadcast_schedule.is_paused", 1);
                    break;
                default:
                    $this->db->where("broadcast_schedule.status", $param['status']);
                    break;
            }
        }

        if (!empty($param['approval'])) {

            switch ($param['approval']) {
                case 1:
                    $this->db->where("broadcast_schedule.submitted_approval", 1);
                    $this->db->where("broadcast_schedule.status_approval", $param['approval']);
                    break;
                case 4:
                    $this->db->where("broadcast_schedule.submitted_approval", 2);
                    break;
                default:
                    $this->db->where("broadcast_schedule.status_approval", $param['approval']);
                    break;
            }
        }

        if (!empty($param['dt_start'])) {
            $this->db->where("DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.schedule), INTERVAL config.timezone HOUR),'%Y-%m-%d')   BETWEEN '" . $param['dt_start'] . "' AND '" . $param['dt_end'] . "'", NULL, FALSE);
        } else {
            $this->db->where("DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.schedule), INTERVAL config.timezone HOUR),'%Y-%m-%d') >= current_date()", NULL, FALSE);
        }

        $count = $this->db->count_all_results('', false);

        switch ($param['order'][0]['column']) {
            case 1:
                $this->db->order_by("broadcast_schedule.title", $param['order'][0]['dir']);
                break;
            case 2:
                $this->db->order_by("broadcast_schedule.schedule", $param['order'][0]['dir']);
                break;
            default:
                $this->db->order_by("broadcast_schedule.id_broadcast_schedule", "asc");
        }

        $this->db->limit($param['length'], $param['start']);

        return ['query' => $this->db->get()->result_array(), 'count' => $count];
    }

    function Add($data)
    {
        $date = new DateTime();

        foreach ($data["others"] as $row) {

            $values = [
                'creation' => $date->getTimestamp(),
                'id_channel' => $row,
                'title' => trim($data['input_title']),
                'token' => Token(),
                'schedule' => $this->getTimezoneChannel($data['datetime_start'], $row),
                'media_type' => 5,
                'expire' =>  $this->getTimezoneChannel($data['datetime_end'], $row),
                'media_size' => !isset($data['media_size']) ? 0 : $data['media_size'],
                'media_duration' => !isset($data['media_duration']) ? 0 : $data['media_duration'],
                'media_url' => $data['media_url'],
                'is_tv_broadcast' => 1,
                'status' => 3,
                'submitted_approval' => 6,
                'status_approval' => 1,
                'id_approval' =>  null
            ];

            $this->db->insert('broadcast_schedule', $values);
            $this->registerLog($this->db->insert_id(), 1);
        }
    }

    function checkScheduleHour($current_time, $schedule)
    {
        if ($current_time > $schedule) {
            return $current_time;
        } else {
            return $schedule;
        }
    }

    function ListChannels()
    {
        $this->db->select('channel.id_channel, channel.name');
        $this->db->from('channel');
        $this->db->where('channel.status', 1);
        $this->db->where('channel.type', 28);

        return $this->db->get()->result_array();
    }

    function registerLog($key_id, $type)
    {
        $date = new DateTime();

        $values = [
            'id_broadcast_schedule' => $key_id,
            'creation' => $date->getTimestamp(),
            'key_remote_id' => $this->session->userdata('key_remote_id'),
            'type' => $type
        ];

        $this->db->insert('broadcast_schedule_log', $values);
    }

    function View($token)
    {
        $this->db->select("
            broadcast_schedule.id_broadcast_schedule,
            DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.schedule), INTERVAL config.timezone HOUR),'%d/%m/%Y %T') schedule,
            DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.creation), INTERVAL config.timezone HOUR),'%d/%m/%Y %T') creation,
            DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.expire), INTERVAL config.timezone HOUR),'%d/%m/%Y %T') expire,
            broadcast_schedule.media_url,
            broadcast_schedule.media_type,
            broadcast_schedule.status,
            broadcast_schedule.token,
            broadcast_schedule.title,
            broadcast_schedule.data,
            broadcast_schedule.submitted_approval,
            channel.name,
            contact.full_name,
            contact.key_remote_id,
            DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_approval_log.creation), INTERVAL config.timezone HOUR), '%d/%m/%Y %H:%i') log_creation,  
            broadcast_approval_log.creation log_creation_timestamp,
            broadcast_approval_log.status as log_status,
            broadcast_approval_log.id_approval as log_id_approval,
            broadcast_approval_log.message as log_message,
            broadcast_approval_log.token_schedule as token_schedule,
            broadcast_schedule_log.key_remote_id
        ");

        $this->db->from("broadcast_schedule");
        $this->db->join("broadcast_schedule_log", "broadcast_schedule.id_broadcast_schedule = broadcast_schedule_log.id_broadcast_schedule", "left");
        $this->db->join("channel", "broadcast_schedule.id_channel = channel.id_channel", "inner");
        $this->db->join("config", "channel.id_channel = config.id_channel", "inner");
        $this->db->join("broadcast_approval_log", "broadcast_schedule.id_approval = broadcast_approval_log.id_approval", "left");
        $this->db->join("contact", "broadcast_approval_log.key_remote_id = contact.key_remote_id", "left");
        $this->db->where("broadcast_schedule.token", $token);
        $this->db->order_by("broadcast_approval_log.creation", "asc");

        return $this->db->get()->result_array();
    }

    function getScheduleLog($token)
    {
        $this->db->select("
            broadcast_schedule_log.type as log_status, DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule_log.creation), INTERVAL config.timezone HOUR), '%d/%m/%Y %H:%i') log_creation,
            broadcast_schedule_log.creation as log_timestamp_creation,
            broadcast_schedule.token as token_schedule,
            user.name as full_name
        ");

        $this->db->from("broadcast_schedule_log");
        $this->db->join("broadcast_schedule", "broadcast_schedule_log.id_broadcast_schedule = broadcast_schedule.id_broadcast_schedule", "inner");
        $this->db->join("channel", "broadcast_schedule.id_channel = channel.id_channel", "inner");
        $this->db->join("config", "channel.id_channel = config.id_channel", "inner");
        $this->db->join("user", "broadcast_schedule_log.key_remote_id = user.key_remote_id", "inner");

        $this->db->where("broadcast_schedule.token", "$token");
        $this->db->order_by("broadcast_schedule_log.creation", "desc");

        return $this->db->get()->result_array();
    }

    function getTimezoneChannel($datetime, $id_channel)
    {
        if ($datetime == "") return 0;

        $this->db->select("config.timezone");
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

            list($D, $H) = explode("T", $datetime);

            $format_date = str_replace('-', '/', $D);
            $broadcast_date = new DateTime($format_date . " " . $H . ":00");
            $broadcast_date->modify($hour);

            return strtotime($broadcast_date->format('Y-m-d H:i:s'));
        }
    }

    function getCampaignScheduledSameDate($data)
    {
        $this->db->select('broadcast_schedule.schedule');
        $this->db->select('broadcast_schedule.expire');
        $this->db->from('broadcast_schedule');
        $this->db->where('broadcast_schedule.status', 3);
        $this->db->where('broadcast_schedule.is_tv_broadcast', 1);
        $this->db->where_in('broadcast_schedule.id_channel', $data['id_channel']);
        $this->db->where('broadcast_schedule.expire >=', strtotime($data['datetime_start']) + (5 * 60));
        $this->db->where('broadcast_schedule.schedule <=', strtotime($data['datetime_end']));

        return $this->db->get()->result_array();
    }

    function Cancel($token)
    {
        $this->db->select("broadcast_schedule.id_broadcast_schedule, broadcast_schedule.status");
        $this->db->from("broadcast_schedule");
        $this->db->where(" broadcast_schedule.token", $token);

        $result = $this->db->get()->result_array();

        if (!empty($result)) {

            if ($result[0]["status"] == 3 || $result[0]["status"] == 6) {

                $this->db->where('id_broadcast_schedule', $result[0]["id_broadcast_schedule"]);
                $this->db->update('broadcast_schedule', ['status' => 5]);
                $this->registerLog($result[0]["id_broadcast_schedule"], 4);

                return [
                    "success" => [
                        "status" => true,
                        "data" => "[Cancelled campaign]"
                    ]
                ];
            }
        } else {
            return [
                "error" => [
                    "code" => "TA-021",
                    "title" => "not found",
                    "message" => "Campaign not found"
                ]
            ];
        }
    }

    function Pause($token)
    {
        $this->db->select("broadcast_schedule.id_broadcast_schedule, broadcast_schedule.status");
        $this->db->from("broadcast_schedule");
        $this->db->where(" broadcast_schedule.token", $token);

        $result = $this->db->get()->result_array();

        if (!empty($result)) {
            if ($result[0]["status"] == 6) {

                $this->db->where('id_broadcast_schedule', $result[0]["id_broadcast_schedule"]);
                $this->db->update('broadcast_schedule', ['is_paused' => 1]);
                $this->registerLog($result[0]["id_broadcast_schedule"], 2);

                return [
                    "success" => [
                        "status" => true,
                        "data" => "[Paused campaign]"
                    ]
                ];
            }
        } else {
            return [
                "error" => [
                    "code" => "TA-021",
                    "title" => "not found",
                    "message" => "Campaign not found"
                ]
            ];
        }
    }

    function Resume($token)
    {
        $this->db->select("broadcast_schedule.id_broadcast_schedule, broadcast_schedule.status");
        $this->db->from("broadcast_schedule");
        $this->db->where(" broadcast_schedule.token", $token);

        $result = $this->db->get()->result_array();

        if (!empty($result)) {
            if ($result[0]["status"] == 6) {

                $this->db->where('id_broadcast_schedule', $result[0]["id_broadcast_schedule"]);
                $this->db->update('broadcast_schedule', ['is_paused' => 2]);
                $this->registerLog($result[0]["id_broadcast_schedule"], 3);

                return [
                    "success" => [
                        "status" => true,
                        "data" => "[Campaign resumed]"
                    ]
                ];
            }
        } else {
            return [
                "error" => [
                    "code" => "TA-021",
                    "title" => "not found",
                    "message" => "Campaign not found"
                ]
            ];
        }
    }
}
