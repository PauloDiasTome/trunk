<?php

class Participants_model extends TA_model
{
    function Get($id)
    {
        $sql = "select\n";
        $sql .= "contact.id_contact,\n";
        $sql .= "contact.creation,\n";
        $sql .= "contact.key_remote_id\n";
        $sql .= "from contact inner join contact_group on contact.id_contact = contact_group.id_contact\n";
        $sql .= "where contact_group.id_group_contact = '" . $id . "'\n";
        $sql .= "order by contact.key_remote_id\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }
}
