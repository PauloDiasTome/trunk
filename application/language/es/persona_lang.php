<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Vista Waba
$lang['persona_waba_header'] = "Personas";
$lang['persona_waba_edit'] = "Editar Persona";
$lang['persona_waba_add'] = "Nueva Persona";

// Vista buscar
$lang['persona_topnav'] = "Personas";

$lang['persona_btn_new'] = "Nuevo";
$lang['persona_btn_export'] = "Exportar";

$lang['persona_column_name'] = "Nombre";
$lang['persona_column_channel'] = "Canal";
$lang['persona_column_creation'] = "Fecha de creación";
$lang['persona_column_subscribers'] = "Suscriptores";
$lang['persona_column_action_export'] = "Exportar";
$lang['persona_column_action_edit'] = "Editar";
$lang['persona_column_action_delete'] = "Eliminar";

$lang['persona_tooltip_update'] = "Se actualiza cada 7 días";
$lang['persona_row_subscriber'] = " Suscriptor";
$lang['persona_row_subscribers'] = " Suscriptores";

$lang['persona_export_title'] = "Exportación de datos";
$lang['persona_export_email'] = "¿Desea enviar los datos al correo electrónico?";
$lang['persona_export_btn_confirm'] = "Confirmar";
$lang['persona_export_btn_return'] = "Volver";

$lang['persona_export_contacts_title'] = "Exportación de datos";
$lang['persona_export_contacts_email'] = "¿Desea enviar los datos al correo electrónico?";
$lang['persona_export_contacts_btn_confirm'] = "Confirmar";
$lang['persona_export_contacts_btn_return'] = "Volver";
$lang['persona_export_contacts_no_permit_title'] = "¡Límite de exportaciones alcanzado!";
$lang['persona_export_contacts_no_permit_content'] = "Se han realizado demasiadas exportaciones por este usuario, intente nuevamente más tarde";

// Vista agregar
$lang['persona_add_title'] = "Nuevo";
$lang['persona_add_select_channel'] = "Seleccionar canal";
$lang['persona_add_select_channel_placeholder'] = "Seleccionar";
$lang['persona_add_btn_cancel'] = "Cancelar";

// Vista editar
$lang["persona_edit_title"] = "Editar";
$lang['persona_edit_btn_return'] = "Volver";
$lang['persona_edit_selected_channel'] = "Canal seleccionado";

// Vista agregar | editar
$lang['persona_name'] = "Nombre";
$lang['persona_name_placeholder'] = "Ingrese el nombre";

$lang['persona_link_add_contacts'] = "Agregar contactos";
$lang['persona_link_import'] = "Importar";

$lang['persona_btn_save'] = "Guardar";
$lang['persona_number_of_contacts'] = "Número de contactos: ";
$lang['persona_add_picture'] = "AGREGAR UNA IMAGEN";

// Modal agregar participantes
$lang['persona_participants_title'] = "Agregar Participantes";
$lang['persona_participants_placeholder'] = "Ingrese el número o nombre";
$lang['persona_participants_btn_confirm'] = "Confirmar";
$lang['persona_participants_btn_cancel'] = "Limpiar";

// Modal agregar participantes - JS
$lang['persona_participants_select_all'] = "Seleccionar todos";
$lang['persona_participants_no_contacts'] = "No se encontraron contactos";

// Modal importar
$lang['persona_import_title'] = "Importar contactos";
$lang['persona_import_alert_import'] = "Atención con los estándares de inserción de datos.";
$lang['persona_import_obs'] = "Copia tu lista de contactos (Excel o Google Sheets) y pégalos abajo, luego haz clic en continuar.";
$lang['persona_import_btn_import_advance'] = "Avanzar";
$lang['persona_import_column_relate_data'] = "Relaciona las columnas con los tipos de datos";
$lang['persona_import_btn_import_contacts'] = "Importar contactos";

// Modal importar - JS
$lang['persona_import_select_data_type'] = "Selecciona el tipo de dato";
$lang['persona_import_option_name'] = "Nombre";
$lang['persona_import_option_phone'] = "Teléfono";
$lang['persona_import_option_email'] = "Correo electrónico";

// Modal progreso
$lang['persona_progress_title_save'] = "Guardando datos";
$lang['persona_progress_title_import'] = "Importando datos";
$lang['persona_progress_body'] = "Por favor espere, no cierre esta pestaña del navegador.";

// Modal progreso - JS
$lang['persona_progress_default'] = "Procesando...";
$lang['persona_progress_import'] = "Importados {1} de {2} contactos";
$lang['persona_progress_save'] = "Guardando...";

// Alerta guardar - JS
$lang['persona_alert_empty_title'] = "¡Atención!";
$lang['persona_alert_empty_text'] = "Ingresa al menos un participante.";
$lang['persona_alert_empty_text_confirmButtonText'] = "Ok";

// Alerta cancelar persona | cambiar canal
$lang['persona_alert_title'] = "¡Atención!";
$lang['persona_alert_text'] = "Toda la selección de participantes se perderá";
$lang['persona_alert_btn_confirm'] = "Confirmar";
$lang['persona_alert_btn_cancel'] = "Cancelar";

// Alerta eliminar
$lang['persona_alert_delete_title'] = "¿Estás seguro?";
$lang['persona_alert_delete_text'] = "¿Realmente quieres eliminar esta persona?";
$lang['persona_alert_delete_confirmButtonText'] = "Sí";
$lang['persona_alert_delete_cancelButtonText'] = "No";
$lang['persona_alert_delete_sucess'] = "¡Eliminado con éxito!";
$lang['persona_alert_delete_two_confirmButtonTextt'] = "Ok";

// Alerta dropzone - JS
$lang['persona_alert_dropzone_maxSize_title'] = "¡Atención!";
$lang['persona_alert_dropzone_maxSize_text'] = "Límite máximo de 10 MB";
$lang['persona_alert_dropzone_maxSize_confirmButtonText'] = "Ok";

$lang['persona_alert_dropzone_archives_title'] = "¡Atención!";
$lang['persona_alert_dropzone_archives_text'] = "Solo se aceptan archivos (jpeg, jpg o png).";
$lang['persona_alert_dropzone_archives_confirmButtonText'] = "Ok";

// Alerta exportar
$lang['persona_alert_export_title'] = "¡Lista enviada!";
$lang['persona_alert_export_text'] = "El correo puede tardar hasta una hora en llegar.";
$lang['persona_alert_export_confirmButtonText'] = "Ok";

// Alerta importar
$lang['persona_alert_import_info'] = "Cada importación está sujeta a un límite máximo de 10 mil contactos.\nNo se importarán números duplicados.\nEl número debe tener DDI y DDD.\nEl nombre debe tener hasta 100 caracteres.\nEl correo electrónico debe tener hasta 55 caracteres.";
$lang['persona_alert_import_info_preview'] = "Los datos resaltados en rojo indican información duplicada.\nNo se importarán números duplicados.\nEl número debe tener DDI y DDD.\nEl nombre debe tener hasta 100 caracteres.\nEl correo electrónico debe tener hasta 55 caracteres.";

// Alerta importar - JS
$lang['persona_alert_import_title'] = "¡Atención!";
$lang['persona_alert_import_phone_select'] = "Al menos una columna debe contener el número de teléfono.";
$lang['persona_alert_import_contact_number'] = "Los números no siguen los estándares de inserción de datos.";
$lang['persona_alert_import_contact_limit_exceeded'] = "Cada importación está sujeta a un límite máximo de 10 mil contactos.";

// Alerta información canal  - JS
$lang['persona_alert_channel_btn_import_contacts'] = "Selecciona un canal para importar";
$lang['persona_alert_channel_btn_add_contacts'] = "Selecciona un canal para obtener contactos";

// Validación del formulario - JS
$lang['persona_alert_name'] = "El campo Nombre es obligatorio.";
$lang['persona_alert_channel'] = "El campo Canal es obligatorio.";
$lang['persona_alert_name_min_length'] = "El campo Nombre debe contener al menos 3 caracteres.";
