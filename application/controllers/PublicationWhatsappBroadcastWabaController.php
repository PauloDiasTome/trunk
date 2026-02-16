<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class PublicationWhatsappBroadcastWabaController extends TA_Controller
{

    public function __construct()
    {
        parent::__construct();
        parent::checkPermission("publication_whatsapp_waba");

        $this->load->model('PublicationWhatsappBroadcastWaba_model', '', TRUE);
        $this->lang->load('whatsapp_broadcast_waba_lang', $this->session->userdata('language'));

        $this->load->helper('string');
        $this->load->helper('whatsapp_preview_helper.php');
        $this->load->helper('kanban_communication_helper.php');
    }


    function Index()
    {
        $data['main_content'] = 'pages/publication/whatsapp_waba/broadcast/find';
        $data['view_name'] = $this->lang->line("whatsapp_broadcast_waba_header");
        $data['sidenav'] = array("");
        $data['topnav'] = array('search' => true);
        $data['js'] = array("publicationWhatsappBroadcastWaba.js");
        $data['css'] = array("publicationWhatsappBroadcastWaba.css");
        $data['channel'] = $this->PublicationWhatsappBroadcastWaba_model->listChannel();

        $this->load->view('template',  $data);
    }


    function Get()
    {
        $param = $this->input->post();
        $result = $this->PublicationWhatsappBroadcastWaba_model->Get($param);

        $data = array(
            "draw" => $param['draw'],
            "recordsTotal" => $result['count'],
            "recordsFiltered" => $result['count'],
            "data" => $result["query"]
        );

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }


    function Check_date($date, $hour)
    {
        $dateInformation = strtotime(implode('-', array_reverse(explode('/', trim($date)))) . " " . $hour . ":00");

        $current = date('Y-m-d');
        $current = strtotime($current);

        if ($dateInformation < $current) {
            $this->form_validation->set_message('check_date', $this->lang->line("whatsapp_broadcast_waba_check_date"));
            return false;
        } else if ($dateInformation >= $current) {
            return true;
        }
    }

    public function CheckTimeToEdit($token)
    {
        $data = $this->PublicationWhatsappBroadcastWaba_model->CheckTimeToEdit($token);

        $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }


    function Save($token)
    {
        $data = $this->input->post();

        $this->form_validation->set_rules('input_title', $this->lang->line("whatsapp_broadcast_waba_title"), 'trim|required|max_length[100]');
        $this->form_validation->set_rules('date_start', $this->lang->line("whatsapp_broadcast_waba_date_scheduling"), 'required|callback_check_date[' . $data['time_start'] . ']');
        $this->form_validation->set_rules('time_start', $this->lang->line("whatsapp_broadcast_waba_hour_scheduling"), 'required');
        $this->form_validation->set_rules('select_channel', $this->lang->line("whatsapp_broadcast_waba_select_channel"), 'required');

        if (isset($data['url_file'])) $this->form_validation->set_rules('url_file', $this->lang->line("whatsapp_broadcast_waba_title"), 'trim|required|min_length[3]');
        if (isset($data['select_type_parameters1']) && !isset($data['parametro11'])) $this->form_validation->set_rules('parametro1', $this->lang->line("whatsapp_broadcast_waba_title"), 'trim|required|min_length[3]');
        if (isset($data['select_type_parameters2'])) $this->form_validation->set_rules('parametro2', $this->lang->line("whatsapp_broadcast_waba_title"), 'trim|required|min_length[3]');

        if (isset($data['parametro1']) && !empty($data['headerText']) && strpos($data['headerText'], '{{1}}') !== false) {

            $this->form_validation->set_rules('parametro1', $this->lang->line("whatsapp_broadcast_waba_add_type_parameter"), 'trim|required|min_length[3]');
        }

        if (isset($data['qtdParametros'])) {
            $qtdParameters = $data["qtdParametros"];

            if (intVal($qtdParameters) > 0) {

                for ($i = 1; $i <= $qtdParameters; $i++) {
                    $this->form_validation->set_rules('parametro' . $i, $this->lang->line("parametro"), 'required');
                }
            }
        }

        if ($this->form_validation->run() == true) {

            if ($token != "0") {
                $ret = $this->PublicationWhatsappBroadcastWaba_model->Edit($token, $data);
                foreach ($ret["kanban_communication"] as $key => $value) {
                    $value->Cmd = $value->edit_broadcast ? "editPanelBroadcast" : "createKanbanBroadcast";
                    notifyKanbanCommunication($value);
                }
            } else {
                $ret = $this->PublicationWhatsappBroadcastWaba_model->Add($data);

                if ($ret['status']) {

                    if (isset($data['check_whatsapp']) && $data['check_whatsapp'] == "on") {
                        $data['channels_names'] = $ret['channels_names'][0];

                        if (count($ret['channels_names']) > 1) {
                            $count = count($ret['channels_names']) - 1;
                            $channel_count = $count > 1 ? 'canaiFs' : 'canal';
                            $data['channels_names'] = $ret['channels_names'][0] . ' e +' . $count . ' ' . $channel_count;
                        }

                        $data['submitted_by_user'] = trim($this->session->userdata("name"));
                    }
                }

                notifyKanbanCommunication($ret['kanban_communication']);
            }

            redirect('publication/whatsapp/broadcast/waba', 'refresh');
        } else {
            if ($token != "0")
                $this->Edit($token);
            else
                redirect('publication/whatsapp/broadcast/waba/add', 'refresh');
        }
    }


    function Add()
    {
        $channel = $this->PublicationWhatsappBroadcastWaba_model->listChannel();
        $template = $this->PublicationWhatsappBroadcastWaba_model->listTemplate();
        $queryLastBroadcast = $this->PublicationWhatsappBroadcastWaba_model->queryLastBroadcast();

        $this->load->model('Persona_model', '', TRUE);
        $group = $this->Persona_model->List();

        $data['main_content'] = 'pages/publication/whatsapp_waba/broadcast/add';
        $data['view_name'] = $this->lang->line("whatsapp_broadcast_waba_add");
        $data['sidenav'] = array('');
        $data['channel'] = $channel;
        $data['Groups'] = $group;
        $data['template'] = $template;
        $data['topnav'] = array('search' => false, 'header' => true);
        $data['js'] = array("publicationWhatsappBroadcastWaba.js");
        $data['css'] = array(
            "broadcast" => "publicationWhatsappBroadcastWaba.css",
            "select" => "msFmultiSelect.css",
        );

        if (count($queryLastBroadcast) > 0) {
            $data['date_start'] = $this->VerifyLastDate($queryLastBroadcast[0]["date"]);
            $data['time_start'] = $this->VerifyLastHour($queryLastBroadcast[0]["time"]);
        } else {
            $data['date_start'] = date('d/m/Y');
            $data['time_start'] = date('H:i');
        }

        $this->load->view('template',  $data);
    }


    function sortByDate($a, $b)
    {
        $ad = $a['log_timestamp_creation'];
        $bd = $b['log_timestamp_creation'];
        return ($ad - $bd);
    }


    function View($token)
    {
        $this->load->model('Channel_model', '', TRUE);

        $data['data'] = $this->PublicationWhatsappBroadcastWaba_model->View($token)[0];
        $data['data']['view'] = 'true';
        $data['channel'] = $this->Channel_model->List();
        $data['log'] = $this->PublicationWhatsappBroadcastWaba_model->getScheduleLog($token);

        $data['main_content'] = 'pages/publication/whatsapp_waba/broadcast/view';
        $data['view_name'] = $this->lang->line("whatsapp_broadcast_waba_view");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => false, 'header' => true);
        $data['js'] = array("publicationWhatsappBroadcastWaba.js");
        $data['css'] = array("publicationWhatsappBroadcastWaba.css");

        $this->load->view('template',  $data);
    }


    function CheckParameters($id_template)
    {

        $template = $this->PublicationWhatsappBroadcastWaba_model->getTemplate($id_template);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($template));
    }


    function Edit($token)
    {
        $data['data'] = $this->PublicationWhatsappBroadcastWaba_model->GetBroadcastByToken($token);
        $data['data']['edit'] = 'true';
        $data['template'] = $this->PublicationWhatsappBroadcastWaba_model->getTemplate($data['data']['id_template']);
        $data['type_broadcast'] = $data['data']['media_type'] == 27 ? "template" : "";
        $data['main_content'] = 'pages/publication/whatsapp_waba/broadcast/edit';
        $data['view_name'] = $this->lang->line("whatsapp_broadcast_waba_edit");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => false, 'header' => true);
        $data['js'] = array("publicationWhatsappBroadcastWaba.js");
        $data['css'] = array(
            "broadcast" => "publicationWhatsappBroadcastWaba.css",
            "select" => "msFmultiSelect.css"
        );

        $this->load->view('template',  $data);
    }


    function CalculateBalance($id_channel)
    {
        $data = $this->PublicationWhatsappBroadcastWaba_model->CalculateBalance($id_channel);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }


    function ListGroups($id_channel)
    {
        $data = $this->PublicationWhatsappBroadcastWaba_model->ListGroups($id_channel);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }


    function Cancel($key_id)
    {
        $data = $this->PublicationWhatsappBroadcastWaba_model->Cancel($key_id);

        if (isset($data['success']['status']) && $data['success']['status'] == true) {

            $kanban_communication = new stdClass();
            $kanban_communication->token = $key_id;
            $kanban_communication->key_remote_id = $this->PublicationWhatsappBroadcastWaba_model->getChannelKeyRemoteIdByToken($key_id);
            $kanban_communication->Cmd = "cancelBroadcast";

            notifyKanbanCommunication($kanban_communication);
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }


    function CancelGroup()
    {
        $post = $this->input->post();
        $data = $this->PublicationWhatsappBroadcastWaba_model->CancelGroup($post);

        if (isset($data['success']['status']) && $data['success']['status'] == true) {
            foreach ($post['tokens'] as $key => $val) {
                $kanban_communication = new stdClass();
                $kanban_communication->token = $val;
                $kanban_communication->key_remote_id = $this->PublicationWhatsappBroadcastWaba_model->getChannelKeyRemoteIdByToken($val);
                $kanban_communication->Cmd = "cancelBroadcast";

                notifyKanbanCommunication($kanban_communication);
            }
        }

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }

    function prepareSendPreview()
    {
        $data = $this->input->post();
        $template = $this->PublicationWhatsappBroadcastWaba_model->getTemplate($data['id_template']);
        $data['template'] = $template[0];
        $this->PublicationWhatsappBroadcastWaba_model->prepareCampaignPreview($data);
    }


    function validContactChannel()
    {
        $data = $this->input->post();
        $contactExist = validContactChannel($data);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($contactExist));
    }


    function ResendBroadcast($token)
    {
        $data = $this->PublicationWhatsappBroadcastWaba_model->ResendBroadcast($token);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }


    function VerifyLastDate($data)
    {
        $formatLastDate = DateTime::createFromFormat('d/m/Y', $data)->format('y-m-d');

        $lastDate = new DateTime($formatLastDate);
        $currentDate = new DateTime(date('y-m-d'));

        if ($lastDate >= $currentDate) {
            return $data;
        } else {
            return date('d/m/Y');
        }
    }


    function VerifyLastHour($data)
    {
        if ($data . '00' > date('H:i:s')) {
            return $data;
        } else {
            return date('H:i');
        }
    }
}
