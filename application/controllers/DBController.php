<?php

defined('BASEPATH') or exit('No direct script access allowed');

class DBController extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('DB_model', '', TRUE);
    }

    function Index()
    {
        for ($i = 395; $i < 500; $i++) {

            if ($i != 297) {

                echo "db$i";

                $sqls = $this->DB_model->Get("db$i");

                foreach ($sqls as $sql) {

                    //echo $sql['sql1'] . "<br>";
                    $this->db->query($sql['sql3']);
                    $this->db->query($sql['sql1']);
                    $this->db->query($sql['sql2']);
                }
            }
        }
    }
}
