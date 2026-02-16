<?php

class Notification_model extends TA_model
{
    function CheckNotifications($key_remote_id)
    {
        $sql = "select\n";
        $sql .= "title,\n";
        $sql .= "type,\n";
        $sql .= "media_url\n";
        $sql .= "from talkall_admin.user_notification\n";
        $sql .= "where user_to = '{$key_remote_id}'\n";
        $sql .= "and status = '1'\n";
        $sql .= "and show_timestamp is null;\n";

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
        $result = $this->talkall_admin->query($sql);

        if ($result->num_rows() > 0) {
            return true;
        }

        return false;
    }


    function GetUserNotification($key_remote_id)
    {
        $sql = " select\n";
        $sql .= "id_user_notification,\n";
        $sql .= "title,\n";
        $sql .= "type,\n";
        $sql .= "media_url,\n";
        $sql .= "enable_scroll_bottom\n";
        $sql .= "from talkall_admin.user_notification\n";
        $sql .= "where user_to = '{$key_remote_id}'\n";
        $sql .= "and status = '1'\n";
        $sql .= "and type != '3'\n";
        $sql .= "and show_timestamp is null;\n";

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
        $result = $this->talkall_admin->query($sql);

        if ($result->num_rows() > 0) {
            return $result->result_array();
        }

        return false;
    }


    function SetNotificationRead($notify)
    {
        $values = [
            'show_timestamp' => $notify['show_timestamp'],
            'accept' => isset($notify['accept']) ? $notify['accept'] : '',
            'accept_timestamp' => isset($notify['accept_timestamp']) ? $notify['accept_timestamp'] : '',
            'enable_scroll_bottom' => isset($notify['enable_scroll_bottom']) ? $notify['enable_scroll_bottom'] : ''
        ];

        $this->db = $this->load->database(Setdatabase("talkall_admin"), TRUE);
        $this->db->where('id_user_notification', $notify['id_user_notification']);
        $this->db->update('user_notification', $values);
    }
}
