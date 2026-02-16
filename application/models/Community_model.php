<?php

class Community_model extends TA_model
{
    function Get($param)
    {
        $this->db->select("
    *,
    CASE
        WHEN participantsCount > 0 THEN CONCAT(ROUND(new_participant / NULLIF(participantsCount, 0) * 100, 2), '%')
        ELSE '0%'
    END AS porcent
", false);

$this->db->from("
    (
        SELECT
            community.id_community,
            community.name,
            COUNT(community_participant.id_community_participant) AS participantsCount,
            SUM(
                CASE
                    WHEN DATE_FORMAT(
                        DATE_ADD(FROM_UNIXTIME(community_participant.creation), INTERVAL config.timezone HOUR),
                        '%Y-%m-%d'
                    ) = DATE_FORMAT(
                        DATE_ADD(CONCAT(CURRENT_DATE(), ' ', CURRENT_TIME()), INTERVAL config.timezone HOUR),
                        '%Y-%m-%d'
                    )
                    THEN 1 ELSE 0
                END
            ) AS new_participant
        FROM community
        LEFT JOIN community_participant
            ON community.id_community = community_participant.id_community
        INNER JOIN channel
            ON community.id_channel = channel.id_channel
            AND channel.status IN (1, 3, 4, 5)
        LEFT JOIN config
            ON channel.id_channel = config.id_channel
        WHERE
            LOWER(community.name) LIKE '%'
            AND community.status = 1
        GROUP BY
            community.id_community,
            community.name
    ) AS query
", false);

        $count = $this->db->count_all_results('', false);

        switch ($param['order'][0]['column']) {
            case 0:
                $this->db->order_by('name', $param['order'][0]['dir']);
                break;
            case 1:
                $this->db->order_by('participantsCount', $param['order'][0]['dir']);
                break;
            case 2:
                $this->db->order_by('porcent', $param['order'][0]['dir']);
                break;
        }

        $this->db->limit($param['length'], $param['start']);

        return ['query' => $this->db->get()->result_array(), 'count' => $count];
    }

    function GetInfo($id_community)
    {
        $this->db->select('
        channel.name name_channel,
        community.name name_community,
        community.key_remote_id,
        community.description,
        community.id_community,
        (SELECT COUNT(*) FROM community_participant WHERE id_community = community.id_community) AS participantsCount,
        DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(community.creation), INTERVAL config.timezone HOUR), "%d/%m/%Y") creation,
        community.pictures
    ');
        $this->db->from('community');
        $this->db->join('channel', 'community.id_channel = channel.id_channel', 'inner');
        $this->db->join('config', 'channel.id_channel = config.id_channel', 'inner');
        $this->db->where('community.id_community', $id_community);
        $this->db->where('community.status', 1);

        $result = $this->db->get()->result_array();
        return count($result) > 0 ? $result[0] : [];
    }

    function GetBroadcastSchedule($id_community)
    {
        $this->db->select('broadcast_schedule.id_broadcast_schedule');
        $this->db->from('broadcast_schedule');
        $this->db->join('community', 'broadcast_schedule.groups = community.key_remote_id', 'left');
        $this->db->where('broadcast_schedule.is_wa_community', 1);
        $this->db->where_in('broadcast_schedule.status', [1, 3, 6]);
        $this->db->where_in('community.id_community', $id_community);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $ids = array_column($query->result_array(), 'id_broadcast_schedule');
            return $ids;
        }
    }

    function Participant($id_community)
    {
        $this->db->select('*');
        $this->db->from('community_participant');
        $this->db->where('community_participant.id_community', $id_community);

        return $this->db->get()->result_array();
    }

    function Edit($key_id, $data)
    {
        $values = [
            'name' => trim($data['input-name']),
        ];

        $this->db->where('id_community', $key_id);
        $this->db->update('community', $values);
    }

    function Delete($key_id)
    {
        $id_broadcast = $this->GetBroadcastSchedule($key_id);

        $this->db->trans_start();

        $this->db->set("status", 2);
        $this->db->where('id_community', $key_id);
        $this->db->update('community');

        if (!empty($id_broadcast)) {
            $this->db->set("status", 5);
            $this->db->where_in("id_broadcast_schedule", $id_broadcast);
            $this->db->update("broadcast_schedule");

            $this->db->set("status", 2);
            $this->db->where_in("id_broadcast_schedule", $id_broadcast);
            $this->db->update("broadcast_send");
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ["errors" => ["code" => "PAD-002"]];
        } else {
            $this->db->trans_complete();
            return ["success" => ["status" => true]];
        }
    }
}
