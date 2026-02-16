<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class FeedbackController extends TA_Controller
{
    
    public function __construct()
    {
        parent::__construct();
        parent::checkPermission("feedback");
    }

    function get()
    {

        $this->load->model('Feedback_model', '', TRUE);

        $query = $this->Feedback_model->get();

        foreach ($query as &$row) {
            $path = "profile/" . $row['key_remote_id'] . "@s.whatsapp.net.jpeg";
            if (file_exists($path) == true) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $row['profile'] = 'data:image/' . $type . ';base64,' . base64_encode($data);
            } else {
                $row['profile'] = base_url() . "profile/profile.svg";
            }
            $row['messages'] = $this->Feedback_model->getMessages($row['id_chat_list']);
        }

        $data  = array(
            "title" => "TalkAll | Feedback",
            "data" => $query
        );

        $this->load->view('templates/header', $data);
        $this->load->view('sidenav/sidenav', $data);
        $this->load->view('topnav/topnav', $data);
        $this->load->view('pages/feedback/find', $data);
        $this->load->view('templates/footer', array('page' => 'feedback'));
    }
}
