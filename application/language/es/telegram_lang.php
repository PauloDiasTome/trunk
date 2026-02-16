<?php

defined('BASEPATH') or exit('No direct script access allowed');

// View Telegram
$lang['telegram_channel_header'] = "Publicaciones Telegram Canales";
$lang['telegram_channel_subheader'] = "Publicaciones Telegram Canales";
$lang['telegram_channel_btn_new'] = "Nuevo";
$lang['telegram_channel_btn_filter'] = "Filtrar";
$lang['telegram_channel_btn_export'] = "Exportar";

// View 
$lang['telegram_channel_view'] = "Visualizar campaña";
$lang['telegram_channel_view_information'] = "Información de la campaña";
$lang['telegram_channel_view_timeline'] = "Línea de tiempo";
$lang['telegram_channel_canceled_broadcast_timeline_view'] = "Campaña cancelada";

$lang['telegram_channel_timeline_creation'] = "creó la campaña";
$lang['telegram_channel_timeline_paused'] = "pausó la campaña";
$lang['telegram_channel_timeline_resume'] = "reanudó la campaña";
$lang['telegram_channel_timeline_resend'] = "reenvió la campaña";
$lang['telegram_channel_timeline_edited'] = "editó la campaña";
$lang['telegram_channel_timeline_canceled'] = "canceló la campaña";
$lang['telegram_channel_timeline_send_now'] = "cambió para enviar ahora";
$lang['telegram_channel_timeline_partial'] = "configuró la campaña para enviar parcialmente";
$lang['telegram_channel_timeline_exceed'] = "configuró la campaña para ignorar el período de envío";
$lang['telegram_channel_timeline_creation_api'] = "Campaña creada vía integración de sistema externo";
$lang['telegram_channel_timeline_add_expire'] = "añadió una vigencia para la campaña hasta %s";
$lang['telegram_channel_timeline_remove_expire'] = "eliminó la vigencia de la campaña";
$lang['telegram_channel_timeline_edited_expire'] = "modificó la vigencia de la campaña de %s a %s";

$lang['telegram_channel_view_segmented'] = "Campaña segmentada";
$lang['telegram_channel_view_segmented_yes'] = "Sí";
$lang['telegram_channel_view_segmented_no'] = "No";
$lang['telegram_channel_view_selected_group'] = "Grupo seleccionado";

// View find 
$lang['telegram_channel_topnav'] = "Publicaciones Telegram Canales";
$lang['telegram_channel_column_btn_pause'] = "Pausar";
$lang['telegram_channel_column_btn_resume'] = "Reanudar";
$lang['telegram_channel_column_btn_cancel'] = "Cancelar";
$lang['telegram_channel_column_arquive'] = "Archivo";
$lang['telegram_channel_column_scheduling'] = "Fecha Programada";
$lang['telegram_channel_column_info'] = "Campaña / Fecha";
$lang['telegram_channel_column_channel'] = "Canal";
$lang['telegram_channel_column_title'] = "Título";
$lang['telegram_channel_column_status'] = "Estado";

// View filter
$lang['telegram_channel_filter_title'] = "Filtrar campaña";
$lang['telegram_channel_filter_search'] = "Título";
$lang['telegram_channel_filter_search_placeholder'] = "Buscar...";
$lang['telegram_channel_filter_channel'] = "Canal";
$lang['telegram_channel_filter_status'] = "Estado";
$lang['telegram_channel_filter_status_select'] = "Seleccionar...";
$lang['telegram_channel_filter_status_processing'] = "Esperando envío";
$lang['telegram_channel_filter_status_sending'] = "En curso";
$lang['telegram_channel_filter_status_send'] = "Enviado";
$lang['telegram_channel_filter_status_called_off'] = "Cancelado";
$lang['telegram_channel_filter_period'] = "Período";
$lang['telegram_channel_filter_period_placeholder_date_start'] = "Fecha inicial";
$lang['telegram_channel_filter_period_placeholder_date_end'] = "Fecha final";
$lang['telegram_channel_filter_btn_return'] = "Volver";
$lang['telegram_channel_filter_btn_search'] = "Buscar";

// View export
$lang['telegram_channel_export_title'] = "Exportación de datos";
$lang['telegram_channel_export_email'] = "¿Desea enviar los datos al correo:";
$lang['telegram_channel_export_email_placeholder'] = "Ingrese el correo electrónico para exportación";
$lang['telegram_channel_export_btn_confirm'] = "Confirmar";
$lang['telegram_channel_export_btn_return'] = "Volver";

// View add // 
$lang['telegram_channel_add_title'] = "Nuevo";
$lang['telegram_channel_add_information'] = "Añadir nueva campaña";

// View edit //
$lang['telegram_channel_edit_title'] = "Editar";
$lang['telegram_channel_edit_broadcast_audio'] = "Campaña en audio";
$lang['telegram_channel_edit_broadcast_placeholder'] = "Descripción (opcional)";
$lang['telegram_channel_edit_selected_channel'] = "Canal seleccionado";
$lang['telegram_channel_edit_selected_group'] = "Grupo seleccionado";

// Generic
$lang['telegram_channel_title'] = "Título";
$lang['telegram_channel_date_scheduling'] = "Fecha de programación";
$lang['telegram_channel_date_scheduling_placeholder'] = "Seleccionar fecha";
$lang['telegram_channel_hour_scheduling'] = "Hora de programación";
$lang['telegram_channel_hour_scheduling_placeholder'] = "Seleccionar hora";
$lang['telegram_channel_automatic_response'] = "¿Añadir esta campaña como respuesta automática?";
$lang['telegram_channel_automatic_response_view'] = "Campaña añadida como respuesta automática";
$lang['telegram_channel_automatic_response_yes'] = "Sí";
$lang['telegram_channel_automatic_response_no'] = "No";
$lang['telegram_channel_segments_yes'] = "Sí";
$lang['telegram_channel_segments_no'] = "No";
$lang['telegram_channel_segments_campaign'] = "¿Añadir campaña segmentada?";
$lang['telegram_channel_segments_select'] = "Seleccione el segmento";
$lang['telegram_channel_segments_select_placeholder'] = "Seleccionar";
$lang['telegram_channel_segments_select_group'] = "Seleccionar grupo";
$lang['telegram_channel_segments_select_group_placeholder'] = "Seleccionar...";
$lang['telegram_channel_time_start_validity'] = "Hasta el día";
$lang['telegram_channel_hour_start_validity'] = "Hasta la hora";
$lang['telegram_channel_select'] = "Seleccionar...";
$lang['telegram_channel_select_channel'] = "Seleccionar canal";
$lang['telegram_channel_select_channel_view'] = "Canal seleccionado";
$lang['telegram_channel_type_photo'] = "Crear campaña de medios";
$lang['telegram_channel_type_text'] = "Crear campaña de texto";
$lang['telegram_channel_message'] = "Mensaje: (Hasta 1024 caracteres) | Caracteres restantes:";
$lang['telegram_channel_status_message_placeholder'] = "Escriba aquí...";
$lang['telegram_channel_loading_arquive'] = "Cargar fotos o vídeos (<b>jpeg</b>, <b>jpg</b>, <b>pdf</b> o <b>mp4</b> de hasta <b>10 MB</b>)";
$lang['telegram_channel_title_drop'] = "¿Listo para añadir algo?";
$lang['telegram_channel_subtitle_drop'] = "Arrastra fotos y vídeos aquí para comenzar.";
$lang['telegram_channel_btn_cancel'] = "Cancelar";
$lang['telegram_channel_btn_return'] = "Volver";
$lang['telegram_channel_btn_campaign_preview'] = "Vista previa de la campaña";
$lang['telegram_channel_btn_save'] = "Guardar";
$lang['telegram_channel_choose_type'] = "Seleccione el tipo de campaña";
$lang['telegram_channel_alert_input_required'] = "Este campo es obligatorio.";
$lang['telegram_channel_unsegmented_campaign'] = "Campaña no segmentada (no es posible modificar)";
$lang['telegram_channel_text_caracter'] = "Caracteres restantes:";

// Modal Preview Campaign //
$lang['telegram_channel_campaign_preview'] = "Vista previa de la campaña";
$lang['telegram_channel_campaign_preview_btn_close'] = "Cerrar";
$lang['telegram_channel_campaign_preview_btn_send_preview'] = "Enviar vista previa";
$lang['telegram_channel_campaign_preview_text_movel'] = "Ingrese el número de su celular y le enviaremos una vista previa de la campaña en su WhatsApp, así podrá visualizar la campaña antes de dispararla a la base.";
$lang['telegram_channel_campaign_preview_text_optin'] = "Recuerde que el número debe haber realizado el opt-in completo en el canal seleccionado.";
$lang['telegram_channel_campaign_preview_text_number_fone'] = "Este campo es obligatorio.";
$lang['telegram_channel_campaign_preview_text_preview_info_success'] = "¡Vista previa de la campaña enviada con éxito!";
$lang['telegram_channel_campaign_preview_text_preview_info_message'] = "Recibirá la campaña en hasta 30 minutos. Si tarda más que eso, contacte con el customer success.";
$lang['telegram_channel_campaign_preview_channel_send'] = "Seleccione un canal para envío de la vista previa";
$lang['telegram_channel_campaign_preview_channel_receive'] = "Ingrese un número de teléfono para recibir la vista previa";

$lang['telegram_channel_campaign_preview_atention'] = "Atención";
$lang['telegram_channel_campaign_preview_the_number'] = "El número ";
$lang['telegram_channel_campaign_preview_no_optin'] = " no tiene opt-in con el canal ";
$lang['telegram_channel_campaign_preview_ok'] = "¡Ok!";
$lang['telegram_channel_campaign_preview_select'] = "Seleccionar";
$lang['alert-field-validation_image'] = "Este campo es obligatorio.";

// Modal campaign estimate
$lang['campaign_estimate_title'] = "Atención al tiempo de envío de la campaña programada";
$lang['campaign_estimate_notify_on_modal'] = "La previsión de finalización de esta campaña supera los horarios establecidos para el canal. ¿Qué desea hacer?";
$lang['campaign_estimate_suspend_campaign'] = "Suspender Campaña ";
$lang['campaign_estimate_suspend_campaign_extension'] = "Cancelar inmediatamente la programación de la campaña.";
$lang['campaign_estimate_review_queue'] = "Revisar cola de envío";
$lang['campaign_estimate_review_queue_extension'] = "Gestione las campañas para liberar espacio en la cola";
$lang['campaign_estimate_change_date'] = "Modificar fecha de envío";
$lang['campaign_estimate_change_date_extension'] = "Elija otra fecha y horario para realizar el disparo de la campaña.";
$lang['campaign_estimate_send_partially'] = "Enviar parcialmente";
$lang['campaign_estimate_send_partially_extension'] = "Tenga en cuenta que solo parte de su base recibirá la campaña.";
$lang['campaign_estimate_send_after_hours'] = "Enviar después del horario";
$lang['campaign_estimate_send_after_hours_extension'] = "Utilice el tiempo necesario para enviar la campaña a toda la base.";
$lang['campaign_estimate_channel_info'] = "Estos son los números que están en conflicto: ";
$lang['campaign_estimate_channel_info1'] = " y otros.";
$lang['campaign_estimate_suspend_notify_title'] = "¿Está seguro?";
$lang['campaign_estimate_suspend_notify_text'] = "¿Desea cancelar la programación de esta campaña?";
$lang['campaign_estimate_suspend_notify_ok'] = "Sí";
$lang['campaign_estimate_suspend_notify_cancel'] = "No";
$lang['campaign_estimate_suspend_notify_deleted_title'] = "¡Eliminado!";
$lang['campaign_estimate_suspend_notify_notify_deleted_text'] = "¡Campaña eliminada con éxito!";
$lang['campaign_estimate_partial_notify_title'] = "¿Está seguro?";
$lang['campaign_estimate_partial_notify_text'] = "Esta campaña será enviada respetando el horario límite previamente configurado para los canales seleccionados.";
$lang['campaign_estimate_partial_notify_ok'] = "Sí";
$lang['campaign_estimate_partial_notify_cancel'] = "No";
$lang['campaign_estimate_partial_notify_add_title'] = "¡Publicación registrada!";
$lang['campaign_estimate_partial_notify_sucess'] = "¡Campaña programada con éxito!";
$lang['campaign_estimate_partial_notify_notify_deleted_text'] = "¡Campaña eliminada con éxito!";
$lang['campaign_estimate_after_notify_text'] = "Esta campaña será enviada a toda la base de contactos sin límite de tiempo.";

// Modal campaign overlap
$lang['campaign_overlap_title'] = "Aviso de impacto de la campaña";
$lang['campaign_overlap_notify_on_modal'] = "La nueva campaña afectará sus disparos de hoy. ¿Qué desea hacer?";
$lang['campaign_overlap_channel_info'] = "Este es el número que está en conflicto: ";
$lang['campaign_overlap_channels_info'] = "Estos son los números que están en conflicto: ";
$lang['campaign_overlap_channel_info_others'] = " y otros.";
$lang['campaign_overlap_suspend_campaign'] = "Suspender Campaña ";
$lang['campaign_overlap_suspend_campaign_extension'] = "Cancelar inmediatamente la programación de la campaña.";
$lang['campaign_overlap_change_date'] = "Modificar fecha de envío";
$lang['campaign_overlap_change_date_extension'] = "Elija otra fecha y horario para realizar el disparo de la campaña.";
$lang['campaign_overlap_send_after_hours'] = "Enviar de todas formas";
$lang['campaign_overlap_send_after_hours_extension'] = "Utilice el tiempo necesario para enviar las campañas a toda la base.";

// column
$lang['telegram_channel_datatable_column_status_processing'] = "Esperando envío";
$lang['telegram_channel_datatable_column_status_send'] = "Enviado";
$lang['telegram_channel_datatable_column_status_called_off'] = "Cancelado";
$lang['telegram_channel_datatable_column_status_paused'] = "Pausado";
$lang['telegram_channel_datatable_column_status_sending'] = "En curso";

// dropdown
$lang['telegram_channel_datatable_column_action_view'] = "Visualizar";
$lang['telegram_channel_datatable_column_action_cancel'] = "Cancelar";
$lang['telegram_channel_datatable_column_action_pause'] = "Pausar";
$lang['telegram_channel_datatable_column_action_pause_distribution'] = "Pausar distribución";
$lang['telegram_channel_datatable_column_action_resume'] = "Reanudar";
$lang['telegram_channel_datatable_column_action_resume_distribution'] = "Reanudar distribución";

// alert change type
$lang['telegram_channel_alert_change_type_title'] = "¿Está seguro?";
$lang['telegram_channel_alert_change_type_text'] = "Si modifica el tipo de campaña, perderá la edición actual.";
$lang['telegram_channel_alert_change_type_yes'] = "Sí";
$lang['telegram_channel_alert_change_type_no'] = "No";

// alert delete group
$lang['telegram_channel_alert_group_delete_title'] = "¿Está seguro?";
$lang['telegram_channel_alert_group_delete_text'] = "¿Desea eliminar la(s) campaña(s) seleccionada(s)?";
$lang['telegram_channel_alert_group_delete_confirmButtonText'] = "Sí";
$lang['telegram_channel_alert_group_delete_cancelButtonText'] = "No";

$lang['telegram_channel_alert_group_delete_two_title'] = "¡Eliminado!";
$lang['telegram_channel_alert_group_delete_two_text'] = "¡Campaña(s) eliminada(s) con éxito!";

// alert delete
$lang['telegram_channel_alert_delete_title'] = "¿Está seguro?";
$lang['telegram_channel_alert_delete_text'] = "¿Desea eliminar esta campaña?";
$lang['telegram_channel_alert_delete_confirmButtonText'] = "Sí";
$lang['telegram_channel_alert_delete_cancelButtonText'] = "No";

$lang['telegram_channel_alert_delete_two_title'] = "¡Eliminado!";
$lang['telegram_channel_alert_delete_two_text'] = "¡Campaña eliminada con éxito!";

// alert resend
$lang['telegram_channel_alert_resend'] = "Reenviar";
$lang['telegram_channel_alert_resend_title'] = "¿Está seguro?";
$lang['telegram_channel_alert_resend_text'] = "¿Desea reenviar todas las colas de esta campaña?";
$lang['telegram_channel_alert_resend_confirmButtonText'] = "Sí";
$lang['telegram_channel_alert_resend_cancelButtonText'] = "No";
$lang['telegram_channel_alert_resend_two_title'] = "¡Reenviado!";
$lang['telegram_channel_alert_resend_two_text'] = "¡Campaña reenviada con éxito!";

// alert generic
$lang['telegram_channel_alert_broadcast_title'] = "¿Está seguro?";
$lang['telegram_channel_alert_broadcast_confirmButtonText'] = "Sí";
$lang['telegram_channel_alert_broadcast_cancelButtonText'] = "No";
$lang['telegram_channel_alert_broadcast_two_title'] = "¡Completado!";

// alert pause
$lang['telegram_channel_alert_broadcast_pause_text'] = "¿Desea pausar la campaña seleccionada?";
$lang['telegram_channel_alert_broadcast_pause_all_text'] = "¿Desea pausar todas las campañas seleccionadas?";
$lang['telegram_channel_alert_broadcast_pause_two_text'] = "¡Campaña pausada con éxito!";
$lang['telegram_channel_alert_broadcast_pause_two_all_text'] = "¡Campañas pausadas con éxito!";

// alert resume
$lang['telegram_channel_alert_broadcast_resume_text'] = "¿Desea reanudar la campaña seleccionada?";
$lang['telegram_channel_alert_broadcast_resume_all_text'] = "¿Desea reanudar todas las campañas seleccionadas?";
$lang['telegram_channel_alert_broadcast_resume_two_text'] = "¡Campaña reanudada con éxito!";
$lang['telegram_channel_alert_broadcast_resume_two_all_text'] = "¡Campañas reanudadas con éxito!";

// alert error
$lang['telegram_channel_error_broadcast_title'] = "¡Error!";
$lang['telegram_channel_error_broadcast_message'] = "Ocurrió un error al ejecutar la acción";

// alert export
$lang['telegram_channel_alert_export_title'] = "¡Lista enviada!";
$lang['telegram_channel_alert_export_text'] = "El correo electrónico puede tardar hasta una hora en llegar.";
$lang['telegram_channel_alert_export_confirmButtonText'] = "¡Ok!";

// alert dropzone
$lang['telegram_channel_alert_dropzone_title'] = "Atención";
$lang['telegram_channel_alert_dropzone_text'] = "¡Ha superado el límite de 2 imágenes!";
$lang['telegram_channel_alert_dropzone_confirmButtonText'] = "¡Ok!";

$lang['telegram_channel_alert_dropzone_two_title'] = "Atención";
$lang['telegram_channel_alert_dropzone_two_text'] = "Límite máximo de 10 MB por archivo";
$lang['telegram_channel_alert_dropzone_two_confirmButtonText'] = "¡Ok!";

$lang['telegram_channel_alert_dropzone_three_title'] = "Atención";
$lang['telegram_channel_alert_dropzone_three_text'] = "¡No se permiten imágenes PNG!";
$lang['telegram_channel_alert_dropzone_three_confirmButtonText'] = "¡Ok!";

$lang['telegram_channel_alert_dropzone_three_extensions'] = "Solo se aceptan archivos (jpeg, jpg, pdf o mp4 de hasta 10 MB)";

// Alert check time to edit
$lang['telegram_channel_error_title'] = "Error";
$lang['telegram_channel_error_ta023'] = "No es posible editar esta campaña. El tiempo para edición es de hasta 1 hora antes del envío.";
$lang['telegram_channel_error_ta024'] = "No es posible editar una campaña que ya fue enviada, cancelada o que esté en progreso.";

// Alert datatables
$lang['telegram_channel_datatables_edit_column_action'] = "Editar";
$lang['telegram_channel_datatables_edit_status_2'] = "No es posible editar una campaña que fue enviada.";
$lang['telegram_channel_datatables_edit_status_5'] = "No es posible editar una campaña que fue cancelada.";
$lang['telegram_channel_datatables_edit_status_6'] = "No es posible editar una campaña que está en curso.";
$lang['telegram_channel_datatables_edit_default'] = "No es posible editar esta campaña.";
$lang['telegram_channel_datatables_edit_less_than_one_hour'] = "No es posible editar esta campaña. El tiempo para edición es de hasta 1 hora antes del envío.";

// View form validation
$lang['telegram_channel_check_date'] = "La fecha de programación es menor que la fecha actual";
$lang['telegram_channel_check_hour'] = "¡La hora de programación debe ser como mínimo 30 minutos mayor que la hora actual!";
$lang['telegram_channel_check_date_validity'] = "La fecha de vigencia es menor que la fecha de programación";
// $lang['telegram_channel_check_hour_validity'] = "La hora de vigencia debe ser como mínimo una hora mayor que la hora de programación";
$lang['telegram_channel_check_hour_validity'] = "La hora de vigencia es menor o igual a la hora de programación";
$lang['telegram_channel_alert_field_validation'] = "Este campo es obligatorio.";

$lang['telegram_channel_validation_message'] = "Mensaje";
$lang['telegram_channel_validation_img'] = "Imagen";
$lang['telegram_channel_validation_cancel_title'] = "Error";
$lang['telegram_channel_validation_cancel_description'] = "¡Inténtelo de nuevo más tarde!";
$lang['telegram_channel_validation_cancel'] = "¡Campaña ya cancelada!";

$lang['whatsapp_broadcas_guia_1'] = "Conozca nuestra";
$lang['whatsapp_broadcas_guia_2'] = "¡Guía de buenas prácticas!";