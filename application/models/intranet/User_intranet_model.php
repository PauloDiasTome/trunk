<?php

class User_intranet_model extends TA_Model
{
    public function __construct()
    {
        parent::__construct();
        parent::SetTalkall();
    }

    function Get($text, $start, $length, $order_column, $order_dir)
    {
        $sql = "select\n";
        $sql .= "user.id_user,\n";
        $sql .= "user.key_remote_id,\n";
        $sql .= "company.corporate_name,\n";
        $sql .= "company.cnpj,\n";
        $sql .= "company.id_company,\n";
        $sql .= "company.db,\n";
        $sql .= "user.email,\n";
        $sql .= "user.status\n";
        $sql .= "from user\n";
        $sql .= "inner join company on user.id_company = company.id_company\n";
        $sql .= "where user.email not like 'suporte%@talkall.com.br'\n";
        $sql .= "and (user.email like '%" . $text . "%'\n";
        $sql .= "or company.corporate_name like '%" . $text . "%'\n";
        $sql .= "or company.cnpj like '%" . $text . "%'\n";
        $sql .= "or company.db like '%" . $text . "%')\n";

        switch ($order_column) {
            case 0:
                $sql .= "ORDER BY user.email $order_dir\n";
                break;
            case 1:
                $sql .= "ORDER BY company.corporate_name $order_dir\n";
                break;
            case 2:
                $sql .= "ORDER BY company.db $order_dir\n";
                break;
            case 3:
                $sql .= "ORDER BY user.status $order_dir\n";
                break;
            default:
                $sql .= "ORDER BY 1 desc\n";
                break;
        }

        $sql .= "limit {$start},{$length}\n";

        // var_dump($sql);
        // die;

        $result = $this->db->query($sql);

        // var_dump($result->result_array());
        // die;

        if ($result->num_rows() > 0) {
            return $result->result_array();
        }

        return null;
    }

    function Count($text)
    {
        $sql = "select count(user.id_user) count\n";
        $sql .= "from user\n";
        $sql .= "inner join company on user.id_company = company.id_company\n";
        $sql .= "where user.email not like 'suporte%@talkall.com.br'\n";
        $sql .= "and (user.email like '%" . $text . "%'\n";
        $sql .= "or company.corporate_name like '%" . $text . "%'\n";
        $sql .= "or company.cnpj like '%" . $text . "%'\n";
        $sql .= "or company.db like '%" . $text . "%')\n";
        $sql .= "or company.db like '%" . $text . "%'\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }

    function GetKeyRemoteId($id_user)
    {
        $sql = "select\n";
        $sql .= "user.email,\n";
        $sql .= "company.db\n";
        $sql .= "from talkall_admin.company\n";
        $sql .= "inner join user on user.id_company = company.id_company\n";
        $sql .= "where user.id_user = {$id_user} \n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {

            $this->db = $this->load->database(Setdatabase($result->row()->db), TRUE);

            $sql = "select\n";
            $sql .= "user.key_remote_id\n";
            $sql .= "from user\n";
            $sql .= "where user.status = 1 and user.email = '{$result->row()->email}' \n";

            $result = $this->db->query($sql);

            return $result->row()->key_remote_id;
        } else {
            $error = 'Nenhum registro encontrado para id informado.';
            throw new Exception($error);
        }
    }

    function GetById($id_user)
    {
        $sql = "select\n";
        $sql .= "company.id_company,\n";
        $sql .= "user.email,\n";
        $sql .= "company.db\n";
        $sql .= "from talkall_admin.company\n";
        $sql .= "inner join user on user.id_company = company.id_company\n";
        $sql .= "where user.id_user = {$id_user} \n";

        $result = $this->db->query($sql);
        $data = $result->result_array()[0];

        if ($result->num_rows() > 0) {

            $this->db = $this->load->database(Setdatabase($result->row()->db), TRUE);

            $sql = "select\n";
            $sql .= "from_unixtime(user.creation,'%d/%m/%Y') as creation,\n";
            $sql .= "user.name,\n";
            $sql .= "user.last_name,\n";
            $sql .= "user.key_remote_id, \n";
            $sql .= "user.id_permission,\n";
            $sql .= "user.id_user_call,\n";
            $sql .= "user.id_work_time,\n";
            $sql .= "user.id_user_group,\n";
            $sql .= "user.visible_widget\n";
            $sql .= "from user\n";
            $sql .= "where user.status = 1 and user.email = '{$result->row()->email}' \n";

            $result = $this->db->query($sql);

            $data = array_merge($data, $result->result_array()[0]);

            return $data;
        } else {
            $error = 'Nenhum registro encontrado para id informado.';
            throw new Exception($error);
        }
    }

    function UpdateUser($id_user, $data)
    {
        $values = [
            'name' => $data['name'],
            'last_name' => $data['last_name'],
            'id_user_call' => $data['user_call'],
            'id_user_group' => $data['user_group'],
            'id_permission' => $data['id_permission'],
            'visible_widget' => $data['visible_widget'],
            'id_work_time' => $data['id_work_time'] == 0 ? NULL : $data['id_work_time']
        ];

        // Salva as informações em db cliente //
        $this->load->model('intranet/Company_model', '', TRUE);

        $sql = "select\n";
        $sql .= "company.db\n";
        $sql .= "from user\n";
        $sql .= "inner join company on company.id_company = user.id_company\n";
        $sql .= "where user.id_user = '{$id_user}'\n";

        $company = $this->db->query($sql);

        $this->db = $this->load->database(Setdatabase($company->row()->db), TRUE);

        $sql = "select\n";
        $sql .= "user.id_user\n";
        $sql .= "from user\n";
        $sql .= "where user.key_remote_id = '{$data['key_remote_id']}'\n";
        $sql .= "limit 1\n";

        $user = $this->db->query($sql);

        $this->db->where('id_user', $user->row()->id_user);
        $this->db->update('user', $values);
    }

    function getAllUsers()
    {
        $sql = "select talkall_admin.user.key_remote_id from talkall_admin.user\n";
        $sql .= "where talkall_admin.user.key_remote_id is not null\n";
        $sql .= "order by talkall_admin.user.id_user\n";

        $user = $this->db->query($sql);

        return $user->result_array();
    }

    function getAllUsersCompanies($ids)
    {
        $ids = implode(',', $ids);

        $sql = "select id_company,talkall_admin.user.key_remote_id from talkall_admin.user\n";
        if ($ids > 0) {
            $sql .= "where talkall_admin.user.id_company in ({$ids})\n";
        }

        $sql .= "order by talkall_admin.user.id_user\n";

        $user = $this->db->query($sql);

        return $user->result_array();
    }

    function getUsersById($ids)
    {
        $ids = implode(',', $ids);

        $sql = "select id_company,talkall_admin.user.key_remote_id from talkall_admin.user\n";
        $sql .= "where talkall_admin.user.id_user in ({$ids})\n";
        $sql .= "order by talkall_admin.user.id_user\n";

        $user = $this->db->query($sql);

        return $user->result_array();
    }

    function getIdUserDB($data)
    {
        $sql = "select id_user\n";
        $sql .= "from $data[1].user\n";
        $sql .= "where key_remote_id = '$data[0]'\n";

        $user = $this->db->query($sql);

        return $user->result_array();
    }

    function unlockUser($data)
    {
        $values = [
            'status' => 1,
            'login_retry' => 0
        ];

        $this->db->where('key_remote_id', $data[0]);
        $ret = $this->db->update($data[1] . '.user', $values);

        if ($ret == true) {

            $values = [
                'status' => 1
            ];

            $this->db->where('key_remote_id', $data[0]);
            $ret = $this->db->update('user', $values);

            return $ret;
        } else {
            return false;
        }
    }

    function updateLogUnlockUser($data)
    {
        $ci = &get_instance();
        $ci->load->database();

        $date = new DateTime();

        $values = [
            'id_user' => $data[2],
            'creation' => $date->getTimestamp(),
            'text' => 'LOGIN_UNLOCKED_INTRANET',
            'system' => $ci->agent->platform(),
            'agent' => $ci->agent->browser(),
            'version' => $ci->agent->version(),
            'ip' => $ci->input->ip_address()
        ];

        $this->db->insert($data[1] . '.user_log', $values);
    }
}
