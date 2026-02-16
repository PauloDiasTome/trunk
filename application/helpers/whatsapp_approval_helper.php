<?php

defined('BASEPATH') or exit('No direct script access allowed');

function sendWhatsappApproval($data)
{
    $data['url_params'] = getURLParams($data);

    $date = new DateTime();

    $timestamp = $date->getTimestamp();

    if (count($data['url_params']['contact_data']) > 0) {

        foreach ($data['url_params']['contact_data'] as $maneger) {

            $manager_number = explode('-', $maneger['key_remote_id'])[0];

            $data['key_id'] = strtoupper(random_string('alnum', 32));
            $data['key_remote_id'] = $manager_number . '-554333753133';
            $data['approval_message'] = str_replace(array("\r", "\n"), ' ', $data['approval_message']);

            $jsonObj = new stdClass();
            $jsonObj->Cmd = "TemplateMessage";
            $jsonObj->timestamp = $timestamp;
            $jsonObj->key_id = $data['key_id'];
            $jsonObj->to = $data['key_remote_id'];
            $jsonObj->name = "aprovacao_campanha_v4";
            $jsonObj->namespace = "64772370_c5db_4c4e_8e20_bebe04cb8268";
            $jsonObj->language = "pt_BR";
            $jsonObj->policy = "deterministic";

            $bodyComponentObj = new stdClass();
            $bodyComponentObj->type = "body";

            $bodyParametersChannelObj = new stdClass();
            $bodyParametersChannelObj->type = "text";
            $bodyParametersChannelObj->text = $data['channels_names'];

            $bodyParametersUserObj = new stdClass();
            $bodyParametersUserObj->type = "text";
            $bodyParametersUserObj->text = $data['submitted_by_user'];

            $bodyParametersTextObj = new stdClass();
            $bodyParametersTextObj->type = "text";
            $bodyParametersTextObj->text = '_' . trim(ltrim($data['approval_message'])) . '_';

            $bodyComponentObj->parameters = [$bodyParametersChannelObj, $bodyParametersUserObj, $bodyParametersTextObj];

            $buttonComponentObj = new stdClass();
            $buttonComponentObj->type = "button";
            $buttonComponentObj->index = "0";
            $buttonComponentObj->sub_type = "url";

            $buttonParametersObj = new stdClass();
            $buttonParametersObj->type = "text";
            $buttonParametersObj->text = $data['approval_url'] . $data['url_params']['company_token'] . '/' . $data['url_params']['token_broadcast_schedule'] . '/' . $maneger['token_approval'];

            $buttonComponentObj->parameters = [$buttonParametersObj];

            $jsonObj->component = [$bodyComponentObj, $buttonComponentObj];

            $data['json'] = json_encode($jsonObj);

            saveJsonPending($data);

            $data['token_approval'] = $maneger['token_approval'];
            saveJsonContact($data);

            sendMessage($data);
        }
    }
}

function sendMessage($data)
{

    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://services.talkall.com.br:4000/apiNotify/554333753133',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $data['json'],
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
}

function getURLParams($data)
{
    $res = [];

    $res['company_token'] = getCompanyToken()['token'];
    $res['token_broadcast_schedule'] = getTokenBroadcastSchedule($data)['token_broadcast_schedule'];
    $res['contact_data'] = tokenApproval($data);

    return $res;
}

function saveJsonPending($data)
{
    $ci = &get_instance();
    $ci->load->database();

    $date = new DateTime();

    $values = [
        'creation' => $date->getTimestamp(),
        'key_id' => $data['key_id'],
        'key_remote_id' => $data['key_remote_id'],
        'json' => $data['json'],
    ];

    $ci->db = $ci->load->database(Setdatabase("db1"), TRUE);
    $ret = $ci->db->insert('json_pending', $values);
}

function saveJsonContact($data)
{
    $ci = &get_instance();
    $ci->load->database();

    $values = [
        'json_message' => $data['json']
    ];

    $ci->db = $ci->load->database(Setdatabase($_SESSION['db']), TRUE);
    $ci->db->where('token_approval', $data['token_approval']);
    $ret = $ci->db->update('broadcast_approval_contact', $values);
}

function getCompanyToken()
{
    $ci = &get_instance();
    $ci->load->database();

    $sql = "SELECT company.token FROM talkall_admin.company where company.id_company = '" . $_SESSION['id_company'] . "'";

    $ci->db = $ci->load->database(Setdatabase("talkall_admin"), TRUE);
    $res = $ci->db->query($sql);

    return $res->result_array()[0];
}

function getTokenBroadcastSchedule($data)
{
    $ci = &get_instance();
    $ci->load->database();

    $sql = "SELECT 
                broadcast_approval.token_broadcast_schedule
            FROM
                broadcast_approval
            WHERE
                broadcast_approval.id_approval = '" . $data['id_approval'] . "'";

    $ci->db = $ci->load->database(Setdatabase($_SESSION['db']), TRUE);
    $res = $ci->db->query($sql);

    return $res->result_array()[0];
}

function tokenApproval($data)
{
    $ci = &get_instance();
    $ci->load->database();

    $sql = "SELECT 
                broadcast_approval_contact.token_approval,
                broadcast_approval_contact.key_remote_id
            FROM
                broadcast_approval_contact
            WHERE
                broadcast_approval_contact.id_approval = '" . $data['id_approval'] . "'";

    $ci->db = $ci->load->database(Setdatabase($_SESSION['db']), TRUE);
    $res = $ci->db->query($sql);

    return $res->result_array();
}
