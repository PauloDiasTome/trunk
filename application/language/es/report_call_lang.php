<?php

defined('BASEPATH') or exit('No direct script access allowed');
// Vista Waba
$lang['report_call_waba_header'] = "Informe de Atención";

// Vista de búsqueda
$lang['report_call_service_topnav'] = "Informe de Atención";
$lang['report_call_service_btn_filter'] = "Filtrar";
$lang['report_call_service_btn_export'] = "Exportar";

$lang['report_call_service_filter_title'] = "Filtrar atención";
$lang['report_call_service_filter_search'] = "Teléfono, Nombre de Contacto o Protocolo";
$lang['report_call_service_filter_search_placeholder'] = "Buscar...";
$lang['report_call_service_filter_channel'] = "Canal";
$lang['report_call_service_filter_channel_placeholder'] = "Seleccionar...";
$lang['report_call_service_filter_categories'] = "Categoria";
$lang['report_call_service_filter_categories_deleted'] = "Excluida";
$lang['report_call_service_filter_categories_placeholder'] = "Seleccionar...";
$lang['report_call_service_filter_label'] = "Etiqueta";
$lang['report_call_service_filter_label_placeholder'] = "Seleccionar...";
$lang['report_call_service_filter_user'] = "Usuario";
$lang['report_call_service_filter_user_placeholder'] = "Seleccionar...";
$lang['report_call_service_filter_sector'] = "Sector";
$lang['report_call_service_filter_sector_placeholder'] = "Seleccionar...";
$lang['report_call_service_filter_situation'] = "Situación";
$lang['report_call_service_filter_situation_select'] = "Seleccionar...";
$lang['report_call_service_filter_situation_closed'] = "Cerrado";
$lang['report_call_service_filter_situation_in_attendance'] = "En atención";
$lang['report_call_service_filter_period'] = "Período";
$lang['report_call_service_filter_period_placeholder_date_start'] = "Fecha de inicio";
$lang['report_call_service_filter_period_placeholder_date_end'] = "Fecha final";
$lang['report_call_service_filter_btn_return'] = "Volver";
$lang['report_call_service_filter_btn_search'] = "Buscar";
$lang['report_call_service_filter_period_notify'] = "Período seleccionado no disponible. Datos almacenados por 90 días.";

$lang['report_call_service_column_creation'] = "Fecha de Creación";
$lang['report_call_service_column_protocol'] = "Protocolo ";
$lang['report_call_service_column_name_contact'] = "Nombre/ Contacto";
$lang['report_call_service_column_user_sector'] = "Usuario/ Sector";
$lang['report_call_service_column_channel'] = "Canal";
$lang['report_call_service_column_categories'] = "Categoria";
$lang['report_call_service_column_label'] = "Etiqueta";
$lang['report_call_service_column_situation'] = "Situación/ Tiempo";

$lang['report_call_service_column_action_view'] = "Ver";
$lang['report_call_service_column_action_export'] = "Exportar";

$lang['report_call_service_export_title'] = "Exportación de datos";
$lang['report_call_service_export_email'] = "¿Desea enviar los datos al correo electrónico:";
$lang['report_call_service_export_email_placeholder'] = "Ingrese el correo electrónico para la exportación";
$lang['report_call_service_export_btn_confirm'] = "Confirmar";
$lang['report_call_service_export_btn_return'] = "Volver";
$lang['report_call_service_export_no_permit_title'] = "¡Límite de exportaciones alcanzado!";
$lang['report_call_service_export_no_permit_content'] = "Se han realizado demasiadas exportaciones por este usuario, intente nuevamente más tarde";

$lang['history_report_call_service_btn_export'] = "Exportar conversación";
$lang['history_report_call_service_export_title'] = "Ingrese el correo electrónico para la exportación";
$lang['history_report_call_service_export_email'] = "Correo electrónico";
$lang['history_report_call_service_export_btn_send'] = "Enviar";

$lang['chat_report_call_service_export_title'] = "Ingrese el correo electrónico para la exportación";
$lang['chat_report_call_service_export_email'] = "Correo electrónico";
$lang['chat_report_call_service_export_btn_send'] = "Enviar";

// JS
$lang['report_call_interactive_flow_message_client'] = "Cliente:";
$lang['report_call_interactive_flow_message_yes'] = "Sí";
$lang['report_call_interactive_flow_message_no'] = "No";

// Alerta exportación
$lang['report_call_alert_export_title'] = "¡Lista enviada!";
$lang['report_call_alert_export_text'] = "El correo electrónico puede tardar hasta una hora en llegar.";
$lang['report_call_alert_export_confirmButtonText'] = "¡Ok!";

// Alerta fuera de noventa días
$lang['report_call_alert_out_ninety_days_title'] = "¡Atención!";
$lang['report_call_alert_out_ninety_days_confirmButtonText'] = "¡Ok!";

// Chat
$lang['report_call_chat_status_in_attendance'] = "EN ATENCIÓN";
$lang['report_call_chat_status_attendance_closed'] = "ATENCIÓN CERRADA";

$lang['report_call_chat_transfer_attendance'] = "Transferido a las ";
$lang['report_call_chat_to'] = " a";
$lang['report_call_chat_start_attendance'] = "Inició la atención a las ";
$lang['report_call_chat_waiting_attendance'] = "Puso la atención en espera";
$lang['report_call_chat_closed_attendance'] = "Cerró la atención a las ";

$lang['report_call_chat_no_record_found'] = "No se encontraron registros para este período";

// Evaluación
$lang['report_call_assessment_details'] = "Detalles de la Evaluación";
$lang['report_call_assessment'] = "Evaluación:";
$lang['report_call_AI_feedback'] = "Retroalimentación de la IA:";
$lang['report_call_AI_feedback_none'] = "No se generó ninguna retroalimentación por la IA";
$lang['report_call_AI_feedback_empty'] = "Sin evaluación";
$lang['report_call_AI_feedback_gerated_by_IA'] = "Resultado generado por IA. Recomendamos verificación adicional.";

// Función FSD
$lang['function_fsd_today'] = "Hoy";
$lang['function_fsd_yesterday'] = "Ayer";
$lang['function_fsd_before_yesterday'] = "Anteayer";

$lang['function_fsd_week_day_sun'] = "Domingo";
$lang['function_fsd_week_day_mon'] = "Lunes";
$lang['function_fsd_week_day_tue'] = "Martes";
$lang['function_fsd_week_day_wed'] = "Miércoles";
$lang['function_fsd_week_day_thu'] = "Jueves";
$lang['function_fsd_week_day_fri'] = "Viernes";
$lang['function_fsd_week_day_sat'] = "Sábado";

$lang['report_call_case18_parseint_message_deleted'] = "¡Este mensaje ha sido borrado!";
$lang['report_call_case18_parseint_you_deleted_this_message'] = "¡Has borrado este mensaje!";

$lang['report_call_dt_columndefs_target7_title_edit'] = "En atención";
$lang['report_call_dt_columndefs_target7_title_delete'] = "Cerrado";

$lang['report_call_dt_columndefs_target8_title_edit'] = "Editar";

// Función chatSettings
$lang['report_call_fuction_chatsetting_background'] = "Fondo de Pantalla";
$lang['report_call_fuction_chatsetting_export'] = "Exportar";

// Función calendario
$lang['report_call_calendar_btn_search'] = "Buscar";
$lang['report_call_calendar_btn_return'] = "Volver";

$lang['report_call_calendar_week_day_sun'] = "Dom";
$lang['report_call_calendar_week_day_mon'] = "Lun";
$lang['report_call_calendar_week_day_tue'] = "Mar";
$lang['report_call_calendar_week_day_wed'] = "Mié";
$lang['report_call_calendar_week_day_thu'] = "Jue";
$lang['report_call_calendar_week_day_fri'] = "Vie";
$lang['report_call_calendar_week_day_sat'] = "Sáb";

$lang['report_call_calendar_month_january'] = "Enero";
$lang['report_call_calendar_month_february'] = "Febrero";
$lang['report_call_calendar_month_march'] = "Marzo";
$lang['report_call_calendar_month_april'] = "Abril";
$lang['report_call_calendar_month_may'] = "Mayo";
$lang['report_call_calendar_month_june'] = "Junio";
$lang['report_call_calendar_month_july'] = "Julio";
$lang['report_call_calendar_month_august'] = "Agosto";
$lang['report_call_calendar_month_september'] = "Septiembre";
$lang['report_call_calendar_month_october'] = "Octubre";
$lang['report_call_calendar_month_november'] = "Noviembre";
$lang['report_call_calendar_month_december'] = "Diciembre";

$lang["report_call_document_message"] = "Documento";
$lang["report_call_story_mention"] = "Te mencionó en su propia historia";
$lang["report_call_service_filter_categories_no_category"] = "Sin categoría";

$lang['report_call_template_flow_message_subtitle'] = "Respuesta enviada";
$lang['report_call_template_flow_message_title_modal'] = "Detalles de la respuesta";

$lang["report_call_modal_assessment"] = "Evaluación";
$lang["report_call_modal_comment"] = "Comentario";
