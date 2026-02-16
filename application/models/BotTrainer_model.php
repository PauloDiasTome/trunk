<?php

class BotTrainer_model extends TA_model
{
    function Get($param)
    {
        $this->db->select('
            chatbot.id_chatbot,
            chatbot.option,
            chatbot.description,
            chatbot.is_primary
        ');
        $this->db->from('chatbot');

        if (intval($param['text']) == true)
            $this->db->like('LOWER(chatbot.option)', $this->db->escape_like_str(strtolower($param['text'])));
        else
            $this->db->like('LOWER(chatbot.description)', $this->db->escape_like_str(strtolower($param['text'])));

        $this->db->where('chatbot.id_submenu', NULL);

        $count = $this->db->count_all_results('', false);

        switch ($param['order'][0]['column']) {
            case 0:
                $this->db->order_by("CAST(chatbot.option AS UNSIGNED)", $param['order'][0]['dir']);
                break;
            case 1:
                $this->db->order_by("chatbot.description", $param['order'][0]['dir']);
                break;
        }

        $this->db->limit($param['length'], $param['start']);

        return ['query' => $this->db->get()->result_array(), 'count' => $count];
    }

    function GetInf($key_id)
    {
        $this->db->select('
            chatbot.id_chatbot,
            chatbot.option,
            chatbot.description,
            chatbot.id_submenu,
            chatbot.is_primary,
            chatbot.id_user_group,
            chatbot.text,
            chatbot.media_type,
            chatbot.media_url,
            chatbot.media_caption,
            chatbot.vcard
        ');
        $this->db->from('chatbot');
        $this->db->where('chatbot.id_chatbot', $key_id);

        return $this->db->get()->result_array();
    }

    function GetUserGroupInf()
    {
        $this->db->select('*');
        $this->db->from('user_group');
        $this->db->where('user_group.id_user_group >', 0);
        $this->db->where('user_group.status', 1);

        return $this->db->get()->result_array();
    }

    function GetAccess($key_id, $param)
    {
        $this->db->select('
            chatbot.id_chatbot,
            chatbot.option,
            chatbot.description,
            chatbot.is_primary
        ');
        $this->db->from('chatbot');

        if (intval($param['text']) == true)
            $this->db->like('LOWER(chatbot.option)', $this->db->escape_like_str(strtolower($param['text'])));
        else
            $this->db->like('LOWER(chatbot.description)', $this->db->escape_like_str(strtolower($param['text'])));

        if (intval($key_id) > 0)
            $this->db->where('chatbot.id_submenu', $key_id);

        $count = $this->db->count_all_results('', false);

        switch ($param['order'][0]['column']) {
            case 0:
                $this->db->order_by("CAST(chatbot.option AS UNSIGNED)", $param['order'][0]['dir']);
                break;
            case 1:
                $this->db->order_by("chatbot.description", $param['order'][0]['dir']);
                break;
        }

        $this->db->limit($param['length'], $param['start']);

        return ['query' => $this->db->get()->result_array(), 'count' => $count];
    }

    function Add($data, $id_menu = 0)
    {
        $values = [
            'option'        => trim($data['input_option']),
            'description'   => trim($data['input_content']),
            'text'          => null,
            'media_type'    => isset($data['media_type']) ? trim($data['media_type']) : null,
            'media_caption' => null,
            'media_url'     => null,
            'vcard'         => null,
        ];

        if (isset($data["chatbot_type"])) {
            switch ($data["chatbot_type"]) {
                case "text":
                    $values['text'] = trim($data['input_text'] ?? null);
                    break;
                case "media":
                    $values['media_caption'] = isset($data['media_description']) && trim($data['media_description']) != "" ? trim($data['media_description']) : null;
                    $values['media_url'] = isset($data['file_hidden']) ? trim($data['file_hidden']) : null;

                    if (isset($data['file_name']) && !empty($data['file_name'])) {
                        $values['media_name'] = $data['file_name'];
                    }

                    break;
                case "contact":
                    $vcard = [
                        'display_name' => trim($data['input_name'] ?? ""),
                        'number'       => trim($data['input_phone'] ?? ""),
                    ];
                    $values['text'] = trim($data['input_text_contact'] ?? null);
                    $values['vcard'] = json_encode($vcard);
                    break;
            }
        }

        if (isset($data['id_submenu']) && intval($data['id_submenu']) > 0) {
            $id_menu = intval($data['id_submenu']);
        }

        if (isset($data['sector_toggle']) && $data['sector_toggle'] == "on") {
            $values['id_user_group'] = trim($data['selected_sector']);
            $values['is_menu'] = 1;
            $values['is_primary'] = 2;
        } else {
            $values['is_menu'] = 2;
            $values['is_primary'] = 2;
        }

        if ($id_menu > 0) {
            $values['id_submenu'] = $id_menu;
            $values['is_primary'] = 1;
        }

        $this->db->insert('chatbot', $values);
    }

    function Edit($key_id, $data)
    {
        $values = [
            'option'        => trim($data['input_option']),
            'description'   => trim($data['input_content']),
            'text'          => null,
            'media_type'    => isset($data['media_type']) ? trim($data['media_type']) : null,
            'media_caption' => null,
            'media_url'     => null,
            'vcard'         => null,
        ];

        if (isset($data["chatbot_type"])) {
            switch ($data["chatbot_type"]) {
                case "text":
                    $values['text'] = trim($data['input_text'] ?? null);
                    break;
                case "media":
                    $values['media_caption'] = isset($data['media_description']) && trim($data['media_description']) != "" ? trim($data['media_description']) : null;
                    $values['media_url'] = isset($data['file_hidden']) ? trim($data['file_hidden']) : null;

                    if (isset($data['file_name']) && !empty($data['file_name'])) {
                        $values['media_name'] = $data['file_name'];
                    }

                    break;
                case "contact":
                    $values['text'] = trim($data['input_text_contact'] ?? null);
                    $vcard = [
                        'display_name' => trim($data['input_name'] ?? ""),
                        'number'       => trim($data['input_phone'] ?? ""),
                    ];
                    $values['vcard'] = json_encode($vcard);
                    break;
            }
        }

        if (isset($data['sector_toggle']) && $data['sector_toggle'] == "on") {
            $values['id_user_group'] = intval($data['selected_sector']);
            $values['is_menu'] = 1;
        } else {
            $values['id_user_group'] = null;
            $values['is_menu'] = 2;
        }

        $this->db->where('id_chatbot', $key_id);
        $this->db->update('chatbot', $values);
    }

    function Delete($key_id)
    {
        $this->db->where('id_chatbot', $key_id);
        $this->db->delete('chatbot');

        // Pega id_chatbot nível 2 caso exista
        $this->db->select('chatbot.id_chatbot');
        $this->db->from('chatbot');
        $this->db->where('chatbot.id_submenu', $key_id);

        $query = $this->db->get();

        $this->db->where('id_submenu', $key_id);
        $this->db->delete('chatbot');

        // Deleta nivel 3 do chatbot
        if ($query->num_rows() > 0) {
            $this->db->where('id_submenu', $query->result_array()[0]['id_chatbot']);
            $this->db->delete('chatbot');
        }

        $this->db->select('chatbot.id_chatbot');
        $this->db->from('chatbot');

        $result = $this->db->get();

        if ($result->num_rows() == 0) {
            $this->db->where('chatbot_enable', '1');
            $this->db->update('config', array("chatbot_enable" => 2));
        }
    }
}
