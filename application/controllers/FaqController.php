<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class FaqController extends TA_Controller
{
	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("faq");

		$this->load->model('Faq_model', '', TRUE);
		$this->lang->load('setting_faq_lang', $this->session->userdata('language'));
	}


	function Index()
	{
		$data['main_content'] = 'pages/faq/find';
		$data['view_name'] = $this->lang->line("faq_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("faq.js");

		$this->load->view('template',  $data);
	}


	function Get()
	{
		$post = $this->input->post();

		$records = $this->Faq_model->Count($post['text']  ?? "");
		$query = $this->Faq_model->Get($post['text']  ?? "", $post['start'], $post['length'], $post['order'][0]['column'], $post['order'][0]['dir']);

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


	function valid_title($text)
	{
		$iCount = 0;
		$tags = explode(" ", $text);
		for ($i = 0; $i < count($tags); $i++) {
			if (strlen($tags[$i]) >= 4) {
				$iCount++;
			}
		}
		return $iCount >= 2 ? true : false;
	}


	function Save($key_id)
	{
		$data = $this->input->post();

		$this->form_validation->set_rules('input-title', $this->lang->line("setting_faq_title"), 'callback_valid_title', array('valid_title' => $this->lang->line("setting_faq_title_error_message")));
		$this->form_validation->set_rules('input-content', $this->lang->line("setting_faq_faq_description"), 'trim|max_length[4096]');

		if ($this->form_validation->run() == true) {

			if ($key_id > 0) {
				$this->Faq_model->Edit($key_id, $data);
			} else {
				$this->Faq_model->Add($data);
			}

			redirect("faq", 'refresh');
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
		$data['main_content'] = 'pages/faq/add';
		$data['view_name'] = $this->lang->line("faq_waba_add");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("faq.js");

		$this->load->view('template',  $data);
	}


	function Edit($key_id)
	{
		$data['title'] = "TalkAll | Editar";
		$data['id'] = ($key_id);

		$info = $this->Faq_model->GetInf($key_id);

		$data['data'] = $info[0];

		$data['main_content'] = 'pages/faq/edit';
		$data['view_name'] = $this->lang->line("faq_waba_edit");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("faq.js");

		$this->load->view('template',  $data);
	}


	function Read($key_id)
	{
		$data['title'] = "TalkAll | Editar";
		$data['id'] = ($key_id);

		$info = $this->Faq_model->GetInf($key_id);

		$data['data'] = $info[0];

		$data['main_content'] = 'pages/faq/read';
		$data['view_name'] = $this->lang->line("faq_waba_read");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("faq.js");

		$this->load->view('template',  $data);
	}


	function Delete($key_id)
	{
		$this->load->model('Faq_model', '', TRUE);
		$this->Faq_model->Delete($key_id);
	}
}
