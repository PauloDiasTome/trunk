<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class ReportSmsController extends TA_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('ReportSms_model', '', TRUE);
        $this->lang->load('report_sms_lang', $this->session->userdata('language'));
    }


    function Index()
    {
        $data['main_content'] = 'pages/report/sms/find';
        $data['view_name'] = $this->lang->line("report_sms_waba_header");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => true);
        $data['js'] = array("report_sms.js");

        $this->load->view('template',  $data);
    }


    function Get()
    {
        $post = $this->input->post();

        $length = 100;
        if (isset($post['length']) and $post['length'] != '') {
            $length = $post['length'];
        }

        $data = $this->ReportSms_model->Get($post['text']  ?? "", $post['dt-start'], $post['dt-end'], $post['start'], $length, $post['order'][0]['column'], $post['order'][0]['dir']);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }
}
