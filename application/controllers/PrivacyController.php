<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class PrivacyController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	function Index()
	{
		echo "Em construção";
		header('Location:https://www.talkall.com.br/politicas/politicas-de-privacidade');
	}
}
