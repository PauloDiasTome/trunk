<?php

class Feedback_model extends TA_model
{
    function FormatPhone($num)
    {
        $tam = strlen(preg_replace("/[^0-9]/", "", $num));
        if ($tam == 13) {
            return "(" . substr($num, $tam - 11, 2) . ") " . substr($num, $tam - 9, 5) . "-" . substr($num, -4);
        }
        if ($tam == 12) {
            return "(" . substr($num, $tam - 10, 2) . ") " . substr($num, $tam - 8, 4) . "-" . substr($num, -4);
        }
    }


    function get()
    {
        $sql = "select \n";
        $sql .= "chat_list.id_chat_list,\n";
        $sql .= "DATE_FORMAT(DATE_ADD(from_unixtime(chat_list.creation), INTERVAL config.timezone HOUR),'%d/%m/%Y') as creation,\n";
        $sql .= "channel.name,\n";
        $sql .= "contact.full_name,\n";
        $sql .= "contact.key_remote_id id,\n";
        $sql .= "contact.key_remote_id,\n";
        $sql .= "chat_list.is_broadcast,\n";
        $sql .= "chat_list.is_group,\n";
        $sql .= "chat_list.is_chat\n";
        $sql .= "from chat_list\n";
        $sql .= "inner join channel on chat_list.id_channel = channel.id_channel\n";
        $sql .= "inner join config on channel.id_channel = config.id_channel\n";
        $sql .= "inner join contact on chat_list.id_contact = contact.id_contact\n";

        $result = $this->db->query($sql);

        $data = $result->result_array();

        foreach ($data as &$row) {
            $row['id'] = $this->FormatPhone($row['id']);
        }

        return $data;
    }


    function getMessages($chat_id)
    {

        $sql = "select\n";
        $sql .= "DATE_FORMAT(DATE_ADD(from_unixtime(chat_list.creation), INTERVAL config.timezone HOUR),'%d/%m/%Y') as date,\n";
        $sql .= "DATE_FORMAT(DATE_ADD(from_unixtime(chat_list.creation), INTERVAL config.timezone HOUR),'%k:%i') as time,\n";
        $sql .= "messages.key_from_me,\n";
        $sql .= "contact.full_name,\n";
        $sql .= "messages.data\n";
        $sql .= "from messages\n";
        $sql .= "inner join chat_list on messages.id_chat_list = chat_list.id_chat_list\n";
        $sql .= "inner join channel on chat_list.id_channel = channel.id_channel\n";
        $sql .= "inner join config on channel.id_channel = config.id_channel\n";
        $sql .= "inner join contact on chat_list.id_contact = contact.id_contact\n";
        $sql .= "where chat_list.id_chat_list = '" . $chat_id . "'\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }
}
