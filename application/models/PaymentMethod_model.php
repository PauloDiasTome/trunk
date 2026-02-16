<?php

class PaymentMethod_model extends TA_model
{

    function Get($text, $start, $length, $order_column, $order_dir)
    {
        $sql = "SELECT\n";
        $sql .= "payment_methods.id_payment_method,\n";
        $sql .= "payment_methods.name\n";
        $sql .= "FROM payment_methods\n";
        $sql .= "where payment_methods.name like '%" . $text . "%'\n";

        switch ($order_column) {
            case 0:
                $sql .= "ORDER BY payment_methods.name $order_dir\n";
                break;

            default:
                $sql .= "ORDER BY payment_methods.name asc\n";
        }

        $sql .= "limit {$start},{$length}\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function GetInf($id)
    {
        $sql = "select\n";
        $sql .= "payment_methods.id_payment_method,\n";
        $sql .= "payment_methods.name\n";
        $sql .= "from payment_methods\n";
        $sql .= "where payment_methods.id_payment_method = '$id'\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function List()
    {
        $sql = "SELECT\n";
        $sql .= "payment_methods.id_payment_method,\n";
        $sql .= "payment_methods.name\n";
        $sql .= "FROM payment_methods\n";
        $sql .= "order by payment_methods.name\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Count($text)
    {
        $sql = "select count(payment_methods.id_payment_method) count\n";
        $sql .= "from payment_methods\n";
        $sql .= "where payment_methods.name like '%" . $text . "%'\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Add($data)
    {
        $date = new DateTime();

        $values = [
            'creation' => $date->getTimestamp(),
            'name' => $data['input-name'],
            'status' => 1,
        ];

        $this->db->insert('payment_methods', $values);
    }


    function Edit($key_id, $data)
    {
        $values = [
            'name' => $data['input-name'],
        ];

        $this->db->where('id_payment_method', $key_id);
        $this->db->update('payment_methods', $values);
    }


    function Delete($key_id)
    {
        $this->db->where('id_payment_method', $key_id);
        $this->db->delete('payment_methods');
    }

}
