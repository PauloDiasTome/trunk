<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class PublicationWhatsappBroadcastController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("publication_whatsapp_broadcast");

		$this->load->model('PublicationWhatsappBroadcast_model', '', TRUE);
		$this->lang->load('whatsapp_broadcast_lang', $this->session->userdata('language'));

		$this->load->helper('string');
		$this->load->helper('whatsapp_preview_helper.php');
		$this->load->helper('kanban_communication_helper.php');
		$this->load->helper('cancel_campaign_helper.php');
	}

	function Index()
	{
		$this->load->model('Channel_model', '', TRUE);
		$channels = $this->Channel_model->ListChannelAvailableWithStatus(2);

		$data['main_content'] = 'pages/publication/whatsapp/broadcast/find';
		$data['view_name'] = $this->lang->line("whatsapp_broadcast_waba_header");
		$data['sidenav'] = array('');
		$data['channel'] = $channels;
		$data['topnav'] = array('search' => true);
		$data['js'] = array("publicationWhatsappBroadcast.js");
		$data['css'] = array(
			"broadcast" => "publicationWhatsappBroadcast.css",
			"select" => "msFmultiSelectModal.css"
		);

		$this->load->view('template',  $data);
	}

	function Get()
	{
		$param = $this->input->post();
		$result = $this->PublicationWhatsappBroadcast_model->Get($param);

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

	function Check_date($date, $hour)
	{
		$dateInformation = strtotime(implode('-', array_reverse(explode('/', trim($date)))) . " " . $hour . ":00");

		$current = date('Y-m-d');
		$current = strtotime($current);

		if ($dateInformation < $current) {
			$this->form_validation->set_message('check_date', $this->lang->line("whatsapp_broadcast_check_date"));
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
			$this->form_validation->set_message('check_hour', $this->lang->line("whatsapp_broadcast_check_hour"));
			return false;
		} else if ($hourInformation >= $current) {
			return true;
		}
	}

	function Check_date_validity($date_start_validity, $date_and_times)
	{
		$date_and_times = explode(",", $date_and_times);

		$time_start_validity = $date_and_times[0];
		$date_start = $date_and_times[1];
		$time_start = $date_and_times[2];

		$dateInformation = strtotime(implode('-', array_reverse(explode('/', trim($date_start)))) . " " . $time_start . ":00");
		$dateValidity = strtotime(implode('-', array_reverse(explode('/', trim($date_start_validity)))) . " " . $time_start_validity . ":00");

		if ($dateValidity <= $dateInformation) {
			$this->form_validation->set_message('check_date_validity', $this->lang->line("whatsapp_broadcast_check_date_validity"));
			return false;
		} else {
			return true;
		}
	}

	function Check_hour_validity($time_start_validity, $date_and_times)
	{
		return true;

		$date_and_times = explode(",", $date_and_times);

		$date_start_validity = $date_and_times[0];
		$date_start = $date_and_times[1];
		$time_start = $date_and_times[2];

		$hourInformation = strtotime(implode('-', array_reverse(explode('/', trim($date_start)))) . " " . $time_start . ":00");
		$hourValidity = strtotime(implode('-', array_reverse(explode('/', trim($date_start_validity)))) . " " . $time_start_validity . ":00");

		if ($hourValidity <= $hourInformation) {
			$this->form_validation->set_message('check_hour_validity', $this->lang->line("whatsapp_broadcast_check_hour_validity"));
			return false;
		} else {
			return true;
		}
	}

	public function CheckTimeToEdit($token)
	{
		$data = $this->PublicationWhatsappBroadcast_model->CheckTimeToEdit($token);

		$this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}

	function Save($token)
	{
		$data = $this->input->post(null, false);

		if (isset($data["edit"]) && $data["edit"] === "expire") {
			if (isset($data['toggle_validity'])) {
				$this->form_validation->set_rules('date_start_validity', $this->lang->line("whatsapp_broadcast_time_start_validity"), 'required');
				$this->form_validation->set_rules('time_start_validity', $this->lang->line("whatsapp_broadcast_hour_start_validity"), 'required');
			} else {
				$data['date_start_validity'] = "";
				$data['time_start_validity'] = "";
			}
			$this->PublicationWhatsappBroadcast_model->EditCampaignExpire($token, $data);
			redirect("publication/whatsapp/broadcast", 'refresh');
		}

		if ($data["type_broadcast"] != "") {

			$this->form_validation->set_rules('input_title', $this->lang->line("whatsapp_broadcast_title"), 'trim|required|max_length[100]');
			$this->form_validation->set_rules('date_start', $this->lang->line("whatsapp_broadcast_date_scheduling"), 'required|callback_check_date[' . $data['time_start'] . ']');
			$this->form_validation->set_rules('time_start', $this->lang->line("whatsapp_broadcast_hour_scheduling"), 'required');
			$this->form_validation->set_rules('others[]', $this->lang->line("whatsapp_broadcast_select_channel"), 'required');

			if (isset($data['toggle_validity'])) {
				$this->form_validation->set_rules('date_start_validity', $this->lang->line("whatsapp_broadcast_time_start_validity"), 'required|callback_check_date_validity[' . $data['time_start_validity'] . "," . $data['date_start'] . "," . $data['time_start'] . ']');
				$this->form_validation->set_rules('time_start_validity', $this->lang->line("whatsapp_broadcast_hour_start_validity"), 'required|callback_check_hour_validity[' . $data['date_start_validity'] . "," . $data['date_start'] . "," . $data['time_start'] . ']');
			} else {
				$data['date_start_validity'] = "";
				$data['time_start_validity'] = "";
			}

			if ($data["type_broadcast"] == "text") {

				$this->form_validation->set_rules('input-data', $this->lang->line("whatsapp_broadcast_validation_message"), 'trim|required|min_length[1]|max_length[1024]');

				if ($token != '0') {
					$data["text"] = $data['input-data'];
				} else {
					$data += array("text" => array(trim($data['input-data'])));
					$data += array("media_type" => array("1"));
				}
			}

			if ($data["type_broadcast"] == "image") {

				$this->form_validation->set_rules('file0', $this->lang->line("whatsapp_broadcast_validation_img"), 'trim|required');

				if ($token != '0') {
					$data["text"] = $data['description_data'];
				} else {

					$text = array();
					$byte = array();
					$files = array();
					$thumbnail = array();
					$media_type = array();
					$media_name = array();
					$i = 0;
					$type = "";

					foreach ($data as $key => $value) {

						if ($key == "file" . $i) {

							$nameFile = explode("https://files.talkall.com.br:3000", $value)[1];
							$type = explode(".", $nameFile)[1];

							switch ($type) {
								case "jpg":
								case "jpeg":
								case "jfif":
									array_push($media_type, "3");
									break;
								case "ogg":
									array_push($media_type, "2");
									break;
								case "pdf":
									array_push($media_type, "4");
									break;
								case "mp4":
								case "MP4":
									array_push($media_type, "5");
									break;
							}

							array_push($files, $value);
							unset($data[$key]);
						} else if ($key == "text" . $i) {
							array_push($text, $value);
							unset($data[$key]);
							$i++;
						} else if ($key == "byte" . $i) {
							array_push($byte, $value);
							unset($data[$key]);
						} else if ($key == "media_name" . $i) {
							array_push($media_name, $value);
							unset($data[$key]);
						}
					}

					$data += array("files" => $files);
					$data += array("byte" => $byte);
					$data += array("text" => $text);
					$data += array("media_type" => $media_type);
					$data += array("media_name" => $media_name);
				}
			}

			if ($this->form_validation->run() == false) {
				if ($token != '0') {
					$this->Edit($token);
				} else {

					if ($data["type_broadcast"] == "text") {

						$recover = array(
							"type" => "text",
							"input_data" => trim($data["input-data"]),
							"date_start" => $data["date_start"],
							"time_start" => $data["time_start"],
							"media_title" => isset($data["media_title"]),
							"input_title" => trim($data["input_title"]),
							"select_segmented_group" => $data["select_segmented_group"],
							"date_start_validity" => $data["date_start_validity"],
							"time_start_validity" => $data["time_start_validity"],
							"check_email" => isset($data["check_email"]) ? $data["check_email"] : "",
							"check_whatsapp" => isset($data["check_whatsapp"]) ? $data["check_whatsapp"] : "",
							"others" => isset($data["others"]) ? $data["others"] : "",
							"toggle_validity" => isset($data["toggle_validity"]) ? $data["toggle_validity"] : "off",
						);

						$this->Add($recover);
					} else if ($data["type_broadcast"] == "image") {

						if ($type != "pdf") {

							foreach ($files as $row => $value) {
								$path = $value;
								$dataThumb = file_get_contents($path);
								$thumbnail = array_merge($thumbnail, [base64_encode($dataThumb)]);
							}
						} else {

							foreach ($files as $row => $value) {
								array_push($thumbnail, base_url("assets/img/panel/pdf_example.png"));
							}
						}

						$recover = array(
							"type" => "image",
							"byte" => $byte,
							"text" => $text,
							"file" => $files,
							"thumb" => $thumbnail,
							"media_type" => $media_type,
							"media_name" => $media_name,
							"date_start" => $data["date_start"],
							"time_start" => $data["time_start"],
							"input_title" => $data["input_title"],
							"others" => isset($data["others"]) ? $data["others"] : "",
							"input_data" => isset($data["input_data"]),
							"select_segmented_group" => $data["select_segmented_group"],
							"date_start_validity" => $data["date_start_validity"],
							"time_start_validity" => $data["time_start_validity"],
							"check_email" => isset($data["check_email"]) ? $data["check_email"] : "",
							"check_whatsapp" => isset($data["check_whatsapp"]) ? $data["check_whatsapp"] : "",
							"toggle_validity" => isset($data["toggle_validity"]) ? $data["toggle_validity"] : "off",
						);

						$this->Add($recover);
					} else {
						$this->Add();
					}
				}
			} else {
				if ($token != '0') {
					$ret = $this->PublicationWhatsappBroadcast_model->Edit($token, $data);
					foreach ($ret["kanban_communication"] as $key => $value) {
						$value->Cmd = $value->edit_broadcast ? "editPanelBroadcast" : "createKanbanBroadcast";
						notifyKanbanCommunication($value);
					}
				} else {
					$ret = $this->PublicationWhatsappBroadcast_model->Add($data);

					if ($ret['status']) {

						if (isset($data['check_whatsapp']) && $data['check_whatsapp'] == "on") {

							$data['channels_names'] = $ret['channels_names'][0];

							if (count($ret['channels_names']) > 1) {
								$count = count($ret['channels_names']) - 1;
								$channel_count = $count > 1 ? 'canais' : 'canal';
								$data['channels_names'] = $ret['channels_names'][0] . ' e +' . $count . ' ' . $channel_count;
							}

							$data['submitted_by_user'] = trim($this->session->userdata("name"));
						}
					}

					foreach ($ret["kanban_communication"] as $key => $value) {
						$value->Cmd = "createKanbanBroadcast";
						notifyKanbanCommunication($value);
					}
				}

				redirect("publication/whatsapp/broadcast", 'refresh');
			}
		} else {
			$this->Add();
		}
	}

	public function GetCampaignSegment($id)
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://atez3pwlza.execute-api.us-east-1.amazonaws.com/v1/clusters',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'x-api-key: p0KXczEtZO1TLxBxDTF7c3TRYwVwYU2U6R8caYOO',
				'idvarejista:' .  $id
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);

		return json_decode($response);
	}

	function Add($recover = "NULL")
	{
		$this->load->model('Channel_model', '', TRUE);
		$channels = $this->Channel_model->ListChannelAvailableWithStatus(2);

		$queryChannel = $this->PublicationWhatsappBroadcast_model->queryChannel();
		$queryLastBroadcast = $this->PublicationWhatsappBroadcast_model->queryLastBroadcast();
		$queryExpireBroadcast = $this->PublicationWhatsappBroadcast_model->queryExpireBroadcast();

		if (!empty($queryChannel)) {
			$campaignSegment = $this->getCampaignSegment($queryChannel[0]["id"]);
		}

		if (empty($queryChannel[0]["type"])) {

			$this->load->model('Persona_model', '', TRUE);
			$group = $this->Persona_model->List();

			$queryChannel = "null";
		} else {
			$data['campaignSegment'] = $campaignSegment->clusters;
		}

		$data['main_content'] = 'pages/publication/whatsapp/broadcast/add';
		$data['sidenav'] = array('');
		$data['Channels'] = $channels;
		$data['Groups'] = $group;
		$data['queryChannel'] = $queryChannel;
		$data['view_name'] = $this->lang->line("whatsapp_broadcast_waba_add");
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("publicationWhatsappBroadcast.js");
		$data['css'] = array(
			"broadcast" => "publicationWhatsappBroadcast.css",
			"select" => "msFmultiSelect.css"
		);

		if (count($queryLastBroadcast) > 0) {
			$data['date_start'] = $recover == "NULL" ? $data['date_start'] = $this->VerifyLastDate($queryLastBroadcast[0]["date"]) : $recover["date_start"];
			$data['time_start'] = $recover == "NULL" ? $data['time_start'] = $this->VerifyLastHour($queryLastBroadcast[0]["time"]) : $recover["time_start"];
		} else {
			$data['date_start'] = $recover == "NULL" ? date('d/m/Y') : $recover["date_start"];
			$data['time_start'] = $recover == "NULL" ? date('H:i') : $recover["time_start"];
		}

		if (count($queryExpireBroadcast) > 0) {
			$data['date_start_validity'] = $recover == "NULL" ? $queryExpireBroadcast[0]['date'] : $recover["date_start_validity"];
			$data['time_start_validity'] = $recover == "NULL" ? $queryExpireBroadcast[0]['time'] : $recover["time_start_validity"];
		} else {
			$data['date_start_validity'] = $recover == "NULL" ? "" : $recover["date_start_validity"];
			$data['time_start_validity'] = $recover == "NULL" ? "" : $recover["time_start_validity"];
		}

		$data['input_data'] = $recover == "NULL" ? "" : $recover["input_data"];
		$data['input_title'] = $recover == "NULL" ? "" : $recover["input_title"];
		$data['select_segmented_group'] = $recover == "NULL" ? 0 : $recover["select_segmented_group"];
		$data['check_email'] = $recover == "NULL" ? "" : $recover["check_email"];
		$data['check_whatsapp'] = $recover == "NULL" ? "" : $recover["check_whatsapp"];
		$data['toggle_validity'] = $recover == "NULL" ? "" : $recover["toggle_validity"];
		$data['selected_groups'] = $recover == "NULL" ? "" : $this->PublicationWhatsappBroadcast_model->ListGroups($recover["others"]);

		if ($recover == "NULL") {

			$data['type_broadcast'] = "";
			$data['dropBroadcast'] = "none";
			$data['status_data'] = "none";
			$data['input_files'] = "none";
			$data['status_text'] = "block";
			$data['status_image'] = "block";
		} else {

			switch ($recover['type']) {
				case 'text':

					$data['dropBroadcast'] = "none";
					$data['status_text'] = "none";
					$data['type_broadcast'] = "text";
					$data['input_files'] = "none";
					$data['status_image'] = "none";
					$data['status_data'] = "block";

					$data['others'] = $recover["others"];
					break;
				case 'image':

					$data['status_text'] = "none";
					$data['dropBroadcast'] = "block";
					$data['status_data'] = "none";
					$data['input_files'] = "block";
					$data['status_image'] = "none";

					$data['type_broadcast'] = "image";
					$data['input_data'] = "input_data";

					$data['byte'] = $recover["byte"];
					$data['text'] = $recover["text"];
					$data['file'] = $recover["file"];
					$data['media_name'] = $recover["media_name"];
					$data['thumb'] = $recover["thumb"];
					$data['media_type'] = $recover["media_type"];
					$data['others'] = $recover["others"];
					break;
			}
		}

		$this->load->view('template',  $data);
	}

	function sortByDate($a, $b)
	{
		$ad = $a['log_timestamp_creation'];
		$bd = $b['log_timestamp_creation'];
		return ($ad - $bd);
	}

	function View($token)
	{
		$this->load->model('Channel_model', '', TRUE);

		$data['data'] = $this->PublicationWhatsappBroadcast_model->View($token)[0];
		$data['data']['view'] = 'true';
		$data['channel'] = $this->Channel_model->List();
		$data['log'] = $this->PublicationWhatsappBroadcast_model->getScheduleLog($token);

		$data['main_content'] = 'pages/publication/whatsapp/broadcast/view';
		$data['view_name'] = $this->lang->line("whatsapp_broadcast_view");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("publicationWhatsappBroadcast.js");
		$data['css'] = array("publicationWhatsappBroadcast.css", "msFmultiSelect.css");

		$this->load->view('template',  $data);
	}

	function Edit($token)
	{
		$data['data'] = $this->PublicationWhatsappBroadcast_model->GetBroadcastByToken($token);
		$info = $this->PublicationWhatsappBroadcast_model->CheckTimeToEdit($token);
		$data['data']['edit'] = isset($info["success"]) ? 'true' : 'expire';

		$data['type_broadcast'] = $data['data']['media_type'] == 1 ? "text" : "image";
		$data['main_content'] = 'pages/publication/whatsapp/broadcast/edit';
		$data['view_name'] = $this->lang->line("whatsapp_broadcast_waba_edit");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("publicationWhatsappBroadcast.js");
		$data['css'] = array(
			"broadcast" => "publicationWhatsappBroadcast.css",
			"select" => "msFmultiSelect.css"
		);

		$this->load->view('template',  $data);
	}

	function ViewEdit($key_id, $recover = "NULL")
	{
		$info = $this->PublicationWhatsappBroadcast_model->ViewEdit($key_id);

		if (!empty($info)) {

			$this->load->model('Channel_model', '', TRUE);
			$channels = $this->Channel_model->ListChannelAvailableWithStatus(2);

			$this->load->model('Persona_model', '', TRUE);
			$group = $this->Persona_model->List();

			$queryChannel = [];

			$data['data'] = $info[0];

			$SelectedChannels = [];

			foreach ($info as $row) {
				array_push($SelectedChannels, $row['id_channel']);
			}
			$SelectedChannels = array_unique($SelectedChannels);

			$data['data']['view'] = 'true';

			$files = [];
			$thumbs = [];
			$bytes = [];
			$texts = [];
			$media_type = [];
			$media_name = [];
			$media_url = [];

			foreach ($info as $row => $val) {
				if ($val['media_type'] == 3) {

					if (!empty($val['media_url'])) {
						$path = $val['media_url'];
						$dataThumbs = file_get_contents($path);
						array_push($thumbs, base64_encode($dataThumbs));
					}
				} else if ($val['media_type'] == 4) {
					array_push($thumbs,  base_url("assets/img/panel/pdf_example.png"));
				}
				array_push($files, $val['media_url']);
				array_push($bytes, $val['media_size']);
				array_push($texts, $val['data']);
				array_push($media_name, $val['media_name']);
				array_push($media_type, $val['media_type']);
				array_push($media_url, $val['media_url']);
			}

			$files = array_unique($files);
			$bytes = array_unique($bytes);
			$texts = array_unique($texts);
			$media_url = array_unique($media_url);

			$data['log'] = $info;
			$data['main_content'] = 'pages/publication/whatsapp/broadcast/edit';
			$data['sidenav'] = array('');
			$data['Channels'] = $channels;
			$data['SelectedChannels'] = $SelectedChannels;
			$data['Groups'] = $group;
			$data['queryChannel'] = $queryChannel;
			$data['view_name'] = $this->lang->line("whatsapp_broadcast_view");
			$data['topnav'] = array('search' => false, 'header' => true);
			$data['js'] = array("publicationWhatsappBroadcast.js");
			$data['css'] = array(
				"broadcast" => "publicationWhatsappBroadcast.css",
				"select" => "msFmultiSelect.css"
			);

			$data['input_data'] = $recover == "NULL" ? "" : $recover["input_data"];
			$data['date_start'] = $recover == "NULL" ? explode(" ", $data['data']['schedule'])[0] : $recover["date_start"];
			$data['time_start'] = $recover == "NULL" ? explode(" ", $data['data']['schedule'])[1] : $recover["time_start"];
			$data['input_title'] = $recover == "NULL" ? $data['data']['title'] : $recover["input_title"];
			$data['date_start_validity'] = $recover == "NULL" ? ($data['data']['expire'] != 0 ? explode(" ", $data['data']['expire'])[0] : "") : $recover["date_start_validity"];
			$data['time_start_validity'] = $recover == "NULL" ? ($data['data']['expire'] != 0 ? explode(" ", $data['data']['expire'])[1] : "") : $recover["time_start_validity"];
			$data['select_segmented_group'] = $recover == "NULL" ? ($data['data']['groups'] == 0 ? '' : $data['data']['groups']) : $recover["select_segmented_group"];
			$data['expire'] = $recover == "NULL" ? ($data['data']['expire'] == 0 ? "" : $data['data']['expire']) : $recover["expire"];


			$data['check_email'] = $recover == "NULL" ? "" : $recover["check_email"];
			$data['check_whatsapp'] = $recover == "NULL" ? "" : $recover["check_whatsapp"];
			$data['approval_message'] = $recover == "NULL" ? "" : $recover["approval_message"];
			$data['select_segmented_group_approval'] = $recover == "NULL" ? "" : $recover["select_segmented_group_approval"];

			if ($recover == "NULL") {

				$data['status_text'] = "none";
				$data['status_image'] = "none";

				$data['dropBroadcast'] = $data['data']['media_url'] != NULL ? "block" : "none";
				$data['input_files'] = $data['data']['media_url'] != NULL ? "block" : "none";

				$data['type_broadcast'] = $data['data']['media_url'] != NULL ? "image" : "text";

				$data['status_data'] = $data['data']['media_url'] != NULL ? "none" : "block";

				// $data['byte'] = $data["byte"];
				$data['byte'] = $bytes;
				$data['text'] = $texts;
				$data['file'] = $files;
				$data['media_name'] = $media_name;
				$data['thumb'] = $thumbs;
				$data['media_type'] = $media_type;
				$data['media_url'] = $media_url;
			} else {

				switch ($recover['type']) {
					case 'text':

						$data['dropBroadcast'] = "none";
						$data['status_text'] = "none";
						$data['type_broadcast'] = "text";
						$data['input_files'] = "none";
						$data['status_image'] = "none";
						$data['status_data'] = "block";

						$data['others'] = $recover["others"];
						break;
					case 'image':

						$data['status_text'] = "none";
						$data['dropBroadcast'] = "block";
						$data['status_data'] = "none";
						$data['input_files'] = "block";
						$data['status_image'] = "none";

						$data['type_broadcast'] = "image";
						$data['input_data'] = "input_data";

						$data['byte'] = $recover["byte"];
						$data['text'] = $recover["text"];
						$data['file'] = $recover["file"];
						$data['media_name'] = $recover["media_name"];
						$data['thumb'] = $recover["thumb"];
						$data['others'] = $recover["others"];
						break;
				}
			}
			$this->load->view('template',  $data);
		} else {
			$data['status'] = 0;
			$data['view_name'] = $this->lang->line("whatsapp_broadcast_view");
			$data['main_content'] = 'pages/publication/whatsapp/broadcast/edit';
			$data['js'] = array("publicationWhatsappBroadcast.js");
			$data['css'] = array(
				"broadcast" => "publicationWhatsappBroadcast.css",
				"select" => "msFmultiSelect.css"
			);
			$this->load->view('template',  $data);
		}
	}


	function UpdateApprovalLog($data)
	{
		return $this->PublicationWhatsappBroadcast_model->UpdateApprovalLog($data);
	}


	function ResendApproval()
	{
		$data = $this->input->post();
		$this->PublicationWhatsappBroadcast_model->updateToResendEmail($data);

		$data_contacts = $this->PublicationWhatsappBroadcast_model->getJsonMessage($data);

		foreach ($data_contacts as $data_contact) {

			if ($data_contact['json_message'] != '') {
				$json_message = json_decode($data_contact['json_message']);
				$json_message->key_id = strtoupper(random_string('alnum', 32));

				$data['json'] = json_encode($json_message);
				sendMessage($data);
			}
		}

		$data['status'] = 7;
		$data['direction'] = 1;
		$data['approval_message'] = $data_contacts[0]['message'];

		$values = $this->UpdateApprovalLog($data);
		$values['creation_timestamp'] = $values['creation'];
		$values['creation'] = date('d/m/Y H:i',  $values['creation']);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($values));
	}


	function ListGroups()
	{
		$data = $this->input->post();
		$groups = $this->PublicationWhatsappBroadcast_model->ListGroups($data);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($groups));
	}

	function Cancel($token)
	{
		$data =  cancelCampaing($token);

		if (isset($data["success"])) {
			CreateBroadcastLog($this->PublicationWhatsappBroadcast_model->getIdBroadcastByToken($token), 4);

			$kanban_communication = new stdClass();
			$kanban_communication->token = $token;
			$kanban_communication->key_remote_id = $this->PublicationWhatsappBroadcast_model->getChannelKeyRemoteIdByToken($token);
			$kanban_communication->Cmd = "cancelBroadcast";

			notifyKanbanCommunication($kanban_communication);
		}

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}


	function CancelGroup()
	{
		$post = $this->input->post();
		$data = cancelCampaing($post);


		if (isset($data["success"])) {
			foreach ($post['tokens'] as $token) {
				CreateBroadcastLog($this->PublicationWhatsappBroadcast_model->getIdBroadcastByToken($token), 4);

				$kanban_communication = new stdClass();
				$kanban_communication->token = $token;
				$kanban_communication->key_remote_id = $this->PublicationWhatsappBroadcast_model->getChannelKeyRemoteIdByToken($token);
				$kanban_communication->Cmd = "cancelBroadcast";

				notifyKanbanCommunication($kanban_communication);
			}
		}

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}


	function prepareSendPreview()
	{
		$data = $this->input->post();
		$this->PublicationWhatsappBroadcast_model->prepareMessageBusiness($data);
	}


	function validContactChannel()
	{
		$data = $this->input->post();
		$channel = getChannelPreview($data);
		$data['channel'] = $channel;
		$data['id_channel'] = $data['channel'];
		$contactExist = validContactChannel($data);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($contactExist));
	}


	function pauseBroadcast()
	{
		$data = $this->input->post();
		$this->PublicationWhatsappBroadcast_model->pauseBroadcast($data);

		foreach ($data['tokens'] as $token) {
			CreateBroadcastLog($this->PublicationWhatsappBroadcast_model->getIdBroadcastByToken($token), 2);

			$kanban_communication = new stdClass();
			$kanban_communication->token = $token;
			$kanban_communication->key_remote_id = $this->PublicationWhatsappBroadcast_model->getChannelKeyRemoteIdByToken($token);
			$kanban_communication->Cmd = "pauseBroadcast";

			notifyKanbanCommunication($kanban_communication);
		}

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode(["success" => ["status" => true]]));
	}


	function resumeBroadcast()
	{
		$data = $this->input->post();
		$this->PublicationWhatsappBroadcast_model->resumeBroadcast($data);

		foreach ($data['tokens'] as $token) {
			CreateBroadcastLog($this->PublicationWhatsappBroadcast_model->getIdBroadcastByToken($token), 3);

			$kanban_communication = new stdClass();
			$kanban_communication->token = $token;
			$kanban_communication->key_remote_id = $this->PublicationWhatsappBroadcast_model->getChannelKeyRemoteIdByToken($token);
			$kanban_communication->Cmd = "resumeBroadcast";

			notifyKanbanCommunication($kanban_communication);
		}

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode(["success" => ["status" => true]]));
	}

	function ruleSchedule()
	{
		$info = $this->input->post();
		$data = $this->PublicationWhatsappBroadcast_model->ruleSchedule($info);
		$schedule_conflicts = $this->PublicationWhatsappBroadcast_model->checkCampaignOverlap($info);

		$response = [
			'data' => $data,
			'conflicts' => $schedule_conflicts
		];

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($response));
	}

	function ResendBroadcast($token)
	{
		$data = $this->PublicationWhatsappBroadcast_model->ResendBroadcast($token);

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

	public function countBroadcastsToDelete()
	{
		$data = $this->input->post();
		$count = $this->PublicationWhatsappBroadcast_model->countBroadcasts($data);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($count));
	}
}
