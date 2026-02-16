<?php

class Export_model extends TA_model
{

    function SaveExport($email, $type, $filter)
    {
        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);


        $id_company = $_SESSION['id_company'];

        $user_data = [
            "email" => $email,
            "type" => $type,
        ];

        $get_exports_for_user = $this->getExportsUser($user_data);

        if ($get_exports_for_user == 0) {
            return "Error";
        }

        if (!empty($id_company) && $get_exports_for_user == 1) {

            $sql = "select db, server from talkall_admin.company where id_company = '" . $id_company . "'\n";

            $res = $this->talkall_admin->query($sql);

            $date = new DateTime();
            $data['db'] = $res->result_array()[0]['db'];
            $data['host'] = $res->result_array()[0]['server'];
            $data['key_remote_id'] = "";
            $data['type'] = 'data_' . $type;
            $data['email'] = $email;
            $data['filter'] = $filter;
            $data['user_key_remote_id'] = $this->session->userdata('key_remote_id');

            $ci = &get_instance();

            $values = [
                'id_company' => $id_company,
                'creation' => $date->getTimestamp(),
                'banco' => $data['db'],
                'host' => $data['host'],
                'email' =>  $data['email'],
                'key_remote_id' => $data['key_remote_id'],
                'user_key_remote_id' => $data['user_key_remote_id'],
                'status' => 1,
                'type' => $data['type'],
                'filter' => $data['filter'],
                'ip' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => "{$ci->agent->browser()}, {$ci->agent->platform()}"
            ];

            $this->talkall_admin->insert('export_service', $values);

            return true;
        }
    }

    function getExportsUser($user_data)
    {
        $type = 'data_' . $user_data['type'];
        $this->talkall_admin->select("creation");
        $this->talkall_admin->from("export_service");
        $this->talkall_admin->where("email", $user_data['email']);
        $this->talkall_admin->where("type", $type);
        $this->talkall_admin->where("FROM_UNIXTIME(creation) >= NOW() - INTERVAL 1 HOUR");
        $result = $this->talkall_admin->get()->result_array();

        if (count($result) < 10) {
            return 1;
        } else {
            return 0;
        }
    }
}
