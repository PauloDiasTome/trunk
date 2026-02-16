<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class KanbanController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();
	}


	function kanbanAttendance()
	{
		parent::checkPermission("kanban_attendance");
		$this->lang->load('kanban_attendance_lang', $this->session->userdata('language'));
		$this->load->view('pages/kanban/attendance/kanban.php');
	}


	function kanbanCommunication()
	{
		parent::checkPermission("kanban_communication");
		$this->lang->load('kanban_communication_lang', $this->session->userdata('language'));
		$this->load->view('pages/kanban/communication/kanban.php');
	}
}
