<?php

class ReportChatbotAnalytical_model extends TA_model
{
    function Get($param)
    {
        $this->db->select('
            contact.full_name,
            contact.key_remote_id,
            channel.name,
            chat_list.id_chat_list,
            chatbot_interaction.options,
            FLOOR((UNIX_TIMESTAMP() - chatbot_interaction.creation) / 60) waiting_time,
            LEFT(TRIM(contact.full_name), 10) REGEXP "^[a-dXYZ]" AS ordem,
            LEFT(TRIM(UPPER(contact.full_name)), 10) AS first_name
        ');

        $this->db->from('chatbot_interaction');
        $this->db->join('contact', 'contact.key_remote_id = chatbot_interaction.key_remote_id');
        $this->db->join('channel', 'channel.id_channel = contact.id_channel');
        $this->db->join('chat_list', 'chat_list.id_contact = contact.id_contact');

        $this->db->where('contact.deleted', 1);
        $this->db->where('chatbot_interaction.is_open', 1);

        if (!empty($param['text'])) {
            $this->db->group_start();
            $this->db->like('contact.key_remote_id', $param['text']);
            $this->db->or_like('contact.full_name', $param['text']);
            $this->db->or_like('channel.name', $param['text']);
            $this->db->or_like('chatbot_interaction.options', $param['text']);
            $this->db->group_end();
        }

        if (!empty($param['channel'])) {
            $this->db->where_in('contact.id_channel', $param['channel']);
        }

        if (!empty($param['dt_start']) != "") {
            $this->db->where("DATE_FORMAT(from_unixtime(chatbot_interaction.creation), '%Y-%m-%d') BETWEEN '{$param['dt_start']}' AND '{$param['dt_end']}'", NULL, FALSE);
        }

        $count = $this->db->count_all_results('', false);

        switch ($param['order'][0]['column']) {
            case 0:
                $this->db->order_by('ordem', 'DESC');
                $this->db->order_by('first_name', $param['order'][0]['dir']);
                $this->db->order_by('contact.full_name', $param['order'][0]['dir']);
                break;

            case 1:
                $this->db->order_by('channel.name', $param['order'][0]['dir']);
                $this->db->order_by('contact.id_contact', 'asc');
                break;

            case 2:
                $this->db->order_by('chatbot_interaction.options', $param['order'][0]['dir']);
                break;

            case 3:
                $this->db->order_by('chatbot_interaction.creation', $param['order'][0]['dir']);
                break;

            default:
                $this->db->order_by('ordem', 'DESC');
                $this->db->order_by('first_name', $param['order'][0]['dir']);
                $this->db->order_by('contact.full_name', $param['order'][0]['dir']);
                break;
        }

        $this->db->limit($param['length'], $param['start']);

        return ['query' => $this->db->get()->result_array(), 'count' => $count];
    }

    function List()
    {
        $sql = "select\n";
        $sql .= "chat_list.id_chat_list,\n";
        $sql .= "chat_list_log.start,\n";
        $sql .= "DATE_FORMAT(DATE_ADD(from_unixtime(chat_list_log.start), INTERVAL config.timezone HOUR),'%d/%m/%Y %T') as creation,\n";
        $sql .= "contact.key_remote_id,\n";
        $sql .= "case\n";
        $sql .= "when contact.full_name is null then contact.key_remote_id else contact.full_name\n";
        $sql .= "end full_name,\n";
        $sql .= "'Em atendimento, Encerrado' labels_name,\n";
        $sql .= "'red,green' labels_color,\n";
        $sql .= "'1,2' labels_id,\n";
        $sql .= "user.last_name user,\n";
        $sql .= "case\n";
        $sql .= "when chat_list_log.end is null then 'Em atendimento'\n";
        $sql .= "else\n";
        $sql .= "SEC_TO_TIME(TIMESTAMPDIFF(second,from_unixtime(chat_list_log.start),from_unixtime(chat_list_log.end)))\n";
        $sql .= "end timediff,\n";
        $sql .= "case\n";
        $sql .= "when chat_list_log.end is null then 1 else 2\n";
        $sql .= "end status\n";
        $sql .= "from chat_list_log \n";
        $sql .= "inner join chat_list on chat_list_log.id_chat_list = chat_list.id_chat_list\n";
        $sql .= "inner join contact on chat_list.id_contact = contact.id_contact\n";
        $sql .= "inner join user on chat_list_log.key_remote_id = user.key_remote_id\n";
        $sql .= "inner join channel on chat_list.id_channel = channel.id_channel\n";
        $sql .= "inner join config on channel.id_channel = config.id_channel\n";
        $sql .= "order by chat_list_log.start desc, contact.id_contact\n";

        $result = $this->db->query($sql);

        $data = $result->result_array();

        return $data;
    }

    function getQuoted($quoted_id)
    {
        $sql = "SELECT\n";
        $sql .= "  CASE\n";
        $sql .= "    WHEN messages.media_type = 1 THEN SUBSTRING(messages.data, 1, 30)\n";
        $sql .= "    WHEN messages.media_type = 2 THEN DATE_FORMAT(SEC_TO_TIME(messages.media_duration), '%H:%i')\n";
        $sql .= "    WHEN messages.media_type = 3 THEN SUBSTRING(messages.media_caption, 1, 30)\n";
        $sql .= "    WHEN messages.media_type = 4 THEN SUBSTRING(messages.media_name, 1, 30)\n";
        $sql .= "    WHEN messages.media_type = 5 THEN CASE\n";
        $sql .= "      WHEN messages.media_caption = '0' THEN 'Vídeo'\n";
        $sql .= "      ELSE SUBSTRING(messages.media_caption, 1, 30)\n";
        $sql .= "    END\n";
        $sql .= "    WHEN messages.media_type = 6 THEN 'Localização'\n";
        $sql .= "    WHEN messages.media_type = 9 THEN messages.data\n";
        $sql .= "    WHEN messages.media_type = 26 THEN 'Figurinha'\n";
        $sql .= "    WHEN messages.media_type = 30 THEN messages.data\n";
        $sql .= "    WHEN messages.media_type = 35 THEN messages.data\n";
        $sql .= "  END AS data,\n";
        $sql .= "  messages.key_from_me,\n";
        $sql .= "  messages.media_type,\n";
        $sql .= "  messages.media_url,\n";
        $sql .= "  messages.thumb_image,\n";
        $sql .= "  template.buttons,\n";
        $sql .= "  (\n";
        $sql .= "    SELECT contact.full_name\n";
        $sql .= "    FROM contact\n";
        $sql .= "    WHERE contact.deleted = 1 AND contact.key_remote_id = messages.participant\n";
        $sql .= "    LIMIT 1\n";
        $sql .= "  ) AS participant\n";
        $sql .= "FROM messages\n";
        $sql .= "INNER JOIN chat_list ON messages.id_chat_list = chat_list.id_chat_list\n";
        $sql .= "INNER JOIN channel ON chat_list.id_channel = channel.id_channel\n";
        $sql .= "LEFT JOIN template ON template.name_to_request = messages.name\n";
        $sql .= "  AND template.account_key_remote_id = channel.id\n";
        $sql .= "  AND template.status = 2\n";
        $sql .= "WHERE messages.key_id = '" . $this->db->escape_str($quoted_id) . "'\n";

        $result = $this->db->query($sql);
        return $result->result_array();
    }

    function verifyOutNinetyDays($dt_start)
    {
        $sql = "SELECT 
                    messages.id_message
                FROM
                    messages
                WHERE
                    DATE_FORMAT(FROM_UNIXTIME(messages.creation),'%Y/%m/%d') < DATE_ADD(CURRENT_DATE(),INTERVAL - 90 DAY) LIMIT 1";

        $result = $this->db->query($sql);

        $difference  = strtotime(date('Y-m-d')) - strtotime($dt_start);
        $period = floor($difference / (60 * 60 * 24));

        if ($dt_start == "" || $period <= 90 || $result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function GetMessages($id_chat_list, $creation, $dt_start, $dt_end, $reverse)
    {
        $checkOutNinetyDays =  $this->verifyOutNinetyDays($dt_start);

        if ($checkOutNinetyDays) {

            $sql = "SELECT 
                    messages.creation,
                    messages.ta_key_id token,
                    messages.key_from_me,
                    messages.data,
                    messages.status,
                    messages.media_caption,
                    messages.media_type,
                    CASE
                        WHEN
                            messages.name IS NOT NULL
                        THEN
                            (SELECT 
                                    template.text_body                                    
                                FROM
                                    template
                                WHERE
                                    template.name_to_request = messages.name
                                        AND template.account_key_remote_id = channel.id
                                        AND template.status = 2)
                    END text_body,
                    CASE
                        WHEN
                            messages.name IS NOT NULL
                        THEN
                            (SELECT 
                                    template.buttons                                    
                                FROM
                                    template
                                WHERE
                                    template.name_to_request = messages.name
                                        AND template.account_key_remote_id = channel.id
                                        AND template.status = 2)
                    END buttons,
                    messages.media_mime_type,
                    messages.media_name,
                    messages.key_remote_id,
                    messages.thumb_image,
                    messages.file_name,
                    messages.media_duration,
                    messages.latitude,
                    messages.longitude,
                    messages.quoted_row_id,
                    messages.title,
                    messages.media_duration,
                    messages.page_count,
                    messages.media_url,
                    messages.name,
                    messages.participant,
                    messages.visible,
                    message_referral.source_url,
                    message_referral.source_id,
                    message_referral.source_type,
                    message_referral.headline,
                    message_referral.body,
                    message_referral.media_type media_type_ads,
                    message_referral.video_url,
                    message_referral.thumbnail_url,
                    messages.component components,
                    messages_reaction.emoji reaction
                FROM
                    chat_list
                        INNER JOIN
                    messages ON chat_list.id_chat_list = messages.id_chat_list
                        LEFT JOIN
                    message_referral ON messages.id_message = message_referral.id_message    
                        INNER JOIN
                    config ON chat_list.id_channel = config.id_channel
                        INNER JOIN
                    channel ON chat_list.id_channel = channel.id_channel
                      LEFT JOIN
                     messages_reaction ON messages.id_message = messages_reaction.id_message
                         AND messages_reaction.status = 1
                WHERE
                    chat_list.id_chat_list = '{$id_chat_list}'  AND messages.visible = 1\n";

            if ($creation != "0") {
                if ($reverse == "true") {
                    $sql .= "AND messages.creation >= '{$creation}' AND DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(messages.creation), INTERVAL config.timezone HOUR), '%Y-%m-%d') <= '{$dt_end}' ORDER BY messages.creation ASC LIMIT 0,30";
                } else {
                    $sql .= "AND messages.creation <= '{$creation}' ORDER BY messages.creation DESC, messages.id_message DESC LIMIT 0,30";
                }
            } else {
                if ($dt_start != "") {
                    $sql .= "AND DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(messages.creation), INTERVAL config.timezone HOUR), '%Y-%m-%d') BETWEEN '{$dt_start}' AND '{$dt_end}' ORDER BY messages.creation ASC LIMIT 0,30";
                } else {
                    $sql .= "ORDER BY messages.creation DESC, messages.id_message DESC LIMIT 0,30";
                }
            }

            $result = $this->db->query($sql);
            $data = $result->result_array();

            foreach ($data as &$row) {
                if ($row['quoted_row_id'] != "" && $row['quoted_row_id'] != null) {
                    $aQuoted = $this->getQuoted($row['quoted_row_id']);
                    $row['quoted'] = $aQuoted[0];
                    $row['participant'] = $aQuoted[0]['participant'];
                }
            }

            return $data;
        } else {
            return "false";
        }
    }

    function ConversationHistory($data)
    {
        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);

        $sql = "select id_contact from chat_list where id_chat_list = '" . $data['id_chat_list'] . "'\n";
        $result = $this->db->query($sql);

        $id_contact = $result->result_array()[0]['id_contact'];

        $sql = "select key_remote_id from contact where id_contact = '" . $id_contact . "'\n";
        $res = $this->db->query($sql);

        $key_remote_id = $res->result_array()[0]['key_remote_id'];

        $id_company = $_SESSION['id_company'];

        if (!empty($id_company)) {

            $sql = "select db, server from talkall_admin.company where id_company = '" . $id_company . "'\n";

            $res = $this->talkall_admin->query($sql);

            $date = new DateTime();
            $data['db'] = $res->result_array()[0]['db'];
            $data['host'] = $res->result_array()[0]['server'];
            $data['key_remote_id'] = $key_remote_id;
            $data['user_key_remote_id'] = $this->session->userdata('key_remote_id');

            $ci = &get_instance();

            $values = [
                'id_company' => $id_company,
                'creation' => $date->getTimestamp(),
                'banco' => $data['db'],
                'host' => $data['host'],
                'email' => $data['email'],
                'key_remote_id' => $data['key_remote_id'],
                'user_key_remote_id' => $data['user_key_remote_id'],
                'status' => 1,
                'ip' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => "{$ci->agent->browser()}, {$ci->agent->platform()}"
            ];

            $this->talkall_admin->insert('export_service', $values);

            return true;
        }
    }

    function ListChannels()
    {
        $sql = "SELECT * FROM channel WHERE channel.type IN (2, 8, 9, 10, 12, 16) AND channel.status = 1";

        $result = $this->db->query($sql);

        return $result->result_array();
    }
}
