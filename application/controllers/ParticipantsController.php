<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class ParticipantsController extends TA_Controller
{
	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("participants");
	}
	
	function Get($key_id)
	{
		$post = $this->input->post();

		$this->load->model('Participants_model', '', TRUE);

		$query = $this->Participants_model->Get($key_id);

		return $query->result_array();
	}
}
