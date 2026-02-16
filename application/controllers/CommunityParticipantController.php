<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class CommunityParticipantController extends TA_Controller
{
	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("community");

		$this->load->model('CommunityParticipant_model', '', TRUE);
		$this->lang->load('community_participant_lang', $this->session->userdata('language'));
	}

	function Index()
	{
		$data['participant'] =  $this->CommunityParticipant_model->Participant("");
		$data['totalParticipant'] =  $this->CommunityParticipant_model->TotalParticipant();
		$data['channels'] =  $this->CommunityParticipant_model->ListChannel();

		$data['main_content'] = 'pages/community_participant/find';
		$data['view_name'] = $this->lang->line("community_participant_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['css'] = array("community.css");
		$data['js'] = array("community_participant.js");

		$this->load->view('template', $data);
	}

	function Get()
	{
		$param = $this->input->post();
		$result = $this->CommunityParticipant_model->Get($param);

		foreach ($result['query'] as &$row) {
			$path = "https://files.talkall.com.br:3000/p/" . $row['key_remote_id'] . ".jpeg";
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$row['profile'] = 'data:image/' . $type . ';base64,' . base64_encode($data);
		}

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
}
