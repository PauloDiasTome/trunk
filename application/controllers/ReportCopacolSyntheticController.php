<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class ReportCopacolSyntheticController extends TA_Controller
{

    public function __construct()
    {
        parent::__construct();
        parent::checkPermission("report");

        $this->load->model('ReportCopacolSynthetic_model', '', TRUE);
        $this->lang->load('report_copacol_synthetic_lang', $this->session->userdata('language'));
    }


    function Index()
    {
        $data['main_content'] = 'pages/report/copacol/synthetic/find';
        $data['view_name'] = $this->lang->line("report_copacol_synthetic_topnav");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => true);
        $data['js'] = array("report_copacol_synthetic.js");
        $data['data']['name'] = $this->ReportCopacolSynthetic_model->List();
        $data['data']['nameUser'] = $this->ReportCopacolSynthetic_model->listUser();

        $this->load->view('template',  $data);
    }


    function Bot()
    {

        $post = $this->input->post();
        $query = $this->ReportCopacolSynthetic_model->Bot($post['dt-start'], $post['dt-end'], $post['select-situation'], $post['select-clerk'], $post['order'][0]['column'], $post['order'][0]['dir']);

        $data  = array(
            "draw" => $post['draw'],
            "recordsTotal" => count($query),
            "recordsFiltered" => count($query),
            "data" => $query
        );

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }


    function Ticket()
    {

        $post = $this->input->post();

        $records = $this->ReportCopacolSynthetic_model->Count();
        $query = $this->ReportCopacolSynthetic_model->Ticket($post['start'], $post['length'], $post['dt-start'], $post['dt-end'], $post['select-situation'], $post['select-clerk'], $post['order'][0]['column'], $post['order'][0]['dir']);

        $data  = array(
            "draw" => $post['draw'],
            "recordsTotal" =>  $records[0]['count'],
            "recordsFiltered" =>  $records[0]['count'],
            "data" => $query
        );

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }

}
