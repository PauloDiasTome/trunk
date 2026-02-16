<?php

class ReportSend_model extends TA_model
{
    function Get($param)
    {
        $channels = "";
        $order_dir = $param['order'][0]['dir'] ?? "schedule";

        if (!empty($param['channel']))
            $channels = implode(",", $param['channel']);

        // Alterações no select de acordo com tipo de campanha realizado conforme solicitado na atividade //
        $sql = "SELECT 
                    broadcast_schedule.title,
                    broadcast_schedule.media_url,
                    broadcast_schedule.id_channel,
                    broadcast_schedule.media_type,
                    broadcast_schedule.is_wa_status,
                    broadcast_schedule.is_wa_channel,
                    broadcast_schedule.is_wa_broadcast,
                    broadcast_schedule.is_waba_broadcast,
                    broadcast_schedule.is_wa_community,
                    broadcast_schedule.token,
                    DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR), '%d/%m/%Y - %H:%i') schedule,
                    CONCAT(channel.name, ' (', SUBSTRING_INDEX(channel.id, '@', 1), ')') name_channel,
                    broadcast_schedule.status status_send,
                    CASE
                        WHEN broadcast_schedule.is_wa_community = 1 THEN broadcast_schedule.count
                        WHEN broadcast_schedule.is_wa_status = 1 THEN broadcast_schedule.message_send
                        WHEN broadcast_schedule.is_waba_broadcast = 1 THEN broadcast_schedule.message_send
                        WHEN broadcast_schedule.is_wa_broadcast = 1 THEN 
                            CASE 
                                WHEN broadcast_schedule.message_send > broadcast_schedule.count THEN broadcast_schedule.count
                                ELSE broadcast_schedule.message_send 
                            END
                    END message_send,
                    CASE
                        WHEN broadcast_schedule.is_wa_channel = 1 AND broadcast_schedule.status = 6 THEN '0%'
                        WHEN broadcast_schedule.is_wa_channel = 1 AND broadcast_schedule.status = 2 THEN '100%'
                        WHEN broadcast_schedule.is_wa_community = 1 THEN CONCAT(ROUND(CASE WHEN broadcast_schedule.count = 0 THEN 0 ELSE PERCENTAGE(broadcast_schedule.count, broadcast_schedule.count) END), '%')
                        WHEN broadcast_schedule.is_wa_status = 1 THEN CONCAT(ROUND(CASE WHEN broadcast_schedule.message_send = 0 THEN 0 ELSE PERCENTAGE(broadcast_schedule.count, broadcast_schedule.message_send) END), '%')
                        WHEN broadcast_schedule.is_waba_broadcast = 1 THEN CONCAT(ROUND(CASE WHEN broadcast_schedule.message_send = 0 THEN 0 ELSE PERCENTAGE(broadcast_schedule.count, broadcast_schedule.message_send) END), '%')
                        WHEN broadcast_schedule.is_wa_broadcast = 1 THEN 
                            CASE 
                                WHEN broadcast_schedule.message_send > broadcast_schedule.count THEN CONCAT(ROUND(CASE WHEN broadcast_schedule.count = 0 THEN 0 ELSE PERCENTAGE(broadcast_schedule.count, broadcast_schedule.count) END), '%')
                                ELSE CONCAT(ROUND(CASE WHEN broadcast_schedule.message_send = 0 THEN 0 ELSE PERCENTAGE(broadcast_schedule.count, broadcast_schedule.message_send) END), '%')
                            END
                    END porcent_send,
                    CASE
                        WHEN broadcast_schedule.is_wa_community = 1 THEN broadcast_schedule.count
                        WHEN broadcast_schedule.is_wa_status = 1 THEN broadcast_schedule.message_receipt
                        WHEN broadcast_schedule.is_waba_broadcast = 1 THEN broadcast_schedule.message_receipt
                        WHEN broadcast_schedule.is_wa_broadcast = 1 THEN 
                            CASE 
                                WHEN broadcast_schedule.message_receipt > broadcast_schedule.count THEN broadcast_schedule.count
                                ELSE broadcast_schedule.message_receipt 
                            END
                    END message_receipt,
                    CASE
                        WHEN broadcast_schedule.is_wa_channel = 1 AND broadcast_schedule.status = 6 THEN '0%'
                        WHEN broadcast_schedule.is_wa_channel = 1 AND broadcast_schedule.status = 2 THEN '100%'
                        WHEN broadcast_schedule.is_wa_community = 1 THEN CONCAT(ROUND(CASE WHEN broadcast_schedule.count = 0 THEN 0 ELSE PERCENTAGE(broadcast_schedule.count, broadcast_schedule.count) END), '%')
                        WHEN broadcast_schedule.is_wa_status = 1 THEN CONCAT(ROUND(CASE WHEN broadcast_schedule.message_receipt = 0 THEN 0 ELSE PERCENTAGE(broadcast_schedule.count, broadcast_schedule.message_receipt) END), '%')
                        WHEN broadcast_schedule.is_waba_broadcast = 1 THEN CONCAT(ROUND(CASE WHEN broadcast_schedule.message_receipt = 0 THEN 0 ELSE PERCENTAGE(broadcast_schedule.count, broadcast_schedule.message_receipt) END), '%')
                        WHEN broadcast_schedule.is_wa_broadcast = 1 THEN 
                            CASE 
                                WHEN broadcast_schedule.message_receipt > broadcast_schedule.count THEN CONCAT(ROUND(CASE WHEN broadcast_schedule.count = 0 THEN 0 ELSE PERCENTAGE(broadcast_schedule.count, broadcast_schedule.count) END), '%')
                                ELSE CONCAT(ROUND(CASE WHEN broadcast_schedule.message_receipt = 0 THEN 0 ELSE PERCENTAGE(broadcast_schedule.count, broadcast_schedule.message_receipt) END), '%')
                            END
                    END porcent_receipt,
                    CASE
                        WHEN broadcast_schedule.is_wa_channel = 1 THEN ''
                        WHEN broadcast_schedule.is_wa_community = 1 THEN broadcast_schedule.message_read
                        WHEN broadcast_schedule.is_wa_status = 1 THEN broadcast_schedule.message_read
                        WHEN broadcast_schedule.is_waba_broadcast = 1 THEN broadcast_schedule.message_read
                        WHEN broadcast_schedule.is_wa_broadcast = 1 THEN 
                            CASE 
                                WHEN broadcast_schedule.message_read > broadcast_schedule.count THEN broadcast_schedule.count
                                ELSE broadcast_schedule.message_read 
                            END
                    END message_read,
                    CASE
                        WHEN broadcast_schedule.is_wa_channel = 1 THEN ''
                        WHEN broadcast_schedule.is_wa_community = 1 THEN CONCAT(ROUND(CASE WHEN broadcast_schedule.message_read = 0 THEN 0 ELSE PERCENTAGE(broadcast_schedule.count, broadcast_schedule.message_read) END), '%')
                        WHEN broadcast_schedule.is_wa_status = 1 THEN CONCAT(ROUND(CASE WHEN broadcast_schedule.message_read = 0 THEN 0 ELSE PERCENTAGE(broadcast_schedule.count, broadcast_schedule.message_read) END), '%')
                        WHEN broadcast_schedule.is_waba_broadcast = 1 THEN CONCAT(ROUND(CASE WHEN broadcast_schedule.message_read = 0 THEN 0 ELSE PERCENTAGE(broadcast_schedule.count, broadcast_schedule.message_read) END), '%')
                        WHEN broadcast_schedule.is_wa_broadcast = 1 THEN 
                            CASE 
                                WHEN broadcast_schedule.message_read > broadcast_schedule.count THEN CONCAT(ROUND(CASE WHEN broadcast_schedule.count = 0 THEN 0 ELSE PERCENTAGE(broadcast_schedule.count, broadcast_schedule.count) END), '%')
                                ELSE CONCAT(ROUND(CASE WHEN broadcast_schedule.message_read = 0 THEN 0 ELSE PERCENTAGE(broadcast_schedule.count, broadcast_schedule.message_read) END), '%')
                            END
                    END porcent_read
                FROM
                    broadcast_schedule
                        INNER JOIN
                    channel ON broadcast_schedule.id_channel = channel.id_channel
                        INNER JOIN
                    config ON channel.id_channel = config.id_channel
                WHERE
                    broadcast_schedule.title LIKE '%{$param['text']}%' AND broadcast_schedule.status IN (2, 6) AND channel.type IN (2, 12, 16)\n";

        if (!empty($param['channel'])) {
            $sql .= "AND broadcast_schedule.id_channel IN ($channels)\n";
        }

        if (!empty($param['campaign_type'])) {
            for ($i = 0; $i < count($param['campaign_type']); $i++) {

                $sql .= $i === 0 ? "AND (" : "OR ";

                if ($param['campaign_type'][$i] === "campaign_api") $sql .= "broadcast_schedule.is_waba_broadcast = 1\n";
                if ($param['campaign_type'][$i] === "broadcast") $sql .= "broadcast_schedule.is_wa_broadcast = 1\n";
                if ($param['campaign_type'][$i] === "community") $sql .= "broadcast_schedule.is_wa_community = 1\n";
                if ($param['campaign_type'][$i] === "channel") $sql .= "broadcast_schedule.is_wa_channel = 1\n";
                if ($param['campaign_type'][$i] === "status") $sql .= "broadcast_schedule.is_wa_status = 1\n";
            }

            $sql .= ")\n";
        }

        if (!empty($param['dt_start'])) {
            $sql .= "AND DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.schedule), INTERVAL config.timezone HOUR),'%Y-%m-%d') BETWEEN '{$param['dt_start']}' AND '{$param['dt_end']}'\n";
        } else {
            $sql .= "AND DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.schedule), INTERVAL config.timezone HOUR),'%Y-%m-%d') BETWEEN current_date() AND current_date()\n";
        }

        switch ($param['order'][0]['column']) {
            case 0:
                $sql .= "ORDER BY broadcast_schedule.schedule DESC\n";
                break;
            case 1:
                $sql .= "ORDER BY broadcast_schedule.title $order_dir\n";
                break;
            case 2:
                $sql .= "ORDER BY name_channel $order_dir\n";
                break;
            case 3:
                $sql .= "ORDER BY message_send $order_dir\n";
                break;
            case 4:
                $sql .= "ORDER BY message_receipt $order_dir\n";
                break;
            case 5:
                $sql .= "ORDER BY message_read $order_dir\n";
                break;
        }

        $sql .= "LIMIT {$param['start']},{$param['length']}\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Count($param)
    {
        $channels = "";
        $text = $param['text'] ?? "";

        if (!empty($param['channel']))
            $channels = implode(",", $param['channel']);

        $sql = "SELECT 
                    COUNT(broadcast_schedule.id_broadcast_schedule) count
                FROM
                    broadcast_schedule
                        INNER JOIN
                    channel ON broadcast_schedule.id_channel = channel.id_channel
                        INNER JOIN
                    config ON channel.id_channel = config.id_channel
                WHERE
                    broadcast_schedule.status IN (2, 6) AND broadcast_schedule.title LIKE '%{$text}%' 
                AND broadcast_schedule.status IN (2, 6) AND channel.type IN (2, 12, 16)\n";

        if (!empty($param['channel']))
            $sql .= "AND broadcast_schedule.id_channel IN ($channels)\n";

        if (!empty($param['campaign_type'])) {
            for ($i = 0; $i < count($param['campaign_type']); $i++) {

                $sql .= $i === 0 ? "AND (" : "OR ";

                if ($param['campaign_type'][$i] === "campaign_api") $sql .= "broadcast_schedule.is_waba_broadcast = 1\n";
                if ($param['campaign_type'][$i] === "broadcast") $sql .= "broadcast_schedule.is_wa_broadcast = 1\n";
                if ($param['campaign_type'][$i] === "community") $sql .= "broadcast_schedule.is_wa_community = 1\n";
                if ($param['campaign_type'][$i] === "channel") $sql .= "broadcast_schedule.is_wa_channel = 1\n";
                if ($param['campaign_type'][$i] === "status") $sql .= "broadcast_schedule.is_wa_status = 1\n";
            }

            $sql .= ")\n";
        }

        if (!empty($param['dt_start'])) {
            $sql .= "AND DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.schedule), INTERVAL config.timezone HOUR),'%Y-%m-%d') BETWEEN '{$param['dt_start']}' AND '{$param['dt_end']}'\n";
        } else {
            $sql .= "AND DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_schedule.schedule), INTERVAL config.timezone HOUR),'%Y-%m-%d') BETWEEN current_date() AND current_date()\n";
        }

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function ListChannels()
    {
        $sql = "select channel.id_channel, concat(channel.name,' (',channel.id,')') name FROM channel where type in (2, 12, 16) and status = 1\n";

        $result = $this->db->query($sql);

        $data = $result->result_array();

        return $data;
    }


    function View($token)
    {
        $sql = "SELECT                    
                    REPLACE(channel.id, '@c.us', '') channel,
                    DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.schedule), INTERVAL config.timezone HOUR), '%d/%m/%Y %H:%i') schedule,
                    DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.creation), INTERVAL config.timezone HOUR), '%d/%m/%Y %H:%i') creation,
                    CASE
                        WHEN broadcast_schedule.expire = '0' THEN ''
                        ELSE DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule.expire), INTERVAL config.timezone HOUR), '%d/%m/%Y %H:%i')     
                    END expire,                    
                    broadcast_schedule.id_channel,
                    broadcast_schedule.status,
                    broadcast_schedule.token,
                    broadcast_schedule.groups,
                    broadcast_schedule.title,
                    broadcast_schedule.id_template,
                    broadcast_schedule.data,
                    broadcast_schedule_log.key_remote_id,
                    broadcast_schedule.media_url,
                    broadcast_schedule.media_type,
                    broadcast_schedule.json_parameters,
                    broadcast_schedule.is_waba_broadcast,
                    broadcast_schedule.is_wa_broadcast,
                    broadcast_schedule.is_wa_community,
                    broadcast_schedule.is_wa_status,
                    group_contact.name as group_name,    
                    template.name template_name,
                    template.header,
                    template.text_body text_body,
                    template.text_footer,
                    channel.name name_channel
                FROM
                    broadcast_schedule
                        LEFT JOIN
                    broadcast_schedule_log ON broadcast_schedule.id_broadcast_schedule = broadcast_schedule_log.id_broadcast_schedule
                        INNER JOIN
                    channel ON broadcast_schedule.id_channel = channel.id_channel
                        LEFT JOIN
                    template ON broadcast_schedule.id_template = template.id_template
                        LEFT JOIN
					group_contact on broadcast_schedule.groups = group_contact.id_group_contact
                        INNER JOIN 
                    config ON channel.id_channel = config.id_channel
                WHERE
                    broadcast_schedule.token = '$token'";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function getLog($token)
    {
        $sql = "SELECT 
                    broadcast_schedule_log.*,
                    DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(broadcast_schedule_log.creation),
                                INTERVAL config.timezone HOUR),
                            '%d/%m/%Y %H:%i') log_creation,
                    user.name
                FROM
                    broadcast_schedule_log
                        INNER JOIN
                    broadcast_schedule ON broadcast_schedule_log.id_broadcast_schedule = broadcast_schedule.id_broadcast_schedule
                        INNER JOIN
                    user ON broadcast_schedule_log.key_remote_id = user.key_remote_id
                        INNER JOIN
                    channel ON user.key_remote_id = channel.id
                        INNER JOIN
                    config ON channel.id_channel = config.id_channel
                WHERE
                    broadcast_schedule.token = '$token'
                ORDER BY broadcast_schedule_log.creation";

        $result = $this->db->query($sql);

        return $result->result_array();
    }
}
