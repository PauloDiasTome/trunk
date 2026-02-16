<?php

class ReportChatbotQuantitative_model extends TA_model
{
    public function Get($param)
    {
        $this->db->select('
        chatbot_interaction.options,
        COUNT(*) as total,
        (COUNT(*) - SUM(
            CASE 
                WHEN chatbot_interaction.is_automatic_transfer = 1 
                     OR (chatbot_interaction.is_open = 1 AND chatbot_interaction.is_automatic_transfer = 2)
                THEN 1 ELSE 0 
            END
        )) as adhered,
        ROUND(
            (COUNT(*) - SUM(
                CASE 
                    WHEN chatbot_interaction.is_automatic_transfer = 1 
                         OR (chatbot_interaction.is_open = 1 AND chatbot_interaction.is_automatic_transfer = 2)
                    THEN 1 ELSE 0 
                END
            )) / COUNT(*) * 100, 2
        ) as adherence_rate
    ');

        $this->db->from('chatbot_interaction');
        $this->db->join('contact', 'contact.key_remote_id = chatbot_interaction.key_remote_id');
        $this->db->where('contact.deleted', 1);

        if (!empty($param['text'])) {
            $search = strtolower(trim($param['text']));

            if ($search === 'menu principal' || $search === 'main menu' || $search === 'principal') {
                $search = 'Main_menu';
            }

            $this->db->group_start();
            $this->db->like('contact.key_remote_id', $param['text']);
            $this->db->or_like('contact.full_name', $param['text']);
            $this->db->or_like('chatbot_interaction.options', $search);
            $this->db->group_end();
        }

        if (!empty($param['dt_start'])) {
            $this->db->where("DATE_FORMAT(FROM_UNIXTIME(chatbot_interaction.creation), '%Y-%m-%d') BETWEEN '{$param['dt_start']}' AND '{$param['dt_end']}'", NULL, FALSE);
        }

        $this->db->group_by('chatbot_interaction.options');

        $count = $this->db->count_all_results('', false);

        switch ($param['order'][0]['column']) {
            case 0:
                $this->db->order_by('chatbot_interaction.options', $param['order'][0]['dir']);
                break;
            case 1:
                $this->db->order_by('total', $param['order'][0]['dir']);
                break;
            case 2:
                $this->db->order_by('adhered', $param['order'][0]['dir']);
                break;
            case 3:
                $this->db->order_by('adherence_rate', $param['order'][0]['dir']);
                break;
        }

        $this->db->limit($param['length'], $param['start']);

        return [
            'query' => $this->db->get()->result_array(),
            'count' => $count
        ];
    }
}
