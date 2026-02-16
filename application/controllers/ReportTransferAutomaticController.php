<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class ReportTransferAutomaticController extends TA_Controller
{

    public function __construct()
    {
        parent::__construct();
        parent::checkPermission("report");
        
        $this->load->model('ReportTransferAutomatic_model', '', TRUE);
        $this->lang->load('report_transfer_automatic_lang', $this->session->userdata('language'));
    }


    function Index()
    {
        $userGroup = $this->ReportTransferAutomatic_model->ListUsergroup();
        $users = $this->ReportTransferAutomatic_model->ListUsers();

        $data['main_content'] = 'pages/report/transfer_automatic/find';
        $data['view_name'] = $this->lang->line("report_transfer_automatic_waba_header");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => true);
        $data['css'] = array("report.css");
        $data['js'] = array("report_transfer_automatic.js");

        $data['userGroup'] = $userGroup;
        $data['users'] = $users;

        $this->load->view('template',  $data);
    }


    function Get()
    {
        $post = $this->input->post();

        $records = $this->ReportTransferAutomatic_model->Count($post['text'] ?? "", $post['user'], $post['sector'], $post['dt_start'], $post['dt_end']);
        $query = $this->ReportTransferAutomatic_model->Get($post['text'] ?? "", $post['user'], $post['sector'], $post['dt_start'] ?? "", $post['dt_end'] ?? "", $post['start'], $post['length'], $post['order'][0]['column'], $post['order'][0]['dir']);

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
        $data = $this->ReportTransferAutomatic_model->GetMessages($post['id'], $post['creation']);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }
}
