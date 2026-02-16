<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class TutorialController extends TA_Controller
{
    public function __construct()
    {
        parent::__construct();
        parent::checkPermission("ticket");

        $this->load->model('Tutorial_model', '', TRUE);
    }

    function GetStep()
    {
        $company_id = $this->session->userdata('id_company');

        if (!$company_id) {
            return $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(null));
        }

        $data = $this->Tutorial_model->getStep($company_id);

        return $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data ?: null));
    }

    function SaveStep()
    {

        $company_id = $this->session->userdata('id_company');
        $status = (int) $this->input->post('status');
        $current_step = (int) $this->input->post('current_step');

        if (!$company_id || !is_numeric($status) || !is_numeric($current_step)) {
            echo json_encode(['success' => false]);
            return;
        }

        $this->Tutorial_model->saveStep([
            'company_id'   => $company_id,
            'status'       => $status,
            'current_step' => $current_step
        ]);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['success' => true]));
    }
}
