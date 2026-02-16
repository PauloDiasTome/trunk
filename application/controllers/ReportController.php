<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class ReportController extends TA_Controller
{
	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("report");
	}

	
	function Get()
	{
		$post = $this->input->post();

		$this->load->model('Broadcast_model', '', TRUE);

		$records = $this->Broadcast_model->Count($post['text']  ?? "");
		$query = $this->Broadcast_model->Get($post['text']  ?? "", $post['start'], $post['length']);

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
