<?php

defined('BASEPATH') or exit('No direct script access allowed');

// View Waba
$lang['user_waba_header'] = "Usuarios";
$lang['user_waba_add'] = "Nuevo Usuario";
$lang['user_waba_edit'] = "Editar Usuario";

// View find 
$lang['user_topnav'] = "Usuarios";
$lang['user_btn_new'] = "Nuevo";
$lang['user_btn_filter'] = "Filtrar";
$lang['user_btn_export'] = "Exportar";

$lang['user_column_name'] = "Nombre";
$lang['user_column_sector'] = "Sector";
$lang['user_column_email'] = "Correo electrónico";
$lang['user_column_situaion'] = "Situación";

$lang['user_filter_title'] = "Filtrar usuario";
$lang['user_filter_name'] = "Nombre o correo electrónico";
$lang['user_filter_name_placeholder'] = "Buscar...";
$lang['user_filter_sector'] = "Sector";
$lang['user_filter_sector_placeholder'] = "Seleccionar...";
$lang['user_filter_situation'] = "Situación";
$lang['user_filter_situation_select'] = "Seleccionar...";
$lang['user_filter_situation_verified'] = "Verificado";
$lang['user_filter_situation_not_verified'] = "No verificado";
$lang['user_filter_situation_blocked'] = "Bloqueado";
$lang['user_filter_btn_return'] = "Volver";
$lang['user_filter_btn_search'] = "Buscar";

$lang['user_export_title'] = "Exportación de datos";
$lang['user_export_email'] = "¿Desea enviar los datos al correo electrónico:";
$lang['user_export_email_placeholder'] = "Ingrese el correo electrónico para la exportación";
$lang['user_export_btn_confirm'] = "Confirmar";
$lang['user_export_btn_return'] = "Volver";

$lang['user_password_recovery_title'] = "Restablecer contraseña";
$lang['user_password_recovery_email'] = "Se enviará un correo electrónico de restablecimiento a:";
$lang['user_password_recovery_btn_confirm'] = "Confirmar";
$lang['user_password_recovery_btn_return'] = "Volver";

// Del confirm model
$lang['user_delete_confirm_title'] = "Eliminar usuario";
$lang['user_delete_confirm_text'] = "El usuario";
$lang['user_delete_confirm_two_text'] = "es responsable de algunos contactos.";
$lang['user_delete_confirm_three_text'] = "¿Desea asignar otro usuario como responsable?";
$lang['user_delete_confirm_four_text'] = "Seleccione el nuevo responsable";
$lang['user_delete_confirm_five_text'] = "Ninguno";

// Generic add e edit
$lang['user_register'] = "Fecha de registro";
$lang['user_first_name'] = "Primer nombre";
$lang['user_first_name_placeholder'] = "Ingrese el primer nombre";
$lang['user_full_name'] = "Nombre completo";
$lang['user_full_name_placeholder'] = "Ingrese el nombre completo";
$lang['user_email'] = "Correo electrónico";
$lang['user_email_placeholder'] = "Ingrese el correo electrónico";
$lang['user_widget'] = "Mostrar en el widget";
$lang['user_widget_option_no'] = "No";
$lang['user_widget_option_yes'] = "Sí";
$lang['user_sector'] = "Sector";
$lang['user_sector_language'] = "Idioma";
$lang['user_sector_language_portugues'] = "Portugués (BR)";
$lang['user_sector_language_english'] = "Inglés (US)";
$lang['user_sector_language_spanish'] = "Español (ES)";
$lang['user_service_profile'] = "Límite de atención simultánea";
$lang['user_access_profile'] = "Perfil de acceso";
$lang['user_time_table'] = "Tabla de horarios";
$lang['user_validation_email'] = "El correo electrónico ya está registrado en TalkAll.";
$lang['user_validation_email_invalid'] = "El campo de correo electrónico no es válido";
$lang['user_dropdown_add_service_limit'] = "Agregar límite de atención";
$lang['user_dropdown_add_service_limit_title'] = "Haga clic aquí para agregar uno nuevo";
$lang['user_dropdown_add_new_sector'] = "Agregar nuevo sector";
$lang['user_dropdown_add_new_sector_title'] = "Haga clic aquí para agregar uno nuevo";
$lang['user_input_select'] = "Seleccionar";
$lang['user_btn_return'] = "Volver";
$lang['user_btn_save'] = "Guardar";
$lang['user_btn_cancel'] = "Cancelar";

// modal user call /// 
$lang['user_modal_call_user_title'] = "Agregar límite de atención";
$lang['user_modal_call_nome'] = "Nombre";
$lang['user_modal_call_nome_placeholder'] = "Ingrese nombre";
$lang['user_modal_call_simultaneous_service_limit'] = "Límite de atención simultánea";
$lang['user_modal_call_user_btn_save'] = "Guardar";
$lang['user_modal_call_user_btn_return'] = "Volver";

// modal user sector /// 
$lang['user_modal_sector_user_title'] = "Agregar nuevo sector";
$lang['user_modal_sector_nome'] = "Nombre";
$lang['user_modal_sector_nome_placeholder'] = "Ingrese nombre";
$lang['user_modal_sector_user_btn_save'] = "Guardar";
$lang['user_modal_sector_user_btn_return'] = "Volver";

// View add  
$lang['user_add_title'] = "Nuevo";
$lang['user_add_information'] = "Agregar nuevo usuario";
$lang['user_add_alert_part_one'] = "Se enviará al";
$lang['user_add_alert_part_twe'] = "una confirmación por correo electrónico y la contraseña temporal para el acceso de este usuario.";

// View edit
$lang['user_edit_title'] = "Editar";
$lang["user_edit_information"] = "Información del usuario";
$lang['user_edit_change_profile_picture'] = "Cambiar foto de perfil";
$lang['user_edit_change_password'] = "Restablecer contraseña";
$lang['user_edit_resend_validation_email'] = "Reenviar correo de validación";

// View security
$lang['user_security_manage_information'] = "Administre su información, privacidad y seguridad para que TalkAll satisfaga sus necesidades";
$lang['user_security_title_device'] = "Últimos dispositivos utilizados";
$lang['user_security_connection_talkall'] = "Actualmente está conectado a su cuenta de TalkAll en este dispositivo";
$lang['user_security_user_login'] = "Este usuario aún no ha iniciado sesión";

$lang['user_security_title_safety'] = "Niveles de seguridad";
$lang['user_security_protect_account'] = "Resuelva estas cuestiones para proteger esta cuenta";
$lang['user_security_protect_very_well'] = "¡Todo está bien!";
$lang['user_security_protect_password_ok'] = "Su contraseña está correcta.";
$lang['user_security_protect_click_change_Password'] = "Haga clic para cambiar la contraseña predeterminada";
$lang['user_security_protect_click_disable'] = "Haga clic aquí para deshabilitar";
$lang['user_security_protect_disable'] = "Deshabilitar";
$lang['user_security_protect_add'] = "Agregar";
$lang['user_security_protect_change_Password'] = "Cambie la contraseña predeterminada";
$lang['user_security_protect_add_two_favor_authentication'] = "Autenticación de dos factores por SMS";
$lang['user_security_protect_two_favor_authentication'] = "Agregue autenticación de dos factores para aumentar su seguridad";
$lang['user_security_protect_click_enable_two_favor_authentication'] = "Haga clic para habilitar la autenticación de dos factores";
$lang['user_security_protect_enable_two_favor_authentication'] = "Agregue autenticación de dos factores por SMS";

$lang['user_security_datatable_title'] = "Historial de acciones";
$lang['user_security_culumn_time_hour'] = "Fecha y hora";
$lang['user_security_culumn_fulfilled'] = "Acción realizada";
$lang['user_security_culumn_operating_system'] = "Sistema operativo";
$lang['user_security_culumn_browser'] = "Navegador";
$lang['user_security_culumn_origin'] = "IP de origen";

$lang['user_security_text_login_access_success'] = "Inicio de sesión exitoso";
$lang['user_security_text_login_change_password'] = "Contraseña modificada";
$lang['user_security_text_login_error_password'] = "Contraseña incorrecta";
$lang['user_security_text_login_user_block'] = "Usuario bloqueado";
$lang['user_security_text_login_two_factor_authentication_failed'] = "Falló la autenticación de dos factores";
$lang['user_security_text_login_user_block_two_factor'] = "Usuario bloqueado en la etapa de dos factores";
$lang['user_security_text_login_change_default_intranet'] = "Contraseña predeterminada modificada por la intranet";
$lang['user_security_text_login_unlocked_intranet'] = "Desbloqueo de usuario por la intranet";
$lang['user_security_text_deleted_user'] = "Usuario eliminado";

// JS 
$lang['user_datatle_column_verified_user'] = "¡Usuario verificado!";
$lang['user_datatle_column_unverified_user'] = "¡Usuario no verificado!";
$lang['user_datatle_column_blocked_user'] = "¡Usuario bloqueado!";

$lang['user_datatle_column_action_edit'] = "Editar";
$lang['user_datatle_column_action_delete'] = "Eliminar";
$lang['user_datatle_column_action_security'] = "Seguridad";

// alert delete
$lang['user_alert_delete_title'] = "¿Está seguro?";
$lang['user_alert_delete_text'] = "¿Realmente desea eliminar este usuario?";
$lang['user_alert_delete_confirmButtonText'] = "Sí";
$lang['user_alert_delete_cancelButtonText'] = "No";

$lang['user_alert_warning_delete_title'] = "¡Usuario no eliminado!";
$lang['user_alert_warning_delete'] = "Hay contacto(s) siendo atendidos por este usuario.";
$lang['user_alert_warning_delete_contact_waiting'] = "Hay contacto(s) esperando atención con este usuario.";

$lang['user_alert_delete_two_title'] = "¡Eliminado!";
$lang['user_alert_delete_two_text'] = "¡Usuario eliminado con éxito!";

$lang['user_alert_delete_three_title'] = "¿Está seguro?";
$lang['user_alert_delete_three_text'] = "¿Realmente desea eliminar este usuario?";
$lang['user_alert_delete_three_confirmButtonText'] = "Sí";
$lang['user_alert_delete_three_cancelButtonText'] = "No";

// alert export
$lang['user_alert_export_title'] = "¡Lista enviada!";
$lang['user_alert_export_text'] = "El correo electrónico puede tardar hasta una hora en llegar.";
$lang['user_alert_export_confirmButtonText'] = "Ok";

// alert email
$lang['user_alert_email_title'] = "¡Correo electrónico enviado!";
$lang['user_alert_email_two_title'] = "¡Correo electrónico no enviado!";
$lang['user_alert_email_modal_title'] = "Enviamos un correo a: <b>";
$lang['user_alert_email_two_modal_title'] = "</b> con el enlace para restablecer la contraseña.";
$lang['user_alert_email_resend_title'] = "</b> con el enlace de confirmación.";

$lang['user_alert_notification_email_title'] = "¡Usuario registrado!";
$lang['user_alert_notification_email_text'] = "Correo de confirmación enviado a ";
$lang['user_alert_notification_email_confirmButtonText'] = "OK";
 
// alert validation modal user call
$lang['user_validation_modal_name_required'] = "El campo Nombre es obligatorio.";
$lang['user_validation_modal_name_min_length'] = "El campo Nombre debe tener al menos {param} caracter(es).";
$lang['user_validation_modal_name_max_length'] = "El campo Nombre superó el límite de {param} caracter(es).";

$lang['user_validation_modal_limit_required'] = "El campo Límite de atención es obligatorio.";
$lang['user_validation_modal_limit_negative'] = "El campo Límite de atención no puede contener un número negativo.";
$lang['user_validation_modal_limit_max_lengthNumber'] = "El campo Límite de atención no puede ser mayor que 100";
$lang['user_validation_modal_limit_min_length'] = "El campo Nombre debe tener al menos {param} caracter(es).";
$lang['user_validation_modal_limit_max_length'] = "El campo Nombre superó el límite de {param} caracter(es).";

//email
$lang['email_subject_confirmation'] = "Confirmación de correo electrónico 🤖";
