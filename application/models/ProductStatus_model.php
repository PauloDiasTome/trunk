<?php

class ProductStatus_model extends TA_model
{

    function Get($text, $start, $length)
    {
        $sql = "select\n";
        $sql .= "order_status.id_order_status,\n";
        $sql .= "order_status.name,\n";
        $sql .= "order_status.message,\n";
        $sql .= "order_status.color\n";
        $sql .= "from order_status\n";
        $sql .= "where order_status.name like '%" . $text . "%' \n";
        $sql .= "order by order_status.name \n";
        $sql .= "limit {$start},{$length}\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function GetInf($id)
    {
        $sql = "select\n";
        $sql .= "order_status.id_order_status,\n";
        $sql .= "order_status.name,\n";
        $sql .= "order_status.message,\n";
        $sql .= "order_status.color,\n";
        $sql .= "order_status.is_close\n";
        $sql .= "from order_status\n";
        $sql .= "where order_status.id_order_status = '" . $id . "'\n";

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


    function Add($data)
    {
        $date = new DateTime();

        $values = [
            'creation' => $date->getTImestamp(),
            'name' => $data['input-name'],
            'message' => $data['textarea-message'],
            'is_close' => isset($data['is_open']) ? 1 : 2,
            'color' => $data['input-color'],
        ];

        $this->db->insert('order_status', $values);
    }


    function Edit($key_id, $data)
    {
        $values = [
            'name' => $data['input-name'],
            'message' => $data['textarea-message'],
            'is_close' => isset($data['is_open']) ? 1 : 2,
            'color' => $data['input-color']
        ];

        $this->db->where('id_order_status', $key_id);
        $this->db->update('order_status', $values);
    }


    function List()
    {
        $sql = "select\n";
        $sql .= "ticket_status.id_ticket_status,\n";
        $sql .= "ticket_status.name\n";
        $sql .= "from ticket_status\n";
        $sql .= "WHERE ticket_status.name not like 'bot_%' \n";
        $sql .= "order by ticket_status.name\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Delete($key_id)
    {
        $this->db->where('id_order_status', $key_id);
        $this->db->delete('order_status');
    }


    function ExportCsv()
    {
        $sql = "SELECT
                    ticket_status.name nome
                FROM
                    ticket_status
				WHERE 
					ticket_status.name not like 'bot_%'
                ORDER BY
                    ticket_status.name";

        $result = $this->db->query($sql);

        return $result->result_array();
    }
}
