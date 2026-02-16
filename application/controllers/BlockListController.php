<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class BlockListController extends TA_Controller
{
	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("block_list");

		$this->load->model('BlockList_model', '', TRUE);
		$this->lang->load('blocklist_lang', $this->session->userdata('language'));
	}

	function Index()
	{
		$data['main_content'] = 'pages/blocklist/find';
		$data['view_name'] = $this->lang->line("blocklist_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("blocklist.js");

		$this->load->view('template',  $data);
	}


	function Get()
	{
		$param = $this->input->post();
		$result = $this->BlockList_model->Get($param);

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


	function Delete($key_id)
	{
		$this->load->model('BlockList_model', '', TRUE);
		$this->BlockList_model->Delete($key_id);
	}
}
