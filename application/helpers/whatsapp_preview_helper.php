<?php

defined('BASEPATH') or exit('No direct script access allowed');

function getChannelPreview($data)
{
    $ci = &get_instance();
    $ci->load->database();
    $session = $ci->session->all_userdata();

    $sql = "SELECT channel.id FROM channel WHERE channel.id_channel = '" . $data['id_channel'] . "'";
    $ci->db = $ci->load->database(SetdatabaseRemote($session['db'], $session['host']), TRUE);

    $res = $ci->db->query($sql);
    $result = $res->result_array()[0]['id'];

    return $result;
}


function validContactChannel($data)
{
    $contactExist = validateContact($data);
    return $contactExist;
}


function validateContact($contact)
{
    $isNinthDigit = strlen($contact['key_remote_id']);
    if ($isNinthDigit == "12") {
        $checkedContact = checkContactWithEightDigits($contact);
        return $checkedContact;
    }

    if ($isNinthDigit == "13") {
        $checkedContact = checkContactWithNinthDigit($contact);
        return $checkedContact;
    }

    if ($isNinthDigit < 12 || $isNinthDigit > 13) {
        return false;
    }
}


function checkContactWithEightDigits($contact)
{
    $numberFone = consultContact($contact);
    if ($numberFone != false) {
        $contact['contact'] = $numberFone;
        return $contact;
    }
    if ($numberFone == false) {
        $addNinthDigit = substr_replace($contact['key_remote_id'], '9', 4, 0);
        $contact['key_remote_id'] = $addNinthDigit;
        $numberFone = consultContact($contact);
        if ($numberFone != false) {
            $contact['contact'] = $numberFone;
            return $contact;
        } else {
            return false;
        }
    }
}


function consultContact($contact)
{
    $ci = &get_instance();
    $ci->load->database();
    $session = $ci->session->all_userdata();

    $numberFone = $contact['key_remote_id'] . "-" . $contact['id_channel'];
    $sql = "SELECT contact.key_remote_id, full_name, email FROM contact WHERE contact.key_remote_id = '" . $numberFone . "' and deleted = 1";

    $ci->db = $ci->load->database(SetdatabaseRemote($session['db'], $session['host']), TRUE);
    $res = $ci->db->query($sql);

    $result = $res->result_array();
    $resp = count(empty($result) ? [] : [1]);

    if ($resp == 1) {
        return $result[0];
    } else {
        return false;
    }
}


function checkContactWithNinthDigit($contact)
{
    $numberFone = consultContact($contact);
    if ($numberFone != false) {
        $contact['contact'] = $numberFone;
        return $contact;
    }
    if ($numberFone == false) {
        $startNumberFone = substr($contact['key_remote_id'], -13, 4);
        $endNumberFone =  substr($contact['key_remote_id'], -8);
        $removeNinthDigit = $startNumberFone . $endNumberFone;
        $contact['key_remote_id'] = $removeNinthDigit;
        $numberFone = consultContact($contact);
        if ($numberFone != false) {
            $contact['contact'] = $numberFone;
            return $contact;
        } else {
            return false;
        }
    }
}


function sendCampaignPreview($data)
{
    $ci = &get_instance();
    $ci->load->database();
    $session = $ci->session->all_userdata();

    $date = new DateTime();
    $creation = $date->getTimestamp();
    $key_remote_id = $data['id_channel'];
    $key_id = json_decode($data['json'])->ta_key_id;
    $json = $data['json'];

    $query = "INSERT INTO json_pending (creation, key_remote_id, key_id, json) VALUES ('$creation', '$key_remote_id', '$key_id', '$json')";

    $ci->db = $ci->load->database(SetdatabaseRemote($session['db'], $session['host']), TRUE);
    $res = $ci->db->query($query);

    if ($res) {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://services.talkall.com.br:4000/apiNotify/' .  $data['id_channel'] . '',
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
    } else {
        return false;
    }
}
