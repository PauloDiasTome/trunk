<?php

class DashboardCommunication_model extends TA_Model
{
    function listChannels()
    {
        $this->db->select("channel.id_channel");
        $this->db->select("CASE 
                          WHEN LENGTH(channel.name) > 40 THEN CONCAT(channel.name, ' (', SUBSTR(channel.id, 1, 12),'...)') 
                          ELSE CONCAT(channel.name, ' (', channel.id, ')') 
                      END as name", FALSE);
        $this->db->from("channel");
        $this->db->join('config', 'config.id_channel = channel.id_channel');
        $this->db->where("channel.type", 2);
        $this->db->where("channel.status", 1);

        return $this->db->get()->result_array();
    }

    function listBroadcast()
    {
        $this->db->select("broadcast_schedule.id_broadcast_schedule");
        $this->db->select("broadcast_schedule.id_channel");
        $this->db->select("CASE 
                          WHEN LENGTH(broadcast_schedule.title) > 50 THEN CONCAT(SUBSTR(broadcast_schedule.title, 1, 34), '...') 
                          ELSE CONCAT(broadcast_schedule.title) 
                      END as name", FALSE);
        $this->db->from("broadcast_schedule");
        $this->db->join("channel", "broadcast_schedule.id_channel = channel.id_channel", "inner");
        $this->db->where("channel.type", 2);
        $this->db->where("channel.status", 1);
        $this->db->where("DATE_FORMAT(FROM_UNIXTIME(broadcast_schedule.schedule), '%Y/%m/%d') BETWEEN DATE_ADD(CURRENT_DATE(), INTERVAL - 6 DAY) AND CURRENT_DATE()");
        $this->db->where('(broadcast_schedule.status = 2 AND (broadcast_schedule.is_wa_broadcast = 1 OR broadcast_schedule.is_wa_status = 1))');
        $this->db->order_by("broadcast_schedule.title", "ASC");

        return $this->db->get()->result_array();
    }

    function getInteraction($params)
    {
        $this->db->select("
            SUM(broadcast_schedule.count) as message_send,
            ROUND(PERCENTAGE(SUM(broadcast_schedule.count),SUM(broadcast_schedule.message_send))) as percent_send,
            CASE
                WHEN SUM(broadcast_schedule.message_receipt) > SUM(broadcast_schedule.message_send) THEN SUM(broadcast_schedule.message_send)
                ELSE SUM(broadcast_schedule.message_receipt)
            END message_receipt,
            CASE
                WHEN SUM(broadcast_schedule.message_receipt) > SUM(broadcast_schedule.message_send) THEN ROUND(PERCENTAGE(SUM(broadcast_schedule.count),SUM(broadcast_schedule.message_send)))
                ELSE ROUND(PERCENTAGE(SUM(broadcast_schedule.count), SUM(broadcast_schedule.message_receipt)))
            END percent_receipt,
            ROUND(PERCENTAGE(SUM(broadcast_schedule.count), SUM(broadcast_schedule.message_receipt))) as percent_receipt,
            SUM(broadcast_schedule.message_read) as message_read,
            ROUND(PERCENTAGE(SUM(broadcast_schedule.count), SUM(broadcast_schedule.message_read))) as percent_msg_read,
            SUM(broadcast_schedule.message_reactions) as message_reactions,
            ROUND(PERCENTAGE(SUM(broadcast_schedule.count), SUM(broadcast_schedule.message_reactions))) as percent_reactions,
        ");

        $this->db->from('broadcast_schedule');
        $this->db->join('channel', 'broadcast_schedule.id_channel = channel.id_channel', 'inner');
        $this->db->join('config', 'channel.id_channel = config.id_channel', 'inner');
        $this->db->where('(broadcast_schedule.status = 2 AND (broadcast_schedule.is_wa_broadcast = 1 OR broadcast_schedule.is_wa_status = 1))');

        if (!empty($params['period'])) {
            $this->db->where("DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.schedule), INTERVAL config.timezone HOUR),'%Y-%m-%d') BETWEEN '{$params['period']->dt_start}' AND '{$params['period']->dt_end}'");
        }

        if (!empty($params['id_channel'])) {
            $this->db->where('broadcast_schedule.id_channel', $params['id_channel']);
        }

        if (!empty($params['id_broadcast'])) {
            $this->db->where('broadcast_schedule.id_broadcast_schedule', $params['id_broadcast']);
        }

        $result = $this->db->get();

        $response = [];

        foreach ($result->row_array() as $idx => $val) {

            if (stripos($idx, "message") !== false)  $obj = new stdClass();

            if (stripos($idx, "percent") !== false) {
                $percent = $val == NULL ? 0 : $val;
                $percent = $percent >= 100 ? 100 : $percent;
                $obj->percent = $percent . "%";
                array_push($response, $obj);
            } else {
                $total = $val == NULL ? 0 : $val;
                $obj->total =  str_replace(',', '.', number_format($total));
            }
        }

        return $response;
    }

    function getAudience($params)
    {

        $this->db->select('COUNT(contact.id_contact) as total');
        $this->db->from('contact');
        $this->db->join('channel', 'contact.id_channel = channel.id_channel');
        $this->db->join('config', 'config.id_channel = channel.id_channel');
        $this->db->where('contact.deleted', 1);
        $this->db->where('contact.spam', 1);
        $this->db->where('contact.verify', 2);
        $this->db->where('contact.exist', 1);
        $this->db->where('channel.status', 1);
        $this->db->where('channel.is_broadcast', 1);
        $this->db->where('config.is_broadcast', 1);
        $this->db->where('config.attendance_enable', 2);

        if (empty($params['id_channel'])) {
            $this->db->where("channel.type", 2);
        } else {
            $this->db->where('channel.id_channel', $params['id_channel']);
        }

        if (!empty($params['period'])) {
            $this->db->where("DATE_FORMAT(FROM_UNIXTIME(contact.creation), '%Y-%m-%d') BETWEEN '{$params['period']->dt_start}' AND '{$params['period']->dt_end}'", null, false);
        }

        $totalSubquery = $this->db->get_compiled_select();

        $this->db->select('COUNT(contact.id_contact) as active');
        $this->db->from('contact');
        $this->db->join('channel', 'contact.id_channel = channel.id_channel');
        $this->db->join('config', 'config.id_channel = channel.id_channel');
        $this->db->where('contact.deleted', 1);
        $this->db->where('contact.spam', 1);
        $this->db->where('contact.verify', 2);
        $this->db->where('contact.exist', 1);
        $this->db->where('channel.status', 1);
        $this->db->where('channel.is_broadcast', 1);
        $this->db->where('config.is_broadcast', 1);
        $this->db->where('config.attendance_enable', 2);

        if (empty($params['id_channel'])) {
            $this->db->where("channel.type", 2);
        } else {
            $this->db->where('channel.id_channel', $params['id_channel']);
        }

        if (empty($params['period'])) {
            $this->db->group_start();
            $this->db->where("DATE_FORMAT(FROM_UNIXTIME(contact.broadcast_receipt), '%Y/%m/%d') BETWEEN DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY) AND CURRENT_DATE()", null, false);
            $this->db->or_group_start();
            $this->db->where('contact.broadcast_receipt IS NULL', null, false);
            $this->db->where("DATE_FORMAT(FROM_UNIXTIME(contact.creation), '%Y/%m/%d') BETWEEN DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY) AND CURRENT_DATE()", null, false);
            $this->db->group_end();
            $this->db->group_end();
        } else {
            $dt_start = $params['period']->dt_start;
            $dt_end = $params['period']->dt_end;

            $seven_days = abs((strtotime($dt_start) - strtotime($dt_end)) / (60 * 60 * 24));

            if ($seven_days <= 7) {
                $this->db->where("DATE_FORMAT(FROM_UNIXTIME(contact.creation), '%Y-%m-%d') BETWEEN '{$params['period']->dt_start}' AND '{$params['period']->dt_end}'", null, false);
            } else {
                $this->db->where("DATE_FORMAT(FROM_UNIXTIME(contact.creation), '%Y-%m-%d') BETWEEN '{$params['period']->dt_start}' AND '{$params['period']->dt_end}'", null, false);
                $this->db->where("DATE_FORMAT(FROM_UNIXTIME(contact.broadcast_receipt), '%Y/%m/%d') BETWEEN DATE_ADD(CURRENT_DATE(), INTERVAL - 30 DAY) AND CURRENT_DATE()");
            }
        }

        $activeSubquery = $this->db->get_compiled_select();

        $this->db->select('COUNT(contact.id_contact) as inactive');
        $this->db->from('contact');
        $this->db->join('channel', 'contact.id_channel = channel.id_channel');
        $this->db->join('config', 'config.id_channel = channel.id_channel');
        $this->db->where('contact.deleted', 1);
        $this->db->where('contact.spam', 1);
        $this->db->where('contact.verify', 2);
        $this->db->where('contact.exist', 1);
        $this->db->where('channel.status', 1);
        $this->db->where('channel.is_broadcast', 1);
        $this->db->where('config.is_broadcast', 1);
        $this->db->where('config.attendance_enable', 2);

        if (empty($params['id_channel'])) {
            $this->db->where("channel.type", 2);
        } else {
            $this->db->where('channel.id_channel', $params['id_channel']);
        }

        if (empty($params['period'])) {
            $this->db->where("(DATE_FORMAT(FROM_UNIXTIME(contact.creation), '%Y/%m/%d') < DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY))");
            $this->db->where("(DATE_FORMAT(FROM_UNIXTIME(contact.broadcast_receipt), '%Y/%m/%d') < DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY) OR contact.broadcast_receipt IS NULL)");
        } else {
            $this->db->where("DATE_FORMAT(FROM_UNIXTIME(contact.creation), '%Y-%m-%d') BETWEEN '{$params['period']->dt_start}' AND '{$params['period']->dt_end}'", null, false);
            $this->db->where("contact.creation < UNIX_TIMESTAMP(DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY))", null, false);
            $this->db->where("(DATE_FORMAT(FROM_UNIXTIME(contact.broadcast_receipt), '%Y/%m/%d') < DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY) OR contact.broadcast_receipt IS NULL)");
        }

        $inactiveSubquery = $this->db->get_compiled_select();

        $this->db->select('*');
        $this->db->select("IF(total = 0, 0, ROUND(PERCENTAGE(total, active))) as active_percent", FALSE);
        $this->db->select("IF(total = 0, 0, ROUND(PERCENTAGE(total, inactive))) as inactive_percent", FALSE);
        $this->db->from("($totalSubquery) total, ($activeSubquery) active, ($inactiveSubquery) inactive");

        $query = $this->db->get();

        $response = (object) $query->row_array();

        $response->alert = new stdClass;
        $response->alert->inactive_one = "none";
        $response->alert->inactive_two = "none";
        $response->alert->inactive_three = "none";
        $response->alert->inactive_four = "none";
        $response->alert->total_one = "none";
        $response->alert->total_two = "none";

        if (intval($response->inactive_percent) <= 10) $response->alert->inactive_one = "flex";
        if (intval($response->inactive_percent) >= 10 && intval($response->inactive_percent) <= 25) $response->alert->inactive_two = "flex";
        if (intval($response->inactive_percent) >= 25) $response->alert->inactive_three = "flex";
        if (intval($response->inactive_percent) >= 25) $response->alert->inactive_four = "flex";
        if (intval($response->total) <= 1000) $response->alert->total_one = "flex";
        if (intval($response->total) >= 5000) $response->alert->total_two = "flex";

        return $response;
    }

    function getCampaigns($param)
    {
        $this->db->select('broadcast_schedule.id_broadcast_schedule');
        $this->db->select("DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.schedule), INTERVAL config.timezone HOUR),'%d/%m/%Y %T') as schedule", FALSE);
        $this->db->select('broadcast_schedule.title');
        $this->db->select('broadcast_schedule.data');
        $this->db->select('broadcast_schedule.token');
        $this->db->select('broadcast_schedule.is_wa_broadcast');
        $this->db->select('broadcast_schedule.is_waba_broadcast');
        $this->db->select('broadcast_schedule.is_wa_community');
        $this->db->select('broadcast_schedule.is_wa_status');

        $this->db->from('broadcast_schedule');
        $this->db->join('channel', 'broadcast_schedule.id_channel = channel.id_channel');
        $this->db->join('config', 'channel.id_channel = config.id_channel');

        $this->db->where('(broadcast_schedule.is_wa_broadcast = 1 OR broadcast_schedule.is_wa_community = 1 OR broadcast_schedule.is_waba_broadcast = 1 OR broadcast_schedule.is_wa_status = 1)');
        $this->db->where_in('broadcast_schedule.status', explode(',', $param['status']));

        if (empty($param['period']['dt_start'])) {
            $this->db->where("DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.creation), INTERVAL config.timezone HOUR), '%Y/%m/%d') BETWEEN DATE_ADD(CURRENT_DATE(), INTERVAL - 6 DAY) AND CURRENT_DATE()", NULL, FALSE);
        } else {
            $this->db->where("DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.creation), INTERVAL config.timezone HOUR), '%Y-%m-%d') BETWEEN '{$param['period']['dt_start']}' AND '{$param['period']['dt_end']}'", NULL, FALSE);
        }

        if (!empty($param['broadcast'])) {
            $this->db->where('broadcast_schedule.id_broadcast_schedule', $param['broadcast']);
        }

        $count = $this->db->count_all_results('', false);

        $this->db->order_by('broadcast_schedule.schedule', 'DESC');
        $this->db->limit($param['length'], $param['start']);

        return ['query' => $this->db->get()->result_array(), 'count' => $count];
    }


    // ? FUNÇÕES ABAIXO PARA UTILIZAR COMO EXEMPLO //

    function ShortLinkYearChart($link)
    {
        $sql = "SELECT\n";
        $sql .= "Month(FROM_UNIXTIME(short_link_log.creation)) as mes, Count(*) as total\n";
        $sql .= "FROM short_link\n";
        $sql .= "inner join short_link_log on short_link.id_short_link = short_link_log.id_short_link\n";
        $sql .= "WHERE FROM_UNIXTIME(short_link_log.creation) BETWEEN (current_timestamp() - INTERVAL 6 MONTH) AND current_timestamp()\n";
        $sql .= "and short_link.id_short_link = $link\n";
        $sql .= "and short_link.status = 1\n";
        $sql .= "GROUP BY Month(FROM_UNIXTIME(short_link_log.creation))\n";

        $result = $this->db->query($sql);

        $data = $result->result_array();
        $ATotal = array();
        for ($i = 5; $i >= 0; $i--) {
            $date = new DateTime('NOW');

            if ($i != 0)
                $date->modify("-{$i} MONTH");

            $mes = (int)$date->format('m');
            $ATotal[$mes] = 0;
        }

        foreach ($data as $row) {
            $ATotal[$row['mes']] = (int)$row['total'];
        }

        return $ATotal;
    }


    function ShortLinkLineChart($link, $day = 1)
    {
        $sql = "SET SESSION sql_mode = ''; \n";
        $result = $this->db->query($sql);

        $sql = "SELECT\n";
        $sql .= "DATE_FORMAT(FROM_UNIXTIME(short_link_log.creation), '%d/%m') as dia, Count(*) as total\n";
        $sql .= "FROM short_link\n";
        $sql .= "inner join short_link_log on short_link.id_short_link = short_link_log.id_short_link\n";
        $sql .= "WHERE FROM_UNIXTIME(short_link_log.creation) BETWEEN (current_timestamp() - INTERVAL $day DAY) AND current_timestamp()\n";
        $sql .= "and short_link.id_short_link = $link\n";
        $sql .= "and short_link.status = 1\n";
        $sql .= "GROUP BY DAY(FROM_UNIXTIME(short_link_log.creation))\n";
        $sql .= "order by FROM_UNIXTIME(short_link_log.creation) desc \n";

        $result = $this->db->query($sql);

        $data = $result->result_array();
        $ATotal = array();


        for ($i = $day - 1; $i >= 0; $i--) {
            $date = new DateTime('NOW');

            if ($i != 0)
                $date->modify("-{$i} day");

            $dia = $date->format('d/m');
            $ATotal[$dia] = 0;
            foreach ($data as $row) {
                if ($dia == $row['dia']) {
                    $ATotal[$dia] = (int)$row['total'];
                    break;
                }
            }
        }
        return $ATotal;
    }


    function countShortLinkTotal($link)
    {
        $sql = "select\n";
        $sql .= "count(short_link_log.id_short_link_log) count\n";
        $sql .= "from short_link inner join short_link_log on short_link.id_short_link = short_link_log.id_short_link\n";
        $sql .= "where short_link.status = 1\n";
        $sql .= "and short_link.status = 1\n";
        $sql .= "and short_link.id_short_link = $link\n";

        $result = $this->db->query($sql);

        return $result->result_array()[0]['count'];
    }


    function countShortLinkMonth($link)
    {
        $sql = "select\n";
        $sql .= "count(short_link_log.id_short_link_log) count\n";
        $sql .= "from short_link inner join short_link_log on short_link.id_short_link = short_link_log.id_short_link\n";
        $sql .= "where short_link.status = 1 and extract(YEAR FROM date_format(from_unixtime(short_link_log.creation),'%Y-%m-%d')) = extract(YEAR FROM current_date())\n";
        $sql .= "and extract(MONTH FROM date_format(from_unixtime(short_link_log.creation),'%Y-%m-%d')) = extract(MONTH FROM current_date())\n";
        $sql .= "and short_link.id_short_link = $link\n";
        $sql .= "and short_link.status = 1\n";

        $result = $this->db->query($sql);

        return $result->result_array()[0]['count'];
    }


    function countShortLinkWeek($link)
    {
        $sql = "select\n";
        $sql .= "count(short_link_log.id_short_link_log) count\n";
        $sql .= "from short_link inner join short_link_log on short_link.id_short_link = short_link_log.id_short_link\n";
        $sql .= "where short_link.status = 1 and week(date_format(from_unixtime(short_link_log.creation),'%Y-%m-%d')) = week(now()-1)\n";
        $sql .= "and extract(YEAR FROM date_format(from_unixtime(short_link_log.creation),'%Y-%m-%d')) = extract(YEAR FROM current_date())\n";
        $sql .= "and short_link.id_short_link = $link\n";
        $sql .= "and short_link.status = 1\n";

        $result = $this->db->query($sql);

        return $result->result_array()[0]['count'];
    }


    function countShortLinkDay($link)
    {

        $sql = "select\n";
        $sql .= "count(short_link_log.id_short_link_log) count\n";
        $sql .= "from short_link inner join short_link_log on short_link.id_short_link = short_link_log.id_short_link\n";
        $sql .= "where short_link.status = 1 and date_format(from_unixtime(short_link_log.creation),'%Y-%m-%d') = current_date()\n";
        $sql .= "and short_link.id_short_link = $link\n";
        $sql .= "and short_link.status = 1\n";

        $result = $this->db->query($sql);

        return $result->result_array()[0]['count'];
    }


    function countShortLinkGroupDay($link)
    {
        $sql = "select\n";
        $sql .= "count(short_link_log.id_short_link_log) count,\n";
        $sql .= "date_format(from_unixtime(short_link_log.creation),'%Y-%m-%d')\n";
        $sql .= "from short_link inner join short_link_log on short_link.id_short_link = short_link_log.id_short_link\n";
        $sql .= "where short_link.id_short_link = $link\n";
        $sql .= "and short_link.status = 1\n";
        $sql .= "group by date_format(from_unixtime(short_link_log.creation),'%Y-%m-%d')\n";
        $sql .= "order by date_format(from_unixtime(short_link_log.creation),'%Y-%m-%d') asc\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function countShortLinkLink($link)
    {

        $sql = "select\n";
        $sql .= "count(short_link_log.id_short_link_log) count,\n";
        $sql .= "short_link.link\n";
        $sql .= "from short_link inner join short_link_log on short_link.id_short_link = short_link_log.id_short_link\n";
        $sql .= "where short_link.status = 1\n";
        $sql .= "and short_link.id_short_link = $link\n";
        $sql .= "group by short_link.link;\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function countShortLinkCountry($link, $dt_start, $dt_end)
    {
        $sql = "select\n";
        $sql .= "count(short_link_log.id_short_link_log) count,\n";
        $sql .= "short_link_log.country\n";
        $sql .= "from short_link inner join short_link_log on short_link.id_short_link = short_link_log.id_short_link\n";
        $sql .= "where short_link.status = 1\n";
        $sql .= "and FROM_UNIXTIME(short_link_log.creation) BETWEEN '$dt_start' AND '$dt_end'\n";
        $sql .= "and short_link.id_short_link = $link\n";
        $sql .= "group by short_link_log.country\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function countShortLinkRegion($link, $dt_start, $dt_end)
    {

        $sql = "select\n";
        $sql .= "count(short_link_log.id_short_link_log) count,\n";
        $sql .= "short_link_log.region\n";
        $sql .= "from short_link inner join short_link_log on short_link.id_short_link = short_link_log.id_short_link\n";
        $sql .= "where short_link.status = 1\n";
        $sql .= "and FROM_UNIXTIME(short_link_log.creation) BETWEEN '$dt_start' AND '$dt_end'\n";
        $sql .= "and short_link.id_short_link = $link\n";
        $sql .= "group by short_link_log.region;\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function countShortLinkCity($link, $dt_start, $dt_end)
    {

        $sql = "select\n";
        $sql .= "count(short_link_log.id_short_link_log) count,\n";
        $sql .= "short_link_log.city\n";
        $sql .= "from short_link inner join short_link_log on short_link.id_short_link = short_link_log.id_short_link\n";
        $sql .= "where short_link.status = 1\n";
        $sql .= "and FROM_UNIXTIME(short_link_log.creation) BETWEEN '$dt_start' AND '$dt_end'\n";
        $sql .= "and short_link.id_short_link = $link\n";
        $sql .= "group by short_link_log.city;\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function countShortLinkAgent($link, $dt_start, $dt_end)
    {

        $sql = "select\n";
        $sql .= "count(short_link_log.id_short_link_log) count,\n";
        $sql .= "short_link_log.agent,\n";
        $sql .= "round(count(short_link_log.id_short_link_log)*100/(\n";
        $sql .= "select count(slg.id_short_link_log) from short_link sl inner join short_link_log slg on sl.id_short_link = slg.id_short_link\n";
        $sql .= "where sl.status = 1 and slg.id_short_link = short_link.id_short_link\n";
        $sql .= "),2) percent\n";
        $sql .= "from short_link inner join short_link_log on short_link.id_short_link = short_link_log.id_short_link\n";
        $sql .= "where short_link.status = 1\n";
        $sql .= "and FROM_UNIXTIME(short_link_log.creation) BETWEEN '$dt_start' AND '$dt_end'\n";
        $sql .= "and short_link.id_short_link = $link\n";
        $sql .= "group by short_link_log.agent\n";
        $sql .= "order by (round(count(short_link_log.id_short_link_log)*100/(";
        $sql .= "select count(slg.id_short_link_log) from short_link sl inner join short_link_log slg on sl.id_short_link = slg.id_short_link\n";
        $sql .= "where sl.status = 1 and slg.id_short_link = short_link.id_short_link\n";
        $sql .= "),2)) desc\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function countShortLinkDeviceVersion($link, $dt_start, $dt_end)
    {

        $sql = "select\n";
        $sql .= "count(short_link_log.id_short_link_log) count,\n";
        $sql .= "short_link_log.device_version\n";
        $sql .= "from short_link inner join short_link_log on short_link.id_short_link = short_link_log.id_short_link\n";
        $sql .= "where short_link.status = 1\n";
        $sql .= "and FROM_UNIXTIME(short_link_log.creation) BETWEEN '$dt_start' AND '$dt_end'\n";
        $sql .= "and short_link.id_short_link = $link\n";
        $sql .= "group by short_link_log.device_version;\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    // Ticket
    function countTicketTotal()
    {
        $sql = "select\n";
        $sql .= "count(ticket_status.is_open) count,\n";
        $sql .= "ticket_status.is_open\n";
        $sql .= "from ticket\n";
        $sql .= "inner join ticket_status on ticket_status.id_ticket_status = ticket.id_ticket_status\n";
        $sql .= "group by ticket_status.is_open;\n";

        $result = $this->db->query($sql);
        $total = array(
            'open' => 0,
            'close' => 0
        );

        foreach ($result->result_array() as $row) {
            if ($row['is_open'] == 1) {
                $total['open'] = $row['count'];
            } else {
                $total['close'] = $row['count'];
            }
        }

        return $total;
    }


    function countTicketMonth()
    {
        $sql = "select\n";
        $sql .= "count(ticket.id_ticket) count\n";
        $sql .= "from ticket\n";
        $sql .= "where extract(YEAR FROM date_format(from_unixtime(ticket.creation),'%Y-%m-%d')) = extract(YEAR FROM current_date())\n";
        $sql .= "and extract(MONTH FROM date_format(from_unixtime(ticket.creation),'%Y-%m-%d')) = extract(MONTH FROM current_date());\n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            return $result->result_array()[0]['count'];
        }
        return 0;
    }


    function countTicketWeek()
    {
        $sql = "select\n";
        $sql .= "count(ticket.id_ticket) count\n";
        $sql .= "from ticket\n";
        $sql .= "where week(date_format(from_unixtime(ticket.creation),'%Y-%m-%d')) = week(now()-1)\n";
        $sql .= "and extract(YEAR FROM date_format(from_unixtime(ticket.creation),'%Y-%m-%d')) = extract(YEAR FROM current_date());\n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            return $result->result_array()[0]['count'];
        }
        return 0;
    }


    function countTicketDay()
    {
        $sql = "select\n";
        $sql .= "count(ticket.id_ticket) count\n";
        $sql .= "from ticket\n";
        $sql .= "where date_format(from_unixtime(ticket.creation),'%Y-%m-%d') = current_date();\n";

        $result = $this->db->query($sql);


        if ($result->num_rows() > 0) {
            return $result->result_array()[0]['count'];
        }
        return 0;
    }


    function TicketLastSixMonthsChart()
    {
        $sql = "SET SESSION sql_mode = ''; \n";
        $result = $this->db->query($sql);

        $sql = "select\n";
        $sql .= "Month(FROM_UNIXTIME(ticket.creation)) as mes, Count(*) as total\n";
        $sql .= "from ticket\n";
        $sql .= "WHERE FROM_UNIXTIME(ticket.creation) BETWEEN (current_timestamp() - INTERVAL 6 MONTH) AND current_timestamp()\n";
        $sql .= "GROUP BY Month(FROM_UNIXTIME(ticket.creation));\n";

        $result = $this->db->query($sql);

        $data = $result->result_array();
        $ATotal = array();
        for ($i = 5; $i >= 0; $i--) {
            $date = new DateTime('NOW');

            if ($i != 0)
                $date->modify("-{$i} MONTH");

            $mes = (int)$date->format('m');
            $ATotal[$mes] = 0;
        }

        foreach ($data as $row) {
            $ATotal[$row['mes']] = (int)$row['total'];
        }

        return $ATotal;
    }


    function TicketLineChart($day = 1)
    {
        $sql = "SET SESSION sql_mode = ''; \n";
        $result = $this->db->query($sql);

        $sql = "select\n";
        $sql .= "DATE_FORMAT(FROM_UNIXTIME(ticket.creation), '%d/%m') as dia, Count(*) as total\n";
        $sql .= "from ticket\n";
        $sql .= "WHERE FROM_UNIXTIME(ticket.creation) BETWEEN (current_timestamp() - INTERVAL $day DAY) AND current_timestamp()\n";
        $sql .= "GROUP BY DAY(FROM_UNIXTIME(ticket.creation))\n";
        $sql .= "order by FROM_UNIXTIME(ticket.creation) desc \n";

        $result = $this->db->query($sql);

        $data = $result->result_array();
        $ATotal = array();


        for ($i = $day - 1; $i >= 0; $i--) {
            $date = new DateTime('NOW');

            if ($i != 0)
                $date->modify("-{$i} day");

            $dia = $date->format('d/m');
            $ATotal[$dia] = 0;
            foreach ($data as $row) {
                if ($dia == $row['dia']) {
                    $ATotal[$dia] = (int)$row['total'];
                    break;
                }
            }
        }
        return $ATotal;
    }


    function countTicketUsers($dt_start, $dt_end)
    {
        $sql = "select\n";
        $sql .= "concat(substring_index(user.last_name, ' ', 1),' ', REVERSE(SUBSTRING_INDEX(REVERSE(substring(user.last_name from instr(user.last_name, ' ') + 1)), ' ', 1))) as last_name,\n";
        $sql .= "user.key_remote_id,\n";
        $sql .= "Count(ticket.id_user) as count\n";
        $sql .= "from ticket\n";
        $sql .= "inner join user on user.id_user = ticket.id_user\n";
        $sql .= "where DATE_FORMAT(FROM_UNIXTIME(ticket.creation),'%Y-%m-%d') BETWEEN '$dt_start' AND '$dt_end'\n";
        $sql .= "group by ticket.id_user\n";
        $sql .= "order by count desc;\n";

        $result = $this->db->query($sql);
        $query = $result->result_array();

        foreach ($query as &$row) {
            $path = "profiles/" . $row['key_remote_id'] . ".jpeg";
            if (file_exists($path) == true && filesize($path) > 0) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $row['user_profile'] = 'data:image/' . $type . ';base64,' . base64_encode($data);
            } else {
                $row['user_profile'] = '../assets/img/avatar.svg';
            }
        }

        return $query;
    }


    function countTicketType($dt_start, $dt_end)
    {
        $sql = "select\n";
        $sql .= "ticket_type.name as type,\n";
        $sql .= "ticket_type.color,\n";
        $sql .= "Count(ticket.id_ticket_type) as count\n";
        $sql .= "from ticket\n";
        $sql .= "inner join ticket_type on ticket_type.id_ticket_type = ticket.id_ticket_type\n";
        $sql .= "where DATE_FORMAT(FROM_UNIXTIME(ticket.creation),'%Y-%m-%d') BETWEEN '$dt_start' AND '$dt_end'\n";
        $sql .= "group by ticket.id_ticket_type\n";
        $sql .= "order by count desc;\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function countTicketStatus($dt_start, $dt_end)
    {
        $sql = "select\n";
        $sql .= "ticket_status.name as status,\n";
        $sql .= "ticket_status.color,\n";
        $sql .= "Count(ticket.id_ticket_status) as count\n";
        $sql .= "from ticket\n";
        $sql .= "inner join ticket_status on ticket_status.id_ticket_status = ticket.id_ticket_status\n";
        $sql .= "where DATE_FORMAT(FROM_UNIXTIME(ticket.creation),'%Y-%m-%d') BETWEEN '$dt_start' AND '$dt_end'\n";
        $sql .= "group by ticket.id_ticket_status\n";
        $sql .= "order by count desc;\n";


        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function countVisitorPage($link, $dt_start, $dt_end)
    {
        $sql = "select\n";
        $sql .= "visitor.page,\n";
        $sql .= "count(visitor.id_visitor) visitor,\n";
        $sql .= "(\n";
        $sql .= "select count(distinct v.id_contact) from visitor v\n";
        $sql .= "where v.page = visitor.page\n";
        $sql .= "group by v.page\n";
        $sql .= ") visitor_unique\n";
        $sql .= "from visitor\n";
        $sql .= "where FROM_UNIXTIME(visitor.creation) BETWEEN '$dt_start' AND '$dt_end'\n";
        $sql .= "group by visitor.page,visitor_unique\n";
        $sql .= "order by count(visitor.id_visitor) desc\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function countCalls()
    {
        $sql =  "select\n";
        $sql .= "SUM(CASE WHEN ( FROM_UNIXTIME(chat_list_log.start, '%Y-%m-%d') = CURRENT_DATE() ) THEN 1 ELSE 0 END) as hoje_aberto,\n";
        //$sql .= "SUM(CASE WHEN ( FROM_UNIXTIME(chat_list_log.end, '%Y-%m-%d') = CURRENT_DATE() ) THEN 1 ELSE 0 END) as hoje_fechado,\n";
        $sql .= "SUM(CASE WHEN ( FROM_UNIXTIME(chat_list_log.start, '%Y-%m-%d') = DATE_SUB(CURRENT_DATE(), interval 1 day) ) THEN 1 ELSE 0 END) as ontem_aberto,\n";
        //$sql .= "SUM(CASE WHEN ( FROM_UNIXTIME(chat_list_log.end, '%Y-%m-%d') = DATE_SUB(CURRENT_DATE(), interval 1 day) ) THEN 1 ELSE 0 END) as ontem_fechado,\n";
        $sql .= "SUM(CASE WHEN ( FROM_UNIXTIME(chat_list_log.start, '%Y-%m-%d') BETWEEN DATE_SUB(CURRENT_DATE(), interval 6 day) AND CURRENT_DATE() ) THEN 1 ELSE 0 END) as semana_aberto,\n";
        //$sql .= "SUM(CASE WHEN ( FROM_UNIXTIME(chat_list_log.end, '%Y-%m-%d') BETWEEN DATE_SUB(CURRENT_DATE(), interval 6 day) AND CURRENT_DATE() ) THEN 1 ELSE 0 END) as semana_fechado,\n";
        $sql .= "SUM(CASE WHEN ( FROM_UNIXTIME(chat_list_log.start, '%Y-%m-%d') BETWEEN DATE_SUB(CURRENT_DATE(), interval 32 day) AND CURRENT_DATE() ) THEN 1 ELSE 0 END) as mes_aberto,\n";
        //$sql .= "SUM(CASE WHEN ( FROM_UNIXTIME(chat_list_log.end, '%Y-%m-%d') BETWEEN DATE_SUB(CURRENT_DATE(), interval 31 day) AND CURRENT_DATE() ) THEN 1 ELSE 0 END) as mes_fechado,\n";
        $sql .= "SUM(CASE WHEN ( FROM_UNIXTIME(chat_list_log.start, '%Y-%m-%d') BETWEEN DATE_SUB(CURRENT_DATE(), interval 13 day) AND DATE_SUB(CURRENT_DATE(), interval 7 day) ) THEN 1 ELSE 0 END) as semana_passada_aberto,\n";
        //$sql .= "SUM(CASE WHEN ( FROM_UNIXTIME(chat_list_log.end, '%Y-%m-%d') BETWEEN DATE_SUB(CURRENT_DATE(), interval 13 day) AND DATE_SUB(CURRENT_DATE(), interval 7 day) ) THEN 1 ELSE 0 END) as semana_passada_fechado,\n";
        $sql .= "SUM(CASE WHEN ( FROM_UNIXTIME(chat_list_log.start, '%Y-%m-%d') BETWEEN DATE_SUB(CURRENT_DATE(), interval 63 day) AND DATE_SUB(CURRENT_DATE(), interval 30 day) ) THEN 1 ELSE 0 END) as mes_passado_aberto,\n";
        //$sql .= "SUM(CASE WHEN ( FROM_UNIXTIME(chat_list_log.end, '%Y-%m-%d') BETWEEN DATE_SUB(CURRENT_DATE(), interval 62 day) AND DATE_SUB(CURRENT_DATE(), interval 30 day) ) THEN 1 ELSE 0 END) as mes_passado_fechado,\n";
        $sql .= "count(chat_list_log.start) as total_aberto,\n";
        $sql .= "count(chat_list_log.end) as total_fechado\n";
        $sql .= "from chat_list_log\n";
        $sql .= "inner join chat_list on chat_list_log.id_chat_list = chat_list.id_chat_list\n";
        $sql .= "where chat_list.is_private = 1\n";

        $result = $this->db->query($sql);
        return $result->result_array()[0];
    }



    function countCallsOpen()
    {

        $sql = "select\n";
        $sql .= "count(chat_list.id_chat_list) count\n";
        $sql .= "from chat_list_log\n";
        $sql .= "inner join chat_list on chat_list_log.id_chat_list = chat_list.id_chat_list\n";
        $sql .= "inner join contact on chat_list.id_contact = contact.id_contact\n";
        $sql .= "where contact.spam = 1 and contact.deleted = 1 and chat_list.is_private = 1\n";
        $sql .= "and chat_list.is_close = 1\n";
        $sql .= "and chat_list.is_wait = 1\n";

        $result = $this->db->query($sql);

        return $result->result_array()[0]['count'];
    }


    function countCallsClose()
    {
        $sql = "select\n";
        $sql .= "count(chat_list.id_chat_list) count\n";
        $sql .= "from chat_list_log\n";
        $sql .= "inner join chat_list on chat_list_log.id_chat_list = chat_list.id_chat_list\n";
        $sql .= "inner join contact on chat_list.id_contact = contact.id_contact\n";
        $sql .= "where contact.deleted = 1 and chat_list.is_private = 1\n";
        $sql .= "and FROM_UNIXTIME(chat_list_log.creation, '%Y-%m-%d') = CURRENT_DATE()\n";
        $sql .= "and chat_list.is_close = 2\n";
        $sql .= "and chat_list.is_wait = 1\n";

        $result = $this->db->query($sql);

        return $result->result_array()[0]['count'];
    }


    function countWaitList()
    {
        $sql = "select\n";
        $sql .= "count(chat_list.id_chat_list) count\n";
        $sql .= "from chat_list\n";
        $sql .= "inner join contact on chat_list.id_contact = contact.id_contact\n";
        $sql .= "inner join wait_list on contact.key_remote_id = wait_list.key_remote_id\n";
        $sql .= "where contact.deleted = 1\n";
        $sql .= "and wait_list.status = 1\n";
        $sql .= "order by wait_list.creation\n";

        $result = $this->db->query($sql);

        return $result->result_array()[0]['count'];
    }


    function avgTimeWaitList()
    {

        $sql = "select\n";
        $sql .= "SEC_TO_TIME(ROUND(SUM(TIMESTAMPDIFF(second,from_unixtime(wait_list.creation),from_unixtime(wait_list.timestamp_send_user)))/";
        $sql .= "(select count(wait_list.id_wait_list) from wait_list where wait_list.status = 2))) avg\n";
        $sql .= "from wait_list\n";
        $sql .= "where wait_list.status = 2\n";

        $result = $this->db->query($sql);

        return $result->result_array()[0]['avg'];
    }


    function listCallsOpen()
    {
        $sql = "select\n";
        $sql .= "contact.key_remote_id,\n";
        $sql .= "contact.full_name\n";
        $sql .= "from chat_list \n";
        $sql .= "inner join contact on chat_list.id_contact = contact.id_contact\n";
        $sql .= "inner join user on chat_list.key_remote_id = user.key_remote_id\n";
        $sql .= "where contact.deleted = 1 and chat_list.is_private = 1\n";
        $sql .= "and chat_list.is_close = 1\n";
        $sql .= "and chat_list.is_wait = 1 limit 50;\n";

        $result = $this->db->query($sql);
        $query = $result->result_array();

        foreach ($query as &$row) {
            $path = "profiles/" . $row['key_remote_id'] . ".jpeg";
            if (file_exists($path) == true && filesize($path) > 0) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $row['profile'] = 'data:image/' . $type . ';base64,' . base64_encode($data);
            } else {
                $row['profile'] = '../assets/img/avatar.svg';
            }
        }

        return $query;
    }


    function listWaitList()
    {
        $sql = "select\n";
        $sql .= "contact.key_remote_id,\n";
        $sql .= "contact.full_name\n";
        $sql .= "from chat_list\n";
        $sql .= "inner join contact on chat_list.id_contact = contact.id_contact\n";
        $sql .= "inner join wait_list on contact.key_remote_id = wait_list.key_remote_id\n";
        $sql .= "where contact.deleted = 1\n";
        $sql .= "and wait_list.status = 1\n";
        $sql .= "order by wait_list.creation limit 50\n";

        $result = $this->db->query($sql);
        $query = $result->result_array();

        foreach ($query as &$row) {
            $path = "profiles/" . $row['key_remote_id'] . ".jpeg";
            if (file_exists($path) == true && filesize($path) > 0) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $row['profile'] = 'data:image/' . $type . ';base64,' . base64_encode($data);
            } else {
                $row['profile'] = '../assets/img/avatar.svg';
            }
        }

        return $query;
    }


    function countCallsGroup(int $days = 7)
    {
        $sql = "SELECT\n";
        $sql .= "FROM_UNIXTIME(chat_list_log.start, '%Y-%m-%d') as label,\n";
        $sql .= "Count(chat_list_log.start) as data\n";
        $sql .= "from chat_list_log\n";
        $sql .= "inner join chat_list on chat_list_log.id_chat_list = chat_list.id_chat_list\n";
        $sql .= "where chat_list.is_private = 1\n";
        $sql .= "AND FROM_UNIXTIME(chat_list_log.start, '%Y-%m-%d') BETWEEN DATE_SUB(CURRENT_DATE(), interval $days day) AND CURRENT_DATE()\n";
        $sql .= "GROUP BY FROM_UNIXTIME(chat_list_log.start, '%Y-%m-%d')\n";
        $sql .= "order by COUNT(chat_list_log.start) asc\n";

        $result = $this->db->query($sql);
        $data = $result->result_array();
        return $data;
    }


    function countContactGroup($day)
    {

        $sql = "select\n";
        $sql .= "DATE_FORMAT(from_unixtime(contact.creation),'%Y-%m-%d') as label,\n";
        $sql .= "count(contact.id_contact) as data\n";
        $sql .= "from contact\n";
        $sql .= "where contact.is_private = 1 and contact.deleted = 1\n";
        $sql .= "and FROM_UNIXTIME(contact.creation, '%Y-%m-%d') BETWEEN DATE_SUB(CURRENT_DATE(), interval $day day) AND CURRENT_DATE()\n";
        $sql .= "group by DATE_FORMAT(from_unixtime(contact.creation),'%Y-%m-%d')\n";
        $sql .= "order by DATE_FORMAT(from_unixtime(contact.creation),'%Y-%m-%d') asc;\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function countPlataform()
    {

        $sql = "select\n";
        $sql .= "channel.name as label,\n";
        $sql .= "count(contact.id_contact) as data\n";
        $sql .= "from contact inner join channel on contact.id_channel = channel.id_channel\n";
        $sql .= "where channel.type in (2,8,10,12) and channel.status = 1\n";
        $sql .= "group by channel.name order by data desc\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function countUserPresence()
    {

        $sql = "select\n";
        $sql .= "contact.presence as label,\n";
        $sql .= "count(contact.id_contact) as data\n";
        $sql .= "from contact inner join user on contact.key_remote_id = user.key_remote_id\n";
        $sql .= "where contact.deleted = 1 and user.status = 1\n";
        $sql .= "group by contact.presence\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function countMessages()
    {

        $sql = "select\n";
        $sql .= "count(messages.id_message) as data ,\n";
        $sql .= "case when messages.key_from_me = 2 then 'send' else 'received' end as label\n";
        $sql .= "from messages\n";
        $sql .= "group by messages.key_from_me\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function listTicketIsOpen()
    {

        $sql = "select\n";
        $sql .= "ticket.id_ticket,\n";
        $sql .= "user.last_name,\n";
        $sql .= "user.key_remote_id user_key_remote_id,\n";
        $sql .= "contact.full_name,\n";
        $sql .= "contact.key_remote_id contact_key_remote_id,\n";
        $sql .= "ticket_type.name type_name,\n";
        $sql .= "ticket_type.color type_color,\n";
        $sql .= "ticket_status.name status_name,\n";
        $sql .= "ticket_status.color status_color\n";
        $sql .= "from ticket inner join ticket_type on ticket.id_ticket_type = ticket_type.id_ticket_type\n";
        $sql .= "inner join ticket_status on ticket.id_ticket_status = ticket_status.id_ticket_status\n";
        $sql .= "inner join user on ticket.id_user = user.id_user\n";
        $sql .= "inner join contact on ticket.id_contact = contact.id_contact\n";
        $sql .= "where ticket_status.is_open = 1\n";
        $sql .= "order by ticket.creation;\n";

        $result = $this->db->query($sql);
        $query = $result->result_array();

        foreach ($query as &$row) {

            $path = "profiles/" . $row['user_key_remote_id'] . ".jpeg";
            if (file_exists($path) == true && filesize($path) > 0) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $row['user_profile'] = 'data:image/' . $type . ';base64,' . base64_encode($data);
            } else {
                $row['user_profile'] = '../assets/img/avatar.svg';
            }

            $path = "profiles/" . $row['contact_key_remote_id'] . ".jpeg";
            if (file_exists($path) == true && filesize($path) > 0) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $row['contact_profile'] = 'data:image/' . $type . ';base64,' . base64_encode($data);
            } else {
                $row['contact_profile'] = '../assets/img/avatar.svg';
            }
        }
        return $query;
    }


    function avgUserPresence()
    {

        $sql = "select\n";
        $sql .= "user.last_name,\n";
        $sql .= "user.key_remote_id,\n";
        $sql .= "SEC_TO_TIME(ROUND(AVG(TIMESTAMPDIFF(second,from_unixtime(user_timestamp.start),from_unixtime(user_timestamp.end))))) time_online\n";
        $sql .= "from user inner join user_timestamp on user.id_user = user_timestamp.id_user\n";
        $sql .= "where user_timestamp.end is not null and\n";
        $sql .= "DATE_FORMAT(from_unixtime(user_timestamp.short_timestamp),'%Y-%m-%d') >= DATE_FORMAT(DATE_SUB(from_unixtime(user_timestamp.short_timestamp), INTERVAL 7 DAY),'%Y-%m-%d')\n";
        $sql .= "and user.status = 1\n";
        $sql .= "group by user.last_name,\n";
        $sql .= "user.key_remote_id\n";
        $sql .= "order by time_online desc;\n";

        $result = $this->db->query($sql);
        $query = $result->result_array();

        foreach ($query as &$row) {
            $path = "profiles/" . $row['key_remote_id'] . ".jpeg";
            if (file_exists($path) == true && filesize($path) > 0) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $row['user_profile'] = 'data:image/' . $type . ';base64,' . base64_encode($data);
            } else {
                $row['user_profile'] = '../assets/img/avatar.svg';
            }
        }

        return $query;
    }
}
