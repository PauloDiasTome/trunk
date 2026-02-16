<?php

class OrderStatus_model extends TA_model
{

    function Get($text, $start, $length, $order_column, $order_dir)
    {
        $sql = "select\n";
        $sql .= "order_status.id_order_status,\n";
        $sql .= "DATE_FORMAT(from_unixtime(order_status.creation),'%d/%m/%Y') creation,\n";
        $sql .= "order_status.name,\n";
        $sql .= "order_status.color,\n";
        $sql .= "order_status.message,\n";
        $sql .= "order_status.is_close\n";
        $sql .= "from order_status\n";
        $sql .= "where order_status.name like '%$text%'\n";

        switch ($order_column) {
            case 0:
                $sql .= "ORDER BY order_status.name $order_dir\n";
                break;
            default:
                $sql .= "ORDER BY order_status.name asc\n";
                break;
        }

        $sql .= "limit {$start},{$length}\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Count($text)
    {
        $sql = "select count(order_status.id_order_status) count\n";
        $sql .= "from order_status\n";
        $sql .= "where order_status.name like '%" . $text . "%'\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function List()
    {
        $sql = "select order_status.id_order_status,\n";
        $sql .= "order_status.name\n";
        $sql .= "from order_status\n";
        $sql .= "order by order_status.name\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function GetInf($id)
    {
        $sql = "select\n";
        $sql .= "order_status.id_order_status,\n";
        $sql .= "DATE_FORMAT(from_unixtime(order_status.creation),'%d/%m/%Y') creation,\n";
        $sql .= "order_status.name,\n";
        $sql .= "order_status.color,\n";
        $sql .= "order_status.message,\n";
        $sql .= "order_status.is_close\n";
        $sql .= "from order_status\n";
        $sql .= "where order_status.id_order_status = '$id'\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Add($data)
    {
        $values = [
            'name' => $data['input-name'],
            'message' => $data['input-message'],
            'color' => $data['input-color'],
        ];

        $this->db->insert('order_status', $values);
    }


    function Edit($key_id, $data)
    {
        $values = [
            'name' => $data['input-name'],
            'message' => $data['input-message'],
            'color' => $data['input-color'],
        ];

        $this->db->where('id_order_status', $key_id);
        $this->db->update('order_status', $values);
    }


    function Delete($key_id)
    {
        $this->db->where('id_order_status', $key_id);
        $this->db->delete('order_status');
    }
}
