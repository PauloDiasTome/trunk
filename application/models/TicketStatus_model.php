<?php

class TicketStatus_model extends TA_model
{
    function __construct()
    {
        parent::__construct();
        $this->SetTableName("ticket_status");
        $this->SetPrimaryKey("id_ticket_status");
    }

    function Get($param)
    {
        $this->db->select('
            ticket_status.id_ticket_status,
            ticket_status.name,
            ticket_status.color
        ');

        $this->db->from('ticket_status');
        $this->db->like('LOWER(ticket_status.name)', $this->db->escape_like_str(strtolower($param['text'])));
        $this->db->where('ticket_status.status', 1);

        $count = $this->db->count_all_results('', false);

        switch ($param['order'][0]['column']) {
            case 0:
                $this->db->order_by('ticket_status.name', $param['order'][0]['dir']);
                break;
        }

        $this->db->limit($param['length'], $param['start']);

        return ['query' => $this->db->get()->result_array(), 'count' => $count];
    }

    function GetById($id)
    {
        $this->db->select('
            ticket_status.id_ticket_status,
            ticket_status.name,
            ticket_status.color,
            ticket_status.is_open
        ');

        $this->db->from('ticket_status');
        $this->db->where('ticket_status.id_ticket_status', $id);

        return $this->db->get()->result_array();
    }

    function List()
    {
        $this->db->select('
            ticket_status.id_ticket_status,
            ticket_status.name
        ');

        $this->db->from('ticket_status');
        $this->db->where('ticket_status.status', 1);
        $this->db->order_by('ticket_status.name', 'asc');

        return $this->db->get()->result_array();
    }

    function Add($data)
    {
        $date = new DateTime();

        $values = [
            'creation' => $date->getTImestamp(),
            'name' => trim($data['input-name']),
            'is_open' => $data['is_open'] == 'true' ? 1 : 2,
            'color' => $data['input-color'],
            'status' => 1,
        ];

        $this->db->insert('ticket_status', $values);

        if ($this->db->affected_rows() > 0) {

            $this->db->select('
                ticket_status.name,
                ticket_status.id_ticket_status
            ');

            $this->db->from('ticket_status');
            $this->db->where('ticket_status.status', 1);
            $this->db->order_by('ticket_status.name', 'asc');

            return $this->db->get()->result_array();
        }
    }

    function Edit($key_id, $data)
    {
        $values = [
            'name' => trim($data['input-name']),
            'is_open' => isset($data['is_open']) ? 1 : 2,
            'color' => $data['input-color']
        ];

        $this->db->where('id_ticket_status', $key_id);
        $this->db->update('ticket_status', $values);
    }

    function Delete($key_id)
    {
        $this->db->select('
            ticket.id_contact,
            ticket_status.id_ticket_status,
            ticket_status.is_open
        ');

        $this->db->from('ticket');
        $this->db->join('ticket_status', 'ticket.id_ticket_status = ticket_status.id_ticket_status');
        $this->db->where('ticket.id_ticket_status', $key_id);
        $this->db->where('ticket_status.is_open', 1);

        $query = $this->db->get();

        if ($query->num_rows() == 0) {

            $this->db->select('ticket_status.name');
            $this->db->from('ticket_status');
            $this->db->where('ticket_status.id_ticket_status', $key_id);

            $query = $this->db->get();

            if ($query->num_rows() > 0) {

                $this->db->where('id_ticket_status', $key_id);
                $this->db->update('ticket_status', array('status' => 2));

                return true;
            }
        } else {
            return false;
        }
    }
}
