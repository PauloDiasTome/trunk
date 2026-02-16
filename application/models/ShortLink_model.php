<?php

class ShortLink_model extends TA_model
{

    function Get($text, $start, $length, $order_column, $order_dir)
    {
        $sql = "SELECT\n";
        $sql .= "short_link.id_short_link,\n";
        $sql .= "short_link.name,\n";
        $sql .= "CONCAT('https://whts.me/',short_link.link) link\n";
        $sql .= "FROM user INNER JOIN short_link on user.id_user = short_link.id_user\n";
        $sql .= "WHERE LOWER(short_link.name) like LOWER('%" . $text . "%') AND short_link.status = 1\n";

        switch ($order_column) {
            case 0:
                $sql .= "ORDER BY short_link.name $order_dir\n";
                break;
            case 1:
                $sql .= "ORDER BY short_link.link $order_dir\n";
                break;
        }

        $sql .= "LIMIT {$start},{$length}\n";

        $result = $this->db->query($sql);
        $data = $result->result_array();

        return $data;
    }


    function Count($text)
    {
        $sql = "SELECT COUNT(short_link.id_short_link) count\n";
        $sql .= "FROM short_link\n";
        $sql .= "WHERE LOWER(short_link.name) like LOWER('%" . $text . "%') AND short_link.status = 1\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }



    function Add($data)
    {
        $date = new DateTime();

        $values = [
            'creation' => $date->getTimestamp(),
            'message' => trim($data['input-message']),
            'id_user' => $data['select-user'],
            'name' => trim($data['input-name']),
            'phone' => trim($data['input-phone']),
            'link' => $data['input-link'],
            'title' => trim($data['input-title']),
            'description' => trim($data['input-description'])
        ];

        $this->db->insert('short_link', $values);

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);

        $values = [
            'creation' => $date->getTimestamp(),
            'id_company' => $this->session->userdata()['id_company'],
            'link' => $data['input-link']
        ];

        $this->talkall_admin->insert("link", $values);
    }


    function Edit($key_id, $data)
    {
        $values = [
            'message' => trim($data['input-message']),
            'name' => trim($data['input-name']),
            'id_user' => $data['select-user'],
            'phone' => trim($data['input-phone']),
            'link' => $data['input-link'],
            'title' => trim($data['input-title']),
            'description' => trim($data['input-description']),
            'status' => $data['input-status']
        ];

        $this->db->where('id_short_link', $key_id);
        $this->db->update('short_link', $values);

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);

        $values = [
            'link' => $data['input-link']
        ];

        $this->talkall_admin->where('link', $data['input-link']);
        $this->talkall_admin->update('link', $values);
    }


    function Delete($key_id)
    {
        $sql = "select\n";
        $sql .= "short_link.id_short_link,\n";
        $sql .= "short_link.link\n";
        $sql .= "from short_link\n";
        $sql .= "where short_link.id_short_link = '" . $key_id . "'\n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {

            $values = [
                'status' => 2
            ];


            $this->db->where('id_short_link', $result->row()->id_short_link);
            $this->db->update('short_link', $values);


            $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
            $this->talkall_admin->where("link", $result->row()->link);
            $this->talkall_admin->delete("link");
        }
    }


    function GetInf($id)
    {
        $sql = "select\n";
        $sql .= "short_link.id_short_link,\n";
        $sql .= "short_link.creation,\n";
        $sql .= "short_link.id_user,\n";
        $sql .= "short_link.link,\n";
        $sql .= "short_link.message,\n";
        $sql .= "short_link.description,\n";
        $sql .= "short_link.name,\n";
        $sql .= "short_link.phone,\n";
        $sql .= "short_link.title,\n";
        $sql .= "short_link.status\n";
        $sql .= "from short_link\n";
        $sql .= "where short_link.id_short_link = '" . $id . "';\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function GetLink($key_id)
    {
        $sql = "select\n";
        $sql .= "concat('https://whts.me/',short_link.link) link\n";
        $sql .= "from user inner join short_link on user.id_user = short_link.id_user\n";
        $sql .= "where short_link.id_short_link = '" . $key_id . "'\n";

        $result = $this->db->query($sql);

        $data = $result->result_array();

        return $data;
    }

    function GetBuildLink()
    {
        $link = $this->BuildLink();

        $exists = $this->CheckLinkExists($link);

        while ($exists) {
            $link = $this->BuildLink();
            $exists = $this->CheckLinkExists($link);
        }

        return $link;
    }

    function BuildLink()
    {
        $lmin = 'abcdefghijklmnopqrstuvwxyz';
        $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $num = '1234567890';
        $retorno = '';
        $caracteres = '';

        $caracteres .= $lmin;
        $caracteres .= $lmai;
        $caracteres .= $num;

        $len = strlen($caracteres);

        for ($n = 1; $n <= 4; $n++) {
            $rand = mt_rand(1, $len);
            $retorno .= $caracteres[$rand - 1];
        }

        return $retorno;
    }

    function CheckLinkExists($link)
    {
        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);

        $sql = "select\n";
        $sql .= "link\n";
        $sql .= "from link\n";
        $sql .= "where link = '" . $link . "'\n";

        $result = $this->talkall_admin->query($sql);

        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function List()
    {
        $sql = "select\n";
        $sql .= "short_link.name,\n";
        $sql .= "concat('https://whts.me/',short_link.link) link\n";
        $sql .= "from user inner join short_link on user.id_user = short_link.id_user AND status = 1\n";
        $sql .= "order by short_link.name\n";

        $result = $this->db->query($sql);
        $data = $result->result_array();

        return $data;
    }


    function Register($data)
    {
        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);

        $sql = "select id_contact from chat_list where id_chat_list = '" . $data['id_chat_list'] . "'\n";
        $result = $this->db->query($sql);

        $id_contact = $result->result_array()[0]['id_contact'];

        $sql = "select key_remote_id from contact where id_contact = '" . $id_contact . "'\n";
        $res = $this->db->query($sql);

        $key_remote_id = $res->result_array()[0]['key_remote_id'];

        $id_company = $_SESSION['id_company'];

        if (!empty($id_company)) {
            $sql = "select db, server from talkall_admin.company where id_company = '" . $id_company . "'\n";
            $res = $this->talkall_admin->query($sql);

            $date = new DateTime();
            $data['db'] = $res->result_array()[0]['db'];
            $data['host'] = $res->result_array()[0]['server'];
            $data['key_remote_id'] = $key_remote_id;
            $data['type'] = "data_short_link";
            $data['user_key_remote_id'] = $this->session->userdata('key_remote_id');

            $ci = &get_instance();

            $values = [
                'id_company' => $id_company,
                'creation' => $date->getTimestamp(),
                'banco' => $data['db'],
                'host' => $data['host'],
                'email' => $data['email'],
                'key_remote_id' => $data['key_remote_id'],
                'user_key_remote_id' => $data['user_key_remote_id'],
                'status' => 1,
                'type' => $data['type'],
                'ip' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => "{$ci->agent->browser()}, {$ci->agent->platform()}"
            ];

            $this->talkall_admin->insert('export_service', $values);

            return true;
        }
    }
}
