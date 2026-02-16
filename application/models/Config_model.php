<?php

class Config_model extends TA_model
{
    public function __construct()
    {
        parent::__construct();
    }


    function WaChannelConfig($id)
    {
        $this->db->select('
        channel.id_channel,
        channel.id,
        channel.pw,
        config.id_config,
        channel.minimum_credit,
        config.webhook,
        channel.id_user_group,
        channel.id_work_time,
        channel.name,
        channel.type,
        config.office_hours_end,
        config.timezone,
        config.welcome,
        config.welcome_message,
        config.automatic_message,
        config.attendant_name_enable,
        config.ai_evaluation,
        config.ai_options,
        config.template_wa_business_contains_broadcast,
        config.template_wa_business_no_contains_broadcast,
        config.automatic_transfer,
        config.opt_out_key,
        config.opt_out_message,
        channel.credit_conversation,
        config.return_to_channel_message,
        config.address,
        config.picture,
        config.email,
        config.social_media,
        config.attendance_enable,
        config.chatbot_enable,
        config.company_description,
        config.message_start_attendance,
        config.enable_protocol,
        config.transfer_message,
        config.automatic_transfer_minute,
        channel.is_broadcast,
         channel.integration_type
    ');
        $this->db->from('channel');
        $this->db->join('config', 'channel.id_channel = config.id_channel');
        $this->db->where('channel.id_channel', $id);

        return $this->db->get()->result_array();
    }


    function UpadateWaConfig($data, $messageContainsBroadcast, $messageNoContainsBroadcast)
    {
        if (!empty($data['to_recover_id_user_group'])) {

            if ($data['id_user_group'] != $data['to_recover_id_user_group']) {
                $this->db->set("id_user_group", $data['id_user_group']);
                $this->db->where('id_channel', $data['id_channel']);
                $this->db->where('id_user_group', $data['to_recover_id_user_group']);
                $this->db->update('contact');
            }
        }

        if ($data["is_broadcast"] == 2) {
            $checkbox_on_off_attendance = 1;

            if ($data["type"] == 12 || $data["type"] == 16 || $data["type"] == 2) {
                $this->db->set('name', trim($data['channel_name']));
            }

            $this->db->set('id_user_group', $data['id_user_group']);
            $this->db->set('id_work_time', $data['id_work_time'] == 0 ? null : $data['id_work_time']);
            $this->db->where('id_channel', $data['id_channel']);
            $this->db->update('channel');
            $this->load->helper('date');

            $this->db->set('welcome', trim($data['welcome_message']) == "" ? null : trim($data['welcome_message']));
            $this->db->set('office_hours_end', trim($data['office_hours_end']));
            $this->db->set('message_start_attendance', trim($data['message_start_attendance']));
            $this->db->set('transfer_message', trim($data['transfer_message']));
            $this->db->set('automatic_transfer_minute', $data['automatic_transfer_minute']);
            $this->db->set('chatbot_enable', isset($data['checkbox-on-off-chatbot']) ? $data['checkbox-on-off-chatbot'] : 2);
            $this->db->set('enable_protocol', isset($data['checkbox-on-off-protocol']) ? $data['checkbox-on-off-protocol'] : 2);
            $this->db->set('automatic_transfer', isset($data['checkbox-on-off-automaticTransfer']) ? $data['checkbox-on-off-automaticTransfer'] : 0);
            $this->db->set('attendant_name_enable', isset($data['checkbox-on-off-attendantName']) ? $data['checkbox-on-off-attendantName'] : 0);

            if ($this->checkAiChannel() && isset($data['checkbox-on-off-aiEvaluation']) && $data['ai_options'] != "") {
                $this->db->set('ai_evaluation', $data['checkbox-on-off-aiEvaluation']);
                $this->db->set('ai_options', ($data['ai_options']));
            } else {
                $this->db->set('ai_evaluation', 0);
                $this->db->set('ai_options', null);
            }

            if (isset($data['checkbox-on-off-attendance'])) {
                $this->db->set('attendance_enable', $checkbox_on_off_attendance);
            }

            if ($data["type"] == 12 || $data["type"] == 16) {

                if ($data['url_picture'] != "NULL") {
                    $this->db->set("picture", $data['url_picture']);
                }

                if (empty($data['input-website-social']) && empty($data['input-website-social-second'])) {
                    $this->db->set("social_media", NULL);
                } else {
                    $this->db->set("social_media", $data['input-website-social'] . "," .  $data['input-website-social-second']);
                }

                $this->db->set("email", $data['input-email']);
                $this->db->set("address", $data['input-address']);
                $this->db->set("company_description", trim($data['textarea-description']));
            }
        } else if ($data["is_broadcast"] == 1) {
            if ($data['channel_name']) {
                $this->db->set('name', trim($data['channel_name']));
                $this->db->set('id_work_time', $data['id_work_time'] == 0 ? null : ($data['id_work_time'] == 'none' ? null : $data['id_work_time']));
                $this->db->where('id_channel', $data['id_channel']);
                $this->db->update('channel');
            }

            $this->db->set('welcome', trim($data['welcome_message']) == "" ? null : trim($data['welcome_message']));

            if ($data['integration_type'] == 2) {
            if (empty($data['template_wa_business_contains_broadcast'])) {
                $this->db->set('template_wa_business_contains_broadcast', $messageContainsBroadcast);
            } else {
                $this->db->set('template_wa_business_contains_broadcast', $data['template_wa_business_contains_broadcast']);
            }

            if (empty($data['template_wa_business_no_contains_broadcast'])) {
                $this->db->set('template_wa_business_no_contains_broadcast', $messageNoContainsBroadcast);
            } else {
                $this->db->set('template_wa_business_no_contains_broadcast', $data['template_wa_business_no_contains_broadcast']);
            }

            $this->db->set('automatic_message', $data['automatic_message']);
            $this->db->set('opt_out_key', $data['opt_out_key']);
            $this->db->set('opt_out_message', trim($data['opt_out_message']));
            $this->db->set('return_to_channel_message', $data['return_to_channel_message']);
            }
        };

        $this->db->where('id_channel', $data['id_channel']);
        $this->db->update('config');
    }

    function isInstagram($pw)
    {
        $this->db->select('*');
        $this->db->from('channel');
        $this->db->where('channel.status', 1);
        $this->db->where('channel.pw', $pw);

        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            return false;
        } else {
            return true;
        }
    }

    function ValidationProtocol($data)
    {

        if ($data['checkbox-on-off-protocol'] == 1) {

            if ($data["message_start_attendance"] != "") {

                return "ok";
            } else {

                return 'protocolo';
            }
        } else {

            return "ok";
        }
    }

    function ValidationChatBot($data)
    {

        if ($data['checkbox-on-off-chatbot'] == 1) {
            $response = $this->CheckIsWelcome();
            if ($data["welcome_message"] != "" && $response == "ok") {

                return "ok";
            } else {

                return 'chatbot';
            }
        } else {

            return "ok";
        }
    }

    function ValidationAutomaticTransferTime($data)
    {
        if ($data['checkbox-on-off-automaticTransfer'] == 1) {

            if ($data["automatic_transfer_minute"] != "" && $data['automatic_transfer_minute'] != "0") {

                return "ok";
            } else {

                return 'transferência automática';
            }
        } else {

            return "ok";
        }
    }

    function checkIsWelcome()
    {
        $this->db->select('*');
        $this->db->from('chatbot');

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return "ok";
        } else {
            return "Você precisa cadastrar a mensagem de Boas Vindas!";
        }
    }

    function getChannelInfo($id)
    {
        $this->db->select('*');
        $this->db->from('channel');
        $this->db->where('channel.id_channel', $id);

        return $this->db->get()->result_array();
    }

    function updateAccountTelegram($data)
    {
        $this->db->trans_start();

        $value_to_channel = [
            'name' => $data['channel_name'],
            'id_user_group' => $data['id_user_group'],
            'id_work_time' => $data['id_work_time'],
        ];
        $this->db->where('id_channel', $data['id_channel']);
        $this->db->update('channel', $value_to_channel);

        $value_to_config = [
            'picture' => $data['url_picture'],
            'welcome' => $data['welcome_message'],
            'message_start_attendance' => $data['message_start_attendance'],
            'transfer_message' => $data['transfer_message'],
            'automatic_transfer_minute' => $data['automatic_transfer_minute'],
            'chatbot_enable' => $data['checkbox-on-off-chatbot'],
            'enable_protocol' => $data['checkbox-on-off-protocol'],
            'automatic_transfer' => $data['checkbox-on-off-automaticTransfer'],
            'attendant_name_enable' => $data['checkbox-on-off-attendantName'],
            'office_hours_end' => $data['office_hours_end'],
        ];
        $this->db->where('id_channel', $data['id_channel']);
        $this->db->update('config', $value_to_config);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ["response" =>  "PAD-002"];
        } else {
            $this->db->trans_complete();
            return ["response" =>  "success"];
        }
    }

    function updateTelegramChannelName($data)
    {
        $get_channel_credential = $this->getChannelCredential($data['id_channel']);

        if (is_array($get_channel_credential) && $get_channel_credential['response'] == 'no_credential') {
            return ['response' => 'TA-043'];
        }

        $data['credential'] = $get_channel_credential;

        $update_telegram_api = $this->updateTelegramApi($data);

        $update_telegram_api = json_decode($update_telegram_api, true);

        if ($update_telegram_api['ok'] != true) {
            return ['response' => 'TA-044'];
        }

        $this->db->trans_start();

        $value_to_channel = [
            'name' => $data['channel_name'],
        ];
        $this->db->where('id_channel', $data['id_channel']);
        $this->db->update('channel', $value_to_channel);

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return ["response" =>  "PAD-002"];
        } else {
            $this->db->trans_complete();
            return ["response" =>  "success"];
        }
    }

    function getChannelCredential($id)
    {
        $this->db->select('pw');
        $this->db->from('channel');
        $this->db->where('channel.id_channel', $id);

        $pw = $this->db->get()->result_array()[0]['pw'] ?? '';

        $this->db->select('id');
        $this->db->from('channel');
        $this->db->where('channel.pw', $pw);
        $this->db->where('channel.type', 10);

        $channel_id = $this->db->get()->result_array()[0]['id'] ?? '';

        if ($pw == '' || $channel_id == '') {
            return ['response' => 'no_credential'];
        }

        return $channel_id . ':' . $pw;
    }

    function updateTelegramApi($data)
    {
        $url = "https://api.telegram.org/bot" . $data['credential'] . "/setChatTitle?chat_id=" . $data['channel_number'] . "&title=" . $data['channel_name'];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    function checkAiChannel()
    {
        $this->db->select('channel.id_channel');
        $this->db->from('channel');
        $this->db->join('config', 'channel.id_channel = config.id_channel', 'inner');
        $this->db->where('channel.type', 30);
        $this->db->where('channel.status', 1);

        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            return true;
        }

        return false;
    }
}
