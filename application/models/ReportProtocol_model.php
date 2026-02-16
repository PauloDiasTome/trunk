<?php

class ReportProtocol_model extends TA_model
{
    function Get($text, $start, $length, $order, $orderType)
    {
        $sql = "SELECT\n";
        $sql .= "DATE_FORMAT(from_unixtime(chat_list_log.creation),'%T %m/%d/%Y') creation,\n";
        $sql .= "chat_list_log.protocol,\n";
        $sql .= "contact.full_name,\n";
        $sql .= "contact.key_remote_id,\n";
        $sql .= "user.last_name\n";
        $sql .= "FROM contact\n";
        $sql .= "inner join chat_list on contact.id_contact = chat_list.id_contact\n";
        $sql .= "inner join chat_list_log on chat_list.id_chat_list = chat_list_log.id_chat_list\n";
        $sql .= "inner join user on chat_list_log.key_remote_id = user.key_remote_id\n";
        $sql .= "where chat_list_log.protocol is not null\n";
        $sql .= "and ( ( chat_list_log.protocol like '%" . $text . "%' )\n";
        $sql .= "or ( contact.key_remote_id like '%" . $text . "%' )\n";
        $sql .= "or ( contact.full_name like '%" . $text . "%' )\n";
        $sql .= "or ( user.last_name like '%" . $text . "%' ) )\n";

        switch ($order) {
            case 0:
                $sql .= "order by user.name $orderType\n";
                break;

            case 1:
                $sql .= "order by chat_list_log.protocol $orderType\n";
                break;

            case 2:
                $sql .= "order by contact.full_name $orderType\n";
                break;

            case 3:
                $sql .= "order by contact.key_remote_id $orderType\n";
                break;

            default:
                $sql .= "order by user.last_name $orderType\n";
        }

        $sql .= "limit {$start},{$length}\n";

        $result = $this->db->query($sql);
        $data = $result->result_array();

        return $data;
    }


    function Count($text)
    {
        $sql = "select count(chat_list_log.id_chat_list) count\n";
        $sql .= "FROM contact\n";
        $sql .= "inner join chat_list on contact.id_contact = chat_list.id_contact\n";
        $sql .= "inner join chat_list_log on chat_list.id_chat_list = chat_list_log.id_chat_list\n";
        $sql .= "inner join user on chat_list_log.key_remote_id = user.key_remote_id\n";
        $sql .= "where chat_list_log.protocol is not null\n";
        $sql .= "and ( ( chat_list_log.protocol like '%" . $text . "%' )\n";
        $sql .= "or ( contact.key_remote_id like '%" . $text . "%' )\n";
        $sql .= "or ( contact.full_name like '%" . $text . "%' )\n";
        $sql .= "or ( user.last_name like '%" . $text . "%' ) )\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }
}
