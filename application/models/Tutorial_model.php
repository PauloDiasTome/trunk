<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Tutorial_model extends CI_Model
{
    private $table = 'onboarding_status';

    public function getStep($company_id)
    {
        return $this->db
            ->where('company_id', $company_id)
            ->get($this->table)
            ->row_array();
    }

    public function saveStep($data)
    {
        $exists = $this->db
            ->where('company_id', $data['company_id'])
            ->get($this->table)
            ->row();

        if ($exists) {

            if ((int) $exists->status === 3) {
                return;
            }

            $this->db
                ->where('company_id', $data['company_id'])
                ->update($this->table, [
                    'status'       => $data['status'],
                    'current_step' => $data['current_step']
                ]);
        } else {
            $this->db->insert($this->table, $data);
        }
    }
}
