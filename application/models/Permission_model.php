<?php

class Permission_model extends TA_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->SetTableName("permission");      // Passa o valor para a tabela na TA_Model
        $this->SetPrimaryKey("id_permission");  // Passa o valor para a primary key na TA_Model
    }

    function Get($param)
    {
        $this->db->select('id_permission, name');
        $this->db->from('permission');
        $this->db->like('LOWER(name)', $this->db->escape_like_str(strtolower($param['text'])));
        $this->db->where('adm', 2);

        $count = $this->db->count_all_results('', false);

        switch ($param['order'][0]['column']) {
            case 0:
                $this->db->order_by('name', $param['order'][0]['dir']);
                break;
            default:
                break;
        }

        $this->db->limit($param['length'], $param['start']);

        return ['query' => $this->db->get()->result_array(), 'count' => $count];
    }

    function GetById($key_id)
    {
        return parent::_GetById($key_id);
    }

    function CheckUserPermissions($key_remote_id, $permission)
    {
        $row = $this->GetUserPermissions($key_remote_id);

        foreach ($row as $key => $value) {
            if ($key == $permission && $value == 1)
                return true;
        }
        return false;
    }

    function syncCompanyData()
    {
        $this->talkall_admin = $this->load->database(
            SetdatabaseRemote("talkall_admin", "192.168.190.40"),
            TRUE
        );

        $this->talkall_admin
            ->select('company.is_trial, company.creation, company.attendance, company.communication, company.trial_days')
            ->from('company')
            ->where('company.id_company', $this->session->userdata('id_company'));

        $query = $this->talkall_admin->get();

        if ($query->num_rows() === 0) {
            return;
        }

        $company = $query->row();

        $adjusted_creation = (int) $company->creation - (3 * 3600);
        $adjusted_current  = time() - (3 * 3600);

        $days_since_creation = floor(
            ($adjusted_current - $adjusted_creation) / 86400
        );

        if ($company->communication == 1) {
            $trial_days = (int) $company->trial_days;
            if ($trial_days <= 0) {
                $trial_days = 14;
            }
        } else {
            $trial_days = 14;
        }

        $is_in_trial_period = ($company->is_trial == 1 &&
            $days_since_creation < $trial_days
        );

        $trial_days_remaining = $is_in_trial_period
            ? max(0, $trial_days - $days_since_creation)
            : 0;

        $this->session->set_userdata([
            'is_trial'             => $company->is_trial,
            'attendance'           => $company->attendance,
            'communication'        => $company->communication,
            'is_in_trial_period'   => $is_in_trial_period,
            'trial_days_remaining' => $trial_days_remaining
        ]);
    }

    function CreatePermission($permission)
    {
        $this->db->set($permission);
        $this->db->insert('permission');
    }

    function GetAdmIdPermission()
    {
        $this->db->select('permission.id_permission');
        $this->db->from('permission');
        $this->db->where('permission.adm', 1);

        return $this->db->get()->result_array();
    }

    function GetAllPermissions()
    {
        $this->db->select('*');
        $this->db->from('permission');

        $query = $this->db->get();

        if ($query->num_rows() > 0)
            return $query->result_array();
        else
            throw new Exception('Defina um registro de permissão para esse usuário.');
    }

    function GetUserPermissions($key_remote_id)
    {
        $this->db->select('permission.*, user.key_remote_id');
        $this->db->from('user');
        $this->db->join('permission', 'user.id_permission = permission.id_permission', 'inner');
        $this->db->where('user.key_remote_id', $key_remote_id);

        $query = $this->db->get();

        if ($query->num_rows() > 0)
            return $query->result_array()[0];
        else
            throw new Exception('Defina um registro de permissão para esse usuário.');
    }

    function IPPermissions($key_remote_id, $ip)
    {
        $row = $this->GetUserPermissions($key_remote_id);

        if ($row['ip_list'] != "") {
            $ip_list = explode(',', $row['ip_list']);
            if (count($ip_list) > 0) {
                $ok = false;
                foreach ($ip_list as $ip_item) {
                    if ($ip_item == $ip) {
                        $ok = true;
                    } else {
                        $ok = false;
                    }
                }
                return $ok;
            } else {
                return true;
            }
        }
        return true;
    }

    function UpdatePermission($permission)
    {
        $this->db->set($permission);
        $this->db->where('id_permission', $permission['id_permission']);
        $this->db->update('permission');
    }

    function Delete($key_id)
    {
        $this->db->select('id_permission');
        $this->db->from('user');
        $this->db->where('user.id_permission', $key_id);

        $query = $this->db->get();

        if ($query->num_rows() < 1) {
            $this->db->where('id_permission', $key_id);
            $this->db->delete('permission');
        } else {
            $this->db->select('user.name');
            $this->db->from('user');
            $this->db->where('user.id_permission', $key_id);

            return $this->db->query->result_array();
        }

        return $query->result_array();
    }
}
