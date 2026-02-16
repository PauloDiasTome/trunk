<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Título Waba
$lang['contact_waba_header'] = "Contactos";
$lang['contact_waba_add'] = "Nuevo contacto";
$lang['contact_waba_edit'] = "Editar contacto";

// Vista de búsqueda
$lang['contact_topnav'] = "Contactos";
$lang['contact_btn_new'] = "Nuevo";
$lang['contact_btn_filter'] = "Filtrar";
$lang['contact_btn_consolidate_base'] = "Consolidar base";
$lang['contact_btn_import'] = "Importar archivo txt";
$lang['contact_btn_export'] = "Exportar";
$lang['contact_btn_delete'] = "Eliminar";
$lang['contact_btn_block'] = "Bloquear";
$lang['contact_btn_unblock'] = "Desbloquear";

$lang['contact_column_contact'] = "Contacto";
$lang['contact_column_order'] = "Nº Fila";
$lang['contact_column_channel'] = "Canal";
$lang['contact_column_labels'] = "Etiquetas";
$lang['contact_column_date'] = "Fecha";
$lang['contact_column_creation_date'] = "Fecha de creación";
$lang['contact_column_status'] = "Situación";

$lang['contact_filter_title'] = "Filtrar contactos";
$lang['contact_filter_search'] = "Contacto";
$lang['contact_filter_search_placeholder'] = "Buscar...";
$lang['contact_filter_search_option_starts_with'] = "Empieza con";
$lang['contact_filter_search_option_contains'] = "Contiene";
$lang['contact_filter_channel'] = "Canal";
$lang['contact_filter_channel_placeholder'] = "Seleccionar...";
$lang['contact_filter_persona'] = "Personas";
$lang['contact_filter_persona_placeholder'] = "Seleccionar...";
$lang['contact_filter_tag'] = "Etiquetas";
$lang['contact_filter_tag_placeholder'] = "Seleccionar...";
$lang['contact_filter_responsible'] = "Responsable";
$lang['contact_filter_responsible_select'] = "Seleccionar...";
$lang['contact_filter_situation'] = "Situación";
$lang['contact_filter_situation_select'] = "Seleccionar...";
$lang['contact_filter_situation_verified'] = "Verificado";
$lang['contact_filter_situation_not_verified'] = "No verificado";
$lang['contact_filter_situation_no_whatsapp_account'] = "Sin cuenta de WhatsApp";
$lang['contact_filter_situation_spam'] = "Bloqueado";
$lang['contact_filter_period'] = "Período";
$lang['contact_filter_period_placeholder_date_start'] = "Fecha inicial";
$lang['contact_filter_period_placeholder_date_end'] = "Fecha final";
$lang['contact_filter_last_received_campaign'] = "Fecha de última campaña recibida";
$lang['contact_filter_last_received_campaign_placeholder_date_start'] = "De";
$lang['contact_filter_last_received_campaign_placeholder_date_end'] = "Hasta";
$lang['contact_filter_btn_return'] = "Volver";
$lang['contact_filter_btn_search'] = "Buscar";

$lang['contact_export_title'] = "Exportación de datos";
$lang['contact_export_email'] = "¿Desea enviar los datos al correo electrónico:";
$lang['contact_export_email_placeholder'] = "Ingrese el correo electrónico para la exportación";
$lang['contact_export_btn_confirm'] = "Confirmar";
$lang['contact_export_btn_return'] = "Volver";

$lang['contact_messenger_open'] = "Abrir Messenger";
$lang['contact_messenger_btn_cancel'] = "Cancelar";
$lang['contact_messenger_btn_ok'] = "Ok";

$lang['contact_dropdown_menu_all'] = "Todos";
$lang['contact_dropdown_menu_empty'] = "Ninguno";

// Persona
$lang['contact_btn_add_persona'] = "Añadir Persona";

$lang['contact_persona_modal_action_title'] = "Creando persona";
$lang['contact_persona_modal_action_info'] = "Añadiendo contactos: ";

$lang['contact_modal_persona_title'] = "Añadir nueva persona";
$lang['contact_modal_persona_picture'] = "Añadir una imagen";
$lang['contact_modal_persona_name'] = "Nombre";
$lang['contact_modal_persona_name_placeholder'] = "Ingrese nombre";
$lang['contact_modal_persona_channel'] = "Canal seleccionado";
$lang['contact_modal_persona_btn_return'] = "Volver";
$lang['contact_modal_persona_btn_save'] = "Guardar";

$lang['contact_alert_title'] = "¡Atención!";
$lang['contact_alert_confirmButtonText'] = "Ok";

$lang['contact_alert_dropzone_maxSize_text'] = "Límite máximo de 10 MB";
$lang['contact_alert_dropzone_archives_text'] = "Solo se aceptan archivos (jpeg, jpg o png).";
$lang['contact_alert_contact_blocked_text'] = "Se han seleccionado contactos bloqueados. Desmarque los contactos bloqueados para continuar.";

$lang['contact_alert_created_persona_title'] = "¡Añadido!";
$lang['contact_alert_created_persona_text'] = "¡Persona creada con éxito!";
$lang['contact_alert_persona_multiple_channels_text'] = "La opción de agregar personas está deshabilitada porque este contacto está vinculado a múltiples canales. Para administrar personas, asegúrese de que el contacto esté asociado a un solo canal.";

// Agregar y editar genérico
$lang['contact_record'] = "Registro";
$lang['contact_name'] = "Nombre";
$lang['contact_name_placeholder'] = "Ingrese nombre";
$lang['contact_channel'] = "Canal";
$lang['contact_talkall'] = "ID de TalkAll";
$lang['contact_email'] = "Correo electrónico";
$lang['contact_order'] = "Nº Fila";
$lang['contact_email_placeholder'] = "Ingrese correo electrónico";
$lang['contact_order_placeholder'] = "Ingrese Nº";
$lang['contact_label'] = "Etiquetas";
$lang['contact_label_placeholder'] = "Ninguno";
$lang['contact_service_internship'] = "Estado del servicio";
$lang['contact_responsible'] = "Responsable";
$lang['contact_responsible_placeholder'] = "Ninguno";
$lang['contact_notes'] = "Notas";
$lang['contact_notes_placeholder'] = "Escriba aquí...";
$lang['contact_notes_character'] = "(Hasta 550 caracteres) | Caracteres restantes:";
$lang['contact_btn_save'] = "Guardar";
$lang['contact_btn_return'] = "Volver";
$lang['contact_delete_block_list'] = "Desbloquear";
$lang['contact_all_checkbox'] = "Todos los ";

// Vista de edición
$lang['contact_edit_title'] = "Editar";
$lang['contact_edit_information'] = "Información de contacto";
$lang['contact_edit_input_order_tooltip'] = "Ingrese un número para cambiar el orden de recepción de la campaña para este contacto.";

// Variables globales de JS
$lang['contact_datatable_column_situation_user_verify'] = "Contacto no verificado";
$lang['contact_datatable_column_situation_user_verified'] = "Contacto verificado y sin cuenta de WhatsApp!";
$lang['contact_datatable_column_situation_user_verified_whatsapp'] = "Contacto verificado y tiene cuenta en WhatsApp!";
$lang['contact_datatable_column_situation_user_spam'] = "¡Contacto bloqueado!";
$lang['contact_datatable_dropdown_menu_edit'] = "Editar";
$lang['contact_datatable_dropdown_menu_delete'] = "Eliminar";
$lang['contact_datatable_dropdown_menu_block_list'] = "Bloquear";
$lang['contact_js_replace_data'] = "Ninguno";
$lang['contact_js_let_text_hello'] = "Hola";
$lang['contact_js_label_info_contact_one'] = "¿Desea abrir una conversación con el";
$lang['contact_js_label_info_contact_two'] = "en messenger?";

// Alerta de eliminación
$lang['contact_alert_delete_title'] = "¿Estás seguro?";
$lang['contact_alert_delete_text_contact'] = "¿Eliminar este(s) {{number}} contacto(s)?";
$lang['contact_alert_delete_cancelButtonText'] = "No";
$lang['contact_alert_delete_confirmButtonText'] = "Sí";

// Alerta de bloqueo
$lang['contact_alert_block_title'] = "¿Estás seguro?";
$lang['contact_alert_block_text_contact'] = "¿Bloquear este(s) {{number}} contacto(s)?";
$lang['contact_alert_block_confirmButtonText'] = "Sí";
$lang['contact_alert_block_cancelButtonText'] = "No";

// Alerta de desbloqueo
$lang['contact_alert_unblock_title'] = "¿Estás seguro?";
$lang['contact_alert_unblock_text_contact'] = "¿Desbloquear este(s) {{number}} contacto(s)?";
$lang['contact_alert_unblock_confirmButtonText'] = "Sí";
$lang['contact_alert_unblock_cancelButtonText'] = "No";

// Alerta de contacto en atención
$lang['contact_alert_attendance_title'] = "¡Atención!";
$lang['contact_alert_attendance_text'] = "La acción no se ha completado porque hay {{number}} contacto(s) en atención";
$lang['contact_alert_attendance_confirmButtonText'] = "¡Ok!";

// Alerta de exportación
$lang['contact_alert_export_title'] = "¡Lista enviada!";
$lang['contact_alert_export_text'] = "El correo electrónico puede tardar hasta una hora en llegar.";
$lang['contact_alert_export_confirmButtonText'] = "¡Ok!";

// Información
$lang['contact_info_all'] = "<b>{{number}} contacto(s) seleccionado(s)</b>";
$lang['contact_info_description'] = "Por favor espera, no cierres esta pestaña del navegador.";
$lang['contact_info_processing'] = "Procesando";
$lang['contact_info_the'] = "de";

// Modal de acción
$lang['contact_modal_action_title_delete'] = "Eliminando contacto(s)";
$lang['contact_modal_action_title_block'] = "Bloqueando contacto(s)";
$lang['contact_modal_action_title_unblock'] = "Desbloqueando contacto(s)";
$lang['contact_modal_action_delete'] = "Eliminando";
$lang['contact_modal_action_block'] = "Bloqueando";
$lang['contact_modal_action_unblock'] = "Desbloqueando";

$lang['contact_loading'] = "Cargando...";
$lang['contact_modal_label_title'] = "Agregar etiqueta";
$lang['contact_modal_label_btn_save'] = "Guardar";
$lang['contact_modal_label_btn_cancel'] = "Cancelar";
