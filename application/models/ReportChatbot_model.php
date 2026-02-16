<?php

class ReportChatbot_model extends TA_model
{
    function Get($text, $channel, $dt_start, $dt_end, $start, $length, $order_column, $order_dir)
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
                    DATE_FORMAT(FROM_UNIXTIME(chat_list.first_timestamp_client),
                            '%d/%m/%Y') first_timestamp,
                    DATE_FORMAT(FROM_UNIXTIME(chat_list.last_timestamp_client),
                            '%d/%m/%Y %H:%i:%s') last_timestamp_client,
                    TIMESTAMPDIFF(MINUTE,
                    DATE_FORMAT(FROM_UNIXTIME(chat_list.last_timestamp_client),
                                '%Y-%m-%d %H:%i:%s'),
                        CURRENT_TIME()) minutes,
                CASE
		            WHEN contact.full_name IS NULL THEN contact.key_remote_id
		            WHEN contact.full_name = '0' THEN contact.key_remote_id
                    ELSE contact.full_name
	            END name,
                    contact.key_remote_id number,
                    channel.name channel,
                    channel.id_channel
                FROM
                    chat_list
                        INNER JOIN
                    contact ON contact.id_contact = chat_list.id_contact
                        INNER JOIN
                    channel ON channel.id_channel = chat_list.id_channel
                        INNER JOIN
                    config ON channel.id_channel = config.id_channel

                WHERE chat_list.is_bot = 1 AND contact.deleted = 2 AND channel.type IN (2, 8, 10, 12) 
                AND chat_list.is_private = 2  AND

                CONCAT(contact.full_name, ' ', contact.key_remote_id) LIKE '%{$text}%'\n";

        if (trim($channels) != "") {
            $sql .= "AND channel.id_channel IN ($channels)\n";
        }

        if (trim($dt_start) != "") {
            $sql .= "AND from_unixtime(chat_list.last_timestamp_client) BETWEEN '{$dt_start}' AND '{$dt_end}'";
        }

        switch ($order_column) {
            case 0:
                $sql .= "ORDER BY chat_list.first_timestamp_client $order_dir\n";
                break;

            case 1:
                $sql .= "order by contact.full_name $order_dir\n";
                break;

            case 2:
                $sql .= "order by contact.key_remote_id $order_dir\n";
                break;

            case 3:
                $sql .= "order by chat_list.last_timestamp_client $order_dir\n";
                break;

            case 4:
                $sql .= "order by channel.id $order_dir\n";
                break;

            default:
                $sql .= "ORDER BY contact.full_name DESC\n";
        }

        $sql .= "LIMIT {$start},{$length}\n";

        // echo $sql;
        // die;

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Count($text, $dt_start, $dt_end, $channel)
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
                    contact ON contact.id_contact = chat_list.id_contact
                        INNER JOIN
                    channel ON channel.id_channel = chat_list.id_channel
                    
                WHERE chat_list.is_bot >= 1 AND contact.deleted = 2

                AND CONCAT(contact.full_name, ' ', contact.key_remote_id) LIKE '%{$text}%'\n";

        if (trim($channels) != "") {
            $sql .= "AND channel.id_channel in ($channels)\n";
        }

        if (trim($dt_start) != "") {
            $sql .= "AND from_unixtime(chat_list.last_timestamp_client) BETWEEN '{$dt_start}' AND '{$dt_end}'";
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

        $data = $result->result_array();

        return $data;
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


    function History($id_chat_list, $creation)
    {
        $sql = "select * from messages\n";
        $sql .= "where messages.id_chat_list = $id_chat_list order by messages.creation desc, messages.id_message desc LIMIT 0,20\n";

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
