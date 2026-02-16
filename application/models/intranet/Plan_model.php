<?php

class Plan_model extends TA_Model
{
    public function __construct()
    {
        parent::__construct();
        parent::SetTalkall();
    }

    function Count($text)
    {
        $sql = "select count(talkall_admin.company_plan.id_company_plan) count \n";
        $sql .= "from talkall_admin.company_plan \n";
        $sql .= "where talkall_admin.company_plan.name like '%" . $text . "%'\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }

    function Get($text, $start, $length)
    {
        $sql = "select \n";
        $sql .= "talkall_admin.company_plan.name, \n";
        $sql .= "talkall_admin.company_plan.cost, \n";
        $sql .= "talkall_admin.company_plan.status, \n";
        $sql .= "talkall_admin.company_plan.id_company_plan \n";
        $sql .= "from talkall_admin.company_plan \n";
        $sql .= "where ( talkall_admin.company_plan.name like '%" . $text . "%' ) \n";
        $sql .= "order by talkall_admin.company_plan.name asc limit {$start},{$length} \n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            return $result->result_array();
        }

        return null;
    }

    function GetById($id_plan)
    {
        $sql = "select\n";
        $sql .= "talkall_admin.company_plan.name, \n";
        $sql .= "talkall_admin.company_plan.cost, \n";
        $sql .= "talkall_admin.company_plan.status, \n";
        $sql .= "talkall_admin.company_plan.contacts, \n";
        $sql .= "talkall_admin.company_plan.messages, \n";
        $sql .= "talkall_admin.company_plan.id_company_plan \n";
        $sql .= "from talkall_admin.company_plan\n";
        $sql .= "where talkall_admin.company_plan.id_company_plan = {$id_plan} \n";

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
        $sql = "select * \n";
        $sql .= "from talkall_admin.company_plan\n";
        $sql .= "order by talkall_admin.company_plan.name\n";

        $result = $this->db->query($sql);

        $data = $result->result_array();

        return $data;
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

    function Edit($id_plan, $data)
    {


        // echo str_replace("R$ ","",str_replace(",",".",str_replace(".","",$data['input-cost'])));

        $values = [
            'name' => $data['input-name'],
            'cost'  => str_replace("R$ ", "", str_replace(",", ".", str_replace(".", "", $data['input-cost']))),
            'contacts' => $data['input-contacts'],
            'messages' => $data['input-messages'],
            'status' => $data['select-status'],
        ];

        $this->db->where('id_company_plan', $id_plan);
        $this->db->update('company_plan', $values);
    }

    function Add($data)
    {

        $date = new DateTime();

        $values = [
            'name' => $data['input-name'],
            'cost'  => str_replace("R$ ", "", str_replace(",", ".", str_replace(".", "", $data['input-cost']))),
            'contacts' => $data['input-contacts'],
            'messages' => $data['input-messages'],
            'status' => 1,
            'id_plan' => 0,
            'creation' => $date->getTimestamp(),
        ];

        $this->db->insert('company_plan', $values);
    }
}
