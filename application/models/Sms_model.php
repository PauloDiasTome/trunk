<?php

class Sms_model extends TA_model
{

  public function __construct()
  {
    parent::__construct();
  }


  function SendSms($id_company, $phone, $message)
  {
    $date = new DateTime();
    $this->talkall_admin = $this->load->database(SetdatabaseRemote('talkall_admin', "192.168.190.40"), TRUE);

    $values = [
      'creation' => $date->getTimestamp(),
      'id_company' => $id_company,
      'key_group' => Token(),
      'key_id' => Token(),
      'to' => $phone,
      'msg' => $message,
      'status' => 1
    ];

    $this->talkall_admin->insert('sms_service', $values);
  }
}
