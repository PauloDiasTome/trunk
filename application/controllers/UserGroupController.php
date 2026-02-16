<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class UserGroupController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("usergroup");

		$this->load->model('UserGroup_model', '', TRUE);
		$this->lang->load('usergroup_lang', $this->session->userdata('language'));
	}

	function Index()
	{
		$data['main_content'] = 'pages/usergroup/find';
		$data['view_name'] = $this->lang->line("usergroup_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("usergroup.js");

		$this->load->view('template',  $data);
	}

	function Get()
	{
		$post = $this->input->post();

		$records = $this->UserGroup_model->Count($post['text']);
		$query = $this->UserGroup_model->Get($post['text'] ?? "",$post['start'], $post['length'], $post['order'][0]['column'], $post['order'][0]['dir']);

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

		$this->form_validation->set_rules('input-name', $this->lang->line("usergroup_nome"), 'trim|required|min_length[2]|max_length[100]');

		if ($this->form_validation->run() == true) {

			if ($key_id > 0) {
				$this->UserGroup_model->Edit($key_id, $data);
			} else {
				$this->UserGroup_model->Add($data);
			}

			redirect("usergroup", 'refresh');
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
		$data['main_content'] = 'pages/usergroup/add';
		$data['view_name'] = $this->lang->line("usergroup_waba_add");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("usergroup.js");

		$this->load->view('template',  $data);
	}

	function Edit($key_id)
	{
		$data['title'] = "TalkAll | Editar";
		$data['id'] = ($key_id);

		$info = $this->UserGroup_model->GetInf($key_id);
		$data['data'] = $info[0];

		$data['main_content'] = 'pages/usergroup/edit';
		$data['view_name'] = $this->lang->line("usergroup_waba_edit");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("usergroup.js");

		$this->load->view('template',  $data);
	}

	function Delete($key_id)
	{
		$this->load->model('UserGroup_model', '', TRUE);
		$data = $this->UserGroup_model->Delete($key_id);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}
}
