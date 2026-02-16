<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class WaitListController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("waitlist");

	}


	function Index()
	{
		$data['main_content'] = 'pages/wait/find';
		$data['view_name'] = 'Fila de Espera';
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("wait.js");

		$this->load->view('template',  $data);
	}


	function Get()
	{
		$post = $this->input->post();

		$this->load->model('WaitList_model', '', TRUE);

		$records = $this->WaitList_model->Count($post['text']  ?? "");
		$query = $this->WaitList_model->Get($post['text']  ?? "", $post['start'], $post['length']);

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
