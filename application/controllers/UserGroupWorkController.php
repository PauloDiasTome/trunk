<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class UserGroupWorkController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();

		parent::checkPermission("group_contact");

		$this->lang->load('usergroup_work_lang', $this->session->userdata('language'));
	}


	function Index()
	{
		$data['main_content'] = 'pages/user/group/work/find';
		$data['view_name'] = 'Grupo de Trabalho';
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("UserGroupWork.js");

		$this->load->view('template',  $data);
	}


	function Get()
	{
		$post = $this->input->post();

		$this->load->model('UserGroupWork_model', '', TRUE);

		$records = $this->UserGroupWork_model->Count($post['text']);
		$query = $this->UserGroupWork_model->Get($post['text'], $post['start'], $post['length']);

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

		$this->form_validation->set_rules('input-name', 'Nome', 'trim|required|min_length[3]');

		if ($this->form_validation->run() == true) {

			$this->load->model('UserGroupWork_model', '', TRUE);
			if ($key_id > 0) {
				$this->UserGroupWork_model->Edit($key_id, $data);
			} else {
				$this->UserGroupWork_model->Add($data);
			}

			redirect("user/group/work", 'refresh');
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
		$this->load->model('Contact_model', '', TRUE);
		$query = $this->Contact_model->GetAllInternalContact();

		foreach ($query as &$row) {
			$path = "profiles/" . $row['key_remote_id'] . ".jpeg";
			if (file_exists($path) == true && filesize($path) > 0) {
				$type = pathinfo($path, PATHINFO_EXTENSION);
				$file = file_get_contents($path);
				$row['profile'] = 'data:image/' . $type . ';base64,' . base64_encode($file);
			} else {
				$row['profile'] = base_url() . 'assets/img/avatar.svg';
			}
		}

		$data['main_content'] = 'pages/user/group/work/add';
		$data['view_name'] = "Add";
		$data['sidenav'] = array('');
		$data['js'] = array("UserGroupWork.js");
		$data['view_name'] = "Add";
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['data'] = $query;

		$this->load->view('template',  $data);
	}


	function Edit($key_id)
	{
		$data['title'] = "TalkAll | Editar";
		$data['id'] = ($key_id);

		$this->load->model('UserGroupWork_model', '', TRUE);
		$info = $this->UserGroupWork_model->GetInf($key_id);

		$this->load->model('Contact_model', '', TRUE);
		$query = $this->Contact_model->GetAllInternalContact();

		$this->load->model('GroupParticipants_model', '', TRUE);
		$participants = $this->GroupParticipants_model->Get($key_id);

		$data['data'] = $info[0];

		foreach ($participants as &$row) {
			$path = "profiles/" . $row['key_remote_id'] . ".jpeg";
			if (file_exists($path) == true && filesize($path) > 0) {
				$type = pathinfo($path, PATHINFO_EXTENSION);
				$file = file_get_contents($path);
				$row['profile'] = 'data:image/' . $type . ';base64,' . base64_encode($file);
			} else {
				$row['profile'] = base_url() . 'assets/img/avatar.svg';
			}
		}

		foreach ($query as &$row) {
			$path = "profiles/" . $row['key_remote_id'] . ".jpeg";
			if (file_exists($path) == true && filesize($path) > 0) {
				$type = pathinfo($path, PATHINFO_EXTENSION);
				$file = file_get_contents($path);
				$row['profile'] = 'data:image/' . $type . ';base64,' . base64_encode($file);
			} else {
				$row['profile'] = base_url() . 'assets/img/avatar.svg';
			}
		}

		$data['contact'] = $query;
		$data['participants'] = $participants;
		$data['main_content'] = 'pages/user/group/work/edit';
		$data['view_name'] = "Edit";
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("UserGroupWork.js");

		$this->load->view('template',  $data);
	}


	function Delete($key_id)
	{
		$this->load->model('UserGroupWork_model', '', TRUE);
		$this->UserGroupWork_model->Delete($key_id);
	}
}
