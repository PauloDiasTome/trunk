<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class ShortLinkController extends TA_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("shortlink");

		$this->load->model('ShortLink_model', '', TRUE);
		$this->lang->load('shortlink_lang', $this->session->userdata('language'));
	}


	function Index()
	{

		$data['view']  = !filter_var($this->input->get('view', TRUE), FILTER_VALIDATE_BOOLEAN);

		$data['main_content'] = 'pages/shortlink/find';
		$data['view_name'] = $this->lang->line("shortlink_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("shortlink.js");

		$this->load->view('template',  $data);
	}


	function Get()
	{
		$post = $this->input->post();

		$this->load->model('ShortLink_model', '', TRUE);

		$records = $this->ShortLink_model->Count($post['text']  ?? "");
		$query = $this->ShortLink_model->Get($post['text']  ?? "", $post['start'], $post['length'], $post['order'][0]['column'], $post['order'][0]['dir']);

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

		$this->form_validation->set_rules('input-name', $this->lang->line("shortlink_link_name_validation"), 'trim|required|max_length[100]');
		$this->form_validation->set_rules('input-phone', $this->lang->line("shortlink_number"), 'trim|required|max_length[600]');
		$this->form_validation->set_rules('input-title', $this->lang->line("shortlink_title"), 'max_length[100]');
		$this->form_validation->set_rules('input-description', $this->lang->line("shortlink_description"), 'max_length[140]');
		$this->form_validation->set_rules('input-message', $this->lang->line("shortlink_default_message"), 'max_length[140]');


		if ($this->form_validation->run() == true) {

			if ($key_id > 0) {
				$this->ShortLink_model->Edit($key_id, $data);
			} else {
				$this->ShortLink_model->Add($data);
			}
			redirect("shortlink", 'refresh');
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
		$users = $this->User_model->List();
		$data['users'] = $users;

		$this->load->model('ShortLink_model', '', TRUE);

		$data['link'] = $this->ShortLink_model->GetBuildLink();

		$data['main_content'] = 'pages/shortlink/add';
		$data['view_name'] = $this->lang->line("shortlink_waba_add");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("shortlink.js");

		$this->load->view('template',  $data);
	}


	function qrcode($key_id)
	{

		$this->load->model('ShortLink_model', '', TRUE);

		$data['link'] = $this->ShortLink_model->GetLink($key_id);

		$data['main_content'] = 'pages/shortlink/qrcode';
		$data['view_name'] = "Edit";
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("qrcode.js", "qrcode.min.js");

		$this->load->view('template',  $data);
	}


	function Edit($key_id)
	{
		$data['title'] = "TalkAll | Editar";
		$data['id'] = ($key_id);

		$this->load->model('ShortLink_model', '', TRUE);
		$info = $this->ShortLink_model->GetInf($key_id);

		$this->load->model('User_model', '', TRUE);
		$users = $this->User_model->List();

		$data['data'] = $info[0];
		$data['users'] = $users;

		$data['main_content'] = 'pages/shortlink/edit';
		$data['view_name'] = $this->lang->line("shortlink_waba_edit");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("shortlink.js");

		$this->load->view('template',  $data);
	}


	function Delete($key_id)
	{
		$this->load->model('ShortLink_model', '', TRUE);
		$this->ShortLink_model->Delete($key_id);
	}
}
