<?php

class CommunityParticipant_model extends TA_model
{
    function Get($param)
    {
        $this->db->select("
            community_participant.id_community_participant,
            community_participant.key_remote_id,
            'https/files.talkall.com.br:3000' as profile,
            community.name,
            DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(community_participant.creation), INTERVAL config.timezone HOUR), '%d/%m/%Y - %H:%i') as accession_date,
            TIMESTAMPDIFF(DAY,
                DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(community_participant.creation), INTERVAL config.timezone HOUR), '%Y-%m-%d'),
                DATE_FORMAT(DATE_ADD(CONCAT(CURRENT_DATE(), ' ', CURRENT_TIME()), INTERVAL config.timezone HOUR), '%Y-%m-%d')) as base_time
        ");
        $this->db->from('community_participant');
        $this->db->join('community', 'community_participant.id_community = community.id_community', 'inner');
        $this->db->join('channel', 'community.id_channel = channel.id_channel', 'inner');
        $this->db->join('config', 'channel.id_channel = config.id_channel', 'inner');
        $this->db->where_in('channel.status', array(1,3,4,5));
        $this->db->where('community.status', 1);
        $this->db->like("CONCAT(LOWER(community.name), ' ', community_participant.key_remote_id)", $this->db->escape_like_str(strtolower($param['text'])));

        if (!empty($param['community'])) {
            $this->db->where('community.id_community', $param['community']);
        }

        if (trim($param['dt_start']) != "") {
            $this->db->where("DATE_FORMAT(DATE_ADD(from_unixtime(community_participant.creation), INTERVAL config.timezone HOUR),'%Y-%m-%d') BETWEEN '{$param['dt_start']}' AND '{$param['dt_end']}'", null, false);
        }

        $count = $this->db->count_all_results('', false);

        switch ($param['order'][0]['column']) {
            case 0:
                $this->db->order_by('community_participant.key_remote_id', $param['order'][0]['dir']);
                break;
            case 1:
                $this->db->order_by('community_participant.key_remote_id', $param['order'][0]['dir']);
                break;
            case 2:
                $this->db->order_by('community.name', $param['order'][0]['dir']);
                break;
            case 3:
                $this->db->order_by('base_time', $param['order'][0]['dir']);
                break;
        }

        $this->db->limit($param['length'], $param['start']);

        return ['query' => $this->db->get()->result_array(), 'count' => $count];
    }

    function ListChannel()
    {
        $this->db->select('channel.name, channel.id_channel');
        $this->db->from('channel');
        $this->db->where_in('channel.type', array(2, 12, 16));
        $this->db->where('channel.status', 1);

        return $this->db->get()->result_array();
    }

    function Participant($id_community)
    {
        $this->db->select('community.*, COUNT(community_participant.id_community_participant) AS participantsCount');
        $this->db->from('community');
        $this->db->join('community_participant', 'community_participant.id_community = community.id_community', 'left');
        $this->db->join('channel', 'community.id_channel = channel.id_channel', 'inner');
        $this->db->where_in('channel.status', array(1,3,4,5));
        $this->db->where('community.status', 1);
        $this->db->where('community.name !=', '');

        if (!empty($id_community)) {
            $this->db->where('community.id_community', $id_community);
        }

        $this->db->group_by('community.id_community');

        return $this->db->get()->result_array();
    }

    function TotalParticipant()
    {
        $this->db->select('COUNT(community_participant.id_community_participant) AS total');
        $this->db->from('community');
        $this->db->join('community_participant', 'community_participant.id_community = community.id_community', 'inner');
        $this->db->join('channel', 'community.id_channel = channel.id_channel', 'inner');
        $this->db->where_in('channel.status', array(1,3,4,5));
        $this->db->where('community.name IS NOT NULL');
        $this->db->where('community.name !=', '');
        $this->db->where('community.status', 1);

        $result = $this->db->get()->row_array();
        return $result['total'];
    }
}
