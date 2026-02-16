<?php

class ReportCall_model extends TA_model
{
    function Get($text, $channel, $label, $user, $sector, $category, $situation, $dt_start, $dt_end, $start, $length, $order_column, $order_dir)
    {
        $sectors = "";
        $labels = "";
        $channels = "";
        $categories = "";
        $no_category = false;
        $total_categories = 0;

        if ($sector != "NULL") {
            foreach ($sector as $key => $val) {
                $sectors .= $sector[$key];
                if ($val != end($sector)) {
                    $sectors .= ",";
                }
            }
        }

        if ($label != "NULL") {
            foreach ($label as $key => $val) {
                $labels .= $label[$key];
                if ($val != end($label)) {
                    $labels .= ",";
                }
            }
        }

        if ($channel != "NULL") {
            foreach ($channel as $key => $val) {
                $channels .= $channel[$key];
                if ($val != end($channel)) {
                    $channels .= ",";
                }
            }
        }

        if ($category != "NULL") {
            foreach ($category as $key => $val) {

                if ($category[$key] === 'no_category') {
                    $no_category = true;
                    $category[$key] = 0;
                    $categories .= $category[$key];

                    if (count($category) == 1) {
                        $total_categories = 1;
                    }
                } else {
                    $categories .= $category[$key];
                }

                if ($val != end($category)) {
                    $categories .= ",";
                }
            }
        }

        $sql = "SELECT 
                     channel.name AS channel_name,
                     chat_list_log.protocol,
                     chat_list_log.start,
                     chat_list_log.rating,
                     chat_list_log.feedback,
                     chat_list.id_chat_list,
                     contact.key_remote_id AS key_remote_id,
                     user.last_name AS user,
                     user_group.name AS group_name,
                     DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(chat_list_log.start), INTERVAL config.timezone HOUR), '%d/%m/%Y %T') AS creation,
                     CASE
                         WHEN contact.full_name IS NULL THEN contact.key_remote_id
                         ELSE contact.full_name
                     END AS full_name,
                     CASE
                     WHEN chat_list_log.end IS NULL THEN
                             TIMESTAMPDIFF(MINUTE, DATE_FORMAT(FROM_UNIXTIME(chat_list_log.start), '%Y-%m-%d %H:%i:%s'), CURRENT_TIMESTAMP())
                     ELSE
                         TIMESTAMPDIFF(MINUTE, DATE_FORMAT(FROM_UNIXTIME(chat_list_log.start), '%Y-%m-%d %H:%i:%s'), 
                              DATE_FORMAT(FROM_UNIXTIME(chat_list_log.end), '%Y-%m-%d %H:%i:%s'))
                     END AS minutes,
                     CASE
                         WHEN chat_list_log.end IS NULL THEN 1
                         ELSE 2
                     END AS status,
                     GROUP_CONCAT(DISTINCT label.name, '/', label.color) AS labels
                FROM
                     chat_list_log
                     INNER JOIN chat_list ON chat_list_log.id_chat_list = chat_list.id_chat_list
                     INNER JOIN contact ON chat_list.id_contact = contact.id_contact
                     INNER JOIN user ON chat_list_log.key_remote_id = user.key_remote_id
                     INNER JOIN user_group ON user_group.id_user_group = user.id_user_group
                     INNER JOIN channel ON chat_list.id_channel = channel.id_channel
                     INNER JOIN config ON channel.id_channel = config.id_channel
                     LEFT JOIN chat_list_label ON chat_list_label.id_chat_list = chat_list.id_chat_list
                     LEFT JOIN label ON chat_list_label.id_label = label.id_label
            WHERE 
                ((contact.key_remote_id LIKE '%$text%') 
                    OR (LOWER(user.last_name) LIKE LOWER('%$text%'))
                    OR (LOWER(contact.full_name) LIKE LOWER('%$text%'))
                OR (LOWER(chat_list_log.protocol) LIKE LOWER('%$text%'))
                    OR (CASE              
                            WHEN 'em atendimento' = LOWER('$text') THEN chat_list_log.end IS NULL
                            WHEN 'encerrado' = LOWER('$text') THEN chat_list_log.end IS NOT NULL
                        END)
                OR (LOWER(user_group.name) LIKE LOWER('%$text%'))) 
                AND contact.deleted = 1\n";

        if ($dt_start != "") {
            $sql .= "AND chat_list.is_private = 1 AND DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(chat_list_log.start), INTERVAL config.timezone HOUR),'%Y-%m-%d') BETWEEN '$dt_start' AND '$dt_end'\n";
        }

        if (!empty($labels)) {
            $sql .= "AND chat_list.id_chat_list IN (SELECT DISTINCT(id_chat_list) FROM chat_list_label WHERE id_label IN ($labels))\n";
        }

        if (!empty($user)) {
            $sql .= "AND user.id_user IN ($user)\n";
        }

        if (!empty($sectors)) {
            $sql .= "AND user_group.id_user_group IN ($sectors)\n";
        }

        if (!empty($channels)) {
            $sql .= "AND channel.id_channel IN ($channels)\n";
        }

        if ($no_category) {
            if ($total_categories == 1) {
                $sql .= "AND chat_list_log.id_category IS NULL\n";
            } else {
                $sql .= "AND (chat_list_log.id_category IN ($categories) OR chat_list_log.id_category IS NULL)\n";
            }
        }

        if (!$no_category && !empty($categories)) {
            $sql .= "AND chat_list_log.id_category IN ($categories)\n";
        }

        if (!empty($situation)) {
            switch ($situation) {
                case "1":
                    $sql .= "AND chat_list_log.end IS NOT NULL\n";
                    break;
                case "2":
                    $sql .= "AND chat_list_log.end IS NULL\n";
                    break;
            }
        }

        $sql .= "GROUP BY 
                channel.name, 
                chat_list_log.protocol, 
                chat_list_log.start, 
                chat_list_log.rating,
                chat_list_log.feedback,
                chat_list.id_chat_list, 
                contact.key_remote_id, 
                user_group.name, 
                contact.full_name, 
                chat_list_log.end, 
                user.last_name, 
                chat_list_log.creation\n";

        switch ($order_column) {
            case 0:
                $sql .= "ORDER BY chat_list_log.creation $order_dir\n";
                break;
            case 1:
                $sql .= "ORDER BY LOWER(chat_list_log.protocol) $order_dir\n";
                break;
            case 2:
                $sql .= "ORDER BY LOWER(contact.full_name) $order_dir\n";
                break;
            case 4:
                $sql .= "ORDER BY LOWER(user.last_name) $order_dir\n";
                break;
            case 5:
                $sql .= "ORDER BY LOWER(channel.name) $order_dir\n";
                break;
            case 6:
                $sql .= "ORDER BY LOWER(chat_list_log.rating) $order_dir\n";
                break;
        }

        $sql .= "LIMIT {$start}, {$length}\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }

    function Count($text, $channel, $label, $user, $sector, $categories, $situation, $dt_start, $dt_end)
    {
        $sectors = "";
        $labels = "";
        $channels = "";
        $categories_sql = "";
        $no_category = false;
        $total_categories = 0;

        if ($sector != "NULL") {
            $sectors = implode(",", $sector);
        }

        if ($label != "NULL") {
            $labels = implode(",", $label);
        }

        if ($channel != "NULL") {
            $channels = implode(",", $channel);
        }

        if ($categories != "NULL") {

            if (in_array('no_category', $categories)) {
                $no_category = true;

                if (count($categories) == 1) {
                    $total_categories = 1;
                }
            }

            $categories_sql = implode(",", $categories);
            $categories_sql = str_replace('no_category', '0', $categories_sql);
        }

        $sql = "SELECT COUNT(*) AS total FROM (
            SELECT chat_list_log.id_chat_list
                FROM
                    chat_list_log
                INNER JOIN chat_list ON chat_list_log.id_chat_list = chat_list.id_chat_list
                INNER JOIN contact ON chat_list.id_contact = contact.id_contact
                INNER JOIN user ON chat_list_log.key_remote_id = user.key_remote_id
                INNER JOIN user_group ON user_group.id_user_group = user.id_user_group
                INNER JOIN channel ON chat_list.id_channel = channel.id_channel
                INNER JOIN config ON channel.id_channel = config.id_channel
                LEFT JOIN chat_list_label ON chat_list_label.id_chat_list = chat_list.id_chat_list
                LEFT JOIN label ON chat_list_label.id_label = label.id_label
            WHERE 
                contact.deleted = 1 AND (
                    contact.key_remote_id LIKE '%$text%' OR
                    LOWER(user.last_name) LIKE LOWER('%$text%') OR
                    LOWER(contact.full_name) LIKE LOWER('%$text%') OR
                    LOWER(chat_list_log.protocol) LIKE LOWER('%$text%') OR
                    LOWER(user_group.name) LIKE LOWER('%$text%') OR
                    (('em atendimento' = LOWER('$text') AND chat_list_log.end IS NULL) OR 
                    ('encerrado' = LOWER('$text') AND chat_list_log.end IS NOT NULL))
                )\n";

        if ($dt_start != "") {
            $sql .= "AND chat_list.is_private = 1 AND DATE_FORMAT(DATE_ADD(from_unixtime(chat_list_log.start), INTERVAL config.timezone HOUR),'%Y-%m-%d') BETWEEN '$dt_start' AND '$dt_end'\n";
        }

        if (!empty($labels)) {
            $sql .= "AND chat_list.id_chat_list IN (SELECT DISTINCT(id_chat_list) FROM chat_list_label WHERE id_label IN ($labels))\n";
        }

        if (!empty($user)) {
            $sql .= "AND user.id_user IN ($user)\n";
        }

        if (!empty($sectors)) {
            $sql .= "AND user_group.id_user_group IN ($sectors)\n";
        }

        if (!empty($channels)) {
            $sql .= "AND channel.id_channel IN ($channels)\n";
        }

        if (!empty($categories_sql) && !$no_category) {
            $sql .= "AND chat_list_log.id_category IN ($categories_sql)\n";
        }

        if ($no_category) {
            if ($total_categories == 1) {
                $sql .= "AND chat_list_log.id_category IS NULL\n";
            } else {
                $sql .= "AND (chat_list_log.id_category IN ($categories_sql) OR chat_list_log.id_category IS NULL)\n";
            }
        }

        if (!empty($situation)) {
            switch ($situation) {
                case "1":
                    $sql .= "AND chat_list_log.end IS NOT NULL\n";
                    break;
                case "2":
                    $sql .= "AND chat_list_log.end IS NULL\n";
                    break;
            }
        }

        $sql .= "GROUP BY 
            channel.name, 
            chat_list_log.protocol, 
            chat_list_log.start, 
            chat_list.id_chat_list, 
            contact.key_remote_id, 
            user_group.name, 
            contact.full_name, 
            chat_list_log.end, 
            user.last_name, 
            chat_list_log.creation
            ) AS sub";

        $result = $this->db->query($sql)->row();
        return $result->total ?? 0;
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

    function  verifyOutNinetyDays($dt_start)
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


    function GetMessages($post)
    {
        $id_chat_list = $post['id'] ?? "";
        $protocol = $post['protocol'] ?? "";
        $dt_start = $post['dt_start'] ?? "";
        $dt_end = $post['dt_end'] ?? "";
        $reverse = $post['reverse'] ?? "";
        $creation = $post['creation'] ?? "";

        $checkOutNinetyDays =  $this->verifyOutNinetyDays($dt_start);

        if ($checkOutNinetyDays) {

            $sql = "SELECT 
                messages.id_chat_list,
                messages.creation,
                messages.ta_key_id AS token,
                messages.key_from_me,
                messages.data,
                messages.status,
                messages.media_caption,
                messages.media_type,
                CASE
                    WHEN messages.name IS NOT NULL THEN
                        (SELECT template.text_body
                        FROM template
                        WHERE template.name_to_request = messages.name
                        AND template.account_key_remote_id = channel.id
                        AND template.status = 2
                        LIMIT 1)
                END AS text_body,
                 CASE
                     WHEN messages.name IS NOT NULL
                    THEN
                        (SELECT template.header
                        FROM template
                        WHERE template.name_to_request = messages.name
                        AND template.account_key_remote_id = channel.id
                        AND template.status = 2)
                    END AS header,
                CASE
                    WHEN messages.name IS NOT NULL THEN
                        (SELECT template.buttons
                        FROM template
                        WHERE template.name_to_request = messages.name
                        AND template.account_key_remote_id = channel.id
                        AND template.status = 2
                        LIMIT 1)
                END AS buttons,
                CASE
                    WHEN messages.key_from_me = 2 THEN user.last_name
                END AS user_name,
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
                        LEFT JOIN
                    user ON messages.key_remote_id = user.key_remote_id
                        INNER JOIN
                    config ON chat_list.id_channel = config.id_channel
                        INNER JOIN
                    channel ON chat_list.id_channel = channel.id_channel
                      LEFT JOIN
                     messages_reaction ON messages.id_message = messages_reaction.id_message
                         AND messages_reaction.status = 1
                WHERE
                    chat_list.id_chat_list = '{$id_chat_list}' AND messages.visible = 1\n";

            if (isset($protocol) && !empty($protocol)) {
                $sql .= "AND messages.id_message >= (
                                SELECT m2.id_message
                                FROM messages m2
                                WHERE m2.data = '{$protocol}'
                                AND m2.id_chat_list = chat_list.id_chat_list
                                ORDER BY m2.id_message
                                LIMIT 1
                            )";
                $sql .= "ORDER BY messages.id_message ASC LIMIT 30";
            }

            if ($creation != "0" && !empty($creation)) {
                if ($reverse == 'false') {
                    $sql .= "AND messages.creation <= '{$creation}' AND DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(messages.creation), INTERVAL config.timezone HOUR), '%Y-%m-%d') ORDER BY messages.creation DESC LIMIT 30";
                } else {
                    $sql .= "AND messages.creation >= '{$creation}' AND DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(messages.creation), INTERVAL config.timezone HOUR), '%Y-%m-%d') ORDER BY messages.creation, messages.id_message DESC LIMIT 30";
                }
            }

            if ($creation == '0' && empty($protocol)) {
                if ($dt_start != "") {
                    $sql .= "AND DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(messages.creation), INTERVAL config.timezone HOUR), '%Y-%m-%d') BETWEEN '{$dt_start}' AND '{$dt_end}' ORDER BY messages.creation ASC LIMIT 0,30";
                } else {
                    $sql .= "ORDER BY messages.creation DESC, messages.id_message DESC LIMIT 0,30";
                }
            }
            
            $result = $this->db->query($sql);
            $data = $result->result_array();

            if (count($data) > 0) {

                if (count($data) <= 13 && $creation == '0' && !empty($dt_start)) {
                    $data = $this->getMoreMessagesDtStart($id_chat_list, $dt_start);
                }

                if (count($data) <= 13 && isset($protocol) && !empty($protocol)) {
                    $data = $this->getMoreMessages($id_chat_list, $protocol);
                }
            }

            foreach ($data as &$row) {
                if ($row['quoted_row_id'] != "" && $row['quoted_row_id'] != null) {
                    $aQuoted = $this->getQuoted($row['quoted_row_id']);
                    $row['quoted'] = $aQuoted[0] ?? null;
                    $row['participant'] = $aQuoted[0]['participant'] ?? null;
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

    function ListUsers()
    {
        $sql = "SELECT * FROM user WHERE user.status = 1 ORDER BY user.last_name ASC";

        $result = $this->db->query($sql);

        return $result->result_array();
    }

    function ListLabels()
    {
        $sql = "SELECT * FROM label";

        $result = $this->db->query($sql);

        return $result->result_array();
    }

    function ListChannels()
    {
        $sql = "SELECT * FROM channel WHERE channel.type IN (2, 8, 9, 10, 12, 16) AND channel.status = 1";

        $result = $this->db->query($sql);

        return $result->result_array();
    }

    function ListCategories()
    {
        $sql = "SELECT * FROM category";

        $result = $this->db->query($sql);

        return $result->result_array();
    }

    function getMoreMessages($id_chat_list, $protocol)
    {
        $sql = "SELECT 
                messages.id_chat_list,
                messages.creation,
                messages.ta_key_id AS token,
                messages.key_from_me,
                messages.data,
                messages.status,
                messages.media_caption,
                messages.media_type,
                CASE
                    WHEN messages.name IS NOT NULL THEN
                        (SELECT template.text_body
                        FROM template
                        WHERE template.name_to_request = messages.name
                        AND template.account_key_remote_id = channel.id
                        AND template.status = 2
                        LIMIT 1)
                END AS text_body,
                 CASE
                     WHEN messages.name IS NOT NULL
                    THEN
                        (SELECT template.header
                        FROM template
                        WHERE template.name_to_request = messages.name
                        AND template.account_key_remote_id = channel.id
                        AND template.status = 2)
                    END AS header,
                CASE
                    WHEN messages.name IS NOT NULL THEN
                        (SELECT template.buttons
                        FROM template
                        WHERE template.name_to_request = messages.name
                        AND template.account_key_remote_id = channel.id
                        AND template.status = 2
                        LIMIT 1)
                END AS buttons,
                CASE
                    WHEN messages.key_from_me = 2 THEN user.last_name
                END AS user_name,
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
                message_referral.media_type AS media_type_ads,
                message_referral.video_url,
                message_referral.thumbnail_url,
                messages.component AS components,
                messages_reaction.emoji AS reaction
            FROM (
                (SELECT *
                FROM messages
                WHERE id_chat_list = '$id_chat_list'
                AND visible = 1
                AND id_message < (
                        SELECT id_message
                        FROM messages
                        WHERE data = '$protocol'
                        AND id_chat_list = '$id_chat_list'
                        ORDER BY id_message
                        LIMIT 1
                )
                ORDER BY id_message DESC
                LIMIT 14)

                UNION ALL
            
                (SELECT *
                FROM messages
                WHERE id_chat_list = '$id_chat_list'
                AND visible = 1
                AND id_message = (
                        SELECT id_message
                        FROM messages
                        WHERE data = '$protocol'
                        AND id_chat_list = '$id_chat_list'
                        ORDER BY id_message
                        LIMIT 1
                ))

                UNION ALL

                (SELECT *
                FROM messages
                WHERE id_chat_list = '$id_chat_list'
                AND visible = 1
                AND id_message > (
                        SELECT id_message
                        FROM messages
                        WHERE data = '$protocol'
                        AND id_chat_list = '$id_chat_list'
                        ORDER BY id_message
                        LIMIT 1
                )
                ORDER BY id_message ASC
                LIMIT 30)
            ) AS messages
            LEFT JOIN message_referral 
                ON messages.id_message = message_referral.id_message
            LEFT JOIN user 
                ON messages.key_remote_id = user.key_remote_id
            INNER JOIN chat_list 
                ON chat_list.id_chat_list = messages.id_chat_list
            INNER JOIN config 
                ON chat_list.id_channel = config.id_channel
            INNER JOIN channel 
                ON chat_list.id_channel = channel.id_channel
            LEFT JOIN messages_reaction 
                ON messages.id_message = messages_reaction.id_message 
            AND messages_reaction.status = 1
            ORDER BY messages.id_message ASC";

        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result ?? [];
    }

    function getMoreMessagesDtStart($id_chat_list, $dt_start)
    {
        $sql = "SELECT 
                messages.id_chat_list,
                messages.creation,
                messages.ta_key_id AS token,
                messages.key_from_me,
                messages.data,
                messages.status,
                messages.media_caption,
                messages.media_type,
                CASE
                    WHEN messages.name IS NOT NULL THEN
                        (SELECT template.text_body
                        FROM template
                        WHERE template.name_to_request = messages.name
                        AND template.account_key_remote_id = channel.id
                        AND template.status = 2
                        LIMIT 1)
                END AS text_body,
                 CASE
                     WHEN messages.name IS NOT NULL
                    THEN
                        (SELECT template.header
                        FROM template
                        WHERE template.name_to_request = messages.name
                        AND template.account_key_remote_id = channel.id
                        AND template.status = 2)
                    END AS header,
                CASE
                    WHEN messages.name IS NOT NULL THEN
                        (SELECT template.buttons
                        FROM template
                        WHERE template.name_to_request = messages.name
                        AND template.account_key_remote_id = channel.id
                        AND template.status = 2
                        LIMIT 1)
                END AS buttons,
                CASE
                    WHEN messages.key_from_me = 2 THEN user.last_name
                END AS user_name,
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
                message_referral.media_type AS media_type_ads,
                message_referral.video_url,
                message_referral.thumbnail_url,
                messages.component AS components,
                messages_reaction.emoji AS reaction
            FROM (
                (SELECT m.*
                FROM messages m
                INNER JOIN chat_list cl ON cl.id_chat_list = m.id_chat_list
                INNER JOIN config c ON cl.id_channel = c.id_channel
                WHERE m.id_chat_list = '$id_chat_list'
                AND m.visible = 1
                AND m.id_message < (
                        SELECT m2.id_message
                        FROM messages m2
                        INNER JOIN chat_list cl2 ON cl2.id_chat_list = m2.id_chat_list
                        INNER JOIN config c2 ON cl2.id_channel = c2.id_channel
                        WHERE DATE_FORMAT(
                                DATE_ADD(
                                    FROM_UNIXTIME(m2.creation),
                                    INTERVAL TIME_TO_SEC(c2.timezone) SECOND
                                ),
                                '%Y-%m-%d'
                            ) >= '{$dt_start}'
                        AND m2.id_chat_list = '$id_chat_list'
                        ORDER BY m2.id_message
                        LIMIT 1
                )
                ORDER BY m.id_message DESC
                LIMIT 14)

                UNION ALL

                (SELECT m.*
                FROM messages m
                INNER JOIN chat_list cl ON cl.id_chat_list = m.id_chat_list
                INNER JOIN config c ON cl.id_channel = c.id_channel
                WHERE m.id_chat_list = '$id_chat_list'
                AND m.visible = 1
                AND m.id_message = (
                        SELECT m2.id_message
                        FROM messages m2
                        INNER JOIN chat_list cl2 ON cl2.id_chat_list = m2.id_chat_list
                        INNER JOIN config c2 ON cl2.id_channel = c2.id_channel
                        WHERE DATE_FORMAT(
                                DATE_ADD(
                                    FROM_UNIXTIME(m2.creation),
                                    INTERVAL TIME_TO_SEC(c2.timezone) SECOND
                                ),
                                '%Y-%m-%d'
                            ) <= '{$dt_start}'
                        AND m2.id_chat_list = '$id_chat_list'
                        ORDER BY m2.id_message desc
                        LIMIT 1
                ))

                UNION ALL

                (SELECT m.*
                FROM messages m
                INNER JOIN chat_list cl ON cl.id_chat_list = m.id_chat_list
                INNER JOIN config c ON cl.id_channel = c.id_channel
                WHERE m.id_chat_list = '$id_chat_list'
                AND m.visible = 1
                AND m.id_message > (
                        SELECT m2.id_message
                        FROM messages m2
                        INNER JOIN chat_list cl2 ON cl2.id_chat_list = m2.id_chat_list
                        INNER JOIN config c2 ON cl2.id_channel = c2.id_channel
                        WHERE DATE_FORMAT(
                                DATE_ADD(
                                    FROM_UNIXTIME(m2.creation),
                                    INTERVAL TIME_TO_SEC(c2.timezone) SECOND
                                ),
                                '%Y-%m-%d'
                            ) >= '{$dt_start}'
                        AND m2.id_chat_list = '$id_chat_list'
                        ORDER BY m2.id_message
                        LIMIT 1
                )
                ORDER BY m.id_message ASC
                LIMIT 30)
            ) AS messages
            LEFT JOIN message_referral 
                ON messages.id_message = message_referral.id_message
            LEFT JOIN user 
                ON messages.key_remote_id = user.key_remote_id
            INNER JOIN chat_list 
                ON chat_list.id_chat_list = messages.id_chat_list
            INNER JOIN config 
                ON chat_list.id_channel = config.id_channel
            INNER JOIN channel 
                ON chat_list.id_channel = channel.id_channel
            LEFT JOIN messages_reaction 
                ON messages.id_message = messages_reaction.id_message 
            AND messages_reaction.status = 1
            ORDER BY messages.id_message ASC";

        $query = $this->db->query($sql);
        $result = $query->result_array();

        return $result ?? [];
    }
}
