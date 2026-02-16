<?php

class Calendar_model extends TA_model
{

    function Get($text, $start, $length)
    {
        $sql = "select * from schedule\n";
        $sql .= "where schedule.title like '%" . $text . "%'\n";
        $sql .= "limit {$start},{$length}\n";

        $result = $this->db->query($sql);
        $data = $result->result_array();

        return $data;
    }


    function GetInf($id)
    {
        $sql = "select * from schedule\,";
        $sql .= "where schedule.id_schedule = '" . $id . "';\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Count($text)
    {
        $sql = "select count(schedule.id_schedule) count\n";
        $sql .= "from schedule\n";
        $sql .= "where schedule.title like '%" . $text . "%'\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Add($data)
    {
        $date = new DateTime();

        $schedule = $data['date_start'] . " " . $data['time_start'];

        $dtime = DateTime::createFromFormat("d/m/Y H:i", $schedule);
        $timestamp = $dtime->getTimestamp();

        $values = [
            'creation' => $date->getTimestamp(),
            'title' => $data['input-title'],
            'text' => $data['input-text'],
            'schedule' => $timestamp,
            'status' => 1,
        ];

        $this->db->insert('schedule', $values);
    }

    
    function Delete($key_id)
    {
        $this->db->where('id_schedule', $key_id);
        $this->db->delete('schedule');
    }
}
