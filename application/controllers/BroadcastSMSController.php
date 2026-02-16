<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class BroadcastSMSController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("broadcast_sms");

		$this->load->model('BroadcastSMS_model', '', TRUE);
		$this->lang->load('sms_broadcast_lang', $this->session->userdata('language'));
	}

	function Index()
	{
		$data['main_content'] = 'pages/publication/sms/find';
		$data['view_name'] = $this->lang->line("sms_broadcast_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("publicationSMSbroadcast.js");

		$this->load->view('template',  $data);
	}

	function Get()
	{
		$param = $this->input->post();
		$result = $this->BroadcastSMS_model->Get($param);

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

	function Add()
	{
		$queryLastBroadcast = $this->BroadcastSMS_model->queryLastBroadcast();

		if (count($queryLastBroadcast) > 0) {
			$data['date_start'] =  $this->VerifyLastDate($queryLastBroadcast[0]["date"]);
			$data['time_start'] =  $this->VerifyLastHour($queryLastBroadcast[0]["time"]); 
		} else {
			$data['date_start'] = date('d/m/Y'); 
			$data['time_start'] =  date('H:i');
		}

		$data['main_content'] = 'pages/publication/sms/add';
		$data['sidenav'] = array('');
		$data['groups'] = $this->BroadcastSMS_model->ListPersonaSMS();
		$data['channel'] = $this->BroadcastSMS_model->ListChannelSMS();
		$data['view_name'] = $this->lang->line("sms_broadcast_waba_add");
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("publicationSMSbroadcast.js");

		$this->load->view('template',  $data);
	}

	function Save($data)
	{
		$data = $this->input->post(null, false);

		$this->form_validation->set_rules('input_title', $this->lang->line("sms_broadcast_title"), 'trim|required|min_length[1]|max_length[100]');
		$this->form_validation->set_rules('date_start', $this->lang->line("sms_broadcast_date_scheduling"), 'required|callback_check_date[' . $data['time_start'] . ']');
		$this->form_validation->set_rules('input_data', $this->lang->line("sms_broadcast_validation_message"), 'trim|required|min_length[1]|max_length[140]');

		if ($this->form_validation->run() == true) {

			$this->BroadcastSMS_model->Add($data);
			redirect("broadcast/sms", 'refresh');
		} else {
			$this->Add();
		}
	}

	function View($key_id, $data = NULL)
	{
		$data['data'] = $this->BroadcastSMS_model->View($key_id);
		$data['log'] = $this->BroadcastSMS_model->getScheduleLog($key_id);

		$data['main_content'] = 'pages/publication/sms/view';
		$data['view_name'] = $this->lang->line("sms_broadcast_view");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("publicationSMSbroadcast.js");
		$data['css'] = array("publicationSMSBroadcast.css");

		$this->load->view('template',  $data);
	}

	function Check_date($date, $hour)
	{
		$dateInformation = strtotime(implode('-', array_reverse(explode('/', trim($date)))) . " " . $hour . ":00");

		$current = date('Y-m-d');
		$current = strtotime($current);

		if ($dateInformation < $current) {
			$this->form_validation->set_message('check_date', $this->lang->line("sms_broadcast_check_date"));
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
			$this->form_validation->set_message('check_hour', $this->lang->line("sms_broadcast_check_hour"));
			return false;
		} else if ($hourInformation >= $current) {
			return true;
		}
	}
	
	function sortByDate($a, $b)
	{
		$ad = $a['log_timestamp_creation'];
		$bd = $b['log_timestamp_creation'];
		return ($ad - $bd);
	}

	function Cancel($key_id)
	{
		$data =  $this->BroadcastSMS_model->Cancel($key_id);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}

	function Pause($token)
	{
		$data =  $this->BroadcastSMS_model->Pause($token);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}

	function Resume($token)
	{
		$data =  $this->BroadcastSMS_model->Resume($token);

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
