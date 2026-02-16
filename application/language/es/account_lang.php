<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Vista login
$lang['account_title'] = "Talkall";
$lang['account_subtitle'] = "Ingrese sus credenciales";

$lang['account_form_email'] = "Correo electrónico";
$lang['account_form_email_placeholder'] = "Por favor, ingrese el correo electrónico";
$lang['account_form_password'] = "Contraseña";
$lang['account_form_password_placeholder'] = "Por favor, ingrese la contraseña";

$lang['account_form_btn_enter'] = "Ingresar";
$lang['account_form_btn_enter_text'] = "Ingresando, por favor espere un momento";
$lang['account_form_forgot_password_text'] = "¿Olvidó su contraseña?";

// Controlador //

$lang['account_alert_invalid_email_title'] = "Error de inicio de sesión";
$lang['account_alert_invalid_email_message'] = "El usuario o la contraseña que ingresó no están conectados a una cuenta. ¡Por favor, ingrese un usuario y una contraseña válidos!";
$lang['account_alert_invalid_email_btn'] = "Regresar";

$lang['account_alert_invalid_forgot_email_title'] = "Error de inicio de sesión";
$lang['account_alert_invalid_forgot_email_message'] = "El correo electrónico que ingresó no es válido. ¡Por favor, ingrese un correo válido para cambiar su contraseña!";
$lang['account_alert_invalid_forgot_email_btn'] = "Regresar";

$lang['account_alert_block_user_title'] = "Credencial Bloqueada";
$lang['account_alert_block_user_message'] = "Ha intentado iniciar sesión incorrectamente tres veces consecutivas, y su credencial ha sido bloqueada. Comuníquese con el soporte técnico para desbloquear la cuenta: ";
$lang['account_alert_block_user_btn'] = "¿Olvidó su contraseña?";

$lang['account_alert_warnig_block_user_title'] = "Error de inicio de sesión";
$lang['account_alert_warnig_block_user_subtitle'] = "Correo electrónico o contraseña no válidos";
$lang['account_alert_warnig_block_user_message1'] = "Le quedan";
$lang['account_alert_warnig_block_user_message2'] = "intentos para acceder al sistema. ¡De lo contrario, su cuenta será bloqueada!";
$lang['account_alert_warnig_block_user_btn'] = "Regresar";

$lang['account_alert_invalid_caracteres_email_title'] = "Error de inicio de sesión";
$lang['account_alert_invalid_caracteres_email_message'] = "El correo electrónico que ingresó supera el límite de caracteres permitido por la plataforma.";
$lang['account_alert_invalid_caracteres_email_btn'] = "Regresar";

$lang['account_alert_block_access_work_time_title'] = "Error de inicio de sesión";
$lang['account_alert_block_access_work_time_message'] = "Horario no permitido para el acceso.";
$lang['account_alert_block_access_work_time_btn'] = "Regresar";

$lang['account_alert_authentication_2fa_title'] = "Autenticación de dos factores";
$lang['account_alert_authentication_2fa_message'] = "Ingrese el código de 6 dígitos enviado por SMS al número: ";

$lang['account_alert_required_intranet_title'] = "Atención";
$lang['account_alert_required_intranet_messge'] = "Es necesario generar una contraseña de soporte en la intranet.";
$lang['account_alert_required_intranet_btn'] = "Regresar";

$lang['account_alert_external_access_not_allwed_title'] = "Atención";
$lang['account_alert_external_access_not_allwed_message'] = "¡El acceso externo no está permitido para su usuario!";
$lang['account_alert_external_access_not_allwed_btn'] = "Regresar";

$lang['account_alert_expired_code_attempts_title'] = "Atención";
$lang['account_alert_expired_code_attempts_message'] = "Ha excedido el límite de intentos de inicio de sesión. ¡Por favor, inténtelo más tarde!";
$lang['account_alert_expired_code_attempts_btn'] = "Regresar";

$lang['account_alert_2fa_warning_block_user_message1'] = "Le quedan";
$lang['account_alert_2fa_warning_block_user_message2'] = "intentos para acceder al sistema. ¡De lo contrario, su cuenta será bloqueada!";

$lang['account_alert_2fa_blocked_user_message'] = "Ha intentado iniciar sesión incorrectamente tres veces consecutivas, y su credencial ha sido bloqueada. Comuníquese con el soporte técnico para desbloquear la cuenta: ";

$lang['account_alert_2fa_unexpected_error_message'] = "Error de inicio de sesión, ¡por favor inténtelo más tarde!";

$lang['account_alert_2fa_expired_message'] = "El código ha expirado. ¡Por favor, vuelva a enviar el código!";

$lang['account_alert_2fa_default_message'] = "Algo salió mal, ¡inténtelo más tarde!";

$lang['account_alert_new_password_error_title'] = "Error";
$lang['account_alert_new_password_error_btn'] = "Regresar";
$lang['account_alert_new_password_error_message'] = "";

$lang['account_alert_new_password_enter_password'] = "Ingrese su nueva contraseña";
$lang['account_alert_new_password_placeholder'] = "Nueva contraseña";
$lang['account_alert_new_password_placeholder_repeat'] = "Repetir nueva contraseña";
$lang['account_alert_new_password_button'] = "Establecer nueva contraseña";

$lang['account_alert_new_password_success_title'] = "¡Todo listo!";
$lang['account_alert_new_password_success_btn'] = "Ir al inicio de sesión";
$lang['account_alert_new_password_success_message'] = "Su contraseña ha sido restablecida con éxito. Acceda a <b>app.talkall.com.br</b> para iniciar sesión y entrar al sistema.";

$lang['account_alert_forgot_password_success_title'] = "¡Todo listo!";
$lang['account_alert_forgot_password_success_btn'] = "Ir al inicio de sesión";
$lang['account_alert_forgot_password_success_message1'] = "Si el correo: <b>";
$lang['account_alert_forgot_password_success_message2'] = "</b> existe en nuestra base de datos, recibirá un enlace para restablecer la contraseña.";

$lang['account_alert_form_validation_password_changed_password'] = "Contraseña";
$lang['account_alert_form_validation_password_changed_confirm'] = "Confirmación de contraseña";

// ----------------------------------------------------------------------------------------

$lang['account_alert_email_title'] = "Enviamos un correo a: <b>";
$lang['account_alert_email_two_title'] = "</b> con el enlace para restablecer la contraseña.";

$lang['account_alert_password_changed'] = "¡Contraseña restablecida a la contraseña predeterminada!";

$lang['account_alert_password_changed_expired'] = "El enlace ha expirado o la URL no es válida.<br>Comuníquese con el administrador para solicitar un nuevo enlace. Error 404 encontrado.";

$lang['account_alert_sendemail_title'] = "Contraseña TalkAll";
$lang['account_alert_sendemail_desciption'] = "Para cambiar su contraseña, por favor";

$lang['account_alert_request'] = "¡La solicitud del SMS ha fallado!";

// Cuenta sin permiso
$lang['account_without_permission_view_name'] = "Sin Permiso";
$lang['account_without_permission_img_title'] = "Imagen de una mano sosteniendo un candado.";
$lang['account_without_permission_img_alt'] = "Imagen de una mano sosteniendo un candado cerrado, que representa que el usuario no tiene permiso para acceder a la funcionalidad.";
$lang['account_without_permission_text_title'] = "¡Ups! No tiene permiso para acceder a esta funcionalidad.";
$lang['account_without_permission_text_body'] = "Verifique su plan de suscripción o contacte al administrador del sistema para obtener acceso.";
