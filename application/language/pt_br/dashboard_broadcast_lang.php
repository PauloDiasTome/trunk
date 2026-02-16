<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Waba navegador
$lang['dashboard_broadcast_waba_header'] = "Dashboard";
$lang["dashboard_broadcast_title"] = "Dashboard Broadcast";

$lang["dashboard_broadcast_header_base_active"] = "Crescimento de Base";
$lang["dashboard_broadcast_header_base_inactive"] = "Base de Inativos";
$lang["dashboard_broadcast_header_broadcast_send"] = "Campanhas Enviadas";
$lang["dashboard_broadcast_header_broadcast_received"] = "Campanhas Recebidas";
$lang["dashboard_ticket_month_short"] = "Jan_Feb_Mar_Apr_May_Jun_Jul_Aug_Sep_Oct_Nov_Dec";

// Filter
$lang['dashboard_broadcast_filter_period'] = "Período";
$lang['dashboard_broadcast_filter_select_today'] = "Hoje";
$lang['dashboard_broadcast_filter_select_yesterday'] = "Ontem";
$lang['dashboard_broadcast_filter_select_week'] = "7 Dias";
$lang['dashboard_broadcast_filter_select_fifteen_days'] = "15 Dias";
$lang['dashboard_broadcast_filter_select_this_month'] = "30 Dias";
$lang['dashboard_broadcast_filter_select_last_month'] = "60 Dias";
$lang['dashboard_broadcast_filter_select_two_months_ago'] = "90 Dias";
$lang['dashboard_broadcast_filter_select_total'] = "Total";

//Export
$lang['dashboard_broadcast_export_graphic'] = "Exportar";
$lang['dashboard_broadcast_export_modal_title'] = "Exportação de dados";
$lang['dashboard_broadcast_export_modal_subtitle'] = "Deseja exportar os dados?";
$lang['dashboard_broadcast_export_modal_confirm'] = "Confirmar";
$lang['dashboard_broadcast_export_modal_cancel'] = "Voltar";

$lang['dashboard_broadcast_export_rack_most_contact'] = "Base de canais - Maior";
$lang['dashboard_broadcast_export_rack_less_contact'] = "Base de canais - Menor";
$lang['dashboard_broadcast_export_rack_most_send'] = "Mais Engajamento";
$lang['dashboard_broadcast_export_rack_less_send'] = "Menos Engajamento";

// Graph Broadcast //
$lang['dashboard_broadcast_graph_title'] = "Campanhas Broadcast";
$lang['dashboard_broadcast_graph_send'] = "Campanhas enviadas";
$lang['dashboard_broadcast_graph_received'] = "Campanhas recebidas";
$lang['dashboard_broadcast_graph_read'] = "Lidas";
$lang['dashboard_broadcast_graph_weekday_monday'] = "Segunda";
$lang['dashboard_broadcast_graph_weekday_tuesday'] = "Terça";
$lang['dashboard_broadcast_graph_weekday_wednesday'] = "Quarta";
$lang['dashboard_broadcast_graph_weekday_thursday'] = "Quinta";
$lang['dashboard_broadcast_graph_weekday_friday'] = "Sexta";
$lang['dashboard_broadcast_graph_weekday_saturday'] = "Sábado";
$lang['dashboard_broadcast_graph_weekday_sunday'] = "Domingo";

// Graph Interaction
$lang['dashboard_broadcast_graph_interaction_title'] = "Audiência";
$lang['dashboard_broadcast_graph_interaction_contact'] = "Contato";
$lang['dashboard_broadcast_graph_interaction_active'] = "Ativo";
$lang['dashboard_broadcast_graph_interaction_inactive'] = "Inativo";
$lang['dashboard_broadcast_graph_interaction_weekday_monday'] = "Segunda";
$lang['dashboard_broadcast_graph_interaction_weekday_tuesday'] = "Terça";
$lang['dashboard_broadcast_graph_interaction_weekday_wednesday'] = "Quarta";
$lang['dashboard_broadcast_graph_interaction_weekday_thursday'] = "Quinta";
$lang['dashboard_broadcast_graph_interaction_weekday_friday'] = "Sexta";
$lang['dashboard_broadcast_graph_interaction_weekday_saturday'] = "Sábado";
$lang['dashboard_broadcast_graph_interaction_weekday_sunday'] = "Domingo";

// Graph Reaction
$lang['dashboard_broadcast_graph_reaction_title'] = "Reações na Publicação 😍";
$lang['dashboard_broadcast_graph_reaction_weekday_monday'] = "Segunda";
$lang['dashboard_broadcast_graph_reaction_weekday_tuesday'] = "Terça";
$lang['dashboard_broadcast_graph_reaction_weekday_wednesday'] = "Quarta";
$lang['dashboard_broadcast_graph_reaction_weekday_thursday'] = "Quinta";
$lang['dashboard_broadcast_graph_reaction_weekday_friday'] = "Sexta";
$lang['dashboard_broadcast_graph_reaction_weekday_saturday'] = "Sábado";
$lang['dashboard_broadcast_graph_reaction_weekday_sunday'] = "Domingo";

// Graph Active 
$lang['dashboard_broadcast_graph_active_title'] = "Interações";
$lang['dashboard_broadcast_graph_active_tooltip'] = "Novos contatos registrados na base";
$lang['dashboard_broadcast_graph_active_weekday_monday'] = "Segunda";
$lang['dashboard_broadcast_graph_active_weekday_tuesday'] = "Terça";
$lang['dashboard_broadcast_graph_active_weekday_wednesday'] = "Quarta";
$lang['dashboard_broadcast_graph_active_weekday_thursday'] = "Quinta";
$lang['dashboard_broadcast_graph_active_weekday_friday'] = "Sexta";
$lang['dashboard_broadcast_graph_active_weekday_saturday'] = "Sábado";
$lang['dashboard_broadcast_graph_active_weekday_sunday'] = "Domingo";

// Graph Inactive 
$lang['dashboard_broadcast_graph_inactive_title'] = "Base de Inativos";
$lang['dashboard_broadcast_graph_inactive_tooltip'] = "Contatos que não receberam ou deixaram de receber mensagens dentro do intervalo de 30 dias até a data";
$lang['dashboard_broadcast_graph_inactive_weekday_monday'] = "Segunda";
$lang['dashboard_broadcast_graph_inactive_weekday_tuesday'] = "Terça";
$lang['dashboard_broadcast_graph_inactive_weekday_wednesday'] = "Quarta";
$lang['dashboard_broadcast_graph_inactive_weekday_thursday'] = "Quinta";
$lang['dashboard_broadcast_graph_inactive_weekday_friday'] = "Sexta";
$lang['dashboard_broadcast_graph_inactive_weekday_saturday'] = "Sábado";
$lang['dashboard_broadcast_graph_inactive_weekday_sunday'] = "Domingo";

$lang['dashboard_broadcast_table_rank_channel'] = "Ranking de canais";
$lang['dashboard_broadcast_table_rank_period'] = "Filtrar por período";

// Ranking channels
$lang['dashboard_broadcast_table_rank_contacts'] = "Base de canais";
$lang['dashboard_broadcast_table_rank_position'] = "Posição";
$lang['dashboard_broadcast_table_rank_channel'] = "Canal";
$lang['dashboard_broadcast_table_rank_progress'] = "Crescimento";
$lang['dashboard_broadcast_table_rank_engagement'] = "Engajamento";
$lang['dashboard_broadcast_table_rank_contacts_most_tooltip'] = "Utiliza como critério os novos contatos registrados durante o período selecionado. Para visualizar o ranking dos canais com mais contatos, aplique o filtro 'TOTAL'";
$lang['dashboard_broadcast_table_rank_contacts_less_tooltip'] = "Utiliza como critério os novos contatos registrados durante o período selecionado. Para visualizar o ranking dos canais com menos contatos, aplique o filtro 'TOTAL'";
$lang['dashboard_broadcast_graph_campaign_tooltip'] = "O gráfico apresenta a quantidade de mensagens enviadas pelo sistema para a Meta distribuir aos contatos, destacando o total de mensagens que chegaram aos dispositivos e a quantidade de clientes com confirmação de leitura ativada. São consideradas apenas as campanhas finalizadas.";
$lang['dashboard_broadcast_graph_interaction_tooltip'] = "Soma total de contatos até o período selecionado e categorizados como ativos e inativos de acordo com a data em que receberam a última campanha.";
$lang['dashboard_broadcast_base_active_tooltip'] = "Percentual de crescimento da base de contatos no período selecionado.";
$lang['dashboard_broadcast_table_rank_engagement_tooltip'] = "Utiliza como critério os novos contatos registrados durante o período selecionado. Para visualizar o ranking dos canais com mais contatos, aplique o filtro TOTAL";

$lang['dashboard_broadcast_table_no_for_the_period'] = "Sem dados para o período selecionado.";