<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class ReportAutomaticTransferController extends TA_Controller
{

    public function __construct()
    {
        parent::__construct();
        parent::checkPermission("report");

        $this->load->model('ReportAutomaticTransfer_model', '', TRUE);
        $this->lang->load('report_waiting_service_lang', $this->session->userdata('language'));
    }


    function Index()
    {
        $UserGroup = $this->ReportAutomaticTransfer_model->UserGroup();

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

        $records = $this->ReportAutomaticTransfer_model->Count($post['text'] ?? "", $post['sector'], $post['dt_start'] ?? "", $post['dt_end'] ?? "");
        $query = $this->ReportAutomaticTransfer_model->Get($post['text'] ?? "", $post['sector'], $post['dt_start'] ?? "", $post['dt_end'] ?? "", $post['start'], $post['length'], $post['order'][0]['column'], $post['order'][0]['dir']);

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
        $data = $this->ReportAutomaticTransfer_model->GetMessages($post['id'], $post['creation']);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }
}
