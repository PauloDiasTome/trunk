<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class TicketTypeController extends TA_Controller
{
	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("ticket");

		$this->load->model('TicketType_model', '', TRUE);
		$this->lang->load('ticket_type_lang', $this->session->userdata('language'));
	}

	function Index()
	{
		$data['main_content'] = 'pages/ticket_type/find';
		$data['view_name'] = $this->lang->line("ticket_type_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("ticket_type.js");
		$data['id'] = 0;

		$this->load->view('template',  $data);
	}

	function Get()
	{
		$param = $this->input->post();
		$result = $this->TicketType_model->Get($param);

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

	function GetSubtype($key_id)
	{
		$param = $this->input->post();
		$result = $this->TicketType_model->GetSubtype($key_id, $param);

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

		$this->form_validation->set_rules('input-name', $this->lang->line("ticket_type_name"), 'trim|required|min_length[3]alpha_numeric_spaces|max_length[100]');
		$this->form_validation->set_rules('ticket_sla', $this->lang->line("ticket_type_ticket_sla"), 'trim|required');
		$this->form_validation->set_rules('user_group', $this->lang->line("ticket_type_sector"), 'trim|required');

		if ($this->form_validation->run() == true) {

			if ($key_id > 0) {
				$this->TicketType_model->Edit($key_id, $data);
			} else {
				$this->TicketType_model->Add($data);
			}

			redirect("ticket/type", 'refresh');
		} else {
			if ($key_id > 0) {
				$this->Edit($key_id);
			} else {
				$this->Add();
			}
		}
	}

	function SaveSubtype($key_id)
	{
		$data = $this->input->post();

		$this->form_validation->set_rules('input-name', $this->lang->line("ticket_type_name"), 'trim|required|min_length[3]alpha_numeric_spaces');
		$this->form_validation->set_rules('ticket_sla', $this->lang->line("ticket_type_ticket_sla"), 'trim|required');

		if ($this->form_validation->run() == true) {

			if ($data['screen'] == "Edit") {
				$this->TicketType_model->Edit($key_id, $data);

				$res = $this->TicketType_model->ListSubtype($key_id);
				redirect("ticket/type/{$res[0]['id_subtype']}", 'refresh');
			} else {

				$this->TicketType_model->AddSubtype($key_id, $data);
				redirect("ticket/type/{$key_id}", 'refresh');
			}
		} else {
			if ($data['screen'] == "Edit") {
				$this->Edit($key_id);
			} else {
				$this->Add($key_id);
			}
		}
	}

	function Add($key_id = 0)
	{
		$this->load->model('UserGroup_model', '', true);
		$this->load->model('TicketSla_model', '', true);

		$data['main_content'] = 'pages/ticket_type/add';
		$data['view_name'] = $this->lang->line("ticket_type_waba_add");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("ticket_type.js");
		$data['group'] = $this->UserGroup_model->List();
		$data['ticket_sla'] = $this->TicketSla_model->List();
		$data['id'] = $key_id;
		$data['is_subtype'] = $key_id != 0 ? "true" : "false";

		$this->load->view('template',  $data);
	}

	function Subtype($key_id)
	{
		$info = $this->TicketType_model->ListSubtype($key_id);

		$data['main_content'] = 'pages/ticket_type/find';
		$data['view_name'] = $this->lang->line("ticket_type_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("ticket_subtype.js");
		$data['id'] = $key_id;
		$data['name'] = $info[0]['name'];

		$this->load->view('template',  $data);
	}

	function Edit($key_id)
	{
		$this->load->model('UserGroup_model', '', true);
		$this->load->model('TicketSla_model', '', true);

		$data['main_content'] = 'pages/ticket_type/edit';
		$data['view_name'] = $this->lang->line("ticket_type_waba_edit");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("ticket_type.js");
		$data['id'] = ($key_id);
		$data['data'] = $this->TicketType_model->GetById($key_id);
		$data['group'] =  $this->UserGroup_model->List();
		$data['ticket_sla'] = $this->TicketSla_model->List();
		$data['is_subtype'] = $this->TicketType_model->isSubtype($key_id);

		$this->load->view('template',  $data);
	}

	function Delete($key_id)
	{
		$data = $this->TicketType_model->Delete($key_id);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}
}
