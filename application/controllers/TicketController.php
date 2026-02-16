<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class TicketController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("ticket");

		$this->load->model('Ticket_model', '', TRUE);
		$this->load->model('TicketType_model', '', TRUE);
		$this->load->model('TicketStatus_model', '', TRUE);
		$this->lang->load('ticket_lang', $this->session->userdata('language'));
	}


	function Index()
	{
		$ticket_type = $this->TicketType_model->List();
		$ticket_status = $this->TicketStatus_model->List();

		$data['main_content'] = 'pages/ticket/find';
		$data['view_name'] = $this->lang->line("ticket_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("ticket.js");

		$data['ticket_type'] = $ticket_type;
		$data['ticket_status'] = $ticket_status;

		$this->load->view('template',  $data);
	}


	function Get()
	{
		$post = $this->input->post();

		$records = $this->Ticket_model->Count($post['text']  ?? "", $post['ticketType'], $post['ticketStatus'], $post['dt_start'] ?? "", $post['dt_end'] ?? "");
		$query = $this->Ticket_model->Get($post['text']  ?? "", $post['ticketType'], $post['ticketStatus'], $post['dt_start'] ?? "", $post['dt_end'] ?? "", $post['start'], $post['length'], $post['order'][0]['column'], $post['order'][0]['dir']);

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

		$this->form_validation->set_rules('input_ticket_type', $this->lang->line("ticket_type"), 'trim|required');
		$this->form_validation->set_rules('input_ticket_status', $this->lang->line("ticket_status"), 'trim|required');
		$this->form_validation->set_rules('input_ticket_company', $this->lang->line("ticket_company"), 'trim|required');

		if ($this->form_validation->run() == true) {

			if ($key_id > 0) {
				$this->Ticket_model->Edit($key_id, $data);
			} else {
				$this->Ticket_model->Add($data);
			}

			redirect("ticket", 'refresh');
		} else {
			if ($key_id > 0) {
				$this->Edit($key_id, $data['input_ticket_type']);
			} else {
				$this->Add($data['input_ticket_type']);
			}
		}
	}


	function SaveCompany()
	{
		$data = $this->input->post();

		$this->load->model('ClientCompany_model', '', TRUE);

		$this->form_validation->set_rules('input-corporate-name', $this->lang->line("ticket_modal_company_corporate_name"), 'trim|required|min_length[3]');
		$this->form_validation->set_rules('input-fantasy-name', $this->lang->line("ticket_modal_company_fantasy_name"), 'trim|required|min_length[3]');
		$this->form_validation->set_rules('input-cnpj', $this->lang->line("ticket_modal_company_cnpj"), 'trim|required|min_length[14]');

		if ($this->form_validation->run() == true) {

			$response = $this->ClientCompany_model->Add($data);

			return $this->output
				->set_content_type('application/json')
				->set_status_header(200)
				->set_output(json_encode($response));
		}
	}


	function saveTicketType()
	{
		$data = $this->input->post();
		$this->load->model('TicketType_model', '', TRUE);

		$this->form_validation->set_rules('input-name', $this->lang->line("ticket_modal_company_corporate_name"), 'trim|required|min_length[3]');

		if ($this->form_validation->run() == true) {

			$response = $this->TicketType_model->Add($data);

			return $this->output
				->set_content_type('application/json')
				->set_status_header(200)
				->set_output(json_encode($response));
		}
	}


	function saveTicketStatus()
	{
		$data = $this->input->post();

		$this->form_validation->set_rules('input-name', $this->lang->line("ticket_status_name"), 'trim|required|min_length[3]');

		if ($this->form_validation->run() == true) {

			$response = $this->TicketStatus_model->Add($data);

			return $this->output
				->set_status_header(200)
				->set_content_type('application/json')
				->set_output(json_encode($response));
		}
	}


	function Add($id_ticket_type = "")
	{
		$type = $this->TicketType_model->List();
		$status = $this->TicketStatus_model->List();
		$company = $this->Ticket_model->ListCompany();

		$this->load->model('UserGroup_model', '', true);
		$group = $this->UserGroup_model->List();

		$this->load->model('TicketSla_model', '', true);
		$ticket_sla = $this->TicketSla_model->List();



		if ($id_ticket_type != "") {
			// Recuperar os subtype referente a esse ticket type //
			$subtype = $this->Ticket_model->ListSubtype($id_ticket_type);
			$data['recover']['subtype'] = $subtype;
			$data['recover']['id_ticket_type'] = $id_ticket_type;
		}

		$data['type'] = $type;
		$data['status'] = $status;
		$data['company'] = $company;
		$data['group'] = $group;
		$data['ticket_sla'] = $ticket_sla;

		$data['main_content'] = 'pages/ticket/add';
		$data['view_name'] = $this->lang->line("ticket_waba_add");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("ticket.js");
		$data['css'] = array(
			"select" => "msFmultiSelect.css",
		);

		$this->load->view('template',  $data);
	}


	function Edit($key_id, $id_ticket_type = "")
	{
		$data['title'] = "TalkAll | Editar";
		$data['id'] = ($key_id);

		$this->load->model('UserGroup_model', '', true);
		$group = $this->UserGroup_model->List();

		$this->load->model('TicketSla_model', '', true);
		$ticket_sla = $this->TicketSla_model->List();

		$this->load->model('TicketType_model', '', true);

		$info = $this->Ticket_model->GetInf($key_id);
		$event = $this->Ticket_model->GetEvent($key_id);
		$type = $this->TicketType_model->List();
		$status = $this->TicketStatus_model->List();
		$company = $this->Ticket_model->ListCompany();
		$subtype = $this->Ticket_model->ListSubtype($info[0]['id_ticket_type']);

		if ($id_ticket_type != "") {
			// Recuperar os subtype referente a esse ticket type //
			$subtype = $this->Ticket_model->ListSubtype($id_ticket_type);
			$data['recover']['subtype'] = $subtype;
			$data['recover']['id_ticket_type'] = $id_ticket_type;
		}

		$data['info'] = $info[0];
		$data['event'] = $event;
		$data['type'] = $type;
		$data['status'] = $status;
		$data['company'] = $company;
		$data['subtype'] = $subtype;
		$data['group'] = $group;
		$data['ticket_sla'] = $ticket_sla;

		$data['main_content'] = 'pages/ticket/edit';
		$data['view_name'] = $this->lang->line("ticket_waba_edit");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("ticket.js");

		$this->load->view('template',  $data);
	}


	function History($key_id)
	{
		$data['title'] = "TalkAll | Histórico";
		$data['id'] = ($key_id);

		$info = $this->Ticket_model->GetInf($key_id);
		$event = $this->Ticket_model->GetEvent($key_id);
		$type = $this->TicketType_model->List();
		$status = $this->TicketStatus_model->List();

		$data['info'] = $info[0];
		$data['event'] = $event;
		$data['type'] = $type;
		$data['status'] = $status;

		$data['main_content'] = 'pages/ticket/history';
		$data['view_name'] = $this->lang->line("ticket_waba_history");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array('ticket.js');

		$this->load->view('template',  $data);
	}


	function ListSubtype($key_id)
	{
		$data = $this->Ticket_model->ListSubtype($key_id);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}


	function ListContact($text)
	{
		$data = $this->Ticket_model->ListContact($text);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}


	function Delete($key_id)
	{
		$this->load->model("Ticket_model", '', TRUE);
		$ret = $this->Ticket_model->Delete($key_id);
		$data = ['msg' => 'ok',];

		if ($ret == 0) {
			$data['msg'] = 'not'; // Caso não possa apagar o ticket
		}

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}
}
