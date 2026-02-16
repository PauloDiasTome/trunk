<?php

class Category_model extends TA_model
{
    public function Get($post)
    {
        $text = $post['text'] ?? '';
        $orderColumn = $post['order'][0]['column'] ?? 0;
        $orderDir = $post['order'][0]['dir'] ?? 'asc';
        $start = $post['start'] ?? 0;
        $length = $post['length'] ?? 10;

        $this->db->select('
        category.id_category,
        category.name
    ');
        $this->db->from('category');
        $this->db->where('status', 1);

        if (!empty($text)) {
            $this->db->like('LOWER(category.name)', strtolower($text));
        }

        // Clona a query para contar os resultados filtrados
        $clone = clone $this->db;
        $count = $clone->count_all_results();

        switch ((int) $orderColumn) {
            case 1:
                $this->db->order_by('category.name', $orderDir);
                break;
            default:
                $this->db->order_by('category.name', $orderDir);
        }

        $this->db->limit($length, $start);
        $query = $this->db->get()->result_array();

        return [
            'query' => $query,
            'count' => $count
        ];
    }

    public function Add($data)
    {
        $this->db->insert('category', [
            'name' => trim($data['name']),
            'description' => trim($data['input-data'])
        ]);

        $categoryId = $this->db->insert_id();

        if (!empty($data['others'])) {
            $insertData = array_map(function ($userGroupId) use ($categoryId) {
                return [
                    'id_category' => $categoryId,
                    'id_user_group' => $userGroupId
                ];
            }, $data['others']);

            $this->db->insert_batch('category_user_group', $insertData);
        }

        return $categoryId;
    }


    public function Edit($categoryId, $data)
    {
        // Atualiza a categoria
        $this->db->where('id_category', $categoryId);
        $this->db->update('category', [
            'name' => trim($data['name']),
            'description' => trim($data['input-data'] ?? '')
        ]);

        // Remove vínculos antigos
        $this->db->where('id_category', $categoryId);
        $this->db->delete('category_user_group');

        // Adiciona os novos vínculos
        if (!empty($data['others'])) {
            $insertData = array_map(function ($userGroupId) use ($categoryId) {
                return [
                    'id_category' => $categoryId,
                    'id_user_group' => $userGroupId
                ];
            }, $data['others']);

            $this->db->insert_batch('category_user_group', $insertData);
        }
    }


    public function Delete($categoryId)
    {

        $this->db->where('id_category', $categoryId);
        $this->db->update('category', ['status' => 2]);

        $this->db->where('id_category', $categoryId);
        $this->db->delete('category_user_group');

        return ['status' => true];
    }

    public function list()
    {
        $this->db->select('id_category, name');
        $this->db->from('category');
        $this->db->order_by('name');
        return $this->db->get()->result_array();
    }

    public function GetInf($id)
    {
        $this->db->select('id_category, name, description');
        $this->db->from('category');
        $this->db->where('id_category', $id);
        return $this->db->get()->result_array();
    }

    public function GetUserGroups($categoryId)
    {
        $this->db->select('id_user_group');
        $this->db->from('category_user_group');
        $this->db->where('id_category', $categoryId);
        $result = $this->db->get()->result_array();
        return array_column($result, 'id_user_group');
    }
}
