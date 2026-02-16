<?php

defined('BASEPATH') or exit('No direct script access allowed');

// View Waba
$lang['sms_broadcast_waba_header'] = "Publicação SMS";
$lang['sms_broadcast_waba_add'] = "Nova publicação SMS";
$lang['sms_broadcast_waba_edit'] = "Editar publicação SMS";

//View 
$lang['sms_broadcast_view'] = "Visualizar campanha";
$lang['sms_broadcast_timeline_view'] = "Linha do tempo";
$lang['sms_broadcast_edit_information'] = "Informações da campanha";

$lang['sms_broadcast_segmented_view'] = "Campanha segmentada";
$lang['sms_broadcast_segmented_view_yes'] = "Sim";
$lang['sms_broadcast_segmented_view_no'] = "Não";
$lang['sms_broadcast_selected_group_view'] = "Grupo selecionado";
$lang['sms_broadcast_select_channel_view'] = "Canal selecionado";
$lang['sms_broadcast_schedule_hour_view'] = "Hora de agendamento";
$lang['sms_broadcast_description_sent_view'] = "Visualização do SMS";

$lang['sms_broadcast_timeline_creation'] = "criou a campanha";
$lang['sms_broadcast_timeline_resend'] = "reenviou a campanha";
$lang['sms_broadcast_timeline_edited'] = "editou a campanha";
$lang['sms_broadcast_timeline_canceled'] = "cancelou a campanha";
$lang['sms_broadcast_timeline_send_now'] = "alterou para enviar agora";
$lang['sms_broadcast_timeline_creation_api'] = "Campanha criada via integração de sistema externo";

// View find 
$lang['sms_broadcast_topnav'] = "Publicações SMS";
$lang['sms_broadcast_btn_new'] = "Novo";
$lang['sms_broadcast_btn_filter'] = "Filtrar";
$lang['sms_broadcast_btn_export'] = "Exportar";

$lang['sms_broadcast_column_btn_pause'] = "Pausar";
$lang['sms_broadcast_column_btn_resume'] = "Retomar";
$lang['sms_broadcast_column_btn_cancel'] = "Cancelar";
$lang['sms_broadcast_column_scheduling'] = "Data Agendada";
$lang['sms_broadcast_column_title'] = "Título";
$lang['sms_broadcast_column_status'] = "Status";

$lang['sms_broadcast_filter_title'] = "Filtrar campanha";
$lang['sms_broadcast_filter_search'] = "Título";
$lang['sms_broadcast_filter_search_placeholder'] = "Pesquisar...";
$lang['sms_broadcast_filter_status'] = "Status";
$lang['sms_broadcast_filter_status_select'] = "Selecionar...";
$lang['sms_broadcast_filter_status_processing'] = "Processando";
$lang['sms_broadcast_filter_status_processed'] = "Processado";
$lang['sms_broadcast_filter_status_send'] = "Enviado";
$lang['sms_broadcast_filter_status_canceling'] = "Cancelando";
$lang['sms_broadcast_filter_status_cancel'] = "Cancelado";
$lang['sms_broadcast_filter_status_sent'] = "Enviado";
$lang['sms_broadcast_filter_status_waiting'] = "Aguardando";
$lang['sms_broadcast_filter_period'] = "Período";
$lang['sms_broadcast_filter_period_placeholder_date_start'] = "Data inicial";
$lang['sms_broadcast_filter_period_placeholder_date_end'] = "Data final";
$lang['sms_broadcast_filter_btn_return'] = "Voltar";
$lang['sms_broadcast_filter_btn_search'] = "Buscar";

$lang['sms_broadcast_export_title'] = "Exportação de dados";
$lang['sms_broadcast_export_email'] = "Deseja enviar os dados para o email:";
$lang['sms_broadcast_export_email_placeholder'] = "Informe o e-mail para exportação";
$lang['sms_broadcast_export_btn_confirm'] = "Confirmar";
$lang['sms_broadcast_export_btn_return'] = "Voltar";

// Generic add,  edit // 
$lang['sms_broadcast_title'] = "Título";
$lang['sms_broadcast_date_scheduling'] = "Data de agendamento";
$lang['sms_broadcast_date_scheduling_placeholder'] = "Selecionar data";
$lang['sms_broadcast_hour_scheduling'] = "Hora de agendamento";
$lang['sms_broadcast_segments_select_placeholder'] = "Selecionar";
$lang['sms_broadcast_segments_select_group'] = "Selecionar o grupo";
$lang['sms_broadcast_segments_select_group_placeholder'] = "Selecionar";
$lang['sms_broadcast_message'] = "Mensagem: (Até 140 caracteres ou 70 com caracteres especiais) | Caracteres Restantes:";
$lang['sms_broadcast_status_message_placeholder'] = "Digite aqui...";
$lang['sms_broadcast_btn_cancel'] = "Cancelar";
$lang['sms_broadcast_btn_return'] = "Voltar";
$lang['sms_broadcast_btn_save'] = "Salvar";
$lang['sms_broadcast_choose_type'] = "Selecione o tipo de campanha";

// View add // 
$lang['sms_broadcast_add_title'] = "Novo";
$lang['sms_broadcast_add_information'] = "Adicionar nova campanha";

// Modal campaing estimate
$lang['campaign_estimate_title'] = "Atenção ao tempo de envio da campanha programada";
$lang['campaign_estimate_notify_on_modal'] = "A previsão de término dessa campanha ultrapassa os horários estabelecidos para o canal. O que você deseja fazer?";
$lang['campaign_estimate_suspend_campaign'] = "Suspender Campanha ";
$lang['campaign_estimate_suspend_campaign_extension'] = "Cancelar imediatamente o agendamento da campanha.";
$lang['campaign_estimate_review_queue'] = "Revisar fila de envio";
$lang['campaign_estimate_review_queue_extension'] = "Gerencie as campanhas para liberar espaço na fila";
$lang['campaign_estimate_change_date'] = "Alterar data de envio";
$lang['campaign_estimate_change_date_extension'] = "Escolha outra data e horário para realizar o disparo da campanha.";
$lang['campaign_estimate_send_partially'] = "Enviar parcialmente";
$lang['campaign_estimate_send_partially_extension'] = "Esteja ciente que apenas parte da sua base receberá a campanha.";
$lang['campaign_estimate_send_after_hours'] = "Enviar após horário";
$lang['campaign_estimate_send_after_hours_extension'] = "Utilize o tempo necessário para enviar a campanha para toda base.";
$lang['campaign_estimate_suspend_notify_title'] = "Tem certeza?";
$lang['campaign_estimate_suspend_notify_text'] = "Quer cancelar o agendamento desta campanha?";
$lang['campaign_estimate_suspend_notify_ok'] = "Sim";
$lang['campaign_estimate_suspend_notify_cancel'] = "Não";
$lang['campaign_estimate_suspend_notify_deleted_title'] = "Deletado!";
$lang['campaign_estimate_suspend_notify_notify_deleted_text'] = "Campanha removida com sucesso!";
$lang['campaign_estimate_partial_notify_title'] = "Tem certeza?";
$lang['campaign_estimate_partial_notify_text'] = "Esta campanha será enviada respeitando o horário limite previamente configurado para os canais selecionados.";
$lang['campaign_estimate_partial_notify_ok'] = "Sim";
$lang['campaign_estimate_partial_notify_cancel'] = "Não";
$lang['campaign_estimate_partial_notify_add_title'] = "Publicação cadastrada!";
$lang['campaign_estimate_partial_notify_sucess'] = "Campanha agendada com sucesso!";
$lang['campaign_estimate_partial_notify_notify_deleted_text'] = "Campanha removida com sucesso!";
$lang['campaign_estimate_after_notify_text'] = "Esta campanha será enviada para toda base de contato sem limite de tempo.";

// column
$lang['sms_broadcast_datatable_column_status_processing'] = "Aguardando envio";
$lang['sms_broadcast_datatable_column_status_send'] = "Enviado";
$lang['sms_broadcast_datatable_column_status_called_off'] = "Cancelado";
$lang['sms_broadcast_datatable_column_status_canceling'] = "Cancelando";
$lang['sms_broadcast_datatable_column_status_paused'] = "Pausado";
$lang['sms_broadcast_datatable_column_status_sending'] = "Em andamento";

// dropdown
$lang['sms_broadcast_datatable_column_action_view'] = "Visualizar";
$lang['sms_broadcast_datatable_column_action_cancel'] = "Cancelar";
$lang['sms_broadcast_datatable_column_action_pause'] = "Pausar";
$lang['sms_broadcast_datatable_column_action_pause_distribution'] = "Pausar distribuição";
$lang['sms_broadcast_datatable_column_action_resume'] = "Retomar";
$lang['sms_broadcast_datatable_column_action_resume_distribution'] = "Retomar distribuição";

// alert delete
$lang['sms_broadcast_alert_delete_title'] = "Você tem certeza?";
$lang['sms_broadcast_alert_delete_text'] = "Quer remover esta campanha?";
$lang['sms_broadcast_alert_delete_confirmButtonText'] = "Sim";
$lang['sms_broadcast_alert_delete_cancelButtonText'] = "Não";

$lang['sms_broadcast_alert_delete_two_title'] = "Deletado!";
$lang['sms_broadcast_alert_delete_two_text'] = "Campanha removida com sucesso!";

// alert generic
$lang['sms_broadcast_alert_broadcast_title'] = "Você tem certeza?";
$lang['sms_broadcast_alert_broadcast_confirmButtonText'] = "Sim";
$lang['sms_broadcast_alert_broadcast_cancelButtonText'] = "Não";
$lang['sms_broadcast_alert_broadcast_two_title'] = "Concluído!";

// alert pause
$lang['sms_broadcast_alert_broadcast_pause_text'] = "Você deseja pausar a campanha selecionada?";
$lang['sms_broadcast_alert_broadcast_pause_all_text'] = "Você deseja pausar todas as campanhas selecionadas?";
$lang['sms_broadcast_alert_broadcast_pause_two_text'] = "Campanha pausada com sucesso!";
$lang['sms_broadcast_alert_broadcast_pause_two_all_text'] = "Campanhas pausadas com sucesso!";

// alert resume
$lang['sms_broadcast_alert_broadcast_resume_text'] = "Você deseja retomar a campanha selecionada?";
$lang['sms_broadcast_alert_broadcast_resume_all_text'] = "Você deseja retomar todas as campanhas selecionadas?";
$lang['sms_broadcast_alert_broadcast_resume_two_text'] = "Campanha retomada com sucesso!";
$lang['sms_broadcast_alert_broadcast_resume_two_all_text'] = "Campanhas retomadas com sucesso!";

// alert error
$lang['sms_broadcast_error_broadcast_title'] = "Erro!";
$lang['sms_broadcast_error_broadcast_message'] = "Ocorreu um erro ao executar a ação";

// alert export
$lang['sms_broadcast_alert_export_title'] = "Lista enviada!";
$lang['sms_broadcast_alert_export_text'] = "O e-mail pode demorar até uma hora para chegar.";
$lang['sms_broadcast_alert_export_confirmButtonText'] = "Ok!";

// select canais
$lang['sms_broadcast_select_channel'] = "Selecionar o canal";

// View form validation
$lang['sms_broadcast_check_date'] = "A data de agendamento é menor que a data atual";
$lang['sms_broadcast_check_hour'] = "A hora de agendamento deve ser no mínimo 30 minutos maior que a hora atual!";
$lang['sms_broadcast_check_date_validity'] = "A data de vigência é menor que a data de agendamento";
// $lang['sms_broadcast_check_hour_validity'] = "A hora de vigência deve ser no mínimo uma hora maior que a hora de agendamento";
$lang['sms_broadcast_check_hour_validity'] = "A hora de vigência é menor ou igual a hora de agendamento";
$lang['sms_broadcast_alert_field_validation'] = "Este campo é obrigatório.";

$lang['sms_broadcast_validation_message'] = "Mensagem";
$lang['sms_broadcast_validation_img'] = "Imagem";
$lang['sms_broadcast_validation_cancel_title'] = "Erro";
$lang['sms_broadcast_validation_cancel_and_send'] = "Campanha já cancelada ou enviada!";
