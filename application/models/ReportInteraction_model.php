<?php

class ReportInteraction_model extends TA_model
{
    function Get($text, $situation, $channel, $dt_start, $dt_end, $start, $length, $order_column, $order_dir)
    {
        $channels = "";

        if (!empty($channel)) {
            foreach ($channel as $key => $val) {
                $channels .= "," . $val;
            }
            $channels = substr($channels, 1);
        }

        $sql = "SELECT
                    chat_list.id_chat_list,
                    DATE_FORMAT(FROM_UNIXTIME(bot_log.start), '%d/%m/%Y') start,
                    channel.name channel,
                    contact.full_name name,
                    contact.key_remote_id number,
                    CASE
                        WHEN
                            chat_list.is_close = 1
                                AND chat_list.key_remote_id IS NOT NULL
                                AND chat_list.is_bot = 2
                        THEN
                            '1'
                        WHEN
                            chat_list.is_close = 1
                                AND chat_list.key_remote_id = 0
                                AND chat_list.is_bot = 1
                        THEN
                            '2'
                        WHEN
                            chat_list.is_close = 1
                                AND chat_list.key_remote_id IS NULL
                                AND chat_list.is_bot = 2
                        THEN
                            '3'
                        WHEN chat_list.is_close = 2 THEN '4'
                    END status
                FROM
                    chat_list
                        INNER JOIN
                    contact ON chat_list.id_contact = contact.id_contact
                        INNER JOIN
                    bot_log ON contact.key_remote_id = bot_log.key_remote_id
                        INNER JOIN
                    channel ON chat_list.id_channel = channel.id_channel
                WHERE 
                    contact.deleted = 1 AND chat_list.is_private = 1 AND
                       CONCAT(contact.full_name, ' ',contact.key_remote_id) LIKE '%{$text}%'\n";

        if (trim($situation) != "") {
            switch ($situation) {
                case 1:
                    $sql .= "AND chat_list.is_close = 1 AND chat_list.key_remote_id IS NOT NULL AND chat_list.is_bot = 2\n";
                    break;
                case 2:
                    $sql .= "AND chat_list.is_close = 1 AND chat_list.key_remote_id = 0 AND chat_list.is_bot = 1\n";
                    break;
                case 3:
                    $sql .= "AND chat_list.is_close = 1 AND chat_list.key_remote_id IS NULL AND chat_list.is_bot = 2\n";
                    break;
                case 4:
                    $sql .= "AND chat_list.is_close = 2\n";
                    break;
            }
        }

        if (trim($channels) != "") {
            $sql .= "AND channel.id_channel IN ($channels)\n";
        }

        if (trim($dt_start) != "") {
            $sql .= "AND FROM_UNIXTIME(bot_log.start) BETWEEN '{$dt_start}' AND '{$dt_end}'";
        }

        switch ($order_column) {
            case 0:
                $sql .= "ORDER BY bot_log.start $order_dir\n";
                break;

            case 1:
                $sql .= "ORDER BY channel.name $order_dir\n";
                break;

            case 2:
                $sql .= "ORDER BY contact.full_name $order_dir\n";
                break;
            case 3:
                $sql .= "ORDER BY contact.key_remote_id $order_dir\n";
                break;
        }

        $sql .= "LIMIT {$start},{$length}\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Count($text, $situation, $channel, $dt_start, $dt_end)
    {
        $channels = "";

        if (!empty($channel)) {
            foreach ($channel as $key => $val) {
                $channels .= "," . $val;
            }
            $channels = substr($channels, 1);
        }

        $sql = "SELECT 
                    COUNT(chat_list.id_chat_list) count
                FROM
                    chat_list
                        INNER JOIN
                    contact ON chat_list.id_contact = contact.id_contact
                        INNER JOIN
                    bot_log ON contact.key_remote_id = bot_log.key_remote_id
                        INNER JOIN
                    channel ON chat_list.id_channel = channel.id_channel
                WHERE 
                    contact.deleted = 2 AND chat_list.is_private = 1 AND
                       CONCAT(contact.full_name, ' ',contact.key_remote_id) LIKE '%{$text}%'\n";

        if (trim($situation) != "") {
            switch ($situation) {
                case 1:
                    $sql .= "AND chat_list.is_close = 1 AND chat_list.key_remote_id IS NOT NULL AND chat_list.is_bot = 2\n";
                    break;
                case 2:
                    $sql .= "AND chat_list.is_close = 1 AND chat_list.key_remote_id = 0 AND chat_list.is_bot = 1\n";
                    break;
                case 3:
                    $sql .= "AND chat_list.is_close = 1 AND chat_list.key_remote_id IS NULL AND chat_list.is_bot = 2\n";
                    break;
                case 4:
                    $sql .= "AND chat_list.is_close = 2\n";
                    break;
            }
        }

        if (trim($channels) != "") {
            $sql .= "AND channel.id_channel in ($channels)\n";
        }

        if (trim($dt_start) != "") {
            $sql .= "AND FROM_UNIXTIME(bot_log.start) BETWEEN '{$dt_start}' AND '{$dt_end}'";
        }

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function List()
    {
        $sql = "SELECT 
                    channel.id_channel,
                    channel.name
                FROM
                    channel
                WHERE 
                    channel.type IN (2, 8, 10, 12)
                ORDER BY
                    channel.name";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function UserGroup()
    {
        $sql = "SELECT * FROM user_group WHERE user_group.status = 1";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function GetUsers($id)
    {
        $sql = "SELECT * FROM user WHERE user.id_user_group = '{$id}' AND user.status = 1";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function getQuoted($quoted_id)
    {
        $sql = "select\n";
        $sql .= "case\n";
        $sql .= "when messages.media_type = 1 then SUBSTRING(messages.data,1,30)\n";
        $sql .= "when messages.media_type = 2 then DATE_FORMAT(SEC_TO_TIME(messages.media_duration),'%H:%s')\n";
        $sql .= "when messages.media_type = 3 then SUBSTRING(messages.media_caption,1,30)\n";
        $sql .= "when messages.media_type = 4 then SUBSTRING(messages.title,1,30)\n";
        $sql .= "when messages.media_type = 5 then case when messages.media_caption = '0' then 'Vídeo' else SUBSTRING(messages.media_caption,1,30) end\n";
        $sql .= "when messages.media_type = 6 then 'Localização'\n";
        $sql .= "when messages.media_type = 9 then SUBSTRING(messages.media_caption,1,30)\n";
        $sql .= "when messages.media_type = 26 then 'Figurinha'\n";
        $sql .= "end\n";
        $sql .= " data,\n";
        $sql .= "messages.key_from_me,\n";
        $sql .= "messages.media_type,\n";
        $sql .= "messages.media_url,\n";
        $sql .= "messages.thumb_image,\n";
        $sql .= "(\n";
        $sql .= "select contact.full_name from contact where contact.deleted = 1 and contact.key_remote_id = messages.participant limit 1\n";
        $sql .= ") participant\n";
        $sql .= "from messages\n";
        $sql .= "where messages.key_id = '" . $quoted_id . "'\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function GetMessages($id, $creation)
    {
        $sql = "SELECT 
                    messages.creation,
                    messages.ta_key_id token,
                    messages.key_from_me,
                    messages.data,
                    messages.status,
                    messages.media_caption,
                    messages.media_type,
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
                    messages.participant
                FROM
                    chat_list
                        INNER JOIN
                    messages ON chat_list.id_chat_list = messages.id_chat_list
                WHERE
                    chat_list.id_chat_list = '{$id}'\n";

        if ($creation != 0) {
            $sql .= "AND messages.creation <= '{$creation}' ORDER BY messages.creation DESC , messages.id_message DESC LIMIT 0 , 30\n";
        } else {
            $sql .= "ORDER BY messages.creation DESC , messages.id_message DESC LIMIT 0 , 30\n";
        }

        $result = $this->db->query($sql);

        $data = $result->result_array();

        foreach ($data as &$row) {
            if ($row['quoted_row_id'] != "") {
                $aQuoted = $this->getQuoted($row['quoted_row_id']);
                $row['quoted'] = isset($aQuoted[0]);
                $row['participant'] = isset($aQuoted[0]['participant']);
            }
        }

        return $data;
    }


    function HistoryScroll($id_chat_list)
    {
        $sql = "select * from messages\n";
        $sql .= "where messages.id_chat_list = $id_chat_list order by messages.creation desc, messages.id_message desc\n";

        $result = $this->db->query($sql);

        $data = $result->result_array();

        foreach ($data as &$row) {
            if ($row['quoted_row_id'] != "") {
                $aQuoted = $this->getQuoted($row['quoted_row_id']);
                $row['quoted'] = $aQuoted[0];
                $row['participant'] = $aQuoted[0]['participant'];
            }
        }

        return $data;
    }
}
