<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class OrderStatusController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('OrderStatus_model', '', TRUE);
		$this->lang->load('order_status_lang', $this->session->userdata('language'));
	}


	function Index()
	{
		$data['main_content'] = 'pages/order/status/find';
		$data['view_name'] = $this->lang->line("order_status_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("order_status.js");

		$this->load->view('template',  $data);
	}


	function Get()
	{
		$post = $this->input->post();

		$records = $this->OrderStatus_model->Count($post['text']  ?? "");
		$query = $this->OrderStatus_model->Get($post['text']  ?? "", $post['start'], $post['length'], $post['order'][0]['column'], $post['order'][0]['dir']);

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

		$this->form_validation->set_rules('input-name', $this->lang->line("order_status_name"), 'trim|required|min_length[3]');

		if ($this->form_validation->run() == true) {

			if ($key_id > 0) {
				$this->OrderStatus_model->Edit($key_id, $data);
			} else {
				$this->OrderStatus_model->Add($data);
			}

			redirect("order/status", 'refresh');
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
		$data = $this->input->post();

		$data['main_content'] = 'pages/order/status/add';
		$data['sidenav'] = array('');
		$data['view_name'] = $this->lang->line("order_status_waba_add");
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("order_status.js");
		$data['data'] = $data;

		$this->load->view('template',  $data);
	}


	function Edit($key_id)
	{
		$data['title'] = "TalkAll | Editar";
		$data['id'] = ($key_id);

		$info = $this->OrderStatus_model->GetInf($key_id);

		$data['data'] = $info[0];

		$data['main_content'] = 'pages/order/status/edit';
		$data['sidenav'] = array('');
		$data['view_name'] = $this->lang->line("order_status_waba_edit");
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("order_status.js");

		$this->load->view('template',  $data);
	}


	function Delete($key_id)
	{
		$this->load->model('OrderStatus_model', '', TRUE);
		$this->OrderStatus_model->Delete($key_id);
	}
}
