<?php
defined('BASEPATH') or exit('No direct script access allowed');

function logMessageErrors($error, $sql)
{
    if ($error['message']) {
        writeToLogFile($error, $sql);
        return false;
    } else {
        return true;
    }
}

function writeToLogFile($error, $sql)
{
    $CI = &get_instance();

    $logDir = APPPATH . '/logs/errors_mysql/';

    if (!is_dir($logDir)) {
        if (!mkdir($logDir, 0777, true) && !is_dir($logDir)) {
            error_log("Erro ao criar o diretório de logs.");
        }
    }

    $logFilePath = $logDir . date('Y-m-d') . '.txt';
    $logMessage = "DATE AND TIME: " . date('Y-m-d H:i:s') . PHP_EOL;
    $logMessage .= "COMPANY: " . $CI->session->userdata('id_company') . ' ' . $CI->session->userdata('db') . PHP_EOL;
    $logMessage .= "USER: " . $CI->session->userdata('id_user') . ' - ' . $CI->session->userdata('name') . ' - ' . $CI->session->userdata('key_remote_id') . PHP_EOL;
    $logMessage .= "REQUEST_URI: " .  $CI->uri->uri_string . PHP_EOL;
    $logMessage .= "CODE: " . $error['code'] . PHP_EOL;
    $logMessage .= "DATABASE ERROR MESSAGE: " . $error['message'] . PHP_EOL;
    $logMessage .= "INVALID QUERY:" . $sql . PHP_EOL . PHP_EOL;

    try {
        file_put_contents($logFilePath, $logMessage, FILE_APPEND);
    } catch (Exception $e) {
        error_log("Erro ao escrever no arquivo de log: " . $e->getMessage());
    }
}
