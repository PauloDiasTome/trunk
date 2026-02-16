<?php

class Order_model extends TA_model
{

    function Get($text, $order_status, $dt_start, $dt_end, $start, $length, $order_column, $order_dir)
    {
        $status = "";

        if (!empty($order_status)) {
            foreach ($order_status as $key => $val) {
                $status .= "," . $val;
            }
            $status = substr($status, 1);
        }

        $sql = "SELECT 
                    messages_order.id_messages_order,
                    DATE_FORMAT(FROM_UNIXTIME(messages_order.creation),'%d/%m/%Y %H:%i') creation,
                    messages_order.order_id,
                    messages_order.seller_jid,
                    order_status.name name_status,
                    order_status.color
                FROM
                    contact
                        INNER JOIN
                    messages_order ON contact.key_remote_id = messages_order.seller_jid
                        LEFT JOIN
                    order_status ON messages_order.id_order_status = order_status.id_order_status
                WHERE
                    contact.deleted = 1 AND contact.spam = 1 AND (contact.key_remote_id LIKE '%$text%'
                        OR messages_order.order_id LIKE '%$text%' 
                        OR order_status.name LIKE '%$text%'
                        OR DATE_FORMAT(FROM_UNIXTIME(messages_order.creation), '%d/%m/%Y %H:%i') LIKE '%$text%')\n";

        if (trim($status) != "") {
            $sql .= "AND order_status.id_order_status IN ($status)\n";
        }

        if (trim($dt_start) != "") {
            $sql .= "AND DATE_FORMAT(from_unixtime(messages_order.creation),'%Y-%m-%d') BETWEEN '{$dt_start}' AND '{$dt_end}'\n";
        }

        switch ($order_column) {
            case 0:
                $sql .= "ORDER BY messages_order.creation $order_dir \n";
                break;

            case 1:
                $sql .= "ORDER BY messages_order.order_id $order_dir \n";
                break;

            case 2:
                $sql .= "ORDER BY messages_order.seller_jid $order_dir \n";
                break;

            case 3:
                $sql .= "ORDER BY order_status.name $order_dir \n";
                break;

            default:
                break;
        }

        $sql .= "LIMIT {$start},{$length}\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Count($text)
    {
        $sql = "SELECT 
                    COUNT(messages_order.id_messages_order) count
                FROM
                    contact
                        INNER JOIN
                    messages_order ON contact.key_remote_id = messages_order.seller_jid
                        LEFT JOIN
                    order_status ON messages_order.id_order_status = order_status.id_order_status
                WHERE
                    contact.deleted = 1 AND contact.spam = 1 AND (contact.key_remote_id LIKE '%$text%'
                        OR messages_order.order_id LIKE '%$text%'
                        OR order_status.name LIKE '%$text%'
                        OR DATE_FORMAT(FROM_UNIXTIME(messages_order.creation),'%d/%m/%Y %H:%i') LIKE '%$text%');\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function GetItems($id, $order, $orderType)
    {
        $sql = "select\n";
        $sql .= "messages_order_product.id_messages_order_product,\n";
        $sql .= "messages_order_product.code,\n";
        $sql .= "messages_order_product.name,\n";
        $sql .= "messages_order_product.quantity,\n";
        $sql .= "messages_order_product.currency,\n";
        $sql .= "messages_order_product.price,\n";
        $sql .= "FORMAT(messages_order_product.quantity*messages_order_product.price/1000,2) total\n";
        $sql .= "from\n";
        $sql .= "messages_order_product\n";
        $sql .= "where messages_order_product.id_messages_order = $id\n";

        switch ($order) {
            case 1:
                $sql .= "order by messages_order_product.name $orderType\n";
                break;
            case 2:
                $sql .= "order by messages_order_product.quantity $orderType\n";
                break;
            case 3:
                $sql .= "order by messages_order_product.price $orderType\n";
                break;
            default:
                $sql .= "order by messages_order_product.code $orderType";
        }

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function GetInfoItems($id)
    {
        $sql = "select\n";
        $sql .= "messages_order_product.id_messages_order_product,\n";
        $sql .= "messages_order_product.code,\n";
        $sql .= "messages_order_product.name,\n";
        $sql .= "messages_order_product.quantity,\n";
        $sql .= "messages_order_product.currency,\n";
        $sql .= "messages_order_product.price\n";
        $sql .= "from messages_order_product\n";
        $sql .= "where messages_order_product.id_messages_order_product = '$id'\n";

        $result = $this->db->query($sql);

        return $result->result_array()[0];
    }


    function GetInf($order_id)
    {
        $sql = "SELECT\n";
        $sql .= "messages_order.id_messages_order,\n";
        $sql .= "DATE_FORMAT(FROM_UNIXTIME(messages_order.creation),'%d/%m/%Y %H:%i') creation,\n";
        $sql .= "contact.full_name,\n";
        $sql .= "contact.key_remote_id,\n";
        $sql .= "channel.id,\n";
        $sql .= "channel.name,\n";
        $sql .= "messages_order.id_payment_method,\n";
        $sql .= "messages_order.id_order_status,\n";
        $sql .= "messages_order.item_count,\n";
        $sql .= "messages_order.key_id,\n";
        $sql .= "messages_order.message,\n";
        $sql .= "messages_order.order_id,\n";
        $sql .= "messages_order.seller_jid,\n";
        $sql .= "messages_order.total,\n";
        $sql .= "FORMAT(messages_order.subtotal, 'G', 'pt-br') as subtotal,\n";
        $sql .= "messages_order.postal,\n";
        $sql .= "messages_order.address,\n";
        $sql .= "messages_order.number,\n";
        $sql .= "messages_order.district,\n";
        $sql .= "messages_order.city,\n";
        $sql .= "messages_order.complement,\n";
        $sql .= "messages_order.distance,\n";
        $sql .= "messages_order.distance_time,\n";
        $sql .= "messages_order.token\n";
        $sql .= "FROM\n";
        $sql .= "messages_order inner join contact on messages_order.seller_jid = contact.key_remote_id\n";
        $sql .= "inner join channel on contact.id_channel = channel.id_channel\n";
        $sql .= "WHERE\n";
        $sql .= "contact.spam = 1 and contact.deleted = 1 and\n";
        $sql .= "messages_order.id_messages_order = '$order_id'\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function CountItems($order_id)
    {
        $sql = "select count(messages_order_product.id_messages_order_product) count\n";
        $sql .= "from\n";
        $sql .= "messages_order_product\n";
        $sql .= "where messages_order_product.id_messages_order = '$order_id'\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function Edit($key_id, $data)
    {
        $values = [
            'id_order_status' => $data['input-order-status'],
            'id_payment_method' => $data['input-payment-method'],
            'postal' => $data['input-postal'],
            'address' => $data['input-address'],
            'number' => $data['input-number'],
            'district' => $data['input-district'],
            'city' => $data['input-city'],
            'complement' => $data['input-complement'],
            'distance' => $data['input-distance'],
            'distance_time' => $data['input-distance-time'],
        ];

        $this->db->where('id_messages_order', $key_id);
        $this->db->update('messages_order', $values);
    }


    function Delete($key_id)
    {
        $this->db->where('id_messages_order', $key_id);
        $this->db->delete('messages_order');
    }


    function DeleteItems($key_id)
    {
        $this->db->where('id_messages_order_product', $key_id);
        $this->db->delete('messages_order_product');
    }


    function ExportCsv($text, $status, $dt_start, $dt_end)
    {
        $sql = "select\n";
        $sql .= "DATE_FORMAT(from_unixtime(messages_order.creation),'%d/%m/%Y %H:%i') creation,\n";
        $sql .= "messages_order.order_id,\n";
        $sql .= "messages_order.seller_jid,\n";
        $sql .= "order_status.name name_status,\n";
        $sql .= "order_status.color\n";
        $sql .= "from contact \n";
        $sql .= "inner join messages_order on contact.key_remote_id = messages_order.seller_jid\n";
        $sql .= "left join order_status on messages_order.id_order_status = order_status.id_order_status\n";
        $sql .= "where contact.deleted = 1 and contact.spam = 1\n";
        $sql .= "and (contact.key_remote_id like '%" . trim($text) . "%'\n";
        $sql .= "or messages_order.order_id like '%" . trim($text) . "%'\n";
        $sql .= "or order_status.name like '%" . trim($text) . "%'\n";
        $sql .= "or DATE_FORMAT(from_unixtime(messages_order.creation),'%d/%m/%Y %H:%i') like '%" . trim($text) . "%')\n";

        if (trim($status) != "") {
            $sql .= "AND order_status.id_order_status in ($status)\n";
        }

        if (trim($dt_start) != "") {
            $sql .= "AND from_unixtime(messages_order.creation) BETWEEN '{$dt_start}' AND '{$dt_end}'";
        }

        $result = $this->db->query($sql);

        return $result->result_array();
    }
}
