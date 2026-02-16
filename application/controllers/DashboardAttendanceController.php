<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class DashboardAttendanceController extends TA_Controller
{
    public function __construct()
    {
        parent::__construct();
        parent::checkPermission("dashboard");

        $this->load->model('DashboardAttendance_model', '', TRUE);
        $this->lang->load('dashboard_attendance_lang', $this->session->userdata('language'));
        $this->load->helper('responses');
    }

    function Index()
    {
        $data['channels'] = $this->DashboardAttendance_model->ListChannels();
        $data['sectors'] = $this->DashboardAttendance_model->ListSectors();
        $data['main_content'] = 'pages/dashboard/attendance';
        $data['view_name'] = $this->lang->line("dashboard_attendance_title");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => false);
        $data['js'] = array('chart2.js', 'dashboard_attendance.js');
        $data['css'] = array('dashboard_attendance.css');

        $this->load->view('template',  $data);
    }

    function GetAvgWaitTime()
    {
        $params = $this->input->post();
        $data = $this->DashboardAttendance_model->GetAvgWaitTime($params);

        return json_response($this, 200, $data);
    }

    function GetAvgResponseTime()
    {
        $params = $this->input->post();
        $data = $this->DashboardAttendance_model->GetAvgResponseTime($params);

        return json_response($this, 200, $data);
    }

    function GetAvgServiceTime()
    {
        $params = $this->input->post();
        $data = $this->DashboardAttendance_model->GetAvgServiceTime($params);

        return json_response($this, 200, $data);
    }

    function TotalAttendances()
    {
        $params = $this->input->post();
        $data = $this->DashboardAttendance_model->TotalAttendances($params);

        return json_response($this, 200, $data);
    }

    function GetPeakService()
    {
        $params = $this->input->post();
        $data = $this->DashboardAttendance_model->GetPeakService($params);

        return json_response($this, 200, $data);
    }

    function getStartedEndClosed()
    {
        $params = $this->input->post();
        $data = $this->DashboardAttendance_model->GetOpenClosed($params);

        return json_response($this, 200, $data);
    }

    public function getCategoryDistribution()
    {
        $id_channel = $this->input->get('id_channel');
        $period = $this->input->get('period');
        $sector = $this->input->get('sector');


        $this->load->model('DashboardAttendance_model');
        $result = $this->DashboardAttendance_model->getCategoryDistribution($id_channel, $period, $sector);

        $response = [
            'success' => true,
            'data' => $result
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    public function getUserStatus()
    {
        $params = $this->input->post();
        $data = $this->DashboardAttendance_model->getUserStatus($params);
        $count = $this->DashboardAttendance_model->getUserStatusCount($params);

        foreach ($data as &$user) {
            $params["key_remote_id"] = $user['user_key_remote_id'];
            $user['tma'] = $this->DashboardAttendance_model->GetAvgServiceTime($params);
            $user['tmr'] = $this->DashboardAttendance_model->GetAvgResponseTime($params);
        }

        $datas  = array(
            "draw" => isset($params['draw']) ? (int)$params['draw'] : 1,
            "recordsTotal" => $count,
            "recordsFiltered" => $count,
            "data" => $data
        );

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($datas));
    }

    public function getChatbotQuantitative()
    {
        $id_channel = $this->input->post('id_channel');
        $period     = $this->input->post('period');

        $result = $this->DashboardAttendance_model->getChatbotQuantitative($id_channel, $period);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'data' => $result
            ]));
    }
    public function getAttendanceOrigin()
    {
        $id_channel = $this->input->get('id_channel');
        $period     = $this->input->get('period');
        $sector     = $this->input->get('sector');

        $result = $this->DashboardAttendance_model->getAttendanceOrigin($id_channel, $period, $sector);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['data' => $result]));
    }

    public function getChatbotAbandonment()
    {
        $id_channel = $this->input->post('id_channel');
        $period     = $this->input->post('period');

        $result = $this->DashboardAttendance_model->getChatbotAbandonment($id_channel, $period);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(['data' => $result]));
    }
}
