<?php

class Tv_model extends TA_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    function Connect($data)
    {
        $this->talkall_admin = $this->load->database(SetdatabaseRemote('talkall_admin', '192.168.190.40'), TRUE);

        $this->talkall_admin->select('channel.id, channel.status, company.db, company.server');
        $this->talkall_admin->from('channel');
        $this->talkall_admin->join('company', 'channel.id_company = company.id_company');
        $this->talkall_admin->where('channel.status', 1);
        $this->talkall_admin->where('channel.type', 28);
        $this->talkall_admin->where('channel.tv_connection_code', $data['connection_code']);

        $result  = $this->talkall_admin->get()->result_array();

        if (!empty($result)) {

            //? cria o token para id na primeira conexão //
            if (strlen($result[0]['id']) <= 3) {

                $token  = 'tv-' . token();

                try {
                    $this->talkall_admin->where('channel.status', 1);
                    $this->talkall_admin->where('channel.type', 28);
                    $this->talkall_admin->where('tv_connection_code', $data['connection_code']);
                    $this->talkall_admin->update('channel', array('id' => $token));

                    $this->db = $this->load->database(SetdatabaseRemote($result[0]['db'],  $result[0]['server']), TRUE);
                    $this->db->where('channel.status', 1);
                    $this->db->where('channel.type', 28);
                    $this->db->where('tv_connection_code', $data['connection_code']);
                    $this->db->update('channel', array('id' => $token));
                } catch (Exception $e) {

                    return ["error" => [
                        "code" => "PAD-002",
                        "title" => "Transaction failure",
                        "message" => $e->getMessage(),
                    ]];
                }
            } else
                $token = $result[0]['id'];

            return ['success' => [
                'status' => true,
                'tv_token' => $token
            ]];
        } else {
            return ["error" => [
                "code" => "TA-006",
                "title" => "Incorrect Code",
                "message" => "The provided code is incorrect"
            ]];
        }
    }
}
