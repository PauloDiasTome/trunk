<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class ProductStatusController extends TA_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('ProductStatus_model', '', TRUE);
    }


    function Index()
    {
        $data['main_content'] = 'pages/product_status/find';
        $data['view_name'] = 'Ticket status';
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => true);
        $data['js'] = array("product_status.js");

        $this->load->view('template',  $data);
    }


    function Get()
    {
        $post = $this->input->post();

        $records = $this->ProductStatus_model->Count($post['text']  ?? "");
        $query = $this->ProductStatus_model->Get($post['text']  ?? "", $post['start'], $post['length']);

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


    function Save($key_id)
    {
        $data = $this->input->post();

        $this->form_validation->set_rules('input-name', 'Nome', 'trim|required|min_length[3]alpha_numeric_spaces');

        if ($this->form_validation->run() == true) {

            if ($key_id > 0) {
                $this->ProductStatus_model->Edit($key_id, $data);
            } else {
                $this->ProductStatus_model->Add($data);
            }

            redirect("product/status", 'refresh');
        } else {
            if ($key_id > 0) {
                $this->Edit($key_id);
            } else {
                $this->Add();
            }
        }
    }


    function Add()
    {
        $data['main_content'] = 'pages/product_status/add';
        $data['sidenav'] = array('');
        $data['view_name'] = "Adicionar";
        $data['topnav'] = array('search' => false, 'header' => true);
        $data['js'] = array("product_status.js");

        $this->load->view('template',  $data);
    }


    function Edit($key_id)
    {
        $data['title'] = "TalkAll | Editar";
        $data['id'] = ($key_id);

        $info = $this->ProductStatus_model->GetInf($key_id);

        $data['data'] = $info[0];

        $data['main_content'] = 'pages/product_status/edit';
        $data['sidenav'] = array('');
        $data['view_name'] = "Editar";
        $data['topnav'] = array('search' => false, 'header' => true);
        $data['js'] = array("product_status.js");

        $this->load->view('template',  $data);
    }


    function Delete($key_id)
    {
        $this->ProductStatus_model->Delete($key_id);
    }
}
