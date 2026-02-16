<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class OAuthController extends TA_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('responses');
    }

    function index()
    {
        echo "ok";
    }

    function OAuthTelegram()
    {
        $data = $this->input->post();

        if ($data['input-token-telegram'] != "") {

            $this->load->model('Telegram_model', 'Telegram_model', FALSE);

            $this->Telegram_model->init(
                $data['input-token-telegram']
            );

            $resp = $this->Telegram_model->OAuth();
            $this->session->set_flashdata('oauth_response', $resp);
            redirect("integration/add", "refresh");
        }
    }

    function OAuthFacebook()
    {
        $code = $this->input->get('code');

        $this->load->model('Facebook_model', '', FALSE);

        $this->Facebook_model->init(
            $this->config->config['facebook_app_id'],
            $this->config->config['facebook_app_secret'],
            base_url("facebook/oauth")
        );

        if ($code == "") {
            redirect($this->Facebook_model->OAuth(), 'refresh');
        } else {
            $this->Facebook_model->getAccessToken($code);
            redirect("integration", "refresh");
        }
    }


    function OAuthInstagram()
    {
        $code = $this->input->get('code');

        $this->load->model('Instagram_model', '', FALSE);

        $this->Instagram_model->init(
            $this->config->config['facebook_app_id'],
            $this->config->config['facebook_app_secret'],
            base_url("instagram/oauth")
        );

        if ($code == "") {
            redirect($this->Instagram_model->OAuth(), 'refresh');
        } else {
            $this->Instagram_model->getAccessToken($code);
            redirect("integration", "refresh");
        }
    }

    function OAuthWABA()
    {
        $code = $this->input->get('code');

        $this->load->model('WABA_model', '', FALSE);

        $this->WABA_model->init(
            $this->config->config['waba_app_id'],
            $this->config->config['waba_app_secret'],
            base_url("waba/oauth")
        );

        if ($code == "") {
            redirect($this->WABA_model->OAuth(), 'refresh');
        } else {
            $response = $this->WABA_model->getAccessToken($code);

            if ($response == "duplicate") {
                redirect("integration?integration=false", "refresh");
            } else {
                redirect("integration?integration=true", "refresh");
            }
        }
    }


    function OAuthMercadoLivre()
    {
        $code = $this->input->get('code');

        $this->load->model('MercadoLivre_model', '', FALSE);

        $this->MercadoLivre_model->init(
            $this->config->config['mercadolivre_app_id'],
            $this->config->config['mercadolivre_secret_key']
        );

        if ($code == "") {

            $url = $this->MercadoLivre_model->getAuthUrl(
                base_url("mercadolivre/oauth"),
                $this->MercadoLivre_model::$AUTH_URL[$this->config->config['mercadolivre_siteId']]
            );

            redirect($url, 'refresh');
        } else {
            $user = $this->MercadoLivre_model->authorize($code, base_url("mercadolivre/oauth"));
            $this->MercadoLivre_model->register($code, $user['body']->access_token);
        }
    }
}
