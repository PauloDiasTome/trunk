<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class ReportContactController extends TA_Controller
{

    public function __construct()
    {
        parent::__construct();
        parent::checkPermission("report");
        
        $this->lang->load('report_contact_lang', $this->session->userdata('language'));
    }


    function Index()
    {
        $data['main_content'] = 'pages/report/contact/find';
        $data['view_name'] = $this->lang->line("report_contact_waba_header");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => true);
        $data['css'] = array("report.css");
        $data['js'] = array("report_contact.js");

        $this->load->view('template',  $data);
    }


    function Get()
    {
        $post = $this->input->post();
        $this->load->model('ReportContact_model', '', TRUE);

        $count = $this->ReportContact_model->Count($post['text'] ?? "");
        $query = $this->ReportContact_model->Get($post['text'] ?? "", $post['start'], $post['length'], $post['order'][0]['column'], $post['order'][0]['dir']);

        $data  = array(
            "draw" => $post['draw'],
            "recordsTotal" => $count[0]['count'],
            "recordsFiltered" => $count[0]['count'],
            "data" => $query
        );

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }

}
