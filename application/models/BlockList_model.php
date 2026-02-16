<?php

class BlockList_model extends TA_model
{
    function Get($param)
    {

        $this->db->select('
            block_list.id_block_list, 
            contact.key_remote_id,
            contact.full_name name_contact,
            user.last_name
        ');

        $this->db->from('contact');
        $this->db->join('block_list', 'contact.id_contact = block_list.id_contact');
        $this->db->join('user', 'block_list.id_user = user.id_user');
        $this->db->join('channel', 'channel.id_channel = contact.id_channel');
        $this->db->where('channel.status', 1);
        $this->db->group_start();
        $this->db->like('LOWER(contact.key_remote_id)', $this->db->escape_like_str(strtolower($param['text'])), 'both', false);
        $this->db->or_like('LOWER(contact.full_name)', $this->db->escape_like_str(strtolower($param['text'])), 'both', false);
        $this->db->or_like('LOWER(user.last_name)', $this->db->escape_like_str(strtolower($param['text'])), 'both', false);
        $this->db->group_end();
        

        $count = $this->db->count_all_results('', false);

        switch ($param['order'][0]['column']) {
            case 1:
                $this->db->order_by("contact.full_name", $param['order'][0]['dir']);
                break;
            case 2:
                $this->db->order_by("user.last_name", $param['order'][0]['dir']);
                break;
            default:
                $this->db->order_by("contact.key_remote_id", $param['order'][0]['dir']);
                break;
        }

        $this->db->limit($param['length'], $param['start']);

        return ['query' => $this->db->get()->result_array(), 'count' => $count];
    }

    function Delete($key_id)
    {
        $this->db->select('block_list.id_contact');
        $this->db->from("block_list");
        $this->db->where("block_list.id_block_list", $this->db->escape_like_str($key_id));

        $query = $this->db->get('');

        if ($query->num_rows() > 0) {

            $data = ['spam' => 1];

            $this->db->where('id_contact', (int) $query->result_array()[0]['id_contact']);
            $this->db->update('contact', $data);

            $this->db->where('id_block_list', $key_id);
            $this->db->delete('block_list');
        }
    }
}
