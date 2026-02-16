<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class ReportInteractionController extends TA_Controller
{

    public function __construct()
    {
        parent::__construct();
        parent::checkPermission("report");

        $this->load->model('ReportInteraction_model', '', TRUE);
        $this->lang->load('report_interaction_lang', $this->session->userdata('language'));
    }


    function Index()
    {
        $channel = $this->ReportInteraction_model->List();
        $userGroup = $this->ReportInteraction_model->UserGroup();

        $data['main_content'] = 'pages/report/interaction/find';
        $data['view_name'] = $this->lang->line("report_interaction_waba_header");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => true);
        $data['css'] = array("report.css");
        $data['js'] = array("report_interaction.js");

        $data['channel'] = $channel;
        $data['userGroup'] = $userGroup;

        $this->load->view('template',  $data);
    }


    function Get()
    {
        $post = $this->input->post();

        $records = $this->ReportInteraction_model->Count($post['text'] ?? "", $post["situation"] ?? "", $post["channel"], $post['dt_start'], $post['dt_end']);
        $query = $this->ReportInteraction_model->Get($post['text'] ?? "", $post["situation"] ?? "", $post["channel"] ?? "", $post['dt_start'] ?? "", $post['dt_end'] ?? "", $post['start'], $post['length'], $post['order'][0]['column'], $post['order'][0]['dir']);

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


    function GetUsers($id)
    {
        $data  = $this->ReportInteraction_model->GetUsers($id);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }


    function GetMessages()
    {
        $post = $this->input->post();
        $data = $this->ReportInteraction_model->GetMessages($post['id'], $post['creation']);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }


    function HistoryScroll()
    {
        $post = $this->input->post();
        $query = $this->ReportInteraction_model->HistoryScroll($post['id']);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($query));
    }
}
