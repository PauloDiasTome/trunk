<?php

class Widget_model extends CI_Model
{
    function Get($widget_token)
    {
        $sql = "select\n";
        $sql .= "company.id_company,\n";
        $sql .= "company.db\n";
        $sql .= "from company inner join channel on company.id_company = channel.id_company\n";
        $sql .= "where company.status = 1 and channel.id = '" . $widget_token . "'\n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {

            $this->db = $this->load->database(Setdatabase($result->row()->db), TRUE);

            $sql = "select\n";
            $sql .= "user.id_user,\n";
            $sql .= "short_link.title,\n";
            $sql .= "short_link.description,\n";
            $sql .= "short_link.link,\n";
            $sql .= "user.last_name,\n";
            $sql .= "user.key_remote_id\n";
            $sql .= "from short_link inner join user on short_link.id_user = user.id_user\n";
            $sql .= "inner join work_time on user.id_work_time = work_time.id_work_time\n";
            $sql .= "inner join work_time_week on work_time.id_work_time = work_time_week.id_work_time\n";
            $sql .= "where user.status = 1 and user.visible_widget = 1 and work_time_week.week = '" . date('N') . "' and TIME(DATE_ADD(now(), INTERVAL \n";
            $sql .= "(\n";
            $sql .= "select config.timezone from config inner join channel on config.id_channel = channel.id_channel\n";
            $sql .= " where channel.id = '$widget_token'\n";
            $sql .= ") HOUR)) between work_time_week.start and work_time_week.end\n";
            $sql .= "group by user.id_user, short_link.title,\n";
            $sql .= "short_link.description,\n";
            $sql .= "short_link.link,\n";
            $sql .= "user.last_name,\n";
            $sql .= "user.key_remote_id\n";
            $sql .= "order by user.id_user\n";

            $result = $this->db->query($sql);

            return $result->result_array();
        }
    }


    function QueryWidget($widget_token)
    {

        $sql = "SELECT * FROM channel WHERE channel.id = '$widget_token'\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }
}
