<?php

class Notification_model extends TA_model
{
    public function __construct()
    {
        parent::__construct();
        parent::SetTalkall();
    }

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

        $result = $this->db->query($sql);

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
        $sql .= "and show_timestamp is null;\n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            return $result->result_array();
        }

        return false;
    }

    function SetNotificationRead($notify)
    {
        $this->db->where('id_user_notification', $notify['id_user_notification']);
        $this->db->set('show_timestamp', $notify['show_timestamp']);
        $this->db->set('accept', $notify['accept']);
        $this->db->set('accept_timestamp', $notify['accept_timestamp']);
        $this->db->set('enable_scroll_bottom', $notify['enable_scroll_bottom']);
        $this->db->update('user_notification');
    }

    function AddNotification($data)
    {   
        $date = new DateTime();

        $values = [
            'creation' => $date->getTimestamp(),
            'id_notification' => $data['id_notification'],
            'user_from' => $this->session->userdata('key_remote_id'),
            'title' => $data['input-title'],
            'user_to' => $data['key_remote_id'],
            'type' => $data['select-type'],
            'media_url' => $data['file'],
            'enable_scroll_bottom' => $data['select-type'] == '2' ? 1 : null
        ];

        $this->db->insert("user_notification", $values);
    }

    function Count($text)
    {
        $sql = "select count(distinct id_notification) as count\n";
        $sql .= "from talkall_admin.user_notification\n";                
        $sql .= "where user_notification.status = '1'\n";
        $sql .= "and user_notification.title like '%" . $text . "%'\n";

        $result = $this->db->query($sql);

        $total = array(
            'count' => 0
        );

        if ($result->num_rows() > 1) {
            return $total['count'] = $result->result_array();
        }

        return $total; 
    }
    
    function Get($text, $start, $length)
    {
        $sql = "select\n";
        $sql .= "user_notification.id_notification,\n";
        $sql .= "from_unixtime(user_notification.creation,'%d/%m/%Y') as creation,\n";
        $sql .= "user_notification.title,\n";
        $sql .= "user_notification.type,\n";
        $sql .= "user_notification.media_url,\n";
        $sql .= "Count(id_user_notification) as qtd_notify,\n";
        $sql .= "Count(show_timestamp) as qtd_viewed\n";
        $sql .= "from talkall_admin.user_notification\n";
        $sql .= "where user_notification.status = '1'\n";
        $sql .= "and user_notification.title like '%" . $text . "%'\n";
        $sql .= "group by id_notification\n";
        $sql .= "order by user_notification.creation desc limit {$start},{$length}\n";

        $result = $this->db->query($sql);
    
        if($result->num_rows() > 0){
            return  $result->result_array();
        }

        return [];
        
    }

    function Inativar($id)
    {
        $this->db->where('id_notification', $id);
        $this->db->set('status', 2);       
        $this->db->update('user_notification');
    }
}
