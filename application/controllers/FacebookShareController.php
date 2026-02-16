<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class FacebookShareController extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    function Share()
    {
        $this->load->view('pages/share/facebook/view');
    }
}
