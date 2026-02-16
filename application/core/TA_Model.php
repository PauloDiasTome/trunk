<?php

class TA_Model extends CI_Model
{
    public $User_id;
    private $table = null;
    private $primary = null;
    private $order = null;
    private $where = null;

    public function __construct()
    {
        parent::__construct();

        if ($this->session->userdata('db') != null) {
            $this->db = $this->load->database(SetdatabaseRemote($this->session->userdata('db'), $this->session->userdata('host')), TRUE);
            $this->User_id = $this->session->userdata('id_user');
        }
    }

    public function SetTalkall()
    {
        $this->db = $this->load->database(SetdatabaseRemote("talkall_admin", "192.168.190.40"), TRUE);
        $this->User_id = $this->session->userdata('id_user_talkall_admin');
    }

    public function SetClient()
    {
        if( $this->session->userdata('db') == null ){
            $this->db = $this->load->database(SetdatabaseRemote($this->session->userdata('db'), "192.168.190.40"), TRUE);
        } else {
            $this->db = $this->load->database(SetdatabaseRemote($this->session->userdata('db'), $this->session->userdata('host')), TRUE);
        }
    }

    public function SetRemoteDatabase($host, $db)
    {
        $this->db = $this->load->database(SetdatabaseRemote($db, $host), TRUE);
    }

    public function SetTableName($table)
    {
        $this->table = $table;
    }

    public function SetPrimaryKey($primary)
    {
        $this->primary = $primary;
    }

    public function SetListOrderBy($order = NULL)
    {
        $this->order = $order;
    }

    public function SetListWhere($where = NULL)
    {
        $this->where = $where;
    }

    function _List()
    {
        $this->db->select(
            $this->table . '.' . $this->primary . ',' .
                $this->table . '.name'
        );

        $this->db->from($this->table);

        if ($this->where != NULL) {
            foreach ($this->where as $condition) {
                if (!empty($condition)) {
                    $this->db->where($this->table . '.' . $condition[0], $condition[1]);
                }
            }
        }

        if ($this->order != NULL) {
            $this->db->order_by($this->table . '.' . $this->order[0], $this->order[1]);
        }

        return $this->db->get()->result_array();
    }

    function _GetById($key_id)
    {
        $this->db->select('*');
        $this->db->from($this->table);
        $this->db->where($this->table . '.' . $this->primary, $key_id);

        return $this->db->get()->result_array()[0];
    }

    function _Insert($values)
    {
        $return = $this->db->insert($this->table, $values);

        if ($return) {
            return $this->db->insert_id();
        }

        return false;
    }

    function _Update($key_id, $values)
    {
        $this->db->where($this->primary, $key_id);
        $this->db->update($this->table, $values);
    }

    function _Delete($key_id)
    {
        $this->db->where($this->primary, $key_id);
        $this->db->delete($this->table);
    }
}
