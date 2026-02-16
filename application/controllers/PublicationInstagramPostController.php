<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class PublicationInstagramPostController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("publication_instagram");

		$this->load->model('PublicationInstagramPost_model', '', TRUE);
		$this->lang->load('instagram_post_lang', $this->session->userdata('language'));

		$this->load->helper('string');
	}


	function Index()
	{
		$channel = $this->PublicationInstagramPost_model->listChannel();

		$data['main_content'] = 'pages/publication/instagram/post/find';
		$data['view_name'] = $this->lang->line("instagram_post_waba_header");
		$data['sidenav'] = array("");
		$data['channel'] = $channel;
		$data['topnav'] = array('search' => true);
		$data['js'] = array("publicationInstagramPost.js");
		$data['css'] = array("publicationInstagramPost.css");

		$this->load->view('template',  $data);
	}


	function Get()
	{
		$param = $this->input->post();
		$result = $this->PublicationInstagramPost_model->Get($param);

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
			$this->form_validation->set_message('check_date', $this->lang->line("instagram_post_check_date"));
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
			$this->form_validation->set_message('check_hour', $this->lang->line("instagram_post_check_hour"));
			return false;
		} else if ($hourInformation >= $current) {
			return true;
		}
	}


	function Save()
	{
		$data = $this->input->post();

		$this->form_validation->set_rules('input_title', $this->lang->line("instagram_post_title"), 'trim|required|max_length[100]');
		$this->form_validation->set_rules('date_start', $this->lang->line("instagram_post_check_date"), 'required|callback_check_date[' . $data['time_start'] . ']');
		$this->form_validation->set_rules('others[]', $this->lang->line("instagram_post_select_channel"), 'required');
		$this->form_validation->set_rules('file', $this->lang->line("instagram_post_file"), 'required');

		if ($this->form_validation->run()) {

			$nameFile = explode("https://files.talkall.com.br:3000", $data["file"])[1];
			$type = explode(".", $nameFile)[1];

			switch ($type) {
				case "jpg":
				case "jpeg":
					$data["media_type"] = 3;
					break;
				case "mp4":
					$data["media_type"] = 5;
					break;
			}

			$ret = $this->PublicationInstagramPost_model->Add($data);

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

			redirect("publication/instagram/post", 'refresh');
		} else {
			$this->Add();
		}
	}


	function Add()
	{
		$this->load->model('Persona_model', '', TRUE);
		$group = $this->Persona_model->List();
		$queryLastBroadcast = $this->PublicationInstagramPost_model->queryLastBroadcast();
		$channel = $this->PublicationInstagramPost_model->listChannel();

		$data['main_content'] = 'pages/publication/instagram/post/add';
		$data['view_name'] = $this->lang->line("instagram_post_waba_add");
		$data['sidenav'] = array('');
		$data['channel'] = $channel;
		$data['Groups'] = $group;
		$data['instagram'] = $channel;
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("publicationInstagramPost.js");
		$data['css'] = array(
			"broadcast" => "publicationInstagramPost.css",
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
		$view = $this->PublicationInstagramPost_model->View($key_id);
		$timeLine = $this->PublicationInstagramPost_model->getTimeLine($key_id);

		if (!empty($view[0]['media_url'])) {

			$path = $view[0]['media_url'];
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$elm = file_get_contents($path);
			$view[0]['thumb_image'] = 'data:image/' . $type . ';base64,' . base64_encode($elm);
		}

		$log = array_reverse($timeLine);

		$data['log'] = $log;
		$data['main_content'] = 'pages/publication/instagram/post/view';
		$data['view_name'] = $this->lang->line("instagram_post_waba_view");
		$data['sidenav'] = array('');
		$data['view'] = $view;
		$data['data']['id_token'] = $key_id;
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("publicationInstagramPost.js");
		$data['css'] = array(
			"broadcast" => "publicationInstagramPost.css",
			"select" => "msFmultiSelect.css"
		);

		$this->load->view('template',  $data);
	}


	function Edit()
	{
		echo "Passou aqui no edit";
		die;
	}


	function Cancel($key_id)
	{
		$data = $this->PublicationInstagramPost_model->Cancel($key_id);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}


	function CancelGroup()
	{
		$post = $this->input->post();
		$data = $this->PublicationInstagramPost_model->CancelGroup($post);

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
