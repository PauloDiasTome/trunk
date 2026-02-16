<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class IntegrationController extends TA_Controller
{
    public function __construct()
    {
        parent::__construct();
        parent::checkPermission("integration");

        $this->load->model('Integration_model', '', TRUE);
        $this->load->model('Cloud_model', '', TRUE);
        $this->load->model('Business_model', '', TRUE);
        $this->lang->load('setting_integration_lang', $this->session->userdata('language'));
    }

    function Index()
    {
        $user_email = $this->session->userdata('email_user');
        $pages = $this->Integration_model->getPageFacebook();
        $instagram = $this->Integration_model->getPageInstagram();

        // Verificação de usuário de suporte
        $id_suport_user = ($this->StrLike('suporte.%', $user_email) && $this->StrLike('%@talkall.com.br', $user_email));

        $data['data'] = $this->Integration_model->Get('');
        $data['pages'] = $this->isInstagram($pages, $instagram);
        $data['integration'] = $this->input->get('integration');
        $data['id_suport_user'] = $id_suport_user;

        foreach ($data['data'] as &$row) {
            $phone = ($row['type'] == 2) ? $row['id'] : $row['display_phone_number'];
            $row['formatted_phone'] = $this->formatPhoneNumber($phone);
            $row['clean_phone'] = preg_replace('/\D/', '', $phone);
        }

        unset($row);

        $data['main_content'] = 'pages/integration/find';
        $data['view_name'] = $this->lang->line("integration_waba_header");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => true);
        $data['js'] = array("integration.js");
        $data['css'] = array("integration.css");
        $data['resp'] = $this->session->flashdata('response') ?? ['response' => 'no_response'];

        $this->load->view('template',  $data);
    }

    private function formatPhoneNumber($number)
    {
        if (empty($number)) {
            return '';
        }

        $raw = preg_replace('/\D/', '', $number);

        if ($raw == '') {
            return '';
        }

        if (substr($raw, 0, 2) != '55') {
            $raw = '55' . $raw;
        }

        $numberWithoutDDI = substr($raw, 2);

        if (strlen($numberWithoutDDI) >= 10) {
            $ddd = substr($numberWithoutDDI, 0, 2);
            $prefix = substr($numberWithoutDDI, 2, (strlen($numberWithoutDDI) == 11) ? 5 : 4);
            $suffix = substr($numberWithoutDDI, (strlen($numberWithoutDDI) == 11) ? 7 : 6);
            return "+55 ($ddd) $prefix-$suffix";
        }

        return "+55 " . $numberWithoutDDI;
    }

    function Add()
    {
        $pages = $this->Integration_model->getPageFacebook();
        $instagram = $this->Integration_model->getPageInstagram();

        $data['main_content'] = 'pages/integration/newIntegration';
        $data['view_name'] = $this->lang->line("integration_waba_header");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => false);
        $data['js'] = array("integration.js");
        $data['css'] = array("integration.css");

        $data['data'] = $this->Integration_model->Get('');
        $data['pages'] = $this->isInstagram($pages, $instagram);
        $data['resp'] = $this->session->flashdata('oauth_response') ?? ['response' => 'no_response'];

        $this->load->view('template',  $data);
    }

    function Edit($key_id = 0)
    {
        $info = $this->Integration_model->GetById($key_id);

        $data['view_name'] = $this->lang->line("integration_waba_edit");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => false, 'header' => true);
        $data['js'] = array("integration.js");

        if ($info['type'] == 3) {

            $data['main_content'] = 'pages/integration/widgetEdit';
            $queryWidget = $this->Integration_model->QueryWidget($key_id);
            $data['queryWidget'] = $queryWidget;
            $data['js'] = array('widget.js');
            $data['data'] = $info['id'];
        }

        if ($info['type'] == 28) {

            $data['main_content'] = 'pages/integration/tv/edit';
            $data['js'] = array('tv_integration.js');
            $data['css'] = array('integration.css');
            $tv_settings_array = json_decode($info['tv_settings'], true);

            $data['data'] = array(
                'name' => $info['name'],
                'id' => $info['id'],
                'id_channel' => $info['id_channel'],
                'pw' => $info['pw'],
                'picture' => $tv_settings_array['url'],
                'position' => $tv_settings_array['position'],
                'connection_code' => $info['tv_connection_code']
            );
        }

        $this->load->view('template',  $data);
    }

    function Widget()
    {
        $data['main_content'] = 'pages/integration/widget';
        $data['view_name'] = $this->lang->line("integration_waba_add");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => false, 'header' => true);
        $data['js'] = array("integration.js");
        $data['js'] = array('widget.js');
        $data['token'] = $this->load->model('Integration_model', '', TRUE);

        $this->load->view('template',  $data);
    }

    function CopyWidget($key_id)
    {
        $info = $this->Integration_model->GetById($key_id);
        $queryWidget = $this->Integration_model->QueryWidget($key_id);

        $data['main_content'] = 'pages/integration/widgetScritp';
        $data['view_name'] = $this->lang->line("integration_waba_copy");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => false, 'header' => true);
        $data['js'] = array("integration.js");

        $data['data'] = $info['id'];
        $data['queryWidget'] = $queryWidget;

        $this->load->view('template',  $data);
    }

    function tv()
    {
        $data['main_content'] = 'pages/integration/tv/add';
        $data['view_name'] = $this->lang->line("integration_waba_add");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => false, 'header' => true);
        $data['js'] = array("integration.js");
        $data['js'] = array('tv_integration.js');
        $data['token'] = $this->load->model('Integration_model', '', TRUE);
        $data['css'] = array("integration.css");

        $this->load->view('template',  $data);
    }

    function OpenAI()
    {
        $data['main_content'] = 'pages/integration/openAi';
        $data['view_name'] = $this->lang->line("integration_waba_add");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => false, 'header' => true);
        $data['js'] = array("openAi_integration.js");
        $data['token'] = $this->load->model('Integration_model', '', TRUE);
        $data['css'] = array("integration.css");

        $this->load->view('template',  $data);
    }

    function FacebookOAuth()
    {
        $data['main_content'] = 'pages/integration/facebook';
        $data['view_name'] = $this->lang->line("integration_waba_add");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => false, 'header' => true);
        $data['js'] = array("integration.js");

        $this->load->view('template',  $data);
    }

    function BusinessOAuth()
    {
        $data['main_content'] = 'pages/integration/business';
        $data['view_name'] = $this->lang->line("integration_waba_add");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => false, 'header' => true);
        $data['js'] = array("integration.js");

        $this->load->view('template',  $data);
    }


    private function exchangeCodeForAccessToken($code)
    {
        $app_id = '549279743372390';
        $app_secret = 'SEU_APP_SECRET';
        $redirect_uri = 'URL_EXATA_DO_SEU_REDIRECT';

        $url = "https://graph.facebook.com/v19.0/oauth/access_token?" . http_build_query([
            'client_id' => $app_id,
            'redirect_uri' => $redirect_uri,
            'client_secret' => $app_secret,
            'code' => $code,
        ]);

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR => true,
        ]);
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($httpCode !== 200) {
            return null;
        }

        $data = json_decode($response, true);
        return $data['access_token'] ?? null;
    }

    function isInstagram($pages, $instagram)
    {
        foreach ($pages as $idx => $value) {
            foreach ($instagram as $val) {

                if ($val["pw"] == $value["pw"]) {
                    $pages[$idx]["is_instagram"] = 1;
                    $pages[$idx]["id_channel_instagram"] = $val["id_channel"];
                    $pages[$idx]["status_instagram"] = $val["status"];
                }
            }
        }

        return $pages;
    }

    function EditWidget()
    {
        $data = $this->input->post();
        $data = $this->Integration_model->SaveWidget($data);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }

    function sendTvInfo($data)
    {
        $jsonData = json_encode($data);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://services.talkall.com.br:4000/apiNotify/' . $data['id'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        curl_exec($curl);
        curl_close($curl);
    }

    function SaveTv()
    {
        $post = $this->input->post();

        $this->form_validation->set_rules('name', $this->lang->line("setting_integration_tv_label_name"), 'trim|required|min_length[3]|max_length[55]');

        if ($this->form_validation->run() == true) {
            if ($post["id_channel"] == 0) {
                $data = $this->Integration_model->SaveTv($post);
            } else {
                $data = $this->Integration_model->EditTv($post);
                $this->sendTvInfo($data);
            }
        }

        $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }

    function SaveOpenAi()
    {
        $post = $this->input->post();

        $this->form_validation->set_rules('token', $this->lang->line("setting_integration_openai_token"), 'trim|required');
        $this->form_validation->set_rules('version', $this->lang->line("setting_integration_openai_version"), 'trim|required|max_length[30]');

        if ($this->form_validation->run() == true) {
            $data = $this->Integration_model->SaveOpenAi($post);
        }

        $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }

    function GetVersion()
    {
        $token = $this->input->post('token');

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.openai.com/v1/models',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: ' . 'Bearer ' . $token,
                'Cookie: __cf_bm=gKnbk0LJfz1DWFvVAEpuAP0NWk5PTSetYi0M57K06iw-1717618368-1.0.1.1-q4gue61BZfJRqIn8D2QCZQrhINqkofsM6b0AiwpRTZUMI26Zu3Jyk0BOtUB.3.QAIlf0SM2eZQ1Noc7BNvQagQ; _cfuvid=FButkUtjCYoZirXivcHFctvf_FlnfKBVDAhWThJQXGQ-1717588732022-0.0.1.1-604800000'
            ),
        ));

        $data = curl_exec($curl);

        curl_close($curl);

        $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }

    function stopChannelService($id_channel)
    {
        $channel_id = $this->Integration_model->getChannelId($id_channel);

        $curl = curl_init();

        $id = array("id" => $channel_id);
        $body = json_encode($id);

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://192.168.190.45:3044/stopservice',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return;
    }

    function CloudIntegration()
    {
        $code = $this->input->post("code");
        $phone_number_id = $this->input->post("phone_number_id");
        $waba_id = $this->input->post("waba_id");
        $pin_code = $this->input->post("pin_code");

        if ($code && $phone_number_id && $waba_id) {

            $access_token = $this->Cloud_model->GetAccessToken($code);

            if ($access_token) {

                $data = $this->Cloud_model->Add($access_token, $phone_number_id, $waba_id, $pin_code);

                if ($data["success"]["status"] === TRUE) {
                    return redirect(base_url("/integration"));
                } else {
                    return $this->CloudOAuth($data);
                }
            }
        } else {
            return $this->CloudOAuth();
        }
    }

    function SaveBusiness()
    {
        $waba_id = $this->input->post('waba_id');
        $auth_code =  $this->input->post('auth_code');

        $access_token = $this->Business_model->GetAccessToken($auth_code);
        $access_token = "token";

        if ($access_token) {
            // $info = $this->Business_model->GetPhoneNumbers($access_token, $waba_id);

            // $data = [
            //     'access_token' => $access_token,
            //     'id' => isset($info->data[0]->id) ? $info->data[0]->id : null,
            //     'verified_name' => isset($info->data[0]->verified_name) ? $info->data[0]->verified_name : "",
            //     'waba_id' => $waba_id,
            //     'display_phone_number' => isset($info->data[0]->display_phone_number)
            //         ? preg_replace('/[\+\-\s\(\)]/', '', $info->data[0]->display_phone_number)
            //         : ""
            // ];

            $display_phone_number = "554398656546";

            $data = [
                'access_token' => "EAAHzkSf8bGYBQotUtOVChk7syIsxTJs9ZCgt56WdTRs6YyscBt0JHZARGQBIky7G6CGnBY8S49OxN7NeDzyLzB8dTPZAtEBCCTTO4dF5abR6KBZBZATGBctvfYa7IW4N3BeYli3OjkmNLLSUBmlKVVAHZA3ZARF4OqhRHtKjCFCesGcQ73EvTKPRbJOEqCQDZAp0KCZB47GZASOvOKfzmlTOCg7hVsOeVhj8x1GHm3G9IBBYJN9YPgpnhxML2ZCxIj2hjOZBOTfnAl6UyPCzimhWiN9UhpGLPneyg9mZA7Asi",
                'id' => "105190699324768",
                'verified_name' => "Pedrim lojas",
                'waba_id' => "100555013136195",
                'display_phone_number' => $display_phone_number
            ];


            $data = $this->Business_model->Add($data);
            $data['display_phone_number'] = $display_phone_number;
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($data));
        }
    }

    function CloudOAuth($errors = null)
    {
        $data['errors'] = $errors;
        $data['main_content'] = 'pages/integration/cloud';
        $data['view_name'] = $this->lang->line("setting_integration_add_new");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => false, 'header' => true);
        $data['js'] = array("integration.js");

        $this->load->view('template',  $data);
    }

    function CancelTv()
    {
        $post = $this->input->post();
        $data = $this->Integration_model->DeleteTv($post);
        $this->sendTvInfo($data);

        $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([]));
    }

    function DeleteTv($id)
    {
        $data = $this->Integration_model->DeleteTv((int)$id);

        $this->sendTvInfo($data);
        return $data;
    }

    function DeleteOpenAi($id)
    {
        $data = $this->Integration_model->DeleteOpenAi((int)$id);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }

    function ClearChannel()
    {
        $post = $this->input->post();
        $data = $this->Integration_model->ClearChannel($post['id_channel']);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }

    function StrLike($needle, $haystack)
    {
        $regex = '/' . str_replace('%', '.*?', $needle) . '/';

        return preg_match($regex, $haystack) > 0;
    }

    function WABAOAuth()
    {
        $data['main_content'] = 'pages/integration/waba';
        $data['view_name'] = $this->lang->line("setting_integration_add_new");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => false, 'header' => true);
        $data['js'] = array("integration.js");

        $this->load->view('template',  $data);
    }

    function WhatsappBroadcast()
    {
        $data['main_content'] = 'pages/integration/addWhatsappBroadcast';
        $data['view_name'] = $this->lang->line("setting_integration_add_new");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => false, 'header' => true);
        $data['js'] = array("integrationWhatsapp.js");
        $data['css'] = array("integrationWhatsapp.css");

        $this->load->view('template',  $data);
    }

    function WhatsappCommunity()
    {
        $data['main_content'] = 'pages/integration/addWhatsappCommunity';
        $data['view_name'] = $this->lang->line("setting_integration_add_new");
        $data['sidenav'] = array('');
        $data['cacheCommunity'] = '561656464654';
        $data['topnav'] = array('search' => false, 'header' => true);
        $data['js'] = array("integrationCommunity.js");
        $data['css'] = array("integrationCommunity.css");

        $this->load->view('template',  $data);
    }

    function WhatsappNewsletter()
    {
        $data['main_content'] = 'pages/integration/addWhatsappNewsletter';
        $data['view_name'] = $this->lang->line("setting_integration_add_new");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => false, 'header' => true);
        $data['js'] = array("integrationNewsletter.js");
        $data['css'] = array("integrationNewsletter.css");

        $this->load->view('template',  $data);
    }

    function InstagramOAuth()
    {
        $data['main_content'] = 'pages/integration/instagram';
        $data['view_name'] = $this->lang->line("integration_waba_add");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => false, 'header' => true);
        $data['js'] = array("integration.js");

        $this->load->view('template',  $data);
    }

    function MercadoLivreOAuth()
    {
        $data['main_content'] = 'pages/integration/mercadolivre';
        $data['view_name'] = $this->lang->line("integration_waba_add");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => false, 'header' => true);
        $data['js'] = array("integration.js");

        $this->load->view('template',  $data);
    }

    function TelegramOAuth()
    {
        $data['main_content'] = 'pages/integration/telegram/add';
        $data['view_name'] = $this->lang->line("integration_waba_add");
        $data['sidenav'] = array('');
        $data['topnav'] = array('search' => false, 'header' => true);
        $data['js'] = array("integration.js", "integration_telegram.js");

        $this->load->view('template',  $data);
    }

    function HubspotAuth()
    {
        if ($this->input->server('REQUEST_METHOD') == 'GET') {
            $data['main_content'] = 'pages/integration/hubspot';
            $data['view_name'] = $this->lang->line("integration_waba_add");
            $data['sidenav'] = array('');
            $data['topnav'] = array('search' => false, 'header' => true);
            $data['js'] = array("integration.js");

            $url = "https://app.hubspot.com/oauth/authorize?";

            // social timeline
            $url_query = array(
                'client_id' => $this->config->item('hubspot_client_id'),
                'redirect_uri' => base_url('integration/add/hubspot'),
                'scope' => "tickets",
            );
            $url_query = http_build_query($url_query);

            $data['hubspot_url'] =  $url . $url_query;

            $get = $this->input->get();

            if (isset($get['code']) && $get['code'] != "") {
                $this->load->model('Hubspot_model', '', TRUE);
                if ($this->Hubspot_model->AddHubspot($get['code'])) {
                    redirect('integration', 'refresh');
                } else {
                    return show_error("Erro ao fazer a integração", 500, 'Um erro foi encontrado');
                }
            } else {
                $this->load->view('template',  $data);
            }
        }
    }

    function RdStationAuth()
    {
        if ($this->input->server('REQUEST_METHOD') == 'GET') {
            $data['main_content'] = 'pages/integration/rdstation';
            $data['view_name'] = $this->lang->line("integration_waba_add");
            $data['sidenav'] = array('');
            $data['topnav'] = array('search' => false, 'header' => true);
            $data['js'] = array("integration.js");

            $url = "https://api.rd.services/auth/dialog?";

            // social timeline
            $url_query = array(
                'redirect_url' => base_url('integration/add/rdstation'),
                'client_id' => $this->config->item('rdstation_client_id')
            );
            $url_query = http_build_query($url_query);

            $data['rd_url'] =  $url . $url_query;

            $get = $this->input->get();

            if (isset($get['code']) && $get['code'] != "") {
                $this->load->model('RdStation_model', '', TRUE);
                if ($this->RdStation_model->AddRD($get['code'])) {
                    redirect('integration', 'refresh');
                } else {
                    return show_error("Erro ao fazer a integração", 500, 'Um erro foi encontrado');
                }
            } else {
                $this->load->view('template',  $data);
            }
        }
    }

    function ZendeskAuth()
    {
        if ($this->input->server('REQUEST_METHOD') == 'GET') {
            $data['main_content'] = 'pages/integration/zendesk';
            $data['view_name'] = $this->lang->line("integration_waba_add");
            $data['sidenav'] = array('');
            $data['topnav'] = array('search' => false, 'header' => true);
            $data['js'] = array("integration.js");

            $url = "https://d3v-talkall.zendesk.com/oauth/authorizations/new?";

            $url_query = array(
                'client_id' => $this->config->item('zendesk_app_id'),
                'response_type' => 'code',
                'redirect_uri' => base_url('integration/add/zendesk'),
                'scope' => 'write read',
            );
            $url_query = http_build_query($url_query);

            $data['zendesk_url'] =  $url . $url_query;

            $get = $this->input->get();

            if (isset($get['code']) && $get['code'] != "") {
                $this->load->model('Zendesk_model', '', TRUE);

                if ($this->Zendesk_model->Add($get['code'], $get['state'])) {
                    redirect('integration', 'refresh');
                } else {
                    return show_error("Erro ao fazer a integração", 500, 'Um erro foi encontrado');
                }
            } else {
                $this->load->view('template',  $data);
            }
        }
    }

    function WhatsappBroadcastSave()
    {
        $post = $this->input->post('channels');
        $data = json_decode($post, true);

        $this->Integration_model->AddChannelBusiness($data);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(["status" => true]));
    }

    function SaveNumberTrigger()
    {
        $post = $this->input->post();

        $data = [[
            'phone' => $post['trigger'],
            'name' => $post['name'],
            'file' => $post['photo_url'] ?? '',
            'description' => $post['description'] ?? '',
        ]];

        $this->Integration_model->AddChannelBusiness($data);

        $trigger = [
            'name' => $post['name'],
            'trigger' => $post['trigger'],
            'description' => $post['description'] ?? '',
            'photo_url' => $post['photo_url'] ?? ''
        ];

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode([
                'success' => true,
                'trigger' => $post['trigger'],
                'name'    => $post['name']
            ]));
    }

    function WhatsappBroadcastDuplicatePhone($phone)
    {
        $data = $this->Integration_model->WhatsappBroadcastDuplicatePhone($phone);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(["status" => $data]));
    }

    function WhatsappCommunityDuplicatePhoneTrigger($phone)
    {
        $data = $this->Integration_model->WhatsappCommunityDuplicatePhoneTrigger($phone);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(["status" => $data]));
    }

    function WhatsappCommunityDuplicatePhoneCreator($phone)
    {
        $data = $this->Integration_model->WhatsappCommunityDuplicatePhoneCreator($phone);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(["status" => $data]));
    }

    function PageDelete($id_page)
    {
        $this->Integration_model->PageDelete((int)$id_page);
        $this->Index();
    }

    function Delete($id_channel)
    {
        $analyze_channel = $this->Integration_model->analyzeChannel($id_channel);

        if ($analyze_channel != 0) {
            return $this->output
                ->set_content_type('application/json')
                ->set_status_header(200)
                ->set_output(json_encode($analyze_channel));
        }

        $this->stopChannelService($id_channel);
        return $this->Integration_model->Delete((int)$id_channel);
    }
    public function WhatsappCommunitySave()
    {
        $post = $this->input->post('communitys');

        $this->Integration_model->addCommunity($post);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(["status" => true]));
    }

    public function WhatsappCommunityChannels()
    {
        $data = $this->Integration_model->getCommunityChannels();

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }

    public function WhatsappNewsletterSave()
    {
        $post = $this->input->post('newsletters');

        $this->Integration_model->addNewsletter($post);

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode(["status" => true]));
    }
    public function WhatsappNewsletterChannels()
    {
        $data = $this->Integration_model->getNewsletterChannels();

        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($data));
    }
}
