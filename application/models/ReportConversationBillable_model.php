<?php

class ReportConversationBillable_model extends TA_model
{
    function Get($text, $start, $length, $order_column, $order_dir)
    {
        $sql = "SELECT\n";
        $sql .= "from_unixtime(conversation_billable.creation) creation,\n";
        $sql .= "conversation_billable.key_remote_id,\n";
        $sql .= "conversation_billable.origin_type,\n";
        $sql .= "conversation_billable.billable,\n";
        $sql .= "conversation_billable.current_price\n";
        $sql .= "from conversation_billable\n";
        $sql .= "where conversation_billable.key_remote_id like '%" . $text . "%'\n";

        switch ($order_column) {
            case 0:
                $sql .= "order by from_unixtime(conversation_billable.creation) $order_dir \n";
                break;
            case 1:
                $sql .= "order by conversation_billable.key_remote_id $order_dir \n";
                break;
            case 2:
                $sql .= "order by conversation_billable.origin_type $order_dir \n";
                break;
            case 3:
                $sql .= "order by conversation_billable.billable $order_dir \n";
                break;
            case 4:
                $sql .= "order by conversation_billable.current_price $order_dir \n";
                break;
        }

        $sql .= "limit {$start},{$length}\n";

        $result = $this->db->query($sql);

        $data = $result->result_array();

        return $data;
    }


    function Count($text)
    {
        $sql = "select count(*) count\n";
        $sql .= "from conversation_billable\n";
        $sql .= "where conversation_billable.key_remote_id like '%" . $text . "%'\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }
}
