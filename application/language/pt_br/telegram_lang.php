<?php

defined('BASEPATH') or exit('No direct script access allowed');

// View Telegram
$lang['telegram_channel_header'] = "Publicações Telegram Canais";
$lang['telegram_channel_subheader'] = "Publicações Telegram Canais";
$lang['telegram_channel_btn_new'] = "Novo";
$lang['telegram_channel_btn_filter'] = "Filtrar";
$lang['telegram_channel_btn_export'] = "Exportar";

// View 
$lang['telegram_channel_view'] = "Visualizar campanha";
$lang['telegram_channel_view_information'] = "Informações da campanha";
$lang['telegram_channel_view_timeline'] = "Linha do tempo";
$lang['telegram_channel_canceled_broadcast_timeline_view'] = "Campanha cancelada";

$lang['telegram_channel_timeline_creation'] = "criou a campanha";
$lang['telegram_channel_timeline_paused'] = "pausou a campanha";
$lang['telegram_channel_timeline_resume'] = "retomou a campanha";
$lang['telegram_channel_timeline_resend'] = "reenviou a campanha";
$lang['telegram_channel_timeline_edited'] = "editou a campanha";
$lang['telegram_channel_timeline_canceled'] = "cancelou a campanha";
$lang['telegram_channel_timeline_send_now'] = "alterou para enviar agora";
$lang['telegram_channel_timeline_partial'] = "configurou a campanha para enviar parcialmente";
$lang['telegram_channel_timeline_exceed'] = "configurou a campanha para ignorar o período de envio";
$lang['telegram_channel_timeline_creation_api'] = "Campanha criada via integração de sistema externo";
$lang['telegram_channel_timeline_add_expire'] = "adicionou uma vigência para a campanha até %s";
$lang['telegram_channel_timeline_remove_expire'] = "removeu a vigência da campanha";
$lang['telegram_channel_timeline_edited_expire'] = "alterou a vigência da campanha de %s para %s";

$lang['telegram_channel_view_segmented'] = "Campanha segmentada";
$lang['telegram_channel_view_segmented_yes'] = "Sim";
$lang['telegram_channel_view_segmented_no'] = "Não";
$lang['telegram_channel_view_selected_group'] = "Grupo selecionado";

// View find 
$lang['telegram_channel_topnav'] = "Publicações Telegram Canais";
$lang['telegram_channel_column_btn_pause'] = "Pausar";
$lang['telegram_channel_column_btn_resume'] = "Retomar";
$lang['telegram_channel_column_btn_cancel'] = "Cancelar";
$lang['telegram_channel_column_arquive'] = "Arquivo";
$lang['telegram_channel_column_scheduling'] = "Data Agendada";
$lang['telegram_channel_column_info'] = "Campanha / Data";
$lang['telegram_channel_column_channel'] = "Canal";
$lang['telegram_channel_column_title'] = "Título";
$lang['telegram_channel_column_status'] = "Status";

// View filter
$lang['telegram_channel_filter_title'] = "Filtrar campanha";
$lang['telegram_channel_filter_search'] = "Título";
$lang['telegram_channel_filter_search_placeholder'] = "Pesquisar...";
$lang['telegram_channel_filter_channel'] = "Canal";
$lang['telegram_channel_filter_status'] = "Status";
$lang['telegram_channel_filter_status_select'] = "Selecionar...";
$lang['telegram_channel_filter_status_processing'] = "Aguardando envio";
$lang['telegram_channel_filter_status_sending'] = "Em andamento";
$lang['telegram_channel_filter_status_send'] = "Enviado";
$lang['telegram_channel_filter_status_called_off'] = "Cancelado";
$lang['telegram_channel_filter_period'] = "Período";
$lang['telegram_channel_filter_period_placeholder_date_start'] = "Data inicial";
$lang['telegram_channel_filter_period_placeholder_date_end'] = "Data final";
$lang['telegram_channel_filter_btn_return'] = "Voltar";
$lang['telegram_channel_filter_btn_search'] = "Buscar";

// View export
$lang['telegram_channel_export_title'] = "Exportação de dados";
$lang['telegram_channel_export_email'] = "Deseja enviar os dados para o email:";
$lang['telegram_channel_export_email_placeholder'] = "Informe o e-mail para exportação";
$lang['telegram_channel_export_btn_confirm'] = "Confirmar";
$lang['telegram_channel_export_btn_return'] = "Voltar";

// View add // 
$lang['telegram_channel_add_title'] = "Novo";
$lang['telegram_channel_add_information'] = "Adicionar nova campanha";

// View edit //
$lang['telegram_channel_edit_title'] = "Editar";
$lang['telegram_channel_edit_broadcast_audio'] = "Campanha em áudio";
$lang['telegram_channel_edit_broadcast_placeholder'] = "Descrição (opcional)";
$lang['telegram_channel_edit_selected_channel'] = "Canal selecionado";
$lang['telegram_channel_edit_selected_group'] = "Grupo selecionado";

// Generic
$lang['telegram_channel_title'] = "Título";
$lang['telegram_channel_date_scheduling'] = "Data de agendamento";
$lang['telegram_channel_date_scheduling_placeholder'] = "Selecionar data";
$lang['telegram_channel_hour_scheduling'] = "Hora de agendamento";
$lang['telegram_channel_hour_scheduling_placeholder'] = "Selecionar hora";
$lang['telegram_channel_automatic_response'] = "Adicionar essa campanha como resposta automática?";
$lang['telegram_channel_automatic_response_view'] = "Campanha adicionada como resposta automática";
$lang['telegram_channel_automatic_response_yes'] = "Sim";
$lang['telegram_channel_automatic_response_no'] = "Não";
$lang['telegram_channel_segments_yes'] = "Sim";
$lang['telegram_channel_segments_no'] = "Não";
$lang['telegram_channel_segments_campaign'] = "Adicionar campanha segmentada?";
$lang['telegram_channel_segments_select'] = "Selecione o seguimento";
$lang['telegram_channel_segments_select_placeholder'] = "Selecionar";
$lang['telegram_channel_segments_select_group'] = "Selecionar grupo";
$lang['telegram_channel_segments_select_group_placeholder'] = "Selecionar...";
$lang['telegram_channel_time_start_validity'] = "Até o dia";
$lang['telegram_channel_hour_start_validity'] = "Até a hora";
$lang['telegram_channel_select'] = "Selecionar...";
$lang['telegram_channel_select_channel'] = "Selecionar canal";
$lang['telegram_channel_select_channel_view'] = "Canal selecionado";
$lang['telegram_channel_type_photo'] = "Criar campanha de mídia";
$lang['telegram_channel_type_text'] = "Criar campanha de texto";
$lang['telegram_channel_message'] = "Mensagem: (Até 1024 caracteres) | Caracteres restantes:";
$lang['telegram_channel_status_message_placeholder'] = "Digite aqui...";
$lang['telegram_channel_loading_arquive'] = "Carregar fotos ou vídeos (<b>jpeg</b>, <b>jpg</b>, <b>pdf</b> ou <b>mp4</b> de até <b>10 MB</b>)";
$lang['telegram_channel_title_drop'] = "Pronto para adicionar algo?";
$lang['telegram_channel_subtitle_drop'] = "Arraste fotos e vídeos aqui para começar.";
$lang['telegram_channel_btn_cancel'] = "Cancelar";
$lang['telegram_channel_btn_return'] = "Voltar";
$lang['telegram_channel_btn_campaign_preview'] = "Prévia da campanha";
$lang['telegram_channel_btn_save'] = "Salvar";
$lang['telegram_channel_choose_type'] = "Selecione o tipo de campanha";
$lang['telegram_channel_alert_input_required'] = "Este campo é obrigatório.";
$lang['telegram_channel_unsegmented_campaign'] = "Campanha não segmentada (não é possivel alterar)";
$lang['telegram_channel_text_caracter'] = "Caracteres restantes:";

// Modal Preview Campaign //
$lang['telegram_channel_campaign_preview'] = "Prévia da campanha";
$lang['telegram_channel_campaign_preview_btn_close'] = "Fechar";
$lang['telegram_channel_campaign_preview_btn_send_preview'] = "Enviar prévia";
$lang['telegram_channel_campaign_preview_text_movel'] = "Insira o número do seu celular e vamos enviar uma prévia da campanha em seu WhatsApp, assim, você visualiza a campanha antes de dispará-la para a base.";
$lang['telegram_channel_campaign_preview_text_optin'] = "Lembrando que o número deve ter realizado o opt-in completo no canal selecionado.";
$lang['telegram_channel_campaign_preview_text_number_fone'] = "Este campo é obrigatório.";
$lang['telegram_channel_campaign_preview_text_preview_info_success'] = "Prévia da campanha enviada com sucesso!";
$lang['telegram_channel_campaign_preview_text_preview_info_message'] = "Você receberá a campanha em até 30 minutos. Caso demore mais que isso, entre em contato com o customer success.";
$lang['telegram_channel_campaign_preview_channel_send'] = "Selecione um canal para envio do preview";
$lang['telegram_channel_campaign_preview_channel_receive'] = "Digite um número de telefone para recebimento do preview";

$lang['telegram_channel_campaign_preview_atention'] = "Atenção";
$lang['telegram_channel_campaign_preview_the_number'] = "O número ";
$lang['telegram_channel_campaign_preview_no_optin'] = " não tem opt-in com o canal ";
$lang['telegram_channel_campaign_preview_ok'] = "Ok!";
$lang['telegram_channel_campaign_preview_select'] = "Selecionar";
$lang['alert-field-validation_image'] = "Este campo é obrigatório.";

// Modal campaign estimate
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
$lang['campaign_estimate_channel_info'] = "Estes são os números que estão em conflito: ";
$lang['campaign_estimate_channel_info1'] = " e outros.";
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

// Modal campaign overlap
$lang['campaign_overlap_title'] = "Aviso de impacto da campanha";
$lang['campaign_overlap_notify_on_modal'] = "A nova campanha irá impactar nos seus disparos de hoje. O que você deseja fazer?";
$lang['campaign_overlap_channel_info'] = "Este é o número que está em conflito: ";
$lang['campaign_overlap_channels_info'] = "Estes são os números que estão em conflito: ";
$lang['campaign_overlap_channel_info_others'] = " e outros.";
$lang['campaign_overlap_suspend_campaign'] = "Suspender Campanha ";
$lang['campaign_overlap_suspend_campaign_extension'] = "Cancelar imediatamente o agendamento da campanha.";
$lang['campaign_overlap_change_date'] = "Alterar data de envio";
$lang['campaign_overlap_change_date_extension'] = "Escolha outra data e horário para realizar o disparo da campanha.";
$lang['campaign_overlap_send_after_hours'] = "Enviar mesmo assim";
$lang['campaign_overlap_send_after_hours_extension'] = "Utilize o tempo necessário para enviar as campanhas para toda base.";

// column
$lang['telegram_channel_datatable_column_status_processing'] = "Aguardando envio";
$lang['telegram_channel_datatable_column_status_send'] = "Enviado";
$lang['telegram_channel_datatable_column_status_called_off'] = "Cancelado";
$lang['telegram_channel_datatable_column_status_paused'] = "Pausado";
$lang['telegram_channel_datatable_column_status_sending'] = "Em andamento";

// dropdown
$lang['telegram_channel_datatable_column_action_view'] = "Visualizar";
$lang['telegram_channel_datatable_column_action_cancel'] = "Cancelar";
$lang['telegram_channel_datatable_column_action_pause'] = "Pausar";
$lang['telegram_channel_datatable_column_action_pause_distribution'] = "Pausar distribuição";
$lang['telegram_channel_datatable_column_action_resume'] = "Retomar";
$lang['telegram_channel_datatable_column_action_resume_distribution'] = "Retomar distribuição";

// alert change type
$lang['telegram_channel_alert_change_type_title'] = "Você tem certeza?";
$lang['telegram_channel_alert_change_type_text'] = "Se você alterar o tipo de campanha, perderá a edição atual.";
$lang['telegram_channel_alert_change_type_yes'] = "Sim";
$lang['telegram_channel_alert_change_type_no'] = "Não";

// alert delete group
$lang['telegram_channel_alert_group_delete_title'] = "Você tem certeza?";
$lang['telegram_channel_alert_group_delete_text'] = "Quer remover a(s) campanha(s) selecionada(s)?";
$lang['telegram_channel_alert_group_delete_confirmButtonText'] = "Sim";
$lang['telegram_channel_alert_group_delete_cancelButtonText'] = "Não";

$lang['telegram_channel_alert_group_delete_two_title'] = "Deletado!";
$lang['telegram_channel_alert_group_delete_two_text'] = "Campanha(s) removida(s) com sucesso!";

// alert delete
$lang['telegram_channel_alert_delete_title'] = "Você tem certeza?";
$lang['telegram_channel_alert_delete_text'] = "Quer remover esta campanha?";
$lang['telegram_channel_alert_delete_confirmButtonText'] = "Sim";
$lang['telegram_channel_alert_delete_cancelButtonText'] = "Não";

$lang['telegram_channel_alert_delete_two_title'] = "Deletado!";
$lang['telegram_channel_alert_delete_two_text'] = "Campanha removida com sucesso!";

// alert resend
$lang['telegram_channel_alert_resend'] = "Reenviar";
$lang['telegram_channel_alert_resend_title'] = "Você tem certeza?";
$lang['telegram_channel_alert_resend_text'] = "Quer reenviar todas as filas desta campanha?";
$lang['telegram_channel_alert_resend_confirmButtonText'] = "Sim";
$lang['telegram_channel_alert_resend_cancelButtonText'] = "Não";
$lang['telegram_channel_alert_resend_two_title'] = "Reenviado!";
$lang['telegram_channel_alert_resend_two_text'] = "Campanha reenviada com sucesso!";

// alert generic
$lang['telegram_channel_alert_broadcast_title'] = "Você tem certeza?";
$lang['telegram_channel_alert_broadcast_confirmButtonText'] = "Sim";
$lang['telegram_channel_alert_broadcast_cancelButtonText'] = "Não";
$lang['telegram_channel_alert_broadcast_two_title'] = "Concluído!";

// alert pause
$lang['telegram_channel_alert_broadcast_pause_text'] = "Você deseja pausar a campanha selecionada?";
$lang['telegram_channel_alert_broadcast_pause_all_text'] = "Você deseja pausar todas as campanhas selecionadas?";
$lang['telegram_channel_alert_broadcast_pause_two_text'] = "Campanha pausada com sucesso!";
$lang['telegram_channel_alert_broadcast_pause_two_all_text'] = "Campanhas pausadas com sucesso!";

// alert resume
$lang['telegram_channel_alert_broadcast_resume_text'] = "Você deseja retomar a campanha selecionada?";
$lang['telegram_channel_alert_broadcast_resume_all_text'] = "Você deseja retomar todas as campanhas selecionadas?";
$lang['telegram_channel_alert_broadcast_resume_two_text'] = "Campanha retomada com sucesso!";
$lang['telegram_channel_alert_broadcast_resume_two_all_text'] = "Campanhas retomadas com sucesso!";

// alert error
$lang['telegram_channel_error_broadcast_title'] = "Erro!";
$lang['telegram_channel_error_broadcast_message'] = "Ocorreu um erro ao executar a ação";

// alert export
$lang['telegram_channel_alert_export_title'] = "Lista enviada!";
$lang['telegram_channel_alert_export_text'] = "O e-mail pode demorar até uma hora para chegar.";
$lang['telegram_channel_alert_export_confirmButtonText'] = "Ok!";

// alert dropzone
$lang['telegram_channel_alert_dropzone_title'] = "Atenção";
$lang['telegram_channel_alert_dropzone_text'] = "Você ultrapassou o limite de 2 imagens!";
$lang['telegram_channel_alert_dropzone_confirmButtonText'] = "Ok!";

$lang['telegram_channel_alert_dropzone_two_title'] = "Atenção";
$lang['telegram_channel_alert_dropzone_two_text'] = "Limite máximo de 10 MB por arquivo";
$lang['telegram_channel_alert_dropzone_two_confirmButtonText'] = "Ok!";

$lang['telegram_channel_alert_dropzone_three_title'] = "Atenção";
$lang['telegram_channel_alert_dropzone_three_text'] = "Não é permitido imagens PNG!";
$lang['telegram_channel_alert_dropzone_three_confirmButtonText'] = "Ok!";

$lang['telegram_channel_alert_dropzone_three_extensions'] = "São aceitos somente arquivos (jpeg, jpg, pdf ou mp4 de até 10 MB)";

// Alert check time to edit
$lang['telegram_channel_error_title'] = "Erro";
$lang['telegram_channel_error_ta023'] = "Não é possível editar essa campanha. O tempo para edição é de até 1 hora antes do envio.";
$lang['telegram_channel_error_ta024'] = "Não é possível editar uma campanha que já foi enviada, cancelada ou que esteja em progresso.";

// Alert datatables
$lang['telegram_channel_datatables_edit_column_action'] = "Editar";
$lang['telegram_channel_datatables_edit_status_2'] = "Não é possível editar uma campanha que foi enviada.";
$lang['telegram_channel_datatables_edit_status_5'] = "Não é possível editar uma campanha que foi cancelada.";
$lang['telegram_channel_datatables_edit_status_6'] = "Não é possível editar uma campanha que está em andamento.";
$lang['telegram_channel_datatables_edit_default'] = "Não é possível editar essa campanha.";
$lang['telegram_channel_datatables_edit_less_than_one_hour'] = "Não é possivel editar essa campanha. O tempo para edição é de até 1 hora antes do envio.";

// View form validation
$lang['telegram_channel_check_date'] = "A data de agendamento é menor que a data atual";
$lang['telegram_channel_check_hour'] = "A hora de agendamento deve ser no mínimo 30 minutos maior que a hora atual!";
$lang['telegram_channel_check_date_validity'] = "A data de vigência é menor que a data de agendamento";
// $lang['telegram_channel_check_hour_validity'] = "A hora de vigência deve ser no mínimo uma hora maior que a hora de agendamento";
$lang['telegram_channel_check_hour_validity'] = "A hora de vigência é menor ou igual a hora de agendamento";
$lang['telegram_channel_alert_field_validation'] = "Este campo é obrigatório.";

$lang['telegram_channel_validation_message'] = "Mensagem";
$lang['telegram_channel_validation_img'] = "Imagem";
$lang['telegram_channel_validation_cancel_title'] = "Erro";
$lang['telegram_channel_validation_cancel_description'] = "Tente novamente mais tarde!";
$lang['telegram_channel_validation_cancel'] = "Campanha já cancelada!";

$lang['whatsapp_broadcas_guia_1'] = "Conheça nosso";
$lang['whatsapp_broadcas_guia_2'] = "Guia de boas práticas!";