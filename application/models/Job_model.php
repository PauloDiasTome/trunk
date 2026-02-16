<?php

class Job_model extends CI_model
{
    public $now;

    public function __construct()
    {
        parent::__construct();
        ini_set('max_execution_time', 0);

        $this->now = new DateTime();
    }

    // ESSE É JOB PRINCIPAL
    public function MasterJob(): bool
    {
        $sql = "SELECT db FROM company where status = 1";
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            foreach ($data as $banco) {
                $this->CrmJob($banco['db']);
                sleep(1);
                $this->ClearCache($banco['db']);
            }
        }

        return true;
    }


    public function CrmJob($banco)
    {
        $DB = $this->load->database(Setdatabase($banco), TRUE);

        $sql = "SELECT * FROM channel where type in (13,14,15) limit 1";

        $query = $DB->query($sql);

        if ($query->num_rows() > 0) {
            $integration = $query->first_row();

            switch ((int) $integration->type) {
                case 13:
                    $this->load->model('RdStation_model', '', TRUE);
                    $this->RdStation_model->init($DB);
                    $this->RdStation_model->SyncContacts();
                    $DB->close();
                    break;
                case 14:
                    $this->load->model('Hubspot_model', '', TRUE);
                    $this->Hubspot_model->init($DB);
                    $this->Hubspot_model->SyncContacts();
                    $DB->close();
                    break;
                case 15:
                    $this->load->model('Zendesk_model', '', TRUE);
                    $this->Zendesk_model->init($DB);
                    $this->Zendesk_model->SyncContacts();
                    $DB->close();
                    break;
                default:
                    break;
            }
        }
        $DB->close();
    }


    public function ClearCache($banco)
    {

        $DB = $this->load->database(Setdatabase($banco), TRUE);

        $date = new DateTime();
        $diff = $date->diff($this->now);

        // Executa a função que limpa todos os cache.

        if ($diff->i == 30) {
            $this->now =  new DateTime();
            $DB->cache_delete_all();
            echo "\n cache limpado \n";
        }

        $DB->close();
    }


    public function WebhooksJob()
    {
        #  Programmer Disclaimer
        // EU SEI QUE ISSO NÃO É UMA BOA PRATICA !
        // QUANDO ISSO ESTIVER DANDO PROBLEMA UTILIZE O RABBITMQ

        $this->db = $this->load->database(Setdatabase("talkall_admin"), TRUE);

        $sql = "SELECT id_webhook, json, url  FROM webhook where status = 1 limit 50";
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0) {
            $webhook = $query->result_array();

            foreach ($webhook as $json) {
                if ($this->ExternalRequest($json['url'], $json['json'])) {
                    $this->db->set('status', 2);
                    $this->db->where('id_webhook', $json['id_webhook']);
                    $this->db->update('webhook');
                }
            }
        }
        return true;
    }


    private function ExternalRequest($url, $data)
    {
        if ($url === null || $data === null) {
            return false;
        }

        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 3,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json"
                ),
            ));

            curl_exec($curl);
            $err = curl_error($curl);

            if ($err) {
                echo "cURL Error #: " . $err . "\n";
                return false;
            } else {
                return true;
            }

            curl_close($curl);
        } catch (Exception $e) {
            echo "cURL Error #:" . $url . "\n";
            echo "cURL Error #:" . $e->getMessage()  . "\n";
            return false;
        }
    }
}
