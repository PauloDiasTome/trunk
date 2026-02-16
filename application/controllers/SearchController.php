<?php



if (!defined('BASEPATH')) exit('No direct script access allowed');
class SearchController extends TA_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        // parent::checkPermission("report");
    }


    function Index($q = null)
    {
        $data['main_content'] = 'pages/search/index';
        $data['view_name'] = 'Search';
        $data['js'] = array("search.js");

        $this->load->model('Luis_model', '', TRUE);

        $this->Luis_model->QuestionResquest($q);
        //$this->Luis_model->UpdateAllUserName();
        //$this->Luis_model->UpdateAllSectorName();

        if ($this->Luis_model->Erro == "") {
            $this->load->model('Report_model', '', TRUE);
            $data['thead'] = $this->Report_model->Report($this->Luis_model->Fnc, $this->Luis_model->Parameters, null, true)['columns'];
        }

        $data['data'] = array(
            'q' => $q,
            'erro' => $this->Luis_model->Erro,
            'Parameters' => htmlspecialchars(json_encode($this->Luis_model->Parameters)),
            'Fnc' => $this->Luis_model->Fnc
        );

        $this->load->view('template', $data);
    }


    function SearchResult()
    {
        // datatabless post
        $this->load->model('Report_model', '', TRUE);
        $post = $this->input->post();

        $Pagination = array(
            'start' => (int)$post['start'],
            'length' => (int)$post['length']
        );

        $report = $this->Report_model->Report($post['Fnc'], json_decode($post['Parameters'], true), $Pagination, false);

        $data  = array(
            "draw" => (int)$post['draw'],
            "recordsTotal" => $report['total'],
            "recordsFiltered" => $report['total'],
            "data" => $report['values']
        );

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }
}
