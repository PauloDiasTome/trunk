<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Title Waba
$lang['report_interaction_synthetic_waba_header'] = "Informe de Interacciones";

// View Find
$lang['report_interaction_synthetic_topnav'] = "Informe de Interacciones";
$lang['report_interaction_synthetic_btn_filter'] = "Filtrar";
$lang['report_interaction_synthetic_btn_export'] = "Exportar";

$lang['report_interaction_synthetic_chatbot_interaction'] = "Interacciones del Chatbot";
$lang['report_interaction_synthetic_waiting_service'] = "Esperando Atención";
$lang['report_interaction_synthetic_service'] = "Atención";

$lang['report_interaction_synthetic_period'] = "Período:";
$lang['report_interaction_synthetic_day'] = "Hoy";

$lang['report_interaction_synthetic_column_count'] = "Cantidad";
$lang['report_interaction_synthetic_column_chatbot'] = "Información de interacciones del chatbot";
$lang['report_interaction_synthetic_column_wating_service'] = "Esperando atención";
$lang['report_interaction_synthetic_column_attendance'] = "Atención";

$lang['report_interaction_synthetic_export_title'] = "Exportación de datos";
$lang['report_interaction_synthetic_export_email'] = "¿Desea enviar los datos al correo electrónico:";
$lang['report_interaction_synthetic_export_email_placeholder'] = "Ingrese el correo electrónico para exportación";
$lang['report_interaction_synthetic_export_btn_confirm'] = "Confirmar";
$lang['report_interaction_synthetic_export_btn_return'] = "Volver";

$lang['report_interaction_synthetic_filter_title'] = "Filtrar interacciones del chatbot y atención";
$lang['report_interaction_synthetic_filter_report'] = "Seleccionar informe";
$lang['report_interaction_synthetic_filter_report_placeholder'] = "Seleccionar...";
$lang['report_interaction_synthetic_filter_report_interaction_chatbot'] = "Interacciones del chatbot";
$lang['report_interaction_synthetic_filter_report_wainting_attendance'] = "Esperando atención";
$lang['report_interaction_synthetic_filter_report_attendance'] = "Atención";
$lang['report_interaction_synthetic_filter_period'] = "Período";
$lang['report_interaction_synthetic_filter_period_placeholder_date_start'] = "Fecha inicial";
$lang['report_interaction_synthetic_filter_period_placeholder_date_end'] = "Fecha final";
$lang['report_interaction_synthetic_filter_btn_return'] = "Volver";
$lang['report_interaction_synthetic_filter_btn_search'] = "Buscar";
$lang['report_interaction_synthetic_filter_period_notify'] = "Período seleccionado no disponible. Datos almacenados por 90 días";

$lang['report_interaction_synthetic_history_title'] = "Exportar Conversación";
$lang['report_interaction_synthetic_history_email'] = "¿Desea enviar los datos al correo electrónico?";
$lang['report_interaction_synthetic_history_btn_send'] = "Enviar";
$lang['report_interaction_synthetic_history_btn_export'] = "Exportar Conversación";

// ReportInteractionSynthetic.php

// Function ChatBot
$lang['report_interaction_synthetic_model_function_chatbot_chatbot_total_interactions'] = "Interacciones totales del chatbot";
$lang['report_interaction_synthetic_model_function_chatbot_most_famous_option'] = "Opción más solicitada del chatbot";
$lang['report_interaction_synthetic_model_function_chatbot_less_famous_option'] = "Opción menos solicitada del chatbot";
$lang['report_interaction_synthetic_model_function_chatbot_auto_transfered_service'] = "Transferido automáticamente a la atención";
$lang['report_interaction_synthetic_model_function_chatbot_info_options_interactions'] = "Interacciones con opciones informativas";
$lang['report_interaction_synthetic_model_function_chatbot_info_to_advanced_for_service'] = "Interacciones que avanzaron a la atención";
$lang['report_interaction_synthetic_model_function_chatbot_out_of_order'] = "No disponible";

// Function Waiting Service
$lang['report_interaction_synthetic_model_function_waiting_service_contacts_waited'] = "Contactos que esperaron atención";
$lang['report_interaction_synthetic_model_function_waiting_service_avarage_waiting_time'] = "Tiempo medio de espera de atención";
$lang['report_interaction_synthetic_model_function_waiting_service_highest_avarage_waiting_time_by_department'] = "Tiempo medio de espera más alto por departamento";

// Function Attendance
$lang['report_interaction_synthetic_model_function_attendence_service_total'] = "Total de atenciones";
$lang['report_interaction_synthetic_model_function_attendence_most_famous_sector'] = "Sector más solicitado";
$lang['report_interaction_synthetic_model_function_attendence_less_famous_sector'] = "Sector menos solicitado";
$lang['report_interaction_synthetic_model_function_attendence_best_service_sector_media'] = "Promedio de atención más alto por sector";
$lang['report_interaction_synthetic_model_function_attendence_service_sector_media'] = "Promedio de atención más alto por agente";
$lang['report_interaction_synthetic_model_function_attendence_service_finish_total'] = "Total de atenciones finalizadas";
$lang['report_interaction_synthetic_model_function_attendence_service_open_total'] = "Total de atenciones abiertas";
$lang['report_interaction_synthetic_model_function_attendence_out_of_order'] = "No disponible";

// JS

// Function modal filter
$lang['report_interaction_synthetic_modal_filter_date_start_placeholder'] = "Fecha Inicial";
$lang['report_interaction_synthetic_modal_filter_channel'] = "Canal";

// Function FSD (FormatShortDate)
$lang['report_interaction_synthetic_function_fsd_today'] = "Hoy";
$lang['report_interaction_synthetic_function_fsd_yesterday'] = "Ayer";
$lang['report_interaction_synthetic_function_fsd_day_before_yesterday'] = "Anteayer";

$lang['report_interaction_synthetic_function_fsd_sunday'] = "Domingo";
$lang['report_interaction_synthetic_function_fsd_monday'] = "Lunes";
$lang['report_interaction_synthetic_function_fsd_tuesday'] = "Martes";
$lang['report_interaction_synthetic_function_fsd_wednesday'] = "Miércoles";
$lang['report_interaction_synthetic_function_fsd_thursday'] = "Jueves";
$lang['report_interaction_synthetic_function_fsd_friday'] = "Viernes";
$lang['report_interaction_synthetic_function_fsd_saturday'] = "Sábado";

// Function message
$lang['report_interaction_synthetic_function_message_if'] = "Este mensaje fue eliminado";
$lang['report_interaction_synthetic_function_message_else'] = "Has eli minado este mensaje";

// Parseint case
$lang['report_interaction_synthetic_parseint_case_20'] = "Transferió la atención a";
$lang['report_interaction_synthetic_parseint_case_21'] = "Inició la atención";
$lang['report_interaction_synthetic_parseint_case_22'] = "Puso la atención en espera";
$lang['report_interaction_synthetic_parseint_case_23'] = "Finalizó la atención";

// alert export
$lang['report_interaction_synthetic_alert_export_title'] = "¡Lista enviada!";
$lang['report_interaction_synthetic_alert_export_text'] = "El correo puede tardar hasta una hora en llegar.";
$lang['report_interaction_synthetic_alert_export_confirmButtonText'] = "¡Ok!";
