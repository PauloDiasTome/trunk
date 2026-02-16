<?php

class Channel_model extends TA_model
{

    function GetToken($bot_id)
    {
        $sql = "select\n";
        $sql .= "channel.id,\n";
        $sql .= "channel.pw\n";
        $sql .= "from company inner join channel on company.id_company = channel.id_company\n";
        $sql .= "where channel.id = '$bot_id'\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function List()
    {
        $sql = "select\n";
        $sql .= "channel.id_channel,\n";
        $sql .= "channel.id,\n";
        $sql .= "channel.name\n";
        $sql .= "from channel\n";
        $sql .= "where channel.status = 1 and channel.type in(2);\n";

        $result = $this->db->query($sql);

        return $result->result_array();
    }


    function ListChannelAvailableWithStatus($type)
    {
        $sql = "SELECT 
                    channel.id_channel,
                    channel.id,
                    channel.status,
                    CONCAT(channel.name,
                            ' (',
                            REPLACE(channel.id, '@c.us', ''),
                            ')') name,
                    CASE
                        WHEN channel.type = 2 THEN 'WhatsApp Broadcast'
                        WHEN channel.type = 4 THEN 'WhatsApp Status'
                    END type
                FROM
                    channel
                WHERE
                    channel.type = '$type' AND channel.status != 2
                order by 0 + channel.name";

        $result = $this->db->query($sql);

        return $result->result_array();
    }
}
