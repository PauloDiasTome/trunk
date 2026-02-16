<?php

class Persona_model extends TA_model
{
    function Get($param)
    {
        $this->db->select('
            group_contact.id_group_contact,
            group_contact.name,
            group_contact.profile,
            group_contact.key_id,
            DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(group_contact.creation), INTERVAL config.timezone HOUR), "%d/%m/%Y %H:%i") creation,
            CONCAT(channel.name, \' (\', REPLACE(channel.id, \'@c.us\', \'\'), \')\') as channel,
            (SELECT COUNT(contact_group.id_contact)
            FROM contact_group
            WHERE contact_group.id_group_contact = group_contact.id_group_contact) as participant_count
        ');

        $this->db->from('group_contact');
        $this->db->join('channel', 'group_contact.id_channel = channel.id_channel', 'left');
        $this->db->join('config', 'channel.id_channel = config.id_channel');
        $this->db->like('LOWER(group_contact.name)', $this->db->escape_like_str(strtolower($param['text'])));
        $this->db->or_like('LOWER(channel.name)', $this->db->escape_like_str(strtolower($param['text'])));
        $this->db->or_like('LOWER(channel.id)', $this->db->escape_like_str(strtolower($param['text'])));

        $count = $this->db->count_all_results('', false);

        $this->db->order_by('group_contact.name', $param['order'][0]['dir']);

        $this->db->limit($param['length'], $param['start']);

        return ['query' => $this->db->get()->result_array(), 'count' => $count];
    }

    function GetById($group_contact)
    {
        $this->db->select('
            group_contact.name,
            group_contact.profile,
            group_contact.id_channel,
            channel.id,
            CONCAT(channel.name, \' (\', REPLACE(channel.id, \'@c.us\', \'\'), \')\') as channel
        ');

        $this->db->from('group_contact');
        $this->db->join('channel', 'channel.id_channel = group_contact.id_channel');
        $this->db->where('group_contact.id_group_contact', $group_contact);

        return $this->db->get()->row_array();
    }

    function GetContacts($id_channel, $contacts)
    {
        if (empty($contacts)) {
            return [];
        }

        $escaped_contacts = array_map(function ($c) {
            return "'" . $this->db->escape_str($c) . "'";
        }, $contacts);

        $contacts_list = implode(',', $escaped_contacts);

        $sql = "
            SELECT 
                contact.id_contact,
                contact.email,
                contact.full_name AS name,
                contact.key_remote_id
            FROM contact
            WHERE 
                contact.deleted = 1
                AND contact.is_private = 1
                AND contact.id_channel = ?
                AND contact.key_remote_id IN ($contacts_list)
        ";

        $query = $this->db->query($sql, [$id_channel]);
        return $query->result_array() ?: [];
    }

    function List()
    {
        $this->db->select('
            group_contact.id_group_contact,
            group_contact.name
        ');

        $this->db->from('group_contact');
        $this->db->order_by('group_contact.name');

        return  $this->db->get()->result_array();
    }

    function ListChannel()
    {
        $this->db->select('
            channel.id,
            channel.id_channel,
            CONCAT(channel.name, \' (\', REPLACE(channel.id, \'@c.us\', \'\'), \')\') as name
        ');

        $this->db->from('channel');
        $this->db->where_in('channel.type', [2, 12, 16, 6]);
        $this->db->where('channel.status', 1);

        return $this->db->get()->result_array();
    }

    function ListParticipants($id)
    {
        $this->db->select('
            contact.id_contact,
            contact.full_name name,
            contact.key_remote_id,
            contact.email
        ');

        $this->db->from('contact');
        $this->db->join('contact_group', 'contact.id_contact = contact_group.id_contact', 'left');
        $this->db->where('contact_group.id_group_contact', $id["id_persona"]);
        $this->db->order_by("CASE WHEN contact.full_name REGEXP '^[A-Za-z]' THEN 0 ELSE 1 END, contact.full_name");

        return $this->db->get()->result_array();
    }

    function ListChannelContacts($param)
    {
        $this->db->select('
            contact.id_contact,
            contact.key_remote_id,
            contact.email,
            contact.full_name as name
        ');

        $this->db->from('contact');
        $this->db->join('channel', 'contact.id_channel = channel.id_channel', 'inner');
        $this->db->where('contact.id_channel', $param['id_channel']);
        $this->db->where('contact.deleted', 1);
        $this->db->where('contact.is_private', 1);
        $this->db->group_start();
        $this->db->like('LOWER(contact.full_name)', $this->db->escape_like_str(strtolower($param['search'])));
        $this->db->or_like('contact.key_remote_id', $param['search']);
        $this->db->group_end();
        $this->db->order_by("CASE WHEN contact.full_name REGEXP '^[A-Za-z]' THEN 0 ELSE 1 END, contact.full_name");

        if ($param['limit'] != "false")
            $this->db->limit($param['limit'], $param['offset']);

        return $this->db->get()->result_array();
    }

    function Add($param)
    {
        if (empty($param["id_persona"])) {
            $date = new DateTime();

            $values = [
                'name' => trim($param['name']),
                'id_channel' => $param['id_channel'],
                'creation' => $date->getTimestamp(),
                'profile' => $param['image'] != null ? $param['image'] : null
            ];

            $this->db->insert('group_contact', $values);

            $param["id_persona"] = $this->db->insert_id();
        }

        $this->AddParticipants($param);

        return ["success" => ["status" => true, "id_persona" => $param["id_persona"]]];
    }

    function AddParticipants($param)
    {
        $contacts_id = json_decode($param['contacts_id'], true);
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

    function Edit($param)
    {
        $values = [
            'profile' => $param['image'],
            'name' => trim($param['name'])
        ];

        $this->db->where('id_group_contact', $param['id_persona']);
        $this->db->update('group_contact', $values);

        $this->AddParticipants($param);
        return ["success" => ["status" => true]];
    }

    function ImportContacts($param)
    {
        $values = array();
        $id_contacts = array();
        $data = $this->FormatedNumbers($param);

        $contacts_info = $this->RemoveDuplicateContacts($data);

        $duplicated = $contacts_info["duplicated"];
        $contacts = $data["data"]["contacts"];

        if (!empty($contacts_info["duplicated"])) {

            $this->updateContact($contacts, $duplicated);
        }

        if (!empty($contacts_info["contacts"])) {

            foreach ($contacts_info["contacts"] as &$contact) {
                $values[] = array(
                    "full_name" => isset($contact["name"]) ? substr($contact["name"], 0, 100) : $contact["key_remote_id"],
                    "email" => isset($contact["email"]) ? substr($contact["email"], 0, 55) : null,
                    "key_remote_id" => $contact["key_remote_id"],
                    "id_channel" => $param["id_channel"],
                    "creation" => time(),
                    "is_imported" => 2,
                    "is_group" => 1,
                    "verify" => 2,
                    "exist" => 1
                );
            }

            $this->db->insert_batch("contact", $values);

            $query_id_contacts = $this->db->query("SELECT id_contact FROM contact WHERE deleted = 1 AND key_remote_id IN ('" . implode("','", array_column($contacts_info["contacts"], "key_remote_id")) . "')");

            foreach ($query_id_contacts->result() as $row) {
                $id_contacts[] = $row->id_contact;
            }

            foreach ($contacts_info["contacts"] as &$contact) {
                $key = array_search($contact["key_remote_id"], array_column($values, "key_remote_id"));
                $contact["id_contact"] = (isset($id_contacts[$key])) ? $id_contacts[$key] : null;
            }
        }

        if (empty($contacts_info["duplicated"]) && empty($contacts_info["contacts"])) {
            return [];
        } else if (empty($contacts_info["duplicated"]) && !empty($contacts_info["contacts"])) {
            return array_values($contacts_info["contacts"]);
        } else if (!empty($contacts_info["duplicated"]) && empty($contacts_info["contacts"])) {
            return array_values($contacts_info["duplicated"]);
        } else {
            return array_merge($contacts_info["contacts"], $contacts_info["duplicated"]);
        }
    }

    function DeleteParticipants($key_id)
    {
        $this->db->where('id_group_contact', $key_id);
        $this->db->delete('contact_group');

        return ["success" => ["status" => true]];
    }

    function Delete($key_id)
    {
        $this->db->where('id_group_contact', $key_id);
        $this->db->delete('group_contact');

        $this->db->where('id_group_contact', $key_id);
        $data = $this->db->delete('contact_group');

        return $data;
    }

    function RemoveDuplicateContacts($param)
    {
        $contacts = $param["data"]["contacts"];
        $contact_from_base = $param["contact_from_base"];

        if (empty($contact_from_base)) {
            return ["contacts" => $contacts, "duplicated" => []];
        }

        // Criar array de números normalizados existentes na base
        $existing_normalized = [];
        foreach ($contact_from_base as $existing) {
            $normalized = $this->normalizeNumber($existing["key_remote_id"]);
            $existing_normalized[] = $normalized;
        }

        // Filtrar contatos novos comparando versões normalizadas
        $unique_contacts = array_filter($contacts, function ($contact) use ($existing_normalized) {
            $contact_normalized = $this->normalizeNumber($contact["key_remote_id"]);
            return !in_array($contact_normalized, $existing_normalized);
        });

        // Remover duplicatas dos contatos da base
        $unique_base_contacts = [];
        $processed_normalized = [];

        foreach ($contact_from_base as $base_contact) {
            $normalized = $this->normalizeNumber($base_contact["key_remote_id"]);
            if (!in_array($normalized, $processed_normalized)) {
                $processed_normalized[] = $normalized;
                $unique_base_contacts[] = $base_contact;
            }
        }

        return [
            "contacts" => array_values($unique_contacts),
            "duplicated" => array_values($unique_base_contacts)
        ];
    }

    function normalizeNumber($number)
    {
        $parts = explode('-', $number);
        $phone = preg_replace('/\D/', '', $parts[0]);
        $channel = isset($parts[1]) ? $parts[1] : '';

        if (strlen($phone) >= 10) {
            $countryCode = substr($phone, 0, 2); // 55
            $areaCode = substr($phone, 2, 2);    // DDD
            $restOfNumber = substr($phone, 4);   // resto

            // Normalizar sempre para versão SEM o nono dígito para comparação
            $firstDigit = substr($restOfNumber, 0, 1);
            if (in_array($firstDigit, ['9', '8', '7']) && strlen($restOfNumber) == 9) {
                // Remove o nono dígito se existir
                $normalized = $countryCode . $areaCode . substr($restOfNumber, 1);
            } else {
                $normalized = $phone;
            }
        } else {
            $normalized = $phone;
        }

        return $normalized . ($channel ? '-' . $channel : '');
    }


    function FormatedNumbers($data)
    {
        $key_remote_id = [];
        $contacts = json_decode($data["contacts"], true);

        foreach ($contacts as &$param) {
            $param["key_remote_id"] = $param["key_remote_id"] . "-" . $data["channel"];
            $key_remote_id[] = $param["key_remote_id"];
        }

        $data["contacts"] = $contacts;

        // Gerar todas as variações possíveis dos números
        $all_variations = $this->generateAllNumberVariations($key_remote_id);

        // Buscar contatos existentes com todas as variações
        $contact_from_base = $this->GetContacts($data["id_channel"], $all_variations);

        return [
            "data" => $data,
            "contact_from_base" => $contact_from_base
        ];
    }

    function generateAllNumberVariations($numbers)
    {
        $variations = [];

        foreach ($numbers as $number) {
            // Separar número do canal
            $parts = explode('-', $number);
            $phone = $parts[0];
            $channel = isset($parts[1]) ? $parts[1] : '';

            // Limpar o número (manter apenas dígitos)
            $cleanPhone = preg_replace('/\D/', '', $phone);

            if (strlen($cleanPhone) >= 10) {
                // Extrair código do país (55), DDD e número
                $countryCode = substr($cleanPhone, 0, 2); // 55
                $areaCode = substr($cleanPhone, 2, 2);    // 14
                $restOfNumber = substr($cleanPhone, 4);   // resto do número

                // Verificar se é celular (números que começam com 9, 8 ou 7)
                $firstDigitOfNumber = substr($restOfNumber, 0, 1);

                if (in_array($firstDigitOfNumber, ['9', '8', '7'])) {
                    // É celular
                    if (strlen($restOfNumber) == 9) {
                        // Tem 9 dígitos no número (já tem o nono dígito)
                        // Criar versão sem o nono dígito
                        $withoutNinth = $countryCode . $areaCode . substr($restOfNumber, 1);
                        $variations[] = $withoutNinth . ($channel ? '-' . $channel : '');
                    } else if (strlen($restOfNumber) == 8) {
                        // Tem 8 dígitos no número (não tem o nono dígito)
                        // Criar versão com o nono dígito
                        $withNinth = $countryCode . $areaCode . '9' . $restOfNumber;
                        $variations[] = $withNinth . ($channel ? '-' . $channel : '');
                    }
                }
            }

            // Sempre adicionar o número original
            $variations[] = $number;
        }

        return array_unique($variations);
    }

    function removeNinthDigit($numbers)
    {
        $result = [];

        foreach ($numbers as $number) {
            $parts = explode('-', $number);
            $phone = $parts[0];
            $channel = isset($parts[1]) ? $parts[1] : '';

            // Remove caracteres não numéricos para análise
            $cleanPhone = preg_replace('/\D/', '', $phone);

            // Se tem 11 dígitos e o 3º dígito é 9, remove o 9
            if (strlen($cleanPhone) == 11 && substr($cleanPhone, 2, 1) == '9') {
                $phoneWithout9 = substr($cleanPhone, 0, 2) . substr($cleanPhone, 3);
                $result[] = $phoneWithout9 . ($channel ? '-' . $channel : '');
            }

            // Sempre adiciona o número original também
            $result[] = $number;
        }

        return array_unique($result);
    }

    function convertNumbersWithAndWithout9(array $numbers)
    {
        $with9 = [];
        $without9 = [];

        foreach ($numbers as $number) {
            // Separar número do canal
            [$phone, $channel] = explode('-', $number);

            // Extrair DDI, DDD e número
            $ddi = substr($phone, 0, 2);      // 55
            $ddd = substr($phone, 2, 2);      // Ex: 43
            $numberRest = substr($phone, 4);  // O restante (número do telefone)

            // Verificar se tem nono dígito (números móveis no Brasil têm 9 dígitos, fixos não)
            // Exemplo de regra: se começa com 9 e tem 9 dígitos depois do DDD

            if (strlen($numberRest) === 9 && substr($numberRest, 0, 1) === '9') {
                // ✅ Tem nono dígito, vamos gerar a versão sem o 9
                $numberWithout9 = $ddi . $ddd . substr($numberRest, 1);
                $without9[] = $numberWithout9 . '-' . $channel;

                // ✅ Mantém também a versão com 9
                $with9[] = $phone . '-' . $channel;
            } else {
                // ✅ Não tem nono dígito, vamos gerar a versão com 9 na frente
                $numberWith9 = $ddi . $ddd . '9' . $numberRest;
                $with9[] = $numberWith9 . '-' . $channel;

                // ✅ Mantém também a versão sem 9
                $without9[] = $phone . '-' . $channel;
            }
        }

        return [
            'with9' => $with9,
            'without9' => $without9
        ];
    }

    function AddNinthDigit($key_remote_id)
    {
        $number_with_ninth = [];

        foreach ($key_remote_id as $item) {
            $number_with_ninth[] = str_replace(' ', '', preg_replace("/^(\d{4})(\d{8}-\d+)$/", "$1 9$2", $item));
        }

        return array_merge($key_remote_id, $number_with_ninth);
    }

    function updateContact($contacts, $duplicated)
    {
        foreach ($duplicated as &$dup) {
            $split_dup = explode('-', $dup['key_remote_id']);
            $split_dup = substr($split_dup[0], -8);

            foreach ($contacts as $contact) {
                $contact_parts = explode('-', $contact['key_remote_id']);
                $contact_key8  = substr($contact_parts[0], -8);

                if ($contact_key8 === $split_dup) {
                    $dup['email'] = $contact['email'];
                    $dup['name']  = $contact['name'];
                    break;
                }
            }
        }

        $has_data = false;

        for ($i = 0; $i < count($duplicated); $i++) {

            $e = $duplicated[$i];

            if (empty($e['id_contact'])) {
                continue;
            }

            $this->db->where('id_contact', $e['id_contact']);

            $has_data = false;

            if (!empty($e['email'])) {
                $this->db->set('email', $e['email']);
                $has_data = true;
            }

            if (!empty($e['name'])) {
                $this->db->set('full_name', $e['name']);
                $has_data = true;
            }

            if ($has_data) {
                $this->db->update('contact');
            }

            $this->db->reset_query();
        }
    }
}
