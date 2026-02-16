<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class DashboardController extends TA_Controller
{
    public function __construct()
    {
        parent::__construct();
        parent::checkPermission("dashboard");

        $this->lang->load('dashboard_lang', $this->session->userdata('language'));
    }

    // DASHBOARD LINK CURTO - COMENTEI POIS NÃO ESTÁ UTILIZANDO //
    // function Index($link)
    // {
    //     //ESSA MODEL POSSUI CACHE DE BANCO
    //     // O CACHE FICA NA PASTA db-cache
    //     $this->load->model('Dashboard_model', '', TRUE);

    //     //Tabelas
    //     if ($this->input->server('REQUEST_METHOD') == 'POST') {

    //         $param = $this->input->post();
    //         $func = array();

    //         switch ($param['type']) {
    //             case "count_device_version":
    //                 $func = $this->Dashboard_model->countShortLinkDeviceVersion($link, $param['dt-start'], $param['dt-end']);
    //                 break;
    //             case "count_country":
    //                 $func = $this->Dashboard_model->countShortLinkCountry($link, $param['dt-start'], $param['dt-end']);
    //                 break;
    //             case "count_region":
    //                 $func = $this->Dashboard_model->countShortLinkRegion($link, $param['dt-start'], $param['dt-end']);
    //                 break;
    //             case "count_city":
    //                 $func = $this->Dashboard_model->countShortLinkCity($link, $param['dt-start'], $param['dt-end']);
    //                 break;
    //             case "count_visitor":
    //                 $func = $this->Dashboard_model->countVisitorPage($link, $param['dt-start'], $param['dt-end']);
    //                 break;
    //             case "count_agent":
    //                 $func = $this->Dashboard_model->countShortLinkAgent($link, $param['dt-start'], $param['dt-end']);
    //                 break;
    //         }

    //         $data  = array(
    //             "recordsTotal" => count($func),
    //             "data" => $func
    //         );

    //         return $this->output
    //             ->set_content_type('application/json')
    //             ->set_status_header(200)
    //             ->set_output(json_encode($data));
    //     }

    //     $data['main_content'] = 'pages/dashboard/find';
    //     $data['view_name'] = $this->lang->line("dashboard_waba_header");
    //     $data['sidenav'] = array('');
    //     $data['topnav'] = array('search' => true);
    //     $data['js'] = array();

    //     //Contadores
    //     $data['count'] = $this->Dashboard_model->countShortLinkTotal($link);
    //     $data['count_month'] = $this->Dashboard_model->countShortLinkMonth($link);
    //     $data['count_week'] = $this->Dashboard_model->countShortLinkWeek($link);
    //     $data['count_day'] = $this->Dashboard_model->countShortLinkDay($link);

    //     //Graficos
    //     $data['month_chart'] = $this->Dashboard_model->ShortLinkLineChart($link, 31);
    //     $data['week_chart'] = $this->Dashboard_model->ShortLinkLineChart($link, 7);
    //     $data['year_chart'] = $this->Dashboard_model->ShortLinkYearChart($link);

    //     // Isso aqui não estava usando então comentei
    //     // $data['count_group_day'] = $this->Dashboard_model->countShortLinkGroupDay($link);
    //     // $data['count_link'] = $this->Dashboard_model->countShortLinkLink($link);

    //     $this->load->view('template',  $data);
    // }

    function Ticket()
    {
        $link = 3;
        //ESSA MODEL POSSUI CACHE DE BANCO
        // O CACHE FICA NA PASTA db-cache
        $this->load->model('Dashboard_model', '', TRUE);

        //Tabelas
        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $param = $this->input->post();
            $func = array();

            switch ($param['type']) {
                case "count_users":
                    $func = $this->Dashboard_model->countTicketUsers($param['dt-start'], $param['dt-end']);
                    break;
                case "count_type":
                    $func = $this->Dashboard_model->countTicketType($param['dt-start'], $param['dt-end']);
                    break;
                case "count_status":
                    $func = $this->Dashboard_model->countTicketStatus($param['dt-start'], $param['dt-end']);
                    break;
            }

            $data  = array(
                "recordsTotal" => count($func),
                "data" => $func
            );

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($data));
        }

        $data['main_content'] = 'pages/dashboard/tickets';
        $data['view_name'] = $this->lang->line("dashboard_waba_header");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => true);
        $data['js'] = array();

        //Contadores
        $data['count'] = $this->Dashboard_model->countTicketTotal();
        $data['count_month'] = $this->Dashboard_model->countTicketMonth();
        $data['count_week'] = $this->Dashboard_model->countTicketWeek();
        $data['count_day'] = $this->Dashboard_model->countTicketDay();

        // //Graficos
        $data['month_chart'] = $this->Dashboard_model->TicketLineChart(31);
        $data['week_chart'] = $this->Dashboard_model->TicketLineChart(7);
        $data['six_months_chart'] = $this->Dashboard_model->TicketLastSixMonthsChart();

        $this->load->view('template',  $data);
    }

    function Calls()
    {
        $data['main_content'] = 'pages/dashboard/calls';
        $data['view_name'] = $this->lang->line("dashboard_waba_header");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => true);
        $data['js'] = array();

        $this->load->model('Dashboard_model', '', TRUE);

        //Graficos
        $data['count_calls_group_week'] = $this->Dashboard_model->countCallsGroup(7);
        $data['count_calls_group_month'] = $this->Dashboard_model->countCallsGroup(31);
        $data['count_contact_week'] = $this->Dashboard_model->countContactGroup(7);
        $data['count_contact_month'] = $this->Dashboard_model->countContactGroup(30);
        $data['count_plataform'] = $this->Dashboard_model->countPlataform();
        $data['count_user_presence'] = $this->Dashboard_model->countUserPresence();
        $data['count_messages'] = $this->Dashboard_model->countMessages();

        //Indicadores
        $data['count_calls'] = $this->Dashboard_model->countCalls();
        $data['count_calls_open'] = $this->Dashboard_model->countCallsOpen();
        $data['count_calls_close'] = $this->Dashboard_model->countCallsClose();
        $data['count_wait_list'] = $this->Dashboard_model->countWaitList();
        $data['avg_wait_list'] = $this->Dashboard_model->avgTimeWaitList();
        $data['list_calls_open'] = $this->Dashboard_model->listCallsOpen();
        $data['list_wait_list'] = $this->Dashboard_model->listWaitList();

        $data['list_ticket_is_open'] = $this->Dashboard_model->listTicketIsOpen();
        $data['user_avg_presence'] = $this->Dashboard_model->avgUserPresence();

        $this->load->view('template',  $data);
    }
}
