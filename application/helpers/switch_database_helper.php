<?php

defined('BASEPATH') || exit('No direct script access allowed');

function Setdatabase($database)
{
    return array(
        'dsn'    => "mysql:host=192.168.129.114;dbname=$database",
        // 'dsn'    => "mysql:app.talkall.com.br;dbname=$database",
        'hostname' => '192.168.129.114',
        // 'hostname' => 'app.talkall.com.br',
        'username' => 'root',
        'password' => 'My@dm1nH0m$',
        // 'password' => 'My@dm1ng3ltdb$',
        'database' =>  $database,
        'dbdriver' => 'mysqli',
        'dbprefix' => '',
        'pconnect' => FALSE,
        'db_debug' => (ENVIRONMENT !== 'production'),
        'cache_on' => FALSE,
        'cachedir' => (FCPATH . 'db-cache'),
        'char_set' => 'utf8mb4',
        'dbcollat' => 'utf8mb4_unicode_ci',
        'swap_pre' => '',
        'encrypt' => FALSE,
        'compress' => TRUE,
        'stricton' => FALSE,
        'failover' => array(),
        'save_queries' => TRUE
    );
}

function SetdatabaseRemote($database, $host)
{
    return array(
        'dsn' => "mysql:host=192.168.129.114;dbname=$database",
        // 'dsn' => "mysql:host=app.talkall.com.br;dbname=$database",
        'hostname' => '192.168.129.114',
        // 'hostname' => 'app.talkall.com.br',
        'username' => 'root',
        // 'password' => 'My@dm1ng3ltdb$',
        'password' => 'My@dm1nH0m$',
        'database' => $database,
        'dbdriver' => 'mysqli',
        'dbprefix' => '',
        'pconnect' => FALSE,
        'db_debug' => (ENVIRONMENT !== 'production'),
        'cache_on' => FALSE,
        'cachedir' => (FCPATH . 'db-cache'),
        'char_set' => 'utf8mb4',
        'dbcollat' => 'utf8mb4_unicode_ci',
        'swap_pre' => '',
        'encrypt' => FALSE,
        'compress' => TRUE,
        'stricton' => FALSE,
        'failover' => array(),
        'save_queries' => TRUE
    );
}
