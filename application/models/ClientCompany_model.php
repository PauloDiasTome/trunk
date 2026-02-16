<?php

class ClientCompany_model extends TA_model
{

    function Get($text, $start, $length, $order_column, $order_dir)
    {
        $sql = "SELECT 
                    company.id_company,
                    company.fantasy_name,
                    company.corporate_name,
                    company.cnpj
                FROM
                    company
                WHERE company.fantasy_name LIKE '%" . $text . "%'\n";

        switch ($order_column) {
            case 0:
                $sql .= "ORDER BY company.fantasy_name $order_dir\n";
                break;
            case 1:
                $sql .= "ORDER BY company.cnpj $order_dir\n";
                break;
        }

        $sql .= "LIMIT {$start},{$length}\n";

        $result = $this->db->query($sql);

        $data = $result->result_array();

        return $data;
    }


    function Count($text)
    {
        $sql = "SELECT 
                  COUNT(company.id_company) count
                FROM
                    company
                WHERE company.fantasy_name LIKE '%" . $text . "%'\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function GetInf($id)
    {
        $sql = "SELECT 
                    company.id_company,
                    DATE_FORMAT(FROM_UNIXTIME(company.creation),'%d/%m/%Y') creation,
                    company.fantasy_name,
                    company.corporate_name,
                    company.cnpj
                FROM
                    company
                WHERE company.id_company = '$id'";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Add($data)
    {
        $date = new DateTime();

        $values = [
            'creation' => $date->getTimestamp(),
            'corporate_name' => trim($data['input-corporate-name']),
            'cnpj' => $data['input-cnpj'],
            'fantasy_name' => trim($data['input-fantasy-name']),
        ];

        $this->db->insert('company', $values);

        
        if ($this->db->affected_rows() > 0) {

            $sql = "SELECT company.fantasy_name, company.id_company FROM company ORDER BY company.fantasy_name";

            $result = $this->db->query($sql);

            return $result->result_array();
        }
    }


    function Edit($key_id, $data)
    {
        $values = [
            'corporate_name' => trim($data['input-corporate-name']),
            'cnpj' => $data['input-cnpj'],
            'fantasy_name' => trim($data['input-fantasy-name']),
        ];

        $this->db->where('id_company', $key_id);
        $this->db->update('company', $values);
    }


    function Delete($key_id)
    {
        $this->db->where('id_company', $key_id);
        $this->db->delete('company');
    }
}
