<?php

class Server_model extends TA_Model
{
    public function __construct()
    {
        parent::__construct();
        parent::SetTalkall();
    }

    function Count($text)
    {
        $sql = "select count(talkall_admin.server.id_server) count\n";
        $sql .= "from talkall_admin.server\n";
        $sql .= "where talkall_admin.server.name like '%" . $text . "%'\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }

    function Get($text, $start, $length)
    {
        $sql = "select\n";
        $sql .= "talkall_admin.server.creation,\n";
        $sql .= "talkall_admin.server.id_server,\n";
        $sql .= "talkall_admin.server.name\n";
        $sql .= "from talkall_admin.server\n";
        $sql .= "where ( talkall_admin.server.name like '%" . $text . "%' )\n";
        $sql .= "order by talkall_admin.server.name asc limit {$start},{$length}\n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            return $result->result_array();
        }

        return null;
    }

    function GetById($id_company)
    {
        $sql = "select\n";
        $sql .= "talkall_admin.server.creation,\n";
        $sql .= "talkall_admin.server.id_server,\n";
        $sql .= "talkall_admin.server.name\n";
        $sql .= "from talkall_admin.server\n";
        $sql .= "where talkall_admin.server.id_server = {$id_company} \n";

        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array()[0];
        } else {
            $error = 'Nenhum registro encontrado para id informado.';
            throw new Exception($error);
        }
    }

    function List()
    {
        $sql = "select\n";
        $sql .= "talkall_admin.server.id_server,\n";
        $sql .= "talkall_admin.server.name\n";
        $sql .= "from talkall_admin.server\n";
        $sql .= "order by talkall_admin.server.name\n";

        $result = $this->db->query($sql);

        $data = $result->result_array();

        return $data;
    }

    function Edit($id_server, $data)
    {
        $values = [
            'name'  => $data['input-server'],
        ];

        $this->db->where('id_server', $id_server);
        $this->db->update('server', $values);
    }

    function Add($data)
    {
        $date = new DateTime();
        $t = $date->getTimestamp();

        $values = [
            'creation' => $t,
            'name'  => $data['input-server'],
        ];

        $this->db->insert('server', $values);
    }

    function Delete($id_server)
    {

        $sql = "select * from server as srv \n";
        $sql .= "inner join channel as ch \n";
        $sql .= "on srv.id_server = ch.id_server \n";
        $sql .= "where srv.id_server = {$id_server} \n";

        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array()[0];
        } else {
            $this->db->where('id_server', $id_server);
            $this->db->delete('server');

            $result = ['type' => 'del_ok'];
            return $result;
        }
    }
}
