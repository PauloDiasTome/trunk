<?php

class Script_model extends TA_model
{
    function GetInfo()
    {       
        $sql = "select * \n";
        $sql .= "from talkall_admin.company\n";        

        $result = $this->db->query($sql);

        $data = $result->result_array();

        return $data;
    }
}
