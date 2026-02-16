<?php

class ReportCopacolSynthetic_model extends TA_model
{

    function Bot($dt_start, $dt_end, $situation, $id_user, $order, $orderType)
    {
        $sql = "SELECT 
                    ticket_type.id_ticket_type,
                    ticket_type.name,
                    SUM(CASE
                        WHEN
                            ticket_log.id_ticket_status = 10
                                AND ticket_log.id_ticket_type = ticket_type.id_ticket_type
                        THEN
                            1
                        WHEN
                            ticket_log.id_ticket_status = 8
                                AND ticket_log.id_ticket_type = ticket_type.id_ticket_type
                        THEN
                            1
                        ELSE 0
                    END) finalizados,
                    SUM(CASE
                        WHEN
                            ticket_log.id_ticket_status = 10
                                AND ticket_log.id_ticket_type = ticket_type.id_ticket_type
                        THEN
                            1
                        ELSE 0
                    END) qtda_s_rota_lgpd,
                    SUM(CASE
                        WHEN
                            ticket.id_ticket_status = 7
                                AND ticket.id_ticket_type = ticket_type.id_ticket_type
                        THEN
                            1
                        ELSE 0
                    END) abandonado,
                    MEDIA_EFETIVA(SUM(CASE
                                WHEN
                                    ticket_log.id_ticket_status = 10
                                        AND ticket_log.id_ticket_type = ticket_type.id_ticket_type
                                THEN
                                    1
                                WHEN
                                    ticket_log.id_ticket_status = 8
                                        AND ticket_log.id_ticket_type = ticket_type.id_ticket_type
                                THEN
                                    1
                            END),
                            SUM(CASE
                                WHEN
                                    ticket_log.id_ticket_status = 10
                                        AND ticket_log.id_ticket_type = ticket_type.id_ticket_type
                                THEN
                                    1
                                ELSE 0
                            END)) media_efetiva,
                    SUM(CASE
                        WHEN
                            ticket_log.id_ticket_status = 10
                                AND ticket_log.id_ticket_type = ticket_type.id_ticket_type
                        THEN
                            1
                        WHEN
                            ticket_log.id_ticket_status = 8
                                AND ticket_log.id_ticket_type = ticket_type.id_ticket_type
                        THEN
                            1
                        WHEN
                            ticket.id_ticket_status = 7
                                AND ticket.id_ticket_type = ticket_type.id_ticket_type
                        THEN
                            1
                    END) total
                FROM
                    ticket_log
                        INNER JOIN
                    ticket_type ON ticket_log.id_ticket_type = ticket_type.id_ticket_type
                        INNER JOIN
                    ticket ON ticket.id_ticket = ticket_log.id_ticket
                WHERE
                    ticket_type.id_ticket_type IN (16 , 18, 19, 20)
                AND DATE_FORMAT(FROM_UNIXTIME(ticket_log.creation),
                    '%Y-%m-%d') BETWEEN '$dt_start' AND '$dt_end'";

        if ($situation != "") {
            $sql .= "AND ticket_log.id_ticket_type = '$situation'";
        }

        if ($id_user != "") {
            $sql .= "AND ticket_log.id_user  = '$id_user'";
        }

        $sql .= "GROUP BY ticket_log.id_ticket_type , ticket_type.name";

        switch ($order) {
            case 1:
                $sql .= "order by ticket_type.name $orderType\n";
                break;
        }

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Ticket($start, $length, $dt_start, $dt_end, $situation, $id_user, $order, $orderType)
    {
        $sql = "SELECT 
                    ticket.id_ticket_type,
                    ticket_type.name,
                    COUNT(id_ticket) AS aberto,
                    SUM(CASE
                        WHEN ticket_status.is_open = 2 THEN 1
                        ELSE 0
                    END) finalizados,
                    SUM(CASE
                        WHEN ticket_status.is_open = 1 THEN 1
                        ELSE 0
                    END) pendentes,
                    MEDIA_DE_TEMPO(TIMESTAMPDIFF(MINUTE,
                                MIN(DATE_FORMAT(FROM_UNIXTIME(ticket.creation),
                                        '%Y-%m-%d %T')),
                                MAX(DATE_FORMAT(FROM_UNIXTIME(ticket.timestamp_close),
                                        '%Y-%m-%d %T'))),
                            SUM(CASE
                                WHEN ticket_status.is_open = 2 THEN 1
                                ELSE 0
                            END)) media_de_tempo,
                    COUNT(id_ticket) total
                FROM
                    ticket
                        INNER JOIN
                    ticket_type ON ticket.id_ticket_type = ticket_type.id_ticket_type
                        INNER JOIN
                    ticket_status ON ticket.id_ticket_status = ticket_status.id_ticket_status
                WHERE
                    ticket_type.id_ticket_type NOT IN (16 , 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27) 
                AND DATE_FORMAT(FROM_UNIXTIME(ticket.creation),'%Y-%m-%d') BETWEEN '$dt_start' AND '$dt_end'";



        if ($situation != "") {
            $sql .= "AND ticket_type.id_ticket_type = '" . $situation . "'";
        }

        if ($id_user != "") {
            $sql .= "AND ticket.id_user = '" . $id_user . "'";
        }

        switch ($order) {
            case 1:
                $sql .= "order by ticket.id_ticket_type $orderType\n";
                break;
        }

        if ($situation != "" || $id_user != "") {
            $sql .= "GROUP BY ticket.id_ticket_type";
        } else {
            $sql .= "GROUP BY ticket.id_ticket_type
                LIMIT {$start},{$length}";
        }

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Count()
    {
        $sql = "SELECT 
                    COUNT(ticket.id_ticket_type) count
                FROM
                    ticket
                WHERE
                    ticket.id_ticket_type NOT IN (16 , 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27)";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function list()
    {
        $sql = "SELECT 
                    ticket_type.name, ticket_type.id_ticket_type
                FROM
                    ticket_type
                WHERE
                    ticket_type.id_ticket_type  NOT IN (16 , 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27)";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function listUser()
    {
        $sql = "SELECT 
                    user.name, user.id_user
                FROM
                    user";

        $result = $this->db->query($sql);

        return $result->result_array();
    }
    
}
