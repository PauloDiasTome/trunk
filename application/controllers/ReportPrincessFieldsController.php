<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class ReportPrincessFieldsController extends TA_Controller
{

    public function __construct()
    {
        parent::__construct();
        parent::checkPermission("report");

        $this->load->model('ReportPrincessFields_model', '', TRUE);
        $this->lang->load('report_princess_lang', $this->session->userdata('language'));
    }


    function Index()
    {
        $data['main_content'] = 'pages/report/princess_fields/find';
        $data['view_name'] = $this->lang->line("report_princess_waba_header");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => true);
        $data['css'] = array("report.css");
        $data['js'] = array("report_princess_fields.js");

        $this->load->view('template',  $data);
    }


    function Get()
    {
        $post = $this->input->post();

        $records = $this->ReportPrincessFields_model->Count($post['text']  ?? "", $post['dt_start'], $post['dt_end']);
        $query = $this->ReportPrincessFields_model->Get($post['text'] ?? "", $post['dt_start'], $post['dt_end'], $post['start'], $post['length'], $post['order'][0]['column'], $post['order'][0]['dir']);

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
        $query = $this->ReportPrincessFields_model->History($post['id'], $post['creation']);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($query));
    }

}
