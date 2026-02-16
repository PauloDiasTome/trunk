<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function Token()
{  
    if (function_exists('com_create_guid') === true)
    {
        return trim(trim(com_create_guid(), '{}'), '-');
    }

    return sprintf('%04X%04X%04X%04X%04X%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}

function hiddenString($str, $start = 1, $end = 1)
{
    $len = strlen($str);
    return substr($str, 0, $start) . str_repeat('*', $len - ($start + $end)) . substr($str, $len - $end, $end);
}

function checkOrAddWaServer($val)
{
    return strpos($val, "@c.us") ? $val : $val. "@c.us";
}

// base_url da api requer autenticação
// o dominio que expõe os arquivos são o platform_domain
function public_base_url($val)
{
    $config =& get_config();
    return str_replace($config['api_domain'], $config['platform_domain'],base_url($val) );
}

function ReplaceSimbols($word)
{       
    $word = strtr(utf8_decode($word),utf8_decode('ŠŒŽšœžŸ¥µÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöøùúûüýÿ,_;:-\/|'),'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy        ');    
    return strtolower($word);
}

