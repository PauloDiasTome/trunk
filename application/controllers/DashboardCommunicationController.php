<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class DashboardCommunicationController extends TA_Controller
{
	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("dashboard");

		$this->load->model('DashboardCommunication_model', '', TRUE);
		$this->lang->load('dashboard_communication_lang', $this->session->userdata('language'));
	}

	function Index()
	{
		$data['main_content'] = 'pages/dashboard/communition';
		$data['view_name'] = $this->lang->line("dashboard_communication_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false);
		$data['js'] = array("dashboard_communication.js", "chart.js");
		$data['css'] = array("dashboard_communication.css");

		$data['channels'] = $this->DashboardCommunication_model->listChannels();
		$data['broadcast'] = $this->DashboardCommunication_model->listBroadcast();
		$data['interaction'] = $this->DashboardCommunication_model->getInteraction("");
		$data['audience'] = $this->DashboardCommunication_model->getAudience("");

		$this->load->view('template',  $data);
	}

	function Get()
	{
		$params = array(
			'id_channel' => $this->input->get("id_channel"),
			'id_broadcast' => $this->input->get("id_broadcast"),
			'period' => json_decode($this->input->get("period"))
		);

		$data['interaction'] = $this->DashboardCommunication_model->getInteraction($params);
		$data['audience'] = $this->DashboardCommunication_model->getAudience($params);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}

	function GetCampaigns()
	{
		$query = $this->DashboardCommunication_model->getCampaigns($this->input->post());

		$data  = array(
			"draw" => $this->input->post('draw'),
			"data" => $query['query'],
			"recordsTotal" => $query['count'],
			"recordsFiltered" => strval($query['count']),
		);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}
}