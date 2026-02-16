<?php

class Integration_model extends TA_model
{
    public function __construct()
    {
        parent::__construct();
        $this->SetTableName("channel");
        $this->SetPrimaryKey("id_channel");
    }

    function Get()
    {
        $this->db->select("
                        channel.id_channel,
                        REPLACE(channel.id, '@c.us', '') as id,
                        channel.type,
                        channel.name,
                        channel.display_phone_number,
                        channel.credit_conversation,
                        channel.credit_template,
                        channel.pricing_template,
                        channel.status,
                        (SELECT COUNT(*) FROM channel WHERE type = 12 AND status != 2) as countBusinessAPI,
                        (SELECT COUNT(*) FROM channel WHERE type = 16 AND status != 2) as countCloudAPI,
                        (SELECT EXISTS(SELECT 1 FROM channel WHERE type = 30 AND status = 1)) as hasOpenAi,
                        (SELECT COUNT(*) FROM contact WHERE contact.id_channel = channel.id_channel AND contact.deleted = 1) as contacts
        ");

        $this->db->from('channel');
        $this->db->where('channel.status !=', 2);
        $this->db->where_in('channel.type', array(2, 6, 8, 9, 10, 12, 16, 28, 30, 31));
        $this->db->order_by("FIELD(channel.type, 3, 6, 10,31, 30, 28, 8, 9, 12, 16, 2)", null, false);
        $this->db->order_by("channel.name REGEXP '^[0-9]'", 'ASC');
        $this->db->order_by("CAST(SUBSTRING_INDEX(channel.name, ' ', 1) AS UNSIGNED)", null, false);
        $this->db->order_by('channel.name', 'ASC');

        $query = $this->db->get();
        $data = array();

        foreach ($query->result_array() as $row) {
            $type = $row['type'];

            $data[] = array(
                'id_channel' => $row['id_channel'],
                'id' => $row['id'],
                'type' => $row['type'],
                'name' => $row['name'],
                'display_phone_number' => $row['display_phone_number'],
                'status' => $row['status'],
                'countCloudAPI'   => (int)$row['countCloudAPI'],
                'countBusinessAPI' => (int)$row['countBusinessAPI'],
                'hasOpenAi' => ($row['hasOpenAi'] == 1),
                'contacts' => (int)$row['contacts'],
            );

            foreach ($data as $index => $item) {
                if ($item['id'] == 'tv-') {
                    $data[$index]['id'] .= $index;
                }
            }

            if ($type == 6) {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "http://sms-talkall.brazilsouth.cloudapp.azure.com/api/v1/get-credits",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "access_token: {$row['id']}",
                        "Authorization: Bearer {{token}}"
                    ),
                ));

                $response = curl_exec($curl);
                $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                curl_close($curl);

                $data[count($data) - 1]['balance_sms'] = 0;
                if ($status_code == 200) {
                    $data_res = json_decode($response, true);
                    $data[count($data) - 1]['balance_sms'] = $data_res['credits'];
                }
            }
        }

        return $data;
    }

    function GetById($id)
    {
        $this->db->select('*');
        $this->db->from('channel');
        $this->db->join('config', 'channel.id_channel = config.id_channel', 'left');
        $this->db->where('channel.id_channel', $id);
        $this->db->where_in('channel.type', array(3, 28));
        $this->db->where('channel.status', 1);
        return $this->db->get()->result_array()[0];
    }

    function getChannelId($id_channel)
    {
        $this->db->select('channel.id');
        $this->db->from('channel');
        $this->db->where('channel.id_channel', $id_channel);

        return $this->db->get()->result_array()[0]['id'];
    }

    function getPageFacebook($id_channel = "")
    {
        $this->db->select('
            channel.name,
            channel.id_channel,
            channel.id_user_group,
            channel.id_work_time,
            channel.type,
            channel.id,
            channel.pw,
            config.picture,
            channel.status
        ', false);

        $this->db->from('channel');
        $this->db->join('config', 'channel.id_channel = config.id_channel', 'inner');
        $this->db->where('channel.status !=', 2);
        $this->db->where('channel.type', 8);

        if (!empty($id_channel)) {
            $this->db->where('channel.id_channel', $id_channel);
        }

        return $this->db->get()->result_array();
    }

    function getPageInstagram($id_channel = "")
    {
        $this->db->select('
            channel.name,
            channel.id_channel,
            channel.id_user_group,
            channel.id_work_time,
            channel.type,
            channel.id,
            channel.pw,
            config.picture,
            channel.status
        ', false);

        $this->db->from('channel');
        $this->db->join('config', 'channel.id_channel = config.id_channel', 'inner');
        $this->db->where('channel.status !=', 2);
        $this->db->where('channel.type', 9);

        if (!empty($id_channel)) {
            $this->db->where('channel.id_channel', $id_channel);
        }

        return $this->db->get()->result_array();
    }

    function GetBroadcastSchedule($id_channel)
    {
        $today_start = strtotime('today');

        $this->db->select('broadcast_schedule.id_broadcast_schedule');
        $this->db->from('broadcast_schedule');
        $this->db->where_in('broadcast_schedule.status', [1, 3, 6]);
        $this->db->where('broadcast_schedule.id_channel', $id_channel);
        $this->db->where('broadcast_schedule.schedule <', $today_start);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $ids = array_column($query->result_array(), 'id_broadcast_schedule');
            return $ids;
        }
    }

    function PageDelete($id_page)
    {
        $this->db->select('*');
        $this->db->from('channel');
        $this->db->where('channel.status', 1);
        $this->db->where('channel.id', $id_page);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            $this->db->select('*');
            $this->db->from('channel');
            $this->db->where('channel.status', 1);
            $this->db->where('channel.pw', $query->result_array()[0]['pw']);

            $query = $this->db->get();

            if ($query->num_rows() > 0) {

                foreach ($query->result_array() as $row) {

                    $this->db->where('id_channel', $row['id_channel']);
                    $this->db->update('channel', array('status' => 2));

                    $this->db->where('id_channel', $row['id_channel']);
                    $this->db->update('contact', array('deleted' => 2));

                    $this->db->where('id_channel', $row['id_channel']);
                    $this->db->delete('config');

                    $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
                    $this->talkall_admin->where('id', $row['id']);
                    $this->talkall_admin->where('id_company', $this->session->userdata('id_company'));
                    $this->talkall_admin->delete('channel');
                }
            }
        }
    }

    function QueryWidget($key_id = 0)
    {
        $this->db->select('*');
        $this->db->from('channel');
        $this->db->where('channel.id_channel', $key_id);
        $this->db->where('channel.type', 3);
        $this->db->where('channel.status', 1);

        return $this->db->get()->result_array();
    }

    function SaveWidget($data)
    {
        if ($data['id_channel'] == '0') {

            $widget_token = Token();

            $date = new DateTime();

            $values = [
                'name' => $data['name'],
                'type' => 3,
                'id' => $widget_token,
                'status' => 1,
                'button_text' => $data['button'],
                'button_color' => $data['color'],
                'title' => $data['title'],
                'subtitle' => $data['subtitle'],
                'position' => $data['position']
            ];

            $this->db->insert('channel', $values);

            $channel_id = $this->db->insert_id();

            $values = [
                'id_channel' => $channel_id,
                'timezone' => '-00:00',
                'welcome' => '',
            ];

            $this->db->insert('config', $values);

            $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);

            $values = [
                'id' => $widget_token,
                'creation' => $date->getTimestamp(),
                'id_company' => $this->session->userdata('id_company'),
                'type' => 3,
                'status' => 1,
                'executed' => 1
            ];

            $this->talkall_admin->insert('channel', $values);

            $this->db->select('id_channel');
            $this->db->from('channel');
            $this->db->where('id', $widget_token);

            $query = $this->db->get();

            if ($query->num_rows() > 0) {
                return $query->result_array()[0];
            }
        } else {

            $values = [
                'name' => $data['name'],
                'button_text' => $data['button'],
                'button_color' => $data['color'],
                'title' => $data['title'],
                'subtitle' => $data['subtitle'],
                'position' => $data['position']
            ];

            $this->db->where('id_channel', $data['id_channel']);
            $this->db->update('channel', $values);
        }
    }

    function SaveTv($data)
    {
        $tv_token = 'tv-';

        $date = new DateTime();

        $values = [
            'name' => $data['name'],
            'type' => 28,
            'id' => $tv_token,
            'status' => 1,
            'tv_connection_code' =>  $data['connection_code']
        ];

        $this->db->insert('channel', $values);

        $channel_id = $this->db->insert_id();

        $values = [
            'id_channel' => $channel_id,
            'timezone' => '-03:00',
            'welcome' => '',
            'tv_settings' => $data['tv_settings']
        ];

        $this->db->insert('config', $values);

        $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);

        $values = [
            'id' => $tv_token,
            'creation' => $date->getTimestamp(),
            'id_company' => $this->session->userdata('id_company'),
            'type' => 28,
            'status' => 1,
            'executed' => 1,
            'tv_connection_code' =>  $data['connection_code']
        ];

        $this->talkall_admin->insert('channel', $values);

        $this->db->select('channel.tv_connection_code');
        $this->db->from('channel');
        $this->db->where('tv_connection_code', $data['connection_code']);

        $result = $this->db->get()->row_array();

        if (!empty($result)) {
            return $result['tv_connection_code'];
        }
    }

    function EditTv($data)
    {
        $this->db->where('id_channel', $data['id_channel']);
        $this->db->update('channel', array('name' => $data['name']));

        $this->db->where('id_channel', $data['id_channel']);
        $this->db->update('config', array('tv_settings' => $data['tv_settings']));

        $this->db->select('channel.id, channel.name, config.tv_settings');
        $this->db->from('channel');
        $this->db->join('config', 'channel.id_channel = config.id_channel');
        $this->db->where('channel.id_channel', $data['id_channel']);
        $result = $this->db->get()->row_array();

        if (!empty($result)) {

            $postData = array(
                'Cmd' => 'UpdateTv',
                'status' => 200,
                'id' => $result['id'],
                'name' => $result['name'],
                'tv_settings' => $result['tv_settings']
            );

            return $postData;
        }
    }

    function DeleteTv($data)
    {
        $this->db->select('*');
        $this->db->from('channel');
        $this->db->where('status', 1);
        $this->db->where('type', 28);

        if ($data['connection_code']) {
            $this->db->where('tv_connection_code', $data['connection_code']);
        } else {
            $this->db->where('id_channel', $data);
        }

        $result = $this->db->get()->row_array();

        if (!empty($result)) {

            $this->db->trans_begin();

            try {
                $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);

                $this->talkall_admin->where('status', 1);
                $this->talkall_admin->where('type', 28);
                $this->talkall_admin->where('tv_connection_code', $result['tv_connection_code']);
                $this->talkall_admin->delete('channel');

                if ($this->talkall_admin->trans_status() === FALSE) {
                    throw new Exception("Failed to delete");
                }

                $this->db->where('id_channel', $result['id_channel']);
                $this->db->delete('config');

                if ($this->db->trans_status() === FALSE) {
                    throw new Exception("Failed to delete");
                }

                // Se não há tv conectada, deleta o registro
                if (strlen($result['id']) <= 7) {
                    $this->db->where('id_channel', $result['id_channel']);
                    $this->db->delete('channel');

                    if ($this->db->trans_status() === FALSE) {
                        throw new Exception("Failed to delete");
                    }
                } else {
                    // tv já está conectada, desativa
                    $this->db->set('status', 2);
                    $this->db->where('id_channel', $result['id_channel']);
                    $this->db->update('channel');

                    if ($this->db->trans_status() === FALSE) {
                        throw new Exception("Update failure");
                    }
                }

                $this->db->trans_commit();

                $postData = array(
                    'Cmd' => 'disconnect',
                    'status' => 200,
                    'id' => $result['id'],
                );

                return ($postData);
            } catch (Exception $e) {
                $this->db->trans_rollback();

                return ["error" => [
                    "code" => "PAD-002",
                    "title" => "Transaction failure",
                    "message" => $e->getMessage(),
                ]];
            }
        }
    }

    function ClearChannel($id_channel)
    {
        $channel_number = $this->getChannelId($id_channel);
        $id_broadcast_schedule = $this->GetBroadcastSchedule($id_channel);

        if (!empty($channel_number)) {
            $this->db->trans_start();

            $this->db->where("key_remote_id", $channel_number);
            $this->db->delete("json_pending");

            if (!empty($id_broadcast_schedule)) {
                $this->db->set("status", 5);
                $this->db->where_in("id_broadcast_schedule", $id_broadcast_schedule);
                $this->db->update("broadcast_schedule");

                $this->db->set("status", 2);
                $this->db->where_in("id_broadcast_schedule", $id_broadcast_schedule);
                $this->db->update("broadcast_send");
            }

            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return ["errors" => ["code" => "PAD-002"]];
            } else {
                $this->db->trans_complete();
                return ["success" => ["status" => true]];
            }
        }
    }

    function SaveOpenAi($data)
    {
        $date = new DateTime();
        $id = Token();

        $this->db->trans_start();

        $values = [
            'name' => "OpenAI - ChatGPT",
            'type' => 30,
            'id' => $id,
            'pw' => $data['token'],
        ];

        $this->db->insert('channel', $values);

        $channel_id = $this->db->insert_id();

        $values = [
            'id_channel' => $channel_id,
            'timezone' => '-03:00',
            'welcome' => '',
            'office_hours_end' => '',
            'attendance_enable' => 2,
        ];

        $this->db->insert('config', $values);

        $values = [
            'type' => 30,
            'creation' => $date->getTimestamp(),
            'id_company' => $this->session->userdata('id_company'),
            'id' => $id,
            'status' => 1,
            'pw' => $data['token'],
            'gpt_version' => $data['version'],
        ];

        $this->talkall_admin = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), TRUE);
        $this->talkall_admin->trans_start();
        $this->talkall_admin->insert('channel', $values);

        if ($this->db->trans_status() === FALSE || $this->talkall_admin->trans_status() === FALSE) {
            $this->db->trans_rollback();
            $this->talkall_admin->trans_rollback();
            return ["errors" => ["code" => "PAD-002"]];
        } else {
            $this->db->trans_complete();
            $this->talkall_admin->trans_complete();
            return ["success" => ["status" => true]];
        }
    }

    function DeleteOpenAi($id)
    {
        $this->db->select('channel.id');
        $this->db->from('channel');
        $this->db->where('status', 1);
        $this->db->where('type', 30);
        $this->db->where('id_channel', $id);

        $channel = $this->db->get()->row_array();

        if (!empty($channel)) {

            $this->db->trans_start();

            $this->db->where('id_channel', $id);
            $this->db->update('channel', array('status' => 2));

            $this->db->where('id_channel', $id);
            $this->db->delete('config');

            $this->talkall_admin = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), TRUE);
            $this->talkall_admin->trans_start();

            $this->talkall_admin->where('id', $channel['id']);
            $this->talkall_admin->delete('channel');

            if ($this->db->trans_status() === FALSE || $this->talkall_admin->trans_status() === FALSE) {
                $this->db->trans_rollback();
                $this->talkall_admin->trans_rollback();
                return ["errors" => ["code" => "PAD-002"]];
            } else {
                $this->db->trans_complete();
                $this->talkall_admin->trans_complete();
                return ["success" => ["status" => true]];
            }
        }
    }

    //! -- Funções sem uso:
    function Add($data)
    {
        $values = [
            'name' => $data['input-name'],
            'color' => $data['input-color'],
        ];

        $this->db->insert('label', $values);
    }

    function Widget()
    {
        $this->db->select('channel.id');
        $this->db->from('channel');
        $this->db->where('channel.type', 3);
        $this->db->where('channel.status', 1);
        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {

            return $query->result_array()[0]['id'];
        } else {

            $widget_token = Token();

            $date = new DateTime();

            $values = [
                'name' => 'Widget',
                'type' => 3,
                'id' => $widget_token,
                'status' => 1,
            ];

            $this->db->insert('channel', $values);

            $channel_id =  $this->db->insert_id();

            $values = [
                'id_channel' => $channel_id,
                'timezone' => '-00:00',
                'welcome' => '',
            ];

            $this->db->insert('config', $values);

            $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);

            $values = [
                'id' => $widget_token,
                'creation' => $date->getTimestamp(),
                'id_company' => $this->session->userdata('id_company'),
                'type' => 3,
                'status' => 1,
                'executed' => 1
            ];

            $this->talkall_admin->insert('channel', $values);

            return $widget_token;
        }
    }

    function Delete($id_channel)
    {
        try {
            $this->cancelAllCampaigns($id_channel);

            $this->closeUserAttendance($id_channel);

            $this->disableContact($id_channel);

            $this->deleteQueueSend($id_channel);

            $this->deleteParticipantsCampaign($id_channel);

            $this->registerEndAttendance($id_channel);

            $this->deletePersona($id_channel);

            $this->disableChannel($id_channel);

            $this->deleteConfChannel($id_channel);

            $this->deleteChannelTalkallAdmin($id_channel);
        } catch (\Throwable $th) {
            return "erro";
        }
    }

    function analyzeChannel($id_channel)
    {
        $contact_wait_list = $this->contactWaitList($id_channel);

        if ($contact_wait_list != 0) {
            return [
                "errors" => [
                    "code" => "TA-009",
                    "message" => "Contact in wait list",
                    "detail" => $contact_wait_list
                ]
            ];
        }

        $contact_attendance = $this->contactAttendance($id_channel);

        if ($contact_attendance != 0) {
            return [
                "errors" => [
                    "code" => "TA-010",
                    "message" => "Contact in attendance",
                    "detail" => $contact_attendance
                ]
            ];
        }

        $campaign_in_progress = $this->campaignProgress($id_channel);

        if ($campaign_in_progress != 0) {
            return [
                "errors" => [
                    "code" => "TA-011",
                    "message" => "Sending campaign",
                    "detail" => $campaign_in_progress
                ]
            ];
        }
    }

    function contactWaitList($id_channel)
    {
        $account_key_remote_id = $this->getInfoChannel($id_channel)['id'];

        $this->db->select("id_wait_list");
        $this->db->from("wait_list");
        $this->db->where("account_key_remote_id", $account_key_remote_id);
        $this->db->where("status", 1);

        return $this->db->get()->num_rows();
    }

    function contactAttendance($id_channel)
    {
        $this->db->select("id_chat_list");
        $this->db->from("chat_list");
        $this->db->where("id_channel", $id_channel);
        $this->db->where("is_close", 1);
        $this->db->where("is_bot", 2);

        return $this->db->get()->num_rows();
    }

    function campaignProgress($id_channel)
    {
        $this->db->select("id_broadcast_schedule");
        $this->db->from("broadcast_schedule");
        $this->db->where("id_channel", $id_channel);
        $this->db->where("status", 6);

        return $this->db->get()->num_rows();
    }

    function cancelAllCampaigns($id_channel)
    {
        $values = [
            "status" => 5
        ];

        $this->db->where("id_channel", $id_channel);
        $this->db->update("broadcast_schedule", $values);
    }

    function closeUserAttendance($id_channel)
    {
        $values = [
            "key_remote_id" => null,
            "deleted" => 2
        ];

        $this->db->where("id_channel", $id_channel);
        $this->db->update("chat_list", $values);
    }

    function disableContact($id_channel)
    {
        $values = [
            "deleted" => 2
        ];

        $this->db->where("id_channel", $id_channel);
        $this->db->update("contact", $values);
    }

    function deleteQueueSend($id_channel)
    {

        $this->db->where("id_channel", $id_channel);
        $this->db->delete("broadcast_send");
    }

    function deleteParticipantsCampaign($id_channel)
    {
        $this->db->select("id_broadcast_schedule");
        $this->db->from("broadcast_schedule");
        $this->db->where("id_channel", $id_channel);

        $result = $this->db->get()->result_array();

        foreach ($result as $value) {
            $i = $value['id_broadcast_schedule'];

            $this->db->where("id_broadcast_schedule", $i);
            $this->db->delete("broadcast_schedule_participants");
        }
    }

    function registerEndAttendance($id_channel)
    {
        $this->db->select('id_chat_list');
        $this->db->from('chat_list');
        $this->db->where('id_channel', $id_channel);

        $response = $this->db->get();
        $last_id_chat_list = $response->result_array();

        foreach ($last_id_chat_list as $row) {
            $id_chat_list = $row['id_chat_list'];

            $this->db->set('end', 'UNIX_TIMESTAMP()', false);
            $this->db->where('id_chat_list', $id_chat_list);
            $this->db->where('end is null', null, false);
            $this->db->update('chat_list_log');
        }
    }

    function deletePersona($id_channel)
    {
        $this->db->select('id_group_contact');
        $this->db->from('group_contact');
        $this->db->where('id_channel', $id_channel);

        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            $id_persona = $result->result_array();

            foreach ($id_persona as $value) {

                $id_group_contact = $value['id_group_contact'];

                $this->db->where("id_group_contact", $id_group_contact);
                $this->db->delete("group_contact");

                $this->db->where("id_group_contact", $id_group_contact);
                $this->db->delete("contact_group");
            }
        }
    }

    function disableChannel($id_channel)
    {
        $this->db->where('id_channel', $id_channel);
        $this->db->update('channel', array('status' => 2));
    }

    function deleteConfChannel($id_channel)
    {
        $this->db->where('id_channel', $id_channel);
        $this->db->delete('config');
    }

    function deleteChannelTalkallAdmin($id_channel)
    {
        $this->db->select('id');
        $this->db->from('channel');
        $this->db->where('id_channel', $id_channel);

        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            $id = $result->result_array()[0];

            $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);
            $this->talkall_admin->where('id', $id['id']);
            $this->talkall_admin->delete('channel');
        }
    }

    function getInfoChannel($id_channel)
    {
        $this->db->select('*');
        $this->db->from('channel');
        $this->db->where('channel.id_channel', $id_channel);
        $this->db->where('channel.status', 1);
        return $this->db->get()->result_array()[0];
    }

    function WhatsappBroadcastDuplicatePhone($phone)
    {
        $phone = preg_replace('/\D/', '', $phone);

        $ddi = substr($phone, 0, 2);
        $phoneVariants = [];

        if ($ddi == '55') {
            $ddd = substr($phone, 2, 2);
            $rest = substr($phone, 4);

            if (substr($rest, 0, 1) === '9' && strlen($rest) === 9) {
                // Com nono dígito → gera versão sem
                $withoutNine = $ddd . substr($rest, 1);
                $phoneVariants[] = '55' . $ddd . $rest;
                $phoneVariants[] = '55' . $withoutNine;
            } else {
                // Sem nono dígito → gera versão com
                $withNine = $ddd . '9' . $rest;
                $phoneVariants[] = '55' . $ddd . $rest;
                $phoneVariants[] = '55' . $withNine;
            }
        } else
            $phoneVariants[] = $phone;

        $this->db->select('id');
        $this->db->from('channel');
        $this->db->where_in('channel.status', [1, 5]);
        $this->db->where('channel.type', 2);

        $this->db->group_start();
        foreach ($phoneVariants as $variant) {
            $this->db->or_like('channel.id', $variant, 'both');
        }
        $this->db->group_end();

        $result = $this->db->get()->result_array();

        if ($result) {
            $dbPhone = $result[0]['id'];
            $dbPhone = preg_replace('/\D/', '', $dbPhone);

            if ($ddi == '55') {
                if (strlen($phone) < 11 || strlen($dbPhone) < 11) {
                    return false;
                }

                $baseNumber = substr($phone, 4);
                $dbBaseNumber = substr($dbPhone, 4);

                $inputWithNine    = (substr($baseNumber, 0, 1) !== '9') ? '9' . $baseNumber : $baseNumber;
                $inputWithoutNine = (substr($baseNumber, 0, 1) === '9') ? substr($baseNumber, 1) : $baseNumber;

                $dbWithNine    = (substr($dbBaseNumber, 0, 1) !== '9') ? '9' . $dbBaseNumber : $dbBaseNumber;
                $dbWithoutNine = (substr($dbBaseNumber, 0, 1) === '9') ? substr($dbBaseNumber, 1) : $dbBaseNumber;

                if (
                    $inputWithNine === $dbWithNine ||
                    $inputWithNine === $dbWithoutNine ||
                    $inputWithoutNine === $dbWithNine ||
                    $inputWithoutNine === $dbWithoutNine
                ) {
                    return true;
                }
            } else {
                if ($dbPhone === $phone) {
                    return true;
                }
            }
        }
        return false;
    }

    function addChannelBusiness($data)
    {
        for ($i = 0; $i < count($data); $i++) {

            $values = [
                'id' => $data[$i]['phone'] . "@talkall.net",
                'type' => 4,
                'name' => $data[$i]['name'],
                'display_phone_number' => $data[$i]['phone'],
                'status' => 1,
            ];

            $this->db->insert('channel', $values);

            $values = [
                'id' => $data[$i]['phone'],
                'type' => 2,
                'name' => $data[$i]['name'],
                'display_phone_number' => $data[$i]['phone'],
                'status' => 5,
                'is_broadcast' => 1
            ];

            $this->db->insert('channel', $values);

            $channel_id = $this->db->insert_id();

            $values = [
                'id_channel' => $channel_id,
                'timezone' => '-03:00',
                'picture' => $data[$i]['file'],
                'email' => $data[$i]['email'],
                'address' => $data[$i]['address'],
                'social_media' => $data[$i]['site'],
                'welcome' => '',
                'is_broadcast' => 1
            ];

            $this->db->insert('config', $values);

            $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);

            $date = new DateTime();

            $values = [
                'id' => $data[$i]['phone'],
                'creation' => $date->getTimestamp(),
                'id_company' => $this->session->userdata('id_company'),
                'type' => 2,
                'status' => 5,
                'executed' => 1,
                'is_broadcast' => 1
            ];

            $this->talkall_admin->insert('channel', $values);
        }
    }

    function addCommunity($data)
    {
        $data = json_decode($data);

        // var_dump($data);
        // exit;

        for ($i = 0; $i < count($data); $i++) {

            $date = new DateTime();
            $id_channel = null;

            if ($data[$i]->trigger->new == true) {

                $existing_channel_id = $this->channelExists($data[$i]->trigger->number);

                if (!$existing_channel_id) {
                    $values = [
                        'id' => $data[$i]->trigger->number . "@talkall.net",
                        'type' => 4,
                        'name' => $data[$i]->trigger->name,
                        'display_phone_number' => $data[$i]->trigger->number,
                        'status' => 1,
                    ];
                    $this->db->insert('channel', $values);

                    $values = [
                        'id' => $data[$i]->trigger->number,
                        'type' => 2,
                        'name' => $data[$i]->trigger->name,
                        'display_phone_number' => $data[$i]->trigger->number,
                        'status' => 5,
                        'is_broadcast' => 1
                    ];
                    $this->db->insert('channel', $values);

                    $id_channel = $this->db->insert_id();

                    $values = [
                        'id_channel'   => $id_channel,
                        'timezone'     => '-03:00',
                        'picture'      => $data[$i]->trigger->file ?? '',
                        'is_broadcast' => 1,
                        'company_description' => $data[$i]->trigger->description
                    ];
                    $this->db->insert('config', $values);

                    $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);

                    $values = [
                        'id' => $data[$i]->trigger->number,
                        'creation'  => $date->getTimestamp(),
                        'id_company' => $this->session->userdata('id_company'),
                        'type'      => 2,
                        'status'    => 5,
                        'executed'  => 1,
                        'is_broadcast' => 1
                    ];
                    $this->talkall_admin->insert('channel', $values);
                } else {
                    $id_channel = $existing_channel_id;
                }
            } else {
                $id_channel = $data[$i]->trigger->id_channel;
            }

            $values = [
                'id_channel'        => $id_channel,
                'creation'          => $date->getTimestamp(),
                'name'              => $data[$i]->name,
                'subject'           => $data[$i]->trigger->name,
                'description'       => $data[$i]->description,
                'participantsCount' => 0,
                'status'            => 1,
                'creator_name'      => $data[$i]->creator->name,
                'creator_number'    => $data[$i]->creator->number,
                'pictures'           => $data[$i]->file,
            ];

            $this->db->insert('community', $values);
        }
    }

    public function getCommunityChannels()
    {
        $query = $this->db->select("
        community.id_community,
        community.creator_name,
        community.creator_number,
        community.name AS community_name,
        channel.id AS channel_internal_id,
        channel.name AS channel_name,
        channel.id_channel
    ")
            ->from("community")
            ->join("channel", "channel.id_channel = community.id_channel")
            ->where("community.status", 1)
            ->where_in("channel.status", [1, 5])
            ->where("channel.type", 2)
            ->get();

        $result = $query->result_array();
        $cacheCommunitys = [];

        foreach ($result as $index => $row) {
            $cacheCommunitys[$index] = [
                "uuid" => (int)$row["id_community"],
                "name" => $row["community_name"],
                "creator" => [
                    "name"   => $row["creator_name"],
                    "number" => $row["creator_number"],
                ],
                "trigger" => [
                    "name"       => $row["channel_name"],
                    "number"     => $row["channel_internal_id"],
                    "id_channel" => $row["id_channel"],
                ]
            ];
        }

        return $cacheCommunitys;
    }


    function channelExists($id)
    {
        $this->db->select('id_channel');
        $this->db->from('channel');
        $this->db->where('id', $id);
        $this->db->where_in('status', [1, 5]);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row()->id_channel;
        }

        return false;
    }

    function addNewsletter($data)
    {
        $data = json_decode($data);

        for ($i = 0; $i < count($data); $i++) {

            $date = new DateTime();
            $id_channel = null;

            if ($data[$i]->trigger->new == true) {

                $existing_channel_id = $this->channelExists($data[$i]->trigger->number);

                if (!$existing_channel_id) {
                    $values = [
                        'id' => $data[$i]->trigger->number . "@talkall.net",
                        'type' => 4,
                        'name' => $data[$i]->trigger->name,
                        'display_phone_number' => $data[$i]->trigger->number,
                        'status' => 1,
                    ];
                    $this->db->insert('channel', $values);

                    $values = [
                        'id' => $data[$i]->trigger->number,
                        'type' => 2,
                        'name' => $data[$i]->trigger->name,
                        'display_phone_number' => $data[$i]->trigger->number,
                        'status' => 5,
                        'is_broadcast' => 1
                    ];
                    $this->db->insert('channel', $values);

                    $id_channel = $this->db->insert_id();

                    $values = [
                        'id_channel'   => $id_channel,
                        'timezone'     => '-03:00',
                        'picture'      => $data[$i]->trigger->file ?? '',
                        'is_broadcast' => 1,
                        'company_description' => $data[$i]->trigger->description
                    ];
                    $this->db->insert('config', $values);

                    $this->talkall_admin = $this->load->database(Setdatabase("talkall_admin"), TRUE);

                    $values = [
                        'id' => $data[$i]->trigger->number,
                        'creation'  => $date->getTimestamp(),
                        'id_company' => $this->session->userdata('id_company'),
                        'type'      => 2,
                        'status'    => 5,
                        'executed'  => 1,
                        'is_broadcast' => 1
                    ];
                    $this->talkall_admin->insert('channel', $values);
                } else {
                    $id_channel = $existing_channel_id;
                }
            } else {
                $id_channel = $data[$i]->trigger->id_channel;
            }

            $values = [
                'id_channel'        => $id_channel,
                'creation'          => $date->getTimestamp(),
                'name'              => $data[$i]->name,
                'description'       => $data[$i]->description,
                'subscribers_count' => 0,
                'status'            => 1,
                'creator_name'      => $data[$i]->creator->name,
                'creator_number'    => $data[$i]->creator->number,
                'pictures'           => $data[$i]->file,
            ];

            $this->db->insert('newsletter', $values);
        }
    }

    function getNewsletterChannels()
    {
        $query = $this->db->select("
        newsletter.id_newsletter,
        newsletter.creator_name,
        newsletter.creator_number,
        newsletter.name AS newsletter_name,
        channel.id AS channel_internal_id,
        channel.name AS channel_name,
        channel.id_channel
    ")
            ->from("newsletter")
            ->join("channel", "channel.id_channel = newsletter.id_channel")
            ->where("newsletter.status", 1)
            ->where_in("channel.status", [1, 5])
            ->where("channel.type", 2)
            ->get();

        $result = $query->result_array();
        $cacheNewsletters = [];

        foreach ($result as $index => $row) {
            $cacheNewsletters[$index] = [
                "uuid" => (int)$row["id_newsletter"],
                "name" => $row["newsletter_name"],
                "creator" => [
                    "name"   => $row["creator_name"],
                    "number" => $row["creator_number"],
                ],
                "trigger" => [
                    "name"       => $row["channel_name"],
                    "number"     => $row["channel_internal_id"],
                    "id_channel" => $row["id_channel"]
                ]
            ];
        }

        return $cacheNewsletters;
    }
}
