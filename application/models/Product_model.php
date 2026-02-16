<?php

class Product_model extends TA_model
{

    function Get($text, $situation, $start, $length, $order_column, $order_dir)
    {
        $sql = "select\n";
        $sql .= "product.creation,\n";
        $sql .= "product.id_product,\n";
        $sql .= "product.title,\n";
        $sql .= "product.code,\n";
        $sql .= "product.price,\n";
        $sql .= "product.is_appealed,\n";
        $sql .= "product.is_rejected,\n";
        $sql .= "case\n";
        $sql .= "when product.status = 1 then 'Pendente'\n";
        $sql .= "when product.status = 2 then \n";
        $sql .= "	case \n";
        $sql .= "		when product.is_approved = 1 and product.is_rejected = 2 then 'Pendente'\n";
        $sql .= "        when product.is_approved = 2 and product.is_rejected = 1 then 'Pendente'\n";
        $sql .= "        when product.is_approved = 2 and product.is_rejected = 2 then 'Rejeitado'\n";
        $sql .= "		when product.is_approved = 1 and product.is_rejected = 1 then 'Aprovado'\n";
        $sql .= "	end\n";
        $sql .= "when product.status = 3 then 'Removendo'\n";
        $sql .= "when product.status = 4 then 'Apagado'\n";
        $sql .= "end status\n";
        $sql .= "from product\n";
        $sql .= "where product.status != 4 and ( ( product.code like '%" . $text . "%' ) or ( product.title like '%" . $text . "%' ) )\n";

        if (!empty($situation)) {
            if ($situation == 1) {
                $sql .= "and product.is_approved = 2 and product.is_rejected = 1\n";
            } else if ($situation == 2) {
                $sql .= "and product.is_approved = 1 and product.is_rejected = 1\n";
            } else {
                $sql .= "and product.is_approved = 2 and product.is_rejected = 2\n";
            }
        }

        switch ($order_column) {
            case 0:
                $sql .= "ORDER BY product.id_product $order_dir\n";
                break;
            case 1:
                $sql .= "ORDER BY product.title $order_dir\n";
                break;
            case 2:
                $sql .= "ORDER BY product.price $order_dir\n";
                break;
            default:
                $sql .= "ORDER BY product.title asc\n";
        }

        $sql .= "limit {$start},{$length}\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function GetInf($id)
    {
        $sql = "select\n";
        $sql .= "DATE_FORMAT(from_unixtime(product.creation),'%d/%m/%Y') creation,\n";
        $sql .= "product.id_product,\n";
        $sql .= "product.title,\n";
        $sql .= "product.code,\n";
        $sql .= "product.price,\n";
        $sql .= "product.short_description,\n";
        $sql .= "product.thumbnail,\n";
        $sql .= "product.status,\n";
        $sql .= "product.url,\n";
        $sql .= "product.media_url,\n";
        $sql .= "product.currency\n";
        $sql .= "from product\n";
        $sql .= "where product.id_product = '" . $id . "';\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Count($text, $situation)
    {
        $sql = "select count(product.id_product) count\n";
        $sql .= "from product\n";
        $sql .= "where product.status != 4 and ( ( product.code like '%" . $text . "%' ) or ( product.title like '%" . $text . "%' ) )";

        if (!empty($situation)) {
            if ($situation == 1) {
                $sql .= "and product.is_approved = 2 and product.is_rejected = 1\n";
            } else if ($situation == 2) {
                $sql .= "and product.is_approved = 1 and product.is_rejected = 1\n";
            } else {
                $sql .= "and product.is_approved = 2 and product.is_rejected = 2\n";
            }
        }

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Add($data)
    {
        $date = new DateTime();

        $values = [
            'creation' => $date->getTimestamp(),
            'title' => $data['input-title'],
            'price' => $data['input-price'],
            'short_description' => $data['input-description'],
            'code' => $data['input-code'],
            'url' => $data['input-url'],
            'thumbnail' => $data['thumbnail'],
            'media_url' => "https://messenger.talkall.com.br/products/" . $data['cover'],
            'currency' => $data['input-currency'],
            'status' => 1,
        ];

        $this->db->insert('product', $values);

        $product_id = $this->db->insert_id();

        for ($i = 1; isset($data['file' . $i]); $i++) {

            $value = [
                'creation' => $date->getTimestamp(),
                'id_product' => $product_id,
                'order' => 1,
                'media_url' => "https://messenger.talkall.com.br/products/" . $data['file' . $i],
                'thumbnail' => $data['thumbnail' . $i],
            ];

            $this->db->insert('product_picture', $value);
        }
    }


    function Edit($key_id, $data)
    {
        $date =  new DateTime();

        $value_product = [
            'creation' => $date->getTimestamp(),
            'title' => $data['input-title'],
            'price' => $data['input-price'],
            'short_description' => $data['input-description'],
            'code' => $data['input-code'],
            'url' => $data['input-url'],
            'currency' => $data['input-currency'],
            'status' => 1
        ];

        $this->db->where('id_product', $key_id);
        $this->db->update('product', $value_product);

        if ($data['cover'] != "") {

            $value_img = [
                'thumbnail' => $data['thumbnail'],
                'media_url' => "https://messenger.talkall.com.br/products/" . $data['cover'],
            ];
        }

        $values = array_merge($value_product, $value_img);

        $this->db->update('product', $values);

        for ($i = 1; isset($data['file' . $i]); $i++) {

            $value = [
                'creation' => $date->getTimestamp(),
                'id_product' => $key_id,
                'order' => 1,
                'media_url' => "https://messenger.talkall.com.br/products/" . $data['file' . $i],
                'thumbnail' => $data['thumbnail' . $i],
            ];

            $this->db->insert('product_picture', $value);
        }
    }


    function DeleteProductPicture($key_id)
    {
        $this->db->where('id_product_picture', $key_id);
        $this->db->delete('product_picture');
    }


    function Delete($key_id)
    {
        $values = ['status' => 3,];

        $this->db->where('id_product', $key_id);
        $this->db->update('product', $values);
    }


    function List_files($key_id)
    {
        $sql = "SELECT 
                    product.media_url cover_url,
                    product.thumbnail cover_thumbnail,
                    product.id_product cover_id
                FROM
                    product
                WHERE
                    product.id_product = '$key_id'";

        $cover = $this->db->query($sql);

        $sq2 = "SELECT 
                    product_picture.media_url,
                    product_picture.thumbnail,
                    product_picture.id_product_picture
                FROM
                    product_picture
                WHERE
                    product_picture.id_product = '$key_id'";

        $products = $this->db->query($sq2);

        return array_merge($cover->result_array(), $products->result_array());
    }


    function Appeal($key_id)
    {
        $sql = "SELECT * FROM product WHERE product.id_product = '$key_id'";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function AppealSave($key_id, $data)
    {
        $value = ['is_appealed' => 2];

        $this->db->where('id_product', $key_id);
        $this->db->update('product', $value);

        $date =  new DateTime();

        $value = [
            'status' => 2,
            'creation' => $date->getTimestamp(),
            'wa_product_id' => $data['wa_product_id'],
            'description' => $data['input-description'],
        ];

        $this->db->insert('product_appeal', $value);
    }

}
