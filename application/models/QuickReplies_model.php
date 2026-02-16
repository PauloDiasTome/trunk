<?php

class QuickReplies_model extends TA_model
{

    function Get($post)
    {
        $text = $post['text'];
        $order_column = $post['order'][0]['column'];
        $order_dir = $post['order'][0]['dir'];
        $start = $post['start'];
        $length = $post['length'];

        $sql = "select\n";
        $sql .= "quick_replies.creation,\n";
        $sql .= "quick_replies.id_quick_replies,\n";
        $sql .= "quick_replies.title,\n";
        $sql .= "quick_replies.tag,\n";
        $sql .= "quick_replies.content\n";
        $sql .= "from quick_replies\n";
        $sql .= "where (lower(quick_replies.title) like lower('%{$text}%') or lower(quick_replies.tag) like lower('%{$text}%')) ";

        if ($this->session->is_admin == "f")
            $sql .= "and ( quick_replies.key_remote_id is null or quick_replies.key_remote_id = '{$this->session->key_remote_id}' )";

        switch ($order_column) {
            case 0:
                $sql .= "order by quick_replies.title {$order_dir}\n";
                break;
            case 1:
                $sql .= "order by quick_replies.tag {$order_dir}\n";
                break;

            default:
        }

        $sql .= "limit {$start},{$length}\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Count($post)
    {
        $text = $post['text'];

        $sql = "select count(quick_replies.id_quick_replies) count\n";
        $sql .= "from quick_replies\n";
        $sql .= "where (lower(quick_replies.title) like lower('%{$text}%') or lower(quick_replies.tag) like lower('%{$text}%')) ";

        if ($this->session->is_admin == "f")
            $sql .= "and ( quick_replies.key_remote_id is null or quick_replies.key_remote_id = '{$this->session->key_remote_id}' )";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Add($data)
    {
        $date = new DateTime();
        if ($data['type_reply'] == 'text') {
            $data['input-content'] =  str_replace("1&&&", "%", $data['input-content']);
        } else {
            $data['input-content'] = null;
        }

        $values = [
            'title' => trim($data['input-title']),
            'creation' => $date->getTimestamp(),
            'tag' => "/" . str_replace("/", "", trim($data['input-tag'])),
            'content' => trim($data['input-content']),
            'key_remote_id' => isset($data['private']) ? $this->session->key_remote_id : null,
            'media_type' => isset($data['media_type']) ? $data['media_type'] : null,
            'media_url' => isset($data['file']) ? $data['file'] : null,
            'media_size' => isset($data['byte']) ? $data['byte'] : null,
            'media_duration' => isset($data['duration']) ? $data['duration'] : null,
            'media_title' => isset($data['media_name']) ? $data['media_name'] : null
        ];

        $this->db->insert('quick_replies', $values);
    }


    function Edit($key_id, $data)
    {
        $context = ($data['type_reply'] == 'media') ? null : str_replace("1&&&", "%", $data['input-content']);

        $values = [
            'title' => trim($data['input-title']),
            'tag' => "/" . str_replace("/", "", trim($data['input-tag'])),
            'content' => $context,
            'key_remote_id' => isset($data['private']) ? $this->session->key_remote_id : null,
            'media_type' => isset($data['media_type']) ? $data['media_type'] : null,
            'media_url' => isset($data['file']) ? $data['file'] : null,
            'media_title' => (isset($data['media_name']) && $data['media_name'] !== 'undefined') ? $data['media_name'] : null,
            'media_size' => (isset($data['byte']) && $data['byte'] > 0) ? $data['byte'] : null,
            'media_duration' => (isset($data['duration']) && $data['duration'] > 0) ? $data['duration'] : null
        ];

        $this->db->where('id_quick_replies', $key_id);
        $this->db->update('quick_replies', $values);
    }


    function Delete($key_id)
    {
        $this->db->where('id_quick_replies', $key_id);
        $this->db->delete('quick_replies');
    }


    function List()
    {
        $sql = "select\n";
        $sql .= "quick_replies.creation,\n";
        $sql .= "quick_replies.id_quick_replies,\n";
        $sql .= "quick_replies.title,\n";
        $sql .= "quick_replies.tag,\n";
        $sql .= "quick_replies.content\n";
        $sql .= "from quick_replies\n";
        $sql .= "where quick_replies.key_remote_id is null or quick_replies.key_remote_id = '" . $this->session->key_remote_id . "' ";

        $result = $this->db->query($sql);

        $data = $result->result_array();

        return $data;
    }


    function GetInf($id)
    {
        $sql = "select *\n";
        $sql .= "from quick_replies\n";
        $sql .= "where quick_replies.id_quick_replies = '" . $id . "';\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }
}
