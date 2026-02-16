<?php
defined('BASEPATH') or exit('No direct script access allowed');

function CreateUserLog(string $db, string $host, string $action, int $id_user)
{
    $ci = &get_instance();
    $ci->load->database();

    $date = new DateTime();
    $values = [
        'id_user' => $id_user,
        'creation' => $date->getTimestamp(),
        'system' => $ci->agent->platform(),
        'text' => $action,
        'ip' => $ci->input->ip_address(),
        'agent' => $ci->agent->browser(),
        'version' => $ci->agent->version()
    ];
    $ci->db = $ci->load->database(SetdatabaseRemote($db, $host), TRUE);
    $ci->db->insert('user_log', $values);
}

function CreateBroadcastLog(int $key_id = NULL, string $type, $json = NULL)
{
    $date = new DateTime();

    $ci = &get_instance();
    $ci->load->database();
    $ci->load->library('session');
    $ci->load->library('user_agent');

    $key_remote_id = $ci->session->userdata('key_remote_id_login_support') ?? $ci->session->userdata('key_remote_id');
    $db = $ci->session->userdata('db');
    $host = $ci->session->userdata('host');

    $values = [
        'id_broadcast_schedule' => $key_id,
        'creation' => $date->getTimestamp(),
        'key_remote_id' => $key_remote_id,
        'type' => $type,
        'log' => $json
    ];

    $ci->db = $ci->load->database(SetdatabaseRemote($db, $host), TRUE);
    $ci->db->insert('broadcast_schedule_log', $values);
}

function CreateWebSessionToken($user)
{
    $ci = &get_instance();
    $ci->load->database();

    $date = new DateTime();

    $values = [
        'creation' => $date->getTimestamp(),
        'key_remote_id' => $user['key_remote_id'],
        'browser_token' => $user['WebSessionToken'],
        'ip' => $ci->input->ip_address(),
        'os' => $ci->agent->browser(),
        'app_version' => $ci->config->item('application_version')
    ];

    $ci->db = $ci->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), TRUE);

    $ci->db->where('key_remote_id', $user['key_remote_id']);
    $ci->db->delete('web_session');

    $ci->db->insert('web_session', $values);
}