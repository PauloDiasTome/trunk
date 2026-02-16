<?php

use yidas\queue\worker\Controller as WorkerController;

class ServiceController extends WorkerController {

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('path');
        $this->logPath = set_realpath('log/talkall.log');

        echo "Log path {$this->logPath}";
        
    }

    public $workerSleep = 1;
    public $workerWaitSeconds = 1;


    // Initializer
    protected function init() {
        $this->load->model('Job_model', '', TRUE);
    }

    // AQUI AONDE RODA TODOS OS PROCESSOS
    // ESSE É O WORKER PRINCIPAL
    protected function handleWork()
    {

        $data = date('d/m/y h:m:s');
        echo "$data :: Runing Database's Job's ... \n";
        $this->Job_model->MasterJob();
        echo "$data :: Runing Webhook's Job's ... \n";
        return $this->Job_model->WebhooksJob();
    }

    // ISSO AQUI FAZ O WORKER SEMPRE FICAR EXECUTANDO
    protected function handleListen() {
        return true;
    }
        
}
