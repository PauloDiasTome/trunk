<?php

class ReportInteractionSynthetic_model extends TA_model
{
    function ChatBot($param)
    {
        $total_chatbot = $this->totalChatbot($param);

        $get_automatic_transfer = $this->getAutomaticTransfer($param);

        $get_info = $this->getInfo($param);

        $get_attendance = $this->getAttendance($param);

        $most_total_chatbot = reset($total_chatbot);
        $less_total_chatbot = end($total_chatbot);

        $total_interaction_bot = empty($total_chatbot) ? "" : array_sum(array_column($total_chatbot, "qty"));
        $option_more_wanted = empty($most_total_chatbot) ? "" : $most_total_chatbot["qty"] .  "  (" . $most_total_chatbot["options"] . ")";
        $option_less_wanted = empty($less_total_chatbot) ? "" : $less_total_chatbot["qty"] . "  (" . $less_total_chatbot["options"] . ")";
        $automatic_transfer = empty($get_automatic_transfer) ? "" : $get_automatic_transfer['automatic'];
        $option_info = empty($get_info) ? "" : $get_info['info'];
        $option_attendance = empty($get_attendance) ? "" : $get_attendance['attendance'];

        $data = array(
            [
                "title" => $this->lang->line("report_interaction_synthetic_model_function_chatbot_chatbot_total_interactions"),
                "qtde" => trim($total_interaction_bot) == "" ? "0" : $total_interaction_bot,
            ],
            [
                "title" => $this->lang->line("report_interaction_synthetic_model_function_chatbot_most_famous_option"),
                "qtde" => trim($option_more_wanted) == "" ? $this->lang->line("report_interaction_synthetic_model_function_chatbot_out_of_order") : $option_more_wanted,
            ],
            [
                "title" => $this->lang->line("report_interaction_synthetic_model_function_chatbot_less_famous_option"),
                "qtde" => trim($option_less_wanted) == "" ? $this->lang->line("report_interaction_synthetic_model_function_chatbot_out_of_order") : $option_less_wanted,
            ],
            [
                "title" =>  $this->lang->line("report_interaction_synthetic_model_function_chatbot_auto_transfered_service"),
                "qtde" => trim($automatic_transfer)  == "" ? "0" : $automatic_transfer,
            ],
            [
                "title" => $this->lang->line("report_interaction_synthetic_model_function_chatbot_info_options_interactions"),
                "qtde" => trim($option_info)   == "" ? "0" : $option_info,
            ],
            [
                "title" => $this->lang->line("report_interaction_synthetic_model_function_chatbot_info_to_advanced_for_service"),
                "qtde" => trim($option_attendance) == "" ? "0" : $option_attendance,
            ],
        );

        return $data;
    }

    function WaitingService($param)
    {
        $dt_start = $param['dt_start'];
        $dt_end = $param['dt_end'];
        $time = $param['time'];
        $channel = '';

        if (isset($param['channel']) && $param['channel'] == 0) {
            $channel = '';
        } else {
            $channel = $param['channel'];
        }

        $sql = "SELECT 
                    user_group.name name_sector,
                    CONCAT(FLOOR(SUM(TIMESTAMPDIFF(MINUTE,
                                        DATE_FORMAT(FROM_UNIXTIME(waiting.creation),
                                                '%Y-%m-%d %H:%i:%s'),
                                        DATE_FORMAT(FROM_UNIXTIME(waiting.timestamp_send_user),
                                                '%Y-%m-%d %H:%i:%s'))) / 60),
                            'h ',
                            MOD(SUM(TIMESTAMPDIFF(MINUTE,
                                    DATE_FORMAT(FROM_UNIXTIME(waiting.creation),
                                            '%Y-%m-%d %H:%i:%s'),
                                    DATE_FORMAT(FROM_UNIXTIME(waiting.timestamp_send_user),
                                            '%Y-%m-%d %H:%i:%s'))),
                                60),
                            'm') tempo_sector,
                    CONCAT(FLOOR(MEDIA(SUM(TIMESTAMPDIFF(MINUTE,
                                                DATE_FORMAT(FROM_UNIXTIME(waiting.creation),
                                                        '%Y-%m-%d %H:%i:%s'),
                                                DATE_FORMAT(FROM_UNIXTIME(waiting.timestamp_send_user),
                                                        '%Y-%m-%d %H:%i:%s'))),
                                            COUNT(waiting.id_wait_list)) / 60),
                            'h ',
                            MOD(MEDIA(SUM(TIMESTAMPDIFF(MINUTE,
                                            DATE_FORMAT(FROM_UNIXTIME(waiting.creation),
                                                    '%Y-%m-%d %H:%i:%s'),
                                            DATE_FORMAT(FROM_UNIXTIME(waiting.timestamp_send_user),
                                                    '%Y-%m-%d %H:%i:%s'))),
                                        COUNT(waiting.id_wait_list)),
                                60),
                            'm') media_sector
                FROM
                    wait_list waiting
                        INNER JOIN
                    user_group ON waiting.id_user_group = user_group.id_user_group\n";

        if (trim($dt_start) != "") {
            $sql  .= "WHERE DATE_FORMAT(FROM_UNIXTIME(waiting.creation),'%Y-%m-%d') BETWEEN '$dt_start' AND '$dt_end' AND waiting.account_key_remote_id like '%$channel' \n";
        } else {
            $sql  .= "WHERE DATE_FORMAT(FROM_UNIXTIME(waiting.creation),'%Y-%m-%d') = CURRENT_DATE() AND waiting.account_key_remote_id like '%$channel'\n";
        }

        $sql .= "GROUP BY user_group.id_user_group
                   ORDER BY media_sector DESC
                     LIMIT 1";

        sleep($time);
        $response = $this->db->query($sql);

        $query = "SELECT 
                    CONCAT(FLOOR(MEDIA(SUM(TIMESTAMPDIFF(MINUTE,
                                                DATE_FORMAT(FROM_UNIXTIME(wait_list.creation),
                                                        '%Y-%m-%d %H:%i:%s'),
                                                DATE_FORMAT(FROM_UNIXTIME(wait_list.timestamp_send_user),
                                                        '%Y-%m-%d %H:%i:%s'))),
                                            COUNT(wait_list.id_wait_list)) / 60),
                            'h ',
                            MOD(MEDIA(SUM(TIMESTAMPDIFF(MINUTE,
                                            DATE_FORMAT(FROM_UNIXTIME(wait_list.creation),
                                                    '%Y-%m-%d %H:%i:%s'),
                                            DATE_FORMAT(FROM_UNIXTIME(wait_list.timestamp_send_user),
                                                    '%Y-%m-%d %H:%i:%s'))),
                                        COUNT(wait_list.id_wait_list)),
                                60),
                            'm') media,
                    (SELECT 
                            COUNT(wait_list.id_wait_list)
                        FROM
                            wait_list\n";

        if (trim($dt_start) != "") {
            if ($channel == '') {
                $query  .= "WHERE DATE_FORMAT(FROM_UNIXTIME(wait_list.creation),'%Y-%m-%d') BETWEEN '$dt_start' AND '$dt_end') waiting\n";
            } else {
                $query  .= "WHERE DATE_FORMAT(FROM_UNIXTIME(wait_list.creation),'%Y-%m-%d') BETWEEN '$dt_start' AND '$dt_end' AND wait_list.account_key_remote_id = '$channel') waiting\n";
            }
        } else {
            if ($channel == '') {
                $query  .= "WHERE DATE_FORMAT(FROM_UNIXTIME(wait_list.creation),'%Y-%m-%d') = CURRENT_DATE()) waiting\n";
            } else {
                $query  .= "WHERE DATE_FORMAT(FROM_UNIXTIME(wait_list.creation),'%Y-%m-%d') = CURRENT_DATE() AND wait_list.account_key_remote_id = '$channel') waiting\n";
            }
        }

        $query .= "FROM
                    wait_list\n";

        if (trim($dt_start) != "") {
            if ($channel == '') {
                $query  .= "WHERE DATE_FORMAT(FROM_UNIXTIME(wait_list.creation),'%Y-%m-%d') BETWEEN '$dt_start' AND '$dt_end'\n";
            } else {
                $query  .= "WHERE DATE_FORMAT(FROM_UNIXTIME(wait_list.creation),'%Y-%m-%d') BETWEEN '$dt_start' AND '$dt_end' AND wait_list.account_key_remote_id = '$channel'\n";
            }
        } else {
            if ($channel == '') {
                $query  .= "WHERE DATE_FORMAT(FROM_UNIXTIME(wait_list.creation),'%Y-%m-%d') = CURRENT_DATE()\n";
            } else {
                $query  .= "WHERE DATE_FORMAT(FROM_UNIXTIME(wait_list.creation),'%Y-%m-%d') = CURRENT_DATE() AND wait_list.account_key_remote_id = '$channel'\n";
            }
        }

        $res = $this->db->query($query);

        $result  = array_merge($res->result_array(), $response->result_array());

        $data = array(
            [
                'title' => $this->lang->line("report_interaction_synthetic_model_function_waiting_service_contacts_waited"),
                'qtde' => isset($result[0]['waiting']) ? $result[0]['waiting'] : '0',
            ],
            [
                'title' => $this->lang->line("report_interaction_synthetic_model_function_waiting_service_avarage_waiting_time"),
                'qtde' => isset($result[0]['media']) ? $result[0]['media'] : '0',
            ],
            [
                'title' => $this->lang->line("report_interaction_synthetic_model_function_waiting_service_highest_avarage_waiting_time_by_department"),
                'qtde' => isset($result[1]['media_sector']) ?  $result[1]['media_sector'] . "  (" . $result[1]['name_sector'] . ")" : "0",
            ],
        );

        return $data;
    }


    function Attendance($param)
    {
        $dt_start = $param['dt_start'];
        $dt_end = $param['dt_end'];
        $time = $param['time'];
        $channel = '';

        if (isset($param['id_channel']) && $param['id_channel'] == 0) {
            $channel = '';
        } else {
            $channel = $param['id_channel'];
        }

        $sql = "SELECT 
                    COUNT(chat_list_log.id_chat_list) total_attendance,
                    SUM(CASE
                        WHEN chat_list_log.end IS NOT NULL THEN 1
                    END) closed,
                    SUM(CASE
                        WHEN chat_list_log.end IS NULL THEN 1
                    END) open
                FROM
                    chat_list_log inner join chat_list on chat_list_log.id_chat_list = chat_list.id_chat_list\n";

        if (trim($dt_start) != "") {
            if ($channel == '') {
                $sql  .= "WHERE DATE_FORMAT(FROM_UNIXTIME(chat_list_log.creation),'%Y-%m-%d') BETWEEN '$dt_start' AND '$dt_end'\n";
            } else {
                $sql  .= "WHERE DATE_FORMAT(FROM_UNIXTIME(chat_list_log.creation),'%Y-%m-%d') BETWEEN '$dt_start' AND '$dt_end' AND chat_list.id_channel = '$channel'\n";
            }
        } else {
            if ($channel == '') {
                $sql  .= "WHERE DATE_FORMAT(FROM_UNIXTIME(chat_list_log.creation),'%Y-%m-%d') = CURRENT_DATE()\n";
            } else {
                $sql  .= "WHERE DATE_FORMAT(FROM_UNIXTIME(chat_list_log.creation),'%Y-%m-%d') = CURRENT_DATE() AND chat_list.id_channel = '$channel'\n";
            }
        }

        sleep($time);
        $response = $this->db->query($sql);

        $response = $response->result_array();

        $total_attendance = $response[0]['total_attendance'];
        $closed = $response[0]['closed'];
        $open = $response[0]['open'];

        $query = "SELECT 
                    user_group.name,
                    COUNT(chat_list_log.id_chat_list) count,
                    SUM(TIMESTAMPDIFF(MINUTE,
                        DATE_FORMAT(FROM_UNIXTIME(chat_list_log.start),
                                '%Y-%m-%d %H:%i:%s'),
                        DATE_FORMAT(FROM_UNIXTIME(chat_list_log.end),
                                '%Y-%m-%d %H:%i:%s'))) minutes,
                    CONCAT(FLOOR(MEDIA(SUM(TIMESTAMPDIFF(MINUTE,
                                                DATE_FORMAT(FROM_UNIXTIME(chat_list_log.start),
                                                        '%Y-%m-%d %H:%i:%s'),
                                                DATE_FORMAT(FROM_UNIXTIME(chat_list_log.end),
                                                        '%Y-%m-%d %H:%i:%s'))),
                                            COUNT(chat_list_log.id_chat_list_log)) / 60),
                            'h ',
                            MOD(MEDIA(SUM(TIMESTAMPDIFF(MINUTE,
                                            DATE_FORMAT(FROM_UNIXTIME(chat_list_log.start),
                                                    '%Y-%m-%d %H:%i:%s'),
                                            DATE_FORMAT(FROM_UNIXTIME(chat_list_log.end),
                                                    '%Y-%m-%d %H:%i:%s'))),
                                        COUNT(chat_list_log.id_chat_list_log)),
                                60),
                            'm') media
                FROM
                    chat_list_log
                        INNER JOIN
                    user_group ON chat_list_log.id_user_group = user_group.id_user_group
                        INNER JOIN
                     chat_list on chat_list_log.id_chat_list = chat_list.id_chat_list\n";

        if (trim($dt_start) != "") {
            if ($channel == '') {
                $query  .= "WHERE DATE_FORMAT(FROM_UNIXTIME(chat_list_log.creation),'%Y-%m-%d') BETWEEN '$dt_start' AND '$dt_end'\n";
            } else {
                $query  .= "WHERE DATE_FORMAT(FROM_UNIXTIME(chat_list_log.creation),'%Y-%m-%d') BETWEEN '$dt_start' AND '$dt_end' AND chat_list.id_channel = '$channel'\n";
            }
        } else {
            if ($channel == '') {
                $query  .= "WHERE DATE_FORMAT(FROM_UNIXTIME(chat_list_log.creation),'%Y-%m-%d') = CURRENT_DATE()\n";
            } else {
                $query  .= "WHERE DATE_FORMAT(FROM_UNIXTIME(chat_list_log.creation),'%Y-%m-%d') = CURRENT_DATE() AND chat_list.id_channel = '$channel'\n";
            }
        }

        $query .= "GROUP BY user_group.name
                   ORDER BY minutes desc\n";

        $resp = $this->db->query($query);

        $resp = $resp->result_array();

        $responseFirts = $resp != '' ? reset($resp) : null;
        $responseLast = $resp != '' ? array_pop($resp) : null;

        $sector_more_wanted = $responseFirts != null ? $responseFirts['count'] .  " (" . $responseFirts['name'] . ")" : null;
        $sector_less_wanted =  $responseFirts != null ? ($responseFirts == $responseLast ? '--' : $responseLast['count'] . " (" . $responseLast['name'] . ")") : null;
        $media_sector = $responseFirts != null ? $responseFirts['media'] . " (" . $responseFirts['name'] . ")" : null;

        $queryTwo = "SELECT 
                        (SELECT 
                                user.name
                            FROM
                                user
                            WHERE
                                user.key_remote_id = chat_list_log.key_remote_id) name,
                        chat_list_log.key_remote_id,
                        COUNT(chat_list_log.id_chat_list) count,
                        SUM(TIMESTAMPDIFF(MINUTE,
                            DATE_FORMAT(FROM_UNIXTIME(chat_list_log.start),
                                    '%Y-%m-%d %H:%i:%s'),
                            DATE_FORMAT(FROM_UNIXTIME(chat_list_log.end),
                                    '%Y-%m-%d %H:%i:%s'))) minutes,
                        CONCAT(FLOOR(MEDIA(SUM(TIMESTAMPDIFF(MINUTE,
                                                    DATE_FORMAT(FROM_UNIXTIME(chat_list_log.start),
                                                            '%Y-%m-%d %H:%i:%s'),
                                                    DATE_FORMAT(FROM_UNIXTIME(chat_list_log.end),
                                                            '%Y-%m-%d %H:%i:%s'))),
                                                COUNT(chat_list_log.id_chat_list)) / 60),
                                'h ',
                                MOD(MEDIA(SUM(TIMESTAMPDIFF(MINUTE,
                                                DATE_FORMAT(FROM_UNIXTIME(chat_list_log.start),
                                                        '%Y-%m-%d %H:%i:%s'),
                                                DATE_FORMAT(FROM_UNIXTIME(chat_list_log.end),
                                                        '%Y-%m-%d %H:%i:%s'))),
                                            COUNT(chat_list_log.id_chat_list)),
                                    60),
                                'm') media
                    FROM
                         chat_list_log inner join chat_list on chat_list_log.id_chat_list = chat_list.id_chat_list\n";

        if (trim($dt_start) != "") {
            if ($channel == '') {
                $queryTwo  .= "WHERE DATE_FORMAT(FROM_UNIXTIME(chat_list_log.creation),'%Y-%m-%d') BETWEEN '$dt_start' AND '$dt_end'\n";
            } else {
                $queryTwo  .= "WHERE DATE_FORMAT(FROM_UNIXTIME(chat_list_log.creation),'%Y-%m-%d') BETWEEN '$dt_start' AND '$dt_end' AND chat_list.id_channel = '$channel'\n";
            }
        } else {
            if ($channel == '') {
                $queryTwo  .= "WHERE DATE_FORMAT(FROM_UNIXTIME(chat_list_log.creation),'%Y-%m-%d') = CURRENT_DATE()\n";
            } else {
                $queryTwo  .= "WHERE DATE_FORMAT(FROM_UNIXTIME(chat_list_log.creation),'%Y-%m-%d') = CURRENT_DATE() AND chat_list.id_channel = '$channel'\n";
            }
        }

        $queryTwo .= "GROUP BY chat_list_log.key_remote_id
                      ORDER BY minutes DESC\n;";

        $respTwo = $this->db->query($queryTwo);
        $respTwo =  $respTwo->result_array();

        $responseFirts = reset($respTwo);
        $responseLast = array_pop($respTwo);

        $media_attendance = $responseFirts != null ? $responseFirts['media'] . " (" . $responseFirts['name'] . ")" : null;

        $data = array(
            [
                'title' => $this->lang->line("report_interaction_synthetic_model_function_attendence_service_total"),
                'qtde' => $total_attendance,
            ],
            [
                'title' => $this->lang->line("report_interaction_synthetic_model_function_attendence_most_famous_sector"),
                'qtde' => trim($sector_more_wanted) != null ? $sector_more_wanted : '0',
            ],
            [
                'title' => $this->lang->line("report_interaction_synthetic_model_function_attendence_less_famous_sector"),
                'qtde' => trim($sector_less_wanted) != null ? $sector_less_wanted : '0',
            ],
            [
                'title' => $this->lang->line("report_interaction_synthetic_model_function_attendence_best_service_sector_media"),
                'qtde' => trim($media_sector) != null ? $media_sector : '0',
            ],
            [
                'title' => $this->lang->line("report_interaction_synthetic_model_function_attendence_service_sector_media"),
                'qtde' => trim($media_attendance) != null ? $media_attendance : '0',
            ],
            [
                'title' => $this->lang->line("report_interaction_synthetic_model_function_attendence_service_finish_total"),
                'qtde' => $closed != null  ? $closed : "0",
            ],
            [
                'title' => $this->lang->line("report_interaction_synthetic_model_function_attendence_service_open_total"),
                'qtde' => $open != null ? $open : "0",
            ],
        );

        return $data;
    }

    function IsBot()
    {
        $sql = "SELECT 
                    config.chatbot_enable
                FROM
                    config
                        INNER JOIN
                    channel ON channel.id_channel = config.id_channel
                WHERE
                    channel.type IN (2 , 12, 16)
                        AND channel.status = 1 AND config.attendance_enable = 1 AND config.chatbot_enable = 1";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function getChannels()
    {
        $this->db->select(['id_channel', 'name', 'id']);
        $this->db->from('channel');
        $this->db->where_in('type', [2, 12, 16]);
        $this->db->where('status', 1);

        return $this->db->get()->result_array();
    }

    function totalChatbot($param)
    {
        $channel = '';

        if (isset($param['channel']) && $param['channel'] == 0) {
            $channel = '';
        } else {
            $channel = $param['channel'];
        }

        if (empty($param['dt_start'])) {
            $this->db->select('chatbot.description AS options, COUNT(messages.id_message) AS qty');
            $this->db->from('chatbot');
            $this->db->join('chatbot AS submenu', 'chatbot.id_chatbot = submenu.id_submenu', 'left');
            $this->db->join(
                'messages',
                'chatbot.id_chatbot = messages.id_chat_bot AND 
                    messages.creation >= UNIX_TIMESTAMP(CURRENT_DATE()) AND 
                    messages.creation < UNIX_TIMESTAMP(CURRENT_DATE() + INTERVAL 1 DAY)',
                'left'
            );
            $this->db->join('chat_list', 'chat_list.id_chat_list = messages.id_chat_list', 'left');
            $this->db->join('channel', 'chat_list.id_channel = channel.id_channel', 'left');
            $this->db->where('submenu.id_submenu IS NULL');
            $this->db->group_start();
            $this->db->like('channel.id', $channel);
            $this->db->or_where('channel.id IS NULL');
            $this->db->group_end();
            $this->db->group_by('chatbot.description');
            $this->db->order_by('qty', 'DESC');

            return $this->db->get()->result_array() ?? ['qty' => 0, 'options' => ''];
        }

        if (!empty($param['dt_start'])) {
            $this->db->select('chatbot.description AS options, COUNT(messages.id_message) AS qty');
            $this->db->from('chatbot');
            $this->db->join('chatbot AS submenu', 'chatbot.id_chatbot = submenu.id_submenu', 'left');
            $this->db->join(
                'messages',
                "chatbot.id_chatbot = messages.id_chat_bot AND 
                messages.creation BETWEEN UNIX_TIMESTAMP('{$param['dt_start']} 00:00:00') 
                AND UNIX_TIMESTAMP('{$param['dt_end']} 23:59:59')",
                'left'
            );
            $this->db->join('chat_list', 'chat_list.id_chat_list = messages.id_chat_list', 'left');
            $this->db->join('channel', 'chat_list.id_channel = channel.id_channel', 'left');
            $this->db->where('submenu.id_submenu IS NULL');
            $this->db->group_start();
            $this->db->like('channel.id', $channel);
            $this->db->or_where('channel.id IS NULL');
            $this->db->group_end();
            $this->db->group_by('chatbot.description');
            $this->db->order_by('qty', 'DESC');

            return $this->db->get()->result_array() ?? ['qty' => 0, 'options' => ''];
        }
    }

    function getAutomaticTransfer($param)
    {
        $channel = '';

        if (isset($param['channel']) && $param['channel'] == 0) {
            $channel = '';
        } else {
            $channel = $param['channel'];
        }

        if (empty($param['dt_start'])) {
            $this->db->select('COUNT(wait_list.id_wait_list) AS automatic');
            $this->db->from('wait_list');
            $this->db->join('channel', 'wait_list.account_key_remote_id = channel.id', 'left');
            $this->db->where('wait_list.type', 1);
            $this->db->where('wait_list.status', 2);
            $this->db->where('wait_list.creation >= UNIX_TIMESTAMP(CURRENT_DATE()) AND wait_list.creation < UNIX_TIMESTAMP(CURRENT_DATE() + INTERVAL 1 DAY)');
            $this->db->like('channel.id', $channel);

            return $this->db->get()->result_array()[0] ?? ['automatic' => 0];
        }

        if (!empty($param['dt_start'])) {

            $this->db->select('COUNT(wait_list.id_wait_list) AS automatic');
            $this->db->from('wait_list');
            $this->db->join('channel', 'wait_list.account_key_remote_id = channel.id', 'left');
            $this->db->where('wait_list.type', 1);
            $this->db->where('wait_list.status', 2);
            $this->db->where('wait_list.creation BETWEEN UNIX_TIMESTAMP("' . $param['dt_start'] . ' 00:00:00") AND UNIX_TIMESTAMP("' . $param['dt_end'] . ' 23:59:59")');
            $this->db->like('channel.id', $channel);

            return $this->db->get()->result_array()[0] ?? ['automatic' => 0];
        }
    }

    function getInfo($param)
    {
        $channel = '';

        if (isset($param['channel']) && $param['channel'] == 0) {
            $channel = '';
        } else {
            $channel = $param['channel'];
        }

        if (empty($param['dt_start'])) {
            $this->db->select('COUNT(messages.id_message) AS info');
            $this->db->from('messages');

            $this->db->join('chatbot', 'chatbot.id_chatbot = messages.id_chat_bot');
            $this->db->join('chat_list', 'chat_list.id_chat_list = messages.id_chat_list');
            $this->db->join('channel', 'channel.id_channel = chat_list.id_channel');

            $this->db->where('is_menu', 2);
            $this->db->where('messages.creation >= UNIX_TIMESTAMP(CURDATE())', null, false);
            $this->db->where('messages.creation <= UNIX_TIMESTAMP(CURDATE() + INTERVAL 1 DAY) - 1', null, false);
            $this->db->like('channel.id', $channel);
            $this->db->where_not_in('id_chat_bot', "SELECT id_submenu FROM chatbot WHERE id_submenu IS NOT NULL", false);

            return $this->db->get()->result_array()[0] ?? ['info' => 0];
        }

        if (!empty($param['dt_start'])) {
            $this->db->select('COUNT(messages.id_message) AS info');
            $this->db->from('messages');

            $this->db->join('chatbot', 'chatbot.id_chatbot = messages.id_chat_bot');
            $this->db->join('chat_list', 'chat_list.id_chat_list = messages.id_chat_list');
            $this->db->join('channel', 'channel.id_channel = chat_list.id_channel');

            $this->db->where('is_menu', 2);
            $this->db->where('messages.creation BETWEEN UNIX_TIMESTAMP("' . $param['dt_start'] . ' 00:00:00") AND UNIX_TIMESTAMP("' . $param['dt_end'] . ' 23:59:59")');
            $this->db->like('channel.id', $channel);
            $this->db->where_not_in('id_chat_bot', "SELECT id_submenu FROM chatbot WHERE id_submenu IS NOT NULL", false);

            return $this->db->get()->result_array()[0] ?? ['info' => 0];
        }
    }

    function getAttendance($param)
    {
        $channel = '';

        if (isset($param['channel']) && $param['channel'] == 0) {
            $channel = '';
        } else {
            $channel = $param['channel'];
        }

        if (empty($param['dt_start'])) {
            $this->db->select('COUNT(messages.id_message) AS attendance');
            $this->db->from('messages');

            $this->db->join('chatbot', 'chatbot.id_chatbot = messages.id_chat_bot');
            $this->db->join('chat_list', 'chat_list.id_chat_list = messages.id_chat_list');
            $this->db->join('channel', 'channel.id_channel = chat_list.id_channel');

            $this->db->where('is_menu', 1);
            $this->db->where('messages.creation >= UNIX_TIMESTAMP(CURDATE())', null, false);
            $this->db->where('messages.creation <= UNIX_TIMESTAMP(CURDATE() + INTERVAL 1 DAY) - 1', null, false);
            $this->db->like('channel.id', $channel);
            $this->db->where_not_in('id_chat_bot', "SELECT id_submenu FROM chatbot WHERE id_submenu IS NOT NULL", false);

            return $this->db->get()->result_array()[0] ?? ['attendance' => 0];
        }

        if (!empty($param['dt_start'])) {
            $this->db->select('COUNT(messages.id_message) AS attendance');
            $this->db->from('messages');

            $this->db->join('chatbot', 'chatbot.id_chatbot = messages.id_chat_bot');
            $this->db->join('chat_list', 'chat_list.id_chat_list = messages.id_chat_list');
            $this->db->join('channel', 'channel.id_channel = chat_list.id_channel');

            $this->db->where('is_menu', 1);
            $this->db->where('messages.creation BETWEEN UNIX_TIMESTAMP("' . $param['dt_start'] . ' 00:00:00") AND UNIX_TIMESTAMP("' . $param['dt_end'] . ' 23:59:59")');
            $this->db->like('channel.id', $channel);
            $this->db->where_not_in('id_chat_bot', "SELECT id_submenu FROM chatbot WHERE id_submenu IS NOT NULL", false);

            return $this->db->get()->result_array()[0] ?? ['attendance' => 0];
        }
    }
}
