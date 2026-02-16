<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class CalendarController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("calendar");

		$this->load->model('Calendar_model', '', TRUE);
	}

	function Index()
	{
		$data['main_content'] = 'pages/calendar/find';
		$data['view_name'] = 'Calendário';
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("label.js");

		$this->load->view('template',  $data);
	}


	function Get()
	{
		$post = $this->input->post();

		$records = $this->Calendar_model->Count($post['text']  ?? "");
		$query = $this->Calendar_model->Get($post['text']  ?? "", $post['start'], $post['length']);

		$data  = array(
			"draw" => $post['draw'],
			"recordsTotal" => $records[0]['count'],
			"recordsFiltered" => $records[0]['count'],
			"data" => $query
		);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}


	function Save($key_id)
	{
		$data = $this->input->post();

		if ($key_id > 0) {
			$this->Calendar_model->Edit($key_id, $data);
		} else {
			$this->Calendar_model->Add($data);
		}
	}


	function Form()
	{
		$data['main_content'] = 'pages/calendar/form';
		$data['view_name'] = "Edit";
		$data['js'] = array("calendar.js");

		$this->load->view('template',  $data);
	}


	function Add()
	{
		$data['main_content'] = 'pages/calendar/add';
		$data['view_name'] = "Edit";
		$data['js'] = array("calendar.js");

		$this->load->view('template',  $data);
	}


	function Edit($key_id)
	{
		$data['title'] = "TalkAll | Editar";
		$data['id'] = ($key_id);

		$info = $this->Calendar_model->GetInf($key_id);

		$data['data'] = $info[0];

		$data['main_content'] = 'pages/calendar/edit';
		$data['view_name'] = "Edit";
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("calendar.js");

		$this->load->view('template',  $data);
	}


	function Delete($key_id)
	{
		$this->load->model('Calendar_model', '', TRUE);
		$this->Calendar_model->Delete($key_id);
	}
}
