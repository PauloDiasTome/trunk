<?php

class Label_model extends TA_model
{

    public function __construct()
    {
        parent::__construct();
        $this->SetTableName("label");
        $this->SetPrimaryKey("id_label");
    }

    function Get($param)
    {
        $this->db->select('
            label.id_label,
            label.name as tag, 
            label.color
        ');

        $this->db->from('label');
        $this->db->like('label.name', $param['text']);

        $count = $this->db->count_all_results('', false);

        switch ($param['order'][0]['column']) {
            case 0:
                $this->db->order_by('label.name', $param['order'][0]['dir']);
                break;
        }

        $this->db->limit($param['length'], $param['start']);

        return ['query' => $this->db->get()->result_array(), 'count' => $count];
    }

    function Add($data)
    {
        $values = [
            'name' => trim($data['input-name']),
            'color' => $data['input-color'],
        ];

        $this->db->insert('label', $values);
    }

    function Edit($key_id, $data)
    {
        $values = [
            'name' => trim($data['input-name']),
            'color' => $data['input-color'],
        ];
        $this->db->where('id_label', $key_id);
        $this->db->update('label', $values);
    }

    function GetById($key_id)
    {
        return parent::_GetById($key_id);
    }

    function Delete($param)
    {
        if(empty($param['id'])){
            return ['errors' => ['code' => ['TA-029']] , 'message' => 'Label not found!'];
        }

        $key_id = $param['id'];

        if(isset($param['delete']) && $param['delete'] == 1){
            $this->db->trans_start();
            
            $this->db->where('id_label', $key_id);
            $this->db->delete('chat_list_label');
    
            $this->db->where('id_label', $key_id);
            $this->db->delete('label');
    
    
            if ($this->db->trans_status() === FALSE) {
                return ["errors" => ["code" => "PAD-002"]];
            } else {
                $this->db->trans_complete();
                return ["success" => ["status" => true]];
            }

        }else {
            $this->db->select('chat_list_label.id_chat_list_label, chat_list.id_contact');
            $this->db->from('chat_list_label');
            $this->db->join('chat_list', 'chat_list_label.id_chat_list = chat_list.id_chat_list');
            $this->db->join('contact', 'chat_list.id_contact = contact.id_contact');
            $this->db->where("id_label = '$key_id' AND contact.deleted = 1");

            $query = $this->db->get();

            if ($query->num_rows() < 1) {
                $this->db->where('id_label', $key_id);
                $this->db->delete('label');
            }
            return $query->result_array();
        }
    }
}
