<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class MobileController extends TA_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('Mobile_model', '', TRUE);
	}

	public function index()
	{
		// Perhaps in the future
	}


	public function add($name, $phone, $key_remote)
	{
		$ret = [];

		if ((isset($name) and strlen($name) > 1) and (isset($phone) and strlen($phone) > 9)) {
			$data = ["name" => $name, "phone" => $phone, "key_remote_id" => $key_remote];
			$ret = $this->Mobile_model->checkUserToAddOnMobileDB($data);
		} else {
			$ret["msg"] = "error";
			if (strlen($name) < 2) {
				$ret["alert"]["name"] = "incorrect";
			}
			if (strlen($phone) < 9) {
				$ret["alert"]["phone"] = "short number";
			}
		}

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($ret));
	}
}
