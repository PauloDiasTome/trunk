<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class ClientCompanyController extends TA_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("company");

		$this->load->model('ClientCompany_model', '', TRUE);
		$this->lang->load('client_company_lang', $this->session->userdata('language'));
	}


	function Index()
	{
		$data['main_content'] = 'pages/client_company/find';
		$data['view_name'] = $this->lang->line("client_company_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("client_company.js");

		$this->load->view('template',  $data);
	}


	function Get()
	{
		$post = $this->input->post();

		$records = $this->ClientCompany_model->Count($post['text']  ?? "");
		$query = $this->ClientCompany_model->Get($post['text']  ?? "", $post['start'], $post['length'], $post['order'][0]['column'], $post['order'][0]['dir']);

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

		$this->form_validation->set_rules('input-corporate-name', $this->lang->line("client_company_corporate_name"), 'trim|required|min_length[3]|max_length[60]');
		$this->form_validation->set_rules('input-cnpj', $this->lang->line("client_company_cnpj"), 'trim|required|min_length[18]|max_length[18]');
		$this->form_validation->set_rules('input-fantasy-name', $this->lang->line("client_company_fantasy_name"), 'trim|required|min_length[3]|max_length[60]');

		if ($this->form_validation->run() == true) {

			if ($key_id > 0) {
				$this->ClientCompany_model->Edit($key_id, $data);
			} else {
				$this->ClientCompany_model->Add($data);
			}

			redirect("client/company", 'refresh');
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
		$data['main_content'] = 'pages/client_company/add';
		$data['view_name'] = $this->lang->line("client_company_waba_add");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("client_company.js");

		$this->load->view('template',  $data);
	}


	function Edit($key_id)
	{
		$data['title'] = $this->lang->line("client_company_waba_edit");
		$data['id'] = ($key_id);

		$info = $this->ClientCompany_model->GetInf($key_id);

		$data['main_content'] = 'pages/client_company/edit';
		$data['view_name'] = $this->lang->line("client_company_waba_edit");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['data'] = $info[0];
		$data['js'] = array("client_company.js");

		$this->load->view('template',  $data);
	}


	function Delete($key_id)
	{
		$this->ClientCompany_model->Delete($key_id);
	}
}
