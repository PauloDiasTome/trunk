<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class PublicationWhatsappStatusController extends TA_Controller
{
	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("publication_whatsapp_status");

		$this->load->model('PublicationWhatsappStatus_model', '', TRUE);
		$this->lang->load('whatsapp_status_lang', $this->session->userdata('language'));

		$this->load->helper('string');
		$this->load->helper('cancel_campaign_helper.php');
		$this->load->helper('kanban_communication_helper.php');
	}

	function Index()
	{
		$this->load->model('Channel_model', '', TRUE);
		$channels = $this->Channel_model->ListChannelAvailableWithStatus(2);

		$data['main_content'] = 'pages/publication/whatsapp/status/find';
		$data['view_name'] = $this->lang->line("whatsapp_status_waba_header");
		$data['sidenav'] = array('');
		$data['channel'] = $channels;
		$data['topnav'] = array('search' => true);
		$data['js'] = array("publicationWhatsappStatus.js");
		$data['css'] = array(
			"broadcast" => "publicationWhatsappStatus.css",
			"select" => "msFmultiSelectModal.css"
		);

		$this->load->view('template',  $data);
	}


	function Get()
	{
		$param = $this->input->post();
		$result = $this->PublicationWhatsappStatus_model->Get($param);

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


	function check_date($date, $hour)
	{
		$dateInformation = strtotime(implode('-', array_reverse(explode('/', trim($date)))) . " " . $hour . ":00");

		$current = date('Y-m-d');
		$current = strtotime($current);

		if ($dateInformation < $current) {
			$this->form_validation->set_message('check_date', $this->lang->line("whatsapp_status_validation_date"));
			return false;
		} else if ($dateInformation >= $current) {
			return true;
		}
	}


	// function check_hour($hour, $date)
	// {
	// 	$hourInformation = strtotime(implode('-', array_reverse(explode('/', trim($date)))) . " " . $hour . ":00");

	// 	$current = date('Y-m-d H:i:s');
	// 	$current = strtotime($current)  + 60 * 30;

	// 	if ($hourInformation <= $current) {
	// 		$this->form_validation->set_message('check_hour', $this->lang->line("whatsapp_status_validation_hour"));
	// 		return false;
	// 	} else if ($hourInformation > $current) {
	// 		return true;
	// 	}
	// }


	public function CheckTimeToEdit($token)
	{
		$data = $this->PublicationWhatsappStatus_model->CheckTimeToEdit($token);

		$this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}


	function Save($token)
	{
		$data = $this->input->post(null, false);

		if ($data["type_status"] != "") {

			$this->form_validation->set_rules('input_title', $this->lang->line("whatsapp_status_title"), 'trim|required|max_length[100]');
			$this->form_validation->set_rules('date_start', $this->lang->line("whatsapp_status_date_scheduling"), 'required|callback_check_date[' . $data['time_start'] . ']');
			$this->form_validation->set_rules('time_start', $this->lang->line("whatsapp_status_hour_scheduling"), 'required');
			$this->form_validation->set_rules('others[]', $this->lang->line("whatsapp_status_select_channel"), 'required');

			if ($data["type_status"] == "text") {

				$this->form_validation->set_rules('input_data', $this->lang->line("whatsapp_status_message"), 'trim|required|min_length[1]|max_length[700]');

				if ($token != '0') {
					$data["text"] = $data['input_data'];
				} else {
					$data += array("text" => array(trim($data['input_data'])));
					$data += array("media_type" => array("1"));
				}
			}

			if ($data["type_status"] == "image") {
				$this->form_validation->set_rules('file0', $this->lang->line("whatsapp_status_subtitle_drop"), 'trim|required');

				if ($token != '0') {
					$data["text"] = $data['description_data'];
				} else {

					$text = array();
					$files = array();
					$media_size = array();
					$media_type = array();

					$i = 0;

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
								case "mp4":
									array_push($media_type, "5");
									break;
							}


							array_push($files, $value);
							unset($data[$key]);
						} else if ($key == "text" . $i) {

							array_push($text, $value);
							unset($data[$key]);
							$i++;
						} else if ($key == "media_size" . $i) {
							array_push($media_size, $value);
						}
					}

					$thumbnail = array();
					foreach ($files as $row) {
						$path = $row;
						$dataThumb = file_get_contents($path);
						$thumbnail = array_merge($thumbnail, [base64_encode($dataThumb)]);
					}

					$data += array("files" => $files);
					$data += array("text" => $text);
					$data += array("media_type" => $media_type);
					$data += array("media_size" => $media_size);
					$data += array("thumb" => $thumbnail);
				}
			}

			if ($this->form_validation->run() == false) {

				if ($token != '0') {
					$this->Edit($token);
				} else {

					if ($data["type_status"] == "text") {

						$recover  = array(
							"type" => "text",
							"date_start" => $data["date_start"],
							"time_start" => $data["time_start"],
							"input_data" => trim($data["input_data"]),
							"input_title" => trim($data["input_title"]),
							"others" => isset($data["others"]) ? $data['others'] : "",
							"check_email" => isset($data["check_email"]) ? $data['check_email'] : "",
							"check_whatsapp" => isset($data["check_whatsapp"]) ? $data['check_whatsapp'] : ""
						);

						$this->Add($recover);
					} else if ($data["type_status"] == "image") {

						$thumbnail = array();
						foreach ($files as $row) {
							$path = $row;
							$dataThumb = file_get_contents($path);
							$thumbnail = array_merge($thumbnail, [base64_encode($dataThumb)]);
						}

						$recover  = array(
							"type" => "image",
							"text" => $text,
							"file" => $files,
							"thumb" => $thumbnail,
							"media_size" => $media_size,
							"media_type" => $media_type,
							"date_start" => $data["date_start"],
							"time_start" => $data["time_start"],
							"input_title" => $data["input_title"],
							"input_data" => isset($data["input_data"]),
							"others" => isset($data["others"]) ? $data['others'] : "",
							"check_email" => isset($data["check_email"]) ? $data['check_email'] : "",
							"check_whatsapp" => isset($data["check_whatsapp"]) ? $data['check_whatsapp'] : ""
						);
						$this->Add($recover);
					} else {
						$this->Add();
					}
				}
			} else {
				if ($token != '0') {
					$ret = $this->PublicationWhatsappStatus_model->Edit($token, $data);
					foreach ($ret["kanban_communication"] as $key => $value) {
						$value->Cmd = $value->edit_broadcast ? "editPanelBroadcast" : "createKanbanBroadcast";
						notifyKanbanCommunication($value);
					}
				} else {
					$ret = $this->PublicationWhatsappStatus_model->Add($data);

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
				redirect("publication/whatsapp/status", 'refresh');
			}
		} else {
			$this->Add();
		}
	}


	function Add($recover = "NULL")
	{
		$this->load->model('Channel_model', '', TRUE);

		$this->load->model('Persona_model', '', TRUE);
		$group = $this->Persona_model->List();

		$queryLastBroadcast = $this->PublicationWhatsappStatus_model->queryLastBroadcast();

		$data['main_content'] = 'pages/publication/whatsapp/status/add';
		$data['view_name'] = $this->lang->line("whatsapp_status_waba_add");
		$data['sidenav'] = array('');
		$data['Channels'] = $this->Channel_model->ListChannelAvailableWithStatus(2);
		$data['Groups'] = $group;
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("publicationWhatsappStatus.js");
		$data['css'] = array(
			"broadcast" => "publicationWhatsappStatus.css",
			"select" => "msFmultiSelect.css"
		);

		if (count($queryLastBroadcast) > 0) {
			$data['date_start'] = $recover == "NULL" ? $data['date_start'] = $this->VerifyLastDate($queryLastBroadcast[0]["date"]) : $recover["date_start"];
			$data['time_start'] = $recover == "NULL" ? $data['time_start'] = $this->VerifyLastHour($queryLastBroadcast[0]["time"]) : $recover["time_start"];
		} else {
			$data['date_start'] = $recover == "NULL" ? date('d/m/Y') : $recover["date_start"];
			$data['time_start'] = $recover == "NULL" ? date('H:i') : $recover["time_start"];
		}

		$data['input_data'] = $recover == "NULL" ? "" : $recover["input_data"];
		$data['input_title'] = $recover == "NULL" ? "" : $recover["input_title"];
		$data['check_email'] = $recover == "NULL" ? "" : $recover["check_email"];
		$data['check_whatsapp'] = $recover == "NULL" ? "" : $recover["check_whatsapp"];

		if ($recover == "NULL") {

			$data['type_status'] = "";
			$data['dropStatus'] = "none";
			$data['media_size'] = "none";
			$data['status_data'] = "none";
			$data['input_files'] = "none";
			$data['status_text'] = "block";
			$data['status_image'] = "block";
		} else {

			switch ($recover['type']) {
				case 'text':

					$data['dropStatus'] = "none";
					$data['status_text'] = "none";
					$data['media_size'] = "none";
					$data['type_status'] = "text";
					$data['input_files'] = "none";
					$data['status_image'] = "none";
					$data['status_data'] = "block";

					$data['others'] = $recover["others"];
					break;
				case 'image':

					$data['status_text'] = "none";
					$data['dropStatus'] = "block";
					$data['status_data'] = "none";
					$data['input_files'] = "block";
					$data['status_image'] = "none";

					$data['type_status'] = "image";
					$data['input_data'] = "input_data";

					$data['text'] = $recover["text"];
					$data['file'] = $recover["file"];
					$data['thumb'] = $recover["thumb"];
					$data['media_type'] = $recover["media_type"];
					$data['others'] = $recover["others"];
					$data['media_size'] = $recover["media_size"];
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

		$data['data'] = $this->PublicationWhatsappStatus_model->View($token)[0];
		$data['data']['view'] = 'true';
		$data['channel'] = $this->Channel_model->List();
		$data['log'] = $this->PublicationWhatsappStatus_model->getScheduleLog($token);

		$data['main_content'] = 'pages/publication/whatsapp/status/view';
		$data['view_name'] = $this->lang->line("whatsapp_status_waba_view");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("publicationWhatsappStatus.js");
		$data['css'] = array("publicationWhatsappStatus.css", "msFmultiSelect.css");

		$this->load->view('template',  $data);
	}


	function Edit($token)
	{
		$data['data'] = $this->PublicationWhatsappStatus_model->GetBroadcastByToken($token);
		$data['data']['edit'] = 'true';
		$data['type_status'] = $data['data']['media_type'] == 1 ? "text" : "image";
		$data['main_content'] = 'pages/publication/whatsapp/status/edit';
		$data['view_name'] = $this->lang->line("whatsapp_status_waba_edit");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("publicationWhatsappStatus.js");
		$data['css'] = array(
			"broadcast" => "publicationWhatsappStatus.css",
			"select" => "msFmultiSelect.css"
		);

		$this->load->view('template',  $data);
	}


	function Cancel($token)
	{
		$data = CancelCampaing($token);

		if (isset($data["success"])) {
			CreateBroadcastLog($this->PublicationWhatsappStatus_model->getIdBroadcastByToken($token), 4);

			$kanban_communication = new stdClass();
			$kanban_communication->token = $token;
			$kanban_communication->key_remote_id = $this->PublicationWhatsappStatus_model->getChannelKeyRemoteIdByToken($token);
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
		$data = CancelCampaing($post);

		if (isset($data["success"])) {
			foreach ($post['tokens'] as $token) {
				CreateBroadcastLog($this->PublicationWhatsappStatus_model->getIdBroadcastByToken($token), 4);

				$kanban_communication = new stdClass();
				$kanban_communication->token = $token;
				$kanban_communication->key_remote_id = $this->PublicationWhatsappStatus_model->getChannelKeyRemoteIdByToken($token);
				$kanban_communication->Cmd = "cancelBroadcast";

				notifyKanbanCommunication($kanban_communication);
			}
		}

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}

	function ResendBroadcast($token)
	{
		$data = $this->PublicationWhatsappStatus_model->ResendBroadcast($token);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}

	function ruleSchedule()
	{
		$info = $this->input->post();
		$data = $this->PublicationWhatsappStatus_model->ruleSchedule($info);
		$schedule_conflicts = $this->PublicationWhatsappStatus_model->checkCampaignOverlap($info);

		$response = [
			'data' => $data,
			'conflicts' => $schedule_conflicts
		];

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($response));
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
