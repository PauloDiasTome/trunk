<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Vista Waba
$lang['sms_broadcast_waba_header'] = "Publicación SMS";
$lang['sms_broadcast_waba_add'] = "Nueva publicación SMS";
$lang['sms_broadcast_waba_edit'] = "Editar publicación SMS";

// Vista
$lang['sms_broadcast_view'] = "Ver campaña";
$lang['sms_broadcast_timeline_view'] = "Línea de tiempo";
$lang['sms_broadcast_edit_information'] = "Información de la campaña";

$lang['sms_broadcast_segmented_view'] = "Campaña segmentada";
$lang['sms_broadcast_segmented_view_yes'] = "Sí";
$lang['sms_broadcast_segmented_view_no'] = "No";
$lang['sms_broadcast_selected_group_view'] = "Grupo seleccionado";
$lang['sms_broadcast_select_channel_view'] = "Canal seleccionado";
$lang['sms_broadcast_schedule_hour_view'] = "Hora de programación";
$lang['sms_broadcast_description_sent_view'] = "Vista del SMS";

$lang['sms_broadcast_timeline_creation'] = "creó la campaña";
$lang['sms_broadcast_timeline_resend'] = "reenvió la campaña";
$lang['sms_broadcast_timeline_edited'] = "editó la campaña";
$lang['sms_broadcast_timeline_canceled'] = "canceló la campaña";
$lang['sms_broadcast_timeline_send_now'] = "cambió a enviar ahora";
$lang['sms_broadcast_timeline_creation_api'] = "Campaña creada a través de integración de sistema externo";

// Vista de búsqueda
$lang['sms_broadcast_topnav'] = "Publicaciones SMS";
$lang['sms_broadcast_btn_new'] = "Nuevo";
$lang['sms_broadcast_btn_filter'] = "Filtrar";
$lang['sms_broadcast_btn_export'] = "Exportar";

$lang['sms_broadcast_column_btn_pause'] = "Pausar";
$lang['sms_broadcast_column_btn_resume'] = "Reanudar";
$lang['sms_broadcast_column_btn_cancel'] = "Cancelar";
$lang['sms_broadcast_column_scheduling'] = "Fecha programada";
$lang['sms_broadcast_column_title'] = "Título";
$lang['sms_broadcast_column_status'] = "Estado";

$lang['sms_broadcast_filter_title'] = "Filtrar campaña";
$lang['sms_broadcast_filter_search'] = "Título";
$lang['sms_broadcast_filter_search_placeholder'] = "Buscar...";
$lang['sms_broadcast_filter_status'] = "Estado";
$lang['sms_broadcast_filter_status_select'] = "Seleccionar...";
$lang['sms_broadcast_filter_status_processing'] = "Procesando";
$lang['sms_broadcast_filter_status_processed'] = "Procesado";
$lang['sms_broadcast_filter_status_send'] = "Enviado";
$lang['sms_broadcast_filter_status_canceling'] = "Cancelando";
$lang['sms_broadcast_filter_status_cancel'] = "Cancelado";
$lang['sms_broadcast_filter_status_sent'] = "Enviado";
$lang['sms_broadcast_filter_status_waiting'] = "Esperando";
$lang['sms_broadcast_filter_period'] = "Período";
$lang['sms_broadcast_filter_period_placeholder_date_start'] = "Fecha de inicio";
$lang['sms_broadcast_filter_period_placeholder_date_end'] = "Fecha de finalización";
$lang['sms_broadcast_filter_btn_return'] = "Volver";
$lang['sms_broadcast_filter_btn_search'] = "Buscar";

$lang['sms_broadcast_export_title'] = "Exportación de datos";
$lang['sms_broadcast_export_email'] = "¿Desea enviar los datos al correo electrónico?";
$lang['sms_broadcast_export_email_placeholder'] = "Ingrese el correo electrónico para exportar";
$lang['sms_broadcast_export_btn_confirm'] = "Confirmar";
$lang['sms_broadcast_export_btn_return'] = "Volver";

// Agregar, editar genérico
$lang['sms_broadcast_title'] = "Título";
$lang['sms_broadcast_date_scheduling'] = "Fecha de programación";
$lang['sms_broadcast_date_scheduling_placeholder'] = "Seleccionar fecha";
$lang['sms_broadcast_hour_scheduling'] = "Hora de programación";
$lang['sms_broadcast_segments_select_placeholder'] = "Seleccionar";
$lang['sms_broadcast_segments_select_group'] = "Seleccionar grupo";
$lang['sms_broadcast_segments_select_group_placeholder'] = "Seleccionar";
$lang['sms_broadcast_message'] = "Mensaje: (Hasta 140 caracteres o 70 con caracteres especiales) | Caracteres restantes:";
$lang['sms_broadcast_status_message_placeholder'] = "Escriba aquí...";
$lang['sms_broadcast_btn_cancel'] = "Cancelar";
$lang['sms_broadcast_btn_return'] = "Volver";
$lang['sms_broadcast_btn_save'] = "Guardar";
$lang['sms_broadcast_choose_type'] = "Seleccionar tipo de campaña";

// Vista agregar
$lang['sms_broadcast_add_title'] = "Nuevo";
$lang['sms_broadcast_add_information'] = "Agregar nueva campaña";

// Modal estimación de campaña
$lang['campaign_estimate_title'] = "Atención al tiempo de envío de la campaña programada";
$lang['campaign_estimate_notify_on_modal'] = "La estimación de finalización de esta campaña supera el horario establecido para el canal. ¿Qué desea hacer?";
$lang['campaign_estimate_suspend_campaign'] = "Suspender campaña";
$lang['campaign_estimate_suspend_campaign_extension'] = "Cancelar inmediatamente la programación de la campaña.";
$lang['campaign_estimate_review_queue'] = "Revisar cola de envío";
$lang['campaign_estimate_review_queue_extension'] = "Administre las campañas para liberar espacio en la cola";
$lang['campaign_estimate_change_date'] = "Cambiar fecha de envío";
$lang['campaign_estimate_change_date_extension'] = "Elija otra fecha y hora para enviar la campaña.";
$lang['campaign_estimate_send_partially'] = "Enviar parcialmente";
$lang['campaign_estimate_send_partially_extension'] = "Tenga en cuenta que solo una parte de su base recibirá la campaña.";
$lang['campaign_estimate_send_after_hours'] = "Enviar después del horario";
$lang['campaign_estimate_send_after_hours_extension'] = "Use el tiempo necesario para enviar la campaña a toda la base.";
$lang['campaign_estimate_suspend_notify_title'] = "¿Está seguro?";
$lang['campaign_estimate_suspend_notify_text'] = "¿Desea cancelar la programación de esta campaña?";
$lang['campaign_estimate_suspend_notify_ok'] = "Sí";
$lang['campaign_estimate_suspend_notify_cancel'] = "No";
$lang['campaign_estimate_suspend_notify_deleted_title'] = "¡Eliminado!";
$lang['campaign_estimate_suspend_notify_notify_deleted_text'] = "¡Campaña eliminada con éxito!";
$lang['campaign_estimate_partial_notify_title'] = "¿Está seguro?";
$lang['campaign_estimate_partial_notify_text'] = "Esta campaña se enviará respetando el horario límite previamente configurado para los canales seleccionados.";
$lang['campaign_estimate_partial_notify_ok'] = "Sí";
$lang['campaign_estimate_partial_notify_cancel'] = "No";
$lang['campaign_estimate_partial_notify_add_title'] = "¡Publicación registrada!";
$lang['campaign_estimate_partial_notify_sucess'] = "¡Campaña programada con éxito!";
$lang['campaign_estimate_partial_notify_notify_deleted_text'] = "¡Campaña eliminada con éxito!";
$lang['campaign_estimate_after_notify_text'] = "Esta campaña se enviará a toda la base de contactos sin límite de tiempo.";

// columna
$lang['sms_broadcast_datatable_column_status_processing'] = "Esperando envío";
$lang['sms_broadcast_datatable_column_status_send'] = "Enviado";
$lang['sms_broadcast_datatable_column_status_called_off'] = "Cancelado";
$lang['sms_broadcast_datatable_column_status_canceling'] = "Cancelando";
$lang['sms_broadcast_datatable_column_status_paused'] = "Pausado";
$lang['sms_broadcast_datatable_column_status_sending'] = "En proceso";

// dropdown
$lang['sms_broadcast_datatable_column_action_view'] = "Ver";
$lang['sms_broadcast_datatable_column_action_cancel'] = "Cancelar";
$lang['sms_broadcast_datatable_column_action_pause'] = "Pausar";
$lang['sms_broadcast_datatable_column_action_pause_distribution'] = "Pausar distribución";
$lang['sms_broadcast_datatable_column_action_resume'] = "Reanudar";
$lang['sms_broadcast_datatable_column_action_resume_distribution'] = "Reanudar distribución";

// alerta eliminar
$lang['sms_broadcast_alert_delete_title'] = "¿Está seguro?";
$lang['sms_broadcast_alert_delete_text'] = "¿Desea eliminar esta campaña?";
$lang['sms_broadcast_alert_delete_confirmButtonText'] = "Sí";
$lang['sms_broadcast_alert_delete_cancelButtonText'] = "No";

$lang['sms_broadcast_alert_delete_two_title'] = "¡Eliminado!";
$lang['sms_broadcast_alert_delete_two_text'] = "¡Campaña eliminada con éxito!";

// alerta genérica
$lang['sms_broadcast_alert_broadcast_title'] = "¿Está seguro?";
$lang['sms_broadcast_alert_broadcast_confirmButtonText'] = "Sí";
$lang['sms_broadcast_alert_broadcast_cancelButtonText'] = "No";
$lang['sms_broadcast_alert_broadcast_two_title'] = "¡Completado!";

// alerta pausa
$lang['sms_broadcast_alert_broadcast_pause_text'] = "¿Desea pausar la campaña seleccionada?";
$lang['sms_broadcast_alert_broadcast_pause_all_text'] = "¿Desea pausar todas las campañas seleccionadas?";
$lang['sms_broadcast_alert_broadcast_pause_two_text'] = "¡Campaña pausada con éxito!";
$lang['sms_broadcast_alert_broadcast_pause_two_all_text'] = "¡Campañas pausadas con éxito!";

// alerta reanudar
$lang['sms_broadcast_alert_broadcast_resume_text'] = "¿Desea reanudar la campaña seleccionada?";
$lang['sms_broadcast_alert_broadcast_resume_all_text'] = "¿Desea reanudar todas las campañas seleccionadas?";
$lang['sms_broadcast_alert_broadcast_resume_two_text'] = "¡Campaña reanudada con éxito!";
$lang['sms_broadcast_alert_broadcast_resume_two_all_text'] = "¡Campañas reanudadas con éxito!";

// alerta error
$lang['sms_broadcast_error_broadcast_title'] = "¡Error!";
$lang['sms_broadcast_error_broadcast_message'] = "Ocurrió un error al ejecutar la acción";

// alerta exportar
$lang['sms_broadcast_alert_export_title'] = "¡Lista enviada!";
$lang['sms_broadcast_alert_export_text'] = "El correo puede tardar hasta una hora en llegar.";
$lang['sms_broadcast_alert_export_confirmButtonText'] = "¡Ok!";

// seleccionar canales
$lang['sms_broadcast_select_channel'] = "Seleccionar canal";

// Validación del formulario
$lang['sms_broadcast_check_date'] = "La fecha de programación es menor que la fecha actual";
$lang['sms_broadcast_check_hour'] = "La hora de programación debe ser al menos 30 minutos mayor que la hora actual!";
$lang['sms_broadcast_check_date_validity'] = "La fecha de validez es menor que la fecha de programación";
// $lang['sms_broadcast_check_hour_validity'] = "La hora de validez debe ser al menos una hora mayor que la hora de programación";
$lang['sms_broadcast_check_hour_validity'] = "La hora de validez es menor o igual a la hora de programación";
$lang['sms_broadcast_alert_field_validation'] = "Este campo es obligatorio.";

$lang['sms_broadcast_validation_message'] = "Mensaje";
$lang['sms_broadcast_validation_img'] = "Imagen";
$lang['sms_broadcast_validation_cancel_title'] = "Error";
$lang['sms_broadcast_validation_cancel_and_send'] = "¡Campaña ya cancelada o enviada!";
