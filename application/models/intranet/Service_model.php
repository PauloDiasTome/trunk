<?php

class Service_model extends TA_model
{
    public function __construct()
    {
        parent::__construct();
        parent::SetTalkall();
    }

    function Count($fields)
    {
        $text = $fields['text'];
        $dtstart = $fields['dt-start'];
        $dtend = $fields['dt-end'];

        $sql = "SELECT 
                    count(id_service) count
                FROM
                    service
                    LEFT JOIN (SELECT 1 AS id, 'ativo' AS description) AS active ON(active.id = service.status)
                    LEFT JOIN (SELECT 2 AS id, 'inativo' AS description) AS inactive ON(inactive.id = service.status)
                WHERE
                    (from_unixtime(creation,'%Y-%m-%d') between '$dtstart' and '$dtend')
                    AND (name like '%$text%' OR service.description like '%$text%' OR price = '$text' OR active.description = LOWER('$text') OR inactive.description = LOWER('$text'))\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }

    function Get($fields)
    {
        $text = $fields['text'];
        $start = $fields['start'];
        $length = $fields['length'];
        $dtstart = $fields['dt-start'];
        $dtend = $fields['dt-end'];
        $order = $fields['order'][0];
        $columns = $fields['columns'];

        $sql = "SELECT 
                    id_service,
                    from_unixtime(creation,'%d/%m/%Y') creation,
                    name,
                    service.description,
                    price,
                    status
                FROM
                    service
                    LEFT JOIN (SELECT 1 AS id, 'ativo' AS description) AS active ON(active.id = service.status)
                    LEFT JOIN (SELECT 2 AS id, 'inativo' AS description) AS inactive ON(inactive.id = service.status)
                WHERE
                    (from_unixtime(creation,'%Y-%m-%d') between '$dtstart' and '$dtend')
                    AND (name like '%$text%' OR service.description like '%$text%' OR price = '$text' OR active.description = LOWER('$text') OR inactive.description = LOWER('$text'))
                ORDER BY 
                    service.{$columns[$order['column']]['data']} {$order['dir']}
                LIMIT 
                    {$start},{$length}";

        $result = $this->db->query($sql);

        return $result->result_array();
    }

    function Edit($id_service, $data)
    {
        $values = [
            'name'  => $data['name'],
            'description'  => $data['description'],
            'price'  => $data['price'],
            'status'  => $data['status']
        ];

        $this->db->where('id_service', $id_service);
        $this->db->update('service', $values);
    }

    function Add($data)
    {
        $date = new DateTime();
        $t = $date->getTimestamp();

        $values = [
            'creation' => $t,
            'name'  => $data['name'],
            'description'  => $data['description'],
            'price'  => $data['price'],
            'status'  => $data['status']
        ];

        $this->db->insert('service', $values);
    }

    function List()
    {
        $sql = "select\n";
        $sql .= "(id_service),\n";
        $sql .= "name\n";
        $sql .= "from talkall_admin.service\n";
        $sql .= "where status = 1\n";
        $sql .= "order by talkall_admin.service.name\n";

        $result = $this->db->query($sql);

        $data = $result->result_array();

        return $data;
    }

    function GetById($id_service)
    {
        $sql = "SELECT 
                    id_service,
                    from_unixtime(creation,'%d/%m/%Y') creation,
                    name,
                    description,
                    price,
                    status
                FROM
                    service
                WHERE
                    id_service = $id_service";

        $result = $this->db->query($sql);

        return $result->first_row();
    }
}
