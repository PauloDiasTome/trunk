<?php

class DB_model extends TA_model
{
    function Get($db)
    {
        $sql = "select concat('delete from $db.chat_list where $db.chat_list.id_chat_list = ', $db.chat_list.id_chat_list, ';') sql2,\n";
        $sql .= "concat('delete from $db.chat_list_log where $db.chat_list_log.id_chat_list = ',$db.chat_list.id_chat_list,';') sql1,\n";
        $sql .= "concat('delete from $db.chat_list_label where $db.chat_list_label.id_chat_list = ',$db.chat_list.id_chat_list,';') sql3\n";
        $sql .= "from $db.chat_list inner join $db.contact on $db.chat_list.id_contact = $db.contact.id_contact\n";
        $sql .= "where $db.contact.is_private = 2;\n";

        $this->db->db_debug = true;
        $result = $this->db->query($sql);

        return $result->result_array();
    }
}
