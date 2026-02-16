<?php

class Report_model extends TA_model
{

    public function __construct()
    {
        parent::__construct();
    }

    //////////////////////////////////////////////
    // Fnc (string): Intenção gerada pelo luis, function será executada
    // Parameters (array): Data , nome , etc.. gerado pelo luis
    // Pagination (array): limit do sql (opcional)
    // Columns (bool): Retorna apenas as colunas (Não executa a consulta no banco)
    //////////////////////////////////////////////

    public function Report($Fnc, $Parameters, $Pagination = null, $Columns = false)
    {
        switch ($Fnc) {
            case "Contacts.Synthetic":
                return $this->ContactsSynthetic($Parameters, $Pagination, $Columns);
                break;
            case "Contacts.Analytic":
                return $this->ContactsAnalytic($Parameters, $Pagination, $Columns);
                break;
            case "Contacts.WaitList.Analytic":
                return $this->ContactsWaitListAnalytic($Parameters, $Pagination, $Columns);
                break;
            case "Contacts.WaitList.Synthetic":
                return $this->ContactsWaitListSynthetic($Parameters, $Pagination, $Columns);
                break;
            case "SectorCall.Synthetic":
                return $this->SectorCallSynthetic($Parameters, $Pagination, $Columns);
                break;
            case "SectorCall.Analytic":
                return $this->SectorCallAnalytic($Parameters, $Pagination, $Columns);
                break;
            case "UserCall.Synthetic":
                return $this->UserCallsSynthetic($Parameters, $Pagination, $Columns);
                break;
            case "UserCalls.Analytic":
                return $this->UserCallsAnalytic($Parameters, $Pagination, $Columns);
                break;
            case "Users.Status":
                return $this->UsersStatus($Parameters, $Pagination, $Columns);
                break;
            case "Users.OnlineTime":
                return $this->UsersOnlineTime($Parameters, $Pagination, $Columns);
                break;
        }
    }

    // Retorna Todos os contatos //
    private function ContactsSynthetic($Parameters, $Pagination, $Columns)
    {
        $data['columns'] = array(
            'name' => 'Nome',
            'total' => 'Quantidade'
        );

        if ($Columns) {
            return $data;
        }

        $sql = "select \n";
        $sql .= "'<b>Total</b>'as name,\n";
        $sql .= "concat('<b>',count(contact.id_contact),'</b>') as total\n";
        $sql .= "from contact\n";
        $sql .= "where is_private = 1\n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            $data['values'] = $this->db->query($sql)->result_array();
            $data['total'] = $result->num_rows();
            return $data;
        }
    }

    // Retorna lista dos contatos //
    private function ContactsAnalytic($Parameters, $Pagination, $Columns)
    {
        $data['columns'] = array(
            'creation' => 'Data Hora Cadastro',
            'key_remote_id' => 'Número',
            'full_name' => 'Nome do contato'
        );

        if ($Columns) {
            return $data;
        }
        $sql = "select \n";
        $sql .= "count(wait_list.id_wait_list) as total\n";
        $sql .= "from wait_list\n";
        $sql .= "inner join contact on contact.key_remote_id = wait_list.key_remote_id\n";
        $sql .= "inner join channel on contact.id_channel = channel.id_channel\n";
        $sql .= "inner join config on channel.id_channel = config.id_channel\n";
        $sql .= "where wait_list.status = 1\n";

        if (isset($Parameters['number_1'])) {
            $sql .= "and contact.key_remote_id like '%{$Parameters['number_1']}%'\n";
        }

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            $total = $result->result_array()[0]['total'];

            if ($total > 0) {

                $data['total'] = $total;

                $replace = "count(wait_list.id_wait_list) as total\n";

                $query = "DATE_FORMAT(DATE_ADD(from_unixtime(contact.timestamp), INTERVAL config.timezone HOUR),'%d/%m/%Y %T') as creation,\n";
                $query .= "contact.full_name,\n";
                $query .= "contact.key_remote_id\n";

                $sql = str_replace($replace, $query, $sql);

                $sql .= "order by wait_list.creation desc \n";

                if (isset($Pagination)) {
                    $sql .= "limit {$Pagination['start']},{$Pagination['length']}";
                }

                $data['values'] = $this->db->query($sql)->result_array();
                return $data;
            }

            $data['total'] = 0;
            $data['values'] = '';
            return $data;
        }
    }

    // Retorna a lista de contatos que estão na fila de fila de espera //
    private function ContactsWaitListAnalytic($Parameters, $Pagination, $Columns)
    {
        $data['columns'] = array(
            'creation' => 'Data Hora Cadastro',
            'key_remote_id' => 'Número',
            'full_name' => 'Nome do contato'
        );

        if ($Columns) {
            return $data;
        }

        $sql = "select \n";
        $sql .= "count(wait_list.id_wait_list) as total\n";
        $sql .= "from wait_list\n";
        $sql .= "inner join contact on contact.key_remote_id = wait_list.key_remote_id\n";
        $sql .= "inner join channel on contact.id_channel = channel.id_channel\n";
        $sql .= "inner join config on channel.id_channel = config.id_channel\n";
        $sql .= "where wait_list.status = 1\n";

        if (isset($Parameters['number_1'])) {
            $sql .= "and contact.key_remote_id like '%{$Parameters['number_1']}%'\n";
        }

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            $total = $result->result_array()[0]['total'];

            if ($total > 0) {

                $data['total'] = $total;

                $replace = "count(wait_list.id_wait_list) as total\n";

                $query = "DATE_FORMAT(DATE_ADD(from_unixtime(contact.timestamp), INTERVAL config.timezone HOUR),'%d/%m/%Y %T') as creation,\n";
                $query .= "contact.full_name,\n";
                $query .= "contact.key_remote_id\n";

                $sql = str_replace($replace, $query, $sql);

                $sql .= "order by wait_list.creation desc \n";

                if (isset($Pagination)) {
                    $sql .= "limit {$Pagination['start']},{$Pagination['length']}";
                }

                $data['values'] = $this->db->query($sql)->result_array();
                return $data;
            }

            $data['total'] = 0;
            $data['values'] = '';
            return $data;
        }
    }

    // Retorna quantidade de contatos na fila de espera //
    private function ContactsWaitListSynthetic($Parameters, $Pagination, $Columns)
    {
        $data['columns'] = array(
            'total' => 'Total',
            'valor' => 'Valor',
        );

        if ($Columns) {
            return $data;
        }

        $sql = "select\n";
        $sql .= "'<b>Total Em Espera</b>' as total,\n";
        $sql .= "concat('<b>', count(wait_list.id_wait_list) , '</b>') as valor\n";
        $sql .= "from wait_list\n";
        $sql .= "where wait_list.status = 1\n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            $data['values'] = $this->db->query($sql)->result_array();
        }

        $data['total'] = 0;
        $data['values'] = '';
        return $data;
    }

    // Retorna a contagem de atendimentos únicos por usuário //
    private function UserCallsSynthetic($Parameters, $Pagination, $Columns)
    {
        $data['columns'] = array(
            'last_name' => 'Nome do usuário',
            'total' => 'Quantidade'
        );

        if ($Columns) {
            return $data;
        }

        $sql = "select\n";
        $sql .= "user.last_name,\n";
        $sql .= "count(chat_list_log.id_chat_list_log) as total\n";
        $sql .= "from\n";
        $sql .= "chat_list\n";
        $sql .= "inner join chat_list_log on chat_list.id_chat_list = chat_list_log.id_chat_list\n";
        $sql .= "inner join user on chat_list_log.key_remote_id = user.key_remote_id\n";
        $sql .= "inner join contact on chat_list.id_contact = contact.id_contact\n";
        $sql .= "where chat_list.is_private = 1\n";

        if (isset($Parameters['date_1'])) {
            $sql .= "and DATE_FORMAT(from_unixtime(chat_list_log.creation),'%Y-%m-%d') = '{$Parameters['date_1']}'\n";
        }

        if (isset($Parameters['number_1'])) {
            $sql .= "and contact.key_remote_id like '%{$Parameters['number_1']}%'\n";
        }

        if (isset($Parameters['name_1'])) {
            if (isset($Parameters['name_2'])) {
                $sql .= "and ((user.last_name like '%{$Parameters['name_1']}%') or (user.last_name like '%{$Parameters['name_2']}%')) \n";
            } else {
                $sql .= "and user.last_name like '%{$Parameters['name_1']}%'\n";
            }
        }

        $sql .= "group by user.last_name desc\n";
        $sql .= "order by total desc\n";

        $result = $this->db->query($sql);

        $data['total'] = $result->num_rows();

        $result = $result->result_array();

        $soma = 0;
        foreach ($result as &$row) {
            $soma = (int)$row['total'] + $soma;
        }

        array_push($result, array("last_name" => '<b>Total</b>', "total" => "<b>$soma</b>"));

        $data['values'] = $result;

        return $data;
    }

    // Retorna a contagem de atendimentos únicos por setor //
    private function SectorCallSynthetic($Parameters, $Pagination, $Columns)
    {
        $data['columns'] = array(
            'name' => 'Nome do Setor',
            'total' => 'Atendimentos Realizados'
        );

        if ($Columns) {
            return $data;
        }

        $sql = "select\n";
        $sql .= "user_group.name,\n";
        $sql .= "count(chat_list_log.id_chat_list_log) as total\n";
        $sql .= "from\n";
        $sql .= "chat_list\n";
        $sql .= "inner join chat_list_log on chat_list.id_chat_list = chat_list_log.id_chat_list\n";
        $sql .= "inner join user on chat_list_log.key_remote_id = user.key_remote_id\n";
        $sql .= "inner join contact on chat_list.id_contact = contact.id_contact\n";
        $sql .= "inner join user_group on user.id_user_group = user_group.id_user_group\n";
        $sql .= "where chat_list.is_private = 1\n";

        if (isset($Parameters['date_1'])) {
            $sql .= "and DATE_FORMAT(from_unixtime(chat_list_log.creation),'%Y-%m-%d') = '{$Parameters['date_1']}'\n";
        }

        if (isset($Parameters['sector_1'])) {
            $sql .= "and user_group.name like '%{$Parameters['sector_1']}%'\n";
        }

        if (isset($Parameters['date_range_1'])) {
            $sql .= "and DATE_FORMAT(from_unixtime(chat_list_log.creation),'%Y-%m-%d') between '{$Parameters['date_range_1']['start']}' and '{$Parameters['date_range_1']['end']}' \n";
        }

        $sql .= "group by user_group.name\n";
        $sql .= "order by total desc\n";

        $result = $this->db->query($sql);

        $data['total'] = $result->num_rows();

        $result = $result->result_array();

        $soma = 0;
        foreach ($result as &$row) {
            $soma = (int)$row['total'] + $soma;
        }

        array_push($result, array("name" => '<b>Total</b>', "total" => "<b>$soma</b>"));

        $data['values'] = $result;

        return $data;
    }

    // Retorna a lista de atendimentos únicos por usuário por setor //
    private function SectorCallAnalytic($Parameters, $Pagination, $Columns)
    {
        $data['columns'] = array(
            'name' => 'Setor',
            'last_name' => 'Nome do usuário',
            'key_remote_id' => 'Número',
            'full_name' => 'Nome do contato',
            'start' => 'Data Hora Abertura',
            'end' => 'Data Hora Encerramento',
            'status' => 'Situação do atendimento'
        );

        if ($Columns) {
            return $data;
        }


        $sql =  "select\n";
        $sql .= "count(chat_list_log.id_chat_list_log) as total\n";
        $sql .= "from chat_list\n";
        $sql .= "inner join contact on chat_list.id_contact = contact.id_contact\n";
        $sql .= "inner join chat_list_log on chat_list.id_chat_list = chat_list_log.id_chat_list\n";
        $sql .= "inner join user on chat_list_log.key_remote_id = user.key_remote_id\n";
        $sql .= "inner join user_group on user.id_user_group = user_group.id_user_group\n";
        $sql .= "where chat_list.is_private = 1\n";

        if (isset($Parameters['date_1'])) {
            $sql .= "and DATE_FORMAT(from_unixtime(chat_list_log.creation),'%Y-%m-%d') = '{$Parameters['date_1']}'\n";
        }

        if (isset($Parameters['number_1'])) {
            $sql .= "and contact.key_remote_id like '%{$Parameters['number_1']}%'\n";
        }

        if (isset($Parameters['name_1'])) {
            $sql .= "and ((user.last_name like '%{$Parameters['name_1']}%') or (contact.full_name like '%{$Parameters['name_1']}%') )\n";
        }

        if (isset($Parameters['name_2'])) {
            $sql .= "and ((user.last_name like '%{$Parameters['name_2']}%') or (contact.full_name like '%{$Parameters['name_2']}%') )\n";
        }

        if (isset($Parameters['sector_1'])) {
            $sql .= "and user_group.name like '%{$Parameters['sector_1']}%'\n";
        }

        if (isset($Parameters['date_range_1'])) {
            $sql .= "and DATE_FORMAT(from_unixtime(chat_list_log.creation),'%Y-%m-%d') between '{$Parameters['date_range_1']['start']}' and '{$Parameters['date_range_1']['end']}' \n";
        }

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            $total = $result->result_array()[0]['total'];

            if ($total > 0) {

                $data['total'] = $total;

                $replace = "count(chat_list_log.id_chat_list_log) as total\n";

                $query = "DATE_FORMAT(from_unixtime(chat_list_log.creation),'%d/%m/%Y %T') as creation,\n";
                $query .= "user_group.name,\n";
                $query .= "DATE_FORMAT(from_unixtime(chat_list_log.start),'%d/%m/%Y %T') as start,\n";
                $query .= "DATE_FORMAT(from_unixtime(chat_list_log.end),'%d/%m/%Y %T') as end,\n";
                $query .= "contact.key_remote_id,\n";
                $query .= "contact.full_name,\n";
                $query .= "user.last_name,\n";
                $query .= "case\n";
                $query .= "when chat_list_log.end is null then 'Em atendimento' else 'Finalizado'\n";
                $query .= "end status\n";

                $sql = str_replace($replace, $query, $sql);

                $sql .= "order by chat_list_log.creation desc\n";

                if (isset($Pagination)) {
                    $sql .= "limit {$Pagination['start']},{$Pagination['length']}";
                }

                $data['values'] = $this->db->query($sql)->result_array();
                return $data;
            }

            $data['total'] = 0;
            $data['values'] = '';
            return $data;
        }
    }

    // Retorna a quantidade de atendimentos únicos por usuário //
    private function UserCallsAnalytic($Parameters, $Pagination, $Columns)
    {
        $data['columns'] = array(
            'creation' => 'Data Hora Inicio',
            //'name' => 'Setor',
            'last_name' => 'Nome do usuário',
            'key_remote_id' => 'Número',
            'full_name' => 'Nome do contato',
            'start' => 'Data Hora Abertura',
            'end' => 'Data Hora Encerramento',
            'status' => 'Situação do atendimento'
        );

        if ($Columns) {
            return $data;
        }


        $sql =  "select\n";
        $sql .= "count(chat_list_log.id_chat_list_log) as total\n";
        $sql .= "from chat_list\n";
        $sql .= "inner join contact on chat_list.id_contact = contact.id_contact\n";
        $sql .= "inner join chat_list_log on chat_list.id_chat_list = chat_list_log.id_chat_list\n";
        $sql .= "inner join user on chat_list_log.key_remote_id = user.key_remote_id\n";
        $sql .= "inner join user_group on user.id_user_group = user_group.id_user_group\n";
        $sql .= "where chat_list.is_private = 1\n";

        if (isset($Parameters['date_1'])) {
            $sql .= "and DATE_FORMAT(from_unixtime(chat_list_log.creation),'%Y-%m-%d') = '{$Parameters['date_1']}'\n";
        }

        if (isset($Parameters['number_1'])) {
            $sql .= "and contact.key_remote_id like '%{$Parameters['number_1']}%'\n";
        }

        if (isset($Parameters['name_1'])) {
            $sql .= "and ((user.last_name like '%{$Parameters['name_1']}%') or (contact.full_name like '%{$Parameters['name_1']}%') )\n";
        }

        if (isset($Parameters['name_2'])) {
            $sql .= "and ((user.last_name like '%{$Parameters['name_2']}%') or (contact.full_name like '%{$Parameters['name_2']}%') )\n";
        }

        if (isset($Parameters['sector_1'])) {
            $sql .= "and user_group.name like '%{$Parameters['sector_1']}%'\n";
        }

        if (isset($Parameters['date_range_1'])) {
            $sql .= "and DATE_FORMAT(from_unixtime(chat_list_log.creation),'%Y-%m-%d') between '{$Parameters['date_range_1']['start']}' and '{$Parameters['date_range_1']['end']}' \n";
        }

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            $total = $result->result_array()[0]['total'];

            if ($total > 0) {

                $data['total'] = $total;

                $replace = "count(chat_list_log.id_chat_list_log) as total\n";

                $query = "DATE_FORMAT(from_unixtime(chat_list_log.creation),'%d/%m/%Y %T') as creation,\n";
                $query .= "user_group.name,\n";
                $query .= "DATE_FORMAT(from_unixtime(chat_list_log.start),'%d/%m/%Y %T') as start,\n";
                $query .= "DATE_FORMAT(from_unixtime(chat_list_log.end),'%d/%m/%Y %T') as end,\n";
                $query .= "contact.key_remote_id,\n";
                $query .= "contact.full_name,\n";
                $query .= "user.last_name,\n";
                $query .= "case\n";
                $query .= "when chat_list_log.end is null then 'Em atendimento' else 'Finalizado'\n";
                $query .= "end status\n";

                $sql = str_replace($replace, $query, $sql);

                $sql .= "order by chat_list_log.creation desc\n";

                if (isset($Pagination)) {
                    $sql .= "limit {$Pagination['start']},{$Pagination['length']}";
                }

                $data['values'] = $this->db->query($sql)->result_array();
                return $data;
            }

            $data['total'] = 0;
            $data['values'] = '';
            return $data;
        }
    }

    // Retorna o status dos usuários on/off data/hora //
    private function UsersStatus($Parameters, $Pagination, $Columns)
    {
        $data['columns'] = array(
            'full_name' => 'Nome do contato',
            'setor' => 'Setor',
            'presence' => 'status'
        );

        if ($Columns) {
            return $data;
        }

        $sql = "select\n";
        $sql .= "count(contact.key_remote_id) as total\n";
        $sql .= "from contact\n";
        $sql .= "inner join channel on contact.id_channel = channel.id_channel\n";
        $sql .= "inner join user on user.key_remote_id = contact.key_remote_id\n";
        $sql .= "inner join user_group on user_group.id_user_group = user.id_user_group\n";
        $sql .= "inner join config on channel.id_channel = config.id_channel\n";
        $sql .= "where contact.is_private = 2\n";

        if (isset($Parameters['date_1'])) {
            $sql .= "and DATE_FORMAT(DATE_ADD(from_unixtime(contact.timestamp), INTERVAL config.timezone HOUR),'%Y-%m-%d') between '{$Parameters['date_1']}' and current_date()\n";
        }

        if (isset($Parameters['date_range_1'])) {
            $sql .= "and DATE_FORMAT(DATE_ADD(from_unixtime(contact.timestamp), INTERVAL config.timezone HOUR),'%Y-%m-%d') between '{$Parameters['date_range_1']['start']}' and '{$Parameters['date_range_1']['end']}' \n";
        }

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            $total = $result->result_array()[0]['total'];

            if ($total > 0) {

                $data['total'] = $total;

                $replace = "count(contact.key_remote_id) as total\n";

                $query = "contact.full_name,\n";
                $query .= "user_group.name as setor,\n";
                $query .= "case\n";
                $query .= "	when contact.is_group = 1 then\n";
                $query .= "case when contact.presence = 'available' then 'Online'\n";
                $query .= "else\n";
                $query .= "case\n";
                $query .= "	when DATE_FORMAT(from_unixtime(contact.timestamp),'%Y-%m-%d') = current_date() then concat('Visto por último hoje às: ',DATE_FORMAT(DATE_ADD(from_unixtime(contact.timestamp), INTERVAL config.timezone HOUR),'%H:%i'))\n";
                $query .= "	when DATE_FORMAT(from_unixtime(contact.timestamp),'%Y-%m-%d') = DATE_SUB(current_date(), INTERVAL 1 DAY) then concat('Visto por ontem às: ',DATE_FORMAT(DATE_ADD(from_unixtime(contact.timestamp), INTERVAL config.timezone HOUR),'%H:%i'))\n";
                $query .= "else\n";
                $query .= "	concat('Visto por último: ',DATE_FORMAT(DATE_ADD(from_unixtime(contact.timestamp), INTERVAL config.timezone HOUR),'%d/%m/%Y %H:%i'))\n";
                $query .= "end end\n";
                $query .= "end presence\n";


                $sql = str_replace($replace, $query, $sql);

                $sql .= "order by DATE_FORMAT(DATE_ADD(from_unixtime(contact.timestamp), INTERVAL config.timezone HOUR),'%d/%m/%Y %H:%i') desc \n";

                if (isset($Pagination)) {
                    $sql .= "limit {$Pagination['start']},{$Pagination['length']}";
                }

                $data['values'] = $this->db->query($sql)->result_array();
                return $data;
            }

            $data['total'] = 0;
            $data['values'] = '';
            return $data;
        }
    }

    // Retorna o tempo que o usario está conectado //
    private function UsersOnlineTime($Parameters, $Pagination, $Columns)
    {
        $data['columns'] = array(
            'last_name' => 'Nome do usuário',
            'setor' => 'Setor',
            'timediff' => 'Tempo online'
        );

        if ($Columns) {
            return $data;
        }

        $sql = "select count(user.id_user) as total from user\n";

        if (isset($Parameters['name_1'])) {
            $sql .= "where (user.last_name like '%{$Parameters['name_1']}%')\n";
        }

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {
            $total = $result->result_array()[0]['total'];

            if ($total > 0) {

                $data['total'] = $total;

                $sql = "select\n";
                $sql .= "user.last_name,\n";
                $sql .= "user_group.name as setor,\n";
                $sql .= "sec_to_time(\n";
                $sql .= "sum(\n";
                $sql .= "TIMESTAMPDIFF(second,from_unixtime(user_timestamp.start),from_unixtime(\n";
                $sql .= "case when user_timestamp.end is null then UNIX_TIMESTAMP() else user_timestamp.end end)))) timediff\n";
                $sql .= "from user\n";
                $sql .= "inner join user_timestamp on user.id_user = user_timestamp.id_user\n";
                $sql .= "inner join user_group on user.id_user_group = user_group.id_user_group\n";

                if (isset($Parameters['name_1'])) {
                    $sql .= "where (user.last_name like '%{$Parameters['name_1']}%')\n";
                }

                $sql .= "group by user.last_name, user_group.name\n";

                if (isset($Pagination)) {
                    $sql .= "limit {$Pagination['start']},{$Pagination['length']}";
                }

                $data['values'] = $this->db->query($sql)->result_array();
                return $data;
            }

            $data['total'] = 0;
            $data['values'] = '';
            return $data;
        }
    }
}
