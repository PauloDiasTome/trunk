<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class ReportProtocolController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("report_protocol");
		
		$this->lang->load('report_protocol_lang', $this->session->userdata('language'));
	}


	function Index()
	{
		$data['main_content'] = 'pages/report/protocol/find';
		$data['view_name'] = $this->lang->line("report_protocol_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("report_protocol.js");

		$this->load->view('template',  $data);
	}


	function Get()
	{
		$post = $this->input->post();

		$this->load->model('ReportProtocol_model', '', TRUE);

		$records = $this->ReportProtocol_model->Count($post['text']  ?? "");
		$query = $this->ReportProtocol_model->Get($post['text']  ?? "", $post['start'], $post['length'], $post['order'][0]['column'], $post['order'][0]['dir']);

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

}
