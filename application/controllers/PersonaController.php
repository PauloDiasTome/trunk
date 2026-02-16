<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class PersonaController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("persona");

		$this->load->model('Persona_model', '', TRUE);
		$this->lang->load('persona_lang', $this->session->userdata('language'));
	}

	function Index()
	{
		$data['main_content'] = 'pages/persona/find';
		$data['view_name'] = $this->lang->line("persona_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("persona.js");

		$this->load->view('template',  $data);
	}

	function Get()
	{
		$param = $this->input->post();
		$result = $this->Persona_model->Get($param);

		$data  = array(
			"draw" => $param["draw"],
			"recordsTotal" => $result["count"],
			"recordsFiltered" => $result["count"],
			"data" => $result["query"]
		);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}

	function Save()
	{
		$data = $this->input->post();

		$this->form_validation->set_rules('name', $this->lang->line("persona_name"), 'trim|required|min_length[3]alpha_numeric_spaces|max_length[100]');
		$this->form_validation->set_rules('id_channel', $this->lang->line("persona_add_select_channel"), 'trim|required');

		if ($this->form_validation->run() == true) {

			if ($data["type"] == "add")
				$response = $this->Persona_model->Add($data);

			if ($data["type"] == "edit")
				$response = $this->Persona_model->Edit($data);

			return $this->output
				->set_content_type('application/json')
				->set_status_header(200)
				->set_output(json_encode($response));
		} else {
			if ($data["type"] == "add")
				$this->Add();

			if ($data["type"] == "edit")
				$this->Edit($data["id_persona"]);
		}
	}

	function Add()
	{
		$data['main_content'] = 'pages/persona/add';
		$data['view_name'] = $this->lang->line("persona_waba_add");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("persona.js", "util.js");
		$data['css'] = array("persona.css");
		$data['channel'] = $this->Persona_model->ListChannel();

		$this->load->view('template',  $data);
	}

	function Edit($key_id)
	{
		$data['main_content'] = 'pages/persona/edit';
		$data['view_name'] = $this->lang->line("persona_waba_edit");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("persona.js", "util.js");
		$data['css'] = array("persona.css");
		$data['id'] = $key_id;
		$data['persona_info'] = $this->Persona_model->GetById($key_id);

		$this->load->view('template',  $data);
	}

	function ImportContacts()
	{
		$param = $this->input->post();
		$data = $this->Persona_model->ImportContacts($param);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}

	function ListContacts()
	{
		$param = $this->input->post();
		$data = $this->Persona_model->ListChannelContacts($param);

		foreach ($data as &$row) {
			$this->loadProfileImage($row, $row["key_remote_id"]);
		}

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}

	function ListParticipants()
	{
		$id = $this->input->post();
		$data = $this->Persona_model->ListParticipants($id);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}

	function LoadProfileImage(&$row, $key_remote_id)
	{
		$path = "profiles/{$key_remote_id}.jpeg";

		if (file_exists($path) && filesize($path) > 0) {
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$file = file_get_contents($path);
			$row["profile"] = "data:image/" . $type . ";base64," . base64_encode($file);
		} else {
			$row["profile"] = base_url() . "assets/img/avatar.svg";
		}
	}

	function DeleteParticipants()
	{
		$param = $this->input->post();
		$data = $this->Persona_model->DeleteParticipants($param["id_persona"]);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}

	function Delete($key_id)
	{
		$data = $this->Persona_model->Delete($key_id);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}
}
