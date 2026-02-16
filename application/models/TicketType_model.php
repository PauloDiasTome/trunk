<?php

class TicketType_model extends TA_model
{
    public function __construct()
    {
        parent::__construct();
        $this->SetTableName("ticket_type");
        $this->SetPrimaryKey("id_ticket_type");

        $this->SetListOrderBy(["name", "asc"]);
        $this->SetListWhere(array(["is_primary", 1], ["status", 1]));
    }

    function Get($param)
    {
        $this->db->select('
            ticket_type.id_ticket_type,
            ticket_type.name ticket_type_name,
            ticket_type.color ticket_type_color,
            ticket_sla.name ticket_sla_name,
            ticket_sla.color ticket_sla_color,
            user_group.id_user_group ticket_group_id,
            user_group.name ticket_group_name
        ');

        $this->db->from('ticket_type');
        $this->db->join('ticket_sla', 'ticket_type.id_ticket_sla = ticket_sla.id_ticket_sla', 'left');
        $this->db->join('user_group', 'ticket_type.id_user_group = user_group.id_user_group', 'left');
        $this->db->where('ticket_type.is_primary', 1);
        $this->db->where('ticket_type.status', 1);
        $this->db->group_start();
        $this->db->like('LOWER(ticket_type.name)', $this->db->escape_like_str(strtolower($param['text'])));
        $this->db->or_like('LOWER(user_group.name)', $this->db->escape_like_str(strtolower($param['text'])));
        $this->db->group_end();

        $count = $this->db->count_all_results('', false);

        switch ($param['order'][0]['column']) {
            case 0:
                $this->db->order_by("ticket_type.name", $param['order'][0]['dir']);
                break;
            case 1:
                $this->db->order_by("user_group.name", $param['order'][0]['dir']);
                break;
            case 2:
                $this->db->order_by("ticket_sla.name", $param['order'][0]['dir']);
                break;
            default:
                $this->db->order_by("user_group.name", $param['order'][0]['dir']);
                break;
        }

        $this->db->limit($param['length'], $param['start']);

        return ['query' => $this->db->get()->result_array(), 'count' => $count];
    }

    function GetById($key_id)
    {
        return parent::_GetById($key_id);
    }

    function List()
    {
        return parent::_List();
    }

    function Add($data)
    {
        $date = new DateTime();

        $values = [
            'creation' => $date->getTImestamp(),
            'name' => trim($data['input-name']),
            'id_user_group' => $data['user_group'],
            'id_ticket_sla' => $data['ticket_sla']
        ];

        $this->db->insert('ticket_type', $values);

        if ($this->db->affected_rows() > 0) {

            $this->db->select('
                ticket_type.name,
                ticket_type.id_ticket_type
            ');

            $this->db->from('ticket_type');
            $this->db->where('ticket_type.status', 1);
            $this->db->where('ticket_type.is_primary', 1);
            $this->db->order_by('ticket_type.name', 'asc');

            return $this->db->get()->result_array();
        }
    }

    function AddSubtype($key_id, $data)
    {
        $this->db->select('
            ticket_type.id_user_group
        ');

        $this->db->from('ticket_type');
        $this->db->where('ticket_type.id_ticket_type', $key_id);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $date = new DateTime();

            $values = [
                'creation' => $date->getTImestamp(),
                'name' => trim($data['input-name']),
                'is_primary' => 2,
                'id_subtype' => $key_id,
                'id_user_group' => $query->result_array()[0]['id_user_group'],
                'id_ticket_sla' => $data['ticket_sla']
            ];

            $this->db->insert('ticket_type', $values);
        }
    }

    function Edit($key_id, $data)
    {
        $this->db->select('
            ticket_type.id_ticket_type
        ');

        $this->db->from('ticket_type');
        $this->db->where('ticket_type.id_ticket_type', $key_id);
        $this->db->where('ticket_type.is_primary', 2);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $values = [
                'name' => trim($data['input-name']),
                'id_ticket_sla' => $data['ticket_sla']
            ];

            $this->db->where('id_ticket_type', $key_id);
            $this->db->update('ticket_type', $values);
        } else {

            $values = [
                'name' => trim($data['input-name']),
                'id_user_group' => $data['user_group'],
                'id_ticket_sla' => $data['ticket_sla']
            ];

            $this->db->where('id_ticket_type', $key_id);
            $this->db->update('ticket_type', $values);

            $this->db->select('
                ticket_type.id_ticket_type
            ');

            $this->db->from('ticket_type');
            $this->db->where('ticket_type.id_subtype', $key_id);

            if ($query->num_rows() > 0) {

                $values = [
                    'id_user_group' => $data['user_group'],
                ];

                foreach ($query->result_array() as $elm) {

                    $this->db->where('id_ticket_type', $elm['id_ticket_type']);
                    $this->db->update('ticket_type', $values);
                }
            }
        }
    }

    function GetSubtype($key_id, $param)
    {
        $this->db->select('
            ticket_type.id_ticket_type,
            ticket_type.name ticket_type_name,
            ticket_type.color ticket_type_color,
            ticket_sla.name ticket_sla_name,
            ticket_sla.color ticket_sla_color,
            user_group.id_user_group ticket_group_id,
            user_group.name ticket_group_name
        ');

        $this->db->from('ticket_type');
        $this->db->join('ticket_sla', 'ticket_type.id_ticket_sla = ticket_sla.id_ticket_sla', 'left');
        $this->db->join('user_group', 'ticket_type.id_user_group = user_group.id_user_group', 'left');
        $this->db->where('ticket_type.id_subtype', $key_id);
        $this->db->where('ticket_type.status', 1);
        $this->db->group_start();
        $this->db->like('LOWER(ticket_type.name)', $this->db->escape_like_str(strtolower($param['text'])));
        $this->db->or_like('LOWER(user_group.name)', $this->db->escape_like_str(strtolower($param['text'])));
        $this->db->group_end();

        $count = $this->db->count_all_results('', false);

        switch ($param['order'][0]['column']) {
            case 0:
                $this->db->order_by('ticket_type_name', $param['order'][0]['dir']);
                break;
            case 1:
                $this->db->order_by('ticket_group_name', $param['order'][0]['dir']);
                break;
            case 2:
                $this->db->order_by('ticket_sla_name', $param['order'][0]['dir']);
                break;
            default:
                $this->db->order_by('ticket_group_name', $param['order'][0]['dir']);
                break;
        }

        $this->db->limit($param['length'], $param['start']);

        return ['query' => $this->db->get()->result_array(), 'count' => $count];
    }

    function isSubtype($key_id)
    {
        $this->db->select('
            ticket_type.id_ticket_type
        ');

        $this->db->from('ticket_type');
        $this->db->where('ticket_type.id_ticket_type', $key_id);
        $this->db->where('ticket_type.is_primary', 1);

        $query = $this->db->get('');

        return $query->num_rows() > 0 ? false : true;
    }

    function ListSubtype($key_id)
    {
        $this->db->select('
            ticket_type.id_subtype id_subtype,
            ticket_type.name name
        ');

        $this->db->from('ticket_type');
        $this->db->join('ticket_sla', 'ticket_type.id_ticket_sla = ticket_sla.id_ticket_sla', 'left');
        $this->db->join('user_group', 'ticket_type.id_user_group = user_group.id_user_group');
        $this->db->where('ticket_type.id_ticket_type', $key_id);

        return $this->db->get()->result_array();
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
        $this->db->where('ticket.id_ticket_type', $key_id);
        $this->db->where('ticket_status.is_open', 1);

        $query = $this->db->get();

        if ($query->num_rows() == 0) {

            $this->db->select('*');
            $this->db->from('ticket_type');
            $this->db->where('ticket_type.id_ticket_type', $key_id);

            $query = $this->db->get();

            $name = '[removido] ' . $query->result_array()[0]['name'];

            if ($query->num_rows() > 0) {

                $this->db->select('*');
                $this->db->from('ticket_type');
                $this->db->where('ticket_type.id_subtype', $key_id);

                $query = $this->db->get();

                $this->db->trans_start();

                if ($query->num_rows() > 0) {

                    foreach ($query->result_array() as $key) {

                        $values = [
                            'status' => 2,
                            'name ' => '[removido] ' . $key['name'],
                        ];

                        $this->db->where('id_ticket_type', $key["id_ticket_type"]);
                        $this->db->update('ticket_type',  $values);
                    }
                }

                $this->db->where('id_ticket_type', $key_id);
                $this->db->update('ticket_type', array('status' => 2, 'name' => $name));

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    return ["errors" => ["code" => "TA-001"]];
                } else {
                    return ["success" => ["status" => true]];
                }
            }
        } else {
            return ["errors" => ["code" => "TA-002"]];
        }
    }
}
