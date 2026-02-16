<?php

class Oauth_clients_model extends TA_Model
{
    public function __construct()
    {
        parent::__construct();
        parent::SetTalkall();
    }


    function List()
    {
        $sql = "SELECT * FROM oauth_clients where user_id = $this->User_id \n";
        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Get($id)
    {
        $id = $this->db->escape($id);
        $sql = "SELECT * FROM oauth_clients where client_id = $id \n";
        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            return $result->result_array()[0];
        }
    }


    function Update($data)
    {
        $this->db->set('scope', implode(" ", $data['scope']));
        $this->db->set('name', $data['name']);
        $this->db->set('redirect_uri', $data['redirect_uri']);
        $this->db->set('webhooks', $data['webhooks']);
        $this->db->where('client_id', $data['client_id']);

        return $this->db->update('oauth_clients');
    }


    function Add($data)
    {
        $data['user_id'] = $this->User_id;
        $data['active'] = 1;
        $data['grant_types'] = 'authorization_code refresh_token';
        $data['scope'] = implode(" ", $data['scope']);

        $this->db->insert('oauth_clients', $data);
    }


    function Delete($id)
    {
        return $this->db->delete('oauth_clients', array('client_id' => $id));
    }
}
