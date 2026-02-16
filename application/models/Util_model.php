<?php

class Util_model extends TA_model
{
    function List($where = NULL, $not_like = NULL, $order = NULL)
    {
        $this->db->select(
            $this->table . '.' . $this->primary,
            $this->table . '.name',
        );

        $this->db->from($this->table);

        return $this->db->get()->result_array();
    }

    function GetById($key_id, $table = NULL, $primary = NULL)
    {
        $this->db->select('*');

        if ($table != NULL) {
            // se a função for chamada do controller 
            $this->db->from($table);
            $this->db->where($table . '.' . $primary, $key_id);
        } else {
            // se a função for chamada do model 
            $this->db->from($this->table);
            $this->db->where($this->table . '.' . $this->primary, $key_id);
        }

        return $this->db->get()->result_array()[0];
    }

    function Insert($values)
    {
        $return = $this->db->insert($this->table, $values);

        if ($return) {
            return $this->db->insert_id();
        }
    }

    function Update($key_id, $values)
    {
        $this->db->where($this->primary, $key_id);
        $this->db->update($this->table, $values);
    }

    function Delete($key_id)
    {
        $this->db->where($this->primary, $key_id);
        $this->db->delete($this->table);
    }
}
