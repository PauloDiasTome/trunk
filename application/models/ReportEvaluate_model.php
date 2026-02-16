<?php

class ReportEvaluate_model extends TA_model
{
    function Get($text, $dt_start, $dt_end, $start, $length, $order_column, $order_dir)
    {
        $sql = "SELECT\n";
        $sql .= "user.last_name user_name,\n";
        $sql .= "COUNT(CASE evaluation\n";
        $sql .= "WHEN 'EVALUATE_LEVEL_1' THEN 1\n";
        $sql .= "ELSE NULL\n";
        $sql .= "END) evaluate_level_1,\n";
        $sql .= "COUNT(CASE evaluation\n";
        $sql .= "WHEN 'EVALUATE_LEVEL_2' THEN 1\n";
        $sql .= "ELSE NULL\n";
        $sql .= "END) evaluate_level_2,\n";
        $sql .= "COUNT(CASE evaluation\n";
        $sql .= "WHEN 'EVALUATE_LEVEL_3' THEN 1\n";
        $sql .= "ELSE NULL\n";
        $sql .= "END) evaluate_level_3,\n";
        $sql .= "COUNT(evaluate_service.id_evaluate_service) qtda\n";
        $sql .= "FROM\n";
        $sql .= "evaluate_service\n";
        $sql .= "INNER JOIN\n";
        $sql .= "user ON evaluate_service.user_key_remote_id = user.key_remote_id\n";
        $sql .= "WHERE\n";
        $sql .= "LOWER(user.last_name) like LOWER('%" . $text . "%')\n";
        $sql .= "and evaluate_service.status = 3\n";
        $sql .= "and evaluate_service.evaluation is not null\n";
        $sql .= "and evaluate_service.evaluation != ''\n";
        $sql .= "and evaluate_service.evaluation != 'null'\n";

        if (trim($dt_start) != "") {
            $sql .= "AND DATE_FORMAT(from_unixtime(evaluate_service.creation),'%Y-%m-%d') BETWEEN '{$dt_start}' AND '{$dt_end}'";
        }

        $sql .= "GROUP BY user.last_name\n";

        switch ($order_column) {
            case 0:
                $sql .= "order by user.last_name $order_dir \n";
                break;
            case 2:
                $sql .= "order by qtda $order_dir \n";
                break;
            default:
                $sql .= "order by user $order_dir \n";
        }

        $sql .= "limit {$start},{$length}\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }
}
