<?php

/**
 * Traducción de mensajes del sistema para CodeIgniter(tm)
 *
 * @author Comunidad de CodeIgniter
 * @copyright Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license http://opensource.org/licenses/MIT Licencia MIT
 * @link http://codeigniter.com
 */
defined('BASEPATH') or exit('No se permite el acceso directo al script');

$lang['email_must_be_array'] = 'El método de validación de correo electrónico debe recibir un array.';
$lang['email_invalid_address'] = 'Dirección de correo electrónico inválida: %s';
$lang['email_attachment_missing'] = 'No se puede localizar el siguiente archivo adjunto: %s';
$lang['email_attachment_unreadable'] = 'No se puede abrir el archivo adjunto: %s';
$lang['email_no_from'] = 'No se puede enviar el correo electrónico sin una dirección de origen.';
$lang['email_no_recipients'] = 'Debe incluir destinatarios: To (para), Cc (copia), o Bcc (copia oculta)';
$lang['email_send_failure_phpmail'] = 'No se puede enviar el correo electrónico usando PHP mail(). Es posible que su servidor no esté configurado para enviar correos electrónicos usando este método.';
$lang['email_send_failure_sendmail'] = 'No se puede enviar el correo electrónico usando PHP Sendmail. Es posible que su servidor no esté configurado para enviar correos electrónicos usando este método.';
$lang['email_send_failure_smtp'] = 'No se puede enviar el correo electrónico usando PHP SMTP. Es posible que su servidor no esté configurado para enviar correos electrónicos usando este método.';
$lang['email_sent'] = 'Su mensaje ha sido enviado correctamente utilizando el siguiente protocolo: %s';
$lang['email_no_socket'] = 'No se puede abrir un socket para Sendmail. Por favor, verifique las configuraciones.';
$lang['email_no_hostname'] = 'No especificó una dirección SMTP.';
$lang['email_smtp_error'] = 'Ocurrieron los siguientes errores SMTP: %s';
$lang['email_no_smtp_unpw'] = 'Error: Debe asignar un usuario y contraseña para SMTP.';
$lang['email_failed_smtp_login'] = 'Falló al enviar el comando AUTH LOGIN. Error: %s';
$lang['email_smtp_auth_un'] = 'Falló la autenticación del usuario. Error: %s';
$lang['email_smtp_auth_pw'] = 'Falló la autenticación de la contraseña. Error: %s';
$lang['email_smtp_data_failure'] = 'No se pudo enviar los datos: %s';
$lang['email_exit_status'] = 'Código de estado de salida: %s';


// lang para pasta views/email/*
$lang['email_confirmation'] = "Tu correo ha sido confirmado";
$lang["email_will_recieve_access"] = "En unos momentos recibirás en tu correo los datos de acceso a la plataforma.";
$lang["email_thanks_for_using"] = "Gracias por usar TalkAll";
$lang["email_confirm_btn"] = "Confirmar correo";
$lang["email_privacy_policy"] = "Política de privacidad";
$lang["email_use_terms"] = "Términos de uso";
$lang["email_contact_us"] = "Contáctanos";
$lang["email_rights_reserved"] = "Todos los derechos reservados.";

$lang["email_almost_ready"] = "Estás casi listo para comenzar";
$lang["email_store_securely"] = "Guarda esta información de forma segura";
$lang["email_acces_panel"] = "Acceder al panel";
$lang["email_just_contact"] = "Contacto";
$lang["email_or_click_here"] = "O HAGA CLICK AQUI";
