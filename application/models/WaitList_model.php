<?php

class WaitList_model extends TA_model
{
    function Get($text, $start, $length)
    {
        $this->db->select('
            wait_list.creation,
            wait_list.id_wait_list,
            wait_list.key_remote_id,
            contact.full_name,
            user_group.name stage,
            wait_list.user_key_remote_id
        ');

        $this->db->from('wait_list');
        $this->db->join('contact','wait_list.key_remote_id = contact.key_remote_id');
        $this->db->left('user_group','contact.id_user_group = user_group.id_user_group');
        $this->db->where('wait_list.status',1);
        $this->db->like('wait_list.key_remote_id',$this->db->escape_like_str($text));
        $this->db->limit($length, $start);

        $query = $this->db->get('');

        return $query->result_array();
    }

    function Count($text)
    {
        $this->db->select('count(wait_list.id_wait_list) count');
        $this->db->from('wait_list');
        $this->db->where('wait_list.status',1);
        $this->db->like('wait_list.key_remote_id',$this->db->escape_like_str($text));

        $query = $this->db->get('');

        return $query->result_array();
    }
}
