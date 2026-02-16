<?php

class NotificatioAlert_model extends TA_model
{
    function Get($key_remote_id)
    {
        $sql = "select notification_alert_url from user WHERE key_remote_id = '{$key_remote_id}' ";
        $result = $this->db->query($sql);

        $data = $result->result_array();
        return $data;
    }


    function Update($key_remote_id, $media_file)
    {
        $sql = "update user set notification_alert_url = '{$media_file}' WHERE key_remote_id = '{$key_remote_id}' ";
        $this->db->query($sql);
    }
    

    function Delete($key_remote_id)
    {
        $sql = "update user set notification_alert_url = NULL WHERE key_remote_id = '{$key_remote_id}' ";
        $this->db->query($sql);
    }
}
