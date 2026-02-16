<?php

defined('BASEPATH') or exit('No direct script access allowed');

function CancelCampaing($token)
{
    $ci = &get_instance();
    $ci->load->database();
    $session = $ci->session->all_userdata();
    $ci->db = $ci->load->database(SetdatabaseRemote($session['db'], $session['host']), TRUE);

    $ids_schedule = GetCampaignByToken($ci, $token);

    if (empty($ids_schedule)) {
        return ["errors" => ["code" => "TA-027"]];
    }

    if (is_array($ids_schedule) == false) {
        $ids_schedule = [$ids_schedule];
    }

    CancelCampaignSchedule($ci, $ids_schedule);
    DeleteCampaignBroadcastSend($ci, $ids_schedule);

    return ["success" => ["status" => true]];
}

function GetCampaignByToken($client, $token)
{
    $client->db->select("broadcast_schedule.id_broadcast_schedule");
    $client->db->from("broadcast_schedule");

    if (is_array($token))
        $client->db->where_in("broadcast_schedule.token", $token["tokens"]);
    else
        $client->db->where_in("broadcast_schedule.token", $token);

    $result = $client->db->get()->result_array();

    return array_column($result, 'id_broadcast_schedule');
}

function CancelCampaignSchedule($client, $ids_schedule)
{
    foreach ($ids_schedule as $value) {
        $client->db->where("id_broadcast_schedule", $value);
        $client->db->update("broadcast_schedule", array("status" => 5));
    }
}

function DeleteBroadcastParticipants($client, $ids_schedule)
{
    foreach ($ids_schedule as $value) {
        $client->db->where("id_broadcast_schedule", $value);
        $client->db->delete("broadcast_schedule_participants");
    }
}

function DeleteCampaignBroadcastSend($client, $ids_schedule)
{
    foreach ($ids_schedule as $value) {
        $client->db->where("id_broadcast_schedule", $value);
        $client->db->delete("broadcast_send");
    }
}
