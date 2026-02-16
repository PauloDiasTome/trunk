<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class ReportCallController extends TA_Controller
{

    public function __construct()
    {
        parent::__construct();
        parent::checkPermission("report_call");

        $this->load->model('UserGroup_model', '', TRUE);
        $this->load->model('ReportCall_model', '', TRUE);
        $this->lang->load('report_call_lang', $this->session->userdata('language'));
    }


    function Index()
    {
        $data['department'] = $this->User_model->ListDepartment();

        $users = $this->ReportCall_model->ListUsers();
        $labels = $this->ReportCall_model->ListLabels();
        $channels = $this->ReportCall_model->ListChannels();
        $categories = $this->ReportCall_model->ListCategories();

        $data['main_content'] = 'pages/report/call/find';
        $data['view_name'] = $this->lang->line("report_call_waba_header");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => true);
        $data['css'] = array("report.css");
        $data['js'] = array("report_call.js");

        $data['users'] = $users;
        $data['labels'] = $labels;
        $data['channels'] = $channels;
        $data['categories'] = $categories;

        $this->load->view('template',  $data);
    }


    function Get()
    {
        $post = $this->input->post();

        $records = $this->ReportCall_model->Count(
            $post['text'] ?? "",
            $post["channel"],
            $post["label"],
            $post['user'],
            $post["sector"],
            $post["categories"],
            $post["situation"],
            $post['dt_start'] ?? "",
            $post['dt_end'] ?? ""
        );

        $query = $this->ReportCall_model->Get(
            $post['text'] ?? "",
            $post["channel"],
            $post["label"],
            $post['user'],
            $post["sector"],
            $post["categories"],
            $post["situation"],
            $post['dt_start'] ?? "",
            $post['dt_end'] ?? "",
            $post['start'],
            $post['length'],
            $post['order'][0]['column'],
            $post['order'][0]['dir']
        );

        $data  = array(
            "draw" => $post['draw'],
            "recordsTotal" => $records,
            "recordsFiltered" => $records,
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
        $data = $this->ReportCall_model->GetMessages($post);

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
