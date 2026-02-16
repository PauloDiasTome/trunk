<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class PublicationWhatsappBroadcastCommunityController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("publication_whatsapp_community");

		$this->load->model('PublicationWhatsappBroadcastCommunity_model', 'PublicationWhatsappBroadcastCommunity_model', TRUE);
		$this->lang->load('whatsapp_broadcast_community_lang', $this->session->userdata('language'));

		$this->load->helper('string');
		$this->load->helper('cancel_campaign_helper.php');
		$this->load->helper('kanban_communication_helper.php');
	}

	function Index()
	{
		$this->load->model('Channel_model', '', TRUE);
		$channels = $this->Channel_model->ListChannelAvailableWithStatus(2);
		$community = $this->PublicationWhatsappBroadcastCommunity_model->GetCommunity();

		$data['main_content'] = 'pages/publication/whatsapp/broadcast_community/find';
		$data['view_name'] = $this->lang->line("whatsapp_broadcast_community_waba_header");
		$data['sidenav'] = array('');
		$data['channel'] = $channels;
		$data['community'] = $community;
		$data['topnav'] = array('search' => true);
		$data['js'] = array("publicationWhatsappBroadcastCommunity.js");
		$data['css'] = array("publicationWhatsappBroadcast_community.css", "msFmultiSelect.css");

		$this->load->view('template',  $data);
	}

	function Get()
	{
		$param = $this->input->post();
		$result = $this->PublicationWhatsappBroadcastCommunity_model->Get($param);

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
			$this->form_validation->set_message('check_date', $this->lang->line("whatsapp_broadcast_community_check_date"));
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
			$this->form_validation->set_message('check_hour', $this->lang->line("whatsapp_broadcast_community_check_hour"));
			return false;
		} else if ($hourInformation >= $current) {
			return true;
		}
	}

	function ConvertPollOptionToJson($data)
	{
		$options = array_filter($data, function ($key) {
			return substr($key, 0, 6) === 'option' && $key !== 'option';
		}, ARRAY_FILTER_USE_KEY);

		$values = array_values($options);
		$result = array_filter($values);

		$json = array(
			"question" => $data['question'],
			"option" => $result,
			"multiple_answers" => isset($data['multiple_answers']) ? true : false
		);

		return [json_encode($json, JSON_PRETTY_PRINT)];
	}

	public function CheckTimeToEdit($token)
	{
		$data = $this->PublicationWhatsappBroadcastCommunity_model->CheckTimeToEdit($token);

		$this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}

	function Save($token)
	{
		$data = $this->input->post(null, false);

		$this->form_validation->set_rules('input_title', $this->lang->line("whatsapp_broadcast_community_title"), 'trim|required|min_length[3]|max_length[100]');
		$this->form_validation->set_rules('date_start', $this->lang->line("whatsapp_broadcast_community_date_scheduling"), 'required|callback_check_date[' . $data['time_start'] . ']');
		$this->form_validation->set_rules('channels[]', $this->lang->line("whatsapp_broadcast_community_select_channel"), 'required');
		$this->form_validation->set_rules('select_segmented_community[]', $this->lang->line("whatsapp_broadcast_community_select_communities"), 'required');

		if ($data["type_broadcast"] == "text") {
			$this->form_validation->set_rules('input-data', $this->lang->line("whatsapp_broadcast_community_validation_message"), 'trim|required|min_length[1]|max_length[1024]');
			$data += array("text" => array(trim($data['input-data'])));
			$data += array("media_type" => array("1"));
		}

		if ($data["type_broadcast"] == "media") {
			$this->form_validation->set_rules('file0', $this->lang->line("whatsapp_broadcast_community_validation_img"), 'trim|required');

			$text = array();
			$byte = array();
			$files = array();
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
						case "pdf":
							array_push($media_type, "4");
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

		if ($data["type_broadcast"] == "poll") {
			$this->form_validation->set_rules('question', $this->lang->line("whatsapp_broadcast_community_validation_img"), 'trim|required');

			$data += array("text" => $this->ConvertPollOptionToJson($data));
			$data += array("media_type" => array("33"));
		}

		if ($this->form_validation->run() == true) {
			if ($token != "0") {
				$ret = $this->PublicationWhatsappBroadcastCommunity_model->Edit($token, $data);
				foreach ($ret["kanban_communication"] as $key => $value) {
					$value->Cmd = $value->edit_broadcast ? "editPanelBroadcast" : "createKanbanBroadcast";
					notifyKanbanCommunication($value);
				}
			} else {
				$ret = $this->PublicationWhatsappBroadcastCommunity_model->Add($data);
				foreach ($ret["kanban_communication"] as $key => $value) {
					$value->Cmd = "createKanbanBroadcast";
					notifyKanbanCommunication($value);
				}
			}

			redirect("publication/whatsapp/broadcast/community", 'refresh');
		} else {
			if ($token != '0') {
				$this->Edit($token);
			} else {
				$this->Add();
			}
		}
	}

	function Add()
	{
		$this->load->model('Channel_model', '', TRUE);
		$channels = $this->PublicationWhatsappBroadcastCommunity_model->getAllChannels();

		$queryLastBroadcast = $this->PublicationWhatsappBroadcastCommunity_model->queryLastBroadcast();

		if (count($queryLastBroadcast) > 0) {
			$data['date_start'] =  $this->VerifyLastDate($queryLastBroadcast[0]["date"]);
			$data['time_start'] =  $this->VerifyLastHour($queryLastBroadcast[0]["time"]);
		} else {
			$data['date_start'] = date('d/m/Y');
			$data['time_start'] =  date('H:i');
		}

		$data['main_content'] = 'pages/publication/whatsapp/broadcast_community/add';
		$data['sidenav'] = array('');
		$data['channels'] = $channels;
		$data['view_name'] = $this->lang->line("whatsapp_broadcast_community_waba_add");
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("publicationWhatsappBroadcastCommunity.js");
		$data['css'] = array("publicationWhatsappBroadcast_community.css");

		$this->load->view('template',  $data);
	}

	function View($token)
	{
		$this->load->model('Channel_model', '', TRUE);

		$data['data'] = $this->PublicationWhatsappBroadcastCommunity_model->View($token)[0];
		$data['data']['view'] = 'true';
		$data['channel'] = $this->Channel_model->List();
		$data['log'] = $this->PublicationWhatsappBroadcastCommunity_model->getScheduleLog($token);

		$data['main_content'] = 'pages/publication/whatsapp/broadcast_community/view';
		$data['view_name'] = $this->lang->line("whatsapp_broadcast_community_view");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("publicationWhatsappBroadcastCommunity.js");
		$data['css'] = array("publicationWhatsappBroadcast_community.css", "msFmultiSelect.css");

		$this->load->view('template',  $data);
	}

	function Edit($token)
	{
		$data['data'] = $this->PublicationWhatsappBroadcastCommunity_model->GetBroadcastByToken($token);
		$data['data']['edit'] = 'true';
		$data['data']['type_broadcast'] = $data['data']['media_type'] == 1 ? "text" : ($data['data']['media_type'] == 33 ? "poll" : "media");

		$data['main_content'] = 'pages/publication/whatsapp/broadcast_community/edit';
		$data['view_name'] = $this->lang->line("whatsapp_broadcast_community_waba_edit");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("publicationWhatsappBroadcastCommunity.js");
		$data['css'] = array("publicationWhatsappBroadcast_community.css", "msFmultiSelect.css");

		$this->load->view('template',  $data);
	}

	function ListCommunity($id_channel)
	{
		$ListCommunity = $this->PublicationWhatsappBroadcastCommunity_model->ListCommunity($id_channel);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($ListCommunity));
	}

	function Cancel($token)
	{
		$data = CancelCampaing($token);

		if (isset($data["success"])) {
			CreateBroadcastLog($this->PublicationWhatsappBroadcastCommunity_model->getIdBroadcastByToken($token), 4);

			$kanban_communication = new stdClass();
			$kanban_communication->token = $token;
			$kanban_communication->key_remote_id = $this->PublicationWhatsappBroadcastCommunity_model->getChannelKeyRemoteIdByToken($token);
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
				CreateBroadcastLog($this->PublicationWhatsappBroadcastCommunity_model->getIdBroadcastByToken($token), 4);

				$kanban_communication = new stdClass();
				$kanban_communication->token = $token;
				$kanban_communication->key_remote_id = $this->PublicationWhatsappBroadcastCommunity_model->getChannelKeyRemoteIdByToken($token);
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
		$data = $this->PublicationWhatsappBroadcastCommunity_model->ResendBroadcast($token);

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
