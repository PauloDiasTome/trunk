<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class ShortLinkLogController extends CI_Controller
{

	
	public function __construct()
	{
		parent::__construct();
	}


	function Index($link)
	{
		$this->load->model('ShortLinkLog_model', '', TRUE);
		$info = $this->ShortLinkLog_model->Get($link);

		$data['link'] = $link;
		$data['title'] = $info['title'];
		$data['description'] = $info['description'];
		$data['image'] = base_url() . "profiles/" . $info['key_remote_id'] . ".jpeg";

		$this->load->view('pages/shortlink/index.php', $data);
	}


	function Register()
	{
		$data = $this->input->post();
		$this->load->model('ShortLinkLog_model', '', TRUE);

		return $this->ShortLinkLog_model->Log($data, $data['link']);
	}

}
