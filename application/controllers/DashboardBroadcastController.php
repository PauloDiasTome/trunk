<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class DashboardBroadcastController extends TA_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('DashboardBroadcast_model', '', TRUE);
        $this->lang->load('dashboard_broadcast_lang', $this->session->userdata('language'));
    }


    function Index()
    {
        $data['channels'] = $this->DashboardBroadcast_model->listChannels();
        $data['main_content'] = 'pages/dashboard/broadcast';
        $data['view_name'] = $this->lang->line("dashboard_broadcast_title");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => false);
        $data['js'] = array('chart2.js', 'dashboard_broadcast.js');
        $data['css'] = array('dashboard_broadcast.css');

        $this->load->view('template',  $data);
    }

    function GetBroadcast()
    {
        $params = $this->input->post();
        $data = $this->DashboardBroadcast_model->getBroadcast($params);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }

    function GetInteraction()
    {
        $params = $this->input->post();
        $data = $this->DashboardBroadcast_model->getInteraction($params);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }

    function GetReactions()
    {
        $params = $this->input->post();
        $data = $this->DashboardBroadcast_model->getReactions($params);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }

    function GetAllContacts()
    {
        $params = $this->input->post();
        $data = $this->DashboardBroadcast_model->getAllContacts($params);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }

    function GetCampaignStats()
    {
        $params = $this->input->post();
        $data = $this->DashboardBroadcast_model->getCampaignStats($params);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }

    function GetActiveContacts()
    {
        $params = $this->input->post();
        $data = $this->DashboardBroadcast_model->getActiveContacts($params);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }

    function GetInactiveContacts()
    {
        $params = $this->input->post();
        $data = $this->DashboardBroadcast_model->getInactiveContacts($params);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }

    function Engagement()
    {
        $params = $this->input->post();

        $data = $this->DashboardBroadcast_model->getEngagement($params);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }
}
