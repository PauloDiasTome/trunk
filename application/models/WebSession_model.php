<?php

class WebSession_model extends TA_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    function FindWebSession($WebSessionToken)
    {
        $sql = "SELECT * FROM talkall_admin.web_session where browser_token = '$WebSessionToken';\n";

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);

        $result = $this->talkall_admin->query($sql);

        if( $result->num_rows() > 0 ) {

            if( $result->result_array()[0]['os'] != $this->agent->browser() && $result->result_array()[0]['ip'] != $this->input->ip_address() ){
                return false;
            } else {
                return true;
            }

        } else {
            return false;
        }
    }

    function GetInfoWebSession($WebSessionToken){

        $sql = "SELECT * FROM talkall_admin.web_session where browser_token = '$WebSessionToken';\n";

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);

        $result = $this->talkall_admin->query($sql);

        return $result->result_array()[0];
    }
}
