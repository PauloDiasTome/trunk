<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class LabelController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("label");

		$this->load->model('Label_model', '', TRUE);
		$this->lang->load('label_lang', $this->session->userdata('language'));
	}

	function Index()
	{
		$data['main_content'] = 'pages/label/find';
		$data['view_name'] = $this->lang->line("label_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("label.js");
		$this->load->view('template',  $data);
	}

	function Get()
	{
		$param = $this->input->post();
		$result = $this->Label_model->Get($param);

		$data  = array(
			"draw" => $param['draw'],
			"recordsTotal" => $result['count'],
			"recordsFiltered" => $result['count'],
			"data" => $result['query']
		);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}

	function Save($key_id)
	{
		$data = $this->input->post();

		$this->form_validation->set_rules('input-name', $this->lang->line("label_name"), 'trim|required|min_length[3]|max_length[30]');

		if ($this->form_validation->run() == true) {

			if ($key_id > 0) {
				$this->Label_model->Edit($key_id, $data);
			} else {
				$this->Label_model->Add($data);
			}

			redirect("label", 'refresh');
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
		$data['main_content'] = 'pages/label/add';
		$data['view_name'] = $this->lang->line("label_waba_add");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("label.js");
		$data['data'] = $data;

		$this->load->view('template',  $data);
	}

	function Edit($key_id)
	{
		$data['title'] = $this->lang->line("label_waba_edit");
		$data['id'] = ($key_id);

		$info = $this->Label_model->GetById($key_id);

		$data['data'] = $info;

		$data['main_content'] = 'pages/label/edit';
		$data['view_name'] = $this->lang->line("label_waba_edit");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("label.js");

		$this->load->view('template',  $data);
	}

	function Delete()
	{
		$post = $this->input->post();
		$data = $this->Label_model->Delete($post);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}
}
