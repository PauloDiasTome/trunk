<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Token extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->output
            ->set_header('Content-Type: application/json');
        $this->load->library("Server", "server");
    }

    function index()
    {
        $this->server->Token();
    }
}
