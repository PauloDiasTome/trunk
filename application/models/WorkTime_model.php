<?php

class WorkTime_model extends TA_model
{
    public function __construct()
    {
        parent::__construct();
    }

    function Get($text, $start, $length, $order_column, $order_dir)
    {
        $this->db->select('*');
        $this->db->from('work_time');
        $this->db->where('work_time.status', 1);
        $this->db->like('work_time.name', $this->db->escape_like_str($text));

        switch ($order_column) {
            case 0:
                $this->db->order_by("work_time.name", $order_dir);
                break;
        }

        $this->db->limit($length, $start);

        $query = $this->db->get('');

        return $query->result_array();
    }

    function Count($text)
    {
        $this->db->select('count(work_time.id_work_time) count');
        $this->db->from('work_time');
        $this->db->where('work_time.status', 1);
        $this->db->like('work_time.name', $this->db->escape_like_str($text));

        $query = $this->db->get('');

        return $query->result_array();
    }

    function GetById($id_work_time)
    {
        $this->db->select('*');
        $this->db->from('work_time');
        $this->db->where('work_time.id_work_time', $id_work_time);

        $query = $this->db->get('');

        return $query->result_array();
    }

    function List()
    {
        $this->db->select('*');
        $this->db->from('work_time');
        $this->db->where("work_time.status", 1);
        $this->db->order_by("work_time.name", "asc");


        $query = $this->db->get('');

        return $query->result_array();
    }

    function WorkTimeWeekList($id_work_time)
    {
        $this->db->select('*');
        $this->db->from('work_time_week');
        $this->db->where('work_time_week.id_work_time', $id_work_time);

        $query = $this->db->get('');

        return $query->result_array();
    }

    function UpdateWorkTime($id_work_time, $new, $old = null, $name = null)
    {
        if ($old != null) {
            foreach ($old as $row) {
                $this->db->delete('work_time_week', array(
                    'id_work_time_week' => $row['id_work_time_week'],
                    'id_work_time' => $id_work_time
                ));
            }
        }

        if (count($new) > 0) {
            foreach ($new as &$row) {
                $row['id_work_time'] = $id_work_time;
                $this->db->insert('work_time_week', $row);
            }
        }

        if ($name != null) {
            $now = new DateTime();
            $this->db->set('name', $name);
            $this->db->set('creation', $now->getTimestamp());
            $this->db->where('id_work_time', $id_work_time);
            $this->db->update('work_time');
        }
    }

    function AddWorkTime($nome)
    {
        $now = new DateTime();

        $data = array(
            'name' => $nome,
            'creation' => $now->getTimestamp()
        );

        $this->db->insert('work_time', $data);

        return $this->db->insert_id();
    }

    function UserList($id_work_time)
    {
        $this->db->select('user.id_work_time,user.name');
        $this->db->from('user');
        $this->db->where('user.id_work_time', $id_work_time);
        $this->db->where('user.status', 1);

        $query = $this->db->get('');

        return $query->result_array();
    }

    function ChannelList($id_work_time)
    {
        $this->db->select('channel.id_work_time, channel.name');
        $this->db->from('channel');
        $this->db->where('channel.id_work_time', $id_work_time);
        $this->db->where('channel.status', 1);

        $query = $this->db->get('');

        return $query->result_array();
    }

    function DeleteWorkTime($id_work_time)
    {
        $channelList = $this->ChannelList($id_work_time);
        $userList = $this->UserList($id_work_time);

        if ($channelList == null && $userList == null) {
            $this->db->where('id_work_time', $id_work_time);
            $this->db->update('work_time', array('status' => 2));
        } else {
            if ($userList != null) {
                return $userList;
            } else {
                return $channelList;
            }
        }
    }
}
