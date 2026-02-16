<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class WorkTimeController extends TA_Controller
{

    public function __construct()
    {
        parent::__construct();
        parent::checkPermission("work_time");

        $this->load->model('WorkTime_model', '', TRUE);
        $this->lang->load('setting_worktime_lang', $this->session->userdata('language'));
    }

    function Index()
    {
        if ($this->input->server('REQUEST_METHOD') == 'GET') {

            $data['main_content'] = 'pages/worktime/find';
            $data['view_name'] = $this->lang->line("worktime_waba_header");
            $data['sidenav'] = array('');
            $data['topnav'] = array('search' => true);
            $data['js'] = array("worktime.js");

            $this->load->view('template',  $data);
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $post = $this->input->post();

            $total = $this->WorkTime_model->Count($post['search']['value']);
            $db = $this->WorkTime_model->Get($post['text'], $post['start'], $post['length'], $post['order'][0]['column'], $post['order'][0]['dir']);

            $data  = array(
                "draw" => $post['draw'],
                "recordsTotal" => $total[0]['count'],
                "recordsFiltered" => $total[0]['count'],
                "data" => $db
            );

            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($data));
        }
    }


    function Edit($id_work_time = null)
    {
        $data['main_content'] = 'pages/worktime/edit';
        $data['view_name'] = $this->lang->line("worktime_waba_edit");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => false, 'header' => true);
        $data['js'] = array("worktime.js");

        if ($this->input->server('REQUEST_METHOD') == 'GET') {

            if ($id_work_time != null) {
                $data['data'] = $this->WorkTime_model->GetById($id_work_time)[0];
                $data['data']['work_time_week'] = $this->WorkTime_model->WorkTimeWeekList($id_work_time);
            }

            $this->load->view('template',  $data);
        }

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $post = $this->input->post();

            $postback = array();

            foreach ($post as $key => $value) {
                if ((bool)strpos($key, 'start')) {
                    $this->form_validation->set_rules($key, 'Inicio', 'required');
                    $week = (int)$key[0];
                    $postback[$week] = array("week" => $week, "start" => $value, "end" => "");
                }

                if ((bool)strpos($key, 'end')) {
                    $this->form_validation->set_rules($key, 'Fim', 'required');
                    $postback[$week]["end"] = $value;
                }
            }

            $this->form_validation->set_rules('name', $this->lang->line("setting_timetable_subtitle_label"), 'required|min_length[3]|max_length[100]');
            $this->load->helper(array('form', 'url'));
            $this->load->library('form_validation');

            if ($this->form_validation->run() == FALSE) {
                if ($id_work_time != null) {
                    $data['data'] = $this->WorkTime_model->GetById($id_work_time)[0];
                    $this->WorkTime_model->WorkTimeWeekList($id_work_time);
                    $data['data']['work_time_week'] = $this->WorkTime_model->WorkTimeWeekList($id_work_time);
                } else {
                    $data['data']['work_time_week'] = $postback;
                }

                $this->load->view('template',  $data);
            } else {
                if ($id_work_time != null) {
                    $old = $this->WorkTime_model->WorkTimeWeekList($id_work_time);
                    $this->WorkTime_model->UpdateWorkTime($id_work_time, $postback, $old, $post['name']);
                } else {
                    $id_work_time = $this->WorkTime_model->AddWorkTime($post['name']);

                    $this->WorkTime_model->UpdateWorkTime($id_work_time, $postback);
                }


                return redirect('worktime');
            }
        }
    }


    function Delete($id_work_time)
    {
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($this->WorkTime_model->DeleteWorkTime($id_work_time)));
    }
    
}
