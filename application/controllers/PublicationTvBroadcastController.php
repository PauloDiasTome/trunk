<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class PublicationTvBroadcastController extends TA_Controller
{
	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("publication_tv_broadcast");

		$this->load->model('PublicationTvBroadcast_model', '', TRUE);
		$this->lang->load('tv_broadcast_lang', $this->session->userdata('language'));
	}

	function Index()
	{
		$data['main_content'] = 'pages/publication/tv/broadcast/find';
		$data['view_name'] = $this->lang->line("tv_broadcast_waba_header");
		$data['sidenav'] = array('');
		$data['channel'] = $this->PublicationTvBroadcast_model->ListChannels();
		$data['topnav'] = array('search' => true);
		$data['js'] = array("publicationTvBroadcast.js");
		$data['css'] = array("select" => "msFmultiSelectModal.css", "publicationTvBroadcast.css");

		$this->load->view('template',  $data);
	}

	function Get()
	{
		$param = $this->input->post();
		$result = $this->PublicationTvBroadcast_model->Get($param);

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

	function handleCampaignScheduledSameDate()
	{
		$data = $this->input->post();
		$campaign_scheduled_same_date = $this->PublicationTvBroadcast_model->getCampaignScheduledSameDate($data);
		$result = false;

		if (empty($campaign_scheduled_same_date))
			$result = true;

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($result));
	}

	function checkDate()
	{
		$data = $this->input->post();
		$date = new DateTime();

		$timestamp_current = $date->getTimestamp();
		$timestamp_start = strtotime($data['datetime_start']);
		$timestamp_end = strtotime($data['datetime_end']);

		if ($timestamp_start < $timestamp_current || $timestamp_end < $timestamp_start)
			return false;

		return true;
	}

	function Save()
	{
		$data = $this->input->post(null, false);

		$this->form_validation->set_rules('input_title', $this->lang->line("whatsapp_broadcast_title"), 'trim|required|max_length[100]');
		$this->form_validation->set_rules('datetime_start', $this->lang->line("whatsapp_broadcast_date_scheduling"), 'required|callback_checkDate');
		$this->form_validation->set_rules('others[]', $this->lang->line("whatsapp_broadcast_select_channel"), 'required');

		if ($this->form_validation->run() == true) {
			$this->PublicationTvBroadcast_model->Add($data);

			redirect("publication/tv/broadcast", 'refresh');
		} else {
			$this->Add();
		}
	}

	function organizePost($data)
	{
		$type = [];
		$files = [];
		$second = [];

		foreach ($data as $key => $value) {

			if (preg_match('/^file(\d+)$/', $key, $matches)) {
				$name_file = parse_url($value, PHP_URL_PATH);
				$extension = pathinfo($name_file, PATHINFO_EXTENSION);

				switch ($extension) {
					case "jpg":
					case "jpeg":
					case "jfif":
						$type[] = "3";
						break;
					case "mp4":
						$type[] = "5";
						break;
				}

				$files[] = $value;
				unset($data[$key]);
			}

			if (preg_match('/^seconds(\d+)$/', $key, $matches)) {
				$second[] = $value;
				unset($data[$key]);
			}
		}

		$data += array("files" => $this->convertFilesToJson($files, $type, $second));

		return $data;
	}

	function convertFilesToJson($files, $type, $second)
	{
		$slider = [];

		foreach ($files as $index => $url) {
			$_type = isset($type[$index]) ? $type[$index] : "";
			$_seconds = isset($second[$index]) ? $second[$index] : "";

			$slider[] = [
				'url' => $url,
				'type' => $_type,
				'second' => $_seconds,
			];
		}

		$result = ['slider' => $slider];
		return json_encode($result, JSON_PRETTY_PRINT);
	}

	function Add()
	{
		$data['main_content'] = 'pages/publication/tv/broadcast/add';
		$data['sidenav'] = array('');
		$data['channels'] = $this->PublicationTvBroadcast_model->ListChannels();
		$data['view_name'] = $this->lang->line("tv_broadcast_waba_add");
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("publicationTvBroadcast.js");
		$data['css'] = array("msFmultiSelect.css", "publicationTvBroadcast.css");

		$this->load->view('template',  $data);
	}

	function View($token)
	{
		$data['data'] = $this->PublicationTvBroadcast_model->View($token)[0];
		$data['log'] = $this->PublicationTvBroadcast_model->getScheduleLog($token);

		$data['main_content'] = 'pages/publication/tv/broadcast/view';
		$data['view_name'] = $this->lang->line("tv_broadcast_view");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("publicationTvBroadcast.js");
		$data['css'] = array("msFmultiSelect.css", "publicationTvBroadcast.css");

		$this->load->view('template',  $data);
	}

	function Cancel($token)
	{
		$data = $this->PublicationTvBroadcast_model->Cancel($token);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}

	function Pause($token)
	{
		$data = $this->PublicationTvBroadcast_model->Pause($token);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}

	function Resume($token)
	{
		$data = $this->PublicationTvBroadcast_model->Resume($token);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}
}
