<?php

class DashboardBroadcast_model extends TA_model
{
    function getBroadcast($params)
    {
        $this->db->select("
            CASE 
                WHEN '{$params['period']}' IN ('today', 'yesterday') 
                THEN DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR), '%d/%m/%Y %H:%i:%s') 
                ELSE DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR), '%d/%m/%Y') 
            END AS date_all,
            
            CASE 
                WHEN '{$params['period']}' IN ('today', 'yesterday') 
                THEN DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR), '%d/%m/%Y %H:%i:%s') 
                ELSE DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR), '%d/%m/%Y') 
            END AS title,

            IFNULL(SUM(
                CASE
                    WHEN broadcast_schedule.is_wa_community = 1 THEN broadcast_schedule.count
                    WHEN broadcast_schedule.is_wa_status = 1 THEN broadcast_schedule.message_send
                    WHEN broadcast_schedule.is_waba_broadcast = 1 THEN broadcast_schedule.message_send
                    WHEN broadcast_schedule.is_wa_broadcast = 1 THEN 
                        CASE
                            WHEN broadcast_schedule.message_send > broadcast_schedule.count THEN broadcast_schedule.count
                            ELSE broadcast_schedule.message_send
                        END
                END
            ), 0) AS total_message_send,

            IFNULL(SUM(
                CASE
                    WHEN broadcast_schedule.is_wa_community = 1 THEN broadcast_schedule.message_read
                    WHEN broadcast_schedule.is_wa_status = 1 THEN broadcast_schedule.message_read
                    WHEN broadcast_schedule.is_waba_broadcast = 1 THEN broadcast_schedule.message_read
                    WHEN broadcast_schedule.is_wa_broadcast = 1 THEN 
                        CASE
                            WHEN broadcast_schedule.message_read > broadcast_schedule.count THEN broadcast_schedule.count
                            ELSE broadcast_schedule.message_read
                        END
                END
            ), 0) AS total_message_read,

            IFNULL(SUM(
                CASE
                    WHEN broadcast_schedule.is_wa_community = 1 THEN broadcast_schedule.count
                    WHEN broadcast_schedule.is_wa_status = 1 THEN broadcast_schedule.message_receipt
                    WHEN broadcast_schedule.is_waba_broadcast = 1 THEN broadcast_schedule.message_receipt
                    WHEN broadcast_schedule.is_wa_broadcast = 1 THEN 
                        CASE
                            WHEN broadcast_schedule.message_receipt > broadcast_schedule.count THEN broadcast_schedule.count
                            ELSE broadcast_schedule.message_receipt
                        END
                END
            ), 0) AS total_message_receipt
        ");

        $this->db->from('broadcast_schedule');
        $this->db->join('channel', 'broadcast_schedule.id_channel = channel.id_channel', 'inner');
        $this->db->join('config', 'channel.id_channel = config.id_channel', 'inner');

        if (!empty($params['period'])) {
            $this->db->where("(
                            CASE
                                WHEN '{$params['period']}' = 'today' 
                                    THEN DATE(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR)) = DATE_SUB(CURRENT_DATE, INTERVAL 0 DAY)
                                WHEN '{$params['period']}' = 'yesterday' 
                                    THEN DATE(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR)) = DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)
                                WHEN '{$params['period']}' = 'week' 
                                    THEN DATE(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR)) > DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)
                                WHEN '{$params['period']}' = '15_days' 
                                    THEN DATE(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR)) > DATE_SUB(CURRENT_DATE, INTERVAL 15 DAY)
                                WHEN '{$params['period']}' = 'this_month' 
                                    THEN DATE(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR)) > DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
                                WHEN '{$params['period']}' = 'last_month' 
                                    THEN DATE(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR)) > DATE_SUB(CURRENT_DATE, INTERVAL 60 DAY)
                                WHEN '{$params['period']}' = 'two_months_ago' 
                                    THEN DATE(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR)) > DATE_SUB(CURRENT_DATE, INTERVAL 90 DAY)
                            END
            )", null, false);
        }

        $this->db->where('broadcast_schedule.status', 2);

        if (!empty($params['id_channel'])) {
            $this->db->where_in('broadcast_schedule.id_channel', $params['id_channel']);
        }

        if ($params['period'] == 'today' || $params['period'] == 'yesterday') {
            $this->db->group_by([
                'date_all',
                'title',
                'broadcast_schedule.creation',
                'broadcast_schedule.id_broadcast_schedule'
            ]);
        } else {
            $this->db->group_by([
                'date_all',
                'title'
            ]);
        }

        $this->db->order_by("STR_TO_DATE(date_all, '%d/%m/%Y %H:%i:%s')", "ASC", false);

        $query = $this->db->get();


        if ($query && $query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }

    function getInteraction($params)
    {
        switch ($params['period']) {
            case 'today':
                $period = "STR_TO_DATE('01/01/1900', '%d/%m/%Y') AND CURRENT_DATE";
                $days = 0;
                break;

            case 'yesterday':
                $period = "STR_TO_DATE('01/01/1900', '%d/%m/%Y') AND DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)";
                $days = 1;
                break;

            case 'week':
                $period = "DATE_SUB(CURRENT_DATE, INTERVAL 6 DAY) AND CURRENT_DATE";
                $days = 6;
                break;

            case '15_days':
                $period = "DATE_SUB(CURRENT_DATE, INTERVAL 14 DAY) AND CURRENT_DATE";
                $days = 14;
                break;

            case 'this_month':
                $period = "DATE_SUB(CURRENT_DATE, INTERVAL 29 DAY) AND CURRENT_DATE";
                $days = 29;
                break;

            case 'last_month':
                $period = "DATE_SUB(CURRENT_DATE, INTERVAL 59 DAY) AND CURRENT_DATE";
                $days = 59;
                break;

            default:
                $period = "DATE_SUB(CURRENT_DATE, INTERVAL 89 DAY) AND CURRENT_DATE";
                $days = 89;
                break;
        }

        $query = $this->db->query("
            WITH RECURSIVE date_series AS (
                SELECT DATE_SUB(CURRENT_DATE, INTERVAL $days DAY) AS date_creation
                UNION ALL
                SELECT DATE_ADD(date_creation, INTERVAL 1 DAY)
                FROM date_series
                WHERE date_creation < CURRENT_DATE
            ), 
            search_contacts AS (
                SELECT 
                    DATE(DATE_ADD(FROM_UNIXTIME(contact.creation), INTERVAL config.timezone HOUR)) AS date_creation,
                    COUNT(*) AS total_contacts,
                    COUNT(CASE 
                        WHEN DATE(FROM_UNIXTIME(contact.broadcast_receipt)) > DATE(DATE_SUB(DATE_SUB(CURRENT_DATE, INTERVAL $days DAY), INTERVAL 30 DAY))
                        OR (contact.broadcast_receipt IS NULL AND DATE(FROM_UNIXTIME(contact.creation)) > DATE(DATE_SUB(DATE_SUB(CURRENT_DATE, INTERVAL $days DAY), INTERVAL 30 DAY)))
                        THEN 1 
                    END) AS total_active_contacts
                FROM contact
                INNER JOIN channel ON contact.id_channel = channel.id_channel
                INNER JOIN config ON config.id_channel = channel.id_channel
                WHERE contact.deleted = 1
                AND contact.spam = 1
                AND contact.verify = 2
                AND contact.exist = 1
                AND channel.status = 1
                AND channel.is_broadcast = 1
                AND config.is_broadcast = 1
                AND channel.type IN (2, 8, 9, 12, 16)
                AND config.attendance_enable = 2
                AND contact.id_channel IN ({$params['id_channel']})
                GROUP BY date_creation
            ),
            daily_inactive AS (
                SELECT 
                    d.date_creation,
                    COUNT(*) AS total_inactive_contacts
                FROM date_series d

                LEFT JOIN contact c ON 
                    c.id_channel IN ({$params['id_channel']})
                   AND c.verify = 2
                    AND c.exist = 1
                    AND c.deleted = 1
                    AND c.spam = 1
                    AND (
                        DATE(FROM_UNIXTIME(c.broadcast_receipt)) < DATE(DATE_SUB(d.date_creation, INTERVAL 30 DAY))
                        OR (c.broadcast_receipt IS NULL AND DATE(FROM_UNIXTIME(c.creation)) < DATE(DATE_SUB(d.date_creation, INTERVAL 30 DAY)))
                    )
                GROUP BY d.date_creation
            ),
            accumulated AS (
                SELECT 
                    date_creation,
                    total_inactive_contacts
                FROM daily_inactive
            )
            SELECT 
                DATE_FORMAT(d.date_creation, '%d/%m/%Y') AS date_creation,

                (SELECT SUM(COALESCE(sc.total_contacts, 0)) 
                FROM search_contacts sc 
                WHERE sc.date_creation <= d.date_creation) AS total_contacts,

                (SELECT SUM(COALESCE(sc.total_contacts, 0)) 
                FROM search_contacts sc 
                WHERE sc.date_creation <= d.date_creation)

                -

                COALESCE(a.total_inactive_contacts, 0) AS total_active_contacts,

                
                COALESCE(a.total_inactive_contacts, 0) AS total_inactive_contacts

            FROM date_series d
            LEFT JOIN search_contacts sc ON sc.date_creation = d.date_creation
            LEFT JOIN accumulated a ON a.date_creation = d.date_creation
            WHERE d.date_creation
                BETWEEN $period
            ORDER BY d.date_creation ASC;
        ");

        if ($query->num_rows() > 0) {
            if ($params['period'] === 'today' || $params['period'] === 'yesterday') {
                $tot_data = [];
                $data = array_reverse($query->result_array())[0];

                array_push($tot_data, $data);

                return $tot_data;
            }

            return $query->result_array();
        } else {
            return null;
        }
    }

    function getReactions($params)
    {
        $this->db->select("
            CASE
                WHEN DATE_ADD(FROM_UNIXTIME(broadcast_schedule.creation), INTERVAL config.timezone HOUR) >= CURRENT_DATE - INTERVAL 1 DAY 
                THEN DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.creation), INTERVAL config.timezone HOUR), '%d/%m/%Y %H:%i:%s') 
                ELSE DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.creation), INTERVAL config.timezone HOUR), '%d/%m/%Y') 
            END AS date_all,
            
            SUM(broadcast_schedule.message_reactions) AS total_reactions
        ");

        $this->db->from('broadcast_schedule');
        $this->db->join('channel', 'broadcast_schedule.id_channel = channel.id_channel', 'inner');
        $this->db->join('config', 'channel.id_channel = config.id_channel', 'inner');

        if (!empty($params['period'])) {
            $this->db->where("(
                CASE
                    WHEN '{$params['period']}' = 'today' THEN DATE_ADD(FROM_UNIXTIME(broadcast_schedule.creation), INTERVAL config.timezone HOUR) = CURRENT_DATE
                    WHEN '{$params['period']}' = 'yesterday' THEN DATE_ADD(FROM_UNIXTIME(broadcast_schedule.creation), INTERVAL config.timezone HOUR) BETWEEN CURRENT_DATE - INTERVAL 1 DAY AND CURRENT_DATE
                    WHEN '{$params['period']}' = 'week' THEN DATE_ADD(FROM_UNIXTIME(broadcast_schedule.creation), INTERVAL config.timezone HOUR) > CURRENT_DATE - INTERVAL 7 DAY
                    WHEN '{$params['period']}' = '15_days' THEN DATE_ADD(FROM_UNIXTIME(broadcast_schedule.creation), INTERVAL config.timezone HOUR) > CURRENT_DATE - INTERVAL 15 DAY
                    WHEN '{$params['period']}' = 'this_month' THEN DATE_ADD(FROM_UNIXTIME(broadcast_schedule.creation), INTERVAL config.timezone HOUR) > CURRENT_DATE - INTERVAL 30 DAY
                    WHEN '{$params['period']}' = 'last_month' THEN DATE_ADD(FROM_UNIXTIME(broadcast_schedule.creation), INTERVAL config.timezone HOUR) > CURRENT_DATE - INTERVAL 60 DAY
                    WHEN '{$params['period']}' = 'two_months_ago' THEN DATE_ADD(FROM_UNIXTIME(broadcast_schedule.creation), INTERVAL config.timezone HOUR) > CURRENT_DATE - INTERVAL 90 DAY
                END
            )", null, false);
        }

        $this->db->where('broadcast_schedule.status', 2);

        if (!empty($params['id_channel'])) {
            $this->db->where_in('broadcast_schedule.id_channel', $params['id_channel']);
        }

        $this->db->group_by([
            'date_all',
            "DATE_ADD(FROM_UNIXTIME(broadcast_schedule.creation), INTERVAL config.timezone HOUR)"
        ]);

        $this->db->order_by("DATE_ADD(FROM_UNIXTIME(broadcast_schedule.creation), INTERVAL config.timezone HOUR) ASC", false);

        $query = $this->db->get();

        if ($query && $query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }

    function getAllContacts($params)
    {
        switch ($params['period']) {
            case 'today':
                $period = 0;
                $symbol = "<=";
                break;
            case 'yesterday':
                $period = 1;
                $symbol = "<=";
                break;
            case 'week':
                $period = 7;
                $symbol = "<";
                break;
            case '15_days':
                $period = 15;
                $symbol = "<";
                break;
            case 'this_month':
                $period = 30;
                $symbol = "<";
                break;
            case 'last_month':
                $period = 60;
                $symbol = "<";
                break;
            default:
                $period = 90;
                $symbol = "<";
                break;
        }

        $query = $this->db->query("
                WITH RECURSIVE days(n) AS (
                    SELECT 0 
                    UNION ALL 
                    SELECT n + 1 FROM days WHERE n < $period
                ), 
                total_contacts_base AS (
                    SELECT COUNT(id_contact) AS total_base
                    FROM contact
                    WHERE id_channel = {$params['id_channel']}
                    AND deleted = 1
                )
                SELECT 
                    DATE_FORMAT(DATE_SUB(CURRENT_DATE(), INTERVAL days.n DAY), '%d/%m/%Y') AS date_creation,
                    (SELECT total_base FROM total_contacts_base) AS total_contacts_base,

                    COUNT(CASE 
                        WHEN ( 
                            (contact.broadcast_receipt IS NULL 
                            OR DATE_ADD(FROM_UNIXTIME(contact.broadcast_receipt), INTERVAL COALESCE(config.timezone, 0) HOUR) >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY))
                            AND DATE_ADD(FROM_UNIXTIME(contact.creation), INTERVAL COALESCE(config.timezone, 0) HOUR) >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)
                        ) THEN 1 
                        ELSE NULL 
                    END) AS active_contacts,

                    COUNT(CASE 
                        WHEN contact.id_contact IS NOT NULL AND ( 
                            DATE_ADD(FROM_UNIXTIME(contact.broadcast_receipt), INTERVAL COALESCE(config.timezone, 0) HOUR) <= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)
                            OR contact.broadcast_receipt IS NULL
                        ) THEN 1 
                        ELSE NULL 
                    END) AS inactive_contacts,

                    ROUND(
                        (COUNT(CASE 
                            WHEN ( 
                                (contact.broadcast_receipt IS NULL 
                                OR DATE_ADD(FROM_UNIXTIME(contact.broadcast_receipt), INTERVAL COALESCE(config.timezone, 0) HOUR) >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY))
                                AND DATE_ADD(FROM_UNIXTIME(contact.creation), INTERVAL COALESCE(config.timezone, 0) HOUR) >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)
                            ) THEN 1 
                            ELSE NULL 
                        END) * 100.0) / NULLIF((SELECT total_base FROM total_contacts_base), 0), 2
                    ) AS percentage_active_contacts,

                    ROUND(
                        (COUNT(CASE 
                            WHEN contact.id_contact IS NOT NULL AND ( 
                                DATE_ADD(FROM_UNIXTIME(contact.broadcast_receipt), INTERVAL COALESCE(config.timezone, 0) HOUR) <= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)
                                OR contact.broadcast_receipt IS NULL
                            ) THEN 1 
                            ELSE NULL 
                        END) * 100.0) / NULLIF((SELECT total_base FROM total_contacts_base), 0), 2
                    ) AS percentage_inactive_contacts

                FROM days
                LEFT JOIN channel ON channel.id_channel = {$params['id_channel']}
                LEFT JOIN config ON channel.id_channel = config.id_channel
                LEFT JOIN contact 
                    ON DATE(DATE_ADD(FROM_UNIXTIME(contact.creation), INTERVAL COALESCE(config.timezone, 0) HOUR)) = DATE_SUB(CURRENT_DATE(), INTERVAL days.n DAY)
                    AND contact.id_channel = {$params['id_channel']}
                    AND contact.deleted = 1
                WHERE days.n {$symbol} $period
                GROUP BY date_creation
                ORDER BY STR_TO_DATE(date_creation, '%d/%m/%Y') ASC;
       ");



        if ($query && $query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }

    function getCampaignStats($params)
    {
        $this->db->select(" 
            CASE
                WHEN '{$params['period']}' = 'today' THEN 'Today'
                WHEN '{$params['period']}' = 'yesterday' THEN 'Yesterday'
                WHEN '{$params['period']}' = 'week' THEN 'Last 7 days'
                WHEN '{$params['period']}' = '15_days' THEN 'Last 15 days'
                WHEN '{$params['period']}' = 'this_month' THEN 'Last 30 days'
                WHEN '{$params['period']}' = 'last_month' THEN 'Last 60 days'
                WHEN '{$params['period']}' = 'two_months_ago' THEN 'Last 90 days'
                ELSE 'Without Period'
            END AS period,
            
            IFNULL(
                ROUND(
                    (SUM(
                        CASE
                            WHEN broadcast_schedule.is_wa_community = 1 THEN broadcast_schedule.count
                            WHEN broadcast_schedule.is_wa_status = 1 THEN broadcast_schedule.message_send
                            WHEN broadcast_schedule.is_waba_broadcast = 1 THEN broadcast_schedule.message_send
                            WHEN broadcast_schedule.is_wa_broadcast = 1 THEN 
                                CASE
                                    WHEN broadcast_schedule.message_send > broadcast_schedule.count THEN broadcast_schedule.count
                                    ELSE broadcast_schedule.message_send
                                END
                        END
                    ) / NULLIF(SUM(broadcast_schedule.count), 0)) * 100, 2
                ), 0
            ) AS percentage_message_send,

            IFNULL(
                ROUND(
                    (SUM(
                        CASE
                            WHEN broadcast_schedule.is_wa_community = 1 THEN broadcast_schedule.count
                            WHEN broadcast_schedule.is_wa_status = 1 THEN broadcast_schedule.message_receipt
                            WHEN broadcast_schedule.is_waba_broadcast = 1 THEN broadcast_schedule.message_receipt
                            WHEN broadcast_schedule.is_wa_broadcast = 1 THEN 
                                CASE
                                    WHEN broadcast_schedule.message_receipt > broadcast_schedule.count THEN broadcast_schedule.count
                                    ELSE broadcast_schedule.message_receipt
                                END
                        END
                    ) / NULLIF(SUM(broadcast_schedule.count), 0)) * 100, 2
                ), 0
            ) AS percentage_message_receipt

        ", false);

        $this->db->from('broadcast_schedule');
        $this->db->join('channel', 'broadcast_schedule.id_channel = channel.id_channel', 'inner');
        $this->db->join('config', 'channel.id_channel = config.id_channel', 'inner');

        $this->db->where('broadcast_schedule.status', 2);

        if (!empty($params['id_channel'])) {
            $this->db->where_in('broadcast_schedule.id_channel', $params['id_channel']);
        }

        if (!empty($params['period'])) {
            $this->db->where("(
                CASE
                    WHEN '{$params['period']}' = 'today' 
                        THEN DATE(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR)) = DATE_SUB(CURRENT_DATE, INTERVAL 0 DAY)
                    WHEN '{$params['period']}' = 'yesterday' 
                        THEN DATE(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR)) = DATE_SUB(CURRENT_DATE, INTERVAL 1 DAY)
                    WHEN '{$params['period']}' = 'week' 
                        THEN DATE(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR)) > DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)
                    WHEN '{$params['period']}' = '15_days' 
                        THEN DATE(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR)) > DATE_SUB(CURRENT_DATE, INTERVAL 15 DAY)
                    WHEN '{$params['period']}' = 'this_month' 
                        THEN DATE(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR)) > DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
                    WHEN '{$params['period']}' = 'last_month' 
                        THEN DATE(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR)) > DATE_SUB(CURRENT_DATE, INTERVAL 60 DAY)
                    WHEN '{$params['period']}' = 'two_months_ago' 
                        THEN DATE(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR)) > DATE_SUB(CURRENT_DATE, INTERVAL 90 DAY)
                END
            )", null, false);
        }

        $query = $this->db->get();

        if ($query && $query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }

    function getActiveContacts($params)
    {
        switch ($params['period']) {
            case 'today':
                $period = 0;
                $symbol = "<=";
                break;
            case 'yesterday':
                $period = 1;
                $symbol = "<=";
                break;
            case 'week':
                $period = 7;
                $symbol = "<";
                break;
            case '15_days':
                $period = 15;
                $symbol = "<";
                break;
            case 'this_month':
                $period = 30;
                $symbol = "<";
                break;
            case 'last_month':
                $period = 60;
                $symbol = "<";
                break;
            default:
                $period = 90;
                $symbol = "<";
                break;
        }

        $sql = "
                WITH RECURSIVE days(n) AS (
                    SELECT 0 
                    UNION ALL 
                    SELECT n + 1 FROM days WHERE n < '{$period}'
                )
                SELECT 
                    DATE_FORMAT(DATE_SUB(CURRENT_DATE(), INTERVAL days.n DAY), '%d/%m/%Y') AS date_creation,
                    COUNT(contact.id_contact) AS total,
                    COUNT(CASE 
                        WHEN ( 
                            (contact.broadcast_receipt IS NULL 
                            OR DATE_ADD(FROM_UNIXTIME(contact.broadcast_receipt), INTERVAL COALESCE(config.timezone, 0) HOUR) >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY))
                            AND DATE_ADD(FROM_UNIXTIME(contact.creation), INTERVAL COALESCE(config.timezone, 0) HOUR) >= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)
                        ) THEN 1 
                        ELSE NULL 
                    END) AS active_contacts
                FROM days
                LEFT JOIN channel ON channel.id_channel = '{$params['id_channel']}'
                LEFT JOIN config ON channel.id_channel = config.id_channel
                LEFT JOIN contact 
                    ON DATE(DATE_ADD(FROM_UNIXTIME(contact.creation), INTERVAL COALESCE(config.timezone, 0) HOUR)) = DATE_SUB(CURRENT_DATE(), INTERVAL days.n DAY)
                    AND contact.deleted = 1
                    AND contact.id_channel = '{$params['id_channel']}'
                WHERE days.n {$symbol} '{$period}'
                GROUP BY date_creation
                ORDER BY STR_TO_DATE(date_creation, '%d/%m/%Y') ASC;
        ";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }

    function getInactiveContacts($params)
    {
        switch ($params['period']) {
            case 'today':
                $period = 0;
                $symbol = "<=";
                break;

            case 'yesterday':
                $period = 1;
                $symbol = "<=";
                break;

            case 'week':
                $period = 7;
                $symbol = "<";
                break;

            case '15_days':
                $period = 15;
                $symbol = "<";
                break;

            case 'this_month':
                $period = 30;
                $symbol = "<";
                break;

            case 'last_month':
                $period = 60;
                $symbol = "<";
                break;

            default:
                $period = 90;
                $symbol = "<";
                break;
        }

        $sql = "
            WITH RECURSIVE days(n) AS (
                SELECT 0 
                UNION ALL 
                SELECT n + 1 FROM days WHERE n < '{$period}'
            )
            SELECT 
                DATE_FORMAT(DATE_SUB(CURRENT_DATE(), INTERVAL days.n DAY), '%d/%m/%Y') AS date_creation,
                COUNT(contact.id_contact) AS total,
                COUNT(CASE 
                        WHEN contact.id_contact IS NOT NULL AND ( 
                            DATE_ADD(FROM_UNIXTIME(contact.broadcast_receipt), INTERVAL COALESCE(config.timezone, 0) HOUR) <= DATE_SUB(CURRENT_DATE(), INTERVAL 30 DAY)
                            OR contact.broadcast_receipt IS NULL
                        ) THEN 1 
                        ELSE NULL 
                    END) AS inactive_contacts
            FROM days
            LEFT JOIN channel 
                ON channel.id_channel = '{$params['id_channel']}'
            LEFT JOIN config 
                ON channel.id_channel = config.id_channel
            LEFT JOIN contact 
                ON DATE(DATE_ADD(FROM_UNIXTIME(contact.creation), INTERVAL COALESCE(config.timezone, 0) HOUR)) = DATE_SUB(CURRENT_DATE(), INTERVAL days.n DAY)
                AND contact.id_channel = '{$params['id_channel']}'
                AND contact.deleted = 1
            WHERE days.n {$symbol} '{$period}'
            GROUP BY date_creation
            ORDER BY STR_TO_DATE(date_creation, '%d/%m/%Y') ASC;
       ";

        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return null;
        }
    }

    function listChannels()
    {
        $this->db->select("channel.id_channel, 
                            CASE 
                                WHEN LENGTH(channel.name) > 40 
                                THEN CONCAT(channel.name, ' (', SUBSTR(channel.id, 1, 12),'...)') 
                                ELSE CONCAT(channel.name, ' (', channel.id, ')') 
                            END as name", FALSE);
        $this->db->from("channel");
        $this->db->join('config', 'config.id_channel = channel.id_channel');
        $this->db->where("channel.type", 2);
        $this->db->where("channel.status", 1);


        return $this->db->get()->result_array();
    }

    function getEngagement($params)
    {
        $id_channels = [];

        $most = $this->mostEngagement($params);

        if (count($most) > 0) {
            foreach ($most['most'] as $value) {
                $id_channels[] = $value['id_channel'];
            }
        }

        $params['id_channels'] = $id_channels;

        $less = $this->lessEngagement($params);

        $id_channels = [];
        $params['id_channels'] = '';

        $most_received = $this->getReceived($params, 'DESC');

        if (count($most_received) > 0) {
            foreach ($most_received['list'] as $value) {
                $id_channels[] = $value['id_channel'];
            }
        }

        $less_received = $this->getReceived($params, 'ASC');

        return [
            'most' => $most,
            'less' => $less,
            'most_received' => $most_received,
            'less_received' => $less_received,
        ];
    }

    function mostEngagement($params)
    {
        $period = null;

        if (!empty($params['period'])) {
            $period = $params['period'];

            switch ($period) {
                case 'today':
                    $dt = 0;
                    break;
                case 'yesterday':
                    $dt = 1;
                    break;
                case 'week':
                    $dt = 7;
                    break;
                case '15_days':
                    $dt = 15;
                    break;
                case 'this_month':
                    $dt = 30;
                    break;
                case 'last_month':
                    $dt = 60;
                    break;
                case 'two_months_ago':
                    $dt = 90;
                    break;
                default:
                    $dt = 0;
            }

            $this->db->select('channel.id_channel, channel.id, channel.name, COUNT(contact.id_contact) AS total_contacts', false);
            $this->db->from('contact');
            $this->db->join('channel', 'channel.id_channel = contact.id_channel', 'inner');
            $this->db->where('contact.deleted', 1);
            $this->db->where('contact.spam', 1);
            $this->db->where('channel.status', 1);
            $this->db->where('channel.type', 2);

            if ($period != 'total') {
                $this->db->where("contact.creation >=", "UNIX_TIMESTAMP(DATE_SUB(CURDATE(), INTERVAL $dt DAY))", false);
            }

            $this->db->group_by('channel.id_channel');
            $this->db->order_by('total_contacts', 'DESC');
            $this->db->limit(3);

            $new_contacts = $this->db->get()->result_array();


            return $most = [
                'most' => $new_contacts,
            ];
        }
    }

    function lessEngagement($params)
    {
        $period = null;

        if (!empty($params['period'])) {
            $period = $params['period'];

            switch ($period) {
                case 'today':
                    $dt = 0;
                    break;
                case 'yesterday':
                    $dt = 1;
                    break;
                case 'week':
                    $dt = 7;
                    break;
                case '15_days':
                    $dt = 15;
                    break;
                case 'this_month':
                    $dt = 30;
                    break;
                case 'last_month':
                    $dt = 60;
                    break;
                case 'two_months_ago':
                    $dt = 90;
                    break;
                default:
                    $dt = 0;
            }

            $this->db->select('channel.id_channel, channel.id, channel.name, COUNT(contact.id_contact) AS total_contacts', false);
            $this->db->from('contact');
            $this->db->join('channel', 'channel.id_channel = contact.id_channel', 'inner');
            $this->db->where('contact.deleted', 1);
            $this->db->where('contact.spam', 1);
            $this->db->where('channel.status', 1);
            $this->db->where('channel.type', 2);

            if ($period != 'total') {
                $this->db->where("contact.creation >=", "UNIX_TIMESTAMP(DATE_SUB(CURDATE(), INTERVAL $dt DAY))", false);
            }

            if (count($params['id_channels']) > 0) {
                $id_channels =  $params['id_channels'];
                $this->db->where_not_in('channel.id_channel', $id_channels);
            }

            $this->db->group_by('channel.id_channel');
            $this->db->order_by('total_contacts', 'ASC');
            $this->db->limit(3);

            $new_contacts = $this->db->get()->result_array();

            return $less = [
                'less' => $new_contacts,
            ];
        }
    }

    function getReceived($params, $mode)
    {
        $period = null;

        if (!empty($params['period'])) {
            $period = $params['period'];

            switch ($period) {
                case 'today':
                    $dt = 0;
                    break;
                case 'yesterday':
                    $dt = 1;
                    break;
                case 'week':
                    $dt = 7;
                    break;
                case '15_days':
                    $dt = 15;
                    break;
                case 'this_month':
                    $dt = 30;
                    break;
                case 'last_month':
                    $dt = 60;
                    break;
                case 'two_months_ago':
                    $dt = 90;
                    break;
                default:
                    $dt = 0;
            }

            $this->db->select('
                            channel.id_channel, 
                            channel.id, 
                            channel.name, 
                            SUM(broadcast_schedule.message_send) AS total_send, 
                            SUM(broadcast_schedule.message_receipt) AS total_receipt, 
                            COUNT(broadcast_schedule.id_broadcast_schedule) AS total_campaigns', false);

            $this->db->from('broadcast_schedule');
            $this->db->join('channel', 'channel.id_channel = broadcast_schedule.id_channel', 'inner');
            $this->db->where('broadcast_schedule.status', 2);
            $this->db->where('channel.type', 2);

            if (isset($params['id_channels'])) {
                if (!empty($params['id_channels'])) {
                    $id_channels =  $params['id_channels'];
                    $this->db->where_not_in('channel.id_channel', $id_channels);
                }
            }

            if ($period != "total") {
                $this->db->where("broadcast_schedule.schedule >=", "UNIX_TIMESTAMP(DATE_SUB(CURDATE(), INTERVAL $dt DAY))", false);
            }

            $this->db->group_by(['channel.id_channel']);
            $this->db->order_by('total_send', $mode);
            $this->db->limit(3);

            $result = $this->db->get()->result_array();
            $gain = [];

            foreach ($result as $value) {
                if ($value['total_send'] > 0 && $value['total_receipt'] > 0) {

                    if ($value['total_receipt'] > $value['total_send']) {
                        $gain[] = 100;
                    } else {
                        $gain_percentage = ($value['total_receipt'] / $value['total_send']) * 100;
                        $gain[] = round($gain_percentage, 2);
                    }
                } else {
                    $gain[] = 0;
                }
            }

            return $result = [
                'gain' => $gain,
                'list' => $result
            ];
        }
    }
}
