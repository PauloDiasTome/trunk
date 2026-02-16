<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class DevelopersController extends TA_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function Index()
	{
		if ($_SERVER['HTTP_HOST'] != $this->config->item('dev_domain')) {
			redirect("https://" . $this->config->item('dev_domain'));
		} else {
			$this->load->view('developers/index');
		}
	}
}
