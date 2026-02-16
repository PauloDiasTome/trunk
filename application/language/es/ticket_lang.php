<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Vista Waba
$lang['ticket_waba_header'] = "Tickets";
$lang['ticket_waba_add'] = "Nuevo Ticket";
$lang['ticket_waba_edit'] = "Editar Ticket";
$lang['ticket_waba_history'] = "Historial del Ticket";

// Vista encontrar
$lang['ticket_topnav'] = "Tickets";
$lang['ticket_btn_new'] = "Nuevo";
$lang['ticket_btn_filter'] = "Filtrar";
$lang['ticket_btn_export'] = "Exportar";

$lang['ticket_column_creation'] = "Fecha de Creación";
$lang['ticket_column_contact'] = "Contacto";
$lang['ticket_column_company'] = "Empresa";
$lang['ticket_column_user'] = "Usuario";
$lang['ticket_column_ticket_type'] = "Tipo de ticket";
$lang['ticket_column_ticket_status'] = "Estado del ticket";

$lang['ticket_filter_title'] = "Filtrar ticket";
$lang['ticket_filter_search'] = "Contacto o usuario";
$lang['ticket_filter_search_placeholder'] = "Buscar...";
$lang['ticket_filter_type_ticket'] = "Tipo de ticket";
$lang['ticket_filter_type_ticket_placeholder'] = "Seleccionar...";
$lang['ticket_filter_status_ticket'] = "Estado del ticket";
$lang['ticket_filter_period'] = "Período";
$lang['ticket_filter_period_notify'] = "Período seleccionado no disponible. Datos almacenados durante 90 días";
$lang['ticket_filter_period_placeholder_date_start'] = "Fecha de inicio";
$lang['ticket_filter_period_placeholder_date_end'] = "Fecha final";
$lang['ticket_filter_btn_return'] = "Volver";
$lang['ticket_filter_btn_search'] = "Buscar";

$lang['ticket_export_title'] = "Exportación de datos";
$lang['ticket_export_email'] = "¿Desea enviar los datos al correo electrónico:";
$lang['ticket_export_email_placeholder'] = "¿Ingrese el correo para exportación?";
$lang['ticket_export_btn_confirm'] = "Confirmar";
$lang['ticket_export_btn_return'] = "Volver";

// Genérico agregar, editar e historial
$lang['ticket_history'] = "Historial";
$lang['ticket_creation'] = "Creación";
$lang['ticket_contact'] = "Contacto";
$lang['ticket_search'] = "Buscar...";
$lang['ticket_company'] = "Empresa";
$lang['ticket_users'] = "Usuarios";
$lang['ticket_type'] = "Tipo de ticket";
$lang['ticket_subtype'] = "Subtipo de ticket";
$lang['ticket_select'] = "Seleccionar";
$lang['ticket_status'] = "Estado del ticket";
$lang['ticket_comment'] = "Comentario: (Hasta 1500 caracteres) | Caracteres restantes:";
$lang['ticket_btn_return'] = "Volver";
$lang['ticket_btn_save'] = "Guardar";
$lang['ticket_edit_title'] = "Editar";
$lang["ticket_edit_information"] = "Información del ticket";
$lang["ticket_add_information"] = "Agregar ticket";
$lang['ticket_add_title'] = "Nuevo";
$lang['ticket_dropdown_add_new_ticket_type'] = "Agregar nuevo tipo de ticket";
$lang['ticket_dropdown_add_new_ticket_company'] = "Agregar nueva empresa";
$lang['ticket_dropdown_add_new_ticket_status'] = "Agregar nuevo estado";
$lang['ticket_dropdown_add_new_ticket_type_title'] = "Haga clic aquí para agregar uno nuevo";
$lang['ticket_dropdown_add_new_ticket_company_title'] = "Haga clic aquí para agregar una nueva";
$lang['ticket_dropdown_add_new_ticket_status_title'] = "Haga clic aquí para agregar uno nuevo";
$lang['ticket_status_ticket_info'] = "¿Qué significa este estado para el ticket?";
$lang['ticket_status_open'] = "Abierto";
$lang['ticket_status_closed'] = "Cerrado";
$lang['ticket_status_name'] = "Nombre";

// Modal ticket company ///
$lang['ticket_modal_company_title'] = "Agregar empresa";
$lang['ticket_modal_company_creation'] = "Creación";
$lang['ticket_modal_company_corporate_name'] = "Razón Social";
$lang['ticket_modal_company_corporate_name_placeholder'] = "Ingrese Razón Social";
$lang['ticket_modal_company_cnpj'] = "CNPJ";
$lang['ticket_modal_company_cnpj_placeholder'] = "Ingrese CNPJ";
$lang['ticket_modal_company_fantasy_name'] = "Nombre Fantasía";
$lang['ticket_modal_company_fantasy_name_placeholder'] = "Ingrese nombre Fantasía";

$lang['ticket_modal_company_btn_save'] = "Guardar";
$lang['ticket_modal_company_btn_return'] = "Volver";

// Modal ticket type ///
$lang['ticket_modal_type_title'] = "Agregar tipo de ticket";
$lang['ticket_modal_type_name'] = "Nombre";
$lang['ticket_modal_type_name_placeholder'] = "Ingrese nombre";
$lang['ticket_modal_type_sector'] = "Sector";
$lang['ticket_modal_type_sla'] = "SLA del ticket";
$lang['ticket_modal_type_sla_null'] = "No hay SLA de ticket registrado";

$lang['ticket_modal_type_btn_save'] = "Guardar";
$lang['ticket_modal_type_btn_return'] = "Volver";

// Modal ticket status ///
$lang['ticket_modal_status_title'] = "Agregar estado del ticket";
$lang['ticket_modal_status_name'] = "Nombre";
$lang['ticket_modal_status_name_placeholder'] = "Ingrese nombre";
$lang['ticket_modal_status_cor'] = "Color";

$lang['ticket_modal_status_btn_save'] = "Guardar";
$lang['ticket_modal_status_btn_return'] = "Volver";

// JS

// variables globales de js
$lang['ticket_dt_columndefs_target5_title_history'] = "Historial";
$lang['ticket_dt_columndefs_target5_title_edit'] = "Editar";
$lang['ticket_dt_columndefs_target5_title_delete'] = "Eliminar";

$lang['ticket_modal_filter_dt_start_placeholder_initial_date'] = "Fecha de inicio";

// alerta eliminar
$lang['ticket_alert_delete_title'] = "¿Estás seguro?";
$lang['ticket_alert_delete_text'] = "¿Realmente quieres eliminar este ticket?";
$lang['ticket_alert_delete_confirmButtonText'] = "Sí";
$lang['ticket_alert_delete_cancelButtonText'] = "No";

$lang['ticket_alert_delete_two_title'] = "Atención";
$lang['ticket_alert_delete_two_text'] = "¡No se puede eliminar el ticket!";

$lang['ticket_alert_delete_three_title'] = "¡Eliminado!";
$lang['ticket_alert_delete_three_text'] = "¡Ticket eliminado con éxito!";

// alerta exportar
$lang['ticket_alert_export_title'] = "¡Lista enviada!";
$lang['ticket_alert_export_text'] = "El correo puede tardar hasta una hora en llegar.";
$lang['ticket_alert_export_confirmButtonText'] = "¡Ok!";

// alerta validación modal empresa
$lang['ticket_validation_modal_corporate_name_required'] = "El campo Nombre Corporativo es obligatorio.";
$lang['ticket_validation_modal_corporate_name_min_length'] = "El campo Nombre Corporativo debe tener al menos {param} carácter(es).";
$lang['ticket_validation_modal_corporate_name_max_length'] = "El campo Nombre Corporativo excedió el límite de {param} carácter(es).";
$lang['ticket_validation_modal_cnpj_required'] = "El campo CNPJ es obligatorio.";
$lang['ticket_validation_modal_cnpj_length'] = "El campo CNPJ debe tener {param} carácter(es).";
$lang['ticket_validation_modal_fantasy_name_required'] = "El campo Nombre Fantasía es obligatorio.";
$lang['ticket_validation_modal_fantasy_name_min_length'] = "El campo Nombre Fantasía debe tener al menos {param} carácter(es).";
$lang['ticket_validation_modal_fantasy_name_max_length'] = "El campo Nombre Fantasía excedió el límite de {param} carácter(es).";

// alerta validación modal tipo de ticket
$lang['ticket_validation_modal_ticket_type_name_required'] = "El campo Nombre es obligatorio.";
$lang['ticket_validation_modal_ticket_type_name_min_length'] = "El campo Nombre debe tener al menos {param} carácter(es).";
$lang['ticket_validation_modal_ticket_type_name_max_length'] = "El campo Nombre excedió el límite de {param} carácter(es).";
$lang['ticket_validation_modal_ticket_type_sla_required'] = "El campo SLA del ticket es obligatorio.";
$lang['ticket_validation_modal_ticket_type_group_required'] = "El campo Sector es obligatorio.";

// alerta validación modal estado del ticket
$lang['ticket_validation_modal_status_name_required'] = "El campo Nombre es obligatorio.";
$lang['ticket_validation_modal_status_name_min_length'] = "El campo Nombre debe tener al menos {param} carácter(es).";
$lang['ticket_validation_modal_status_name_max_length'] = "El campo Nombre excedió el límite de {param} carácter(es).";
