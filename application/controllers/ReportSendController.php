<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class ReportSendController extends TA_Controller
{

    public function __construct()
    {
        parent::__construct();
        parent::checkPermission("report_send");

        $this->load->model('ReportSend_model', '', TRUE);
        $this->lang->load('report_send_lang', $this->session->userdata('language'));
    }


    function Index()
    {
        $data['main_content'] = 'pages/report/send/find';
        $data['view_name'] = $this->lang->line("report_send_waba_header");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => true);
        $data['css'] = array("report.css");
        $data['js'] = array("report_send.js");
        $data['channel'] = $this->ReportSend_model->ListChannels();

        $this->load->view('template',  $data);
    }


    function Get()
    {
        $post = $this->input->post();

        $records = $this->ReportSend_model->Count($post);
        $query = $this->ReportSend_model->Get($post);

        $data  = array(
            "draw" => $post['draw'],
            "recordsTotal" => $records[0]['count'],
            "recordsFiltered" => $records[0]['count'],
            "data" => $query
        );

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }


    function View($token)
    {

        $info = $this->ReportSend_model->View($token);
        $log = $this->ReportSend_model->getLog($token);

        $data['data'] = $info[0];

        foreach ($info as $row) {
            if (!empty($row['media_url']) && $row['media_type'] == 3) {

                $path = $row['media_url'];
                $dataThumbs = file_get_contents($path);
                $data['data']['thumb_image'] = base64_encode($dataThumbs);
            }
        }

        $data['log'] = array_reverse($log);
        $data['main_content'] = 'pages/report/send/view';
        $data['view_name'] = $this->lang->line("report_send_waba_header");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => false, 'header' => true);
        $data['css'] = array("report.css");
        $data['js'] = array("report_send.js");

        $this->load->view('template',  $data);
    }
}
