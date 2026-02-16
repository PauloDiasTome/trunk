<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class UserCallController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("usercall");

		$this->load->model('UserCall_model', '', TRUE);
		$this->lang->load('usercall_lang', $this->session->userdata('language'));
	}


	function Index()
	{
		$data['main_content'] = 'pages/usercall/find';
		$data['view_name'] = $this->lang->line("usercall_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("usercall.js");

		$this->load->view('template',  $data);
	}


	function Get()
	{
		$post = $this->input->post();

		$records = $this->UserCall_model->Count($post);
		$query = $this->UserCall_model->Get($post);

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

		$this->form_validation->set_rules('input-name', $this->lang->line("usercall_nome"), 'trim|required|min_length[3]|max_length[30]');
		$this->form_validation->set_rules('input-limit', $this->lang->line("usercall_simultaneous_service_limit"), 'trim|required|min_length[1]|max_length[3]|greater_than_equal_to[1]|less_than_equal_to[100]');

		if ($this->form_validation->run() == true) {

			if ($key_id > 0) {
				$this->UserCall_model->Edit($key_id, $data);
			} else {
				$this->UserCall_model->Add($data);
			}

			redirect("usercall", 'refresh');
		} else {
			if ($key_id > 0) {
				$this->Edit($key_id);
			} else {
				$this->Add();
			}
		}
	}


	function Add()
	{
		$data['main_content'] = 'pages/usercall/add';
		$data['view_name'] = $this->lang->line("usercall_waba_add");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("usercall.js");

		$this->load->view('template',  $data);
	}


	function Edit($key_id)
	{
		$data['title'] = "TalkAll | Editar";
		$data['id'] = ($key_id);

		$info = $this->UserCall_model->GetInf($key_id);

		$data['data'] = $info[0];
		$data['main_content'] = 'pages/usercall/edit';
		$data['view_name'] = $this->lang->line("usercall_waba_edit");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("usercall.js");

		$this->load->view('template',  $data);
	}


	function Delete($key_id)
	{
		$data	= $this->UserCall_model->Delete($key_id);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}
}
