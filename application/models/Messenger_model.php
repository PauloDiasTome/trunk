<?php

class Messenger_model extends TA_model
{

    public function __construct()
    {
        parent::__construct();
    }

    function CheckIfHasBusinessAccount($id = 0)
    {
        $result = $this->db->query("select count(type) as total, type from channel where type = {$id} and status = 1 group by type");

        return $result->result_array();
    }
}
