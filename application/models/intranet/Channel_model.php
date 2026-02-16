<?php

class Channel_model extends TA_model
{
    public function __construct()
    {
        parent::__construct();
        parent::SetTalkall();
    }

    function Get($fields)
    {
        $text = $fields['text'];
        $start = $fields['start'];
        $length = $fields['length'];
        $order = $fields['order'][0];
        $columns = $fields['columns'];
        $orderCreation = "";

        $sql = "select\n";
        $sql .= "from_unixtime(channel.creation,'%d/%m/%Y') as creation,\n";
        $sql .= "channel.id_channel,\n";
        $sql .= "company.corporate_name,\n";
        $sql .= "channel.id,\n";
        $sql .= "server.name server,\n";
        $sql .= "channel.status,\n";
        $sql .= "from_unixtime(channel.t,'%d/%m/%Y %H:%i:%s') last_seen\n";
        $sql .= "from company inner join channel on company.id_company = channel.id_company\n";
        $sql .= "left join server on channel.id_server = server.id_server\n";
        $sql .= "left join (select 1 as id, 'ativo' as description) as active on(active.id = channel.status)\n";
        $sql .= "left join (select 2 as id, 'inativo' as description) as inactive on(inactive.id = channel.status)\n";
        $sql .= "where channel.type = 2 and company.status = 1 and channel.status = 1\n";
        $sql .= "and (company.corporate_name like '%$text%' or channel.id like '%$text%' or server.name like '%$text%' or active.description = LOWER('$text') or inactive.description = LOWER('$text'))\n";
        
        if($columns[$order['column']]['data'] == 'creation')
            $orderCreation = "channel.";

        $sql .= "order by {$orderCreation}{$columns[$order['column']]['data']} {$order['dir']}\n";
        $sql .= "limit {$start},{$length}\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }

    function GetById($id_company)
    {
        $sql = "select\n";
        $sql .= "talkall_admin.channel.id_channel,\n";
        $sql .= "from_unixtime(talkall_admin.channel.creation,'%d/%m/%Y') as creation,\n";
        $sql .= "talkall_admin.channel.id,\n";
        $sql .= "talkall_admin.channel.type,\n";
        $sql .= "talkall_admin.channel.id_company,\n";
        $sql .= "talkall_admin.channel.id_server,\n";
        $sql .= "talkall_admin.channel.status,\n";
        $sql .= "from_unixtime(talkall_admin.channel.t,'%d/%m/%Y %H:%i:%s') last_seen\n";
        $sql .= "from talkall_admin.channel\n";
        $sql .= "where talkall_admin.channel.id_channel = {$id_company}\n";

        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array()[0];
        } else {
            $error = 'Nenhum registro encontrado para id informado.';
            throw new Exception($error);
        }
    }

    function Count($fields)
    {
        $text = $fields['text'];

        $sql = "select count(channel.id_channel) count\n";
        $sql .= "from company inner join channel on company.id_company = channel.id_company\n";
        $sql .= "left join server on channel.id_server = server.id_server\n";
        $sql .= "left join (select 1 as id, 'ativo' as description) as active on(active.id = channel.status)\n";
        $sql .= "left join (select 2 as id, 'inativo' as description) as inactive on(inactive.id = channel.status)\n";
        $sql .= "where channel.type = 2 and company.status = 1\n";
        $sql .= "and (company.corporate_name like '%$text%' or channel.id like '%$text%' or server.name like '%$text%' or active.description = LOWER('$text') or inactive.description = LOWER('$text'))\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }

    function Update($id, $data){

        $values = [
            'id_company' => $data['input-corporate'],
            'id_server'  => $data['input-server'],
        ];

        $this->db->where('id_channel', $id);
        $this->db->update('channel', $values);
    }

    function Add($data)
    {       
        $date = new DateTime();

        $values = [
            'creation' => $date->getTimestamp(),
            'id_company' => $data['input-corporate'],
            'id_server'  => $data['input-server'],
            'id' => $data['input-id'],
            'type' => 2,
            'status' => 1,
            'executed' => 2,
        ];

        $this->db->insert('channel', $values);

        $sql = "select\n";
        $sql .= "company.db\n";
        $sql .= "from company\n";
        $sql .= "where company.id_company = 1\n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {

            $this->db = $this->load->database(Setdatabase($result->result_array()[0]['db']), TRUE);

            $values = [
                'name' => 'WhatsApp',
                'type' => 2,
                'id' => $data['input-id']
            ];

            $this->db->insert('channel',$values);

            $channel_id = $this->db->insert_id();

            $values = [
                'id_channel' => $channel_id,
                'timezone' => '-00:00',
                'attendance_enable' => 1
            ];

            $this->db->insert('config',$values);
        }
    }
}
