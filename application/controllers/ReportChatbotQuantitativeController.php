<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class ReportChatbotQuantitativeController extends TA_Controller
{

    public function __construct()
    {
        parent::__construct();
        // parent::checkPermission("report_chatbot");

        $this->load->model('ReportChatbotQuantitative_model', '', TRUE);
        $this->lang->load('report_chatbot_quantitative_lang', $this->session->userdata('language'));
    }

    function Index()
    {

        $data['main_content'] = 'pages/report/chatbot/quantitative/find';
        $data['view_name'] = $this->lang->line("report_chatbot_waba_header");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => true);
        $data['css'] = array("report_chatbot_quantitative.css");
        $data['js'] = array("report_chatbot_quantitative.js");

        $this->load->view('template',  $data);
    }

    function Get()
    {

        $param = $this->input->post();
        $result = $this->ReportChatbotQuantitative_model->Get($param);

        $data  = array(
            "draw" => $param['draw'],
            "recordsTotal" => $result['count'],
            "recordsFiltered" => $result['count'],
            "data" => $result["query"]
        );

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }
}
