<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class TvController extends TA_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Tv_model', '', TRUE);
    }

    function Index()
    {
        $this->lang->load('tv_lang', "pt_br");
        $this->load->view('pages/tv/index.php');
    }

    function Login()
    {
        $this->lang->load('tv_lang', "pt_br");
        $this->load->view('pages/tv/login.php');
    }

    function Connect()
    {
        $post = $this->input->post();

        $data = $this->Tv_model->Connect($post);

        $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }
}
