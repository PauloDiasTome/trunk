<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | -------------------------------------------------------------------------
  | Hooks
  | -------------------------------------------------------------------------
  | This file lets you define "hooks" to extend CI without hacking the core
  | files.  Please see the user guide for info:
  |
  |	https://codeigniter.com/user_guide/general/hooks.html
  |
 */

$hook['post_controller_constructor'] = array(
    'class' => 'LanguageLoader', //define a classe
    'function' => 'initialize', //define o método a ser executado
    'filename' => 'LanguageLoader.php', //define o nome do arquivo
    'filepath' => 'hooks' //define o diretório onde o arquivo está
);
