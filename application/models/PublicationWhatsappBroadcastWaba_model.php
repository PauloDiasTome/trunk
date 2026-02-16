<?php

class PublicationWhatsappBroadcastWaba_model extends TA_model
{

    function Get($param)
    {
        $this->db->select("
            REPLACE(channel.id, '@c.us', '') as channel,
            DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.schedule), INTERVAL config.timezone HOUR),'%d/%m/%Y %T') as schedule,
            DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.creation), INTERVAL config.timezone HOUR),'%d/%m/%Y %T') as creation,
            CASE
                WHEN broadcast_schedule.expire = '0' THEN ''
                ELSE DATE_FORMAT(FROM_UNIXTIME(broadcast_schedule.expire),'%d/%m/%Y %H:%i')
            END as expire,
            broadcast_schedule.id_channel as id_channel,
            broadcast_schedule.status as status,
            broadcast_schedule.token as token,
            broadcast_schedule.title as title,
            broadcast_schedule.data as data,
            CONCAT(channel.name,' (',REPLACE(channel.id, '@c.us', ''),')') as name
        ");

        $this->db->from('broadcast_schedule');
        $this->db->join('channel', 'broadcast_schedule.id_channel = channel.id_channel', 'INNER');
        $this->db->join('config', 'channel.id_channel = config.id_channel', 'LEFT');

        $this->db->where('broadcast_schedule.is_waba_broadcast', 1);
        $this->db->where('broadcast_schedule.status !=', 7);
        $this->db->like("LOWER(CONCAT(broadcast_schedule.title, ' ', channel.id))", $this->db->escape_like_str(strtolower($param['text'])));

        if (!empty($param['channel'])) {
            $this->db->where_in('broadcast_schedule.id_channel', $param['channel']);
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

        $this->db->group_by('id_broadcast_schedule, channel, schedule, creation, id_channel, creation, data, title, token, expire, status, config.timezone');

        $count = $this->db->count_all_results('', false);

        switch ($param['order'][0]['column']) {
            case 1:
                $this->db->order_by('broadcast_schedule.schedule', $param['order'][0]['dir']);
                break;

            case 2:
                $this->db->order_by('name', $param['order'][0]['dir']);
                break;

            case 3:
                $this->db->order_by('broadcast_schedule.title', $param['order'][0]['dir']);
                break;

            default:
                $this->db->order_by('broadcast_schedule.id_broadcast_schedule', 'DESC');
        }

        $this->db->limit($param['length'], $param['start']);

        return ['query' => $this->db->get()->result_array(), 'count' => $count];
    }

    function Add($data)
    {
        $date = new DateTime();
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
        }

        if ($data["select_segmented_group"] == 0) {
            $this->db->query('SET SESSION group_concat_max_len = 1000000');
            $this->db->select('GROUP_CONCAT(DISTINCT contact.key_remote_id) as participants');
            $this->db->from('contact');
            $this->db->join('channel', 'contact.id_channel = channel.id_channel', 'INNER');
            $this->db->where('contact.deleted', 1);
            $this->db->where('contact.spam', 1);
            $this->db->where('channel.id_channel', $data['select_channel']);
            $this->db->where('contact.is_private', 1);
            $this->db->where('contact.is_group', 1);
            $this->db->where('channel.status', 1);
            $this->db->order_by('contact.creation');
            $this->db->limit(10000);

            $result = $this->db->get();

            if ($result->num_rows() > 0) {
                $participants = $result->row()->participants;
                $list = explode(",", $participants);
            }
        }

        $parametersHeaderType = $data["headerType"];
        $parametersBody = $data["bodyParameters"];
        $parametersButtons = $data["buttonsParameters"];

        $header = [
            "type" => "header",
            "parameters" => array()
        ];
        $body = [
            "type" => "body",
            "parameters" => array()
        ];


        $BodyComponents = array();

        if ($parametersHeaderType != "1" && $parametersHeaderType != "") {
            $urlHeader = $data["url_file"];
            $typeFile = "";
            $components = [];

            switch ($parametersHeaderType) {
                case '1':
                    $typeFile = "text";
                    $components = [
                        "type" => $typeFile,
                        $typeFile => [
                            "link" => $urlHeader
                        ]
                    ];
                    break;

                case '10':
                    $typeFile = "document";
                    $components = [
                        "type" => $typeFile,
                        $typeFile => [
                            "link" => $urlHeader,
                            "filename" => $data["media-name"] ?? ""
                        ]
                    ];
                    break;

                case '3':
                    $typeFile = "image";
                    $components = [
                        "type" => $typeFile,
                        $typeFile => [
                            "link" => $urlHeader
                        ]
                    ];
                    break;

                case '5':
                    $typeFile = "video";
                    $components = [
                        "type" => $typeFile,
                        $typeFile => [
                            "link" => $urlHeader
                        ]
                    ];
                    break;

                default:
                    $typeFile = "";
                    $components = [
                        "type" => $typeFile,
                        $typeFile => [
                            "link" => $urlHeader
                        ]
                    ];
                    break;
            }

            array_push($header["parameters"], $components);
            array_push($BodyComponents, $header);
        }

        if ($parametersBody > 0) {
            for ($i = 0; $i < $parametersBody; $i++) {
                $parameter = $data["select_type_parameters" . ($i + 1)];

                switch ($parameter) {
                    case 'text':
                    case 'clientsData':
                    case 'date':
                        $obj = [
                            "type" => "text",
                            "text" => $data["parametro" . ($i + 1)]
                        ];
                        array_push($body["parameters"], $obj);
                        break;

                    case 'currency':
                        $date_time = [
                            "fallback_value" => "0",
                            "code" => "USD",
                            "amount_1000" =>  str_replace(".", "", $data["parametro11"]) . "0"
                        ];

                        $obj = [
                            "type" => "currency",
                            "currency" => $date_time
                        ];

                        array_push($body["parameters"], $obj);

                        break;

                    default:
                        break;
                }
            }

            array_push($BodyComponents, $body);
        }

        if ($parametersButtons > 0) {

            for ($i = 0; $i < $parametersButtons; $i++) {

                if (isset($data["button_sub_type" . $i]) && $data["button_sub_type" . $i] != "PHONE_NUMBER") {
                    if (isset($data["parameter_button_url"])) {
                        $arrayObj = new stdClass;

                        $components = [
                            "type" => "button",
                            "sub_type" => "url",
                            "index" => strval($i),
                            "parameters" => array()
                        ];

                        $arrayObj = $components;

                        $obj = [
                            "type" => "text",
                            "text" => $data["parameter_button_url"]
                        ];

                        array_push($arrayObj["parameters"], $obj);
                        array_push($BodyComponents, $arrayObj);
                    }
                }
            }
        }

        $header_text = !empty($data['headerText']) ? $data['headerText'] : '';
        $header_placeholder = '';

        if (!empty($data['parametro1'])) $header_placeholder = $data['parametro1'];
        if (!empty($data['parametro11'])) $header_placeholder = $data['parametro11'];

        if (!empty($header_placeholder) && !empty($header_text)) {
            $header_components = $this->getHeaderComponents($header_placeholder);
            array_push($BodyComponents, $header_components);
        }

        $json = json_encode($BodyComponents);
        $data['schedule'] = $this->checkScheduleHour($date->getTimestamp(), $this->getTimezoneChannel($data['date_start'], $data['time_start'], $data['select_channel']));
        $data['token'] = Token();

        $values = [
            'creation' => $date->getTimestamp(),
            'id_channel' => $data['select_channel'],
            'title' => trim($data['input_title']),
            'token' => $data['token'],
            'schedule' => $data['schedule'],
            'media_type' => 27,
            'media_caption' => !isset($data['media_caption']) ? "" : $data['media_caption'],
            'media_size' => !isset($data['media_size']) ? 0 : $data['media_size'],
            'media_duration' => !isset($data['media_duration']) ? 0 : $data['media_duration'],
            'status' => 3,
            'count' => $participants == null ? 0 : count($list),
            'groups' => $data['select_segmented_group'],
            'is_waba_broadcast' => 1,
            'id_template' => $data['select_template'],
            'submitted_approval' => 2,
            'status_approval' => 1,
            'json_parameters' => $json
        ];

        $channel_info = $this->getChannels($values["id_channel"]);
        $token = $values['token'];
        $this->db->insert('broadcast_schedule', $values);
        $key_id = $this->db->insert_id();

        $values = [
            'id_broadcast_schedule' => $key_id,
            'participants' => $participants == null ? "" : $participants
        ];

        $this->db->insert('broadcast_schedule_participants', $values);

        $this->registerLog($key_id, 1);

        $this->RegisterBroadcastTalkallAdmin($data);

        $kanban_communication_elm = new stdClass();
        $kanban_communication_elm->key_remote_id = $channel_info["id"];
        $kanban_communication_elm->token = $token;
        $kanban_communication_elm->Cmd = 'createKanbanBroadcast';

        $ret = [
            "kanban_communication" => $kanban_communication_elm,
            "channels_names" => $channel_info['name'],
            "status" => true
        ];

        return $ret;
    }

    function RegisterBroadcastTalkallAdmin($data)
    {
        $channel = $this->getChannels($data['select_channel']);
        $this->talkall_admin = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), TRUE);

        $date = new DateTime();
        $values = [
            'creation' => $date->getTimestamp(),
            'schedule' => $data['schedule'],
            'title' => $data['input_title'],
            'token' => $data['token'],
            'channel_name' => $channel['name'],
            'channel_id' => $channel['id'],
            'type' => 5,
            'is_trial' => $this->session->userdata('is_trial'),
            'id_company' => $this->session->userdata('id_company'),
        ];

        $this->talkall_admin->insert("broadcast_schedule", $values);
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

    function getScheduleLog($token)
    {
        $this->db->select("
            broadcast_schedule_log.type as log_status, DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule_log.creation), INTERVAL config.timezone HOUR), '%d/%m/%Y %H:%i') log_creation,
            broadcast_schedule_log.creation as log_timestamp_creation,
            broadcast_schedule.token as token_schedule,
            user.name
        ");

        $this->db->from("broadcast_schedule_log");
        $this->db->join("broadcast_schedule", "broadcast_schedule_log.id_broadcast_schedule = broadcast_schedule.id_broadcast_schedule", "inner");
        $this->db->join("channel", "broadcast_schedule.id_channel = channel.id_channel", "inner");
        $this->db->join("config", "channel.id_channel = config.id_channel", "inner");
        $this->db->join("user", "broadcast_schedule_log.key_remote_id = user.key_remote_id", "inner");

        $this->db->where("broadcast_schedule.token", "$token");
        $this->db->order_by("broadcast_schedule_log.creation", "desc");
        $this->db->order_by("broadcast_schedule_log.id_broadcast_schedule_log", "desc");

        return $this->db->get()->result_array();
    }

    function getChannels($idChannel)
    {
        $this->db->select('
            channel.id,
            channel.id_channel,
            channel.id_work_time,
            channel.name
        ');

        $this->db->from('channel');

        $this->db->where('channel.id_channel', $idChannel);
        $this->db->where('channel.status', 1);

        return $this->db->get()->result_array()[0];
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
            broadcast_schedule.groups AS persona_id,
            group_contact.name AS persona_name,
            broadcast_schedule.title AS broadcast_title,
            broadcast_schedule.token,
            broadcast_schedule.media_type,
            broadcast_schedule.id_template,
            template.name AS template_name,
            broadcast_schedule.data,
            broadcast_schedule.json_parameters,
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
        $this->db->join('template', 'template.id_template = broadcast_schedule.id_template', 'left');
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

    function getChannelName($channel_id)
    {
        $this->db->select('channel.name');

        $this->db->from('channel');
        $this->db->where('id_channel', $channel_id);

        return $this->db->get()->result_array()[0]['name'];
    }

    function getTimezoneChannel($date, $time, $id_channel)
    {
        $this->db->select('config.timezone');

        $this->db->from('config');
        $this->db->where('id_channel', $id_channel);

        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            $timezone = $result->row()->timezone;

            $hour = ($timezone[0] === '-') ?
                "+" . intval(substr($timezone, 1, -3)) . " hour" :
                "-" . intval(substr($timezone, 1, -3)) . " hour";

            date_default_timezone_set("UTC");

            $americanData = explode('/', $date)[2] . "/" . explode('/', $date)[1] . "/" . explode('/', $date)[0];
            $broadcastDate = new DateTime($americanData . " " . $time . ":00");
            $broadcastDate->modify($hour);

            return strtotime($broadcastDate->format('Y-m-d H:i:s'));
        }
    }

    function View($key_id)
    {
        $this->db->select("
            broadcast_schedule.id_broadcast_schedule,
            REPLACE(channel.id, '@c.us', '') AS channel,
            DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR), '%d/%m/%Y %H:%i') AS schedule,
            DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.creation), INTERVAL config.timezone HOUR), '%d/%m/%Y %H:%i') AS creation,
            CASE
                WHEN broadcast_schedule.expire = '0' THEN ''
                ELSE DATE_FORMAT(FROM_UNIXTIME(broadcast_schedule.expire), '%d/%m/%Y %H:%i')
            END AS expire,
            broadcast_schedule.id_channel,
            broadcast_schedule.status,
            broadcast_schedule.token,
            broadcast_schedule.groups,
            group_contact.name AS group_name,
            broadcast_schedule.title,
            broadcast_schedule.id_template,
            broadcast_schedule.data,
            broadcast_schedule.json_parameters,
            template.name AS name_template,
            template.text_body,
            CONCAT(channel.name, ' (', REPLACE(channel.id, '@c.us', ''), ')') AS name_channel,
            user.name,
            user.key_remote_id
        ");

        $this->db->from('broadcast_schedule');
        $this->db->join('channel', 'broadcast_schedule.id_channel = channel.id_channel');
        $this->db->join('template', 'broadcast_schedule.id_template = template.id_template');
        $this->db->join('group_contact', 'broadcast_schedule.groups = group_contact.id_group_contact', 'LEFT');
        $this->db->join("broadcast_schedule_log", "broadcast_schedule.id_broadcast_schedule = broadcast_schedule_log.id_broadcast_schedule", "left");
        $this->db->join('config', 'channel.id_channel = config.id_channel');
        $this->db->join('user', 'broadcast_schedule_log.key_remote_id = user.key_remote_id', 'left');

        $this->db->where('broadcast_schedule.is_waba_broadcast', 1);
        $this->db->where('broadcast_schedule.token', $key_id);

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

        $values = [
            'title' => $data['input_title'],
            'schedule' => $this->checkScheduleHour($date->getTimestamp(), $this->getTimezoneChannel($data['date_start'], $data['time_start'], $data['id_channel']))
        ];

        $this->db->where('broadcast_schedule.token', $token);
        $return = $this->db->update('broadcast_schedule', $values);

        $key_id = $this->getIdBroadcastByToken($token);
        $this->registerLog($key_id, 7);

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

    function Cancel($token)
    {
        $this->load->helper('cancel_campaign_helper.php');
        $resp = cancelCampaing($token);

        if (isset($resp['success'])) {
            $key_id = $this->getIdBroadcastByToken($token);
            $this->registerLog($key_id, 4);
        }

        return $resp;
    }

    function CancelGroup($data)
    {
        $this->load->helper('cancel_campaign_helper.php');
        $resp = cancelCampaing($data);

        if (isset($resp['success'])) {
            foreach ($data['tokens'] as $token) {
                $key_id = $this->getIdBroadcastByToken($token);
                $this->registerLog($key_id, 4);
            }
        }

        return $resp;
    }

    function listChannel()
    {
        $this->db->select('id, id_channel, CONCAT(name, \' (\', REPLACE(id, \'@c.us\', \'\'), \')\') as name');

        $this->db->from('channel');
        $this->db->where('(type = 12 AND status = 1) OR (type = 16 AND status = 1)');

        return $this->db->get()->result_array();
    }

    function CalculateBalance($id_channel)
    {
        $subquery = $this->db->select(
            '
            SUM(CASE
                WHEN FROM_UNIXTIME(chat_list.last_timestamp_client) > DATE_SUB(CURDATE(), INTERVAL 1 DAY) THEN 1
            END) detro_da_janela_24h,
            SUM(CASE
                WHEN FROM_UNIXTIME(chat_list.last_timestamp_client) < DATE_SUB(CURDATE(), INTERVAL 1 DAY) THEN 1
            END) fora_da_janela_24h,
            (SELECT FORMAT(channel.user_initiated_price, 2)
            FROM channel
            WHERE channel.id_channel = 2 AND channel.status = 1) credit',
            false
        );

        $this->db->from('chat_list');
        $this->db->join('contact', 'chat_list.id_contact = contact.id_contact', 'inner');

        $this->db->where('chat_list.id_channel', 2);
        $this->db->get_compiled_select();

        $this->db->select('*, FORMAT(fora_da_janela_24h * credit, 2) as total', false)->from("($subquery) query", null, false);

        return $this->db->get()->result_array();
    }

    function listGroups($id_channel)
    {
        $this->db->select('group_contact.id_group_contact, group_contact.name');
        $this->db->from('group_contact');
        $this->db->where('group_contact.id_channel', $id_channel);
        $this->db->order_by('group_contact.name');

        return $this->db->get()->result_array();
    }

    function listTemplate()
    {
        $this->db->select('template.id_template, template.creation, template.name, template.text_body, template.text_footer, template.header, template.header_type, template.buttons, template.account_key_remote_id');
        $this->db->from('template');
        $this->db->where('template.status', 2);

        return $this->db->get()->result_array();
    }

    function getTemplate($id_template)
    {
        $this->db->select('template.id_template, template.creation, template.account_key_remote_id, template.namespace, template.language, template.category, template.name_to_request, template.name, template.text_body, template.text_footer, template.header_type, template.header, template.buttons');

        $this->db->from('template');
        $this->db->where('template.id_template', $id_template);

        return $this->db->get()->result_array();
    }

    function getGroupContactsFromGroupContact($group_contact_id)
    {
        $this->db->get_where('group_contact', array('id_group_contact' => $group_contact_id));

        return $this->db->get()->result_array();
    }

    function queryLastBroadcast()
    {

        $this->db->select("DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(MAX(broadcast_schedule.schedule)), INTERVAL config.timezone HOUR), '%d/%m/%Y') AS date, DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(MAX(broadcast_schedule.schedule)), INTERVAL config.timezone HOUR), '%H:%i') AS time");
        $this->db->from('broadcast_schedule');
        $this->db->join('channel', 'broadcast_schedule.id_channel = channel.id_channel');
        $this->db->join('config', 'channel.id_channel = config.id_channel');
        $this->db->where_in('broadcast_schedule.status', array(1, 2, 3));
        $this->db->where('broadcast_schedule.is_wa_status', 2);
        $this->db->where('broadcast_schedule.is_waba_broadcast', 1);
        $this->db->where('broadcast_schedule.is_wa_broadcast', 2);
        $this->db->where('broadcast_schedule.is_wa_community', 2);
        $this->db->group_by('broadcast_schedule.id_broadcast_schedule');
        $this->db->order_by('broadcast_schedule.id_broadcast_schedule', 'desc');
        $this->db->limit(1);

        return $this->db->get()->result_array();
    }

    function getChannelKeyRemoteIdByToken($token)
    {
        $this->db->select('
            channel.id
        ');

        $this->db->from('channel');
        $this->db->join('broadcast_schedule', 'channel.id_channel = broadcast_schedule.id_channel', 'INNER');

        $this->db->where('channel.status', 1);
        $this->db->where('broadcast_schedule.token', $token);

        return $this->db->get()->result_array()[0]['id'];
    }

    function prepareCampaignPreview($data)
    {
        if ($data['template']['header_type'] == 1 || $data['template']['header_type'] == "")
            $this->createTextPreview($data);
        else
            $this->createMediaPreview($data);
    }

    function createTextPreview($data)
    {
        $json_template = json_decode($this->createJsonTemplate($data), true);

        $body_components = $this->createBodyComponents($data);
        $button_components = $this->createButtonComponents($data);

        if ($body_components != "")
            array_push($json_template['component'], ["type" => "body", "parameters" => $body_components]);

        if ($button_components != "") {
            foreach ($button_components as $button) {
                array_push($json_template['component'], $button);
            }
        }

        $json_obj = json_encode($json_template);
        $data['json'] = $json_obj;
        $data['id_channel'] = $data['channel'];

        sendCampaignPreview($data);
    }

    function createMediaPreview($data)
    {
        $json_template = json_decode($this->createJsonTemplate($data), true);

        $header_components = $this->createHeaderComponents($data);
        $body_components = $this->createBodyComponents($data);
        $button_components = $this->createButtonComponents($data);

        if ($header_components != "")
            array_push($json_template['component'], $header_components);

        if ($body_components != "")
            array_push($json_template['component'], ["type" => "body", "parameters" => $body_components]);

        if ($button_components != "") {
            foreach ($button_components as $button) {
                array_push($json_template['component'], $button);
            }
        }

        $json_obj = json_encode($json_template);
        $data['json'] = $json_obj;
        $data['id_channel'] = $data['channel'];

        sendCampaignPreview($data);
    }

    function createJsonTemplate($data)
    {
        $date = new DateTime();
        $timestamp = $date->getTimestamp();

        $msg = new stdClass();
        $msg->Cmd = "TemplateMessage";
        $msg->event = "PreviewMessage";
        $msg->key_id = strtoupper(random_string('alnum', 32));
        $msg->ta_key_id = strtoupper(random_string('alnum', 32));
        $msg->to = $data['key_remote_id'] . '-' . $data['template']['account_key_remote_id'];
        $msg->fromMe = true;
        $msg->timestamp = $timestamp;
        $msg->namespace = $data['template']['namespace'];
        $msg->policy = "deterministic";
        $msg->language = $data['template']['language'];
        $msg->category = $data['template']['category'];
        $msg->name = $data['template']['name_to_request'];
        $msg->text_body = str_replace(["\r\n", "\r", "\n"], "\\n", $data['template']['text_body']);
        $msg->text_footer = $data['template']['text_footer'];
        $msg->buttons = json_decode($data['template']['buttons'], true);
        $msg->header = $data['template']['header'];
        $msg->component = [];

        return json_encode($msg);
    }

    function createHeaderComponents($data)
    {
        if (!isset($data["url_file"]) || $data["url_file"] == "")
            return [];

        $header_type = $this->getHeaderType($data['template']['header_type']);

        $components = [
            "type" => "header",
            "parameters" => [[
                "type" => $header_type,
                "$header_type" => ["link" => $data['url_file']]
            ]]
        ];

        return $components;
    }

    function createBodyComponents($data)
    {
        if (!isset($data["selects"]) && !isset($data["parameters"]))
            return [];

        $components = [];

        if (isset($data["selects"]) && $data["selects"] != "") {
            $select = $this->selectComponents($data);
            foreach ($select as $component) {
                $components[] = $component;
            }
        }

        if (isset($data["parameters"]) && $data["parameters"] != "") {
            $parameter = $this->parameterComponents($data);
            foreach ($parameter as $component) {
                $components[] = $component;
            }
        }

        return $components;
    }

    function createButtonComponents($data)
    {
        if (!isset($data["buttons"]) || $data["buttons"] == "")
            return [];

        $components = [];
        $params = $data["buttons"];
        $index_params = 0;

        foreach (json_decode($data["template"]["buttons"], true) as $index => $component) {

            switch ($component['type']) {
                case 'URL':
                    $components[] = [
                        "type" => "button",
                        "sub_type" => 'url',
                        "index" => $index,
                        "parameters" => [[
                            "type" => "text",
                            "text" => $params[$index_params]
                        ]]
                    ];
                    $index_params++;
                    break;
                default:
                    break;
            }
        }
        return $components;
    }

    function selectComponents($data)
    {
        $components = [];

        foreach ($data["selects"] as $component) {
            if ($component == "%numero%") {
                $components[] = [
                    "type" => "text",
                    "text" => $data["key_remote_id"]
                ];
            }
            if ($component == "%nome%") {
                $components[] = [
                    "type" => "text",
                    "text" => $data["contacts"]["full_name"]
                ];
            }
            if ($component == "%email%") {
                $components[] = [
                    "type" => "text",
                    "text" => $data["contacts"]["email"] == "" ? $data["key_remote_id"] : $data["contacts"]["email"]
                ];
            }
        }

        return $components;
    }

    function parameterComponents($data)
    {
        $components = [];

        foreach ($data['parameters'] as $component) {
            $components[] = [
                "type" => "text",
                "text" => $component
            ];
        }

        return $components;
    }

    function getHeaderType($data)
    {
        $values = [
            3 => "image",
            5 => "video",
            10 => "document"
        ];
        $header_type = $values;

        return $header_type[$data];
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

            $new_key = token();
            $this->db->where('broadcast_send.token', $token);
            $this->db->update('broadcast_send', [
                'status' => 1,
                'key_id' => $new_key
            ]);

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

    function getHeaderComponents($header_placeholder)
    {

        $components = [
            "type" => "HEADER",
            "parameters" => [[
                "type" => "TEXT",
                "text" => "$header_placeholder"
            ]]
        ];


        return $components;
    }
}
