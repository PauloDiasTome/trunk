<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class ContactController extends TA_Controller
{
	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("contact");

		$this->load->model('Contact_model', '', TRUE);
		$this->lang->load('contact_lang', $this->session->userdata('language'));
	}

	function Index()
	{
		$data['labels'] =  $this->Contact_model->ListLabels();
		$data['users'] =  $this->Contact_model->ListUsers();
		$data['channels'] = $this->Contact_model->ListChannels();
		$data['personas'] = $this->Contact_model->GetPersonas();

		$data['main_content'] = 'pages/contact/find';
		$data['view_name'] = $this->lang->line("contact_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['css'] = array("contact.css");
		$data['js'] = array("contact.js");

		$this->load->view('template', $data);
	}

	function Get()
	{
		$param = $this->input->post();
		$result = $this->Contact_model->Get($param);

		$data  = array(
			"draw" => $param['draw'],
			"recordsTotal" => $result['count'],
			"recordsFiltered" => $result['count'],
			"data" => $result["query"]
		);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}

	function Save($key_id)
	{
		$post = $this->input->post();

		$this->form_validation->set_rules('input-full-name', $this->lang->line("contact_name"), 'trim|required|min_length[3]|max_length[100]');
		$this->form_validation->set_rules('input-order', $this->lang->line("contact_order"), 'trim|max_length[6]');
		$this->form_validation->set_rules('input-email', $this->lang->line("contact_email"), 'trim|max_length[55]');
		$this->form_validation->set_rules('input-note', $this->lang->line("contact_notes"), 'trim|max_length[550]');

		if ($this->form_validation->run()) {
			$this->Contact_model->Edit($key_id, $post);
			redirect("contact", 'refresh');
			return;
		}

		$this->Edit($key_id);
	}

	function SaveLabels($id_contact)
	{
		$post = $this->input->post();
		$labels = $post['id_label'] ?? [];

		$labels_data = $this->Contact_model->updateContactLabels($id_contact, $labels);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode([
				'status' => 'success',
				'names' => $labels_data['names'],
				'colors' => $labels_data['colors']
			]));
	}

	function Edit($key_id)
	{
		$this->load->model('User_model', '', TRUE);
		$this->load->model('UserGroup_model', '', true);

		$data['id'] = $key_id;
		$data['users'] = $this->User_model->List();
		$data['group'] = $this->UserGroup_model->List();
		$data['labels'] = $this->Contact_model->ListLabels();
		$data['data'] = $this->Contact_model->GetById($key_id)[0];
		$data['contact_labels'] = $this->Contact_model->GetContactLabels($key_id);

		$data['main_content'] = 'pages/contact/edit';
		$data['view_name'] = $this->lang->line("contact_waba_edit");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("contact.js");

		$this->load->view('template', $data);
	}

	function ActionContact()
	{
		$post = $this->input->post();

		if ($post["action"] === "attendance")
			$data = $this->Contact_model->VerifyContactAttendance($post['data']);

		if ($post["action"] === "block")
			$data = $this->Contact_model->BlockContact($post['data']);

		if ($post["action"] === "unblock")
			$data = $this->Contact_model->UnblockContact($post['data']);

		if ($post["action"] === "delete")
			$data = $this->Contact_model->DeleteContact($post['data']);

		if ($post["action"] === "persona")
			$data = $this->Contact_model->AddPersona($post);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}

	function SearchSelected()
	{
		$param = $this->input->post();
		$data = $this->Contact_model->Get($param);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}

	function GetAllContacts()
	{
		$data = $this->Contact_model->GetAllContacts();
		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}

	function GetPersonas()
	{
		$post = $this->input->post();

		$channels_raw = $post['channels'] ?? null;
		$channels = is_string($channels_raw) ? json_decode($channels_raw, true) : $channels_raw;

		$data = $this->Contact_model->GetPersonas($channels);

		$this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}
}
