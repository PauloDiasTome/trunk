<?php

class ReportWaitingService_model extends TA_model
{
    function Get($text, $sector, $dt_start, $dt_end, $start, $length, $order_column, $order_dir)
    {
        $sectors = "";

        if (!empty($sector)) {
            foreach ($sector as $key => $val) {
                $sectors .= "," . $val;
            }
            $sectors = substr($sectors, 1);
        }

        $sql = "SELECT 
                    chat_list.id_chat_list,
                        DATE_FORMAT(FROM_UNIXTIME(wait_list.creation),
                            '%d/%m/%Y') creation,
                    TIMESTAMPDIFF(MINUTE,
                        DATE_FORMAT(FROM_UNIXTIME(wait_list.creation),
                                '%Y-%m-%d %H:%i:%s'),
                        CURRENT_TIME()) minutes,
                    contact.key_remote_id contact,
                    contact.full_name name,
                    user.name user,
                    user_group.name sector,
                    chat_list.is_group
                FROM
                    chat_list
                INNER JOIN
                    contact ON chat_list.id_contact = contact.id_contact
                INNER JOIN
                    wait_list ON contact.key_remote_id = wait_list.key_remote_id
                INNER JOIN
                    user_group ON wait_list.id_user_group = user_group.id_user_group
                LEFT JOIN
                    user ON wait_list.user_key_remote_id = user.key_remote_id
                INNER JOIN
                    channel ON chat_list.id_channel = channel.id_channel
                INNER JOIN
                    config ON channel.id_channel = config.id_channel
                WHERE              
                    contact.spam = 1 AND contact.deleted = 1 AND wait_list.status = 1\n";

        if ($text != "") {
            $sql .= "AND (LOWER(contact.full_name) LIKE LOWER('%{$text}%') OR LOWER(contact.key_remote_id) LIKE LOWER('%{$text}%'))\n";
        }

        if ($sector) {
            $sql .= "AND user_group.id_user_group IN($sectors)\n";
        }

        if ($dt_start) {
            $sql .= "AND DATE_FORMAT(DATE_ADD(from_unixtime(wait_list.creation), INTERVAL config.timezone HOUR),'%Y-%m-%d') BETWEEN '$dt_start' AND '$dt_end'\n";
        }

        switch ($order_column) {
            case 0:
                $sql .= "ORDER BY wait_list.creation $order_dir\n";
                break;

            case 1:
                $sql .= "ORDER BY contact.key_remote_id $order_dir\n";
                break;

            case 2:
                $sql .= "ORDER BY contact.full_name $order_dir\n";
                break;

            case 3:
                $sql .= "ORDER BY user.name $order_dir\n";
                break;

            case 4:
                $sql .= "ORDER BY user_group.name $order_dir\n";
                break;

            case 5:
                $sql .= "ORDER BY departament $order_dir\n";
                break;
        }

        $sql .= "LIMIT {$start},{$length}\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Count($text, $sector, $dt_start, $dt_end)
    {
        $sectors = "";

        if (!empty($sector)) {
            foreach ($sector as $key => $val) {
                $sectors .= "," . $val;
            }
            $sectors = substr($sectors, 1);
        }

        $sql = "SELECT 
                  COUNT(wait_list.id_wait_list) count
                FROM
                   chat_list
                INNER JOIN
                    contact ON chat_list.id_contact = contact.id_contact
                INNER JOIN
                    wait_list ON contact.key_remote_id = wait_list.key_remote_id
                INNER JOIN
                    user_group ON wait_list.id_user_group = user_group.id_user_group
                LEFT JOIN
                    user ON wait_list.user_key_remote_id = user.key_remote_id
                INNER JOIN
                    channel ON chat_list.id_channel = channel.id_channel
                INNER JOIN
                    config ON channel.id_channel = config.id_channel
                WHERE              
                    contact.spam = 1 AND contact.deleted = 1 AND wait_list.status = 1 \n";

        if ($text != "") {
            $sql .= "AND (LOWER(contact.full_name) LIKE LOWER('%{$text}%') OR LOWER(contact.key_remote_id) LIKE LOWER('%{$text}%'))\n";
        }

        if ($sectors) {
            $sql .= "AND user_group.id_user_group IN($sectors)\n";
        }

        if ($dt_start) {
            $sql .= "AND DATE_FORMAT(DATE_ADD(from_unixtime(wait_list.creation), INTERVAL config.timezone HOUR),'%Y-%m-%d') BETWEEN '$dt_start' AND '$dt_end'\n";
        }

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function UserGroup()
    {
        $sql = "SELECT * FROM user_group WHERE user_group.status = 1";

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
                    DATE_FORMAT(FROM_UNIXTIME(messages.creation),'%d/%m/%Y'),
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
}
