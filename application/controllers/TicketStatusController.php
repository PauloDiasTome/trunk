<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class TicketStatusController extends TA_Controller
{
	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("ticket");

		$this->load->model('TicketStatus_model', '', TRUE);
		$this->lang->load('ticket_status_lang', $this->session->userdata('language'));
	}

	function Index()
	{
		$data['main_content'] = 'pages/ticket_status/find';
		$data['view_name'] = $this->lang->line("ticket_status_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("ticket_status.js");

		$this->load->view('template',  $data);
	}

	function Get()
	{
		$param = $this->input->post();
		$result = $this->TicketStatus_model->Get($param);

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
		$data = $this->input->post();

		$this->form_validation->set_rules('input-name', $this->lang->line("ticket_status_name"), 'trim|required|min_length[3]alpha_numeric_spaces|max_length[100]');

		if ($this->form_validation->run() == true) {

			if ($key_id > 0) {
				$this->TicketStatus_model->Edit($key_id, $data);
			} else {
				$this->TicketStatus_model->Add($data);
			}

			redirect("ticket/status", 'refresh');
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
		$data['main_content'] = 'pages/ticket_status/add';
		$data['view_name'] = $this->lang->line("ticket_status_waba_add");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("ticket_status.js");

		$this->load->view('template',  $data);
	}

	function Edit($key_id)
	{
		$data['title'] = "TalkAll | Editar";
		$data['id'] = ($key_id);
		$data['data'] = $this->TicketStatus_model->GetById($key_id)[0];

		$data['main_content'] = 'pages/ticket_status/edit';
		$data['view_name'] = $this->lang->line("ticket_status_waba_edit");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("ticket_status.js");

		$this->load->view('template',  $data);
	}

	function Delete($key_id)
	{
		$data = $this->TicketStatus_model->Delete($key_id);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}
}
