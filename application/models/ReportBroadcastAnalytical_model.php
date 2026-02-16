<?php

class ReportBroadcastAnalytical_model extends TA_model
{
    function ListChannels()
    {
        $sql = "SELECT
                    channel.id_channel as id_channel,
                    concat(upper(channel.name),' (',channel.id,')') as name,
                    channel.id as id,
                    channel.consolidating as consolidating,
                    channel.display_phone_number as number,
                    DATE_FORMAT(FROM_UNIXTIME(`consolidation_date`), '%d/%m/%Y') as consolidation_date
                FROM
                    channel
                WHERE 
                    channel.type IN (2, 8, 9, 10, 12, 16) AND channel.status = 1
                ORDER BY
                    channel.name";

        $result = $this->db->query($sql);

        return $result->result_array();
    }
    
    function Get($param)
    {
        $this->db->select("
            channel.id channel,
            DATE_ADD(from_unixtime(broadcast_send.schedule),INTERVAL config.timezone HOUR) schedule,
            DATE_ADD(from_unixtime(broadcast_send.send_timestamp),INTERVAL config.timezone HOUR) send_timestamp,
            case 
                when broadcast_send.status = 1 then 
                    case 
                        when DATE_ADD(from_unixtime(broadcast_send.schedule),INTERVAL config.timezone HOUR) < DATE_ADD(now(), INTERVAL config.timezone HOUR) then 
                        case 
                            when SEC_TO_TIME(timediff(from_unixtime(unix_timestamp()),from_unixtime(broadcast_send.schedule))) > 600 then 'Atrasado' else 'OK' 
                        end 
                        else 'OK' 
                    end
                when broadcast_send.status = 2 then 
                case 
                    when DATE_ADD(from_unixtime(broadcast_send.send_timestamp),INTERVAL config.timezone HOUR) > DATE_ADD(from_unixtime(broadcast_send.schedule), INTERVAL config.timezone HOUR) then 
                    case 
                        when TIME_TO_SEC(timediff(from_unixtime(broadcast_send.send_timestamp),from_unixtime(broadcast_send.schedule))) > 3600 then concat('Enviado com atraso de ',timediff(from_unixtime(broadcast_send.send_timestamp),from_unixtime(broadcast_send.schedule))) 
                        else 'OK' 
                    end
                end
                when broadcast_send.status = 4 then 'Broadcast cancelado'
            end status,
            broadcast_send.participantsList
        ");

        $this->db->from('channel');
        $this->db->join('config', 'channel.id_channel = config.id_channel');
        $this->db->join('broadcast_schedule', 'channel.id_channel = broadcast_schedule.id_channel');
        $this->db->join('broadcast_send', 'broadcast_schedule.id_broadcast_schedule = broadcast_send.id_broadcast_schedule');
        $this->db->where('DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_send.schedule),INTERVAL config.timezone HOUR),"%Y-%m-%d") = current_date()');
        $this->db->like('broadcast_send.participantsList', $this->db->escape_like_str($param['text']));

        if (!empty($param['channel'])) {
            $this->db->where_in('channel.id_channel', $param['channel']);
        }

        if (!empty($param['status'])) {
            $this->db->where_in('broadcast_send.status', $param['status']);
        } else {
            $this->db->where('broadcast_send.status', 1);
        }

        switch ($param['order'][0]['column']) {
            case 0:
                $this->db->order_by('channel', $param['order'][0]['dir']);
                break;
            case 1:
                $this->db->order_by('broadcast_send.schedule', $param['order'][0]['dir']);
                break;
            case 2:
                $this->db->order_by('send_timestamp', $param['order'][0]['dir']);
                break;
            default:
                $this->db->order_by('broadcast_send.schedule', $param['order'][0]['dir']);
        }

        $this->db->limit($param['length'], $param['start']);

        return $this->db->get('')->result_array();
    }

    function Count($param)
    {
        $this->db->select("count(*) count");
        $this->db->from('channel');
        $this->db->join('config', 'channel.id_channel = config.id_channel');
        $this->db->join('broadcast_schedule', 'channel.id_channel = broadcast_schedule.id_channel');
        $this->db->join('broadcast_send', 'broadcast_schedule.id_broadcast_schedule = broadcast_send.id_broadcast_schedule');
        $this->db->where('DATE_FORMAT(DATE_ADD(from_unixtime(broadcast_send.schedule),INTERVAL config.timezone HOUR),"%Y-%m-%d") = current_date()');
        $this->db->like('broadcast_send.participantsList', $this->db->escape_like_str($param['text']));

        if (!empty($param['channel'])) {
            $this->db->where_in('channel.id_channel', $param['channel']);
        }

        if (!empty($param['status'])) {
            $this->db->where_in('broadcast_send.status', $param['status']);
        } else {
            $this->db->where('broadcast_send.status', 1);
        }

        return $this->db->get('')->result_array();
    }
}
