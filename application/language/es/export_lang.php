<?php

defined('BASEPATH') or exit('No se permite el acceso directo al script');

//Exportar contacto
$lang['export_contact_spreadsheet_label_contact'] = 'Contacto';
$lang['export_contact_spreadsheet_label_name'] = 'Nombre';
$lang['export_contact_spreadsheet_label_channel'] = 'Canal';
$lang['export_contact_spreadsheet_label_tag'] = 'Etiqueta';
$lang['export_contact_spreadsheet_label_creation'] = 'Fecha de creación';
$lang['export_contact_spreadsheet_label_situation'] = 'Situación';

$lang['export_contact_spreadsheet_verify_not_verified'] = 'No verificado';
$lang['export_contact_spreadsheet_verify_verified_without_whatsapp'] = 'Verificado sin whatsapp';
$lang['export_contact_spreadsheet_verify_verified_with_whatsapp'] = 'Verificado con whatsapp';

//Exportar Etiqueta
$lang['export_label_spreadsheet_label_name'] = 'Nombre';
$lang['export_label_spreadsheet_label_color_code'] = 'Código de color';

//Exportar Lista Negra
$lang['export_blocklist_spreadsheet_label_talkall_id'] = "ID TalkAll";
$lang['export_blocklist_spreadsheet_label_name'] = "Nombre";
$lang['export_blocklist_spreadsheet_label_user'] = "Usuario";

//Exportar Usuario
$lang['export_user_spreadsheet_label_name'] = "Nombre";
$lang['export_user_spreadsheet_label_sector'] = "Sector";
$lang['export_user_spreadsheet_label_email'] = "Correo electrónico";
$lang['export_user_spreadsheet_label_situation'] = "Situación";

//Exportar Respuestas
$lang['export_replies_spreadsheet_label_title'] = "Título";
$lang['export_replies_spreadsheet_label_tag'] = "Etiqueta";

//Exportar Grupo de Usuario
$lang['export_usergroup_spreadsheet_label_name'] = "Nombre";

//Exportar Permiso
$lang['export_permissions_spreadsheet_label_title'] = "Nombre del Permiso";

//Exportar Llamada de Usuario
$lang['export_usercall_spreadsheet_label_name'] = "Nombre";
$lang['export_usercall_spreadsheet_label_service'] = "Límite de atención simultánea";

//Exportar Ticket
$lang['export_ticket_spreadsheet_label_creation'] = 'Creación';
$lang['export_ticket_spreadsheet_label_contact'] = 'Contacto';
$lang['export_ticket_spreadsheet_label_user'] = 'Usuario';
$lang['export_ticket_spreadsheet_label_ticket_type'] = 'Tipo de ticket';
$lang['export_ticket_spreadsheet_label_ticket_status'] = 'Estado del ticket';

//Exportar Tipo de Ticket
$lang['export_ticket_type_spreadsheet_label_creation'] = "Creación";
$lang['export_ticket_type_spreadsheet_label_contact'] = "Contacto";
$lang['export_ticket_type_spreadsheet_label_sla'] = "SLA";

//Exportar Estado de Ticket
$lang['export_ticket_status_spreadsheet_label_name'] = "Nombre";

//Exportar SLA de Ticket
$lang['export_ticket_status_spreadsheet_label_name'] = "Nombre";
$lang['export_ticket_status_spreadsheet_label_time'] = "Tiempo";

//Exportar Pedido
$lang['export_order_spreadsheet_label_creation'] = "Creación";
$lang['export_order_spreadsheet_label_id_request'] = "Identificador de solicitud";
$lang['export_order_spreadsheet_label_number'] = "Número";
$lang['export_order_spreadsheet_label_status'] = "Estado";

//Exportar Estado de Pedido
$lang['export_order_status_spreadsheet_label_name'] = "Nombre";
$lang['export_order_status_spreadsheet_label_color'] = "Color";

//Exportar Método de Pago de Pedido
$lang['export_order_payment_method_spreadsheet_label_method'] = "Método de pago";

//Exportar Mi Factura
$lang['export_myinvoice_spreadsheet_label_company'] = "Empresa";
$lang['export_myinvoice_spreadsheet_label_description'] = "Descripción";
$lang['export_myinvoice_spreadsheet_label_expire_date'] = "Fecha de vencimiento";
$lang['export_myinvoice_spreadsheet_label_valor'] = "Monto a pagar";
$lang['export_myinvoice_spreadsheet_label_status'] = "Situación";

//Exportar Transmisión de Whatsapp/estado
$lang['export_whatsapp_broadcast_label_archive'] = "Archivo";
$lang['export_whatsapp_broadcast_label_register'] = "Registro";
$lang['export_whatsapp_broadcast_label_scheduling'] = "Programación";
$lang['export_whatsapp_broadcast_label_validity'] = "Vigencia";
$lang['export_whatsapp_broadcast_label_channel'] = "Canal";
$lang['export_whatsapp_broadcast_label_title'] = "Título";
$lang['export_whatsapp_broadcast_label_status'] = "Estado";

//Exportar Informe de Llamadas
$lang['export_report_service_spreadsheet_label_creation'] = "Creación";
$lang['export_report_service_spreadsheet_label_protocol'] = "Protocolo";
$lang['export_report_service_spreadsheet_label_number'] = "Número";
$lang['export_report_service_spreadsheet_label_name'] = "Nombre";
$lang['export_report_service_spreadsheet_label_user'] = "Usuario";
$lang['export_report_service_spreadsheet_label_sector'] = "Sector";
$lang['export_report_service_spreadsheet_label_time'] = "Tiempo";
$lang['export_report_service_spreadsheet_label_situation'] = "Situación";

//Exportar Informe de Evaluación
$lang['export_report_evaluate_spreadsheet_label_user'] = "Usuario";
$lang['export_report_evaluate_spreadsheet_label_feedback'] = "Promedio de evaluaciones";
$lang['export_report_evaluate_spreadsheet_label_feedback_qtd'] = "Cantidad de evaluaciones";

//Exportar Informe de Chatbot
$lang['export_report_chatbot_spreadsheet_label_service_start'] = 'Inicio de la atención';
$lang['export_report_chatbot_spreadsheet_label_name'] = 'Nombre del contacto';
$lang['export_report_chatbot_spreadsheet_label_number'] = 'Número de contacto';
$lang['export_report_chatbot_spreadsheet_label_channel'] = 'Canal';
$lang['export_report_chatbot_spreadsheet_label_timestamp'] = 'Última interacción';

//Exportar Informe de Interacción Sintética
$lang['export_report_interaction_synthetic_label_chatbot_interactions_info'] = "Interacciones del Chatbot";
$lang['export_report_interaction_synthetic_label_chatbot_interactions_qtd'] = "Cantidad";
$lang['export_report_interaction_synthetic_label_waiting_service_info'] = "Esperando Atención";
$lang['export_report_interaction_synthetic_label_waiting_service_qtd'] = "Cantidad";
$lang['export_report_interaction_synthetic_label_service_info'] = "Atención";
$lang['export_report_interaction_synthetic_label_service_qtd'] = "Cantidad";

//Exportar Informe de espera de servicio
$lang['export_report_waiting_service_label_creation_date'] = "Fecha de Creación";
$lang['export_report_waiting_service_label_contact'] = "Contacto";
$lang['export_report_waiting_service_label_name'] = "Nombre";
$lang['export_report_waiting_service_label_user'] = "Usuario";
$lang['export_report_waiting_service_label_sector'] = "Sector";
$lang['export_report_waiting_service_label_time'] = "Tiempo";
$lang['export_report_waiting_service_label_situation'] = "Situación";

//Exportar Informe Copacol Analítico
$lang['export_report_copacol_analytical_label_type_bot'] = "Tipo - Bot/Ticket";
$lang['export_report_copacol_analytical_label_clerk'] = "Atendedor";
$lang['export_report_copacol_analytical_label_name'] = "Nombre";
$lang['export_report_copacol_analytical_label_cellphone'] = "Celular";
$lang['export_report_copacol_analytical_label_effective_date'] = "Fecha efectiva/Aberta";
$lang['export_report_copacol_analytical_label_denied_date'] = "Fecha denegada/cerrada";
$lang['export_report_copacol_analytical_label_stage_time'] = "Tiempo de etapa";
$lang['export_report_copacol_analytical_label_Situation'] = "Situación";

//Exportar Informe Copacol Sintético

//Ticket
$lang['export_report_copacol_synthetic_label_stage'] = "Etapa";
$lang['export_report_copacol_synthetic_label_qtd_finish'] = "QTD Finalizada";
$lang['export_report_copacol_synthetic_label_qtd_route_lgpd'] = "QTD Ruta LGPD";
$lang['export_report_copacol_synthetic_label_abandonment'] = "Abandono";
$lang['export_report_copacol_synthetic_label_effective_ratting'] = "Promedio Efectivo";
$lang['export_report_copacol_synthetic_label_qtd_total'] = "QTD Total";

//Bot
$lang['export_report_copacol_synthetic_label_type'] = "Tipo";
$lang['export_report_copacol_synthetic_label_qtd_open'] = "QTD Abierta";
$lang['export_report_copacol_synthetic_label_qtd_finish_bot'] = "QTD Finalizada";
$lang['export_report_copacol_synthetic_label_pending'] = "Pendiente";
$lang['export_report_copacol_synthetic_label_time_ratting'] = "Promedio de Tiempo";
$lang['export_report_copacol_synthetic_label_qtd_total_bot'] = "QTD Total";

//Exportar Informe Enviar
$lang['export_report_send_label_date'] = "Fecha de Programación";
$lang['export_report_send_label_channel'] = "Canal";
$lang['export_report_send_label_text'] = "Texto";
$lang['export_report_send_label_all_sent'] = "Total Enviado";
$lang['export_report_send_label_all_received'] = "Total Recibido";
$lang['export_report_send_label_all_reader'] = "Total Leído";
$lang['export_report_send_label_situaiton'] = "Situación";

// Exportar Informe Princess Field
$lang['export_report_princess_field_label_first_interation'] = "Primera Interacción";
$lang['export_report_princess_field_label_name'] = "Nombre";
$lang['export_report_princess_field_label_contract'] = "Contrato";
$lang['export_report_princess_field_label_last_interation'] = "Último Mensaje";

//Exportar Contacto
$lang['export_report_contact_label_channel'] = "Canal";
$lang['export_report_contact_label_all'] = "Total general";
$lang['export_report_contact_label_month_up'] = "Aumento Mensual";
$lang['export_report_contact_label_daily_up'] = "Aumento Diario";
$lang['export_report_contact_label_week_count'] = "Porcentaje semanal";
$lang['export_report_contact_label_daily_count'] = "Porcentaje Diario";

//Exportar Catálogo
$lang['export_setting_product_label_code'] = "Código";
$lang['export_setting_product_label_title'] = "Título";
$lang['export_setting_product_label_price'] = "Precio";
$lang['export_setting_product_label_situation'] = "Situación";

//Exportar Bot Entrenador
$lang['export_settings_bot_label_option'] = "Opciones";
$lang['export_settings_bot_label_desc'] = "Descripción";

//Exportar FAQ
$lang['export_setting_faq_label_title'] = "Título";
$lang['export_setting_faq_label_description'] = "Descripción";

//Exportar Tabla de horarios
$lang['export_setting_worktime_label_name'] = "Nombre";

//Exportar Enlace Corto
$lang['export_setting_shortlink_label_name'] = "Nombre";
$lang['export_setting_shortlink_label_link'] = "Enlace";

//Título de la lista
$lang['Export_list_name_contact'] = "Lista de Contactos";
$lang['Export_list_name_label'] = "Lista de Etiquetas";
$lang['Export_list_name_blocklist'] = "Lista de Contactos Bloqueados";
$lang['Export_list_name_user'] = "Lista de Usuarios";
$lang['Export_list_name_replies'] = "Lista de Respuestas";
$lang['Export_list_name_usergroup'] = "Lista de Grupos de Usuarios";
$lang['Export_list_name_permissions'] = "Lista de Permisos";
$lang['Export_list_name_usercall'] = "Lista de Llamadas de Usuarios";
$lang['Export_list_name_ticket'] = "Lista de Tickets";
$lang['Export_list_name_ticket_type'] = "Lista de Tipos de Tickets";
$lang['Export_list_name_ticket_status'] = "Lista de Estados de Tickets";
$lang['Export_list_name_ticket_sla'] = "Lista de SLA de Tickets";
$lang['Export_list_name_order'] = "Lista de Pedidos";
$lang['Export_list_name_order_status'] = "Lista de Estados de Pedidos";
$lang['Export_list_name_order_payment_method'] = "Lista de Métodos de Pago de Pedidos";
$lang['Export_list_name_invoice'] = "Mi Factura";
$lang['Export_list_name_report_service'] = "Informe de Llamadas de Servicios";
$lang['Export_list_name_report_waiting_service'] = "Informe de Espera de Servicio";
$lang['Export_list_name_report_interaction'] = "Informe de Interacción";
$lang['Export_list_name_report_copacol'] = "Informe de Copacol";
$lang['Export_list_name_report_send'] = "Informe de Envíos";
$lang['Export_list_name_report_princess_field'] = "Informe de Princess Field";
$lang['Export_list_name_report_contact'] = "Informe de Contactos";
$lang['Export_list_name_product_catalog'] = "Catálogo de Productos";
$lang['Export_list_name_bot'] = "Entrenador de Bot";
$lang['Export_list_name_faq'] = "FAQ";
$lang['Export_list_name_worktime'] = "Horario de Trabajo";
$lang['Export_list_name_shortlink'] = "Enlace Corto";

//Etiquetas del menú
$lang['menu_export'] = "Exportar";
$lang['menu_export_file'] = "Archivo de Exportación";
$lang['menu_export_list'] = "Listas de Exportación";
