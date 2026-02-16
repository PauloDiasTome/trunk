<?php

class Modelo_model extends TA_model
{

    function Get($text, $start, $length, $order, $orderType)
    {
        $sql = "select\n";
        $sql .= "ticket_status.id_ticket_status,\n";
        $sql .= "ticket_status.name,\n";
        $sql .= "ticket_status.color\n";
        $sql .= "from ticket_status\n";
        $sql .= "where ticket_status.name like '%" . $text . "%' \n";
        $sql .= "and ticket_status.name not like 'bot_%' \n";

        switch ($order) {
            case 0:
                $sql .= "order by ticket_status.name $orderType \n";
                break;
        }

        $sql .= "limit {$start},{$length}\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function GetInf($id)
    {
        $sql = "select\n";
        $sql .= "ticket_status.id_ticket_status,\n";
        $sql .= "ticket_status.name,\n";
        $sql .= "ticket_status.color,\n";
        $sql .= "ticket_status.is_open\n";
        $sql .= "from ticket_status\n";
        $sql .= "where ticket_status.id_ticket_status = '" . $id . "'\n";
        $sql .= "and ticket_status.name not like 'bot_%' \n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Count($text)
    {
        $sql = "select count(ticket_status.id_ticket_status) count\n";
        $sql .= "from ticket_status\n";
        $sql .= "where ticket_status.name like '%" . $text . "%'\n";
        $sql .= "and ticket_status.name not like 'bot_%' \n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Add($data)
    {
        $date = new DateTime();

        $values = [
            'creation' => $date->getTImestamp(),
            'name' => $data['input-name'],
            'is_open' => isset($data['is_open']) ? 1 : 2,
            'color' => $data['input-color'],
        ];

        $this->db->insert('ticket_status', $values);
    }


    function Edit($key_id, $data)
    {
        $values = [
            'name' => $data['input-name'],
            'is_open' => isset($data['is_open']) ? 1 : 2,
            'color' => $data['input-color']
        ];

        $this->db->where('id_ticket_status', $key_id);
        $this->db->update('ticket_status', $values);
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
        $sql = "select\n";
        $sql .= "ticket.id_ticket_status\n";
        $sql .= "from ticket\n";
        $sql .= "where ticket.id_ticket_status = '$key_id'\n";

        $result = $this->db->query($sql);

        if ($result->num_rows() < 1) {
            $this->db->where('id_ticket_status', $key_id);
            $this->db->delete('ticket_status');
        }
        return $result->result_array();
    }


    function ExportCsv($search)
    {
        $sql = "SELECT
                    ticket_status.name
                FROM
                    ticket_status
				WHERE 
					ticket_status.name not like 'bot_%'
                    AND ticket_status.name LIKE '%" . $search . "%'
                ORDER BY
                    ticket_status.name";

        $result = $this->db->query($sql);

        return $result->result_array();
    }
}
