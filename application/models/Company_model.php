<?php

class Company_model extends TA_Model
{
    public function __construct()
    {
        parent::__construct();
        parent::SetRemoteDatabase("192.168.190.40", "talkall_admin");
    }

    function Edit($data)
    {
        $sql = "select\n";
        $sql .= "company_valid.id_company_valid,\n";
        $sql .= "user.email,\n";
        $sql .= "user.password,\n";
        $sql .= "user.id_user,\n";
        $sql .= "company_valid.id_company\n";
        $sql .= "from company inner join user on company.id_company = user.id_company\n";
        $sql .= "inner join company_valid on company.id_company = company_valid.id_company\n";
        $sql .= "where company_valid.token = '" . $data['token'] . "' and company_valid.status = 1 and user.status = 1 and company.status = 1\n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {

            $company_id = (int) $result->result_array()[0]['id_company'];

            $sql = "select concat('db',count(company.id_company)+1) db, replace(left(uuid(),12),'-','') token from company where company.status != 3\n";

            $resultDB = $this->db->query($sql);

            $values = [
                'corporate_name' => $data['corporate_name'],
                'db' => $resultDB->result_array()[0]['db'],
                'token' => $resultDB->result_array()[0]['token']
            ];

            $this->db->where('id_company', $company_id);
            $this->db->update('company', $values);

            $this->SetRemoteDatabase("192.168.190.75", "db1");

            $sql = file_get_contents("/var/www/html/create_database.sql");

            $sql = str_replace("banco", $resultDB->result_array()[0]['db'], $sql);

            $key_remote_id = Token();

            $sql = str_replace("{KEY_REMOTE_ID}", $key_remote_id, $sql);
            $sql = str_replace("{NAME}", "Administrador", $sql);
            $sql = str_replace("{LAST_NAME}", $data['full_name'], $sql);
            $sql = str_replace("{EMAIL}", $result->result_array()[0]['email'], $sql);
            $sql = str_replace("{PASSWORD}", $result->result_array()[0]['password'], $sql);
            $sql = str_replace("{ID_COMPANY}", $result->result_array()[0]['id_company'], $sql);

            $sqls = explode(';', $sql);

            array_pop($sqls);

            $this->db->simple_query("create database " . $resultDB->result_array()[0]['db'] . " CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;");

            foreach ($sqls as $statement) {
                $statment = str_replace("`{TA_SEMICOLON}`", ";", $statement) . ";";
                $this->db->simple_query($statment);
            }

            $this->SetRemoteDatabase("192.168.190.40", "talkall_admin");

            $this->db->simple_query("INSERT INTO `talkall_admin`.`channel`(`id_company`,`creation`,`id`,`type`,`status`,`executed`) VALUES(" . $result->result_array()[0]['id_company'] . ",UNIX_TIMESTAMP(),'$key_remote_id',1,1,1);");

            $this->db->simple_query("INSERT INTO `talkall_admin`.`channel`(`id_company`,`creation`,`id`,`type`,`status`,`executed`) VALUES(" . $result->result_array()[0]['id_company'] . ",UNIX_TIMESTAMP(),'kanban-$key_remote_id',5,1,1); ");

            $this->db->simple_query("INSERT INTO `talkall_admin`.`channel`(`id_company`,`creation`,`id`,`type`,`status`,`executed`) VALUES(" . $result->result_array()[0]['id_company'] . ",UNIX_TIMESTAMP(),'qrcode-$key_remote_id',20,1,1);");

            $this->db->simple_query("INSERT INTO `talkall_admin`.`company_module`(`id_company`,`creation`,`type`) VALUES(" . $result->result_array()[0]['id_company'] . ",UNIX_TIMESTAMP(),'TALKALL_MODULE_ATTENDANCE');");

            return true;
        } else {
            return false;
        }
    }

    function Register($data)
    {
        $date = new DateTime();
        $token = Token();

        $this->db->insert('company', [
            'creation' => $date->getTimestamp(),
            'status' => 3,
            'is_trial' => 1
        ]);
        $company_id = $this->db->insert_id();

        $this->db->insert('user', [
            'creation' => $date->getTimestamp(),
            'id_company' => $company_id,
            'email' => strtolower($data['email']),
            'key_remote_id' => $token,
            'password' => md5($data['password']),
            'status' => 3
        ]);
        $user_id = $this->db->insert_id();

        $this->db->insert('user_valid', [
            'creation' => $date->getTimestamp(),
            'id_user' => $user_id,
            'token' => $token,
            'status' => 3
        ]);

        $channels = [
            ['id' => $token, 'type' => 1],
            ['id' => "kanban-$token", 'type' => 5],
            ['id' => "kanban-communication-$token", 'type' => 27],
            ['id' => "qrcode-$token", 'type' => 20],
            ['id' => "connect-$token", 'type' => 21]
        ];

        foreach ($channels as $ch) {
            $this->db->insert('channel', [
                'creation' => $date->getTimestamp(),
                'id_company' => $company_id,
                'id' => $ch['id'],
                'type' => $ch['type'],
                'status' => 1,
                'executed' => 1
            ]);
        }

        return [
            'id_company' => $company_id,
            'id_user' => $user_id,
            'token' => $token
        ];
    }

    function Add($data)
    {
        $companyName = trim(strtolower($data['company']));

        // Verifica se já existe a empresa
        $this->db->where('LOWER(TRIM(fantasy_name))', $companyName);
        $existing = $this->db->get('company')->row();

        if ($existing) {
            return [
                'status' => 'error',
                'message' => 'Empresa já cadastrada.'
            ];
        }

        $date = new DateTime();
        $creation = $date->getTimestamp();
        $code = rand(10000, 99999);

        $server_info = $this->getServerLowestDb();

        $database_info = $this->GenerateInfoFromDatabase();

        $this->UpdateCountForAllDatabases($server_info);

        $this->db->trans_start();

        $company_id = $data['id_company'];
        $firstName = explode(' ', trim(preg_replace('/\s+/', ' ', $data['name'])))[0];

        $company_values = [
            'db'        => $database_info['db'],
            'token'     => $database_info['token'],
            'server'    => $server_info['server'],
            'port'      => 3306,
            'vm_port'   => $server_info['port'] ?? null,
            'code'      => $code,
            'status'    => 3,
            'fantasy_name' => $data['company'],
            'name_responsible1' => $data['name'],
            'phone_responsible1' => '55' . preg_replace('/\D/', '', $data['phone'])
        ];

        $company_values = array_filter($company_values, function ($v) {
            return !is_null($v) && $v !== '';
        });

        // Atualiza empresa
        $this->db->where('id_company', $company_id);
        $this->db->update('company', $company_values);

        try {

            $this->db->insert('company_valid', [
                'creation'   => $creation,
                'id_company' => $company_id,
                'token'      => $data['token'],
                'status'     => 3
            ]);

            $suport_user_info = $this->CreateSuportUser($data['company'], $company_id);

            $existing_users = $this->GetAllTalkallAdminUsers($company_id);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return [
                    'status' => 'error',
                    'message' => 'Erro ao gravar dados no banco (PAD-002).'
                ];
            } else {
                $this->db->trans_complete();
            }
        } catch (\Exception $e) {

            $this->db->trans_rollback();
            return [
                'status' => 'error',
                'message' => 'Erro inesperado: ' . $e->getMessage()
            ];
        }

        $this->CreateDatabase([
            'db_name'        => $database_info['db'],
            'id_company'     => $company_id,
            'server'         => $server_info['server'],
            'existing_users' => $existing_users,
            'suport_user'    => $suport_user_info,
            'first_name'     => $firstName,
            'name_responsible1' => $data['name'],
            'phone_responsible1' => preg_replace('/\D/', '', $data['phone'])
        ]);

        $this->UpdateDatabaseCount($server_info);

        $this->db->where('id_company', $company_id);
        $this->db->update('company', ['status' => 1]);

        $this->db->where('id_company', $company_id);
        $this->db->update('company_valid', ['status' => 1]);

        $this->db->select('email');
        $this->db->where('id_company', $company_id);
        $this->db->where('status', 1);
        $user  = $this->db->get('user')->row();
        $email = $user->email ?? null;

        $payload = [
            'event'     => 'teste_gratis',
            'timestamp' => date('c'),
            'source'    => 'talkall_app',
            'data'      => [
                'first_name'   => $firstName,
                'company_name' => $data['company'],
                'email'        => $email,
                'mobile'       => '55' . preg_replace('/\D/', '', $data['phone'])
            ]
        ];

        $curl = curl_init('https://n8n.talkall.com.br/webhook/2d664a78-b2d0-4a53-ab48-3bb781ed5585');

        curl_setopt_array($curl, [
            CURLOPT_POST       => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($payload)
        ]);

        curl_exec($curl);
        curl_close($curl);

        return [
            'status' => 'success',
            'message' => 'Email enviado (modo de teste).'
        ];
    }

    function GetAllTalkallAdminUsers($id_company)
    {
        try {
            $this->db->where('id_company', $id_company);
            $users = $this->db->get('user')->result_array();

            if (empty($users)) {
                return ["errors" => ["code" => "USR-001", "message" => "Nenhum usuário encontrado para esta empresa."]];
            }

            $all_users_data = [];

            foreach ($users as $user) {
                $user_id = $user['id_user'];

                $this->db->where('id_user', $user_id);
                $user_valids = $this->db->get('user_valid')->result_array();

                $this->db->like('id', $user['key_remote_id']);
                $this->db->where('id_company', $id_company);
                $channels = $this->db->get('channel')->result_array();

                $all_users_data[] = [
                    'user' => $user,
                    'user_valid' => $user_valids,
                    'channels' => $channels
                ];
            }

            return $all_users_data;
        } catch (\Exception $e) {
            return ["errors" => ["code" => "USR-002", "message" => $e->getMessage()]];
        }
    }

    function CreateSuportUser($fantasy_name, $id_company)
    {
        $this->db->trans_start();

        try {
            $formated_name = $this->formatName($fantasy_name);
            $email = "suporte." . $formated_name . "@talkall.com.br";
            $key_remote_id = Token();

            $user_values = [
                'creation' => time(),
                'id_company' => $id_company,
                'email' => $email,
                'password' => md5(uniqid()),
                'key_remote_id' => $key_remote_id,
                'status' => 1,
                'accessType' => 0,
                'sms_verified' => 0
            ];

            $this->db->insert('user', $user_values);
            $id_user = $this->db->insert_id();

            $channels = [
                ['id' => $key_remote_id, 'type' => 1],
                ['id' => "kanban-$key_remote_id", 'type' => 5],
                ['id' => "kanban-communication-$key_remote_id", 'type' => 27],
                ['id' => "qrcode-$key_remote_id", 'type' => 20],
                ['id' => "connect-$key_remote_id", 'type' => 21]
            ];

            foreach ($channels as $ch) {
                $this->db->insert('channel', [
                    'creation' => time(),
                    'id_company' => $id_company,
                    'id' => $ch['id'],
                    'type' => $ch['type'],
                    'status' => 1,
                    'executed' => 1
                ]);
            }

            $this->db->insert('user_valid', [
                'id_user' => $id_user,
                'creation' => time(),
                'token' => Token(),
                'status' => 1
            ]);

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return ["errors" => ["code" => "PAD-002"]];
            } else {
                $this->db->trans_complete();
                return $user_values;
            }
        } catch (\Exception $e) {
            $this->db->trans_rollback();
            return ["errors" => ["code" => "PAD-002", "message" => $e->getMessage()]];
        }
    }

    function CreateDatabase($values)
    {
        $this->db = $this->load->database(SetServer($values['server']), TRUE);
        if (!$this->db->simple_query("CREATE DATABASE " . $values['db_name'] . " CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;")) {
            return ["errors" => ["code" => "DB-001", "message" => "Falha ao criar database"]];
        }

        $this->db = $this->load->database(SetdatabaseRemote($values['db_name'], $values['server']), TRUE);

        $sql = file_get_contents("create_database.sql");
        $sql = str_replace("banco", $values['db_name'], $sql);
        $sql = str_replace("{KEY_REMOTE_ID}", $values['suport_user']['key_remote_id'], $sql);
        $sql = str_replace("{NAME}", "Administrador", $sql);
        $sql = str_replace("{LAST_NAME}", "Suporte", $sql);
        $sql = str_replace("{EMAIL}", $values['suport_user']['email'], $sql);
        $sql = str_replace("{PASSWORD}", $values['suport_user']['password'], $sql);
        $sql = str_replace("{ID_COMPANY}", $values['id_company'], $sql);

        $sqls = explode(';', $sql);
        array_pop($sqls);

        foreach ($sqls as $key => $statement) {
            $statment = str_replace("`{TA_SEMICOLON}`", ";", $statement) . ";";
            $result = $this->db->simple_query($statment);

            if ($result === FALSE) {
                $error = $this->db->error();
                writeToLogFile($error, $statment, $values['db_name'], $values['server'], $key + 1);
            }
        }

        if (!empty($values['existing_users'])) {
            foreach ($values['existing_users'] as $user_data) {
                $user = $user_data['user'];
                $email = $user['email'] ?? '';

                $exists = $this->db
                    ->where('email', $email)
                    ->get('user')
                    ->row();

                if (!$exists) {
                    $insert_user = [
                        'creation' => $user['creation'] ?? time(),
                        'id_permission' => $user['id_permission'] ?? 1,
                        'id_user_call' => 1,
                        'id_work_time' => 1,
                        'id_user_group' => 1,
                        'key_remote_id' => $user['key_remote_id'] ?? null,
                        'name' => $values['first_name'] ?? '',
                        'last_name' => $values['name_responsible1'] ?? '',
                        'email' => $email,
                        'password' => $user['password'] ?? '',
                        'profile_picture' => $user['profile_picture'] ?? null,
                        'visible' => $user['visible'] ?? 1,
                        'status' => $user['status'] ?? 1,
                        '2fa' => $user['2fa'] ?? 0,
                        'phone' => $values['phone_responsible1'] ?? null,
                        'visible_widget' => $user['visible_widget'] ?? 2,
                        'notification_alert_url' => $user['notification_alert_url'] ?? null,
                        'language' => 'pt_br',
                        'login_retry' => $user['login_retry'] ?? 0,
                        'attendance_available' => $user['attendance_available'] ?? 0
                    ];

                    $this->db->insert('user', $insert_user);

                    if (!empty($user_data['channels'])) {
                        foreach ($user_data['channels'] as $ch) {
                            $channel = [
                                'id' => $ch['id'] ?? null,
                                'type' => $ch['type'] ?? 1,
                                'status' => $ch['status'] ?? 1
                            ];

                            $this->db->insert('channel', $channel);
                            $id_channel = $this->db->insert_id();

                            // Só cria registro na CONFIG se type == 1
                            if (($ch['type'] ?? 1) == 1 && $id_channel) {
                                $config = [
                                    'id_channel' => $id_channel,
                                    'timezone' => '-03:00',
                                    'attendance_enable' => 1,
                                    'chatbot_enable' => 2,
                                    'automatic_transfer' => 2,
                                    'automatic_transfer_minute' => 1,
                                    'attendant_name_enable' => 0,
                                    'interval_broadcast' => 2,
                                    'message_close_enabled' => 2,
                                    'enable_protocol' => 2,
                                    'enabled_lgpd_question' => 2,
                                    'is_broadcast' => 2,
                                    'wa_business_broadcast_limit_send' => 2
                                ];
                                $this->db->insert('config', $config);
                            }

                            // Cria contato apenas se type == 1
                            if (($ch['type'] ?? 1) == 1) {
                                $date = new DateTime();
                                $contact = [
                                    'creation' => $date->getTimestamp(),
                                    'id_channel' => $id_channel,
                                    'key_remote_id' => $user['key_remote_id'] ?? null,
                                    'full_name' => trim(($user['name'] ?? '') . ' ' . ($user['last_name'] ?? '')),
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

                                $this->db->insert('contact', $contact);
                            }
                        }
                    }
                }
            }
        }

        $this->db = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), true);
        if (!$this->db->simple_query("
        INSERT INTO `talkall_admin`.`company_module`
        (`id_company`,`creation`,`type`)
        VALUES
        (" . $values['id_company'] . ",UNIX_TIMESTAMP(),'TALKALL_MODULE_ATTENDANCE'),
        (" . $values['id_company'] . ",UNIX_TIMESTAMP(),'TALKALL_MODULE_COMMUNICATION');
    ")) {
            return ["errors" => ["code" => "DB-003", "message" => "Falha ao inserir módulos"]];
        }

        return ["success" => true];
    }

    function GenerateInfoFromDatabase()
    {
        $this->db->select("db, REPLACE(LEFT(UUID(), 12), '-', '') as token", false);
        $this->db->from("company");
        $this->db->where("db is not null");
        $this->db->order_by("company.creation", "desc");
        $this->db->limit(1);

        $result = $this->db->get()->result_array()[0];
        $database = $result["db"];
        $token = $result["token"];

        $number = (int) str_replace("db", "", $database);
        $number += 1;

        return ["db" => "db" . $number, "token" => $token];
    }

    function GetServer()
    {
        $this->db->select("
            database_count.id_database_count,
            database_count.server,
            database_count.port,
            database_count.count
        ");

        $this->db->from("talkall_admin.database_count");

        return $this->db->get()->result_array();
    }

    function formatName($string)
    {
        $formated_name = preg_replace(
            array(
                "/(á|à|ã|â|ä)/",
                "/(Á|À|Ã|Â|Ä)/",
                "/(é|è|ê|ë)/",
                "/(É|È|Ê|Ë)/",
                "/(í|ì|î|ï)/",
                "/(Í|Ì|Î|Ï)/",
                "/(ó|ò|õ|ô|ö)/",
                "/(Ó|Ò|Õ|Ô|Ö)/",
                "/(ú|ù|û|ü)/",
                "/(Ú|Ù|Û|Ü)/",
                "/(ñ)/",
                "/(Ñ)/"
            ),
            explode(" ", "a A e E i I o O u U n N"),
            $string
        );

        $formated_name = preg_replace('/[^a-zA-Z0-9]/', '', $formated_name);

        return strtolower($formated_name);
    }

    function getServerLowestDb()
    {
        $this->db->select("id_database_count, server, port, count");
        $this->db->from("talkall_admin.database_count");
        $this->db->order_by("count", "asc");
        $this->db->limit(1);

        $lowest = $this->db->get()->result_array()[0];

        return $lowest;
    }

    function UpdateCountForAllDatabases($server_info)
    {
        $database_count = $this->GetCurrentDatabaseInfo($server_info);

        $this->db = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), true);

        $this->db->trans_start();

        $this->db->update_batch("database_count", $database_count, "id_database_count");

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ["errors" => ["code" => "PAD-002"]];
        } else {
            $this->db->trans_complete();
            return ["success" => ["status" => true]];
        }
    }

    function GetCurrentDatabaseInfo($server_info)
    {
        $database_count = [];

        if (!is_array($server_info) || !isset($server_info[0])) {
            $server_info = [$server_info];
        }

        foreach ($server_info as $value) {

            $this->db = $this->load->database(setServer($value['server']), true);

            $this->db->select('COUNT(*) AS count');
            $this->db->from('information_schema.schemata');
            $result = $this->db->get()->row_array();

            $count = isset($result['count']) ? (int) $result['count'] : 0;

            $database_count[] = [
                'id_database_count' => (int) $value['id_database_count'],
                'count' => $count
            ];
        }

        return $database_count;
    }
    function UpdateDatabaseCount($server_info)
    {
        $this->db = $this->load->database(Setdatabase("talkall_admin"), true);

        $this->db->trans_start();

        $this->db->where('id_database_count', $server_info['id_database_count']);
        $this->db->update('database_count', ['count' => $server_info['count'] + 1]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ["errors" => ["code" => "PAD-002"]];
        } else {
            $this->db->trans_complete();
            return ["success" => ["status" => true]];
        }
    }

    function SendEmail($data, $view, $url)
    {
        $email = trim($data["input-email"]);
        $subject = "🤖 Confirmação de Email";

        $payload = [
            "to" => $email,
            "subject" => $subject,
            "template" => $view,
            "context" => [
                "hostname" => "https://app.talkall.com.br/",
                "url" => $url,
                "email" => $email,
                "message" => "Para completar seu registro, acesse o link único abaixo:"
            ]
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://services.talkall.com.br:4004/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => ['Content-Type: application/json']
        ]);
        curl_exec($curl);
        curl_close($curl);
    }

    function SendCommercialEmail($sendTo, $userEmail, $view, $context = [])
    {
        if ($userEmail === null) {
            $userEmail = "";
        }

        if (!is_array($context)) {
            $context = [
                "hostname" => "https://app.talkall.com.br/",
                "url"      => $context,
                "email"    => $userEmail,
                "message"  => "Para completar seu registro, acesse o link único abaixo:"
            ];
        } else {
            $context["email"] = $userEmail;
            $context["hostname"] = "https://app.talkall.com.br/";
        }

        $payload = [
            "to"       => $sendTo,
            "subject"  => "🤖 Confirmação de Email",
            "template" => $view,
            "context"  => $context
        ];

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://services.talkall.com.br:4004/',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => ['Content-Type: application/json']
        ]);
        curl_exec($curl);
        curl_close($curl);
    }

    function GetUserByToken($token)
    {
        return $this->db->select('user.id_user, user.id_company, user.phone, user_valid.id_user_valid, user_valid.status as valid_status')
            ->from('user_valid')
            ->join('user', 'user.id_user = user_valid.id_user')
            ->where('user_valid.token', $token)
            ->get()
            ->row_array();
    }

    function GetCompanyByToken($token)
    {
        return $this->db->select('company.id_company, company.phone_responsible1, company.code')
            ->from('user')
            ->join('company', 'company.id_company = user.id_company')
            ->where('user.key_remote_id', $token)
            ->get()
            ->row_array();
    }

    function GetCompanyValidByToken($token)
    {
        return $this->db
            ->select('status')
            ->from('company_valid')
            ->where('token', $token)
            ->where('status', 1)
            ->get()
            ->row_array();
    }

    function ActivateUser($id_user)
    {
        $this->db->trans_start();

        $this->db->where('id_user', $id_user)
            ->update('user_valid', ['status' => 1]);

        $this->db->where('id_user', $id_user)
            ->update('user', ['status' => 1]);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ["errors" => ["code" => "PAD-002"]];
        } else {
            $this->db->trans_complete();
            return ["success" => ["status" => true]];
        }
    }

    function confirm($token)
    {
        $sql = "select\n";
        $sql .= "company_valid.id_company_valid,\n";
        $sql .= "user.id_user,\n";
        $sql .= "company_valid.id_company\n";
        $sql .= "from company inner join user on company.id_company = user.id_company\n";
        $sql .= "inner join company_valid on company.id_company = company_valid.id_company\n";
        $sql .= "where company_valid.token = '" . $token . "' and company_valid.status = 3 and user.status = 3 and company.status = 3\n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {

            $this->db->set('status', 1);
            $this->db->where('id_company', (int) $result->result_array()[0]['id_company']);
            $this->db->update('user');

            $this->db->set('status', 1);
            $this->db->where('id_company', (int) $result->result_array()[0]['id_company']);
            $this->db->update('company');

            $this->db->set('status', 1);
            $this->db->where('id_company', (int) $result->result_array()[0]['id_company']);
            $this->db->update('company_valid');

            return true;
        } else {
            return false;
        }
    }

    function check_code($code, $token)
    {
        $this->db->where('token', $token);
        $query = $this->db->get('company_valid');

        if ($query->num_rows() > 0) {
            $company_valid = $query->row();

            $this->db->where('id_company', $company_valid->id_company);
            $this->db->where('code', $code);
            $company = $this->db->get('company')->row();

            if ($company) {

                if ($company_valid->status != 1) {
                    $this->db->where('id_company_valid', $company_valid->id_company_valid)
                        ->update('company_valid', ['status' => 1]);
                }

                if ($company->status != 1) {
                    $this->db->where('id_company', $company_valid->id_company)
                        ->update('company', ['status' => 1]);
                }

                return true;
            }
        }

        return false;
    }

    public function UpdatePhone($id_company, $phone)
    {
        return $this->db->where('id_company', $id_company)
            ->update('company', ['phone_responsible1' => $phone]);
    }
}
