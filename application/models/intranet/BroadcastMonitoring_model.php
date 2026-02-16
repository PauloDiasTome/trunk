<?php

class BroadcastMonitoring_model extends TA_model
{
    public function __construct()
    {
        parent::__construct();
        parent::SetRemoteDatabase(
            "appenvio.talkall.com.br",
            "talkall_admin",
            "My@dm1ng3ltdb$"
        );
    }

    function Get()
    {
        $ret = array();
        $sql = "SELECT
                    talkall_admin.tb_empresa.id_empresa,
                    talkall_admin.tb_empresa.razao_social,
                    talkall_admin.tb_empresa.banco
                FROM
                    talkall_admin.tb_empresa
                WHERE
                    talkall_admin.tb_empresa.situacao = 1
                ORDER BY
                    talkall_admin.tb_empresa.banco
                ";

        $data = $this->db->query($sql)->result_array();

        if (count($data) > 0) {
            foreach ($data as $key => $row) {
                $sql = "SELECT 
                            talkall_admin.tb_empresa_integracao.micro,
                            tb_filial.wa,
                            tb_filial.razao_social,
                            tb_filial.id_filial
                        FROM
                            talkall_admin.tb_empresa_integracao
                            INNER JOIN {$row['banco']}.tb_filial ON talkall_admin.tb_empresa_integracao.bot_id = tb_filial.wa
                                                AND tb_filial.situacao = 1
                                                AND talkall_admin.tb_empresa_integracao.situacao = 1";

                $aFilial = $this->db->query($sql)->result_array();

                if (!empty($aFilial)) {

                    $bErro = false;

                    foreach ($aFilial as $filial) {
                        $sql = "SELECT
                                    *
                                FROM
                                    {$row['banco']}.tb_broadcast_list 
                                    LEFT JOIN {$row['banco']}.tb_filial ON tb_broadcast_list.id_filial = tb_filial.id_filial 
                                WHERE
                                    tb_filial.situacao = 1 
                                    AND tb_broadcast_list.data = current_date() 
                                    AND tb_filial.id_filial = {$filial['id_filial']}";

                        $aQuery = $this->db->query($sql)->result_array();

                        if (empty($aQuery)) {
                            array_push($ret, array(
                                'micro' => $filial['micro'],
                                'banco' => $row['banco'],
                                'id_filial' => $filial['id_filial'],
                                'razao_social' => $filial['razao_social'],
                                'bot_id' => $filial['wa'],
                                'dt_agendamento' => '',
                                'broadcast_create' => '',
                                'broadcast' => '',
                                'erro' => '<span style="color:red">Não criou fila (Verificar Emulador)</span>',
                            ));

                            $bErro = true;
                        } else {
                            $sql = "SELECT
                                        talkall_admin.tb_empresa_integracao.micro,
                                        tb_filial.razao_social,
                                        tb_filial.id_filial,
                                        talkall_admin.tb_empresa_integracao.bot_id,
                                        COUNT(tb_broadcast_list.id_broadcast) broadcast_create,
                                        COUNT(CEIL(talkall_admin.tb_empresa_integracao.contact/250)) broadcast
                                    FROM
                                        {$row['banco']}.tb_filial 
                                        LEFT JOIN {$row['banco']}.tb_broadcast_list ON tb_filial.id_filial = tb_broadcast_list.id_filial
                                        INNER JOIN talkall_admin.tb_empresa_integracao ON tb_filial.wa = talkall_admin.tb_empresa_integracao.bot_id
                                    WHERE
                                        tb_filial.situacao = 1 AND tb_broadcast_list.data = current_date()
                                        AND tb_filial.id_filial = {$filial['id_filial']}
                                    GROUP BY
                                        tb_filial.razao_social,
                                        tb_filial.id_filial,
                                        talkall_admin.tb_empresa_integracao.bot_id,
                                        talkall_admin.tb_empresa_integracao.micro,
                                        CEIL(talkall_admin.tb_empresa_integracao.contact/250)";

                            $aQuery = $this->db->query($sql)->result_array();



                            if (!empty($aQuery) && intval($aQuery[0]['broadcast']) > 1) {

                                if ((int) $aQuery[0]['broadcast_create'] != (int) $aQuery[0]['broadcast'] && ((int) $aQuery[0]['broadcast_create'] - 1) != (int) $aQuery[0]['broadcast']) {


                                    if (intval($aQuery[0]['broadcast']) < 80) {
                                        array_push($ret, array(
                                            'micro' => $filial['micro'],
                                            'banco' => $row['banco'],
                                            'id_filial' => $filial['id_filial'],
                                            'razao_social' => $filial['razao_social'],
                                            'bot_id' => $filial['wa'],
                                            'dt_agendamento' => '',
                                            'broadcast_create' => $aQuery[0]['broadcast_create'],
                                            'broadcast' => $aQuery[0]['broadcast'],
                                            'erro' => '<span style="color:red">Filas criadas parcialmente</span>',
                                        ));

                                        $bErro = true;
                                    }
                                }
                            }
                        }

                        if ($bErro == false) {

                            $sql = "SELECT 
                                        *
                                    FROM
                                        {$row['banco']}.tb_fila_transmissao 
                                    WHERE
                                        tb_fila_transmissao.dt_agen = current_date() 
                                        AND current_time() > tb_fila_transmissao.hr_agen
                                        AND tb_fila_transmissao.situacao != 3 
                                        AND tb_fila_transmissao.id_filial = {$filial['id_filial']}";

                            $aQuery = $this->db->query($sql)->result_array();

                            if (!empty($aQuery)) {

                                foreach ($aQuery as $query) {

                                    if ($query['situacao'] == 1) {
                                        array_push($ret, array(
                                            'micro' => $filial['micro'],
                                            'banco' => $row['banco'],
                                            'id_filial' => $filial['id_filial'],
                                            'razao_social' => $filial['razao_social'],
                                            'bot_id' => $filial['wa'],
                                            'dt_agendamento' => $query['hr_agen'],
                                            'broadcast_create' => '',
                                            'broadcast' => '',
                                            'erro' => '<span style="color:blue">Campanha atrasada</span>',
                                        ));
                                    } else {

                                        if ($query['situacao'] == 2) {

                                            $sql = "SELECT
                                                        *
                                                    FROM
                                                        {$row['banco']}.tb_broadcast_send
                                                    WHERE
                                                        tb_broadcast_send.id_fila_transmissao = {$query['id_fila_transmissao']}";

                                            $aQuery = $this->db->query($sql)->result_array();

                                            if (empty($aQuery)) {

                                                array_push($ret, array(
                                                    'micro' => $filial['micro'],
                                                    'banco' => $row['banco'],
                                                    'id_filial' => $filial['id_filial'],
                                                    'razao_social' => $filial['razao_social'],
                                                    'bot_id' => $filial['wa'],
                                                    'dt_agendamento' => $aQuery['hr_agen'],
                                                    'broadcast_create' => '',
                                                    'broadcast' => '',
                                                    'erro' => '<span style="color:red">Campanha processada após criação da fila</span>',
                                                ));
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        return $ret;
    }
}
