<?php

class PublicationTelegramChannel_model extends TA_model
{

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
            CONCAT(channel.name,
                    ' (',
                    REPLACE(channel.id, '@c.us', ''),
                    ')') name
        ");

        $this->db->from("broadcast_schedule");
        $this->db->join("channel", "broadcast_schedule.id_channel = channel.id_channel", "inner");
        $this->db->join("config", "channel.id_channel = config.id_channel", "inner");
        $this->db->where("broadcast_schedule.is_telegram_channel", 1);
        $this->db->like("LOWER(CONCAT(broadcast_schedule . title, ' ', channel . id))", $this->db->escape_like_str(strtolower($param['text'])));
    

        if (!empty($param['channel'])) {
            $this->db->where_in("broadcast_schedule.id_channel", $param['channel']);
        }

        if (!empty($param['status'])) {

            switch ($param['status']) {
                case 1:
                case 3:
                    $this->db->where_in("broadcast_schedule.status", array(1, 3));
                    break;
                case 2:
                    $this->db->where("broadcast_schedule.status", $param['status']);
                    break;
                case 5:
                    $this->db->where("broadcast_schedule.status", $param['status']);
                    break;
                case 6:
                    $this->db->where("broadcast_schedule.status", $param['status']);
                    break;
                default:
                    $this->db->where_in("broadcast_schedule.status", array(1, 2, 3, 4, 5, 6));
                    break;
            }
        }

        if (!empty($param['dt_start'])) {

            $dt_start = $param['dt_start'];
            $dt_end = $param['dt_end'];
            $this->db->where("DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.schedule), INTERVAL config.timezone HOUR),'%Y-%m-%d')  BETWEEN '$dt_start' AND '$dt_end'", NULL, FALSE);
        } else {
            $this->db->where("DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.schedule), INTERVAL config.timezone HOUR),'%Y-%m-%d') >= current_date()", NULL, FALSE);
        }
       
        $count = $this->db->count_all_results('', false);

        switch ($param['order'][0]['column']) {
            case 2:
                $this->db->order_by("broadcast_schedule.schedule", $param['order'][0]['dir']);
                break;

            case 3:
                $this->db->order_by("broadcast_schedule.title", $param['order'][0]['dir']);
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
        $participants = 0;
        $list = 0;
       
        $array_status = [];
        $kanban_communication = [];

        foreach ($data["others"] as $row) {

            foreach ($data["text"] as $key => $value) {

                if (isset($data['isPartial']) == 1) {

                    $idWorkTime['dayWeek'] = date('w', strtotime(str_replace("/", "-", $data['date_start'])));
                    if ($idWorkTime['dayWeek'] == 0) {
                        $idWorkTime['dayWeek'] = 7;
                    }

                    $getIdWork = $this->getChannels($row);

                    if ($getIdWork['id_work_time'] != '') {
                        $idWorkTime['workTime'] = $getIdWork['id_work_time'];
                    } else {
                        $idWorkTime['workTime'] = 0;
                    }

                    $hours = $this->getWorkTimeWeek($idWorkTime);
                    $hourStart = $this->getTimezoneChannel($data['date_start'], rtrim($hours['start'], ":00"), $row);
                    $hourEnd =  $this->getTimezoneChannel($data['date_start'], rtrim($hours['end'], ":00"), $row);
                    $typeLog = 6;
                }

                if (isset($data['isAfter']) == 1) {
                    $typeLog = 5;
                }

                $values = [
                    'creation' => $date->getTimestamp(),
                    'id_channel' => $row,
                    'title' => trim($data['input_title']),
                    'token' => Token(),
                    'schedule' => $this->checkScheduleHour($date->getTimestamp(), $this->getTimezoneChannel($data['date_start'], $data['time_start'], $row)),
                    'media_type' => !isset($data['media_type'][$key]) ? 1 : $data['media_type'][$key],
                    'media_caption' => !isset($data['media_caption']) ? "" : $data['media_caption'],
                    'media_title' => !isset($data['media_name'][$key]) ? null : $data['media_name'][$key],
                    'media_size' => !isset($data['byte'][$key]) ? 0 : $data['byte'][$key],
                    'media_duration' => !isset($data['media_duration']) ? 0 : $data['media_duration'],
                    'data' => $data['text'][$key],
                    'expire' => $this->getTimezoneChannel($data['date_start_validity'], $data['time_start_validity'], $row),
                    'count' => $participants == null ? 0 : 0,
                    'media_url' => !isset($data['files'][$key]) ? null : $data['files'][$key],
                    'is_wa_status' => 2,
                    'is_telegram_channel' => 1,
                    'status' => 3,
                    'submitted_approval' => 2,
                    'status_approval' => 1,
                    'is_limited_time' => isset($data['isPartial']) ? $data['isPartial'] : 2,
                    'limited_time_start' => isset($data['isPartial']) ? $hourStart : null,
                    'limited_time_end' => isset($data['isPartial']) ? $hourEnd : null
                ];

                $token = $values['token'];
                $return = $this->db->insert('broadcast_schedule', $values);
                $key_id = $this->db->insert_id();

                $values = [
                    'id_broadcast_schedule' => $this->db->insert_id(),
                    'participants' => $participants == null ? "" : $participants
                ];

                $return = $this->db->insert('broadcast_schedule_participants', $values);

                array_push($array_status, $return);

                CreateBroadcastLog($key_id, 1);

                if (isset($typeLog)) {
                    CreateBroadcastLog($key_id, $typeLog);
                }

                array_push($array_status, $return);

                $key_remote_id = $this->getChannels($row);

                $kanban_communication_elm = new stdClass();
                $kanban_communication_elm->key_remote_id = $key_remote_id["id"];
                $kanban_communication_elm->token = $token;

                array_push($kanban_communication, $kanban_communication_elm);
            }
        }

        $status = true;

        if (in_array(false, $array_status)) {
            $status = false;
        };

        $channels_names = $this->getChannelName($data["others"]);

        $ret = [
            "kanban_communication" => $kanban_communication,
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

    function CheckStatusToEdit($token)
    {
        $this->db->select('broadcast_schedule.status');
        $this->db->from('broadcast_schedule');
        $this->db->where('broadcast_schedule.token', $token);
        $status = $this->db->get()->result_array()[0]["status"];

        if ($status != '3')
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

        $interval = $schedule_date->getTimestamp() - $date->getTimestamp();

        if ($interval >= 3600) {
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
            broadcast_schedule.groups AS persona_id,
            group_contact.name AS persona_name,
            broadcast_schedule.title AS broadcast_title,
            broadcast_schedule.token,
            broadcast_schedule.media_type,
            broadcast_schedule.data,
            broadcast_schedule.media_url,
            broadcast_schedule.is_limited_time,
            CASE
                WHEN broadcast_schedule.expire = "0" THEN ""
                ELSE DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.expire), INTERVAL config.timezone HOUR), "%d/%m/%Y %H:%i")
            END expire,
            DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR), "%d/%m/%Y %H:%i") AS schedule,
            DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.creation), INTERVAL config.timezone HOUR), "%d/%m/%Y %H:%i") AS creation
        ');

        $this->db->from('broadcast_schedule');
        $this->db->join('channel', 'channel.id_channel = broadcast_schedule.id_channel');
        $this->db->join('group_contact', 'group_contact.id_group_contact = broadcast_schedule.groups', 'left');
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

        $this->db->select("
            channel.name
        ");

        $this->db->from("channel");
        $this->db->where_in("id_channel", $channels_ids);

        $result = $this->db->get()->result_array();
        $channels_names = $result;

        $channels_names_join = [];

        foreach ($channels_names as $value) {
            array_push($channels_names_join, $value['name']);
        }
        return $channels_names_join;
    }

    function getTimezoneChannel($date, $time, $id_channel)
    {

        if ($date == "" || $date == null) return 0;

        $this->db->select("
        config.timezone 
        ");

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
            CONCAT(channel.name, ' (', REPLACE(channel.id, '@c.us', ''), ')') AS name_channel,
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

        if (isset($data['isAfter']) == 1)
            $typeLog = 5;

        if (isset($data['isPartial']) == 1)
            $typeLog = 6;

        list($date_expire, $time_expire) = !empty($data['old_expire']) ? explode(" ", $data['old_expire']) : ["", ""];

        $old_expire = $this->getTimezoneChannel($date_expire, $time_expire, $data['id_channel']);
        $current_expire = $this->getTimezoneChannel($data['date_start_validity'], $data['time_start_validity'], $data['id_channel']);

        $values = [
            'title' => $data['input_title'],
            'schedule' => $this->checkScheduleHour($date->getTimestamp(), $this->getTimezoneChannel($data['date_start'], $data['time_start'], $data['id_channel'])),
            'data' => $data['text'],
            'expire' => $current_expire,
        ];

        $this->db->where('broadcast_schedule.token', $token);
        $return = $this->db->update('broadcast_schedule', $values);

        $key_id = $this->getIdBroadcastByToken($token);
        CreateBroadcastLog($key_id, 7);

        if (isset($typeLog))
            CreateBroadcastLog($key_id, $typeLog);

        $details = ["expire" => [
            "old" => $old_expire ? $this->getExpireDate($old_expire, $data['id_channel']) : null,
            "current" => $current_expire ? $this->getExpireDate($current_expire, $data['id_channel']) : null
        ]];

        $log_expire = $old_expire != $current_expire ? ($old_expire == 0 ? 11 : ($current_expire == 0 ? 12 : 13)) : null;

        if ($log_expire != null)
            CreateBroadcastLog($key_id, $log_expire, $this->JsonLog($log_expire, $details));

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
            $kanban_communication_elm->data->data = $data['text'];
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

    function getExpireDate($timestamp, $id_channel)
    {
        $this->db->select("config.timezone");
        $this->db->from("config");
        $this->db->where("config.id_channel", $id_channel);

        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            $timezone = $result->row()->timezone;

            $utc_date = new DateTime("@$timestamp");
            $utc_date->setTimezone(new DateTimeZone('UTC'));

            $sign = substr($timezone, 0, 1) === '-' ? '-' : '+';
            $hours = intval(substr($timezone, 1, 2));
            $minutes = intval(substr($timezone, 4, 2));

            $utc_date->modify("$sign$hours hours $minutes minutes");

            return $utc_date->format('d/m/Y H:i');
        }
    }

    function EditCampaignExpire($token, $data)
    {
        list($date_expire, $time_expire) = !empty($data['old_expire']) ? explode(" ", $data['old_expire']) : ["", ""];

        $old_expire = $this->getTimezoneChannel($date_expire, $time_expire, $data['id_channel']);
        $current_expire = $this->getTimezoneChannel($data['date_start_validity'], $data['time_start_validity'], $data['id_channel']);

        $this->db->where('broadcast_schedule.token', $token);
        $this->db->update('broadcast_schedule', ['expire' => $current_expire]);

        $details = ["expire" => [
            "old" => $old_expire ? $this->getExpireDate($old_expire, $data['id_channel']) : null,
            "current" => $current_expire ? $this->getExpireDate($current_expire, $data['id_channel']) : null
        ]];

        $key_id = $this->getIdBroadcastByToken($token);
        $log_expire = $old_expire != $current_expire ? ($old_expire == 0 ? 11 : ($current_expire == 0 ? 12 : 13)) : null;

        if ($log_expire != null)
            CreateBroadcastLog($key_id, $log_expire, $this->JsonLog($log_expire, $details));

        return;
    }

    function JsonLog($type, $details = "")
    {
        $data = [
            "action" => [
                "type" => $type
            ],
            "details" => $details
        ];

        return json_encode($data, JSON_PRETTY_PRINT);
    }

    function listGroups($data)
    {
        $this->db->select("group_contact.id_group_contact, group_contact.name");
        $this->db->from("group_contact");
        $this->db->where_in("group_contact.id_channel", implode(",", $data));
        $this->db->order_by("group_contact.name", "asc");

        return $this->db->get()->result_array();
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
        $this->db->where("broadcast_schedule.is_wa_status", 2);
        $this->db->where("broadcast_schedule.is_waba_broadcast", 2);
        $this->db->where("broadcast_schedule.is_wa_broadcast", 1);
        $this->db->where("broadcast_schedule.is_wa_community", 2);
        $this->db->group_by("broadcast_schedule.id_broadcast_schedule");
        $this->db->order_by("broadcast_schedule.id_broadcast_schedule", "desc");
        $this->db->limit(1);

        return $this->db->get()->result_array();
    }

    function queryExpireBroadcast()
    {
        $this->db->select("
            date_format( DATE_ADD(from_unixtime(max(broadcast_schedule.expire)), INTERVAL config.timezone HOUR),'%d/%m/%Y') date,
            date_format( DATE_ADD(from_unixtime(max(broadcast_schedule.expire)), INTERVAL config.timezone HOUR),'%H:%i') time
        ");
        $this->db->from("broadcast_schedule");

        $this->db->join("channel", "broadcast_schedule.id_channel = channel.id_channel", "inner");
        $this->db->join("config", "channel.id_channel = config.id_channel", "inner");

        $this->db->where_in("broadcast_schedule.status", array(1, 2, 3));
        $this->db->where("broadcast_schedule.is_wa_status", 2);
        $this->db->where("broadcast_schedule.is_waba_broadcast", 2);
        $this->db->where("broadcast_schedule.is_wa_broadcast", 1);
        $this->db->where("broadcast_schedule.is_wa_community", 2);
        $this->db->where("expire !=", 0);

        $this->db->group_by("broadcast_schedule.id_broadcast_schedule");
        $this->db->order_by("broadcast_schedule.id_broadcast_schedule", "desc");
        $this->db->limit(1);

        return $this->db->get()->result_array();
    }

    function queryChannel()
    {
        $this->db->select("channel.type, channel.id");
        $this->db->from("channel");
        $this->db->where("channel.type", 255);

        return $this->db->get()->result_array();
    }

    function FindDB($token_company)
    {

        $this->db->select("db");
        $this->db->from("talkall_admin.company");
        $this->db->where("token", $token_company);

        return $this->db->get()->result_array();
    }

    function prepareMessageBusiness($businessContent)
    {
        $this->load->helper('whatsapp_preview_helper.php');

        if ($businessContent['textInputData'][0] != '') {
            $textMessage = $this->createTextMessage($businessContent);
            $jsonObj = $textMessage;
            $businessContent['json'] = $jsonObj;
            sendCampaignPreview($businessContent);
        } else {
            $mediaMessage = $this->createMediaMessage($businessContent);
            $jsonObj = $mediaMessage;
            $businessContent['json'] = $jsonObj;
        }
    }

    function createTextMessage($businessContent)
    {
        $date = new DateTime();
        $timestamp = $date->getTimestamp();

        $msg = new stdClass();
        $msg->Cmd = "TextMessage";
        $msg->event = "PreviewMessage";
        $msg->timestamp = $timestamp;
        $msg->ta_key_id = strtoupper(random_string('alnum', 32));
        $msg->from = $businessContent['key_remote_id'] . '-' . $businessContent['id_channel'];
        $msg->data = $businessContent['textInputData'][0];
        $msg->channel_id = $businessContent['id_channel'];

        return json_encode($msg);
    }

    function createMediaMessage($businessContent)
    {
        if ($businessContent['urlFile'] != '') {
            for ($i = 0; $i < count($businessContent['urlFile']); $i++) {
                $businessContent['urlQueue'] = $businessContent['urlFile'][$i];
                $businessContent['type'] = $businessContent['media_type'][$i];
                $businessContent['text'] = $businessContent['textContent'][$i];
                $businessContent['cmdText'] = $businessContent['cmd'][$i];
                $businessContent['json'] = $this->generateJsonContentMediaMessage($businessContent);
                sendCampaignPreview($businessContent);
            }
        }
    }

    function generateJsonContentMediaMessage($businessContent)
    {
        $date = new DateTime();
        $timestamp = $date->getTimestamp();

        $msg = new stdClass();
        $msg->Cmd = $businessContent["cmdText"];
        $msg->event = "PreviewMessage";
        $msg->timestamp = $timestamp;
        $msg->key_id = strtoupper(random_string('alnum', 32));
        $msg->ta_key_id = strtoupper(random_string('alnum', 32));
        $msg->from = $businessContent['key_remote_id'] . '-' . $businessContent['id_channel'];
        $msg->media_caption = $businessContent['text'];
        $msg->media_name = $businessContent['text'];
        $msg->media_key = strtoupper(random_string('alnum', 32));
        $msg->media_url = $businessContent['urlQueue'];
        $msg->channel_id =  $businessContent['id_channel'];

        return json_encode($msg);
    }

    function pauseBroadcast($data)
    {
        $this->db->where_in('token', $data['tokens']);
        $this->db->update('broadcast_schedule', array("is_paused" => 1));
    }

    function resumeBroadcast($data)
    {
        $this->db->where_in('token', $data['tokens']);
        $this->db->update('broadcast_schedule', array("is_paused" => 2));

        return;
    }

    function getBroadastIdByToken($tokens)
    {
        $this->db->select('id_broadcast_schedule');
        $this->db->from('broadcast_schedule');
        $this->db->where_in('token', $tokens);

        return $this->db->get()->result_array();
    }

    function getChannelKeyRemoteIdByToken($token)
    {
        $this->db->select("channel.id");
        $this->db->from("channel");
        $this->db->join("broadcast_schedule", "channel.id_channel = broadcast_schedule.id_channel", "inner");
        $this->db->where("channel.status", 1);
        $this->db->where("broadcast_schedule.token", $token);

        return $this->db->get()->result_array()[0]["id"];
    }

    function getScheduleLog($token)
    {
        $this->db->select("
            broadcast_schedule_log.type as log_status, DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule_log.creation), INTERVAL config.timezone HOUR), '%d/%m/%Y %H:%i') log_creation,
            broadcast_schedule_log.creation as log_timestamp_creation,
            broadcast_schedule.token as token_schedule,
            broadcast_schedule_log.key_remote_id,
            broadcast_schedule_log.log,
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

        foreach ($data as $key => $value) {
            if ($data[$key]["log"] != null) {
                $data[$key]["log"] = json_decode($value["log"], true);
            }
        }

        return $data;
    }

    function ruleSchedule($data)
    {
        $channel = [];
        $idChannel = explode(",", $data['channels']);
        $info = [];

        foreach ($idChannel as $val) {
            $infoChannel = $this->getChannels($val);

            if (empty($data['persona'])) {
                $contacts = $this->countContacts($val);
            } else {
                $contacts = $this->getParticipants($data['persona']);
            }

            if ($data['count_doc'] > 1) {
                $contacts += $contacts;
            }

            $channel['infoChannel'] = $infoChannel;
            $channel['contacts'] = $contacts;

            if ($infoChannel['id_work_time'] != '')
                $channel['workTime'] = $infoChannel['id_work_time'];
            else
                $channel['workTime'] = 0;

            $channel['time_start'] = $data['time_start'];
            $channel['date_start'] = $data['date_start'];

            $queueRuleOne = $this->ruleOne($channel);

            if ($queueRuleOne['validate'] != false) {
                $validate = true;
            } else {
                $validate = false;
            }

            $channel['pass'] = [
                'id_channel' => $channel['infoChannel']['id_channel'],
                'channel' => $channel['infoChannel']['id'],
                'name' => $channel['infoChannel']['name'],
                'ruleOne' => $validate,
                'time_end' => $queueRuleOne['end']
            ];
            array_push($info, $channel);
        }
        return $info;
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

    function countContacts($idChannel)
    {
        $this->db->select("contact.id_contact");
        $this->db->from("contact");
        $this->db->where("contact.id_channel", $idChannel);
        $this->db->where("contact.deleted", 1);

        $result = $this->db->get();
        return count($result->result_array());
    }

    function getParticipants($idPersona)
    {
        $this->db->select('COUNT(id_contact) as contact_count');
        $this->db->from('contact_group');
        $this->db->where('id_group_contact', $idPersona);
        $query = $this->db->get();

        $result = $query->row();

        return $result->contact_count;
    }

    function ruleOne($info)
    {
        $info['dayWeek'] = date('w', strtotime(str_replace("/", "-", $info['date_start'])));
        if ($info['dayWeek'] == 0) {
            $info['dayWeek'] = 7;
        }

        $hourLimitDayWeek = $this->getWorkTimeWeek($info);

        $desired_start_time = strtotime($info['time_start']);
        $hour_start = strtotime($hourLimitDayWeek['start']);

        if ($desired_start_time < $hour_start) {
            $hourLimitDayWeek['validate'] = false;
            return $hourLimitDayWeek;
        }

        $limitDate = strtotime((str_replace("/", "-", $info['date_start'])) . " " . $hourLimitDayWeek['end']);
        $contacts = $info['contacts'];

        if ($contacts < 250) {
            $hour = "00";
            $minute = "08";
        } else {
            $amountQueues = floor($contacts / 250);
            $totalMinutes = floor($amountQueues * 8);
            $hour = floor($totalMinutes / 60);
            $minute = floor($totalMinutes % 60);
        }

        $totalTimeSendQueues = $hour . ":" . $minute . ":00";
        $campaignDate = (str_replace("/", "-", $info['date_start'])) . " " . $info['time_start'];

        list($h, $m, $s) = explode(':', $totalTimeSendQueues);
        $totalTime = strtotime(date('Y-m-d H:i:s', strtotime($campaignDate) + $s + ($m * 60) + ($h * 3600)));

        if ($totalTime > $limitDate) {
            $hourLimitDayWeek['validate'] = false;
            return $hourLimitDayWeek;
        } else {
            $hourLimitDayWeek['validate'] = true;
            return $hourLimitDayWeek;
        }
    }

    function checkCampaignOverlap($data)
    {
        $idChannel = explode(",", $data['channels']);
        $conflicts = [];

        foreach ($idChannel as $val) {

            if (empty($data['persona'])) {
                $contacts = $this->countContacts($val);
            } else {
                $contacts = $this->getParticipants($data['persona']);
            }

            if ($data['count_doc'] > 1) {
                $contacts += $contacts;
            }

            list($day, $month, $year) = explode('/', $data['date_start']);

            $campaignInfo = [
                'id_channel' => [$val],
                'dt_start' => $year . '-' . $month . '-' . $day
            ];

            $campaignScheduled = $this->getCampaignByDate($campaignInfo);

            if (!empty($campaignScheduled)) {

                $info = [];

                foreach ($campaignScheduled as $campaign) {
                    $info['scheduled_campaign'][] = [
                        'schedule' => $campaign->schedule,
                        'participants' => $campaign->count,
                        'channel_id' => $campaign->id,
                        'channel_name' => $campaign->name,
                        'is_wa_status' => $campaign->is_wa_status,
                        'is_wa_broadcast' => $campaign->is_wa_broadcast
                    ];
                }

                $info['new_campaign'] = [
                    'schedule' => strtotime((str_replace("/", "-", $data['date_start'])) . " " . $data['time_start']),
                    'dt_start' => $data['date_start'],
                    'time_start' => $data['time_start'],
                    'participants' => $contacts,
                    'is_wa_status' => $data['is_wa_status'] ?? 0,
                    'is_wa_broadcast' => $data['is_wa_broadcast'] ?? 0
                ];

                $has_conflict = $this->compareScheduledCampaign($info);

                if ($has_conflict) {
                    $conflicts[] = [
                        'channel_id' => $val,
                        'channel_name' => $campaign->name,
                        'conflict_info' => $has_conflict
                    ];
                }
            }
        }

        return $conflicts; // retorna todos os conflitos encontrados
    }

    function compareScheduledCampaign($data)
    {
        $conflicts = [];

        $new_campaign = $data['new_campaign'];
        $new_campaign_start = $new_campaign['schedule'];
        $new_campaign_participants = $new_campaign['participants'];

        // Calcula fim da nova campanha COM is_wa_status e is_wa_broadcast
        $new_campaign_end = $this->getEndTimestamp([
            'schedule' => $new_campaign_start,
            'participants' => $new_campaign_participants,
            'is_wa_status' => $new_campaign['is_wa_status'] ?? 0,
            'is_wa_broadcast' => $new_campaign['is_wa_broadcast'] ?? 0
        ]);

        foreach ($data['scheduled_campaign'] as $scheduled_campaign) {
            $scheduled_campaign_start = $scheduled_campaign['schedule'];
            $scheduled_campaign_participants = $scheduled_campaign['participants'];

            // Calcula fim da campanha agendada COM is_wa_status e is_wa_broadcast
            $scheduled_campaign_end = $this->getEndTimestamp([
                'schedule' => $scheduled_campaign_start,
                'participants' => $scheduled_campaign_participants,
                'is_wa_status' => $scheduled_campaign['is_wa_status'] ?? 0,
                'is_wa_broadcast' => $scheduled_campaign['is_wa_broadcast'] ?? 0
            ]);

            // Verifica conflito de horário
            if (
                ($new_campaign_start < $scheduled_campaign_end && $new_campaign_end > $scheduled_campaign_start) ||
                ($scheduled_campaign_start < $new_campaign_end && $scheduled_campaign_end > $new_campaign_start)
            ) {
                $key = $scheduled_campaign['channel_id'];
                if (!isset($conflicts[$key])) {
                    $conflicts[$key] = [
                        'channel_name' => $scheduled_campaign['channel_name'],
                        'channel_id' => $scheduled_campaign['channel_id']
                    ];
                }
            }
        }

        return array_values($conflicts);
    }

    function getEndTimestamp($campaign)
    {
        $queueSize = 250;

        if (isset($campaign['is_wa_status']) && isset($campaign['is_wa_broadcast'])) {
            if ($campaign['is_wa_status'] == 1 && $campaign['is_wa_broadcast'] == 2) {
                $queueSize = 2000;
            } elseif ($campaign['is_wa_status'] == 2 && $campaign['is_wa_broadcast'] == 1) {
                $queueSize = 250;
            }
        }

        if ($campaign['participants'] <= $queueSize) {
            $hour = 0;
            $minute = 8;
        } else {
            // Se passar do limite, conta quantas filas (grupos) de contatos tem
            $countQueues = ceil($campaign['participants'] / $queueSize);
            $totalMinutes = $countQueues * 8;
            $hour = floor($totalMinutes / 60);
            $minute = $totalMinutes % 60;
        }

        $totalTime = $campaign['schedule'] + ($hour * 3600) + ($minute * 60);
        return $totalTime;
    }

    function getCampaignByDate($data)
    {
        $this->db->select('broadcast_schedule.schedule, broadcast_schedule.count, channel.name, channel.id, broadcast_schedule.is_wa_status, broadcast_schedule.is_wa_broadcast');
        $this->db->from('broadcast_schedule');
        $this->db->join('channel', 'broadcast_schedule.id_channel = channel.id_channel');
        $this->db->join('config', 'channel.id_channel = config.id_channel');
        $this->db->where("DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR), '%Y-%m-%d') =", $data['dt_start']);
        $this->db->where_in('broadcast_schedule.status', [1, 3, 6]);
        $this->db->where_in('channel.id_channel', $data['id_channel']);

        $query = $this->db->get();

        return $query->result();
    }

    function getWorkTimeWeek($info)
    {
        $id = $info['workTime'];
        $dayWeek = $info['dayWeek'];


        $this->db->select("*");
        $this->db->from("work_time_week");
        $this->db->where("id_work_time", $id);
        $this->db->where("week", $dayWeek);

        $result = $this->db->get();

        if (isset($result->result_array()[0])) {
            return $result->result_array()[0];
        } else {
            return $result->result_array()[0] = ['end' => '23:59:00', 'start' => '06:00:00'];
        }
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

            if ($row->status == 5) {
                $newStatus = 3;
            } else {
                $newStatus = 6;
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
