<?php

class ReportCopacolAnalytical_model extends TA_model
{
    function Get($filter, $selectType, $selectSituation, $dt_start, $dt_end, $start, $length, $order, $orderType)
    {
        $sql = "SELECT\n";

        if ($selectType == 'Ticket' || $selectType == 'selecionar') {
            $sql .= "user.name AS nameUser,\n";
        }

        if ($selectType == 'Bot') {
            $sql .= "contact.creation AS nameUser,\n";
        }

        $sql .= "ticket_type.name AS nameTicketType,
                ticket_status.color AS colorTicketType,
                ticket_status.name AS ticketStatusName,
                contact.key_remote_id,
                contact.full_name AS full_name,
                DATE_FORMAT(from_unixtime(ticket.creation),'%d/%m/%Y %T') creation,
                DATE_FORMAT(from_unixtime(ticket.timestamp_close),'%d/%m/%Y %T') timestamp_close,
                (SELECT
                        TIMESTAMPDIFF(MINUTE,
                                MIN(DATE_FORMAT(FROM_UNIXTIME(ticket.creation),
                                        '%Y-%m-%d %T')),
                                MAX(DATE_FORMAT(FROM_UNIXTIME(ticket.timestamp_close),
                                        '%Y-%m-%d %T')))
                    ) minutes
                FROM
                    ticket
                        INNER JOIN
                    ticket_type ON ticket_type.id_ticket_type = ticket.id_ticket_type
                        INNER JOIN
                    ticket_status ON ticket_status.id_ticket_status = ticket.id_ticket_status
        \n";

        if ($selectType == 'Ticket' || $selectType == 'selecionar') {
            $sql .= "LEFT JOIN user ON user.id_user = ticket.id_user\n";
        }

        $sql .= "INNER JOIN contact ON contact.id_contact = ticket.id_contact
                 INNER JOIN channel ON channel.id_channel = contact.id_channel
                 INNER JOIN config ON config.id_channel = channel.id_channel
            WHERE 
                 DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(ticket.creation),
                 INTERVAL config.timezone HOUR), '%Y-%m-%d') BETWEEN '$dt_start' AND '$dt_end'
        \n";

        if ($selectType == 'Bot') {
            $sql .= "AND ticket_type.name LIKE 'bot_%'\n";
            if ($selectSituation != "selecionar") {
                $sql .= "AND ticket_status.name LIKE '%$selectSituation%'\n";
            }
        }

        if ($selectType == 'Ticket') {
            $sql .= "AND ticket_type.name NOT LIKE 'bot_%'\n";
            if ($selectSituation != "selecionar") {
                $sql .= "AND ticket_status.name LIKE '%$selectSituation%'\n";
            }
        }

        if ($selectType == 'Ticket' || $selectType == 'selecionar') {
            $sql .= "AND ( ( contact.key_remote_id LIKE '%$filter%' ) OR ( user.name LIKE '%$filter%' ) )\n";
        } else {
            $sql .= "AND contact.key_remote_id LIKE '%$filter%'\n";
        }
        $sql .= "group by ticket.id_ticket\n";

        switch ($order) {
            case 0:
                $sql .= "order by nameTicketType $orderType\n";
                break;

            case 1:
                $sql .= "order by nameTicketType $orderType\n";
                break;

            case 2:
                $sql .= "order by department $orderType\n";
                break;

            case 3:
                $sql .= "order by full_name $orderType\n";
                break;

            case 4:
                $sql .= "order by contact.key_remote_id $orderType\n";
                break;

            case 5:
                $sql .= "order by creation $orderType\n";
                break;

            case 6:
                $sql .= "order by timestamp_close $orderType\n";
                break;

            case 7:
                $sql .= "order by minutes $orderType\n";
                break;

            default:
                $sql .= "order by ticketStatusName $orderType\n";
        }

        $sql .= "limit {$start},{$length}\n";

        $result = $this->db->query($sql);

        $data = $result->result_array();

        return $data;
    }


    function Count($filter, $selectType, $selectSituation, $dt_start, $dt_end)
    {
        $sql = "select count(ticket.id_ticket) count\n";
        $sql .= "from ticket\n";
        $sql .= "inner join ticket_status on ticket.id_ticket_status = ticket_status.id_ticket_status\n";
        $sql .= "inner join ticket_type on ticket.id_ticket_type = ticket_type.id_ticket_type\n";
        $sql .= "inner join contact on ticket.id_contact = contact.id_contact\n";
        $sql .= "inner join channel on contact.id_channel = channel.id_channel\n";

        if ($selectType == 'Ticket' || $selectType == 'selecionar') {
            $sql .= "LEFT JOIN user ON user.id_user = ticket.id_user\n";
        }

        $sql .= "inner join config on channel.id_channel = config.id_channel\n";
        $sql .= "where DATE_FORMAT(DATE_ADD(from_unixtime(ticket.creation), INTERVAL config.timezone HOUR),'%Y-%m-%d') between '$dt_start' and '$dt_end'\n";

        if ($selectType == 'Bot') {
            $sql .= "AND ticket_type.name LIKE 'bot_%'\n";
            if ($selectSituation != "selecionar") {
                $sql .= "AND ticket_status.name LIKE '%$selectSituation%'\n";
            }
        }

        if ($selectType == 'Ticket') {
            $sql .= "AND ticket_type.name NOT LIKE 'bot_%'\n";
            if ($selectSituation != "selecionar") {
                $sql .= "AND ticket_status.name LIKE '%$selectSituation%'\n";
            }
        }

        if ($selectType == 'Ticket' || $selectType == 'selecionar') {
            $sql .= "AND ( ( contact.key_remote_id LIKE '%$filter%' ) OR ( user.name LIKE '%$filter%' ) )\n";
        } else {
            $sql .= "AND contact.key_remote_id LIKE '%$filter%'\n";
        }

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function List()
    {
        $sql = "select *\n";
        $sql .= "from ticket_status\n";
        $sql .= "where name not like 'bot_%'\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function ListBot()
    {
        $sql = "select *\n";
        $sql .= "from ticket_status\n";
        $sql .= "where name like 'bot_%'\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }

}
