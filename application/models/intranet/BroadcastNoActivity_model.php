<?php

class BroadcastNoActivity_model extends TA_model
{
    function __construct()
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
        $dbs = $this->GetDbs();
        $list_ret = [];

        for ($i=0; $i < count($dbs); $i++) { 
            $db_data = $this->GetDataDb($dbs[$i]['banco']);
            
            for ($i_dt=0; $i_dt < count($db_data); $i_dt++) { 
                if($db_data[$i_dt]['situacao'] == 1 and $db_data[$i_dt]['result'] != 'ok'){
                    array_push(
                        $list_ret, $db_data[$i_dt]
                    );            
                }
            }
        }                
        return $list_ret;        
    }

    function GetDbs()
    {
        $sql = "SELECT id_empresa, nome_fantasia, banco FROM talkall_admin.tb_empresa";
        
        $result = $this->db->query($sql);
        $data = $result->result_array();

        return $data;
    }

    function GetDataDb($db, $dias = 3)
    {
        $sql = "SELECT \n";
        $sql .= " * from ( select \n";
        $sql .= "tr_f.id_filial, fl.wa, fl.situacao, fl.razao_social,\n";
        $sql .= "case\n";
        $sql .= "when\n";
        $sql .= "DATEDIFF( DATE(now()),max(tr_f.dt_cadastro) ) >= {$dias}\n";
        $sql .= "THEN concat( DATEDIFF( DATE(now()), max(tr_f.dt_cadastro) ), ' dias' )\n";
        $sql .= "else 'ok' \n";
        $sql .= "end as result\n";
        $sql .= "FROM {$db}.tb_fila_transmissao as tr_f\n";
        $sql .= "inner join {$db}.tb_filial as fl\n";
        $sql .= "on fl.id_filial = tr_f.id_filial\n";
        $sql .= "group by tr_f.id_filial \n";  
        $sql .= ") as query where result != 'ok' ";

        $result = $this->db->query($sql);
        $data = $result->result_array();

        return $data;
    }
}
