<?php

class SMS_model extends TA_model
{
    public function __construct()
    {
        parent::__construct();

        parent::SetRemoteDatabase(
            "sms-talkall.brazilsouth.cloudapp.azure.com",
            "talkall_admin"
        );
    }

    function GetById($key_id)
    {
        $sql  = "select\n";
        $sql .= "DATE_FORMAT(from_unixtime(rh.creation), '%d/%m/%Y') creation,\n";
        $sql .= "rh.credit,\n";
        $sql .= "rh.id_recharge_history,\n";
        $sql .= "rh.id_company,\n";
        $sql .= "DATE_FORMAT(from_unixtime(rh.valid), '%d/%m/%Y') valid,\n";
        $sql .= "co.nome_fantasia\n";
        $sql .= "from talkall_admin.recharge_history as rh\n";
        $sql .= "inner join talkall_admin.company as co\n";
        $sql .= "on rh.id_company = co.id_company\n";
        $sql .= "where rh.id_recharge_history = {$key_id}\n";

        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array()[0];
        } else {
            $error = 'Nenhum registro encontrado para id informado.';
            throw new Exception($error);
        }
    }

    function Get($text, $start, $length)
    {
        $sql = "select\n";
        $sql .= "DATE_FORMAT(from_unixtime(recharge_history.creation),'%d/%m/%Y as %H:%i') creation,\n";
        $sql .= "company.nome_fantasia,\n";
        $sql .= "talkall_admin.recharge_history.id_recharge_history,\n";
        $sql .= "DATE_FORMAT(from_unixtime(recharge_history.valid),'%d/%m/%Y') valid,\n";
        $sql .= "recharge_history.credit\n";
        $sql .= "from company inner join recharge_history on company.id_company = recharge_history.id_company\n";
        $sql .= "and company.id_company\n";
        $sql .= "where company.nome_fantasia like '%" . $text . "%'\n";
        $sql .= "order by 1 desc limit {$start},{$length}\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }

    function Count($text)
    {
        $sql = "SELECT count(id_recharge_history) count FROM talkall_admin.recharge_history\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }

    function Edit($key_id, $data)
    {
        $expire = DateTime::createFromFormat('d/m/Y', $data['input-valid']);

        $values = [
            'id_company' => $data['input-id-company'],
            'credit' => $data['input-sms'],
            'valid'  => $expire->getTimestamp(),

        ];

        $this->db->where('id_recharge_history', $key_id);
        $this->db->update('recharge_history', $values);
    }

    function Delete($key_id)
    {

        $values = [
            'status' => $key_id['status'],
        ];

        $this->db->where('id_company', $key_id);
        $this->db->update('status', $values);
    }

    function Add($data)
    {
        $date = new DateTime();

        $values = [
            'creation' => $date->getTimestamp(),
            'id_company' => $data['input-id-company'],
            'valid' => date_timestamp_get(date_create($data['input-valid'])),
            'credit' => $data['input-sms'],
        ];

        $this->db->insert('recharge_history', $values);
    }

    function List()
    {
        $sql = "select\n";
        $sql .= "nome_fantasia,\n";
        $sql .= "id_company\n";
        $sql .= "from talkall_admin.company\n";
        $sql .= "order by talkall_admin.company.nome_fantasia";

        $result = $this->db->query($sql);

        $data = $result->result_array();

        return $data;
    }
}
