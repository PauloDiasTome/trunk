<?php

class Permission_intranet_model extends TA_Model
{
    public function __construct()
    {
        parent::__construct();
        parent::SetTalkall();
    }


    function Get($text, $start, $length, $order_column, $order_dir)
    {
        $sql = "select\n";
        $sql .= "permission.id_permission,\n";
        $sql .= "permission.name\n";
        $sql .= "from permission\n";
        $sql .= "where permission.name like '%" . $text . "%'\n";

        switch ($order_column) {
            case 0:
                $sql .= "ORDER BY permission.name $order_dir\n";
                break;
        }

        $sql .= "limit {$start},{$length}\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Count($text)
    {
        $sql = "select\n";
        $sql .= "count(permission.id_permission) count\n";
        $sql .= "from permission\n";
        $sql .= "where permission.name like '%" . $text . "%'\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function getPermissions()
    {
        $sql = "SELECT * FROM permission_list";

        $result = $this->db->query($sql);
        return $result->result_array();
    }


    function createPermission($data)
    {
        $date = new DateTime();

        $values = [
            'creation' => $date->getTimestamp(),
            'name' => $data['name'],
            'find_company' => isset($data['permissions']['find_company']) ? $data['permissions']['find_company'] : 2,
            'add_company' => isset($data['permissions']['add_company']) ? $data['permissions']['add_company'] : 2,
            'view_company' => isset($data['permissions']['view_company']) ? $data['permissions']['view_company'] : 2,
            'edit_company' => isset($data['permissions']['edit_company']) ? $data['permissions']['edit_company'] : 2,
            'export_company' => isset($data['permissions']['export_company']) ? $data['permissions']['export_company'] : 2,
            'find_server' => isset($data['permissions']['find_server']) ? $data['permissions']['find_server'] : 2,
            'add_server' => isset($data['permissions']['add_server']) ? $data['permissions']['add_server'] : 2,
            'edit_server' => isset($data['permissions']['edit_server']) ? $data['permissions']['edit_server'] : 2,
            'delete_server' => isset($data['permissions']['delete_server']) ? $data['permissions']['delete_server'] : 2,
            'find_user' => isset($data['permissions']['find_user']) ? $data['permissions']['find_user'] : 2,
            'reset_password_user' => isset($data['permissions']['reset_password_user']) ? $data['permissions']['reset_password_user'] : 2,
            'change_password_user' => isset($data['permissions']['change_password_user']) ? $data['permissions']['change_password_user'] : 2,
            'unlock_user_user' => isset($data['permissions']['unlock_user_user']) ? $data['permissions']['unlock_user_user'] : 2,
            'find_channel' => isset($data['permissions']['find_channel']) ? $data['permissions']['find_channel'] : 2,
            'add_channel' => isset($data['permissions']['add_channel']) ? $data['permissions']['add_channel'] : 2,
            'edit_channel' => isset($data['permissions']['edit_channel']) ? $data['permissions']['edit_channel'] : 2,
            'find_permission' => isset($data['permissions']['find_permission']) ? $data['permissions']['find_permission'] : 2,
            'add_permission' => isset($data['permissions']['add_permission']) ? $data['permissions']['add_permission'] : 2,
            'view_permission' => isset($data['permissions']['view_permission']) ? $data['permissions']['view_permission'] : 2,
            'edit_permission' => isset($data['permissions']['edit_permission']) ? $data['permissions']['edit_permission'] : 2,
            'delete_permission' => isset($data['permissions']['delete_permission']) ? $data['permissions']['delete_permission'] : 2
        ];

        $res = $this->db->insert('permission', $values);

        return $res;
    }


    function getPermission($id)
    {
        $sql = "select\n";
        $sql .= "permission.*\n";
        $sql .= "from permission\n";
        $sql .= "where permission.id_permission = $id\n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            return $result->result_array();
        }

        return null;
    }

    function editPermission($data)
    {

        $values = [
            'name' => $data['name'],
            'find_company' => isset($data['permissions']['find_company']) ? $data['permissions']['find_company'] : 2,
            'add_company' => isset($data['permissions']['add_company']) ? $data['permissions']['add_company'] : 2,
            'view_company' => isset($data['permissions']['view_company']) ? $data['permissions']['view_company'] : 2,
            'edit_company' => isset($data['permissions']['edit_company']) ? $data['permissions']['edit_company'] : 2,
            'export_company' => isset($data['permissions']['export_company']) ? $data['permissions']['export_company'] : 2,
            'find_server' => isset($data['permissions']['find_server']) ? $data['permissions']['find_server'] : 2,
            'add_server' => isset($data['permissions']['add_server']) ? $data['permissions']['add_server'] : 2,
            'edit_server' => isset($data['permissions']['edit_server']) ? $data['permissions']['edit_server'] : 2,
            'delete_server' => isset($data['permissions']['delete_server']) ? $data['permissions']['delete_server'] : 2,
            'find_user' => isset($data['permissions']['find_user']) ? $data['permissions']['find_user'] : 2,
            'reset_password_user' => isset($data['permissions']['reset_password_user']) ? $data['permissions']['reset_password_user'] : 2,
            'change_password_user' => isset($data['permissions']['change_password_user']) ? $data['permissions']['change_password_user'] : 2,
            'unlock_user_user' => isset($data['permissions']['unlock_user_user']) ? $data['permissions']['unlock_user_user'] : 2,
            'find_channel' => isset($data['permissions']['find_channel']) ? $data['permissions']['find_channel'] : 2,
            'add_channel' => isset($data['permissions']['add_channel']) ? $data['permissions']['add_channel'] : 2,
            'edit_channel' => isset($data['permissions']['edit_channel']) ? $data['permissions']['edit_channel'] : 2,
            'find_permission' => isset($data['permissions']['find_permission']) ? $data['permissions']['find_permission'] : 2,
            'add_permission' => isset($data['permissions']['add_permission']) ? $data['permissions']['add_permission'] : 2,
            'view_permission' => isset($data['permissions']['view_permission']) ? $data['permissions']['view_permission'] : 2,
            'edit_permission' => isset($data['permissions']['edit_permission']) ? $data['permissions']['edit_permission'] : 2,
            'delete_permission' => isset($data['permissions']['delete_permission']) ? $data['permissions']['delete_permission'] : 2
        ];

        $this->db->where('id_permission', $data['id_permission']);
        $res = $this->db->update('permission', $values);

        return $res;
    }

    function deletePermission($id)
    {
        $this->db->where('id_permission', $id);
        $res = $this->db->delete('permission');

        return $res;
    }
}
