<?php

class ReportTransferAutomatic_model extends TA_model
{
    function Get($text, $user, $sector, $dt_start, $dt_end, $start, $length, $order_column, $order_dir)
    {
        $sectors = "";
        $users = "";

        if (!empty($sector)) {
            foreach ($sector as $key => $val) {
                $sectors .= "," . $val;
            }
            $sectors = substr($sectors, 1);
        }

        if (!empty($user)) {
            foreach ($user as $key => $val) {
                $users .= "," . $val;
            }
            $users = substr($users, 1);
        }

        $sql = "SELECT 
                    chat_list.id_chat_list,
                    contact.key_remote_id number,
                    user_group.name sector,
                    contact.full_name name,
                CASE
                    WHEN wait_list.user_key_remote_id IS NULL THEN user.name
                    WHEN wait_list.user_key_remote_id = '0' THEN ''
                    ELSE user.name
                END user,
                    wait_list.type
                FROM
                    chat_list
                        INNER JOIN
                    contact ON chat_list.id_contact = contact.id_contact
                        INNER JOIN
                    wait_list ON contact.key_remote_id = wait_list.key_remote_id
                        INNER JOIN
                    user_group ON wait_list.id_user_group = user_group.id_user_group
                        INNER JOIN
                    user ON wait_list.user_key_remote_id = user.key_remote_id
                WHERE
                    contact.spam = 1 AND contact.deleted = 1 AND wait_list.type = 'bot'\n";

        if ($text != "") {
            $sql .= "AND CONCAT(contact.full_name, ' ',contact.key_remote_id) LIKE '%{$text}%'\n";
        }

        if ($user) {
            $sql .= "AND user.id_user IN($users)\n";
        }

        if ($sector) {
            $sql .= "AND user_group.id_user_group IN($sectors)\n";
        }

        if ($dt_start) {
            $sql .= "AND from_unixtime(wait_list.creation) BETWEEN '{$dt_start}' AND '{$dt_end}'\n";
        }

        switch ($order_column) {
            case 0:
                $sql .= "ORDER BY contact.key_remote_id $order_dir \n";
                break;
            case 1:
                $sql .= "ORDER BY contact.full_name $order_dir \n";
                break;
            case 2:
                $sql .= "ORDER BY user_group.name $order_dir \n";
                break;
            case 3:
                $sql .= "ORDER BY user.name $order_dir \n";
                break;
        }

        $sql .= " LIMIT {$start},{$length}\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Count($text, $user, $sector, $dt_start, $dt_end)
    {
        $sql = "SELECT 
                    COUNT(chat_list.id_chat_list) count
                FROM
                    chat_list
                        INNER JOIN
                    contact ON chat_list.id_contact = contact.id_contact
                        INNER JOIN
                    wait_list ON contact.key_remote_id = wait_list.key_remote_id
                WHERE
                    contact.spam = 1 AND contact.deleted = 1 AND wait_list.type = 'bot'\n";

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
                    messages.id_chat_bot,
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


    function ListUsergroup()
    {
        $sql = "SELECT * FROM user_group WHERE user_group.status = '1' ";

        $result = $this->db->query($sql);

        return  $result->result_array();
    }


    function ListUsers()
    {
        $sql = "SELECT * FROM user WHERE user.status = '1'";
        $result = $this->db->query($sql);

        return  $result->result_array();
    }
}
