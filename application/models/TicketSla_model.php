<?php

class TicketSla_model extends TA_model
{

    function Get($text, $start, $length, $order_column, $order_dir)
    {
        $sql = "select\n";
        $sql .= "ticket_sla.id_ticket_sla,\n";
        $sql .= "ticket_sla.name,\n";
        $sql .= "ticket_sla.time_sla,\n";
        $sql .= "ticket_sla.color\n";
        $sql .= "from ticket_sla\n";
        $sql .= "where ticket_sla.name like '%" . $text . "%'\n";

        switch ($order_column) {
            case 0:
                $sql .= "order by ticket_sla.name $order_dir \n";
                break;
        }

        $sql .= "limit {$start},{$length}\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function GetInf($id)
    {
        $sql = "select\n";
        $sql .= "ticket_sla.id_ticket_sla,\n";
        $sql .= "ticket_sla.name,\n";
        $sql .= "ticket_sla.color,\n";
        $sql .= "ticket_sla.time_sla\n";
        $sql .= "from ticket_sla\n";
        $sql .= "where ticket_sla.id_ticket_sla = '" . $id . "';\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function List()
    {
        $sql = "select\n";
        $sql .= "ticket_sla.id_ticket_sla,\n";
        $sql .= "concat(ticket_sla.name,' (',ticket_sla.time_sla,')') name\n";
        $sql .= "from ticket_sla\n";
        $sql .= "order by ticket_sla.name\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Count($text)
    {
        $sql = "select count(ticket_sla.id_ticket_sla) count\n";
        $sql .= "from ticket_sla\n";
        $sql .= "where ticket_sla.name like '%" . $text . "%'\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Add($data)
    {
        $date = new DateTime();

        $values = [
            'creation' => $date->getTImestamp(),
            'name' => trim($data['input-name']),
            'time_sla' => $data['input-tempo'],
            'color' => $data['input-color']
        ];

        $this->db->insert('ticket_sla', $values);
    }


    function Edit($key_id, $data)
    {
        $values = [
            'name' => trim($data['input-name']),
            'time_sla' => $data['input-tempo'],
            'color' => $data['input-color']
        ];

        $this->db->where('id_ticket_sla', $key_id);
        $this->db->update('ticket_sla', $values);
    }


    function Delete($key_id)
    {
        $this->db->where('id_ticket_sla', $key_id);
        $this->db->delete('ticket_sla');
    }

}
