<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class ReportCopacolAnalyticalController extends TA_Controller
{

    public function __construct()
    {
        parent::__construct();
        parent::checkPermission("report");

        $this->load->model('ReportCopacolAnalytical_model', '', TRUE);
        $this->lang->load('report_copacol_analytic_lang', $this->session->userdata('language'));
    }

    
    function Index()
    {
        $data['main_content'] = 'pages/report/copacol/analytical/find';
        $data['view_name'] = $this->lang->line("report_copacol_analytic_topnav");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => true);
        $data['js'] = array("report_copacol_analytical.js");
        $data['data']['status'] = $this->ReportCopacolAnalytical_model->List();
        $data['data']['statusBot'] = $this->ReportCopacolAnalytical_model->ListBot();

        $this->load->view('template',  $data);
    }


    function Get()
    {
        $post = $this->input->post();

        $records = $this->ReportCopacolAnalytical_model->Count($post['filter']  ?? "", $post['select-type'], $post['select-situation'], $post['dt-start'], $post['dt-end']);
        $query = $this->ReportCopacolAnalytical_model->Get($post['filter']  ?? "", $post['select-type'], $post['select-situation'], $post['dt-start'], $post['dt-end'], $post['start'], $post['length'], $post['order'][0]['column'], $post['order'][0]['dir']);

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

}
