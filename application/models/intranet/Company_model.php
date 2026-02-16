<?php

class Company_model extends TA_Model
{
    public function __construct()
    {
        parent::__construct();
        parent::SetTalkall();
    }


    function Get($text, $status, $dt_start, $dt_end, $start, $length, $order_column, $order_dir)
    {
        $sql = "SELECT 
                    company.id_company,
                    company.corporate_name,
                    FROM_UNIXTIME(company.creation, '%d/%m/%Y') AS creation,
                    company.fantasy_name,
                    company.cnpj,
                    company.db,
                    user.email,
                (SELECT 
                            COUNT(user.id_company) count
                        FROM
                            talkall_admin.user
                        WHERE
                            user.id_company = company.id_company
                                AND user.status = 1
                        GROUP BY user.id_company) AS number_of_users,
                    company.status,
                    bill.status AS bill_situation
                FROM
                    talkall_admin.company
                        LEFT JOIN
                (SELECT 1 AS id, 'ativo' AS description) AS active ON (active.id = company.status)
                        LEFT JOIN
                (SELECT 2 AS id, 'inativo' AS description) AS inactive ON (inactive.id = company.status)
                        LEFT JOIN
                    talkall_admin.bill ON company.id_customer_vindi = bill.id_customer_vindi
                        LEFT JOIN
                    talkall_admin.user ON company.id_company = user.id_company
                WHERE
                    (company.corporate_name LIKE '%{$text}%'
                        OR company.fantasy_name LIKE '%{$text}%' OR user.email LIKE '%{$text}%' OR company.cnpj LIKE '%{$text}%' 
                        OR active.description = LOWER('$text') OR inactive.description = LOWER('$text'))
                        AND user.email LIKE 'suporte.%' AND user.email LIKE '%@talkall.com.br'\n";

        if (trim($status) != "") {
            $sql .= "AND company.status = '$status'\n";
        } else {
            $sql .= "AND company.status = '1'\n";
        }

        if (trim($dt_start) != "") {
            $sql .= "AND from_unixtime(company.creation) BETWEEN '{$dt_start}' AND '{$dt_end}'\n";
        }

        switch ($order_column) {
            case 0:
                $sql .= "ORDER BY company.creation $order_dir\n";
                break;
            case 1:
                $sql .= "ORDER BY company.corporate_name $order_dir\n";
                break;
            case 2:
                $sql .= "ORDER BY company.fantasy_name $order_dir\n";
                break;
            case 3:
                $sql .= "ORDER BY company.cnpj $order_dir\n";
                break;
            case 4:
                $sql .= "ORDER BY company.status $order_dir\n";
                break;
        }

        $sql .= "limit {$start},{$length}\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Count($text, $status, $dt_start, $dt_end)
    {
        $sql = "SELECT 
                    COUNT(company.id_company) count
                FROM
                    talkall_admin.company
                        LEFT JOIN
                    (SELECT 1 AS id, 'ativo' AS description) AS active ON (active.id = company.status)
                        LEFT JOIN
                    (SELECT 2 AS id, 'inativo' AS description) AS inactive ON (inactive.id = company.status)
                        LEFT JOIN
                    talkall_admin.user ON company.id_company = user.id_company
                WHERE
                    (company.corporate_name LIKE '%{$text}%' OR company.fantasy_name LIKE '%{$text}%' 
                        OR company.cnpj LIKE '%{$text}%' OR user.email LIKE '%{$text}%' 
                        OR active.description = LOWER('$text') OR inactive.description = LOWER('$text'))
                        AND user.email LIKE 'suporte.%' AND user.email LIKE '%@talkall.com.br'\n";

        if (trim($status) != "") {
            $sql .= "AND company.status = '$status'\n";
        }

        if (trim($dt_start) != "") {
            $sql .= "AND from_unixtime(company.creation) BETWEEN '{$dt_start}' AND '{$dt_end}'\n";
        }

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function GetById($id_company)
    {
        $sql = "select\n";
        $sql .= "company.id_company,\n";
        $sql .= "company.corporate_name,\n";
        $sql .= "company.id_company_plan,\n";
        $sql .= "from_unixtime(company.creation,'%d/%m/%Y') as creation,\n";
        $sql .= "company.fantasy_name,\n";
        $sql .= "company.cnpj,\n";
        $sql .= "company.db,\n";
        $sql .= "(select count(user.id_company) count\n";
        $sql .= "from talkall_admin.user\n";
        $sql .= "where user.id_company = company.id_company\n";
        $sql .= "and user.status = 1\n";
        $sql .= "group by user.id_company) as number_of_users,\n";
        $sql .= "company.status,\n";
        $sql .= "company.state_registration,\n";
        $sql .= "company.cep,\n";
        $sql .= "company.address,\n";
        $sql .= "company.number_address,\n";
        $sql .= "company.district,\n";
        $sql .= "company.city,\n";
        $sql .= "company.complement,\n";
        $sql .= "company.name_responsible,\n";
        $sql .= "company.email_responsible,\n";
        $sql .= "company.telephone_responsible,\n";
        $sql .= "company.test_period\n";
        $sql .= "from talkall_admin.company\n";
        $sql .= "where id_company = {$id_company} \n";

        $result = $this->db->query($sql);
        if ($result->num_rows() > 0) {
            return $result->result_array()[0];
        } else {
            $error = 'Nenhum registro encontrado para id informado.';
            throw new Exception($error);
        }
    }


    function GetInfoCompany($id_company)
    {
        $sql = "select\n";
        $sql .= "company.db\n";
        $sql .= "from company\n";
        $sql .= "where company.status = 1\n";
        $sql .= "and company.id_company = {$id_company}\n";

        $result = $this->db->query($sql);

        $data = array(
            'csrfName' => $this->security->get_csrf_token_name(),
            'csrfHash' => $this->security->get_csrf_hash(),
            'userGroup'   => 0,
            'userCall' => 0,
            'permission' => 0,
            'workTime'      => 0
        );

        if ($result->num_rows() > 0) {

            $this->db = $this->load->database(Setdatabase($result->row()->db), TRUE);

            $sql = "select\n";
            $sql .= "user_group.id_user_group,\n";
            $sql .= "user_group.name\n";
            $sql .= "from user_group\n";
            $sql .= "order by user_group.name\n";
            $result = $this->db->query($sql);

            $data['userGroup'] = $result->result_array();

            $sql = "select\n";
            $sql .= "user_calls.id_user_call,\n";
            $sql .= "user_calls.name\n";
            $sql .= "from user_calls\n";
            $sql .= "order by user_calls.name\n";
            $result = $this->db->query($sql);

            $data['userCall'] = $result->result_array();

            $sql = "select\n";
            $sql .= "permission.id_permission,\n";
            $sql .= "permission.name\n";
            $sql .= "from permission\n";
            $sql .= "order by permission.name\n";
            $result = $this->db->query($sql);

            $data['permission'] = $result->result_array();

            $sql = "select\n";
            $sql .= "work_time.id_work_time,\n";
            $sql .= "work_time.name\n";
            $sql .= "from work_time\n";
            $sql .= "order by work_time.name\n";
            $result = $this->db->query($sql);

            $data['workTime'] = $result->result_array();

            return $data;
        } else {
            return $data;
        }
    }


    function List()
    {
        $sql = "select\n";
        $sql .= "(id_company),\n";
        $sql .= "corporate_name\n";
        $sql .= "from talkall_admin.company\n";
        $sql .= "where status = 1\n";
        $sql .= "order by talkall_admin.company.corporate_name\n";

        $result = $this->db->query($sql);

        $data = $result->result_array();

        return $data;
    }


    function UpdateCompany($id_company, $data)
    {
        $values = [
            'corporate_name' => $data['input-corporate-name'],
            'fantasy_name'  => $data['input-fantasy-name'],
            'cnpj' => $data['input-cnpj'],
            'status' => $data['select-status'],
            'id_company_plan' => $data['select-plan'],
            'state_registration' => $data['input-state-registration'],
            'cep' => $data['input-cep'],
            'address' => $data['input-address'],
            'number_address' => $data['input-address-number'],
            'district' => $data['input-district'],
            'city' => $data['input-city'],
            'complement' => $data['input-complement'],
            'name_responsible' => $data['input-name-responsible'],
            'email_responsible' => $data['input-email-responsible'],
            'telephone_responsible' => $data['input-telephone-responsible'],
            'test_period' => $data['select-test-period']
        ];

        $this->db->where('id_company', $id_company);
        $this->db->update('company', $values);
    }


    function Add($data)
    {
        $date = new DateTime();

        $this->db->insert('company', [
            'creation' => $date->getTimestamp(),
            'corporate_name' => $data['input-corporate-name'],
            'fantasy_name'  => $data['input-fantasy-name'],
            'cnpj' => $data['input-cnpj'],
            'status' => $data['select-status'],
            'id_company_plan' => $data['select-plan'],
            'state_registration' => $data['input-state-registration'],
            'cep' => $data['input-cep'],
            'address' => $data['input-address'],
            'number_address' => $data['input-address-number'],
            'district' => $data['input-district'],
            'city' => $data['input-city'],
            'complement' => $data['input-complement'],
            'name_responsible' => $data['input-name-responsible'],
            'email_responsible' => $data['input-email-responsible'],
            'telephone_responsible' => $data['input-telephone-responsible'],
            'test_period' => $data['select-test-period']
        ]);

        $id_company = $this->db->insert_id();

        $email = "suporte." . md5(uniqid()) . "@talkall.com.br";
        $password = md5(uniqid());
        $key_remote_id = Token();

        $this->db->insert('user', [
            'creation' => $date->getTimestamp(),
            'id_company' => $id_company,
            'email' => $email,
            'password' => $password,
            'key_remote_id' => $key_remote_id,
            'status' => 1,
            'accessType' => 1,
            'sms_verified' => 0
        ]);

        $resultDB = $this->db->query(
            "SELECT
                concat('db',count(company.id_company)+1) db 
            FROM
                company 
            WHERE
                company.status != 3;"
        );

        $db = $resultDB->first_row()->db;

        $this->db->where('id_company', $id_company);
        $this->db->update('company', ['db' => $db]);
        $this->db->query("create database " . $db);

        $sql = file_get_contents(base_url() . "create_database.sql");
        $sql = str_replace("banco", $db, $sql);
        $sql = str_replace("{KEY_REMOTE_ID}", $key_remote_id, $sql);
        $sql = str_replace("{NAME}", "Administrador", $sql);
        $sql = str_replace("{LAST_NAME}", "Suporte", $sql);
        $sql = str_replace("{EMAIL}", $email, $sql);
        $sql = str_replace("{PASSWORD}", $password, $sql);
        $sql = str_replace("{ID_COMPANY}", $id_company, $sql);

        $sqls = explode(';', $sql);
        array_pop($sqls);

        foreach ($sqls as $statement) {
            $statment = $statement . ";";
            $this->db->query($statment);
        }

        return $id_company;
    }


    function View($id)
    {
        $sql = 'SELECT company.*,
                       bill.*
                FROM company
                LEFT JOIN bill on company.id_company = bill.id_company
                WHERE company.id_company = ' . $id . '';

        $result = $this->db->query($sql);
        $data = $result->result_array();

        return $data;
    }


    function Edit($id_company, $data)
    {
        $values = [
            'corporate_name' => $data['input-corporate-name'],
            'fantasy_name'  => $data['input-fantasy-name'],
            'cnpj' => $data['input-cnpj'],
            'status' => $data['select-status'],
            'id_company_plan' => $data['select-plan'],
            'state_registration' => $data['input-state-registration'],
            'cep' => $data['input-cep'],
            'address' => $data['input-address'],
            'number_address' => $data['input-address-number'],
            'district' => $data['input-district'],
            'city' => $data['input-city'],
            'complement' => $data['input-complement'],
            'name_responsible' => $data['input-name-responsible'],
            'email_responsible' => $data['input-email-responsible'],
            'telephone_responsible' => $data['input-telephone-responsible'],
            'test_period' => $data['select-test-period']
        ];

        $this->db->where('id_company', $id_company);
        $this->db->update('company', $values);
    }


    function AutoComplete($text)
    {
        $sql = "SELECT
                    company.id_company id,
                    upper(company.corporate_name) text
                FROM
                    talkall_admin.company
                WHERE
                    company.corporate_name LIKE '%$text%'
                ORDER BY
                    company.corporate_name";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function GetAndUpdateCredentials($id_company)
    {
        $date = new DateTime();

        $this->load->helper('string');
        $password = random_string('alnum', 16);

        $company = $this->db->get_where('company', array('id_company =' => $id_company))->first_row();

        $userSuport = $this->db->query(
            "SELECT 
                *
            FROM
                user 
            WHERE 
                email LIKE 'suporte.%'
                AND email LIKE '%@talkall.com.br'
                AND id_company = {$id_company}"
        )->first_row();

        if (!$userSuport) {
            return [
                'status' => false,
                'message' => 'Usuário de suporte inexistente!'
            ];
        }

        $this->db->where('id_user', $userSuport->id_user);
        $this->db->update('user', ['password' => md5($password)]);

        if ($this->db->query("SHOW DATABASES LIKE '{$company->db}'")->num_rows() == 0) {
            return [
                'status' => false,
                'message' => 'Banco inexistente!'
            ];
        }

        $this->db->where('email', $userSuport->email);
        $this->db->update($company->db . '.user', ['password' => md5($password)]);

        $this->db->insert('talkall_admin.user_password_generator', [
            'creation' => $date->getTimestamp(),
            'user_key_remote_id' => $this->session->userdata('key_remote_id'),
            'email' => $userSuport->email,
            'password' => md5($password)
        ]);

        return [
            'email' => $userSuport->email,
            'password' => $password,
            'status' => true,
            'message' => 'Sucesso!'
        ];
    }


    function ListByCNPJ()
    {
        $sql = "select\n";
        $sql .= "(id_company),\n";
        $sql .= "corporate_name,\n";
        $sql .= "cnpj\n";
        $sql .= "from talkall_admin.company\n";
        $sql .= "where cnpj != ''\n";
        $sql .= "and cnpj != '0'\n";
        $sql .= "order by talkall_admin.company.corporate_name\n";

        $result = $this->db->query($sql);

        $data = $result->result_array();

        return $data;
    }


    function UpdateDataVindi($id_company, $id_vindi, $corporate_name)
    {

        $values = [
            'id_customer_vindi' => $id_vindi,
            'corporate_name' => $corporate_name
        ];

        $this->db->where('id_company', $id_company);
        $this->db->update('company', $values);
    }


    function SearchIDByVindi($id_vindi)
    {
        $sql = "select\n";
        $sql .= "(id_company)\n";
        $sql .= "from talkall_admin.company\n";
        $sql .= "where id_customer_vindi = $id_vindi";

        $result = $this->db->query($sql);

        $data = $result->result_array();

        return $data;
    }
}
