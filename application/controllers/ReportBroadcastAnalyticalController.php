
<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class ReportBroadcastAnalyticalController extends TA_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('ReportBroadcastAnalytical_model', '', TRUE);
		$this->lang->load('report_broadcast_analytical_lang', $this->session->userdata('language'));
	}

	function StrLike($needle, $haystack)
	{
		$regex = '/' . str_replace('%', '.*?', $needle) . '/';
		return preg_match($regex, $haystack) > 0;
	}

	function Index()
	{
		$channels = $this->ReportBroadcastAnalytical_model->ListChannels();
		
		$data['channels'] = $channels;
		$data['main_content'] = 'pages/report/broadcast/find';
		$data['view_name'] = $this->lang->line("report_broadcast_analytical_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("report_broadcast_analytical.js");

		$this->load->view('template',  $data);
	}

	function Get()
	{
		$post = $this->input->post();

		$query = $this->ReportBroadcastAnalytical_model->Get($post);
		$records = $this->ReportBroadcastAnalytical_model->Count($post);

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
