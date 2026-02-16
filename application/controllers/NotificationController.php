<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class NotificationController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	function GetNotification()
	{
		$this->load->model('Notification_model', '', TRUE);

		$data = $this->input->post();
		$query = $this->Notification_model->GetUserNotification($data['key_remote_id']);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($query));
	}


	function MarkAsRead()
	{
		$post = $this->input->post();
		$this->load->model('Notification_model', '', TRUE);

		$this->Notification_model->SetNotificationRead($post);
		$this->session->unset_userdata('notify');

		return true;
	}
}
