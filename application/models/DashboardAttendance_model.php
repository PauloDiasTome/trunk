<?php
class DashboardAttendance_model extends TA_Model
{
    public function GetAvgWaitTime($params)
    {
        $waitList = $this->GetWaitListFinished($params);
        $workTime = $this->GetWorkTimeChannel($params['id_channel']);

        $totalSeconds = 0;
        $numAttendances = 0;

        foreach ($waitList as $row) {
            $start = $row['creation'];
            $end = $row['timestamp_send_user'];

            $useful = $this->GetUsefulWorkSeconds($start, $end, $workTime);
            if ($useful > 0 && $end > $start) {
                $totalSeconds += $useful;
                $numAttendances++;
            }
        }

        $average = $numAttendances > 0 ? ($totalSeconds / $numAttendances) : 0;
        $formattedAverage = sprintf('%02d:%02d:%02d', floor($average / 3600), floor(($average % 3600) / 60), $average % 60);

        return $this->FormatFullTime($formattedAverage);
    }

    public function GetAvgResponseTime($params)
    {
        $workTime = $this->GetWorkTimeChannel($params['id_channel']);
        $GetIdsChatLogs = $this->GetIdsChatLogs($params);

        $totalSeconds = 0;
        $totalResponses = 0;

        foreach ($GetIdsChatLogs as $row) {
            $params['id_chat_list_log'] = $row['id_chat_list_log'];
            $getChatMessages = $this->GetChatMessages($params);

            if (!empty($getChatMessages)) {
                $result = $this->GetAverageResponseTime($getChatMessages, $workTime);

                if ($result !== null) {
                    $totalSeconds += $result['total_seconds'];
                    $totalResponses += $result['response_count'];
                }
            }
        }

        if ($totalResponses > 0) {
            $averageSeconds = $totalSeconds / $totalResponses;
            $h = floor($averageSeconds / 3600);
            $m = floor(($averageSeconds % 3600) / 60);
            $s = $averageSeconds % 60;
            $timeFormatted = sprintf('%02d:%02d:%02d', $h, $m, $s);

            return $this->FormatFullTime($timeFormatted);
        }

        return '0h 0m 0s';
    }

    public function GetAvgServiceTime($params)
    {
        $workTime = $this->GetWorkTimeChannel($params['id_channel']);
        $GetIdsChatLogs = $this->GetIdsChatLogs($params); // ⭐ Reutilizar a mesma função

        $totalSeconds = 0;
        $totalAttendances = 0;

        foreach ($GetIdsChatLogs as $row) {
            $attendanceData = $this->GetAttendanceData($row['id_chat_list_log'], $params['id_channel']);

            if (!empty($attendanceData)) {
                $start = $attendanceData['start'];
                $end = $attendanceData['end'];

                $useful = $this->GetUsefulWorkSeconds($start, $end, $workTime);

                if ($useful > 0 && $end > $start) {
                    $totalSeconds += $useful;
                    $totalAttendances++;
                }
            }
        }

        if ($totalAttendances > 0) {
            $averageSeconds = $totalSeconds / $totalAttendances;
            $h = floor($averageSeconds / 3600);
            $m = floor(($averageSeconds % 3600) / 60);
            $s = $averageSeconds % 60;
            $timeFormatted = sprintf('%02d:%02d:%02d', $h, $m, $s);

            return $this->FormatFullTime($timeFormatted);
        }

        return '0h 0m 0s';
    }

    public function GetWaitListFinished($params)
    {
        $this->db->select('
            wait_list.creation + (config.timezone * 3600) AS creation,
            wait_list.timestamp_send_user + (config.timezone * 3600) AS timestamp_send_user
        ');

        $this->db->from('wait_list');
        $this->db->join('channel', 'channel.id = wait_list.account_key_remote_id');
        $this->db->join('config', 'config.id_channel = channel.id_channel');
        $this->db->where('wait_list.timestamp_send_user IS NOT NULL', null, false);
        $this->db->where('wait_list.creation IS NOT NULL', null, false);
        $this->db->where('wait_list.status', 2);
        $this->db->where('channel.id_channel', $params['id_channel']);

        if (!empty($params['sector'])) {
            $this->db->where('wait_list.id_user_group', $params['sector']);
        }

        if (!empty($params['period'])) {
            switch ($params['period']) {
                case 'today':
                    $this->db->where('DATE(FROM_UNIXTIME(wait_list.creation)) = CURDATE()', null, false);
                    break;
                case 'yesterday':
                    $this->db->where('DATE(FROM_UNIXTIME(wait_list.creation)) = CURDATE() - INTERVAL 1 DAY', null, false);
                    break;
                case 'week':
                    $this->db->where('FROM_UNIXTIME(wait_list.creation) >= CURDATE() - INTERVAL 7 DAY', null, false);
                    break;
                case '15_days':
                    $this->db->where('FROM_UNIXTIME(wait_list.creation) >= CURDATE() - INTERVAL 15 DAY', null, false);
                    break;
                case 'this_month':
                    $this->db->where('FROM_UNIXTIME(wait_list.creation) >= CURDATE() - INTERVAL 30 DAY', null, false);
                    break;
                case 'last_month':
                    $this->db->where('FROM_UNIXTIME(wait_list.creation) >= CURDATE() - INTERVAL 60 DAY', null, false);
                    break;
                case 'two_months_ago':
                    $this->db->where('FROM_UNIXTIME(wait_list.creation) >= CURDATE() - INTERVAL 90 DAY', null, false);
                    break;
            }
        }

        return $this->db->get()->result_array();
    }

    public function GetInfoMessages($params)
    {
        $this->db->select('messages.creation, messages.key_from_me');
        $this->db->from('chat_list_log');
        $this->db->join('chat_list', 'chat_list_log.id_chat_list = chat_list.id_chat_list');
        $this->db->join('messages', 'messages.id_chat_list = chat_list.id_chat_list');
        $this->db->where_in('messages.media_type', [1, 2, 3, 4, 5, 7, 8, 9, 10, 26, 27, 28, 30, 31, 33, 35]);
        $this->db->where('chat_list_log.key_remote_id', $params['key_remote_id']);
        $this->db->where('chat_list.id_channel', $params['id_channel']);

        if (!empty($params['sector'])) {
            $this->db->where('chat_list_log.id_user_group', $params['sector']);
        }

        if (!empty($params['period'])) {
            switch ($params['period']) {
                case 'today':
                    $this->db->where('DATE(FROM_UNIXTIME(chat_list_log.end)) = CURDATE()', null, false);
                    $this->db->where('DATE(FROM_UNIXTIME(messages.creation)) = CURDATE()', null, false);
                    break;
                case 'yesterday':
                    $this->db->where('DATE(FROM_UNIXTIME(chat_list_log.end)) = CURDATE() - INTERVAL 1 DAY', null, false);
                    $this->db->where('DATE(FROM_UNIXTIME(messages.creation)) = CURDATE() - INTERVAL 1 DAY', null, false);
                    break;
                case 'week':
                    $this->db->where('FROM_UNIXTIME(chat_list_log.end) >= CURDATE() - INTERVAL 7 DAY', null, false);
                    $this->db->where('FROM_UNIXTIME(messages.creation) >= CURDATE() - INTERVAL 7 DAY', null, false);
                    break;
                case '15_days':
                    $this->db->where('FROM_UNIXTIME(chat_list_log.end) >= CURDATE() - INTERVAL 15 DAY', null, false);
                    $this->db->where('FROM_UNIXTIME(messages.creation) >= CURDATE() - INTERVAL 15 DAY', null, false);
                    break;
                case 'this_month':
                    $this->db->where('FROM_UNIXTIME(chat_list_log.end) >= CURDATE() - INTERVAL 30 DAY', null, false);
                    $this->db->where('FROM_UNIXTIME(messages.creation) >= CURDATE() - INTERVAL 30 DAY', null, false);
                    break;
                case 'last_month':
                    $this->db->where('FROM_UNIXTIME(chat_list_log.end) >= CURDATE() - INTERVAL 60 DAY', null, false);
                    $this->db->where('FROM_UNIXTIME(messages.creation) >= CURDATE() - INTERVAL 60 DAY', null, false);
                    break;
                case 'two_months_ago':
                    $this->db->where('FROM_UNIXTIME(chat_list_log.end) >= CURDATE() - INTERVAL 90 DAY', null, false);
                    $this->db->where('FROM_UNIXTIME(messages.creation) >= CURDATE() - INTERVAL 90 DAY', null, false);
                    break;
            }
        }

        $this->db->where('messages.creation BETWEEN chat_list_log.start AND chat_list_log.end', null, false);

        return $this->db->get()->result_array();
    }

    public function GetChatMessages($params)
    {
        $this->db->select('messages.creation, messages.key_from_me');
        $this->db->from('chat_list_log');
        $this->db->join('chat_list', 'chat_list.id_chat_list = chat_list_log.id_chat_list', 'inner');
        $this->db->join('messages', 'messages.id_chat_list = chat_list.id_chat_list', 'inner');
        $this->db->where_in('messages.media_type', [1, 2, 3, 4, 5, 7, 8, 9, 10, 26, 27, 28, 30, 31, 33, 35]);

        if (!empty($params['id_chat_list_log'])) {
            $this->db->where('chat_list_log.id_chat_list_log', $params['id_chat_list_log']);
        }

        $this->db->where('messages.creation BETWEEN chat_list_log.start AND chat_list_log.end', null, false);
        $this->db->order_by('messages.creation', 'ASC');

        return $this->db->get()->result_array();
    }

    public function GetIdsChatLogs($params)
    {
        $this->db->select('id_chat_list_log');
        $this->db->from('chat_list_log');
        $this->db->join('chat_list', 'chat_list.id_chat_list = chat_list_log.id_chat_list', 'inner');
        $this->db->where('chat_list.id_channel', $params['id_channel']);

        if (!empty($params['key_remote_id'])) {
            $this->db->where('chat_list_log.key_remote_id', $params['key_remote_id']);
        }

        if (!empty($params['sector'])) {
            $this->db->where('chat_list_log.id_user_group', $params['sector']);
        }

        if (!empty($params['period'])) {
            switch ($params['period']) {
                case 'today':
                    $this->db->where('DATE(FROM_UNIXTIME(chat_list_log.end)) = CURDATE()', null, false);
                    break;
                case 'yesterday':
                    $this->db->where('DATE(FROM_UNIXTIME(chat_list_log.end)) = CURDATE() - INTERVAL 1 DAY', null, false);
                    break;
                case 'week':
                    $this->db->where('FROM_UNIXTIME(chat_list_log.end) >= CURDATE() - INTERVAL 7 DAY', null, false);
                    break;
                case '15_days':
                    $this->db->where('FROM_UNIXTIME(chat_list_log.end) >= CURDATE() - INTERVAL 15 DAY', null, false);
                    break;
                case 'this_month':
                    $this->db->where('FROM_UNIXTIME(chat_list_log.end) >= CURDATE() - INTERVAL 30 DAY', null, false);
                    break;
                case 'last_month':
                    $this->db->where('FROM_UNIXTIME(chat_list_log.end) >= CURDATE() - INTERVAL 60 DAY', null, false);
                    break;
                case 'two_months_ago':
                    $this->db->where('FROM_UNIXTIME(chat_list_log.end) >= CURDATE() - INTERVAL 90 DAY', null, false);
                    break;
            }
        }

        return $this->db->get()->result_array();
    }

    public function GetAttendanceData($id_chat_list_log, $id_channel)
    {
        $this->db->select('
            chat_list_log.start + (config.timezone * 3600) AS start,
            chat_list_log.end + (config.timezone * 3600) AS end
        ');

        $this->db->from('chat_list_log');
        $this->db->join('chat_list', 'chat_list_log.id_chat_list = chat_list.id_chat_list', 'inner');
        $this->db->join('channel', 'channel.id_channel = chat_list.id_channel', 'inner');
        $this->db->join('config', 'config.id_channel = channel.id_channel', 'inner');

        $this->db->where('chat_list_log.id_chat_list_log', $id_chat_list_log);
        $this->db->where('channel.id_channel', $id_channel);
        $this->db->where('chat_list_log.start IS NOT NULL', null, false);
        $this->db->where('chat_list_log.end IS NOT NULL', null, false);

        $result = $this->db->get()->row_array();

        return $result ? $result : null;
    }

    public function SumTimes($times)
    {
        $totalSeconds = 0;

        foreach ($times as $time) {
            list($hours, $minutes, $seconds) = sscanf($time, '%d:%d:%d');
            $totalSeconds += $hours * 3600 + $minutes * 60 + $seconds;
        }

        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    public function GetTotalUsers()
    {
        $this->db->from('user');
        $this->db->where('status', 1);

        return $this->db->get()->result_array();
    }

    public function FormatFullTime($time)
    {
        list($h, $m, $s) = explode(':', $time);
        return (int)$h . 'h ' . (int)$m . 'm ' . (int)$s . 's';
    }

    public function GetAverageResponseTime($messages, $workTimeMap)
    {
        $responseTimes = [];
        $lastReceived = null;

        foreach ($messages as $msg) {
            if ($msg['key_from_me'] == 1) {
                $lastReceived = $msg['creation'];
            } elseif ($msg['key_from_me'] == 2 && $lastReceived) {
                $diff = $this->GetUsefulWorkSeconds($lastReceived, $msg['creation'], $workTimeMap);
                if ($diff > 0) {
                    $responseTimes[] = $diff;
                    $lastReceived = null;
                }
            }
        }

        if (count($responseTimes) > 0) {
            return [
                'total_seconds' => array_sum($responseTimes),
                'response_count' => count($responseTimes),
                'average_seconds' => array_sum($responseTimes) / count($responseTimes)
            ];
        }

        return null;
    }

    public function GetUsefulWorkSeconds($start, $end, $workTimeMap)
    {
        $totalUseful = 0;
        $current = $start;

        while ($current < $end) {
            $date = gmdate('Y-m-d', $current);
            $dow = (int)gmdate('N', $current);

            if (isset($workTimeMap[$dow])) {
                $workdayStart = strtotime("$date {$workTimeMap[$dow]['start']} UTC");
                $workdayEnd = strtotime("$date {$workTimeMap[$dow]['end']} UTC");

                $periodStart = max($current, $workdayStart);
                $periodEnd   = min($end, $workdayEnd);

                if ($periodEnd > $periodStart) {
                    $totalUseful += ($periodEnd - $periodStart);
                }
            }

            $current = strtotime(gmdate('Y-m-d', $current) . ' +1 day UTC');
        }
        return $totalUseful;
    }

    public function GetWorkTimeChannel($id_channel)
    {
        $this->db->select('work_time_week.week, work_time_week.start, work_time_week.end');
        $this->db->from('channel');
        $this->db->join('work_time', 'work_time.id_work_time = channel.id_work_time', 'inner');
        $this->db->join('work_time_week', 'work_time_week.id_work_time = work_time.id_work_time', 'inner');
        $this->db->where('channel.id_channel', $id_channel);
        $this->db->order_by('work_time_week.week', 'asc');

        $workTimeMap = [];
        $resultArray =  $this->db->get()->result_array();

        foreach ($resultArray as $item) {
            $workTimeMap[$item['week']] = [
                'start' => $item['start'],
                'end' => $item['end']
            ];
        }

        return $workTimeMap;
    }

    public function TotalAttendances($params)
    {
        $this->db->select('COUNT(*) as count_started_chats');
        $this->db->from('chat_list_log');
        $this->db->join('chat_list', 'chat_list_log.id_chat_list = chat_list.id_chat_list', 'inner');
        $this->db->where('chat_list.id_channel', $params['id_channel']);

        if (!empty($params['sector'])) {
            $this->db->where('chat_list_log.id_user_group', $params['sector']);
        }

        if (!empty($params['period'])) {
            switch ($params['period']) {
                case 'today':
                    $this->db->where('DATE(FROM_UNIXTIME(chat_list_log.start)) = CURDATE()', null, false);
                    break;
                case 'yesterday':
                    $this->db->where('DATE(FROM_UNIXTIME(chat_list_log.start)) = CURDATE() - INTERVAL 1 DAY', null, false);
                    break;
                case 'week':
                    $this->db->where('FROM_UNIXTIME(chat_list_log.start) >= CURDATE() - INTERVAL 7 DAY', null, false);
                    break;
                case '15_days':
                    $this->db->where('FROM_UNIXTIME(chat_list_log.start) >= CURDATE() - INTERVAL 15 DAY', null, false);
                    break;
                case 'this_month':
                    $this->db->where('FROM_UNIXTIME(chat_list_log.start) >= CURDATE() - INTERVAL 30 DAY', null, false);
                    break;
                case 'last_month':
                    $this->db->where('FROM_UNIXTIME(chat_list_log.start) >= CURDATE() - INTERVAL 60 DAY', null, false);
                    break;
                case 'two_months_ago':
                    $this->db->where('FROM_UNIXTIME(chat_list_log.start) >= CURDATE() - INTERVAL 90 DAY', null, false);
                    break;
            }
        }

        return $this->db->get()->result_array()[0];
    }

    public function GetPeakService($params)
    {
        $this->db->select("hours.hour_range, COALESCE(COUNT(chat_list_log.start), 0) AS total_sessions", false)
            ->from("(SELECT '00:00' AS hour_range UNION ALL
                 SELECT '01:00' UNION ALL
                 SELECT '02:00' UNION ALL
                 SELECT '03:00' UNION ALL
                 SELECT '04:00' UNION ALL
                 SELECT '05:00' UNION ALL
                 SELECT '06:00' UNION ALL
                 SELECT '07:00' UNION ALL
                 SELECT '08:00' UNION ALL
                 SELECT '09:00' UNION ALL
                 SELECT '10:00' UNION ALL
                 SELECT '11:00' UNION ALL
                 SELECT '12:00' UNION ALL
                 SELECT '13:00' UNION ALL
                 SELECT '14:00' UNION ALL
                 SELECT '15:00' UNION ALL
                 SELECT '16:00' UNION ALL
                 SELECT '17:00' UNION ALL
                 SELECT '18:00' UNION ALL
                 SELECT '19:00' UNION ALL
                 SELECT '20:00' UNION ALL
                 SELECT '21:00' UNION ALL
                 SELECT '22:00' UNION ALL
                 SELECT '23:00') AS hours", false)
            ->join('channel', 'channel.id_channel = "' . $params['id_channel'] . '"', 'left')
            ->join('config', 'config.id_channel = channel.id_channel', 'left')
            ->join('chat_list', 'chat_list.id_channel = channel.id_channel', 'left')
            ->join('chat_list_log', 'chat_list.id_chat_list = chat_list_log.id_chat_list AND CONCAT(LPAD(HOUR(DATE_SUB(FROM_UNIXTIME(chat_list_log.start), INTERVAL CAST(REPLACE(config.timezone, "-", "") AS UNSIGNED) HOUR)), 2, "0"), ":00") = hours.hour_range', 'left');

        if (!empty($params['id_channel'])) {
            $this->db->where('channel.id_channel', $params['id_channel']);
        }

        if (!empty($params['sector'])) {
            $this->db->where('chat_list_log.id_user_group', $params['sector']);
        }

        if (!empty($params['period'])) {
            switch ($params['period']) {
                case 'today':
                    $this->db->where('DATE(DATE_SUB(FROM_UNIXTIME(chat_list_log.start), INTERVAL CAST(REPLACE(config.timezone, "-", "") AS UNSIGNED) HOUR)) = CURDATE()', null, false);
                    break;
                case 'yesterday':
                    $this->db->where('DATE(DATE_SUB(FROM_UNIXTIME(chat_list_log.start), INTERVAL CAST(REPLACE(config.timezone, "-", "") AS UNSIGNED) HOUR)) = CURDATE() - INTERVAL 1 DAY', null, false);
                    break;
                case 'week':
                    $this->db->where('DATE_SUB(FROM_UNIXTIME(chat_list_log.start), INTERVAL CAST(REPLACE(config.timezone, "-", "") AS UNSIGNED) HOUR) >= CURDATE() - INTERVAL 7 DAY', null, false);
                    break;
                case '15_days':
                    $this->db->where('DATE_SUB(FROM_UNIXTIME(chat_list_log.start), INTERVAL CAST(REPLACE(config.timezone, "-", "") AS UNSIGNED) HOUR) >= CURDATE() - INTERVAL 15 DAY', null, false);
                    break;
                case 'this_month':
                    $this->db->where('DATE_SUB(FROM_UNIXTIME(chat_list_log.start), INTERVAL CAST(REPLACE(config.timezone, "-", "") AS UNSIGNED) HOUR) >= CURDATE() - INTERVAL 30 DAY', null, false);
                    break;
                case 'last_month':
                    $this->db->where('DATE_SUB(FROM_UNIXTIME(chat_list_log.start), INTERVAL CAST(REPLACE(config.timezone, "-", "") AS UNSIGNED) HOUR) >= CURDATE() - INTERVAL 60 DAY', null, false);
                    break;
                case 'two_months_ago':
                    $this->db->where('DATE_SUB(FROM_UNIXTIME(chat_list_log.start), INTERVAL CAST(REPLACE(config.timezone, "-", "") AS UNSIGNED) HOUR)>= CURDATE() - INTERVAL 90 DAY', null, false);
                    break;
            }
        }

        $this->db->group_by('hours.hour_range');
        $this->db->order_by('hours.hour_range');

        $data = $this->db->get()->result();
        $results = [];

        // Inicializando todas as horas com zero
        for ($h = 0; $h < 24; $h++) {
            $hour = str_pad($h, 2, '0', STR_PAD_LEFT) . ":00";
            $results[$hour] = 0;
        }

        // Atualizando os resultados com os dados retornados
        foreach ($data as $row) {
            $results[$row->hour_range] = $row->total_sessions;
        }

        return $results;
    }

    public function ListChannels()
    {
        $this->db->select(
            "channel.id_channel, 
                CASE 
                    WHEN LENGTH(channel.name) > 40 
                    THEN CONCAT(channel.name, ' (', SUBSTR(channel.id, 1, 12),'...)') 
                ELSE CONCAT(channel.name, ' (', channel.id, ')') 
                END as name",
            FALSE
        );

        $this->db->from("channel");
        $this->db->join('config', 'config.id_channel = channel.id_channel');
        $this->db->where_in('channel.type', [2, 8, 9, 16, 12]);
        $this->db->where("channel.status", 1);
        $this->db->where("config.is_broadcast", 2);
        $this->db->order_by('name', 'asc');

        return $this->db->get()->result_array();
    }

    public function ListSectors()
    {
        $this->db->select('name, id_user_group');
        $this->db->from('user_group');
        $this->db->where('status', 1);
        $this->db->order_by('name', 'ASC');

        return $this->db->get()->result_array();
    }

    function GetOpenClosed($params)
    {
        $start_data = $this->getStartClosed($params, 'start');
        $end_data = $this->getStartClosed($params, 'end');

        $unique_dates = [];

        foreach ($start_data as $item) {
            $unique_dates[$item['start_date']] = true;
        }
        foreach ($end_data as $item) {
            $unique_dates[$item['end_date']] = true;
        }

        $unique_dates = array_keys($unique_dates);
        sort($unique_dates);

        $start_indexed = [];
        foreach ($start_data as $item) {
            $start_indexed[$item['start_date']] = $item['total_start'];
        }

        $end_indexed = [];
        foreach ($end_data as $item) {
            $end_indexed[$item['end_date']] = $item['total_end'];
        }

        $result = [
            'date' => [],
            'start' => [],
            'end' => [],
        ];

        foreach ($unique_dates as $iso_date) {
            $formatted_date = date('d/m/Y', strtotime($iso_date));

            $result['date'][] = $formatted_date;
            $result['start'][] = isset($start_indexed[$iso_date]) ? $start_indexed[$iso_date] : 0;
            $result['end'][] = isset($end_indexed[$iso_date]) ? $end_indexed[$iso_date] : 0;
        }

        $response = [];

        $count = count($result['date']);

        for ($i = 0; $i < $count; $i++) {
            $response[] = [
                'attendance_date' => $result['date'][$i],
                'start_count'     => (int) $result['start'][$i],
                'end_count'       => (int) $result['end'][$i]
            ];
        }

        return $response;
    }

    function getStartClosed($params, $field)
    {
        $nam_field = $field . '_date';
        $this->db->select("
                        DATE_FORMAT(
                            CONVERT_TZ(
                                FROM_UNIXTIME(chat_list_log.$field), 
                                '+00:00', 
                                config.timezone
                            ), 
                            '%Y-%m-%d'
                        ) AS $nam_field,
                        COUNT(chat_list_log.id_chat_list) AS total_$field,
                    ", FALSE);

        $this->db->from('chat_list_log');
        $this->db->join('chat_list', 'chat_list_log.id_chat_list = chat_list.id_chat_list');
        $this->db->join('config', 'chat_list.id_channel = config.id_channel');
        $this->db->where('chat_list.id_channel', $params['id_channel']);

        if (!empty($params['sector'])) {
            $this->db->where('chat_list_log.id_user_group', $params['sector']);
        }

        if (!empty($params['period'])) {
            switch ($params['period']) {
                case 'today':
                    $this->db->where("
                        DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(chat_list_log.$field), '+00:00', config.timezone),'%Y-%m-%d') = CURDATE()", null, false);
                    break;
                case 'yesterday':
                    $this->db->where("
                        DATE( CONVERT_TZ(FROM_UNIXTIME(chat_list_log.$field), '+00:00', config.timezone)) = DATE(CONVERT_TZ(NOW() - INTERVAL 1 DAY, '+00:00', config.timezone))
                        ", null, false);
                    break;
                case 'week':
                    $this->db->where("
                        DATE( CONVERT_TZ(FROM_UNIXTIME(chat_list_log.$field), '+00:00', config.timezone)) >= DATE(CONVERT_TZ(NOW() - INTERVAL 7 DAY, '+00:00', config.timezone))
                        ", null, false);
                    break;
                case '15_days':
                    $this->db->where("
                        DATE( CONVERT_TZ(FROM_UNIXTIME(chat_list_log.$field), '+00:00', config.timezone)) >= DATE(CONVERT_TZ(NOW() - INTERVAL 15 DAY, '+00:00', config.timezone))
                        ", null, false);
                    break;
                case 'this_month':
                    $this->db->where("
                        DATE( CONVERT_TZ(FROM_UNIXTIME(chat_list_log.$field), '+00:00', config.timezone)) >= DATE(CONVERT_TZ(NOW() - INTERVAL 30 DAY, '+00:00', config.timezone))
                        ", null, false);
                    break;
                case 'last_month':
                    $this->db->where("
                        DATE( CONVERT_TZ(FROM_UNIXTIME(chat_list_log.$field), '+00:00', config.timezone)) >= DATE(CONVERT_TZ(NOW() - INTERVAL 60 DAY, '+00:00', config.timezone))
                        ", null, false);
                    break;
                case 'two_months_ago':
                    $this->db->where("
                        DATE( CONVERT_TZ(FROM_UNIXTIME(chat_list_log.$field), '+00:00', config.timezone)) >= DATE(CONVERT_TZ(NOW() - INTERVAL 90 DAY, '+00:00', config.timezone))
                        ", null, false);
                    break;
                default:
                    return [];
            }
        }

        $this->db->group_by('chat_list.id_channel');
        $this->db->group_by('config.timezone');
        $this->db->group_by($nam_field);
        $this->db->order_by($nam_field, 'asc');

        return $this->db->get()->result_array() ?: [];
    }

    public function getCategoryDistribution($id_channel = null, $period = null, $sector = null)
    {
        $this->db->select("
            COALESCE(
                CASE 
                    WHEN category.status = 2 THEN CONCAT(category.name, ' (" . $this->lang->line('dashboard_attendance_graph_category_deleted') . ")')
                    ELSE category.name
                END, 
                '" . $this->lang->line('dashboard_attendance_graph_category_uncategorized_services') . "'
            ) AS category_name,
            category.status,
            COUNT(chat_list_log.id_chat_list_log) AS total
        ");
        $this->db->from('chat_list_log');
        $this->db->join('category', 'chat_list_log.id_category = category.id_category', 'left');
        $this->db->join('chat_list', 'chat_list_log.id_chat_list = chat_list.id_chat_list');
        $this->db->join('channel', 'channel.id_channel = chat_list.id_channel');
        $this->db->join('config', 'config.id_channel = channel.id_channel');

        $this->db->where_in('channel.type', [2, 12, 16, 8, 9]);
        $this->db->where('channel.is_broadcast', 2);
        $this->db->where('config.attendance_enable', 1);

        if (!empty($id_channel)) {
            $this->db->where('channel.id_channel', $id_channel);
        }

        if (!empty($sector)) {
            $this->db->where('chat_list_log.id_user_group', $sector);
        }

        if (!empty($period)) {
            switch ($period) {
                case 'today':
                    $this->db->where('DATE(FROM_UNIXTIME(chat_list_log.creation)) = CURDATE()', null, false);
                    break;
                case 'yesterday':
                    $this->db->where('DATE(FROM_UNIXTIME(chat_list_log.creation)) = CURDATE() - INTERVAL 1 DAY', null, false);
                    break;
                case 'week':
                    $this->db->where('FROM_UNIXTIME(chat_list_log.creation) >= CURDATE() - INTERVAL 7 DAY', null, false);
                    break;
                case '15_days':
                    $this->db->where('FROM_UNIXTIME(chat_list_log.creation) >= CURDATE() - INTERVAL 15 DAY', null, false);
                    break;
                case 'this_month':
                    $this->db->where('FROM_UNIXTIME(chat_list_log.creation) >= CURDATE() - INTERVAL 30 DAY', null, false);
                    break;
                case 'last_month':
                    $this->db->where('FROM_UNIXTIME(chat_list_log.creation) >= CURDATE() - INTERVAL 60 DAY', null, false);
                    break;
                case 'two_months_ago':
                    $this->db->where('FROM_UNIXTIME(chat_list_log.creation) >= CURDATE() - INTERVAL 90 DAY', null, false);
                    break;
            }
        }

        $this->db->group_by('category.id_category');
        $this->db->order_by('total', 'DESC');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function getUserStatus($params)
    {
        $this->db->select("
        user.key_remote_id AS user_key_remote_id,
        CONCAT(
            SUBSTRING_INDEX(user.last_name, ' ', 1), ' ',
            REVERSE(SUBSTRING_INDEX(
                REVERSE(SUBSTRING(user.last_name FROM INSTR(user.last_name, ' ') + 1)),
                ' ', 1
            ))
        ) AS name,
        COALESCE(started.total, 0) AS started,
        COALESCE(in_progress.total, 0) AS in_progress,
        COALESCE(on_hold.total, 0) AS on_hold,
        COALESCE(finished.total, 0) AS finished,
        COALESCE(rating.rating, 0.00) AS rating
    ");

        $this->db->from('user');
        $this->db->where('user.status', 1);

        if (!empty($params['sector'])) {
            $this->db->where('user.id_user_group', $params['sector']);
        }

        if (!empty($params['text'])) {
            $searchTerm = $this->db->escape_like_str($params['text']);
            $this->db->where("LOWER(user.last_name) LIKE LOWER('%{$searchTerm}%')");
        }

        /** ================= PERÍODO ================= */
        $periodFilterStart = "";
        $periodFilterEnd   = "";

        if (!empty($params['period'])) {
            switch ($params['period']) {
                case 'today':
                    $periodFilterStart = "AND FROM_UNIXTIME(chat_list_log.start) >= CURDATE()";
                    $periodFilterEnd   = "AND FROM_UNIXTIME(chat_list_log.end) >= CURDATE()";
                    break;

                case 'yesterday':
                    $periodFilterStart = "AND FROM_UNIXTIME(chat_list_log.start) >= CURDATE() - INTERVAL 1 DAY 
                                  AND FROM_UNIXTIME(chat_list_log.start) < CURDATE()";
                    $periodFilterEnd   = "AND FROM_UNIXTIME(chat_list_log.end) >= CURDATE() - INTERVAL 1 DAY 
                                  AND FROM_UNIXTIME(chat_list_log.end) < CURDATE()";
                    break;

                case 'week':
                    $periodFilterStart = "AND FROM_UNIXTIME(chat_list_log.start) >= CURDATE() - INTERVAL 7 DAY";
                    $periodFilterEnd   = "AND FROM_UNIXTIME(chat_list_log.end) >= CURDATE() - INTERVAL 7 DAY";
                    break;

                case '15_days':
                    $periodFilterStart = "AND FROM_UNIXTIME(chat_list_log.start) >= CURDATE() - INTERVAL 15 DAY";
                    $periodFilterEnd   = "AND FROM_UNIXTIME(chat_list_log.end) >= CURDATE() - INTERVAL 15 DAY";
                    break;

                case 'this_month':
                    $periodFilterStart = "AND FROM_UNIXTIME(chat_list_log.start) >= DATE_FORMAT(CURDATE(), '%Y-%m-01')";
                    $periodFilterEnd   = "AND FROM_UNIXTIME(chat_list_log.end) >= DATE_FORMAT(CURDATE(), '%Y-%m-01')";
                    break;

                case 'last_month':
                    $periodFilterStart = "AND FROM_UNIXTIME(chat_list_log.start) >= DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH, '%Y-%m-01')";
                    $periodFilterEnd   = "AND FROM_UNIXTIME(chat_list_log.end) >= DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH, '%Y-%m-01')";
                    break;

                case 'two_months_ago':
                    $periodFilterStart = "AND FROM_UNIXTIME(chat_list_log.start) >= DATE_FORMAT(CURDATE() - INTERVAL 2 MONTH, '%Y-%m-01')";
                    $periodFilterEnd   = "AND FROM_UNIXTIME(chat_list_log.end) >= DATE_FORMAT(CURDATE() - INTERVAL 2 MONTH, '%Y-%m-01')";
                    break;
            }
        }

        /** ================= STARTED ================= */
        $this->db->join("(SELECT chat_list_log.key_remote_id, COUNT(*) total
        FROM chat_list_log
        INNER JOIN chat_list ON chat_list_log.id_chat_list = chat_list.id_chat_list
        INNER JOIN channel ON chat_list.id_channel = channel.id_channel
        WHERE 1=1
        " . (!empty($params['id_channel']) ? "AND channel.id_channel = " . $this->db->escape($params['id_channel']) : "") . "
        $periodFilterStart
        GROUP BY chat_list_log.key_remote_id
    ) started", "started.key_remote_id = user.key_remote_id", "left");

        /** ================= IN PROGRESS ================= */
        $this->db->join("(SELECT chat_list_log.key_remote_id, COUNT(*) total
        FROM chat_list_log
        INNER JOIN chat_list ON chat_list_log.id_chat_list = chat_list.id_chat_list
        INNER JOIN contact ON chat_list.id_contact = contact.id_contact
        INNER JOIN channel ON chat_list.id_channel = channel.id_channel
        INNER JOIN config ON channel.id_channel = config.id_channel
        WHERE chat_list_log.end IS NULL
        AND contact.spam = 1
        AND contact.deleted = 1
        AND chat_list.is_private = 1
        AND chat_list.is_close = 1
        AND chat_list.is_wait = 1
        AND channel.type IN (2,12,16,8,9)
        AND channel.is_broadcast = 2
        AND config.attendance_enable = 1
        " . (!empty($params['id_channel']) ? "AND channel.id_channel = " . $this->db->escape($params['id_channel']) : "") . "
        $periodFilterStart
        GROUP BY chat_list_log.key_remote_id
    ) in_progress", "in_progress.key_remote_id = user.key_remote_id", "left");

        /** ================= ON HOLD ================= */
        $this->db->join("(SELECT chat_list_log.key_remote_id, COUNT(*) total
        FROM chat_list_log
        INNER JOIN chat_list ON chat_list_log.id_chat_list = chat_list.id_chat_list
        WHERE chat_list_log.end IS NULL
        AND chat_list.is_wait = 2
        " . (!empty($params['id_channel']) ? "AND chat_list.id_channel = " . $this->db->escape($params['id_channel']) : "") . "
        $periodFilterStart
        GROUP BY chat_list_log.key_remote_id
    ) on_hold", "on_hold.key_remote_id = user.key_remote_id", "left");

        /** ================= FINISHED ================= */
        $this->db->join("(SELECT chat_list_log.key_remote_id, COUNT(*) total
        FROM chat_list_log
        INNER JOIN chat_list ON chat_list_log.id_chat_list = chat_list.id_chat_list
        WHERE chat_list_log.end IS NOT NULL
        " . (!empty($params['id_channel']) ? "AND chat_list.id_channel = " . $this->db->escape($params['id_channel']) : "") . "
        $periodFilterEnd
        GROUP BY chat_list_log.key_remote_id
    ) finished", "finished.key_remote_id = user.key_remote_id", "left");

        /** ================= RATING MÉDIO ================= */
        $this->db->join("(SELECT
        chat_list_log.key_remote_id,
        ROUND(AVG(chat_list_log.rating), 2) AS rating
        FROM chat_list_log
        INNER JOIN chat_list ON chat_list_log.id_chat_list = chat_list.id_chat_list
        WHERE chat_list_log.end IS NOT NULL
        AND chat_list_log.rating > 0
        " . (!empty($params['id_channel']) ? "AND chat_list.id_channel = " . $this->db->escape($params['id_channel']) : "") . "
        $periodFilterEnd
        GROUP BY chat_list_log.key_remote_id
    ) rating", "rating.key_remote_id = user.key_remote_id", "left");

        /** ================= ORDER ================= */
        switch ($params['order'][0]['column']) {
            case 0:
                $this->db->order_by('name', $params['order'][0]['dir']);
                break;
            case 1:
                $this->db->order_by('started', $params['order'][0]['dir']);
                break;
            case 2:
                $this->db->order_by('in_progress', $params['order'][0]['dir']);
                break;
            case 3:
                $this->db->order_by('on_hold', $params['order'][0]['dir']);
                break;
            case 4:
                $this->db->order_by('finished', $params['order'][0]['dir']);
                break;
            case 5:
                $this->db->order_by('rating', $params['order'][0]['dir']);
                break;
            default:
                $this->db->order_by('name', 'asc');
        }

        $this->db->limit($params['length'], $params['start']);

        return $this->db->get()->result_array();
    }

    public function getUserStatusCount($params)
    {
        $this->db->select("user.key_remote_id");
        $this->db->from('user');
        $this->db->where('user.status', 1);

        if (!empty($params['sector'])) {
            $this->db->where('user.id_user_group', $params['sector']);
        }

        if (!empty($params['text'])) {
            $searchTerm = $this->db->escape_like_str($params['text']);
            $this->db->where("LOWER(user.last_name) LIKE LOWER('%{$searchTerm}%')");
        }

        $periodFilterStart = "";
        $periodFilterEnd = "";

        if (!empty($params['period'])) {
            switch ($params['period']) {
                case 'today':
                    $periodFilterStart = "AND FROM_UNIXTIME(chat_list_log.start) >= CURDATE()";
                    $periodFilterEnd   = "AND FROM_UNIXTIME(chat_list_log.end) >= CURDATE()";
                    break;

                case 'yesterday':
                    $periodFilterStart = "AND FROM_UNIXTIME(chat_list_log.start) >= CURDATE() - INTERVAL 1 DAY 
                                  AND FROM_UNIXTIME(chat_list_log.start) < CURDATE()";
                    $periodFilterEnd   = "AND FROM_UNIXTIME(chat_list_log.end) >= CURDATE() - INTERVAL 1 DAY 
                                  AND FROM_UNIXTIME(chat_list_log.end) < CURDATE()";
                    break;

                case 'week':
                    $periodFilterStart = "AND FROM_UNIXTIME(chat_list_log.start) >= CURDATE() - INTERVAL 7 DAY";
                    $periodFilterEnd   = "AND FROM_UNIXTIME(chat_list_log.end) >= CURDATE() - INTERVAL 7 DAY";
                    break;

                case '15_days':
                    $periodFilterStart = "AND FROM_UNIXTIME(chat_list_log.start) >= CURDATE() - INTERVAL 15 DAY";
                    $periodFilterEnd   = "AND FROM_UNIXTIME(chat_list_log.end) >= CURDATE() - INTERVAL 15 DAY";
                    break;

                case 'this_month':
                    $periodFilterStart = "AND FROM_UNIXTIME(chat_list_log.start) >= DATE_FORMAT(CURDATE(), '%Y-%m-01')";
                    $periodFilterEnd   = "AND FROM_UNIXTIME(chat_list_log.end) >= DATE_FORMAT(CURDATE(), '%Y-%m-01')";
                    break;

                case 'last_month':
                    $periodFilterStart = "AND FROM_UNIXTIME(chat_list_log.start) >= DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH, '%Y-%m-01')";
                    $periodFilterEnd   = "AND FROM_UNIXTIME(chat_list_log.end) >= DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH, '%Y-%m-01')";
                    break;

                case 'two_months_ago':
                    $periodFilterStart = "AND FROM_UNIXTIME(chat_list_log.start) >= DATE_FORMAT(CURDATE() - INTERVAL 2 MONTH, '%Y-%m-01')";
                    $periodFilterEnd   = "AND FROM_UNIXTIME(chat_list_log.end) >= DATE_FORMAT(CURDATE() - INTERVAL 2 MONTH, '%Y-%m-01')";
                    break;
            }
        }

        // Mesmos JOINs do método getUserStatus (sem o SELECT complexo)
        $this->db->join("(SELECT chat_list_log.key_remote_id, COUNT(*) AS total
            FROM chat_list_log
            INNER JOIN chat_list ON chat_list_log.id_chat_list = chat_list.id_chat_list
            INNER JOIN channel ON chat_list.id_channel = channel.id_channel
            WHERE 1=1
            " . (!empty($params['id_channel']) ? "AND channel.id_channel = " . $this->db->escape($params['id_channel']) : "") . "
            $periodFilterStart
            GROUP BY chat_list_log.key_remote_id
        ) started", "started.key_remote_id = user.key_remote_id", 'left');

        $this->db->join("(SELECT chat_list_log.key_remote_id, COUNT(*) AS total
            FROM chat_list_log
            INNER JOIN chat_list ON chat_list_log.id_chat_list = chat_list.id_chat_list
            INNER JOIN contact ON chat_list.id_contact = contact.id_contact
            INNER JOIN channel ON chat_list.id_channel = channel.id_channel
            INNER JOIN config ON channel.id_channel = config.id_channel
            WHERE chat_list_log.end IS NULL
            AND contact.spam = 1
            AND contact.deleted = 1
            AND chat_list.is_private = 1
            AND chat_list.is_close = 1
            AND chat_list.is_wait = 1
            AND channel.type IN (2, 12, 16, 8, 9)
            AND channel.is_broadcast = 2
            AND config.attendance_enable = 1
            " . (!empty($params['id_channel']) ? "AND channel.id_channel = " . $this->db->escape($params['id_channel']) : "") . "
            $periodFilterStart
            GROUP BY chat_list_log.key_remote_id
        ) in_progress", "in_progress.key_remote_id = user.key_remote_id", 'left');

        $this->db->join("(SELECT chat_list_log.key_remote_id, COUNT(*) AS total
            FROM chat_list_log
            INNER JOIN chat_list ON chat_list_log.id_chat_list = chat_list.id_chat_list
            INNER JOIN contact ON chat_list.id_contact = contact.id_contact
            INNER JOIN channel ON chat_list.id_channel = channel.id_channel
            INNER JOIN config ON channel.id_channel = config.id_channel
            WHERE chat_list_log.end IS NULL
            AND contact.spam = 1
            AND contact.deleted = 1
            AND chat_list.is_private = 1
            AND chat_list.is_close = 1
            AND chat_list.is_wait = 2
            AND channel.type IN (2, 12, 16, 8, 9)
            AND channel.is_broadcast = 2
            AND config.attendance_enable = 1
            " . (!empty($params['id_channel']) ? "AND channel.id_channel = " . $this->db->escape($params['id_channel']) : "") . "
            $periodFilterStart
            GROUP BY chat_list_log.key_remote_id
        ) on_hold", "on_hold.key_remote_id = user.key_remote_id", 'left');

        $this->db->join("(SELECT chat_list_log.key_remote_id, COUNT(*) AS total
            FROM chat_list_log
            INNER JOIN chat_list ON chat_list_log.id_chat_list = chat_list.id_chat_list
            INNER JOIN channel ON chat_list.id_channel = channel.id_channel
            INNER JOIN config ON channel.id_channel = config.id_channel
            WHERE chat_list_log.end IS NOT NULL
            " . (!empty($params['id_channel']) ? "AND channel.id_channel = " . $this->db->escape($params['id_channel']) : "") . "
            $periodFilterEnd
            GROUP BY chat_list_log.key_remote_id
        ) finished", "finished.key_remote_id = user.key_remote_id", 'left');

        return $this->db->count_all_results();
    }

    public function getChatbotQuantitative($id_channel = null, $period = null)
    {
        $this->db->select("
            chatbot_interaction.options AS option_name,
            COUNT(chatbot_interaction.id_chatbot_interaction) AS total
        ");

        $this->db->from('chatbot_interaction');
        $this->db->join('contact', 'contact.key_remote_id = chatbot_interaction.key_remote_id', 'left');

        if (!empty($id_channel)) {
            $this->db->where('contact.id_channel', $id_channel);
        }

        if (!empty($period)) {
            switch ($period) {
                case 'today':
                    $this->db->where('DATE(FROM_UNIXTIME(chatbot_interaction.creation)) = CURDATE()', null, false);
                    break;
                case 'yesterday':
                    $this->db->where('DATE(FROM_UNIXTIME(chatbot_interaction.creation)) = CURDATE() - INTERVAL 1 DAY', null, false);
                    break;
                case 'week':
                    $this->db->where('FROM_UNIXTIME(chatbot_interaction.creation) >= CURDATE() - INTERVAL 7 DAY', null, false);
                    break;
                case '15_days':
                    $this->db->where('FROM_UNIXTIME(chatbot_interaction.creation) >= CURDATE() - INTERVAL 15 DAY', null, false);
                    break;
                case 'this_month':
                    $this->db->where('FROM_UNIXTIME(chatbot_interaction.creation) >= CURDATE() - INTERVAL 30 DAY', null, false);
                    break;
                case 'last_month':
                    $this->db->where('FROM_UNIXTIME(chatbot_interaction.creation) >= CURDATE() - INTERVAL 60 DAY', null, false);
                    break;
                case 'two_months_ago':
                    $this->db->where('FROM_UNIXTIME(chatbot_interaction.creation) >= CURDATE() - INTERVAL 90 DAY', null, false);
                    break;
            }
        }

        $this->db->group_by('chatbot_interaction.options');
        $this->db->order_by('total', 'DESC');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAttendanceOrigin($id_channel = null, $period = null, $sector = null)
    {
        $this->db->select("
            chat_list_log.start_mode,
            COUNT(*) AS total
        ");

        $this->db->from('chat_list_log');
        $this->db->join('chat_list', 'chat_list.id_chat_list = chat_list_log.id_chat_list', 'left');

        if (!empty($id_channel)) {
            $this->db->where('chat_list.id_channel', $id_channel);
        }

        if (!empty($sector)) {
            $this->db->where('chat_list_log.id_user_group', $sector);
        }

        if (!empty($period)) {
            switch ($period) {
                case 'today':
                    $this->db->where('DATE(FROM_UNIXTIME(chat_list_log.creation)) = CURDATE()', null, false);
                    break;
                case 'yesterday':
                    $this->db->where('DATE(FROM_UNIXTIME(chat_list_log.creation)) = CURDATE() - INTERVAL 1 DAY', null, false);
                    break;
                case 'week':
                    $this->db->where('FROM_UNIXTIME(chat_list_log.creation) >= CURDATE() - INTERVAL 7 DAY', null, false);
                    break;
                case '15_days':
                    $this->db->where('FROM_UNIXTIME(chat_list_log.creation) >= CURDATE() - INTERVAL 15 DAY', null, false);
                    break;
                case 'this_month':
                    $this->db->where('FROM_UNIXTIME(chat_list_log.creation) >= CURDATE() - INTERVAL 30 DAY', null, false);
                    break;
                case 'last_month':
                    $this->db->where('FROM_UNIXTIME(chat_list_log.creation) >= CURDATE() - INTERVAL 60 DAY', null, false);
                    break;
                case 'two_months_ago':
                    $this->db->where('FROM_UNIXTIME(chat_list_log.creation) >= CURDATE() - INTERVAL 90 DAY', null, false);
                    break;
            }
        }

        $this->db->where_in('chat_list_log.start_mode', [1, 2]);
        $this->db->group_by('chat_list_log.start_mode');
        $this->db->order_by('total', 'DESC');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function getChatbotAbandonment($id_channel = null, $period = null)
    {
        $this->db->select("
            chatbot_interaction.is_automatic_transfer,
            COUNT(*) AS total
        ");

        $this->db->from('chatbot_interaction');
        $this->db->join('contact', 'contact.key_remote_id = chatbot_interaction.key_remote_id', 'left');

        if (!empty($id_channel)) {
            $this->db->where('contact.id_channel', $id_channel);
        }

        if (!empty($period)) {
            switch ($period) {
                case 'today':
                    $this->db->where('DATE(FROM_UNIXTIME(chatbot_interaction.creation)) = CURDATE()', null, false);
                    break;
                case 'yesterday':
                    $this->db->where('DATE(FROM_UNIXTIME(chatbot_interaction.creation)) = CURDATE() - INTERVAL 1 DAY', null, false);
                    break;
                case 'week':
                    $this->db->where('FROM_UNIXTIME(chatbot_interaction.creation) >= CURDATE() - INTERVAL 7 DAY', null, false);
                    break;
                case '15_days':
                    $this->db->where('FROM_UNIXTIME(chatbot_interaction.creation) >= CURDATE() - INTERVAL 15 DAY', null, false);
                    break;
                case 'this_month':
                    $this->db->where('FROM_UNIXTIME(chatbot_interaction.creation) >= CURDATE() - INTERVAL 30 DAY', null, false);
                    break;
                case 'last_month':
                    $this->db->where('FROM_UNIXTIME(chatbot_interaction.creation) >= CURDATE() - INTERVAL 60 DAY', null, false);
                    break;
                case 'two_months_ago':
                    $this->db->where('FROM_UNIXTIME(chatbot_interaction.creation) >= CURDATE() - INTERVAL 90 DAY', null, false);
                    break;
            }
        }

        $this->db->where_in('chatbot_interaction.is_automatic_transfer', [1, 2]);
        $this->db->group_by('chatbot_interaction.is_automatic_transfer');
        $this->db->order_by('total', 'DESC');

        $query = $this->db->get();
        return $query->result_array();
    }
}
