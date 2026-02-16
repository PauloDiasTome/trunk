<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class CommunityController extends TA_Controller
{
	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("community");

		$this->load->model('Community_model', 'Community_model', TRUE);
		$this->load->model('CommunityParticipant_model', '', TRUE);

		$this->lang->load('community_lang', $this->session->userdata('language'));
	}

	function Index()
	{
		$data['totalParticipant'] =  $this->CommunityParticipant_model->TotalParticipant();

		$data['main_content'] = 'pages/community/find';
		$data['view_name'] = $this->lang->line("community_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['css'] = array("community.css");
		$data['js'] = array("community.js");

		$this->load->view('template', $data);
	}

	function Get()
	{
		$param = $this->input->post();
		$result = $this->Community_model->Get($param);

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

		$this->form_validation->set_rules('input-name', $this->lang->line("community_name"), 'trim|required|min_length[3]|max_length[100]');

		if ($this->form_validation->run() == true) {

			$this->Community_model->Edit($key_id, $data);
			redirect("community", 'refresh');
		} else {
			$this->Edit($key_id);
		}
	}

	function Participant($key_id)
	{
		$data['main_content'] = 'pages/community/find';
		$data['view_name'] = $this->lang->line("community_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['css'] = array("community.css");
		$data['js'] = array("community.js");
		$data['data'] = $this->Community_model->Participant($key_id);

		$this->load->view('template',  $data);
	}

	function Edit($key_id)
	{
		$data['main_content'] = 'pages/community/edit';
		$data['view_name'] = $this->lang->line("community_waba_edit");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("community.js");
		$data['css'] = array("community.css");
		$data['data'] = $this->Community_model->GetInfo($key_id);

		$this->load->view('template',  $data);
	}

	function Delete($key_id)
	{
		$data = $this->Community_model->Delete($key_id);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}
}
