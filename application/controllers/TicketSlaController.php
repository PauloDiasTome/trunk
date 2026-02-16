<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class TicketSlaController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("ticket");

		$this->load->model('TicketSla_model', '', TRUE);
		$this->lang->load('ticket_sla_lang', $this->session->userdata('language'));
	}


	function Index()
	{
		$data['main_content'] = 'pages/ticket_sla/find';
		$data['view_name'] = $this->lang->line("ticket_sla_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("ticket_sla.js");

		$this->load->view('template',  $data);
	}


	function Get()
	{
		$post = $this->input->post();

		$records = $this->TicketSla_model->Count($post['text']  ?? "");
		$query = $this->TicketSla_model->Get($post['text']  ?? "", $post['start'], $post['length'], $post['order'][0]['column'], $post['order'][0]['dir']);

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

		$this->form_validation->set_rules('input-name', $this->lang->line("ticket_sla_name"), 'trim|required|min_length[3]|max_length[100]');
		$this->form_validation->set_rules('input-tempo', $this->lang->line("ticket_sla_clock"), 'trim|required|min_length[5]|max_length[5]');

		if ($this->form_validation->run() == true) {

			if ($key_id > 0) {
				$this->TicketSla_model->Edit($key_id, $data);
			} else {
				$this->TicketSla_model->Add($data);
			}

			redirect("ticket/sla", 'refresh');
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
		$data['main_content'] = 'pages/ticket_sla/add';
		$data['view_name'] = $this->lang->line("ticket_sla_waba_add");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("ticket_sla.js");

		$this->load->view('template',  $data);
	}


	function Edit($key_id)
	{
		$data['title'] = "TalkAll | Editar";
		$data['id'] = ($key_id);

		$info = $this->TicketSla_model->GetInf($key_id);

		$data['data'] = $info[0];

		$data['main_content'] = 'pages/ticket_sla/edit';
		$data['view_name'] = $this->lang->line("ticket_sla_waba_edit");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("ticket_sla.js");

		$this->load->view('template',  $data);
	}


	function Delete($key_id)
	{
		$this->load->model('TicketSla_model', '', TRUE);
		$this->TicketSla_model->Delete($key_id);
	}
}
