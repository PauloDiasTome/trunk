<?php

class LastCurrentBroadcast_model extends CI_Model
{
    function Get($bot_id)
    {
        $sql = "select talkall_admin.tb_empresa.banco from talkall_admin.tb_empresa inner join talkall_admin.tb_empresa_integracao on talkall_admin.tb_empresa.id_empresa = talkall_admin.tb_empresa_integracao.id_empresa\n";
        $sql .= "where talkall_admin.tb_empresa_integracao.bot_id = '$bot_id'\n";

        $this->remoteDB = $this->load->database(
            SetdatabaseRemote(
                "talkall_admin",
                "appenvio.talkall.com.br",
            ),
            TRUE
        );

        $result = $this->remoteDB->query($sql);

        if ($result->num_rows() > 0) {

            $this->remoteDB = $this->load->database(
                SetdatabaseRemote(
                    $result->row()->banco,
                    "appenvio.talkall.com.br",
                ),
                TRUE
            );

            $sql = "select\n";
            $sql .= "tb_fila_transmissao.type media_type,\n";
            $sql .= "tb_fila_transmissao.file media_url,\n";
            $sql .= "tb_fila_transmissao.texto media_caption\n";
            $sql .= "from tb_fila_transmissao inner join tb_filial on tb_fila_transmissao.id_filial = tb_filial.id_filial\n";
            $sql .= "where tb_fila_transmissao.situacao = 2 and tb_filial.wa = '$bot_id'\n";
            $sql .= "and addtime(tb_fila_transmissao.dt_limite,tb_fila_transmissao.hr_limite) between now() and addtime(tb_fila_transmissao.dt_limite,tb_fila_transmissao.hr_limite) order by tb_fila_transmissao.id_fila_transmissao desc limit 2;\n";

            $result = $this->remoteDB->query($sql);

            return $result->result_array();
        } else {
            return null;
        }
    }
}
