<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class ReportWaitingServiceController extends TA_Controller
{

    public function __construct()
    {
        parent::__construct();
        parent::checkPermission("report_call");
        
        $this->load->model('ReportWaitingService_model', '', TRUE);
        $this->lang->load('report_waiting_service_lang', $this->session->userdata('language'));
    }


    function Index()
    {
        $UserGroup = $this->ReportWaitingService_model->UserGroup();

        $data['main_content'] = 'pages/report/waiting_service/find';
        $data['view_name'] = $this->lang->line("report_waiting_service_waba_header");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => true);
        $data['css'] = array("report.css");
        $data['js'] = array("report_waiting_service.js");
        $data['userGroup'] = $UserGroup;

        $this->load->view('template',  $data);
    }


    function Get()
    {
        $post = $this->input->post();

        $records = $this->ReportWaitingService_model->Count($post['text'] ?? "", $post['sector'], $post['dt_start'] ?? "", $post['dt_end'] ?? "");
        $query = $this->ReportWaitingService_model->Get($post['text'] ?? "", $post['sector'], $post['dt_start'] ?? "", $post['dt_end'] ?? "", $post['start'], $post['length'], $post['order'][0]['column'], $post['order'][0]['dir']);

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


    function GetMessages()
    {
        $post = $this->input->post();
        $data = $this->ReportWaitingService_model->GetMessages($post['id'], $post['creation']);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }


    function ConversationHistory()
    {
        $data = $this->input->post();

        $this->load->model('ReportCall_model', '', TRUE);
        $info = $this->ReportCall_model->ConversationHistory($data);
    }
}
