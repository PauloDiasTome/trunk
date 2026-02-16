<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class PublicationFacebookPostController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("publication_facebook");

		$this->load->model('PublicationFacebookPost_model', '', TRUE);
		$this->lang->load('facebook_post_lang', $this->session->userdata('language'));

		$this->load->helper('string');
	}


	function Index()
	{
		$channel = $this->PublicationFacebookPost_model->listChannel();

		$data['main_content'] = 'pages/publication/facebook/post/find';
		$data['view_name'] = $this->lang->line("facebook_post_waba_header");
		$data['sidenav'] = array("");
		$data['channel'] = $channel;
		$data['topnav'] = array('search' => true);
		$data['js'] = array("publicationFacebookPost.js");
		$data['css'] = array("publicationFacebookPost.css");

		$this->load->view('template',  $data);
	}


	function Get()
	{
		$param = $this->input->post();
		$result = $this->PublicationFacebookPost_model->Get($param);

		$data = array(
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


	function Check_date($date, $hour)
	{
		$dateInformation = strtotime(implode('-', array_reverse(explode('/', trim($date)))) . " " . $hour . ":00");

		$current = date('Y-m-d');
		$current = strtotime($current);

		if ($dateInformation < $current) {
			$this->form_validation->set_message('check_date', $this->lang->line("facebook_post_check_date"));
			return false;
		} else if ($dateInformation >= $current) {
			return true;
		}
	}


	function Check_hour($hour, $date)
	{
		$hourInformation = strtotime(implode('-', array_reverse(explode('/', trim($date)))) . " " . $hour . ":00");

		$current = date('Y-m-d H:i:s');
		$current = strtotime($current)  + 60 * 30;

		if ($hourInformation <= $current) {
			$this->form_validation->set_message('check_hour', $this->lang->line("facebook_post_check_hour"));
			return false;
		} else if ($hourInformation >= $current) {
			return true;
		}
	}


	function Save()
	{
		$data = $this->input->post();

		$this->form_validation->set_rules('input_title', $this->lang->line("facebook_post_title"), 'trim|required|max_length[100]');
		$this->form_validation->set_rules('date_start', $this->lang->line("facebook_post_date_scheduling"), 'required|callback_check_date[' . $data['time_start'] . ']');
		$this->form_validation->set_rules('others[]', $this->lang->line("facebook_post_select_channel"), 'required');

		if (!isset($data["file0"]) && empty($data["input_data"])) {
			$this->form_validation->set_rules('alert_drop', $this->lang->line("facebook_post_title"), 'trim|required');
			$this->form_validation->set_rules('input_data', $this->lang->line("facebook_post_title"), 'trim|required|max_length[1024]');
		} else if (!isset($data["file0"]) && !(empty($data["input_data"]))) {
			$this->form_validation->set_rules('input_data', $this->lang->line("facebook_post_title"), 'trim|required|max_length[1024]');
		}

		if ($this->form_validation->run() == true) {

			if (isset($data["file0"])) {

				$i = 0;
				$media_type = 0;
				$files = array();

				foreach ($data as $key => $value) {

					if ($key == "file" . $i) {

						$nameFile = explode("https://files.talkall.com.br:3000", $value)[1];
						$type = explode(".", $nameFile)[1];

						switch ($type) {
							case "jpg":
							case "jpeg":
								$media_type = 3;
								break;
							case "mp4":
								$media_type = 5;
								break;
						}

						array_push($files, $value);
						unset($data[$key]);
						$i++;
					}
				}

				$data += array("files" => $files);
				$data += array("media_type" => $media_type);
			}

			$ret = $this->PublicationFacebookPost_model->Add($data);

			if ($ret['status']) {

				if (isset($data['check_whatsapp']) == "on") {
					$data['channels_names'] = $ret['channels_names'][0];

					if (count($ret['channels_names']) > 1) {
						$count = count($ret['channels_names']) - 1;
						$channel_count = $count > 1 ? 'canais' : 'canal';
						$data['channels_names'] = $ret['channels_names'][0] . ' e +' . $count . ' ' . $channel_count;
					}

					$data['submitted_by_user'] = trim($this->session->userdata("name"));
				}
			}

			redirect("publication/facebook/post", 'refresh');
		} else {
			$this->Add();
		}
	}


	function Add()
	{
		$this->load->model('Persona_model', '', TRUE);
		$group = $this->Persona_model->List();
		$queryLastBroadcast = $this->PublicationFacebookPost_model->queryLastBroadcast();
		$channel = $this->PublicationFacebookPost_model->listChannel();

		$data['main_content'] = 'pages/publication/facebook/post/add';
		$data['view_name'] = $this->lang->line("facebook_post_waba_add");
		$data['sidenav'] = array('');
		$data['channel'] = $channel;
		$data['Groups'] = $group;
		$data['pages'] = $channel;
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("publicationFacebookPost.js");
		$data['css'] = array(
			"broadcast" => "publicationFacebookPost.css",
			"select" => "msFmultiSelect.css",
		);

		if (count($queryLastBroadcast) > 0) {
			$data['date_start'] = $this->VerifyLastDate($queryLastBroadcast[0]["date"]);
			$data['time_start'] = $this->VerifyLastHour($queryLastBroadcast[0]["time"]);
		} else {
			$data['date_start'] = date('d/m/Y');
			$data['time_start'] = date('H:i');
		}

		$this->load->view('template',  $data);
	}


	function View($key_id)
	{
		$view = $this->PublicationFacebookPost_model->View($key_id);
		$timeLine = $this->PublicationFacebookPost_model->getTimeLine($key_id);
		$i = 0;

		foreach ($view as &$row) {

			if (!empty($row['media_url'])) {

				$path = $row['media_url'];
				$type = pathinfo($path, PATHINFO_EXTENSION);
				$elm = file_get_contents($path);
				$view[$i]['thumb_image'] = 'data:image/' . $type . ';base64,' . base64_encode($elm);
			}
			$i++;
		}

		$log = array_reverse($timeLine);

		$data['log'] = $log;
		$data['main_content'] = 'pages/publication/facebook/post/view';
		$data['view_name'] = $this->lang->line("facebook_post_waba_view");
		$data['sidenav'] = array('');
		$data['view'] = $view;
		$data['data']['id_token'] = $key_id;
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("publicationFacebookPost.js");
		$data['css'] = array("publicationFacebookPost.css");

		$this->load->view('template',  $data);
	}


	function Cancel($key_id)
	{
		$data = $this->PublicationFacebookPost_model->Cancel($key_id);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}


	function CancelGroup()
	{
		$post = $this->input->post();
		$data = $this->PublicationFacebookPost_model->CancelGroup($post);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}


	function VerifyLastDate($data)
	{
		$formatLastDate = DateTime::createFromFormat('d/m/Y', $data)->format('y-m-d');

		$lastDate = new DateTime($formatLastDate);
		$currentDate = new DateTime(date('y-m-d'));

		if ($lastDate >= $currentDate) {
			return $data;
		} else {
			return date('d/m/Y');
		}
	}


	function VerifyLastHour($data)
	{
		if ($data . '00' > date('H:i:s')) {
			return $data;
		} else {
			return date('H:i');
		}
	}
}
