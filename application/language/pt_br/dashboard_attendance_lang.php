<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Waba navegador
$lang['dashboard_attendance_waba_header'] = "Dashboard";
$lang["dashboard_attendance_title"] = "Dashboard Atendimento";

$lang["dashboard_attendance_header_avg_wait_time"] = "Média de espera";
$lang["dashboard_attendance_header_avg_response_time"] = "Média de resposta";
$lang["dashboard_attendance_header_avg_service_time"] = "Média de atendimento";
$lang["dashboard_attendance_header_total_attendances"] = "Atendimentos";

// tooltip
$lang["dashboard_attendance_header_tooltip_avg_wait_time"] = "Tempo médio de espera até o primeiro atendimento";
$lang["dashboard_attendance_header_tooltip_avg_response_time"] = "Tempo medio para responder o cliente em atendimentos";
$lang["dashboard_attendance_header_tooltip_avg_service_time"] = "Tempo médio da abertura até o encerramento em atendimentos";
$lang["dashboard_attendance_header_tooltip_total_attendances"] = "Quantidade de atendimentos iniciados no periodo selecionado";

$lang["dashboard_ticket_month_short"] = "Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec";

// Filter
$lang['dashboard_attendance_filter_period'] = "Período";
$lang['dashboard_attendance_filter_select_today'] = "Hoje";
$lang['dashboard_attendance_filter_select_yesterday'] = "Ontem";
$lang['dashboard_attendance_filter_select_week'] = "7 Dias";
$lang['dashboard_attendance_filter_select_fifteen_days'] = "15 Dias";
$lang['dashboard_attendance_filter_select_this_month'] = "30 Dias";
$lang['dashboard_attendance_filter_select_last_month'] = "60 Dias";
$lang['dashboard_attendance_filter_select_two_months_ago'] = "90 Dias";
$lang['dashboard_attendance_filter_select_total'] = "Total";
$lang['dashboard_attendance_filter_select'] = "Selecionar";
$lang['dashboard_attendance_filter_search'] = "Pesquisar...";
$lang['dashboard_attendance_filter_not_found'] = "Não encontrado";

// Graphic peak service
$lang['dashboard_attendance_graph_peak_service_title'] = "Horário de pico de inicialização de atendimento";
$lang['dashboard_attendance_graph_peak_service_tooltip'] = "Média de atendimento iniciados por hora nos últimos 7 dias.";
$lang['dashboard_attendance_graph_peak_service_caption'] = "Média de atendimentos iniciados";

// Graphic attendance open and closed
$lang['dashboard_attendance_graph_started_end_closed_title'] = "Atendimentos iniciados/encerrados";
$lang['dashboard_attendance_graph_started_end_closed_leng_star'] = "Iniciados";
$lang['dashboard_attendance_graph_started_end_closed_leng_closed'] = "Encerrados";
$lang['dashboard_attendance_graph_started_end_closed_no_data'] = "Não há dados para o período selecionado";
$lang['dashboard_attendance_graph_started_end_closed_summary'] = "Quantidade de atendimentos iniciados e encerrados no período e canal selecionados";

// Graphic category
$lang['dashboard_attendance_graph_category_title'] = "Atendimentos por categoria";
$lang['dashboard_attendance_graph_category_summary'] = "Mostra a quantidade de atendimentos por categoria no período e canal selecionados";
$lang['dashboard_attendance_graph_category_no_data'] = "Nenhum dado encontrado";
$lang['dashboard_attendance_graph_category_uncategorized_services'] = "Atendimentos sem categoria";
$lang['dashboard_attendance_graph_category_deleted'] = "Excluída";

// Chatbot Quantitative Chart
$lang['dashboard_attendance_graph_chatbot_quantitative_title'] = "Quantidade de acessos por opção do chatbot";
$lang['dashboard_attendance_graph_chatbot_quantitative_summary'] = "Exibe quantas vezes cada opção ou etapa do chatbot foi procurada pelos contatos durante as interações";
$lang['dashboard_attendance_graph_chatbot_quantitative_main_menu'] = "Menu principal";

// Graphic Origin attendance
$lang['dashboard_attendance_graph_chatbot_origin_no_title'] = "Origem dos atendimentos iniciados";
$lang['dashboard_attendance_graph_chatbot_origin_summary'] = "Indica como os atendimentos foram iniciados: ativamente por um atendente ou de forma passiva pelo contato";
$lang['dashboard_attendance_graph_chatbot_origin_no_data'] = "Sem dados";
$lang['dashboard_attendance_graph_chatbot_origin_manual'] = "Ativo";
$lang['dashboard_attendance_graph_chatbot_origin_organic'] = "Passivo";

// Graphic Abandonment attendance
$lang['dashboard_attendance_graph_abandonment_title'] = "Taxa de aderência ao fluxo do chatbot";
$lang['dashboard_attendance_graph_abandonment_summary'] = "Mostra a porcentagem de usuários que seguiram o fluxo completo do chatbot em comparação aos que abandonaram a interação antes de concluir";
$lang['dashboard_attendance_graph_abandonment'] = "Abandonaram";
$lang['dashboard_attendance_graph_no_abandonment'] = "Não abandono";

// User table
$lang['dashboard_attendance_table_user_title'] = "Usuários";
$lang['dashboard_attendance_table_placeholder_search'] = "Buscar por nome...";
$lang['dashboard_attendance_table_user_name'] = "Nome";
$lang['dashboard_attendance_table_user_start'] = "Iniciados";
$lang['dashboard_attendance_table_user_in_service'] = "Em atendimento";
$lang['dashboard_attendance_table_user_on_hold'] = "Em espera";
$lang['dashboard_attendance_table_user_finished'] = "Finalizados";
$lang['dashboard_attendance_table_user_ast'] = "TMA";
$lang['dashboard_attendance_table_user_art'] = "TMR";
$lang['dashboard_attendance_table_user_rating_average'] = "Média de avaliação IA";
$lang['dashboard_attendance_table_show_more'] = "▼ Mostrar mais";
