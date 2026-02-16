<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class MessengerController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("messenger");

		$this->lang->load('messenger_lang', $this->session->userdata('language'));
	}


	function Index()
	{
		$this->load->model('Messenger_model', '', TRUE);
		$business = $this->Messenger_model->CheckIfHasBusinessAccount(12);
		$normal = $this->Messenger_model->CheckIfHasBusinessAccount(2);

		if ($normal != null) {
			$normal = $normal[0];
		} else {
			$normal = ['total' => 0];
		}
		if ($business != null) {
			$business = $business[0];
		} else {
			$business = ['total' => 0];
		}

		$data['data']['normal'] = $normal;
		$data['data']['business'] = $business;

		$this->load->view('pages/messenger/chat', $data);
	}


	function GetResponsible()
	{
		$this->load->model('User_model', '', TRUE);
		$users = $this->User_model->List();

		echo json_encode($users);
		exit();
	}


	function OpenChatMessenger($key_remote_id)
	{

		$this->load->model('Messenger_model', '', TRUE);
		$business = $this->Messenger_model->CheckIfHasBusinessAccount(12);
		$normal = $this->Messenger_model->CheckIfHasBusinessAccount(2);

		if ($normal != null) {
			$normal = $normal[0];
		} else {
			$normal = ['total' => 0];
		}
		if ($business != null) {
			$business = $business[0];
		} else {
			$business = ['total' => 0];
		}

		$data['data']['normal'] = $normal;
		$data['data']['business'] = $business;
		$data['data']['key_remote_id'] = $key_remote_id;
		$data['data']['text'] = $_GET['text'];

		$this->load->view('pages/messenger/chat', $data);
	}
}
