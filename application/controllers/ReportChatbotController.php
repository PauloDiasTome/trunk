<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class ReportChatbotController extends TA_Controller
{

    public function __construct()
    {
        parent::__construct();
        parent::checkPermission("report");

        $this->load->model('ReportChatbot_model', '', TRUE);
        $this->lang->load('report_chatbot_lang', $this->session->userdata('language'));
    }


    function Index()
    {
        $channel = $this->ReportChatbot_model->List();

        $data['main_content'] = 'pages/report/chatbot/find';
        $data['view_name'] = $this->lang->line("report_chatbot_waba_header");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => true);
        $data['css'] = array("report.css");
        $data['js'] = array("report_chatbot.js");

        $data['channel'] = $channel;

        $this->load->view('template',  $data);
    }


    function Get()
    {
        $post = $this->input->post();

        $records = $this->ReportChatbot_model->Count($post['text'] ?? "", $post['dt_start'], $post['dt_end'], $post["channel"]);
        $query = $this->ReportChatbot_model->Get($post['text'] ?? "", $post["channel"] ?? "", $post['dt_start'] ?? "", $post['dt_end'] ?? "", $post['start'], $post['length'], $post['order'][0]['column'], $post['order'][0]['dir']);

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


    function History()
    {
        $post = $this->input->post();
        $query = $this->ReportChatbot_model->History($post['id'], $post['creation']);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($query));
    }


    function HistoryScroll()
    {
        $post = $this->input->post();
        $query = $this->ReportChatbot_model->HistoryScroll($post['id']);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($query));
    }

}
