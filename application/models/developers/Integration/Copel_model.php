<?php

class Copel_model extends TA_model
{
    function Delete($key_id)
    {
        $sql = "select\n";
        $sql .= "block_list.id_contact\n";
        $sql .= "from block_list \n";
        $sql .= "where block_list.id_block_list = '$key_id'\n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {

            $data = ['spam' => 1];
    
            $this->db->where('id_contact', (int) $result->result_array()[0]['id_contact']);
            $this->db->update('contact', $data);

            $this->db->where('id_block_list', $key_id);
            $this->db->delete('block_list');
        }
    }
}
