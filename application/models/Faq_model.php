<?php

class Faq_model extends TA_model
{

    function Get($text, $start, $length, $order_column, $order_dir)
    {
        $sql = "select\n";
        $sql .= "faq.id_faq,\n";
        $sql .= "faq.title,\n";
        $sql .= "user.last_name\n";
        $sql .= "from faq inner join user on faq.key_remote_id = user.key_remote_id\n";
        $sql .= "inner join faq_tag on faq.id_faq = faq_tag.id_faq\n";

        $tags = explode(" ", $text);

        for ($i = 0; $i < count($tags); $i++) {
            if ($i == 0) {
                $sql .= "where ";
            } else {
                $sql .= "or ";
            }
            $sql .= "( faq_tag.tag like '%" . ReplaceSimbols($tags[$i]) . "%' )\n";
        }

        $sql .= "group by faq.id_faq,\n";
        $sql .= "faq.title,\n";
        $sql .= "user.last_name\n";
        // $sql .= "having count(faq_tag.id_faq_tag) > 1\n";

        switch ($order_column) {
            case 0:
                $sql .= "order by faq.title $order_dir \n";
                break;
            case 1:
                $sql .= "order by user.last_name $order_dir \n";
                break;
        }

        $sql .= "limit {$start},{$length}\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Count($text)
    {
        $sql = "select\n";
        $sql .= "count(distinct faq.id_faq) count\n";
        $sql .= "from faq inner join user on faq.key_remote_id = user.key_remote_id\n";
        $sql .= "inner join faq_tag on faq.id_faq = faq_tag.id_faq\n";

        $tags = explode(" ", $text);

        for ($i = 0; $i < count($tags); $i++) {
            if ($i == 0) {
                $sql .= "where ";
            } else {
                $sql .= "or ";
            }
            $sql .= "( faq_tag.tag like '%" . ReplaceSimbols($tags[$i]) . "%' )\n";
        }

        // $sql .= "group by faq.id_faq,\n";
        // $sql .= "faq.title,\n";
        // $sql .= "user.last_name\n";
        // $sql .= "having count(faq_tag.id_faq_tag) > 1\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function GetInf($id)
    {
        $sql = "select *\n";
        $sql .= "from faq\n";
        $sql .= "where faq.id_faq = " . $id . ";\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Edit($key_id, $data)
    {
        $values = [
            'title' => trim($data['input-title']),
            'content' => trim($data['input-content']),
            'key_remote_id' => $this->session->key_remote_id
        ];

        $this->db->where('id_faq', $key_id);
        $this->db->delete('faq_tag');

        $tags = explode(" ", $data['input-title']);

        for ($i = 0; $i < count($tags); $i++) {

            if (strlen($tags[$i]) >= 4) {

                $values_tag = [
                    'id_faq' => $key_id,
                    'tag' => ReplaceSimbols($tags[$i]),
                ];

                $this->db->insert('faq_tag', $values_tag);
            }
        }

        $this->db->where('id_faq', $key_id);
        $this->db->update('faq', $values);
    }


    function Add($data)
    {
        $date = new DateTime();

        $values = [
            'creation' => $date->getTimestamp(),
            'key_remote_id' => $this->session->key_remote_id,
            'title' => trim($data['input-title']),
            'content' => trim($data['input-content']),
            'status' => 1,
        ];

        $this->db->insert('faq', $values);

        $tags = explode(" ", $data['input-title']);

        $faq_id = $this->db->insert_id();

        for ($i = 0; $i < count($tags); $i++) {

            if (strlen($tags[$i]) >= 4) {

                $values = [
                    'id_faq' => $faq_id,
                    'tag' => ReplaceSimbols($tags[$i]),
                ];

                $this->db->insert('faq_tag', $values);
            }
        }
    }


    function Delete($key_id)
    {
        $this->db->where('id_faq', $key_id);
        $this->db->delete('faq_tag');

        $this->db->where('id_faq', $key_id);
        $this->db->delete('faq');
    }
}
