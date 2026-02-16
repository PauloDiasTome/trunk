<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class ReportInteractionSyntheticController extends TA_Controller
{

    public function __construct()
    {
        parent::__construct();
        parent::checkPermission("report_call");

        $this->load->model('ReportInteractionSynthetic_model', '', TRUE);
        $this->lang->load('report_interaction_synthetic_lang', $this->session->userdata('language'));
        $this->load->helper('responses');
    }


    function Index()
    {
        $data['main_content'] = 'pages/report/interaction_synthetic/find';
        $data['view_name'] = $this->lang->line("report_interaciton_synthetic_waba_header");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => false, 'header' => true);
        $data['css'] = array("report.css");
        $data['js'] = array("report_interaction_synthetic.js");
        $data['is_bot'] = $this->ReportInteractionSynthetic_model->IsBot();
        $data['channels'] = $this->ReportInteractionSynthetic_model->getChannels();

        $this->load->view('template',  $data);
    }


    function ChatBot()
    {
        $param = $this->input->post();
        $result = $this->ReportInteractionSynthetic_model->ChatBot($param);

        $data  = array(
            "draw" => $param["draw"],
            "recordsTotal" => 6,
            "recordsFiltered" => 6,
            "data" => $result
        );

        return json_response($this, 200, $data);
    }


    function WaitingService()
    {
        $post = $this->input->post();

        $query = $this->ReportInteractionSynthetic_model->WaitingService($post);

        $data  = array(
            "draw" => $post['draw'],
            "recordsTotal" => 3,
            "recordsFiltered" => 3,
            "data" => $query
        );

        return json_response($this, 200, $data);
    }


    function Attendance()
    {
        $post = $this->input->post();

        $query = $this->ReportInteractionSynthetic_model->Attendance($post);

        $data  = array(
            "draw" => $post['draw'],
            "recordsTotal" => 7,
            "recordsFiltered" => 7,
            "data" => $query
        );

        return json_response($this, 200, $data);
    }
}
