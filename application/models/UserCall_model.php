<?php

class UserCall_model extends TA_model
{

    function Get($post)
    {
        $text = $post['text'];
        $order_column = $post['order'][0]['column'];
        $order_dir = $post['order'][0]['dir'];
        $start = $post['start'];
        $length = $post['length'];

        $sql = "select\n";
        $sql .= "user_calls.id_user_call,\n";
        $sql .= "user_calls.name,\n";
        $sql .= "user_calls.limit\n";
        $sql .= "from user_calls\n";
        $sql .= "where lower(user_calls.name) like lower('%{$text}%')\n";

        switch ($order_column) {
            case 1:
                $sql .= "order by user_calls.limit {$order_dir}\n";
                break;

            default:
                $sql .= "order by user_calls.name {$order_dir}\n";
        }

        $sql .= "limit {$start},{$length}\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Count($post)
    {
        $text = $post['text'];

        $sql = "select count(user_calls.id_user_call) count\n";
        $sql .= "from user_calls\n";
        $sql .= "where lower(user_calls.name) like lower('%{$text}%')\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Add($data)
    {
        $values = [
            'name' => trim($data['input-name']),
            'limit' => trim($data['input-limit'])
        ];

        $this->db->insert('user_calls', $values);

        if ($this->db->affected_rows() > 0) {

            $sql = "SELECT user_calls.id_user_call, user_calls.name, user_calls.limit FROM user_calls ORDER BY user_calls.name";

            $result = $this->db->query($sql);

            return $result->result_array();
        }
    }


    function Edit($key_id, $data)
    {
        $values = [
            'name' => trim($data['input-name']),
            'limit' => trim($data['input-limit'])
        ];

        $this->db->where('id_user_call', $key_id);
        $this->db->update('user_calls', $values);
    }


    function Delete($key_id)
    {
        $sql = "SELECT * FROM user WHERE user.id_user_call = '$key_id'\n";

        $result = $this->db->query($sql);

        if ($result->num_rows() < 1) {
            $this->db->where('id_user_call', $key_id);
            $this->db->delete('user_calls');
        } else {
            $sql2 = "SELECT user.name FROM user WHERE user.id_user_call = '$key_id'\n";
            $resultName = $this->db->query($sql2);
            return $resultName->result_array();
        }

        return $result->result_array();
    }


    function List()
    {
        $sql = "select\n";
        $sql .= "user_calls.id_user_call,\n";
        $sql .= "user_calls.name\n";
        $sql .= "from user_calls\n";
        $sql .= "order by user_calls.name\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function GetInf($id)
    {
        $sql = "select\n";
        $sql .= "user_calls.id_user_call,\n";
        $sql .= "user_calls.name,\n";
        $sql .= "user_calls.limit\n";
        $sql .= "from user_calls\n";
        $sql .= "where user_calls.id_user_call = '" . $id . "';\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }
}
