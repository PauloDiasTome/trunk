<?php

class UserGroupWork_model extends TA_model
{
    function Get($text, $start, $length)
    {
        $sql = "select\n";
        $sql .= "groups.id_group,\n";
        $sql .= "groups.name,\n";
        $sql .= "(\n";
        $sql .= "select count(group_participants.id_group_participant) from group_participants\n";
        $sql .= "where group_participants.id_group = groups.id_group\n";
        $sql .= ") qtda\n";
        $sql .= "from groups\n";
        $sql .= "where groups.name like '%" . $text . "%'\n";
        $sql .= "order by groups.name\n";
        $sql .= "limit {$start},{$length}\n";

        $result = $this->db->query($sql);

        $data = $result->result_array();

        return $data;
    }


    function GetInf($id)
    {
        $sql = "select\n";
        $sql .= "groups.id_group,\n";
        $sql .= "groups.key_remote_id,\n";
        $sql .= "groups.participants,\n";
        $sql .= "groups.name\n";
        $sql .= "from groups\n";
        $sql .= "where groups.id_group = '" . $id . "';\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Count($text)
    {
        $sql = "select count(groups.id_group) count\n";
        $sql .= "from groups\n";
        $sql .= "where groups.name like '%" . $text . "%'\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Edit($key_id, $data)
    {
        $values = [
            'name' => $data['input-name'],
            'participants' => $data['input-participants'],
        ];

        $this->db->where('id_group', $key_id);
        $this->db->update('groups', $values);

        $this->db->where('id_group', $key_id);
        $this->db->delete('group_participants');

        $sql = "select groups.key_remote_id from groups\n";
        $sql .= "where groups.id_group = $key_id\n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {

            $values = [
                'full_name' => $data['input-name'],
            ];

            $this->db->where('key_remote_id', $result->row()->key_remote_id);
            $this->db->update('contact', $values);
        }

        $participants = explode(",", $data['input-participants']);

        foreach ($participants as $participant) {
            $this->AddParticipant(
                $key_id,
                $participant
            );
        }

        $date = new DateTime();

        $sql = "select contact.id_contact,contact.key_remote_id from groups inner join contact on groups.key_remote_id = contact.key_remote_id\n";
        $sql .= "where groups.id_group = $key_id\n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {

            $key_remote_id = $result->row()->key_remote_id;
            $contact_id = $result->row()->id_contact;

            foreach ($participants as $participant) {

                $sql = "select channel.id_channel from channel where channel.id = '$participant'\n";

                $result = $this->db->query($sql);

                if ($result->num_rows() > 0) {

                    $channel_id = $result->row()->id_channel;

                    $sql = "select\n";
                    $sql .= "chat_list.id_chat_list,\n";
                    $sql .= "contact.key_remote_id,\n";
                    $sql .= "contact.is_private,\n";
                    $sql .= "chat_list.is_close,\n";
                    $sql .= "chat_list.is_wait\n";
                    $sql .= "from chat_list\n";
                    $sql .= "inner join channel on chat_list.id_channel = channel.id_channel\n";
                    $sql .= "inner join contact on chat_list.id_contact = contact.id_contact\n";
                    $sql .= "where contact.spam = 1 and channel.status = 1 and contact.deleted = 1 and chat_list.key_remote_id = '" . $participant . "' and contact.key_remote_id = '" . $key_remote_id . "'\n";

                    $result = $this->db->query($sql);

                    if ($result->num_rows() == 0) {

                        $values = [
                            'creation' => $date->getTimestamp(),
                            'id_channel' => $channel_id,
                            'id_contact' => $contact_id,
                            'key_remote_id' => $participant,
                            'is_chat' => 1,
                            'is_wait' => 1,
                            'deleted' => 1,
                            'short_timestamp' => $date->getTimestamp(),
                            'message_no_read' => 0,
                            'is_broadcast' => 1,
                            'is_private' => 2,
                            'is_group' => 2,
                        ];

                        $this->db->insert('chat_list', $values);
                    } else {

                        $values = [
                            'is_close' => 1,
                        ];

                        $this->db->where('id_chat_list', $result->row()->id_chat_list);
                        $this->db->update('chat_list', $values);
                    }
                }
            }
        }
    }


    function AddParticipant($key, $key_remote_id)
    {
        $date = new DateTime();

        $values = [
            'creation' => $date->getTimestamp(),
            'id_group' => $key,
            'key_remote_id' => $key_remote_id
        ];

        $this->db->insert('group_participants', $values);
    }


    function Add($data)
    {
        $date = new DateTime();

        $key_remote_id = $this->session->userdata('key_remote_id') . "-" . $date->getTimestamp();

        $values = [
            'creation' => $date->getTimestamp(),
            'name' => $data['input-name'],
            'key_remote_id' => $key_remote_id,
            'participants' => $data['input-participants'],
        ];

        $this->db->insert('groups', $values);

        $id_group = $this->db->insert_id();

        $participants = explode(",", $data['input-participants']);

        foreach ($participants as $participant) {
            $this->AddParticipant(
                $id_group,
                $participant
            );
        }

        $values = [
            'name' => 'Group',
            'id' => $key_remote_id,
            'type' => 1,
        ];

        $this->db->insert('channel', $values);

        $channel_id =  $this->db->insert_id();

        $values = [
            'id_channel' => $channel_id,
            'timezone' => '-00:00',
            'welcome' => '',
        ];

        $this->db->insert('config', $values);

        $values = [
            'creation' => $date->getTimestamp(),
            'id_channel' => $channel_id,
            'key_remote_id' => $key_remote_id,
            'full_name' => $data['input-name'],
            'deleted' => 1,
            'spam' => 1,
            'sex' => 1,
            'verify' => 2,
            'exist' => 1,
            'is_private' => 2,
            'is_group' => 2,
            'presence' => 'unavailable',
            'timestamp' => $date->getTimestamp()
        ];

        $this->db->insert('contact', $values);

        $contact_id =  $this->db->insert_id();

        foreach ($participants as $participant) {

            $sql = "select channel.id_channel from channel where channel.id = '$participant'\n";

            $result = $this->db->query($sql);

            if ($result->num_rows() > 0) {

                $channel_id = $result->row()->id_channel;

                $sql = "select\n";
                $sql .= "chat_list.id_chat_list,\n";
                $sql .= "contact.key_remote_id,\n";
                $sql .= "contact.is_private,\n";
                $sql .= "chat_list.is_close,\n";
                $sql .= "chat_list.is_wait\n";
                $sql .= "from chat_list\n";
                $sql .= "inner join channel on chat_list.id_channel = channel.id_channel\n";
                $sql .= "inner join contact on chat_list.id_contact = contact.id_contact\n";
                $sql .= "where contact.spam = 1 and channel.status = 1 and contact.deleted = 1 and chat_list.key_remote_id = '" . $key_remote_id . "' and contact.key_remote_id = '" . $participant . "'\n";

                $result = $this->db->query($sql);

                if ($result->num_rows() == 0) {

                    $values = [
                        'creation' => $date->getTimestamp(),
                        'id_channel' => $channel_id,
                        'id_contact' => $contact_id,
                        'key_remote_id' => $participant,
                        'is_chat' => 1,
                        'is_wait' => 1,
                        'deleted' => 1,
                        'short_timestamp' => $date->getTimestamp(),
                        'message_no_read' => 0,
                        'is_broadcast' => 1,
                        'is_private' => 2,
                        'is_group' => 2,
                    ];

                    $this->db->insert('chat_list', $values);
                } else {

                    $values = [
                        'is_close' => 1,
                    ];

                    $this->db->where('id_chat_list', $result->row()->id_chat_list);
                    $this->db->update('chat_list', $values);
                }
            }
        }
    }


    function Delete($key_id)
    {
        $sql = "select * from groups inner join contact on groups.key_remote_id = contact.key_remote_id\n";
        $sql .= "where groups.id_group = $key_id\n";

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {

            $this->db->where('id_group', $key_id);
            $this->db->delete('group_participants');

            $this->db->where('id_group', $key_id);
            $this->db->delete('groups');

            $this->db->where('id_contact', $result->row()->id_contact);
            $this->db->delete('contact');

            $this->db->where('id_channel', $result->row()->id_contact);
            $this->db->delete('chat_list');

            $this->db->where('id_channel', $result->row()->id_channel);
            $this->db->delete('config');

            $this->db->where('id_channel', $result->row()->id_channel);
            $this->db->delete('channel');
        }
    }


    function ExportCsv()
    {
        $sql = "select\n";
        $sql .= "groups.name nome,\n";
        $sql .= "(\n";
        $sql .= "select count(group_participants.id_group_participant) from group_participants\n";
        $sql .= "where group_participants.id_group = groups.id_group\n";
        $sql .= ") quantidade\n";
        $sql .= "from groups\n";
        $sql .= "order by groups.name\n";

        $result = $this->db->query($sql);

        $data = $result->result_array();

        return $data;
    }
}
