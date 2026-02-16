<?php

class ShortLinkLog_model extends CI_Model
{
    function Log($data)
    {
        $sql = "select\n";
        $sql .= "company.id_company,\n";
        $sql .= "company.db,\n";
        $sql .= "company.server\n";
        $sql .= "from company inner join link on company.id_company = link.id_company\n";
        $sql .= "where company.status = 1 and link.link = '" . $data['link'] . "'\n";

        $this->db = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), true);

        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {

            $this->db = $this->load->database(SetdatabaseRemote($result->row()->db, $result->row()->server), TRUE);

            // $sql = "select\n";
            // $sql .= "short_link.id_short_link,\n";
            // $sql .= "short_link.phone,\n";
            // $sql .= "short_link.message\n";
            // $sql .= "from work_time\n";
            // $sql .= "inner join channel on work_time.id_work_time = channel.id_work_time\n";
            // $sql .= "inner join config on channel.id_channel = config.id_channel\n";
            // $sql .= "inner join work_time_week on work_time.id_work_time = work_time_week.id_work_time\n";
            // $sql .= "inner join user on work_time.id_work_time = user.id_work_time\n";
            // $sql .= "inner join short_link on user.id_user = short_link.id_user\n";
            // $sql .= "where user.status = 1 and short_link.link = '" . $data['link'] . "'\n";
            //$sql .= "and work_time_week.week = '" . date('N') . "' and DATE_ADD(current_time(), INTERVAL config.timezone HOUR) between work_time_week.start and work_time_week.end\n";

            $sql = "SELECT 
                        *
                    FROM
                        short_link
                    WHERE
                        short_link.link = '" . $data['link'] . "'\n";

            $result = $this->db->query($sql);

            if ($result->num_rows() > 0) {

                $date = new DateTime();

                $message = $result->result_array()[0]['message'];

                $values = [
                    'id_short_link' =>  $result->result_array()[0]['id_short_link'],
                    'creation' => $date->getTimestamp(),
                    'agent' => $this->agent->browser(),
                    'hostname' => isset($data['hostname']) ? $data['hostname'] : null,
                    'country' => isset($data['country']) ? $data['country'] : null,
                    'region' => isset($data['region']) ? $data['region'] : null,
                    'browser_version' => $this->agent->version(),
                    'is_mobile' => ($this->agent->mobile() != "" ? 2 : 1),
                    'city' => isset($data['city']) ? $data['city'] : null,
                    'device_version' => ($this->agent->mobile() != "" ? $this->agent->mobile() : $this->agent->platform()),
                    'timezone' => isset($data['timezone']) ? $data['timezone'] : null,
                    'org' => isset($data['org']) ? $data['org'] : null,
                    'ip' => isset($data['ip']) ? $data['ip'] : null,
                    'is_facebook' => isset($data['is_facebook']) ? $data['is_facebook'] : null
                ];

                $this->db->insert('short_link_log', $values);

                $phones = explode(",", $result->result_array()[0]['phone']);

                if (strpos($phones[0], "@") == 0) {

                    if (count($phones) > 1) {

                        $filter = "";

                        for ($i = 0; $i < count($phones); $i++) {
                            if ($i == 0) {
                                $filter = "'" . $phones[$i] . "'";
                            } else {
                                $filter .= ",'" . $phones[$i] . "'";
                            }
                        }

                        $sql = "select count(contact.id_contact),channel.id wa from contact inner join channel on contact.id_channel = channel.id_channel\n";
                        $sql .= "where contact.deleted = 1 and contact.spam = 1 and contact.is_private = 1 and channel.type = 2\n";
                        $sql .= "and channel.id in($filter) and channel.status = 1\n";
                        $sql .= "group by channel.id_channel\n";
                        $sql .= "having count(contact.id_contact) < 10000\n";
                        $sql .= "order by count(contact.id_contact) asc\n";
                        $sql .= "limit 1\n";

                        $result = $this->db->query($sql);

                        if ($result->num_rows() > 0) {
                            if ($this->agent->mobile() != "") {
                                echo "https://wa.me/" . $result->row()->wa . "?text=" . $message;
                            } else {
                                echo 'https://web.whatsapp.com/send?phone=' . $result->row()->wa . "&text=" . $message;
                            }
                        } else {
                            echo "..";
                        }
                    } else {

                        if ($this->agent->mobile() != "") {
                            echo "https://wa.me/" . $result->result_array()[0]['phone'] . "?text=" . $message;
                        } else {
                            echo 'https://web.whatsapp.com/send?phone=' . $result->result_array()[0]['phone'] . "&text=" . $message;
                        }
                    }
                } else {

                    if (strpos($phones[0], "@newsletter") > 0) {

                        $sql = "select concat('https://whatsapp.com/channel/',newsletter.invite_code) link FROM newsletter where newsletter.key_remote_id = '" . $phones[0] . "'\n";

                        $result = $this->db->query($sql);

                        if ($result->num_rows() > 0) {
                            echo $result->row()->link;
                        } else {
                            echo "..";
                        }
                    } else {

                        $filter = "";

                        for ($i = 0; $i < count($phones); $i++) {
                            $phone = trim($phones[$i]);
                            if ($i == 0) {
                                $filter = "'" . $phone . "'";
                            } else {
                                $filter .= ",'" . $phone . "'";
                            }
                        }

                        $sql = "SELECT 
                                    community.link,
                                    COUNT(community_participant.id_community_participant) AS total_participants
                                FROM
                                    community
                                        INNER JOIN
                                    community_participant ON community_participant.id_community = community.id_community
                                WHERE
                                    community.key_remote_id IN ($filter) AND community.status = 1
                                GROUP BY community.id_community HAVING total_participants < 2000
                                ORDER BY total_participants ASC LIMIT 1";

                        $result = $this->db->query($sql);

                        if ($result->num_rows() > 0) {
                            echo $result->row()->link;
                        } else {
                            echo "..";
                        }
                    }
                }
            } else {
                echo "..";
            }
        } else {
            echo "..";
        }
    }


    function Get($link)
    {
        $sql = "select\n";
        $sql .= "company.id_company,\n";
        $sql .= "company.db,\n";
        $sql .= "company.server\n";
        $sql .= "from company inner join link on company.id_company = link.id_company\n";
        $sql .= "where company.status = 1 and link.link = '" . $link . "'\n";

        $this->db = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), TRUE);
        $result = $this->db->query($sql);

        if ($result->num_rows() > 0) {

            $this->db = $this->load->database(SetdatabaseRemote($result->row()->db, $result->row()->server), TRUE);

            $sql = "select\n";
            $sql .= "short_link.title,\n";
            $sql .= "short_link.description,\n";
            $sql .= "user.key_remote_id\n";
            $sql .= "from user inner join short_link on user.id_user = short_link.id_user \n";
            $sql .= "where user.status = 1 and short_link.status = 1 and short_link.link = '" . $link . "'\n";

            $result = $this->db->query($sql);

            return $result->result_array()[0];
        }
    }
}
