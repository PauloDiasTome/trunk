<?php

class UserGroup_model extends TA_model
{
    public function __construct()
    {
        parent::__construct();
    }

    function Get($text, $start, $length, $order_column, $order_dir)
    {
        $this->db->select('
            user_group.id_user_group,
            user_group.name
        ');

        $this->db->from('user_group');
        $this->db->where('user_group.status', 1);
        $this->db->like('LOWER(user_group.name)', $this->db->escape_like_str(strtolower($text)));

        switch ($order_column) {
            case 0:
                $this->db->order_by("user_group.name", $order_dir);
                break;
            default:
        }
 
        $this->db->limit($length, $start);

        $query = $this->db->get('');

        return $query->result_array();
    }

    function Count($text)
    {
        $this->db->select('
            count(user_group.id_user_group) count
        ');

        $this->db->from('user_group');
        $this->db->where('user_group.status', 1);
        $this->db->like('LOWER(user_group.name)', $this->db->escape_like_str(strtolower($text)));

        $query = $this->db->get('');

        return $query->result_array();
    }

    function Add($data)
    {
        $date = new DateTime();

        $values = [
            'creation' => $date->getTimestamp(),
            'name' => trim($data['input-name']),
            'status' => 1,
        ];

        $this->db->insert('user_group', $values);

        if ($this->db->affected_rows() > 0) {

            $this->db->select('
                user_group.name,
                user_group.id_user_group
            ');

            $this->db->from('user_group');
            $this->db->where('user_group.status', 1);
            $this->db->order_by('user_group.name', 'asc');

            $query = $this->db->get('');

            return $query->result_array();
        }
    }

    function Edit($key_id, $data)
    {
        $values = [
            'name' => trim($data['input-name']),
        ];

        $this->db->where('id_user_group', $key_id);
        $this->db->update('user_group', $values);
    }

    function Delete($key_id)
    {
        $this->db->select('*');
        $this->db->from('user');
        $this->db->where('user.status', 1);
        $this->db->where('user.id_user_group', $key_id);
        $this->db->not_like('user.email', 'suporte.%');
        $this->db->not_like('user.email', '%@talkall.com.br');

        $query = $this->db->get('');

        if ($query->num_rows() < 1) {

            $values = [
                'status' => 2,
            ];

            $this->db->where('id_user_group', $key_id);
            $this->db->update('user_group', $values);
        } else {

            $this->db->select('
                user.name
            ');

            $this->db->from('user');
            $this->db->where('user.status', 1);
            $this->db->where('user.id_user_group', $key_id);
            $this->db->not_like('user.email', 'suporte.%');
            $this->db->not_like('user.email', '%@talkall.com.br');
        }

        return $query->result_array();
    }

    function List()
    {
        $this->db->select('
            user_group.id_user_group,
            user_group.name
        ');

        $this->db->from('user_group');
        $this->db->where('user_group.status', 1);
        $this->db->order_by('user_group.name', 'asc');

        $query = $this->db->get('');

        return $query->result_array();
    }

    function GetInf($id)
    {
        $this->db->select('
            user_group.id_user_group,
            user_group.name
        ');

        $this->db->from('user_group');
        $this->db->where('user_group.id_user_group', $id);

        $query = $this->db->get('');

        return $query->result_array();
    }
}
