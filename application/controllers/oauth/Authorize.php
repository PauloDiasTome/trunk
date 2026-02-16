<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Authorize extends TA_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library("Server", "server");
        $this->load->model('developers/Oauth_clients_model', 'Oauth_clients_model', TRUE);
    }


    function index()
    {
        $scope = $this->input->get("scope");
        $state = $this->input->get("state");
        $client_id = $this->input->get("client_id");
        $redirect_uri = $this->input->get("redirect_uri");
        $response_type = $this->input->get("response_type");

        $app_data = $this->Oauth_clients_model->Get($client_id);

        if ($app_data === null) {
            $error['msg_error'] = "The client id supplied is invalid";
            $this->load->view('oauth/invalid_url.php', $error);
            return;
        } else {

            if ($scope !== $app_data['scope']) {
                $error['msg_error'] = "The provided scope is invalid";
                $this->load->view('oauth/invalid_url.php', $error);
                return;
            } else if ($state !== "<state>") {
                $error['msg_error'] = "The state provided is invalid";
                $this->load->view('oauth/invalid_url.php', $error);
                return;
            } else if ($response_type !== "code") {
                $error['msg_error'] = "Response type provided is invalid";
                $this->load->view('oauth/invalid_url.php', $error);
                return;
            }
        }


        if (!isset($_POST["authorized"])) {
            $this->server->check_client_id();
            $data = array(
                "scope" => $scope,
                "state" => $state,
                "client_id" => $client_id,
                "redirect_uri" => $redirect_uri,
                "response_type" => $response_type,
                "name" => $this->Oauth_clients_model->Get($client_id)['name']
            );

            $this->load->view("oauth/authorize", $data);
        } else {
            $authorized = $this->input->post("authorized");

            if ($authorized === "yes") {

                if ($this->session->userdata('key_remote_id') == null) {
                    session_destroy();
                    return redirect('account/login?url_callback=' .  $_SERVER["HTTP_REFERER"]);
                } else {

                    $this->server->authorize(($authorized === "yes"));
                }
            } else {
                echo "error";
            }
        }
    }
}
