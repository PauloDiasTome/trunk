<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function json_response($CI, $status, $data) {
    return $CI->output
        ->set_content_type('application/json')
        ->set_status_header($status)
        ->set_output(json_encode($data));
}