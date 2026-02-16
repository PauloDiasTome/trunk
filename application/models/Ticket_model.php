<?php

class Ticket_model extends TA_model
{

    function Get($text, $ticket_type, $ticket_status, $dt_start, $dt_end, $start, $length, $order_column, $order_dir)
    {
        $ticketTypes = "";
        $ticketStatus = "";

        if ($ticket_type != "NULL") {
            foreach ($ticket_type as $key => $val) {
                $ticketTypes .= "," . $val;
            }
            $ticketTypes = substr($ticketTypes, 1);
        }

        if ($ticket_status != "NULL") {
            foreach ($ticket_status as $key => $val) {
                $ticketStatus .= "," . $val;
            }
            $ticketStatus = substr($ticketStatus, 1);
        }

        $sql = "SELECT 
                    ticket.id_ticket,
                    DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(ticket.creation), INTERVAL config.timezone HOUR), '%d/%m/%Y %T') creation,
                    user.name user,
                    company.id_company,
                    company.fantasy_name,
                    ticket_type.name type,
                    ticket_type.color type_color,
                    ticket_status.name status,
                    ticket_status.color status_color
                FROM
                    ticket
                        INNER JOIN
                    ticket_type ON ticket.id_ticket_type = ticket_type.id_ticket_type
                        INNER JOIN
                    ticket_status ON ticket.id_ticket_status = ticket_status.id_ticket_status
                        INNER JOIN
                    user ON user.id_user = ticket.id_user
                        INNER JOIN
                    company ON ticket.id_company = company.id_company
                        INNER JOIN
                    channel ON user.key_remote_id = channel.id  
                        INNER JOIN
                    config ON channel.id_channel = config.id_channel      
                WHERE
                    (LOWER(ticket_type.name) LIKE LOWER('%$text%') OR LOWER(ticket.comment) LIKE LOWER('%$text%') OR LOWER(user.last_name) LIKE LOWER('%$text%') OR LOWER(company.fantasy_name) LIKE LOWER('%$text%')) AND (ticket_type.name NOT LIKE 'bot_%' OR ticket_status.name NOT LIKE 'bot_%')
                AND user.status IN (1,2,4)";

        if (!empty($ticketTypes)) {
            $sql .= "AND ticket_type.id_ticket_type IN ($ticketTypes)\n";
        }

        if (!empty($ticketStatus)) {
            $sql .= "AND ticket_status.id_ticket_status IN ($ticketStatus)\n";
        }

        if ($dt_start != "") {
            $sql .= "AND DATE_FORMAT(from_unixtime(ticket.creation),'%Y-%m-%d') BETWEEN '{$dt_start}' AND '{$dt_end}'";
        }

        switch ($order_column) {
            case 0:
                $sql .= "order by ticket.id_ticket $order_dir\n";
                break;

            case 1:
                $sql .= "order by company.fantasy_name $order_dir \n";
                break;

            case 2:
                $sql .= "order by user.name $order_dir \n";
                break;

            case 3:
                $sql .= "order by ticket_type.name $order_dir \n";
                break;

            case 4:
                $sql .= "order by ticket_status.name $order_dir \n";
                break;

            default:
                $sql .= "order by creation $order_dir \n";
        }

        $sql .= "limit {$start},{$length}\n";

        $result = $this->db->query($sql);

        $data = $result->result_array();

        return $data;
    }


    function GetInf($id)
    {
        $sql = "select\n";
        $sql .= "DATE_FORMAT(from_unixtime(ticket.creation),'%d/%m/%Y %T') creation,\n";
        $sql .= "ticket.id_ticket,\n";
        $sql .= "contact.full_name,\n";
        $sql .= "ticket.id_company,\n";
        $sql .= "ticket.id_ticket_type,\n";
        $sql .= "ticket.id_subtype,\n";
        $sql .= "ticket.id_ticket_status,\n";
        $sql .= "ticket.comment,\n";
        $sql .= "user.last_name\n";
        $sql .= "from ticket left join contact on ticket.id_contact = contact.id_contact\n";
        $sql .= "inner join user on ticket.id_user = user.id_user\n";
        $sql .= "inner join ticket_type on ticket.id_ticket_type = ticket_type.id_ticket_type\n";
        $sql .= "inner join ticket_status on ticket.id_ticket_status = ticket_status.id_ticket_status\n";
        $sql .= "where ticket.id_ticket = '" . $id . "'\n";
        $sql .= "and ticket_status.name not like 'bot_%' \n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function GetEvent($id)
    {
        $sql = "select\n";
        $sql .= "DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(ticket_log.creation), INTERVAL config.timezone HOUR), '%d/%m/%Y %T') creation,\n";
        $sql .= "ticket_type.name type,\n";
        $sql .= "ticket_type.color type_color,\n";
        $sql .= "case\n";
        $sql .= "when ticket_status.status = 2 then CONCAT(ticket_status.name, ' ', '( REMOVIDO )')\n";
        $sql .= "else ticket_status.name\n";
        $sql .= "end status,\n";
        $sql .= "ticket_status.color status_color,\n";
        $sql .= "user.last_name,\n";
        $sql .= "ticket_log.comment\n";
        $sql .= "from ticket_log inner join ticket_status on ticket_log.id_ticket_status = ticket_status.id_ticket_status\n";
        $sql .= "inner join ticket_type on ticket_log.id_ticket_type = ticket_type.id_ticket_type\n";
        $sql .= "inner join user on ticket_log.id_user = user.id_user\n";
        $sql .= "inner join channel on user.key_remote_id = channel.id\n";
        $sql .= "inner join config on channel.id_channel = config.id_channel\n";
        $sql .= "where ticket_log.id_ticket = $id\n";
        $sql .= "and ticket_type.name not like 'bot_%'\n";
        $sql .= "order by ticket_log.creation desc\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Count($text, $ticket_type, $ticket_status, $dt_start, $dt_end)
    {
        $ticketTypes = "";
        $ticketStatus = "";

        if ($ticket_type != "NULL") {
            foreach ($ticket_type as $key => $val) {
                $ticketTypes .= "," . $val;
            }
            $ticketTypes = substr($ticketTypes, 1);
        }

        if ($ticket_status != "NULL") {
            foreach ($ticket_status as $key => $val) {
                $ticketStatus .= "," . $val;
            }
            $ticketStatus = substr($ticketStatus, 1);
        }

        $sql = "SELECT 
                    COUNT(ticket.id_ticket) count
                   FROM
                    ticket
                        INNER JOIN
                    ticket_type ON ticket.id_ticket_type = ticket_type.id_ticket_type
                        INNER JOIN
                    ticket_status ON ticket.id_ticket_status = ticket_status.id_ticket_status
                        INNER JOIN
                    user ON user.id_user = ticket.id_user
                        INNER JOIN
                    company ON ticket.id_company = company.id_company
                        INNER JOIN
                    channel ON user.key_remote_id = channel.id  
                        INNER JOIN
                    config ON channel.id_channel = config.id_channel 
                WHERE
                    (LOWER(ticket_type.name) LIKE LOWER('%$text%') OR LOWER(ticket.comment) LIKE LOWER('%$text%') OR LOWER(user.last_name) LIKE LOWER('%$text%') OR LOWER(company.fantasy_name) LIKE LOWER('%$text%')) AND (ticket_type.name NOT LIKE 'bot_%' OR ticket_status.name NOT LIKE 'bot_%')
                AND user.status IN (1,2,4)";


        if (!empty($ticketTypes)) {
            $sql .= "AND ticket_type.id_ticket_type IN ($ticketTypes)\n";
        }

        if (!empty($ticketStatus)) {
            $sql .= "AND ticket_status.id_ticket_status IN ($ticketStatus)\n";
        }

        if ($dt_start != "") {
            $sql .= "AND from_unixtime(ticket.creation) BETWEEN '{$dt_start}' AND '{$dt_end}'";
        }

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function CheckIfTicketOpen($key_id)
    {
        $sql = "SELECT \n";
        $sql .= "ticket.id_ticket, ticket_status.name, ticket_status.is_open \n";
        $sql .= "FROM ticket \n";
        $sql .= "inner join ticket_status \n";
        $sql .= "on ticket_status.id_ticket_status = ticket.id_ticket_status \n";
        $sql .= "where ticket.id_ticket = {$key_id} ";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Add($data)
    {
        $date = new DateTime();

        if (isset($data['input-contact']) != "") {
            $id_contact = $data['input-contact'];
        } else {
            $id_contact = 0;
        }

        $sql = "select\n";
        $sql .= "user.id_user\n";
        $sql .= "from user\n";
        $sql .= "where user.key_remote_id = '{$this->session->userdata('key_remote_id')}'";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            $id_user = $result->result_array()[0]['id_user'];
        } else {
            return;
        };

        $values = [
            'creation' => $date->getTimestamp(),
            'id_ticket_type' => $data['input_ticket_type'],
            'id_ticket_status' => $data['input_ticket_status'],
            'comment' => trim($data['input-comment']),
            'id_contact' => $id_contact == 0 ? null : $id_contact,
            'id_user' => $id_user == 0 ? null : $id_user,
            'id_company' => $data['input_ticket_company'],
            'id_subtype' => $data['input-ticket-subtype']
        ];

        $this->db->insert('ticket', $values);

        $insert_id = $this->db->insert_id();

        $values = [
            'creation' => $date->getTimestamp(),
            'id_ticket' => $insert_id,
            'id_user' => $this->session->userdata('id_user'),
            'id_ticket_type' => $data['input_ticket_type'],
            'id_ticket_status' => $data['input_ticket_status'],
            'comment' => trim($data['input-comment'])
        ];

        $this->db->insert('ticket_log', $values);
    }


    function Edit($key_id, $data)
    {
        $date = new DateTime();

        $values = [
            'id_ticket_type' => $data['input_ticket_type'],
            'id_ticket_status' => $data['input_ticket_status'],
            'comment' => trim($data['input-comment']),
            'id_company' => $data['input_ticket_company'],
            'id_subtype' => $data['input-ticket-subtype']
        ];

        $this->db->where('id_ticket', $key_id);
        $this->db->update('ticket', $values);

        $values = [
            'creation' => $date->getTimestamp(),
            'id_ticket' => $key_id,
            'id_user' => $this->session->userdata('id_user'),
            'id_ticket_type' => $data['input_ticket_type'],
            'id_ticket_status' => $data['input_ticket_status'],
            'comment' => trim($data['input-comment'])
        ];

        $this->db->insert('ticket_log', $values);
        // * INÍCIO * Inserção de dado na coluna timestamp_close da table ticket //
        $sql = "select\n";
        $sql .= "ticket.id_ticket,\n";
        $sql .= "ticket_status.id_ticket_status,\n";
        $sql .= "ticket_status.is_open\n";
        $sql .= "from ticket\n";
        $sql .= "inner join ticket_status on ticket_status.id_ticket_status = ticket.id_ticket_status\n";
        $sql .= "where ticket.id_ticket = {$key_id} AND ticket_status.is_open = 2\n";
        $sql .= "limit 1\n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            $this->db->where('id_ticket', $key_id);
            $this->db->update('ticket', array('timestamp_close' => $values['creation']));
        }

        // * FIM * Inserção de dado na coluna timestamp_close da table ticket //
    }


    function List()
    {
        $sql = "select\n";
        $sql .= "ticket.id_ticket,\n";
        $sql .= "DATE_FORMAT(from_unixtime(ticket.creation),'%d/%m/%Y %T') creation,\n";
        $sql .= "contact.key_remote_id,\n";
        $sql .= "contact.full_name client,\n";
        $sql .= "user.name user,\n";
        $sql .= "ticket_type.name type,\n";
        $sql .= "ticket_type.color type_color,\n";
        $sql .= "ticket_status.name status,\n";
        $sql .= "ticket_status.color status_color,\n";
        $sql .= "from ticket inner join ticket_type on ticket.id_ticket_type = ticket_type.id_ticket_type\n";
        $sql .= "inner join ticket_status on ticket.id_ticket_status = ticket_status.id_ticket_status\n";
        $sql .= "inner join user on user.id_user = ticket.id_user\n";
        $sql .= "inner join contact on ticket.id_contact = contact.id_contact\n";
        $sql .= "where ticket_status.name not like 'bot_%' \n";
        $sql .= "order by ticket.id_ticket desc\n";

        $result = $this->db->query($sql);

        $data = $result->result_array();

        return $data;
    }


    function ListCompany()
    {
        $sql = "select\n";
        $sql .= "company.id_company,\n";
        $sql .= "company.fantasy_name,\n";
        $sql .= "company.corporate_name\n";
        $sql .= "FROM company\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function ListSubtype($key_id)
    {
        $sql = "select\n";
        $sql .= "ticket_type.name,\n";
        $sql .= "ticket_type.id_subtype,\n";
        $sql .= "ticket_type.id_ticket_type\n";
        $sql .= "FROM ticket_type\n";
        $sql .= "WHERE ticket_type.id_subtype = '$key_id'\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function ListContact($text)
    {
        $text = str_replace("%20", " ", $text);

        $sql = "SELECT 
                    contact.full_name,
                    contact.key_remote_id,
                    contact.id_contact
                FROM 
                    contact 
                WHERE contact.is_private = 1 and contact.deleted = 1 AND ((contact.full_name LIKE '%{$text}%') OR (contact.key_remote_id LIKE '%{$text}%'))
                LIMIT 1000";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Delete($key_id)
    {
        $check = $this->CheckIfTicketOpen($key_id);

        if (isset($check[0]['is_open']) and $check[0]['is_open'] > 1) {

            // Remove o log
            $this->db->where('id_ticket', $key_id);
            $this->db->delete('ticket_log');

            // Remove da lista de espera
            $this->db->where('id_ticket', $key_id);
            $this->db->delete('ticket_wait_list');

            // Deleta o ticket
            $this->db->where('id_ticket', $key_id);
            $this->db->delete('ticket');

            return 1;
        } else {
            return 0;
        }
    }
}
