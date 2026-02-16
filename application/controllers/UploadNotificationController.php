<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class UploadNotificationController extends TA_Controller
{
    public function __construct()
    {
        parent::__construct();
    }


    function Index()
    {

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $this->Upload();
        } else if ($this->input->server('REQUEST_METHOD') == 'DELETE') {
            $this->Delete();
        } else if ($this->input->server('REQUEST_METHOD') == 'GET') {
            if (isset($_GET['user_id']) and $_GET['user_id'] != '') {
                $this->Get();
            } else {
                echo "Error 404";
            }
        } else {
            echo "Error";
        }
    }


    function Get()
    {
        $this->load->model('NotificatioAlert_model', '', TRUE);
        $url_sound = $this->NotificatioAlert_model->Get($_GET['user_id']);

        echo json_encode([
            'status' => 200,
            'media_mime_type' => "audio/wav",
            'media_url' => $url_sound[0]['notification_alert_url'],
        ]);
    }


    function Upload()
    {
        $sound_info = '';
        $extension = '';
        $media_file = '';

        if (isset($_FILES['arq']) and $_FILES['arq'] != '') {
            $path = "notification/";
            $tempFile = $_FILES['arq']['tmp_name'];

            if (!is_dir($path)) {
                mkdir($path);
            }
            if (file_exists($path . $_FILES['arq']['name'])) {
                unlink($path . $_FILES['arq']['name']);
            }

            $type = explode('.', $_FILES['arq']['name']);
            $extension = end($type);
            $file = $path . $_GET['user_id'] . '_' . uniqid() . '.' . $extension;
            move_uploaded_file($tempFile, $file);

            $sound_info = $file;

            if (isset($_GET['user_id'])) {

                $media_file = base_url() . $sound_info;

                $this->load->model('NotificatioAlert_model', '', TRUE);
                $this->NotificatioAlert_model->Update($_GET['user_id'], $media_file);
            }
        }
        echo json_encode([
            'status' => 200,
            'media_caption' => $sound_info,
            'media_mime_type' => "audio/{$extension}",
            'media_url' => $media_file,
            'base64' => "data:audio/{$extension};base64," . base64_encode($sound_info),
        ]);
    }

    
    function Delete()
    {
        $path = "notification/";

        if (isset($_GET['path']) and $_GET['path'] != '') {
            $path = $_GET['path'];
        }

        if (isset($_GET['alert'])) {
            $file = explode('/', str_replace(' ', '+', $_GET['alert']));
            try {
                if (is_file($path . end($file))) {
                    unlink($path . end($file));

                    if (isset($_GET['user_id'])) {

                        $this->load->model('NotificatioAlert_model', '', TRUE);
                        $this->NotificatioAlert_model->Delete($_GET['user_id']);
                    }

                    echo "deleted";
                } else {
                    echo "Not found";
                }
            } catch (\Throwable $th) {
                echo "Error {$th}";
            }
        } else {
            echo "The tag: 'img' cannot be empty";
        }
    }
}
