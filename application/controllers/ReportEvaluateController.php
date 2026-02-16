<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class ReportEvaluateController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("evaluate_report");

		$this->load->model('ReportEvaluate_model', '', TRUE);
		$this->lang->load('report_evaluate_lang', $this->session->userdata('language'));
	}


	function Index()
	{
		$data['main_content'] = 'pages/report/evaluate/find';
		$data['view_name'] = $this->lang->line("report_evaluate_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("report_evaluate.js");

		$this->load->view('template',  $data);
	}


	function Get()
	{
		$post = $this->input->post();

		$query = $this->ReportEvaluate_model->Get($post['text']  ?? "", $post['dt_start'] ?? "", $post['dt_end'] ?? "", $post['start'], $post['length'], $post['order'][0]['column'], $post['order'][0]['dir']);

		$avarages = array();

		$initialSize = count($query);

		for ($i = 0; $i < $initialSize; $i++) {

			$qtdBad = $query[$i]['evaluate_level_1'];
			$qtdGood = $query[$i]['evaluate_level_2'];
			$qtdExcellent = $query[$i]['evaluate_level_3'];

			$avarages[$i] = (50 * ($qtdGood + 2 * $qtdExcellent)) / ($qtdBad + $qtdGood + $qtdExcellent);

			switch ($post['assessment']) {
				case '1':
					if ($avarages[$i] <= 33.33) {
						$query[$i]['avarage'] = $avarages[$i];
					} else {
						unset($query[$i]);
					}
					break;
				case '2':
					if ($avarages[$i] > 33.33 && $avarages[$i] <= 66.66) {
						$query[$i]['avarage'] = $avarages[$i];
					} else {
						unset($query[$i]);
					}
					break;
				case '3':
					if ($avarages[$i] > 66.66) {
						$query[$i]['avarage'] = $avarages[$i];
					} else {
						unset($query[$i]);
					}
					break;
				default:
					$query[$i]['avarage'] = $avarages[$i];
					break;
			}
		}

		$query = array_values($query);

		$records[0]['count'] = count($query);

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
