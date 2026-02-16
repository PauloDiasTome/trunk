<?php

class Contact_model extends TA_model
{
    function Get($param)
    {
        if ($param['checkbox_selected'] == "false") {
            $this->db->select('
            contact.id_contact, 
            DATE_FORMAT(FROM_UNIXTIME(contact.creation), "%d/%m/%Y") as creation_contact,
            contact.key_remote_id AS key_remote_id,
            CASE WHEN COALESCE(contact.full_name, "0") = "" THEN contact.key_remote_id ELSE contact.full_name END AS full_name,
            channel.name AS channel,
            channel.id_channel AS id_channel,
            LEFT(TRIM(contact.full_name), 10) REGEXP "^[a-dXYZ]" AS ordem,
            LEFT(TRIM(UPPER(contact.full_name)), 10) AS first_name,
            contact.spam,
            contact.email,
            contact.verify,
            contact.exist
        ');
        } else {
            $this->db->select('contact.id_contact, channel.id_channel AS channel_id, channel.name AS channel_name');
        }

        if (!empty($param["label"])) {
            $this->db->select('GROUP_CONCAT(DISTINCT label.name) AS labels_name');
        }

        if (!empty($param["responsible"])) {
            $this->db->select('(SELECT user.last_name FROM user WHERE user.key_remote_id = contact.user_key_remote_id) AS responsible', false);
        }

        $this->db->from('contact');
        $this->db->join('channel', 'contact.id_channel = channel.id_channel', 'inner');

        if (!empty($param['persona']) && count($param['persona']) > 0) {
            $this->db->join(
                'contact_group',
                'contact_group.id_contact = contact.id_contact',
                'inner'
            );
            $this->db->where_in(
                'contact_group.id_group_contact',
                $param['persona']
            );
        }

        if (!empty($param["label"])) {
            $this->db->join('chat_list', 'contact.id_contact = chat_list.id_contact', 'left');
            $this->db->join('chat_list_label', 'chat_list_label.id_chat_list = chat_list.id_chat_list', 'left');
            $this->db->join('label', 'chat_list_label.id_label = label.id_label', 'left');
        }

        $this->db->where('contact.is_private', 1);
        $this->db->where('contact.deleted', 1);
        $this->db->where('channel.status !=', 2);
        $this->db->where_in('channel.type', [2, 8, 9, 10, 12, 16]);

        if ($param['text'] != "" && $param['search_type'] == 'contains') {
            $this->db->group_start();
            $this->db->like('LOWER(contact.full_name)', strtolower($param['text']));
            $this->db->or_like('LOWER(channel.name)',  strtolower($param['text']));
            $this->db->or_like('contact.key_remote_id', $param['text']);
            $this->db->group_end();
        }

        if ($param['text'] != "" && $param['search_type'] == 'starts_with') {
            $this->db->group_start();
            $this->db->like('LOWER(contact.full_name)', strtolower($param['text']), 'after');
            $this->db->or_like('LOWER(channel.name)', strtolower($param['text']), 'after');
            $this->db->or_like('contact.key_remote_id', $param['text'], 'after');
            $this->db->group_end();
        }

        if (!empty($param['responsible'])) {
            $this->db->where('contact.user_key_remote_id', $this->db->escape_like_str($param['responsible']));
        }

        if (!empty($param['channel'])) {
            $this->db->where_in('contact.id_channel', $param['channel']);
        }

        if (!empty($param['label'])) {
            $this->db->where_in('label.id_label', $param['label']);
        }

        if (!empty($param['situation'])) {
            switch ($param['situation']) {
                case 4:
                    $this->db->where('contact.spam', 2);
                    break;
                case 3:
                    $this->db->where('contact.exist', 2);
                    $this->db->where('contact.verify', 2);
                    break;
                case 2:
                    $this->db->where('contact.exist', 1);
                    $this->db->where('contact.verify', 2);
                    $this->db->where('contact.spam', 1);
                    break;
                case 1:
                    $this->db->where('contact.verify', 1);
                    break;
            }
        }

        if (!empty($param['dt_start']) && !empty($param['dt_end'])) {
            $this->db->where("DATE_FORMAT(from_unixtime(contact.creation), '%Y-%m-%d') BETWEEN '{$param['dt_start']}' AND '{$param['dt_end']}'", NULL, FALSE);
        }

        if ($param['checkbox_selected'] == "false") {
            $this->db->group_by('contact.id_contact, contact.key_remote_id, contact.email, contact.verify, contact.exist, creation_contact, channel, contact.full_name');
        } else {
            $this->db->group_by('contact.id_contact');
        }

        if ($param['checkbox_selected'] == "false") {
            $count = $this->db->count_all_results('', false);

            switch ($param['order'][0]['column']) {
                case 1:
                    $this->db->order_by('ordem', 'DESC');
                    $this->db->order_by('first_name', $param['order'][0]['dir']);
                    $this->db->order_by('contact.full_name', $param['order'][0]['dir']);
                    break;

                case 2:
                    $this->db->order_by('channel.name', $param['order'][0]['dir']);
                    $this->db->order_by('contact.id_contact', 'asc');
                    break;

                case 3:
                    $this->db->order_by('contact.creation', $param['order'][0]['dir']);
                    break;

                case 4:
                    $this->db->order_by('contact.verify', $param['order'][0]['dir']);
                    $this->db->order_by('contact.exist', $param['order'][0]['dir']);
                    $this->db->order_by('contact.spam', $param['order'][0]['dir']);
                    break;

                default:
                    $this->db->order_by('ordem', 'DESC');
                    $this->db->order_by('first_name', $param['order'][0]['dir']);
                    $this->db->order_by('contact.full_name', $param['order'][0]['dir']);
                    break;
            }

            $this->db->limit($param['length'], $param['start']);

            return ['query' => $this->db->get()->result_array(), 'count' => $count];
        } else {
            return $this->db->get()->result_array();
        }
    }

    function GetAllInternalContact()
    {
        $this->db->select('
            contact.id_contact,
            contact.full_name,
            contact.key_remote_id
        ');

        $this->db->from('user');
        $this->db->join('contact', 'user.key_remote_id = contact.key_remote_id');
        $this->db->where('contact.is_private', 2);
        $this->db->where('user.status', 1);
        $this->db->order_by('contact.key_remote_id');

        return $this->db->get()->result_array();
    }

    public function GetById($key_id)
    {
        $this->db->select('
            GROUP_CONCAT(label.id_label) as labels_id,
            GROUP_CONCAT(label.name) as labels_name,
            GROUP_CONCAT(label.color) as labels_color,
            DATE_FORMAT(DATE_ADD(from_unixtime(contact.creation), INTERVAL config.timezone HOUR),"%d/%m/%Y %T") as creation,
            contact.key_remote_id as id,
            contact.spam,
            channel.id as channel_id,
            channel.name as channel,
            IF(contact.full_name IS NULL OR contact.full_name = "0", "", contact.full_name) as full_name,
            contact.email,
            contact.note,
            contact.user_key_remote_id,
            contact.id_user_group,
            contact.email,
            contact.sex,
            contact.deleted,
            contact.contact_order
        ');

        $this->db->from('contact');
        $this->db->join('contact_tag', 'contact.id_contact_tag = contact_tag.id_contact_tag', 'LEFT');
        $this->db->join('channel', 'contact.id_channel = channel.id_channel', 'INNER');
        $this->db->join('config', 'channel.id_channel = config.id_channel', 'INNER');
        $this->db->join('chat_list', 'contact.id_contact = chat_list.id_contact', 'LEFT');
        $this->db->join('chat_list_label', 'chat_list_label.id_chat_list = chat_list.id_chat_list', 'LEFT');
        $this->db->join('label', 'chat_list_label.id_label = label.id_label', 'LEFT');
        $this->db->join('contact_order', 'contact.id_contact = contact_order.id_contact', 'LEFT');

        $this->db->where('contact.deleted', 1);
        $this->db->where('contact.id_contact', $key_id);

        return $this->db->get()->result_array();
    }

    function ListLabels()
    {
        $this->db->select('
            label.id_label as id_label,
            UPPER(label.name) as name, 
            label.color as color
        ');

        $this->db->from('label');
        $this->db->order_by('label.name');

        return $this->db->get()->result_array();
    }

    function GetContactLabels($id_contact)
    {
        $row = $this->db
            ->select('id_label')
            ->where('id_contact', $id_contact)
            ->get('contact_tag')
            ->row_array();

        if (!$row || empty($row['id_label'])) {
            return [];
        }

        return array_map('intval', explode(',', $row['id_label']));
    }

    function ListChannels()
    {
        $this->db->select('
            channel.id_channel as id_channel,
            CONCAT(UPPER(channel.name), \' (\', channel.id, \')\') as name,
            channel.display_phone_number as number, 
        ');

        $this->db->from('channel');
        $this->db->where_in('channel.type', [2, 8, 9, 10, 12, 16]);
        $this->db->where('channel.status !=', 2);
        $this->db->order_by('channel.name');

        return $this->db->get()->result_array();
    }

    function ListUsers()
    {
        $this->db->select('
            user.key_remote_id, 
            user.name
        ');

        $this->db->from('user');
        $this->db->where('user.status', 1);

        return $this->db->get()->result_array();
    }

    function AddPersona($param)
    {
        if ($param['id_persona'] == "") {

            $values = [
                'name' => trim($param['name']),
                'id_channel' => $param['id_channel'],
                'creation' => time(),
                'profile' => $param['image'] ?? null
            ];

            $this->db->insert('group_contact', $values);

            $param["id_persona"] = $this->db->insert_id();
        }

        $this->AddPersonaParticipants($param);

        return ["success" => ["id_persona" => $param["id_persona"]]];
    }

    function AddPersonaParticipants($param)
    {
        $contacts_id = explode(",", $param["data"]);
        $data = array();

        foreach ($contacts_id as $id) {
            $data[] = array(
                'id_contact' => $id,
                'id_group_contact' => $param['id_persona'],
                'creation' => time()
            );
        }

        $this->db->insert_batch('contact_group', $data);
    }

    function Edit($id_contact, $post)
    {
        $contact = [
            'full_name'     => trim($post['input-full-name']),
            'email'         => trim($post['input-email']),
            'note'          => trim($post['input-note']),
            'id_user_group' => $post['id_user_group'] ?? null,
            'contact_order' => $post['input-order'] === '' ? null : $post['input-order']
        ];

        if (!empty($post['user_key_remote_id']) && $post['user_key_remote_id'] != '0') {
            $contact['user_key_remote_id'] = $post['user_key_remote_id'];
        }

        $this->db->where('id_contact', $id_contact)->update('contact', $contact);
    }

    function updateContactLabels($id_contact, $labels = [])
    {
        $labels_csv = implode(',', $labels);

        $tag = $this->db->where('id_contact', $id_contact)->get('contact_tag')->row_array();
        if (empty($labels)) {
            if ($tag) {
                $this->db->where('id_contact', $id_contact)->delete('contact_tag');
            }
        } else {
            if ($tag) {
                $this->db->where('id_contact', $id_contact)
                    ->update('contact_tag', ['id_label' => $labels_csv]);
            } else {
                $this->db->insert('contact_tag', [
                    'creation' => time(),
                    'id_contact' => $id_contact,
                    'id_label' => $labels_csv
                ]);
            }
        }

        $chat = $this->db->where('id_contact', $id_contact)->get('chat_list')->row_array();

        if (!$chat) {
            $contact = $this->db->where('id_contact', $id_contact)->get('contact')->row_array();

            $data_chat = [
                'id_contact'    => $id_contact,
                'id_channel'    => $contact['id_channel'] ?? 0,
                'key_remote_id' => 0,
                'creation'      => time(),
                'is_chat'       => 1,
                'is_close'      => 1,
                'labels'        => $labels_csv
            ];

            $this->db->insert('chat_list', $data_chat);
            $id_chat_list = $this->db->insert_id();
        } else {
            $id_chat_list = $chat['id_chat_list'];
            $this->db->where('id_chat_list', $id_chat_list)->update('chat_list', ['labels' => $labels_csv]);
        }

        if ($id_chat_list) {
            $this->db->where('id_chat_list', $id_chat_list)->delete('chat_list_label');
            foreach ($labels as $id_label) {
                $this->db->insert('chat_list_label', [
                    'creation' => time(),
                    'id_chat_list' => $id_chat_list,
                    'id_label' => $id_label
                ]);
            }
        }

        if (!empty($labels)) {
            $this->db->select('id_label, name, color');
            $this->db->where_in('id_label', $labels);
            $query = $this->db->get('label');
            $label_data = $query->result_array();

            return [
                'names' => array_column($label_data, 'name'),
                'colors' => array_column($label_data, 'color')
            ];
        }

        return ['names' => [], 'colors' => []];
    }

    function BlockContact($data)
    {
        $contacts = explode(',', $data);
        $ids_contact_in = array_map('intval', $contacts);
        $ids_chat_list = $this->GetIdChatList($ids_contact_in);

        $this->db->trans_start();

        foreach ($ids_contact_in as $id_contact) {

            $value = array(
                'creation' => time(),
                'id_contact' => $id_contact,
                'id_user' => $this->session->userdata('id_user')
            );

            $this->db->insert('block_list', $value);
        }

        $this->db->where_in('id_contact', $ids_contact_in);
        $this->db->update('contact', array("spam" => 2, "user_key_remote_id" => null));

        $this->db->where_in('id_contact', $ids_contact_in);
        $this->db->delete('contact_group');

        $this->db->where_in('id_chat_list', $ids_chat_list);
        $this->db->delete('chat_list_label');

        CreateUserLog($this->db->database, $this->db->hostname, "BLOCK_CONTACT", $this->session->userdata('id_user'));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return ["errors" => ["code" => "PAD-002"]];
        } else {
            return ["success" => ["status" => true]];
        }
    }

    function UnblockContact($data)
    {
        $contacts = explode(',', $data);
        $ids_contact_in = array_map('intval', $contacts);

        $this->db->trans_start();

        $this->db->where_in('id_contact', $ids_contact_in);
        $this->db->update('contact', array("spam" => 1));

        $this->db->where_in('id_contact', $ids_contact_in);
        $this->db->delete('block_list');

        CreateUserLog($this->db->database, $this->db->hostname, "UNBLOCK_CONTACT", $this->session->userdata('id_user'));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return ["errors" => ["code" => "PAD-002"]];
        } else {
            return ["success" => ["status" => true]];
        }
    }

    function GetContactsFromWaitList($ids_contact_in)
    {
        $this->db->select('key_remote_id');
        $this->db->from('contact');
        $this->db->where_in('id_contact', $ids_contact_in);
        $this->db->where('contact.deleted', 1);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return array_column($query->result_array(), 'key_remote_id');
        }

        return FALSE;
    }

    function GetIdChatList($ids_contact_in)
    {
        $this->db->select('id_chat_list');
        $this->db->from('chat_list');
        $this->db->where_in('id_contact', $ids_contact_in);
        $this->db->where('chat_list.deleted', 1);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return array_column($query->result_array(), 'id_chat_list');
        }

        return FALSE;
    }

    function DeleteContact($data)
    {
        $contacts = explode(',', $data);
        $ids_contact_in = array_map('intval', $contacts);
        $key_remote_ids_in = $this->GetContactsFromWaitList($ids_contact_in);

        $this->db->trans_start();

        $this->db->where_in('id_contact', $ids_contact_in);
        $this->db->update('chat_list', array("deleted" => 2));

        $this->db->where_in('id_contact', $ids_contact_in);
        $this->db->update('contact', array("deleted" => 2));

        $this->db->where_in('id_contact', $ids_contact_in);
        $this->db->delete('contact_group');

        if ($key_remote_ids_in != FALSE) {
            $this->db->where_in('key_remote_id', $key_remote_ids_in);
            $this->db->delete('wait_list');
        }

        CreateUserLog($this->db->database, $this->db->hostname, 'DELETE_CONTACT', $this->session->userdata('id_user'));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return ["errors" => ["code" => "PAD-002"]];
        } else {
            return ["success" => ["status" => true]];
        }
    }

    function VerifyContactAttendance($data)
    {
        $contacts = explode(',', $data);
        $ids_contact_in = array_map('intval', $contacts);

        $this->db->select('chat_list.id_contact, SUBSTRING_INDEX(contact.key_remote_id, "-", 1) AS key_remote_id');
        $this->db->from('chat_list');
        $this->db->join('contact', 'chat_list.id_contact = contact.id_contact');
        $this->db->where('chat_list.is_close', 1);
        $this->db->where('chat_list.key_remote_id <>', '0');
        $this->db->where('contact.is_private', '1');
        $this->db->where('chat_list.key_remote_id IS NOT NULL');
        $this->db->where_in('chat_list.id_contact', $ids_contact_in);

        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            return ["success" => ["status" => true, "attendance" => $result->num_rows()]];
        } else {
            return ["success" => ["status" => true,  "attendance" => 0]];
        }
    }

    public function GetAllContacts()
    {
        $this->db->select('contact.id_contact, channel.id_channel as channel_id, channel.name as channel_name');
        $this->db->from('contact');
        $this->db->join('channel', 'contact.id_channel = channel.id_channel');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function GetPersonas($channels_ids = null)
    {
        $this->db->select('id_group_contact, name, id_channel');
        $this->db->from('group_contact');

        if ($channels_ids !== null && is_array($channels_ids) && !empty($channels_ids)) {
            $clean_ids = array_filter(array_map('intval', $channels_ids));

            if (!empty($clean_ids)) {
                $this->db->where_in('id_channel', $clean_ids);
            }
        }

        $this->db->order_by('name', 'ASC');
        return $this->db->get()->result_array();
    }
}
