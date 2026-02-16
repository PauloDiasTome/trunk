																	<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class QRCodeController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	
	function read()
	{
		echo "QRCode";
	}
}
