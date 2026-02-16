<?php

class User_model extends TA_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->lang->load('user_lang', $this->session->userdata('language'));
    }

    function Get($post)
    {
        $text = $post['text'];
        $sector = $post['sector'];
        $situation = $post['situation'];
        $order_column = $post['order'][0]['column'];
        $order_dir = $post['order'][0]['dir'];
        $start = $post['start'];
        $length = $post['length'];

        $sql = "SELECT 
                    user.id_user,
                    user_group.id_user_group,
                    user.name,
                    user.key_remote_id,
                    user_group.name AS department,
                    user.email,
                    user.status
                FROM
                    user
                        LEFT JOIN
                    user_group ON user_group.id_user_group = user.id_user_group
                WHERE
                    user.visible = 1 AND user.status <> 2
                        AND (LOWER(user.name) LIKE LOWER('%{$text}%') OR LOWER(user.email) LIKE LOWER('%{$text}%') OR LOWER(user_group.name) LIKE LOWER('%{$text}%'))\n";

        if (!empty($sector)) {
            $sql .= "AND user_group.id_user_group IN (" . implode(",", $sector) . ")\n";
        }

        if (!empty($situation)) {
            $sql .= "AND user.status = {$situation} \n";
        }

        switch ($order_column) {
            case 0:
                $sql .= "ORDER BY user.name {$order_dir}\n";
                break;

            case 1:
                $sql .= "ORDER BY user_group.name {$order_dir}\n";
                break;

            case 2:
                $sql .= "ORDER BY user.email {$order_dir}\n";
                break;

            default:
                $sql .= "ORDER BY user.name {$order_dir}\n";
        }

        $sql .= "LIMIT {$start},{$length}\n";

        $result = $this->db->query($sql);

        return  $result->result_array();
    }


    function Count($post)
    {
        $text = $post['text'];
        $sector = $post['sector'];
        $situation = $post['situation'];

        $sql = "SELECT 
                    COUNT(user.id_user) count
                FROM
                    user
                        LEFT JOIN
                    user_group ON user_group.id_user_group = user.id_user_group
                WHERE
                    user.visible = 1 AND user.status <> 2
                        AND (LOWER(user.name) LIKE LOWER('%{$text}%') OR LOWER(user.email) LIKE LOWER('%{$text}%') OR LOWER(user_group.name) LIKE LOWER('%{$text}%'))\n";

        if (!empty($sector)) {
            $sectorList = implode(",", $sector);
            $sql .= "AND user_group.id_user_group IN ({$sectorList})\n";
        }

        if (!empty($situation)) {
            $sql .= "AND user.status = {$situation}\n";
        }

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Add($data)
    {
        $date = new DateTime();
        $key_remote_id = Token();

        $values = [
            'creation' => $date->getTimestamp(),
            'name' => trim($data['input-name']),
            'key_remote_id' => $key_remote_id,
            'last_name' => trim($data['input-last-name']),
            'email' => trim(strtolower($data['input-email'])),
            'password' => $this->config->item('default_password'),
            'id_user_group' => $data['user_group'] == 0 ? null : $data['user_group'],
            'id_user_call' => $data['user_call'] == 0 ? null : $data['user_call'],
            'id_permission' => $data['id_permission'],
            'visible_widget' => intval($data['visible_widget']),
            'visible' => 1,
            'language' => $data['sector_language'],
            'id_work_time' => $data['id_work_time'] == 0 ? NULL : $data['id_work_time'],
            'attendance_available' => '0',
            'status' => 3
        ];

        $this->db->insert('user', $values);

        $user_id = $this->db->insert_id();

        $values = [
            'name' => trim($data['input-name']),
            'id' => $key_remote_id,
            'type' => 1,
        ];

        $this->db->insert('channel', $values);

        $channel_id =  $this->db->insert_id();

        $values = [
            'name' => 'Kanban',
            'id' => 'kanban-' . $key_remote_id,
            'pw' => 'kanban-' . $key_remote_id,
            'type' => 5,
        ];

        $this->db->insert('channel', $values);

        $values = [
            'name' => 'kanban-communication',
            'id' => 'kanban-communication-' . $key_remote_id,
            'pw' => 'kanban-communication-' . $key_remote_id,
            'type' => 27,
        ];

        $this->db->insert('channel', $values);

        $values = [
            'name' => 'Connect',
            'id' => 'connect-' . $key_remote_id,
            'pw' => 'connect-' . $key_remote_id,
            'type' => 21,
        ];

        $this->db->insert('channel', $values);
        $id = $this->db->insert_id();

        $values = [
            'id_channel' => $id,
            'timezone' => '-00:00',
            'welcome_message' => '',
        ];

        $this->db->insert('config', $values);

        $values = [
            'name' => 'App',
            'id' => 'app-' . $key_remote_id,
            'pw' => 'app-' . $key_remote_id,
            'type' => 22,
        ];

        $this->db->insert('channel', $values);
        $id = $this->db->insert_id();

        $values = [
            'id_channel' => $id,
            'timezone' => '-00:00',
            'welcome_message' => '',
        ];

        $this->db->insert('config', $values);

        $values = [
            'creation' => $date->getTimestamp(),
            'id_user' => $user_id,
            'token' => $key_remote_id,
        ];

        $this->db->insert('user_token', $values);

        $values = [
            'id_channel' => $channel_id,
            'timezone' => '-03:00',
            'welcome_message' => '',
        ];

        $this->db->insert('config', $values);

        $values = [
            'creation' => $date->getTimestamp(),
            'id_channel' => $channel_id,
            'key_remote_id' => $key_remote_id,
            'full_name' => trim($data['input-last-name']),
            'deleted' => 1,
            'spam' => 1,
            'sex' => 1,
            'verify' => 2,
            'exist' => 1,
            'is_private' => 2,
            'is_group' => 1,
            'presence' => 'unavailable',
            'timestamp' => $date->getTimestamp()
        ];

        $this->db->insert('contact', $values);

        // Salva as informações em talkall_admin //

        $this->talkall_admin = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), TRUE);

        $values = [
            'creation' => $date->getTimestamp(),
            'key_remote_id' => $key_remote_id,
            'id_company' => $this->session->userdata('id_company'),
            'email' => trim(strtolower($data['input-email'])),
            'password' => $this->config->item('default_password'),
            'status' => 3
        ];

        $this->talkall_admin->insert("user", $values);

        $user_id = $this->talkall_admin->insert_id();

        $values = [
            'id' => 'kanban-' . $key_remote_id,
            'pw' => 'kanban-' . $key_remote_id,
            'creation' => $date->getTimestamp(),
            'id_company' => $this->session->userdata('id_company'),
            'type' => 5,
            'status' => 1,
            'executed' => 1
        ];

        $this->talkall_admin->insert('channel', $values);

        $values = [
            'id' => 'kanban-communication-' . $key_remote_id,
            'pw' => 'kanban-communication-' . $key_remote_id,
            'creation' => $date->getTimestamp(),
            'id_company' => $this->session->userdata('id_company'),
            'type' => 27,
            'status' => 1,
            'executed' => 1
        ];

        $this->talkall_admin->insert('channel', $values);

        $values = [
            'id' => 'connect-' . $key_remote_id,
            'pw' => 'connect-' . $key_remote_id,
            'creation' => $date->getTimestamp(),
            'id_company' => $this->session->userdata('id_company'),
            'type' => 21,
            'status' => 1,
            'executed' => 1
        ];

        $this->talkall_admin->insert('channel', $values);

        $values = [
            'id' => 'app-' . $key_remote_id,
            'pw' => 'app-' . $key_remote_id,
            'creation' => $date->getTimestamp(),
            'id_company' => $this->session->userdata('id_company'),
            'type' => 22,
            'status' => 1,
            'executed' => 1
        ];

        $this->talkall_admin->insert('channel', $values);

        $values = [
            'id' => $key_remote_id,
            'pw' => $key_remote_id,
            'creation' => $date->getTimestamp(),
            'id_company' => $this->session->userdata('id_company'),
            'type' => 1,
            'status' => 1,
            'executed' => 1
        ];

        $this->talkall_admin->insert('channel', $values);

        $token = Token();

        $values = [
            'creation' => $date->getTimestamp(),
            'id_user' => $user_id,
            'token' => $token,
            'status' => 3,
        ];

        $this->talkall_admin->insert('user_valid', $values);

        $view = "email_confirm_" . $data['sector_language'];

        $url =  base_url() . "email/confirm/" . $token;

        $this->SendEmail($data, $view, $url);
    }


    function Edit($key_id, $data)
    {
        $values = [
            'name' => trim($data['input-name']),
            'last_name' => trim($data['input-last-name']),
            'id_user_call' => $data['user_call'],
            'id_user_group' => $data['user_group'],
            'id_permission' => $data['id_permission'],
            'visible_widget' => $data['visible_widget'],
            'id_work_time' => $data['id_work_time'] == 0 ? NULL : $data['id_work_time'],
            'language' => $data['sector_language']
        ];

        $this->db->where('id_user', $key_id);
        $this->db->update('user', $values);

        $sql = "SELECT user.key_remote_id FROM user WHERE user.id_user = '$key_id'";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {

            $value = [
                'full_name' => trim($data['input-last-name']),
            ];

            $this->db->where('key_remote_id', $result->row()->key_remote_id);
            $this->db->update('contact', $value);
        }
    }

    function Delete($key_id)
    {
        $sql = "select *\n";
        $sql .= "from user\n";
        $sql .= "where user.id_user = {$key_id}\n";
        $sql .= "limit 1\n";

        $result = $this->db->query($sql);

        $query = "select * from chat_list where key_remote_id = '{$result->row()->key_remote_id}' and is_close = 1 and is_private = 1\n";

        $attendance = $this->db->query($query);

        // usuario com atendimento em aberto  //
        if ($attendance->num_rows() > 0) {
            return array("status" => 1);
        }

        $query_wait_list = "select * from wait_list where status = 1 and user_key_remote_id = '{$result->row()->key_remote_id}'\n";

        $waiting = $this->db->query($query_wait_list);

        // usuario tem contatos aguardando atendimento //
        if ($waiting->num_rows() > 0) {
            return array("status" => 3);
        }

        $query_resposible = "select * from contact where contact.deleted = 1 and contact.spam = 1 and contact.user_key_remote_id = '{$result->row()->key_remote_id}'\n";

        $responsible = $this->db->query($query_resposible);

        // usuario possui contatos como responsavel // 
        if ($responsible->num_rows() > 0) {

            $query_name = "select name, id_user from user where user.key_remote_id = '{$result->row()->key_remote_id}'";

            $result_name = $this->db->query($query_name);

            $response = array("user" => $result_name->row()->name, "id_user" => $result_name->row()->id_user,  "status" => 2);

            return $response;
        }



        if ($result->num_rows() > 0 && $attendance->num_rows() == 0 && $responsible->num_rows() == 0 && $waiting->num_rows() == 0) {

            $this->db->where('key_remote_id', $result->row()->key_remote_id);
            $this->db->update('contact', array('deleted' => 2));

            $this->db->where('email', $result->row()->email);
            $this->db->update('user', array('status' => 2, 'id_user_call' => NULL));

            $this->db->where('id', $result->row()->key_remote_id);
            $this->db->update('channel', array('status' => 2));

            $this->db->where('id', "kanban-" . $result->row()->key_remote_id);
            $this->db->update('channel', array('status' => 2));

            $this->db->where('id', "connect-" . $result->row()->key_remote_id);
            $this->db->update('channel', array('status' => 2));

            $this->db->where('id', "app-" . $result->row()->key_remote_id);
            $this->db->update('channel', array('status' => 2));

            $this->db->where('key_remote_id', $result->row()->key_remote_id);
            $this->db->delete('group_participants');

            $this->talkall_admin = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), TRUE);

            $this->talkall_admin->where('email', $result->row()->email);
            $this->talkall_admin->update('user', array('status' => 2));

            $this->talkall_admin->where('id', $result->row()->key_remote_id);
            $this->talkall_admin->update('channel', array('status' => 2));

            $this->talkall_admin->where('id', "connect-" . $result->row()->key_remote_id);
            $this->talkall_admin->update('channel', array('status' => 2));

            $this->talkall_admin->where('id', "qrcode-" . $result->row()->key_remote_id);
            $this->talkall_admin->update('channel', array('status' => 2));

            $this->talkall_admin->where('id', "kanban-" . $result->row()->key_remote_id);
            $this->talkall_admin->update('channel', array('status' => 2));

            $this->talkall_admin->where('id', "app-" . $result->row()->key_remote_id);
            $this->talkall_admin->update('channel', array('status' => 2));

            $this->talkall_admin->where('key_remote_id', $result->row()->key_remote_id);
            $this->talkall_admin->delete('web_session');

            CreateUserLog($this->db->database, $this->db->hostname, 'DELETE_USER', $this->session->userdata('id_user'), 0);
        }

        return array("status" => "");
    }


    function GetProfilePicture($key_remote_id)
    {
        $path = "profiles/" . $key_remote_id . ".jpeg";
        if (file_exists($path) == true && filesize($path) > 0) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        } else {
            return '../../assets/img/avatar.svg';
        }
    }


    function GetTypeAccess($email)
    {
        $sql = "select accessType FROM talkall_admin.user where email = '{$email}' ";

        parent::SetTalkall();

        $result = $this->db->query($sql);

        parent::SetClient();

        $data = null;

        if ($result->num_rows() > 0) {
            $data = $result->result_array()[0];
        }

        return $data;
    }


    function GetUserData($email, $password)
    {
        if ($email == null || $password == null) {
            return null;
        }

        $email = $this->db->escape($email); # prevent sql injection
        $password = md5($password);

        $this->db = $this->load->database(SetdatabaseRemote('talkall_admin', '192.168.190.40'), TRUE);

        $sql = "SELECT\n";
        $sql .= "company.id_company,\n";
        $sql .= "company.db,\n";
        $sql .= "company.server host,\n";
        $sql .= "company.is_trial,\n";
        $sql .= "user.id_user,\n";
        $sql .= "user.key_remote_id,\n";
        $sql .= "web_session.browser_token,\n";
        $sql .= "web_session.ip,\n";
        $sql .= "web_session.app_version,\n";
        $sql .= "web_session.os\n";
        $sql .= "FROM\n";
        $sql .= "company\n";
        $sql .= "INNER JOIN user ON company.id_company = user.id_company\n";
        $sql .= "left join web_session on user.key_remote_id = web_session.key_remote_id\n";
        $sql .= "WHERE\n";
        $sql .= "company.status = 1 AND user.status in(1,4)\n";
        $sql .= "AND user.email = $email\n";
        $sql .= "AND user.password = '$password'\n";
        $sql .= "LIMIT 5\n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {

            $result = $result->result_array()[0];
            $this->db = $this->load->database(SetdatabaseRemote($result['db'], $result['host']), TRUE);

            $sql = "select\n";
            $sql .= "user.name,\n";
            $sql .= "user.status,\n";
            $sql .= "'" . $result['id_company'] . "' as id_company,\n";
            $sql .= "'" . $result['db'] . "' as db,\n";
            $sql .= "'" . $result['host'] . "' as host,\n";
            $sql .= "'" . $result['id_user'] . "' as id_user_talkall_admin,\n";
            $sql .= "'" . $result['browser_token'] . "' as browser_token,\n";
            $sql .= "'" . $result['ip'] . "' as ip,\n";
            $sql .= "'" . $result['app_version'] . "' as app_version,\n";
            $sql .= "'" . $result['os'] . "' as os,\n";
            $sql .= "'" . $result['is_trial'] . "' as is_trial,\n";
            $sql .= "user.id_user, user.key_remote_id, user.2fa, user.email, user.phone, user.password, user.2fa, coalesce(user.language, 'pt_br') as language, \n";
            $sql .= "CASE id_permission WHEN 1 THEN 't' ELSE 'f' END AS is_admin \n";
            $sql .= "from contact, user\n";
            $sql .= "where contact.key_remote_id = user.key_remote_id and\n";
            $sql .= "user.email = $email and user.password = '$password'\n";
            $sql .= "and user.status in(1,4)\n";

            $result = $this->db->query($sql);

            if ($result->num_rows() > 0) {
                return $result->result_array()[0];
            }
        }

        return null;
    }


    function ValidWebSessionToken($WebSessionToken)
    {
        $sql = "select * from web_session where web_session.browser_token = '$WebSessionToken';\n";

        $this->talkall_admin = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), TRUE);

        $result = $this->talkall_admin->query($sql);

        return $result->num_rows() > 0 ? true : false;
    }


    function confirm($token)
    {
        $this->talkall_admin = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), TRUE);

        $sql = "
        SELECT
            user_valid.id_user_valid,
            user.key_remote_id,
            user.id_user,
            user.email,
            company.db,
            company.server,
            user.id_company
        FROM user
        INNER JOIN user_valid ON user.id_user = user_valid.id_user
        INNER JOIN company ON user.id_company = company.id_company
        WHERE user_valid.token = '$token'
        AND user_valid.status = 3
        AND user.status IN (3, 4)
    ";

        $result = $this->talkall_admin->query($sql);

        if ($result->num_rows() > 0) {
            $userData = $result->row();

            $this->talkall_admin->set('status', 1);
            $this->talkall_admin->where('id_user', (int) $userData->id_user);
            $this->talkall_admin->update('user');

            $this->talkall_admin->set('status', 1);
            $this->talkall_admin->where('id_user', (int) $userData->id_user);
            $this->talkall_admin->update('user_valid');

            $this->db = $this->load->database(SetdatabaseRemote($userData->db, $userData->server), TRUE);

            $this->db->set('status', 1);
            $this->db->where('key_remote_id', $userData->key_remote_id);
            $this->db->update('user');

            $queryLang = $this->db
                ->select('language')
                ->from('user')
                ->where('key_remote_id', $userData->key_remote_id)
                ->get();

            $language = ($queryLang->num_rows() > 0) ? $queryLang->row()->language : 'pt_br';

            $data["title"] = "Senha";
            $data["password"] = "Talkall@123";
            $data["input-email"] = $userData->email;
            $data["sector_language"] = $language;

            $url = base_url() . "/";
            $view = "user_access_" . $language;

            $this->SendEmail($data, $view, $url);

            return true;
        } else {
            return false;
        }
    }



    public function setUserSession($user)
    {
        $user['picture']  = $this->GetProfilePicture($user['key_remote_id']);
        //$user['lang'] = $this->GetLang();

        $this->load->model('Notification_model', '', TRUE);
        if ($this->Notification_model->CheckNotifications($user['key_remote_id'])) {
            $user['notify'] = true;
        }

        $this->RevokeRecoveryPassword($user['key_remote_id']);

        $this->session->set_userdata($user);

        CreateUserLog($this->db->database, $this->db->hostname, 'LOGIN_ACCESS_SUCCESS', $user['id_user']);
    }


    function blockUser2fa($user_key_remote_id)
    {
        $id_user = 0;
        $id_user_talkall_admin = 0;

        $this->db = $this->load->database(SetdatabaseRemote('talkall_admin', "192.168.190.40"), TRUE);

        $sql = "SELECT 
                    user.key_remote_id, company.db, company.server, user.id_user
                FROM
                    talkall_admin.user
                        INNER JOIN
                    company ON user.id_company = company.id_company
                WHERE
                    user.key_remote_id = $user_key_remote_id AND company.status = 1 AND user.status = 1";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {

            $db = $result->result_array()[0]["db"];
            $host = $result->result_array()[0]["server"];
            $id_user_talkall_admin = $result->result_array()[0]["id_user"];
            $key_remote_id = $result->result_array()[0]["key_remote_id"];

            $this->db = $this->load->database(SetdatabaseRemote($db, $host), TRUE);

            $sql = "SELECT 
                        user.id_user
                    FROM
                        user
                    WHERE
                        user.key_remote_id = '$key_remote_id' AND user.status = 1";

            $result = $this->db->query($sql);

            if ($result->num_rows() > 0) {
                $id_user = $result->result_array()[0]['id_user'];
            }
        } else {
            return null;
        }

        $this->db->set('status', 4);
        $this->db->set('login_retry', 3);
        $this->db->where('id_user', $id_user);
        $this->db->update('user');

        $this->talkall_admin = $this->load->database(SetdatabaseRemote('talkall_admin', "192.168.190.40"), TRUE);
        $this->talkall_admin->set('status', 4);
        $this->talkall_admin->where('id_user', $id_user_talkall_admin);
        $this->talkall_admin->update('user');
    }

    //Enviar sms tela de segurança de usuário//
    function ConfirmPhone($phone, $email, $user_key_remote_id, $resend)
    {
        if ($resend == "true") {

            $sql = "SELECT 
                        user_2fa.code,
                        user_2fa.id_user_2fa,
                        user_2fa.resend_code
                    FROM
                        user_2fa
                    WHERE
                        user_2fa.user_key_remote_id = '$user_key_remote_id' AND user_2fa.is_expired = 0 
                    ORDER BY user_2fa.id_user_2fa DESC LIMIT 1";

            $result = $this->db->query($sql);

            if ($result->num_rows() > 0) {

                $code = $result->result_array()[0]["code"];
                $id_user_2fa = $result->result_array()[0]["id_user_2fa"];
                $resend_code = (int)$result->result_array()[0]["resend_code"];
                $id_company = $this->session->userdata('id_company');

                $this->db->set('resend_code', $resend_code + 1);
                $this->db->set('expire', date('Y-m-d H:i:s', strtotime('+5 minutes')));
                $this->db->set('is_add_2fa', 1);
                $this->db->where('id_user_2fa', $id_user_2fa);
                $this->db->update('user_2fa');

                $this->load->model('Sms_model', '', TRUE);

                if ($this->Sms_model->SendSms($id_company, $phone, "Seu código Talkall é {$code}")) {
                    $this->db->set('sms_send', 1);
                    $this->db->where('id_user_2fa', $id_user_2fa);
                    $this->db->update('user_2fa');
                };

                if (($resend_code + 1) >= 5) {
                    return "LOGIN_EXPIRED_CODE_ATTEMPTS";
                } else {
                    return true;
                }
            }
        } else {

            $data = array(
                'code' => mt_rand(100000, 999999),
                'user_key_remote_id' => $user_key_remote_id,
                'is_add_2fa' => 1,
                'expire' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
                'retry' => 0
            );

            $this->db->insert('user_2fa', $data);

            $insert_id = $this->db->insert_id();
            $id_company = $this->session->userdata('id_company');

            if ($phone != null) {

                $this->load->model('Sms_model', '', TRUE);

                if ($this->Sms_model->SendSms($id_company, $phone, "Seu código Talkall é {$data['code']}")) {
                    $this->db->set('sms_send', 1);
                    $this->db->where('id_user_2fa', $insert_id);
                    $this->db->update('user_2fa');
                };
                return true;
            }

            if ($email != null) {
                //TODO: ADD EMAIL REQUEST
            }
        }
    }


    function Remove2fa($key_remote_id)
    {
        $this->db->set('2fa', 0);
        $this->db->where('key_remote_id', $key_remote_id);
        $this->db->update('user');
    }


    //Enviar sms tela de login//
    function RequestTwofa($phone, $email, $user_key_remote_id, $resend)
    {
        if ($resend) {

            $sql = "SELECT 
                        user_2fa.code,
                        user_2fa.id_user_2fa,
                        user_2fa.resend_code
                    FROM
                        user_2fa
                    WHERE
                        user_2fa.user_key_remote_id = '$user_key_remote_id' AND user_2fa.is_expired = 0 
                    ORDER BY user_2fa.id_user_2fa DESC LIMIT 1";

            $result = $this->db->query($sql);

            if ($result->num_rows() > 0) {

                $code = $result->result_array()[0]["code"];
                $id_user_2fa = $result->result_array()[0]["id_user_2fa"];
                $resend_code = (int)$result->result_array()[0]["resend_code"];
                $id_company = $this->session->userdata('id_company');

                $this->db->set('resend_code', $resend_code + 1);
                $this->db->set('expire', date('Y-m-d H:i:s', strtotime('+5 minutes')));
                $this->db->set('is_add_2fa', 2);
                $this->db->where('id_user_2fa', $id_user_2fa);
                $this->db->update('user_2fa');

                $this->load->model('Sms_model', '', TRUE);

                if ($this->Sms_model->SendSms($id_company, $phone, "Seu código Talkall é {$code}")) {
                    $this->db->set('sms_send', 1);
                    $this->db->where('id_user_2fa', $id_user_2fa);
                    $this->db->update('user_2fa');
                };

                if (($resend_code + 1) >= 5) {
                    return "LOGIN_EXPIRED_CODE_ATTEMPTS";
                } else {
                    return true;
                }
            }
        } else {

            $sql = "SELECT 
                        COUNT(user_2fa.id_user_2fa) count
                    FROM
                        user_2fa
                    WHERE
                        user_2fa.user_key_remote_id = '$user_key_remote_id' AND DATE(user_2fa.creation) = CURRENT_DATE()
                    AND user_2fa.is_add_2fa != 1;";

            $result = $this->db->query($sql);

            $qtde = (int)$result->result_array()[0]["count"];

            if ($qtde >= 4) {
                return "LOGIN_EXPIRED_CODE_ATTEMPTS";
            } else {

                $data = array(
                    'code' => mt_rand(100000, 999999),
                    'user_key_remote_id' => $user_key_remote_id,
                    'is_add_2fa' => 2,
                    'expire' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
                    'retry' => 0
                );

                $this->db->insert('user_2fa', $data);

                $insert_id = $this->db->insert_id();
                $id_company = $this->session->userdata('id_company');

                if ($phone != null) {

                    $this->load->model('Sms_model', '', TRUE);

                    if ($this->Sms_model->SendSms($id_company, $phone, "Seu código Talkall é {$data['code']}")) {
                        $this->db->set('sms_send', 1);
                        $this->db->where('id_user_2fa', $insert_id);
                        $this->db->update('user_2fa');
                    };
                    return true;
                }

                if ($email != null) {
                    //TODO: ADD EMAIL REQUEST
                }
            }
        }

        return false;
    }


    function CheckTwofaAdd($code, $user_key_remote_id)
    {
        $user_key_remote_id = $this->db->escape($user_key_remote_id);

        $sql = "select user.key_remote_id, user.email, user.id_user, user.password, user.phone, user.name, user.2fa, user_2fa.* \n";
        $sql .= "from user_2fa\n";
        $sql .= "inner join user on user.key_remote_id = user_2fa.user_key_remote_id\n";
        $sql .= "where user_key_remote_id = $user_key_remote_id \n";
        $sql .= "and is_expired = 0 \n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {

            $last = $result->last_row();
            $id = $last->id_user_2fa;

            $result  = $result->result_array();
            $user_2fa = array();
            $date = date('Y-m-d H:i:s');

            //disable code not utilizable
            foreach ($result as $row) {
                if ((int)$row['id_user_2fa'] != $id) {
                    $this->db->set('is_expired', 1);
                    $this->db->where('id_user_2fa', $row['id_user_2fa']);
                    $this->db->update('user_2fa');
                } else {
                    $user_2fa = $row;
                }
            }

            //check expire
            if ($date > $user_2fa['expire']) {
                $this->db->set('is_expired', 1);
                $this->db->where('id_user_2fa', $user_2fa['id_user_2fa']);
                $this->db->update('user_2fa');

                if ($code === $user_2fa['code'] && (int)$user_2fa['retry'] <= 3) {
                    return false;
                }
                return false;
            }

            //check code
            if ($code === $user_2fa['code'] && (int)$user_2fa['retry'] <= 3) {

                $this->db->set('is_expired', '1');
                $this->db->where('id_user_2fa', $user_2fa['id_user_2fa']);
                $this->db->update('user_2fa');

                $this->db->set('2fa', 1);
                $this->db->where('id_user', $user_2fa['id_user_2fa']);
                $this->db->update('user');

                $this->session->mark_as_temp(array('user_2fa', $user_2fa), 300);
                return true;
            }
        }

        return false;
    }


    public function CheckTwofa($code, $user_key_remote_id)
    {
        $data["qtde"] = 0;
        $data["2fa"] = false;
        $data["block"] = false;
        $data["error"] = false;
        $data["expired_code"] = false;

        $user_key_remote_id = $this->db->escape($user_key_remote_id);

        # prevent sql injection
        $sql = "select user.key_remote_id, user.email, user.id_user, user.password, user.phone, user.name, user.2fa, user_2fa.* \n";
        $sql .= "from user_2fa\n";
        $sql .= "inner join user on user.key_remote_id = user_2fa.user_key_remote_id\n";
        $sql .= "where user_key_remote_id = $user_key_remote_id \n";
        $sql .= "and is_expired = 0 \n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {

            $last = $result->last_row();
            $id = $last->id_user_2fa;

            $result  = $result->result_array();
            $user_2fa = array();
            $date = date('Y-m-d H:i:s');

            //disable code not utilizable
            foreach ($result as $row) {
                if ((int)$row['id_user_2fa'] != $id) {
                    $this->db->set('is_expired', 1);
                    $this->db->where('id_user_2fa', $row['id_user_2fa']);
                    $this->db->update('user_2fa');
                } else {
                    $user_2fa = $row;
                }
            }

            if ($user_2fa == null) {
                return $data;
            }

            //check expire
            if ($date > $user_2fa['expire']) {
                $this->db->set('is_expired', 1);
                $this->db->where('id_user_2fa', $user_2fa['id_user_2fa']);
                $this->db->update('user_2fa');

                if ($code === $user_2fa['code'] && (int)$user_2fa['retry'] <= 3) {
                    $data["expired_code"] = true;
                    return $data;
                }

                $data["expired_code"] = true;
                return $data;
            }

            //check code
            if ($code === $user_2fa['code'] && (int)$user_2fa['retry'] <= 3) {

                $this->db->set('is_expired', '1');
                $this->db->where('id_user_2fa', $user_2fa['id_user_2fa']);
                $this->db->update('user_2fa');

                $this->db->set('2fa', 1);
                $this->db->where('id_user', $user_2fa['id_user_2fa']);
                $this->db->update('user');

                $this->session->mark_as_temp(array('user_2fa', $user_2fa), 300);

                $data["2fa"] = true;

                return $data;
            } else {

                CreateUserLog($this->db->database, $this->db->hostname, 'LOGIN_TWO_FACTOR_AUTHENTICATION_FAILED', $user_2fa['id_user']);

                //check by retry
                if (((int)$user_2fa['retry'] + 1) === 3) {
                    $this->db->set('is_expired', 1);
                    $this->db->set('retry', ((int)$user_2fa['retry'] + 1));
                    $this->db->where('id_user_2fa', $user_2fa['id_user_2fa']);
                    $this->db->update('user_2fa');

                    $this->blockUser2fa($user_key_remote_id);
                    CreateUserLog($this->db->database, $this->db->hostname, 'LOGIN_USER_BLOCK_TWO_FACTOR', $user_2fa['id_user']);

                    $data["block"] = true;

                    return $data;
                }

                $this->db->set('retry', ((int)$user_2fa['retry'] + 1));
                $this->db->where('id_user_2fa', $user_2fa['id_user_2fa']);
                $this->db->update('user_2fa');

                $data["qtde"] = (int)$user_2fa['retry'] + 1;

                return $data;
            }
        }

        $data["error"] = true;
        return $data;
    }


    private function get_line_in_language($key, $lang_code)
    {
        $original_lang = $this->session->userdata('language');
        $this->lang->load('user', $lang_code);
        $line = $this->lang->line($key);
        $this->lang->load('user', $original_lang);
        return $line;
    }


    function SendEmail($data, $view, $url)
    {

        $email = trim($data["input-email"]);

        $subject = $this->get_line_in_language('email_subject_confirmation', $data['sector_language']);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://services.talkall.com.br:4004/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
						"to": "' . $email . '",
						"subject": "' . $subject . '",
						"template": "' . $view . '",
						"context": {
							"hostname": "https://app.talkall.com.br/",
							"url": "' . $url . '",
							"email": "' . $email . '"
						}

					}',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $this->output->set_status_header(200);
    }


    function EmailExist($email)
    {
        $this->SetTalkall();

        $sql = "select * from talkall_admin.user where talkall_admin.user.email = '" . $email . "' and ( talkall_admin.user.status = 1 or talkall_admin.user.status = 3 );";

        $result = $this->db->query($sql);

        $this->SetClient();

        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }


    function DeleteUser($data)
    {
        $sql = "select *\n";
        $sql .= "from user\n";
        $sql .= "where user.id_user = " . $data["id_user"] . "\n";
        $sql .= "limit 1\n";

        $result = $this->db->query($sql);

        if ($data["id_transfer"] != 0) {

            $sql = "select *\n";
            $sql .= "from user\n";
            $sql .= "where user.id_user = " . $data["id_transfer"] . "\n";
            $sql .= "and user.status = 1\n";
            $sql .= "limit 1\n";

            $rest = $this->db->query($sql);

            $key_remote_id_transfer = $rest->row()->key_remote_id;
            $id_user_group_transfer = $rest->row()->id_user_group;

            $id_contact = "";

            $sql = "select * from contact where contact.user_key_remote_id = '{$result->row()->key_remote_id}'\n";
            $result_resposible = $this->db->query($sql);

            if ($result_resposible->num_rows() > 0) {

                for ($i = 0; $i < $result_resposible->num_rows(); $i++) {

                    $this->db->where('id_contact', $result_resposible->result_array()[$i]['id_contact']);
                    $this->db->update('contact', array('user_key_remote_id' => $key_remote_id_transfer, 'id_user_group' => $id_user_group_transfer));

                    $id_contact .= $i > 0 ? "," . $result_resposible->result_array()[$i]['id_contact'] : $result_resposible->result_array()[$i]['id_contact'];
                }

                $aContact = array();
                $aContact['id_contact'] = $id_contact;
                $aContact['action'] = "DELETE_USER";
                $aContact['to_from_key_remote_id'] = $key_remote_id_transfer;

                $log_user = json_encode($aContact);
            }
        } else {

            $sql = "select contact.id_contact, channel.id_user_group\n";
            $sql .= "from contact \n";
            $sql .= "inner join channel on contact.id_channel = channel.id_channel\n";
            $sql .= "where contact.user_key_remote_id = '{$result->row()->key_remote_id}'\n";
            $response = $this->db->query($sql);


            for ($i = 0; $i < $response->num_rows(); $i++) {

                $this->db->where('id_contact', $response->result_array()[$i]['id_contact']);
                $this->db->update('contact', array('user_key_remote_id' => "0", 'id_user_group' => $response->result_array()[$i]['id_user_group']));
            }
        }


        if ($result->num_rows() > 0) {

            $this->db->where('key_remote_id', $result->row()->key_remote_id);
            $this->db->update('contact', array('deleted' => 2));

            $this->db->where('email', $result->row()->email);
            $this->db->update('user', array('status' => 2));

            $this->db->where('id', $result->row()->key_remote_id);
            $this->db->update('channel', array('status' => 2));

            $this->db->where('id', "kanban-" . $result->row()->key_remote_id);
            $this->db->update('channel', array('status' => 2));

            $this->db->where('id', "app-" . $result->row()->key_remote_id);
            $this->db->update('channel', array('status' => 2));

            $this->db->where('id', "connect-" . $result->row()->key_remote_id);
            $this->db->update('channel', array('status' => 2));

            $this->db->where('key_remote_id', $result->row()->key_remote_id);
            $this->db->delete('group_participants');

            $this->talkall_admin = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), TRUE);

            $this->talkall_admin->where('email', $result->row()->email);
            $this->talkall_admin->update('user', array('status' => 2));

            $this->talkall_admin->where('key_remote_id', $result->row()->key_remote_id);
            $this->talkall_admin->delete('web_session');

            $this->talkall_admin->where('id', $result->row()->key_remote_id);
            $this->talkall_admin->update('channel', array('status' => 2));

            $this->talkall_admin->where('id', "kanban-" . $result->row()->key_remote_id);
            $this->talkall_admin->update('channel', array('status' => 2));

            $this->talkall_admin->where('id', "app-" . $result->row()->key_remote_id);
            $this->talkall_admin->update('channel', array('status' => 2));

            $this->talkall_admin->where('id', "connect-" . $result->row()->key_remote_id);
            $this->talkall_admin->update('channel', array('status' => 2));

            CreateUserLog($this->db->database, $this->db->hostname, 0, $this->session->userdata('id_user'), $log_user);
        }
    }


    function List()
    {
        $sql = "select\n";
        $sql .= "user.id_user,\n";
        $sql .= "user.key_remote_id,\n";
        $sql .= "user.last_name,\n";
        $sql .= "id_user_group,\n";
        $sql .= "user.name from user\n";
        $sql .= "where user.status = 1\n";
        $sql .= "order by user.name\n";

        $result = $this->db->query($sql);

        $data = $result->result_array();

        return $data;
    }


    function GetUserById($id)
    {
        $sql = "SELECT 
                    user.id_user,
                    DATE_FORMAT(from_unixtime(user.creation),'%d/%m/%Y') as creation,
                    user.id_permission,
                    id_user_call,
                    id_user_group,
                    user.key_remote_id,
                    user.name,
                    user.last_name,
                    user.email,
                    user.profile_picture,
                    user.visible, 
                    user.status,
                    user.id_work_time,
                    user.attendance_available,
                    user.visible_widget,
                    user.language,
                    user.2fa
                FROM
                    user 
                WHERE user.id_user = {$id}\n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            $row = $result->result_array()[0];
            return $row;
        } else {
            $error = 'Usúario Não encontrado';
            throw new Exception($error);
        }

        return $result->result_array();
    }


    function GetUserByKeyRemoteId($key_remote_id)
    {
        $sql = "select *\n";
        $sql .= "from user\n";
        $sql .= "where user.key_remote_id = '{$key_remote_id}'\n";
        $sql .= "limit 1\n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            $row = $result->result_array()[0];
            return $row;
        } else {
            $error = 'Usúario Não encontrado';
            throw new Exception($error);
        }

        return $result->result_array();
    }


    function GetUserByEmail($email)
    {
        $sql = "select\n";
        $sql .= "company.id_company,\n";
        $sql .= "company.db,\n";
        $sql .= "company.server\n";
        $sql .= "from company inner join user on company.id_company = user.id_company\n";
        $sql .= "where company.status = 1 and user.status in(1,4) \n";
        $sql .= "and user.email = '{$email}' \n";

        $this->talkall_admin = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), TRUE);
        $result = $this->talkall_admin->query($sql);

        if ($result->num_rows() > 0) {

            $this->db = $this->load->database(SetdatabaseRemote($result->row()->db, $result->row()->server), TRUE);

            $sql = "select\n";
            $sql .= "'{$result->row()->id_company}' id_company,\n";
            $sql .= "user.language,\n";
            $sql .= "user.key_remote_id\n";
            $sql .= "from  user\n";
            $sql .= "where user.email = '{$email}'\n";
            $sql .= "and user.status in(1,4)\n";

            $result = $this->db->query($sql);

            if ($result->num_rows() > 0) {
                return $result->result_array()[0];
            }
        }

        return $result->result_array();
    }


    function GetByToken($key_remote_id)
    {
        $sql = "select\n";
        $sql .= "user.id_user,\n";
        $sql .= "user.language,\n";
        $sql .= "user.key_remote_id,\n";
        $sql .= "user.email\n";
        $sql .= "from user\n";
        $sql .= "where user.status in (1,4) and user.key_remote_id = '{$key_remote_id}' limit 1";

        $result = $this->db->query($sql);

        $data = $result->result_array();

        if ($result->num_rows() > 0) {
            $data = $result->result_array();
            return $data[0];
        } else {
            return null;
        }
    }


    function SetPasswordRecovery($origin_key_remote_id, $key_remote_id, $id_company)
    {
        $date = new DateTime();
        $expire = new DateTime();
        $expire->modify('+2 hours');

        $values = [
            'creation' => $date->getTimestamp(),
            'id_company' => $id_company,
            'expire' => $expire->getTimestamp(),
            'token' => Token(),
            'origin_key_remote_id' => $origin_key_remote_id,
            'key_remote_id' => $key_remote_id,
            'status' => 1
        ];

        // caso o maldito ficar pedindo varios emails invalido os anteriores //

        $this->talkall_admin = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), TRUE);
        $this->talkall_admin->where(array('key_remote_id' => $key_remote_id, 'status' => 1));
        $this->talkall_admin->set('status', 2);
        $this->talkall_admin->update('user_reset_password');
        $this->talkall_admin->insert('user_reset_password', $values);

        return $values;
    }


    function GetPasswordRecovery($token)
    {
        $sql = "select\n";
        $sql .= "*\n";
        $sql .= "from talkall_admin.user_reset_password\n";
        $sql .= "where talkall_admin.user_reset_password.status = 1 and talkall_admin.user_reset_password.token = '{$token}' limit 1";

        $this->talkall_admin = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), TRUE);
        $result = $this->talkall_admin->query($sql);
        $date = new DateTime();

        if ($result->num_rows() > 0) {
            $data = $result->result_array();
            //expire
            if ((int) $date->getTimestamp() > (int) $data[0]['expire']) {

                $this->talkall_admin->where(array('id_user_reset_password' => (int) $data[0]['id_user_reset_password'], 'status' => 1));
                $this->talkall_admin->set('status', 2);
                $this->talkall_admin->update('user_reset_password');

                return null;
            }
            return $data[0];
        } else {
            return null;
        }
    }


    function SetNewPassword($key_remote_id, $password)
    {
        $sql = "select\n";
        $sql .= "talkall_admin.company.db,\n";
        $sql .= "talkall_admin.company.server\n";
        $sql .= "from talkall_admin.company inner join talkall_admin.channel on talkall_admin.company.id_company = talkall_admin.channel.id_company\n";
        $sql .= "where talkall_admin.channel.id = '{$key_remote_id}' and talkall_admin.channel.status = 1";

        $this->db = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), TRUE);

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {

            $banco = $result->row()->db;
            $host = $result->row()->server;

            $sql = "select\n";
            $sql .= "user.id_user,\n";
            $sql .= "user.email\n";
            $sql .= "from user\n";
            $sql .= "where user.status in(1,4) and user.key_remote_id = '{$key_remote_id}'\n";

            $this->db = $this->load->database(SetdatabaseRemote($banco, $host), TRUE);

            $result = $this->db->query($sql);

            if ($result->num_rows() > 0) {

                $this->db->where(array('key_remote_id' => $key_remote_id));
                $this->db->set('status', 1);
                $this->db->set('login_retry', 0);
                $this->db->set('password', md5($password));
                $this->db->update('user');

                $user = $this->GetByToken($key_remote_id);

                $this->talkall_admin = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), TRUE);

                //UPDATE PASSWORD
                $this->talkall_admin->where(array('key_remote_id' => $key_remote_id));
                $this->talkall_admin->set('password', md5($password));
                $this->talkall_admin->set('status', "1");
                $this->talkall_admin->update('user');

                $this->talkall_admin->where(array('key_remote_id' => $key_remote_id));
                $this->talkall_admin->delete('web_session');

                $this->db = $this->load->database(SetdatabaseRemote($banco, $host), TRUE);

                //inativa o token após senha alterada//
                $this->talkall_admin->where(array('key_remote_id' => $key_remote_id, 'status' => 1));
                $this->talkall_admin->set('status', 2);
                $this->talkall_admin->update('user_reset_password');

                CreateUserLog($banco, $host, 'LOGIN_CHANGE_PASSWORD', $user['id_user']);
            }
        }
    }

    function SetPasswordDefault($key_remote_id)
    {
        $sql = "select\n";
        $sql .= "talkall_admin.company.db,\n";
        $sql .= "talkall_admin.company.server\n";
        $sql .= "from talkall_admin.company inner join talkall_admin.channel on talkall_admin.company.id_company = talkall_admin.channel.id_company\n";
        $sql .= "where talkall_admin.channel.id = '{$key_remote_id}'";

        $result = $this->db->query($sql);
        $db = $result->result_array()[0]['db'];
        $host = $result->result_array()[0]['server'];

        if ($result->num_rows() > 0) {

            $sql = "select\n";
            $sql .= "user.id_user,\n";
            $sql .= "user.email\n";
            $sql .= "from user\n";
            $sql .= "where user.status = 1 and user.key_remote_id = '{$key_remote_id}'\n";

            $this->db = $this->load->database(SetdatabaseRemote($db, $host), TRUE);

            $result = $this->db->query($sql);

            if ($result->num_rows() > 0) {

                $this->db->where(array('key_remote_id' => $key_remote_id));
                $this->db->set('password', $this->config->item('default_password'));
                $this->db->update('user');

                $user = $this->GetByToken($key_remote_id);

                $this->talkall_admin = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), TRUE);

                //UPDATE PASSWORD
                $this->talkall_admin->where(array('email' => $result->row()->email));
                $this->talkall_admin->set('password', $this->config->item('default_password'));
                $this->talkall_admin->update('user');

                CreateUserLog($this->db->database, $this->db->hostname, 'LOGIN_CHANGE_DEFAULT_INTRANET', $user['id_user']);
            }
        }
    }


    function RevokeRecoveryPassword($key_remote_id)
    {
        $this->talkall_admin = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), TRUE);
        //INVALID TOKEN OF RESET DE PASSWORD 
        $this->talkall_admin->where(array('key_remote_id' => $key_remote_id, 'status' => 1));
        $this->talkall_admin->set('status', 2);
        $this->talkall_admin->update('user_reset_password');
    }


    function GetAllUserLog($id_user, $start = 0, $length = 10)
    {
        $id_user = $this->db->escape($id_user);

        $sql = "SELECT 
                    DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(user_log.creation), INTERVAL config.timezone HOUR),'%d/%m/%Y %T') date,
                    CONCAT(user_log.agent, ' - ', user_log.version) browser,
                    user_log.text,
                    user_log.system,
                    user_log.ip
                FROM
                    user_log
                INNER JOIN
                    user ON user_log.id_user = user.id_user
                INNER JOIN
                    channel ON user.key_remote_id = channel.id
                INNER JOIN
                    config ON channel.id_channel = config.id_channel
                WHERE
                   user_log.id_user = $id_user
                ORDER BY user_log.id_user_log DESC LIMIT $start, $length";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
    }


    function GetUserLoginLog($id_user, $start = 0, $length = 2)
    {
        $id_user = $this->db->escape($id_user);

        $sql = "SELECT 
                    DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(user_log.creation), INTERVAL config.timezone HOUR),'%d/%m/%Y %T') AS date,
                    user_log.agent,
                    user_log.version,
                    user_log.system,
                    user_log.ip
                FROM
                    user_log
                    INNER JOIN
                    user ON user_log.id_user = user.id_user
                INNER JOIN
                    channel ON user.key_remote_id = channel.id
                INNER JOIN
                    config ON channel.id_channel = config.id_channel
                WHERE
                    user_log.id_user = $id_user AND (user_log.text = 'login' or user_log.text = 'login in' or user_log.text = 'LOGIN_ACCESS_SUCCESS')  
                ORDER BY user_log.id_user_log DESC LIMIT $start, $length";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
    }


    function CountAllUserLog($id_user)
    {
        $id_user = $this->db->escape($id_user);

        $sql = "SELECT count(id_user_log) as total FROM user_log where id_user = $id_user\n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            return (int) $result->result_array()[0]['total'];
        }
    }


    function SetTwoFa($key_remote_id, $setTwoFa)
    {
        $data = "";

        if ($setTwoFa === "true") {

            $data = "active";
            $this->db->set('2fa', 1);
        } else {

            $data = "inactive";
            $this->db->set('2fa', 0);
        }

        $this->db->where('key_remote_id', $key_remote_id);
        $this->db->update('user');

        return $data;
    }

    function checkPhone($key_remote_id, $phone)
    {
        $sql = "SELECT user.phone FROM user WHERE user.key_remote_id = '$key_remote_id' AND user.status = 1";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            if ($phone == $result->result_array()[0]['phone']) {
                return true;
            } else {
                return false;
            }
        }
    }

    function SetUserPhone($phone, $key_remote_id)
    {
        $this->db->set('phone', $phone);
        $this->db->where('key_remote_id', $key_remote_id);
        $this->db->update('user');
    }

    function AutoComplete($text)
    {
        $sql = "SELECT
                    id_user as id,
                    email as text
                FROM
                    talkall_admin.user
                WHERE
                    email LIKE '%$text%'
                ORDER BY
                    user.email
                LIMIT 20";

        $dbOld = $this->db;

        $this->db = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), TRUE);

        $query = $this->db->query($sql)->result_array();

        $this->db = $dbOld;

        return $query;
    }

    function CheckTel($tel, $code = null)
    {
        $this->db = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), TRUE);

        $this->db->select('
            user.key_remote_id,
            user.email,
            user.sms_code
        ');

        $this->db->from('user');
        $this->db->where('user.status', 1);
        $this->db->like('user.phone', $this->db->escape_like_str($tel));

        if ($code != null) {
            $this->db->where('sms_code', $code);
        }

        $query = $this->db->get('');

        return $query->result_array();
    }

    function InsertVerifyCode($code, $id)
    {
        $this->db = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), TRUE);
        $this->db->set('user.sms_code', $code);
        $this->db->where('user.key_remote_id', $id);
        $this->db->update('user');
    }

    function VerifyCode($code, $tel)
    {
        $this->db = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), TRUE);

        $this->db->set('sms_verified', 1);
        $this->db->where('user.phone', $tel);
        $this->db->where('user.sms_code', $code);
        $this->db->where('user.sms_verified <> 1');
        $this->db->update('user');
    }

    function ListDepartment()
    {
        $this->db->select('
            user_group.id_user_group,
            user_group.name
        ');

        $this->db->from('user_group');
        $this->db->where('user_group.status', 1);

        $query = $this->db->get('');

        return $query->result_array();
    }

    function ListUser()
    {
        $this->db->select('
            user.id_user,
            user.name
        ');

        $this->db->from('user');
        $this->db->where('user.status', 1);
        $this->db->order_by('user.name', 'desc');

        $query = $this->db->get('');

        return $query->result_array();
    }

    function GetTokenUserValid($key_remote_id)
    {
        $this->db = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), TRUE);

        $this->db->select(
            'user_valid.token'
        );
        $this->db->from('user');
        $this->db->join('user_valid', 'user.id_user = user_valid.id_user');
        $this->db->where('user.key_remote_id', $key_remote_id);

        $query = $this->db->get('');

        return $query->row()->token;
    }

    function blockUser($id_user, $id_user_talkall_admin)
    {
        $this->db->set('status', 4);
        $this->db->where('id_user', $id_user);
        $this->db->update('user');

        $this->talkall_admin = $this->load->database(SetdatabaseRemote('talkall_admin', "192.168.190.40"), TRUE);
        $this->talkall_admin->set('status', 4);
        $this->talkall_admin->where('id_user', $id_user_talkall_admin);
        $this->talkall_admin->update('user');
    }


    function valid_access($id_user)
    {
        $this->db->select('
            user_log.text,
            user.status,
            user.login_retry
        ');

        $this->db->from('user_log');
        $this->db->join('user', 'user_log.id_user = user.id_user');
        $this->db->where('user_log.id_user', $id_user);
        $this->db->order_by('user_log.id_user_log', 'DESC');
        $this->db->limit(1);

        $query = $this->db->get('');

        if ($query->num_rows() > 0) {

            $status = $query->result_array()[0]["status"];
            $login_user_block = $query->result_array()[0]["text"];
            $login_retry = $query->result_array()[0]["login_retry"];

            if ($login_user_block == "LOGIN_USER_BLOCK" || $login_user_block == "LOGIN_USER_BLOCK_TWO_FACTOR" || $status == "4" || $login_retry == "3") {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    function setLoginRetry($id_user)
    {
        $isErrorToday = false;

        $this->db->select('
            user_log.text
        ');
        $this->db->from('user_log');
        $this->db->where('DATE_FORMAT(FROM_UNIXTIME(user_log.creation),  "%Y-%m-%d") = CURRENT_DATE()');
        $this->db->where('user_log.id_user', $id_user);
        $this->db->order_by('user_log.id_user_log', 'desc');
        $this->db->limit(1);

        $query = $this->db->get('');

        if ($query->num_rows() > 0) {
            if ($query->result_array()[0]["text"] == "LOGIN_ERROR_PASSWORD") {
                $isErrorToday = true;
            }
        }

        if ($isErrorToday) {
            $qtdeRetry = $this->errorPassWord($id_user);
            $total = $qtdeRetry + 1;
            $this->db->set('login_retry', $total);
        } else {
            $this->db->set('login_retry', 1);
        }

        $this->db->where('id_user', $id_user);
        $this->db->update('user');
    }

    function resetLoginRetry($id_user)
    {
        $this->db->select('
            user.login_retry
        ');

        $this->db->from('user');

        $query = $this->db->get('');

        if ($query->result_array()[0]["login_retry"] != 0) {
            $this->db->set('login_retry', 0);
            $this->db->where('id_user', $id_user);
            $this->db->update('user');
        }
    }

    function errorPassWord($id_user)
    {
        $this->db->select('
            user.login_retry
        ');

        $this->db->from('user');
        $this->db->where('user.id_user', $id_user);

        $query = $this->db->get('');

        return $query->num_rows() > 0 ? $query->result_array()[0]["login_retry"] : null;
    }

    function setUserErrorPassWord($email)
    {
        $this->db = $this->load->database(SetdatabaseRemote('talkall_admin', "192.168.190.40"), TRUE);

        $sql = "SELECT 
                    user.key_remote_id, company.db, company.server, user.id_user
                FROM
                    talkall_admin.user
                        INNER JOIN
                    company ON user.id_company = company.id_company
                WHERE
                    user.email = '$email' AND company.status = 1 AND user.status IN(1,4)";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {

            $db = $result->result_array()[0]["db"];
            $host = $result->result_array()[0]["server"];
            $id_user_talkall_admin = $result->result_array()[0]["id_user"];
            $key_remote_id = $result->result_array()[0]["key_remote_id"];

            $this->db = $this->load->database(SetdatabaseRemote($db, $host), TRUE);

            $sql = "SELECT 
                        user.id_user
                    FROM
                        user
                    WHERE
                        user.email = '$email' AND user.key_remote_id = '$key_remote_id' AND user.status IN(1,4)";

            $result = $this->db->query($sql);

            if ($result->num_rows() > 0) {

                $id_user = $result->result_array()[0]['id_user'];

                $block_user = $this->valid_access($id_user);

                //não registra mais log caso usuário já esteja bloqueado//
                if ($block_user === false) {

                    $this->setLoginRetry($id_user);

                    CreateUserLog($db, $host, 'LOGIN_ERROR_PASSWORD', $id_user);

                    $qtdeError = $this->errorPassWord($id_user);
                    if ($qtdeError === "3") {
                        $this->blockUser($id_user, $id_user_talkall_admin);
                        CreateUserLog($db, $host, 'LOGIN_USER_BLOCK', $id_user);
                    }
                }
                return $id_user;
            }
        } else {
            return null;
        }
    }


    function Is_Blocking($id_user)
    {
        $this->db->select('
            permission.block_access_work_time
        ');

        $this->db->from('user');
        $this->db->join('permission', 'user.id_permission = permission.id_permission');
        $this->db->where('user.id_user', $id_user);
        $this->db->where('user.status', 1);

        $query = $this->db->get('');

        return $query->result_array()[0]["block_access_work_time"] == 1 ? true : false;
    }

    function GetUserWorkTime($id_user)
    {
        $this->db->select(
            '
            user.id_user,
            user.name,
            work_time.name,
            work_time_week.week,
            work_time_week.start,
            work_time_week.end'
        );

        $this->db->from('work_time');
        $this->db->join('work_time_week', 'work_time.id_work_time = work_time_week.id_work_time');
        $this->db->join('user', 'work_time.id_work_time = user.id_work_time');
        $this->db->join('contact', 'user.key_remote_id = contact.key_remote_id');
        $this->db->join('config', 'contact.id_channel = config.id_channek');
        $this->db->where('user.id_user', $id_user);
        $this->db->where('user.status', 1);
        $this->db->where('DATE_ADD(CONCAT(CURRENT_DATE(), " ", CURRENT_TIME), INTERVAL config.timezone HOUR) BETWEEN CONCAT(CURRENT_DATE(), " ",  work_time_week.start) AND CONCAT(CURRENT_DATE(), " ", work_time_week.end)');
        $this->db->where('WEEKDAY(CONCAT(CURRENT_DATE(), " ", CURRENT_TIME))+1 = work_time_week.week"');

        $query = $this->db->get('');

        return $query->num_rows() > 0 ? false : true;
    }

    function GetBrowserWebSession($key_remote_id)
    {
        parent::SetTalkall();

        $this->db->select('*');
        $this->db->from('web_session');
        $this->db->where('browser_token', $key_remote_id);

        $query = $this->db->get('');

        parent::SetClient();

        return $query->num_rows() > 0 ? $query->result_array()[0]['browser_token'] : false;
    }

    function getSupportPasswordCreatorKeyRemoteId($email)
    {
        $this->db = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), TRUE);

        $this->db->select('user_password_generator.user_key_remote_id');
        $this->db->from('user_password_generator');
        $this->db->where('email', $email);
        $this->db->order_by('id_upg', 'desc');
        $this->db->limit(1);

        $key_remote_id = $this->db->get()->result()[0]->user_key_remote_id;
        $database = $this->GetDatabaseByKeyRemoteId($email);

        $this->db = $this->load->database(SetdatabaseRemote($database["db"], $database["server"]), TRUE);

        return $key_remote_id;
    }

    function GetDatabaseByKeyRemoteId($email)
    {
        $this->db->select('company.db, company.server');
        $this->db->from('user');
        $this->db->join('company', 'user.id_company = company.id_company');
        $this->db->where('user.email', $email);
        $this->db->where('user.status', 1);

        return $this->db->get()->result_array()[0];
    }
}
