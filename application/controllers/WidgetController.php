<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class WidgetController extends TA_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	function Index($widget_token)
	{
		$this->load->model('Widget_model', '', TRUE);
		$info['Users'] = $this->Widget_model->Get($widget_token);
		$info['Widget'] = $this->Widget_model->QueryWidget($widget_token);

		$this->load->view('pages/widget/widget.php', $info);
	}
}
