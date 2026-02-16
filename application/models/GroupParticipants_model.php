<?php

class GroupParticipants_model extends TA_model
{
    function Get($id){

        $sql = "select\n";
        $sql .= "group_participants.id_group_participant,\n";
        $sql .= "user.last_name full_name,\n";
        $sql .= "group_participants.key_remote_id\n";
        $sql .= "from group_participants inner join user on group_participants.key_remote_id = user.key_remote_id\n";
        $sql .= "where group_participants.id_group = $id\n";
        $sql .= "order by user.last_name\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }
}
