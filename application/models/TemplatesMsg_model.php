<?php

class TemplatesMsg_model extends TA_model
{

    function GetTemplate($text, $start, $length, $order_column, $order_dir)
    {
        $sql = "select template.*, \n";
        $sql .= "channel.name as channel_name\n";
        $sql .= "FROM template \n";
        $sql .= "LEFT JOIN channel on channel.id = template.account_key_remote_id \n";
        $sql .= "WHERE template.status <> 4 \n";
        $sql .= "and LOWER(CONCAT(template.name, ' ', channel.name)) LIKE LOWER('%{$text}%') \n";
        $sql .= "and channel.status = 1 \n";

        switch ($order_column) {
            case 0:
                $sql .= "ORDER BY template.creation $order_dir \n";
                break;
            case 1:
                $sql .= "ORDER BY template.name $order_dir \n";
                break;
            case 2:
                $sql .= "ORDER BY channel_name $order_dir \n";
                break;
            case 3:
                $sql .= "ORDER BY template.category $order_dir \n";
                break;
            case 4:
                $sql .= "ORDER BY template.status $order_dir \n";
                break;
        }

        $sql .= "limit {$start},{$length}\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Count($text)
    {
        $sql = "select count(id_template) count\n";
        $sql .= "FROM template \n";
        $sql .= "LEFT JOIN channel on channel.id = template.account_key_remote_id \n";
        $sql .= "WHERE template.status <> 4 \n";
        $sql .= "and LOWER(CONCAT(template.name, ' ', channel.name)) LIKE LOWER('%{$text}%') \n";
        $sql .= "and channel.status = 1 \n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function GetInfoTemplate($key)
    {
        $sql = "SELECT * FROM template where id_template = '{$key}' ";
        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function GetToken($type, $channel_id)
    {
        $sql = "SELECT whatsapp_business_messaging, pw FROM channel where type = {$type} and id = '{$channel_id}' and pw <> '' and status = 1";
        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function ChangeStatus($data, $id)
    {

        $this->db->where('id_template', $id);
        $ret = $this->db->update('template', ['status' => $data['status']]);

        return $ret;
    }


    function GetInf($id)
    {
        $sql = "select template.*, \n";
        $sql .= "channel.name as channel_name, \n";
        $sql .= "channel.type as channel_type \n";
        $sql .= "from template \n";
        $sql .= "left join channel on channel.id = template.account_key_remote_id \n";
        $sql .= "where (id_template = " . $id . ")";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function List($id)
    {
        $sql = "SELECT * FROM template where status = {$id}";

        $result = $this->db->query($sql);
        return $result->result_array();
    }


    function GetData()
    {
        $sql = "select\n";
        $sql .= "id_template_type,\n";
        $sql .= "pt_BR,\n";
        $sql .= "category\n";
        $sql .= "from template_type\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }

    function GetDataCloud()
    {
        $sql = "select\n";
        $sql .= "id_template_type,\n";
        $sql .= "pt_BR,\n";
        $sql .= "category,\n";
        $sql .= "type\n";
        $sql .= "from template_type\n";
        $sql .= "where type = 16\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function GetCompareWaba()
    {
        $sql = "select * \n";
        $sql .= "from template \n";
        $sql .= "where status <> 4 \n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Add($data)
    {
        $date = new DateTime();

        $values = [
            'creation' => $date->getTimestamp(),
            'name' => $data['name'],
            'name_to_request' => $data['name_to_request'],
            'text_body' => $data['text_body'],
            'header' => $data['header'],
            'header_type' => $data['header_type'],
            'text_footer' => $data['text_footer'],
            'language' => $data['language'],
            'category' => $data['category'],
            'namespace' => $data['namespace'],
            'rejected_reason' => $data['rejected_reason'],
            'status' => $data['status'],
            'buttons' => $data['buttons'],
            'account_key_remote_id' => $data['account_key_remote_id'],
            'template_id' => $data['template_id'],
            'template_json' => $data['template_json']
        ];

        $ret = $this->db->insert('template', $values);

        if ($ret == true) {
            return true;
        }

        return false;
    }


    function updateName($data)
    {
        $this->db->where('id_template', $data['id_template']);
        $this->db->update('template', ['name' => $data['name']]);

        return true;
    }


    function UpdateStatus($key_id, $status)
    {
        $this->db->where('name_to_request', $key_id);
        $this->db->update('template', ['status' => $status]);

        return true;
    }

    function getChannels()
    {
        $sql = "SELECT 
                    channel.id_channel, 
                    channel.name, 
                    channel.id,
                    channel.type

                FROM
                    channel
                WHERE
                    (type = 12 OR type = 16) AND status = 1;";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function getChannelIdByType($channel_type)
    {
        $sql = "SELECT 
                    channel.id
                FROM
                    channel
                WHERE
                    type = {$channel_type} and status = 1";

        $result = $this->db->query($sql);

        return $result->result_array()[0]['id'];
    }


    function getTemplateTypes()
    {

        $sql = "SELECT 
                    id_template_type,
		            category as en_us,
                    pt_BR as pt_br
                FROM template_type";

        $result = $this->db->query($sql);

        return $result->result_array();
    }
}
