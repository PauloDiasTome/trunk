<?php

class ReportContact_model extends TA_model
{

    function Get($text, $start, $length, $order_column, $order_dir)
    {
        $sql =  "SELECT 
                    *,
                    CONCAT(ROUND((count_month / count_contact * 100), 2), '%') AS p_month,
                    CONCAT(ROUND((count_day / count_contact * 100), 2), '%') AS p_day
                FROM
                    (SELECT DISTINCT
                        channel.name,
                            (SELECT 
                                    COUNT(contact.id_contact)
                                FROM
                                    contact
                                WHERE
                                    contact.id_channel = channel.id_channel) AS count_contact,
                            (SELECT 
                                    COUNT(contact.id_contact)
                                FROM
                                    contact
                                WHERE
                                    contact.id_channel = channel.id_channel
                                        AND FROM_UNIXTIME(contact.creation, '%m') = EXTRACT(MONTH FROM NOW())
                                        AND FROM_UNIXTIME(contact.creation, '%Y') = EXTRACT(YEAR FROM NOW())) AS count_month,
                            (SELECT 
                                    COUNT(contact.id_contact)
                                FROM
                                    contact
                                WHERE
                                    contact.id_channel = channel.id_channel
                                        AND FROM_UNIXTIME(contact.creation, '%Y-%m-%d') = DATE(NOW())) AS count_day,
                            (SELECT 
                                    COUNT(contact.id_channel)
                                FROM
                                    contact
                                WHERE
                                    contact.id_channel = channel.id_channel
                                        AND FROM_UNIXTIME(contact.creation, '%Y-%m-%d') <= DATE(DATE(NOW()) - 1)) AS a_day_ago,
                            (SELECT 
                                    COUNT(contact.id_channel)
                                FROM
                                    contact
                                WHERE
                                    contact.id_channel = channel.id_channel
                                        AND FROM_UNIXTIME(contact.creation, '%Y-%m-%d') <= DATE(DATE(NOW()) - 2)) AS two_day_ago,
                            (SELECT 
                                    COUNT(contact.id_channel)
                                FROM
                                    contact
                                WHERE
                                    contact.id_channel = channel.id_channel
                                        AND FROM_UNIXTIME(contact.creation, '%Y-%m-%d') <= DATE(DATE(NOW()) - 3)) AS three_day_ago,
                            (SELECT 
                                    COUNT(contact.id_channel)
                                FROM
                                    contact
                                WHERE
                                    contact.id_channel = channel.id_channel
                                        AND FROM_UNIXTIME(contact.creation, '%Y-%m-%d') <= DATE(DATE(NOW()) - 4)) AS four_day_ago,
                            (SELECT 
                                    COUNT(contact.id_channel)
                                FROM
                                    contact
                                WHERE
                                    contact.id_channel = channel.id_channel
                                        AND FROM_UNIXTIME(contact.creation, '%Y-%m-%d') <= DATE(DATE(NOW()) - 5)) AS five_day_ago,
                            (SELECT 
                                    COUNT(contact.id_channel)
                                FROM
                                    contact
                                WHERE
                                    contact.id_channel = channel.id_channel
                                        AND FROM_UNIXTIME(contact.creation, '%Y-%m-%d') <= DATE(DATE(NOW()) - 6)) AS six_day_ago,
                            (SELECT 
                                    COUNT(contact.id_channel)
                                FROM
                                    contact
                                WHERE
                                    contact.id_channel = channel.id_channel
                                        AND FROM_UNIXTIME(contact.creation, '%Y-%m-%d') <= DATE(DATE(NOW()) - 7)) AS seven_day_ago
                    FROM
                        contact
                    INNER JOIN channel ON contact.id_channel = channel.id_channel) AS query
                    WHERE name like '%$text%'";

        switch ($order_column) {
            case 0:
                $sql .= "order by channel.name $order_dir\n";
                break;

            case 1:
                $sql .= "order by count_contact $order_dir\n";
                break;

            case 2:
                $sql .= "order by count_month $order_dir\n";
                break;

            default:
                $sql .= "order by count_day $order_dir\n";
        }
        $sql .= " limit {$start},{$length}";

        $result = $this->db->query($sql);

        return  $result->result_array();
    }


    function Count($text)
    {
        $sql =  "SELECT 
                    count(*) as count
                FROM
                    (SELECT DISTINCT
                        channel.name,
                            (SELECT 
                                    COUNT(contact.id_contact)
                                FROM
                                    contact
                                WHERE
                                    contact.id_channel = channel.id_channel) AS count_contact,
                            (SELECT 
                                    COUNT(contact.id_contact)
                                FROM
                                    contact
                                WHERE
                                    contact.id_channel = channel.id_channel
                                        AND FROM_UNIXTIME(contact.creation, '%m') = EXTRACT(MONTH FROM NOW())
                                        AND FROM_UNIXTIME(contact.creation, '%Y') = EXTRACT(YEAR FROM NOW())) AS count_month,
                            (SELECT 
                                    COUNT(contact.id_contact)
                                FROM
                                    contact
                                WHERE
                                    contact.id_channel = channel.id_channel
                                        AND FROM_UNIXTIME(contact.creation, '%Y-%m-%d') = DATE(NOW())) AS count_day,
                            (SELECT 
                                    COUNT(contact.id_channel)
                                FROM
                                    contact
                                WHERE
                                    contact.id_channel = channel.id_channel
                                        AND FROM_UNIXTIME(contact.creation, '%Y-%m-%d') <= DATE(DATE(NOW()) - 1)) AS a_day_ago,
                            (SELECT 
                                    COUNT(contact.id_channel)
                                FROM
                                    contact
                                WHERE
                                    contact.id_channel = channel.id_channel
                                        AND FROM_UNIXTIME(contact.creation, '%Y-%m-%d') <= DATE(DATE(NOW()) - 2)) AS two_day_ago,
                            (SELECT 
                                    COUNT(contact.id_channel)
                                FROM
                                    contact
                                WHERE
                                    contact.id_channel = channel.id_channel
                                        AND FROM_UNIXTIME(contact.creation, '%Y-%m-%d') <= DATE(DATE(NOW()) - 3)) AS three_day_ago,
                            (SELECT 
                                    COUNT(contact.id_channel)
                                FROM
                                    contact
                                WHERE
                                    contact.id_channel = channel.id_channel
                                        AND FROM_UNIXTIME(contact.creation, '%Y-%m-%d') <= DATE(DATE(NOW()) - 4)) AS four_day_ago,
                            (SELECT 
                                    COUNT(contact.id_channel)
                                FROM
                                    contact
                                WHERE
                                    contact.id_channel = channel.id_channel
                                        AND FROM_UNIXTIME(contact.creation, '%Y-%m-%d') <= DATE(DATE(NOW()) - 5)) AS five_day_ago,
                            (SELECT 
                                    COUNT(contact.id_channel)
                                FROM
                                    contact
                                WHERE
                                    contact.id_channel = channel.id_channel
                                        AND FROM_UNIXTIME(contact.creation, '%Y-%m-%d') <= DATE(DATE(NOW()) - 6)) AS six_day_ago,
                            (SELECT 
                                    COUNT(contact.id_channel)
                                FROM
                                    contact
                                WHERE
                                    contact.id_channel = channel.id_channel
                                        AND FROM_UNIXTIME(contact.creation, '%Y-%m-%d') <= DATE(DATE(NOW()) - 7)) AS seven_day_ago
                                    
                    FROM
                        contact
                    INNER JOIN channel ON contact.id_channel = channel.id_channel) AS query
                    WHERE name like '%$text%'
                    ORDER BY name";

        $result = $this->db->query($sql);
        $result->result_array();

        return $result;
    }
}
