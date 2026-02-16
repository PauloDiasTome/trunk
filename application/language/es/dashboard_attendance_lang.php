<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Waba navegador
$lang['dashboard_attendance_waba_header'] = "Panel";
$lang["dashboard_attendance_title"] = "Panel de Atención";

$lang["dashboard_attendance_header_avg_wait_time"] = "Tiempo medio de espera";
$lang["dashboard_attendance_header_avg_response_time"] = "Tiempo medio de respuesta";
$lang["dashboard_attendance_header_avg_service_time"] = "Tiempo medio de atención";
$lang["dashboard_attendance_header_total_attendances"] = "Atenciones";

// tooltip
$lang["dashboard_attendance_header_tooltip_avg_wait_time"] = "Tiempo medio de espera hasta la primera atención";
$lang["dashboard_attendance_header_tooltip_avg_response_time"] = "Tiempo medio para responder al cliente en atenciones";
$lang["dashboard_attendance_header_tooltip_avg_service_time"] = "Tiempo medio desde la apertura hasta el cierre de las atenciones";
$lang["dashboard_attendance_header_tooltip_total_attendances"] = "Cantidad de atenciones iniciadas en el período seleccionado";

$lang["dashboard_ticket_month_short"] = "Ene_Feb_Mar_Abr_May_Jun_Jul_Ago_Sep_Oct_Nov_Dic";

// Filter
$lang['dashboard_attendance_filter_period'] = "Período";
$lang['dashboard_attendance_filter_select_today'] = "Hoy";
$lang['dashboard_attendance_filter_select_yesterday'] = "Ayer";
$lang['dashboard_attendance_filter_select_week'] = "7 Días";
$lang['dashboard_attendance_filter_select_fifteen_days'] = "15 Días";
$lang['dashboard_attendance_filter_select_this_month'] = "30 Días";
$lang['dashboard_attendance_filter_select_last_month'] = "60 Días";
$lang['dashboard_attendance_filter_select_two_months_ago'] = "90 Días";
$lang['dashboard_attendance_filter_select_total'] = "Total";
$lang['dashboard_attendance_filter_select'] = "Select";
$lang['dashboard_attendance_filter_search'] = "Buscar";
$lang['dashboard_attendance_filter_not_found'] = "No encontrado";

// Graphic peak service
$lang['dashboard_attendance_graph_peak_service_title'] = "Hora pico de inicio de atención";
$lang['dashboard_attendance_graph_peak_service_tooltip'] = "Promedio de atenciones iniciadas por hora en los últimos 7 días.";
$lang['dashboard_attendance_graph_peak_service_caption'] = "Número promedio de llamadas iniciadas";

// Graphic attendance open and closed
$lang['dashboard_attendance_graph_started_end_closed_title'] = "Atenciones iniciadas y cerradas";
$lang['dashboard_attendance_graph_started_end_closed_leng_star'] = "Iniciadas";
$lang['dashboard_attendance_graph_started_end_closed_leng_closed'] = "Cerradas";
$lang['dashboard_attendance_graph_started_end_closed_no_data'] = "No hay datos para el período seleccionado";
$lang['dashboard_attendance_graph_started_end_closed_summary'] = "Cantidad de atenciones iniciadas y cerradas en el período y canal seleccionados";

// Graphic category
$lang['dashboard_attendance_graph_category_title'] = "Distribución de categorías de servicios";
$lang['dashboard_attendance_graph_category_summary'] = "Muestra el número de asistencias por categoría en el período y canal seleccionados";
$lang['dashboard_attendance_graph_category_no_data'] = "No se encontraron datos";
$lang['dashboard_attendance_graph_category_uncategorized_services'] = "Servicios sin categorizar";
$lang['dashboard_attendance_graph_category_deleted'] = "Eliminado";

// Chatbot Quantitative Chart
$lang['dashboard_attendance_graph_chatbot_quantitative_title'] = "Número de visitas por opción del chatbot";
$lang['dashboard_attendance_graph_chatbot_quantitative_summary'] = "Muestra cuántas veces los contactos visitaron cada opción o paso del chatbot durante las interacciones";
$lang['dashboard_attendance_graph_chatbot_quantitative_main_menu'] = "Menú principal";

// Graphic origin attendance
$lang['dashboard_attendance_graph_chatbot_origin_no_title'] = "Origen de las llamadas iniciadas";
$lang['dashboard_attendance_graph_chatbot_origin_summary'] = "Indica cómo se iniciaron las llamadas: activas por un asistente o pasivamente por el contacto";
$lang['dashboard_attendance_graph_chatbot_origin_no_data'] = "Sin datos";
$lang['dashboard_attendance_graph_chatbot_origin_manual'] = "Activo";
$lang['dashboard_attendance_graph_chatbot_origin_organic'] = "Pasivo";

// Graphic Abandonment attendance
$lang['dashboard_attendance_graph_abandonment_title'] = "Tasa de adherencia al flujo del chatbot";
$lang['dashboard_attendance_graph_abandonment_summary'] = "Muestra el porcentaje de usuarios que siguieron todo el flujo del chatbot en comparación con los que abandonaron la interacción antes de completarla";
$lang['dashboard_attendance_graph_abandonment'] = "Abandono";
$lang['dashboard_attendance_graph_no_abandonment'] = "No abandonar";

// User table
$lang['dashboard_attendance_table_user_title'] = "Usuarios";
$lang['dashboard_attendance_table_placeholder_search'] = "Buscar por nombre...";
$lang['dashboard_attendance_table_user_name'] = "Nombre";
$lang['dashboard_attendance_table_user_start'] = "Iniciado";
$lang['dashboard_attendance_table_user_in_service'] = "En servicio";
$lang['dashboard_attendance_table_user_on_hold'] = "En espera";
$lang['dashboard_attendance_table_user_finished'] = "Finalizado";
$lang['dashboard_attendance_table_user_ast'] = "TMA";
$lang['dashboard_attendance_table_user_art'] = "TMR";
$lang['dashboard_attendance_table_user_rating_average'] = "Calificación promedio de IA";
$lang['dashboard_attendance_table_show_more'] = "▼ Mostrar más";
