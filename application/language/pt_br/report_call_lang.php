<?php

defined('BASEPATH') or exit('No direct script access allowed');
// View Waba
$lang['report_call_waba_header'] = "Relatório de Atendimento";

// View find 
$lang['report_call_service_topnav'] = "Relatório de Atendimento";
$lang['report_call_service_btn_filter'] = "Filtrar";
$lang['report_call_service_btn_export'] = "Exportar";

$lang['report_call_service_filter_title'] = "Filtrar atendimento";
$lang['report_call_service_filter_search'] = "Telefone, Nome do Contato ou Protocolo";
$lang['report_call_service_filter_search_placeholder'] = "Pesquisar...";
$lang['report_call_service_filter_channel'] = "Canal";
$lang['report_call_service_filter_channel_placeholder'] = "Selecionar...";
$lang['report_call_service_filter_categories'] = "Categoria";
$lang['report_call_service_filter_categories_deleted'] = "Excluída";
$lang['report_call_service_filter_categories_placeholder'] = "Selecionar...";
$lang['report_call_service_filter_label'] = "Etiqueta";
$lang['report_call_service_filter_label_placeholder'] = "Selecionar...";
$lang['report_call_service_filter_user'] = "Usuário";
$lang['report_call_service_filter_user_placeholder'] = "Selecionar...";
$lang['report_call_service_filter_sector'] = "Setor";
$lang['report_call_service_filter_sector_placeholder'] = "Selecionar...";
$lang['report_call_service_filter_situation'] = "Situação";
$lang['report_call_service_filter_situation_select'] = "Selecionar...";
$lang['report_call_service_filter_situation_closed'] = "Encerrado";
$lang['report_call_service_filter_situation_in_attendance'] = "Em atendimento";
$lang['report_call_service_filter_period'] = "Período";
$lang['report_call_service_filter_period_placeholder_date_start'] = "Data inicial";
$lang['report_call_service_filter_period_placeholder_date_end'] = "Data final";
$lang['report_call_service_filter_btn_return'] = "Voltar";
$lang['report_call_service_filter_btn_search'] = "Buscar";
$lang['report_call_service_filter_period_notify'] = "Período selecionado indisponível. Dados armazenados por 90 dias.";

$lang['report_call_service_column_creation'] = "Data de Criação";
$lang['report_call_service_column_protocol'] = "Protocolo ";
$lang['report_call_service_column_name_contact'] = "Nome/ Contato";
$lang['report_call_service_column_user_sector'] = "Usuário/ Setor";
$lang['report_call_service_column_channel'] = "Canal";
$lang['report_call_service_column_categories'] = "Categoria";
$lang['report_call_service_column_label'] = "Etiqueta";
$lang['report_call_service_column_evaluation'] = "Avaliação de Atendimento";
$lang['report_call_service_column_situation'] = "Situação/ Tempo";

$lang['report_call_service_column_action_view'] = "Visualizar";
$lang['report_call_service_column_action_export'] = "Exportar";

$lang['report_call_service_export_title'] = "Exportação de dados";
$lang['report_call_service_export_email'] = "Deseja enviar os dados para o email:";
$lang['report_call_service_export_email_placeholder'] = "Informe o e-mail para exportação";
$lang['report_call_service_export_btn_confirm'] = "Confirmar";
$lang['report_call_service_export_btn_return'] = "Voltar";
$lang['report_call_service_export_no_permit_title'] = "Limite de exportações atingido!";
$lang['report_call_service_export_no_permit_content'] = "Muitas exportações realizadas por esse usuário, tente novamente mais tarde";

$lang['history_report_call_service_btn_export'] = "Exportar conversa";
$lang['history_report_call_service_export_title'] = "Informe o e-mail para a exportação";
$lang['history_report_call_service_export_email'] = "E-mail";
$lang['history_report_call_service_export_btn_send'] = "Enviar";

$lang['chat_report_call_service_export_title'] = "Informe o e-mail para a exportação";
$lang['chat_report_call_service_export_email'] = "E-mail";
$lang['chat_report_call_service_export_btn_send'] = "Enviar";

// JS
$lang['report_call_interactive_flow_message_client'] = "Cliente:";
$lang['report_call_interactive_flow_message_yes'] = "Sim";
$lang['report_call_interactive_flow_message_no'] = "Não";

// Alert export
$lang['report_call_alert_export_title'] = "Lista enviada!";
$lang['report_call_alert_export_text'] = "O e-mail pode demorar até uma hora para chegar.";
$lang['report_call_alert_export_confirmButtonText'] = "Ok!";

// Alert out ninety days
$lang['report_call_alert_out_ninety_days_title'] = "Atenção!";
$lang['report_call_alert_out_ninety_days_confirmButtonText'] = "Ok!";

// Chat
$lang['report_call_chat_status_in_attendance'] = "EM ATENDIMENTO";
$lang['report_call_chat_status_attendance_closed'] = "ATENDIMENTO ENCERRADO";

$lang['report_call_chat_transfer_attendance'] = "Transferiu o atendimento às ";
$lang['report_call_chat_to'] = " para";
$lang['report_call_chat_start_attendance'] = "Iniciou o atendimento às ";
$lang['report_call_chat_waiting_attendance'] = "Colocou o atendimento em espera";
$lang['report_call_chat_closed_attendance'] = "Encerrou o atendimento às ";

$lang['report_call_chat_no_record_found'] = "Nenhum registro encontrado para esse período";
$lang['report_call_chat_audio_icon_title'] = "Transcrever áudio";
$lang['report_call_chat_audio_text'] = "Transcrevendo áudio, por favor aguarde.";
$lang['report_call_chat_transcribe_audio_error_apy_key'] = "Para usar esse recurso, integre a plataforma com a sua conta OpenAI através do link: ";
$lang['report_call_chat_transcribe_audio_error_response'] = "Houve uma falha ao transcrever o áudio.";

// Avaliação
$lang['report_call_assessment_details'] = "Detalhes da Avaliação";
$lang['report_call_assessment'] = "Avaliação:";
$lang['report_call_AI_feedback'] = "Feedback da IA:";
$lang['report_call_AI_feedback_none'] = "Nenhum feedback foi gerado pela IA";
$lang['report_call_AI_feedback_empty'] = "Sem avaliação";
$lang['report_call_AI_feedback_gerated_by_IA'] = "Resultado gerado por IA. Recomendamos verificação adicional.";

// Função FSD
$lang['function_fsd_today'] = "Hoje";
$lang['function_fsd_yesterday'] = "Ontem";
$lang['function_fsd_before_yesterday'] = "Anteontem";

$lang['function_fsd_week_day_sun'] = "Domingo";
$lang['function_fsd_week_day_mon'] = "Segunda-Feira";
$lang['function_fsd_week_day_tue'] = "Terça-Feira";
$lang['function_fsd_week_day_wed'] = "Quarta-Feira";
$lang['function_fsd_week_day_thu'] = "Quinta-Feira";
$lang['function_fsd_week_day_fri'] = "Sexta-Feira";
$lang['function_fsd_week_day_sat'] = "Sábado";

$lang['report_call_case18_parseint_message_deleted'] = "Essa mensagem foi apagada!";
$lang['report_call_case18_parseint_you_deleted_this_message'] = "Você apagou essa mensagem!";

$lang['report_call_dt_columndefs_target7_title_edit'] = "Em atendimento";
$lang['report_call_dt_columndefs_target7_title_delete'] = "Encerrado";

$lang['report_call_dt_columndefs_target8_title_edit'] = "Editar";

// Function chatSettings
$lang['report_call_fuction_chatsetting_background'] = "Papel de Parede";
$lang['report_call_fuction_chatsetting_export'] = "Exportar";

// Function calendar
$lang['report_call_calendar_btn_search'] = "Buscar";
$lang['report_call_calendar_btn_return'] = "Voltar";

$lang['report_call_calendar_week_day_sun'] = "Dom";
$lang['report_call_calendar_week_day_mon'] = "Seg";
$lang['report_call_calendar_week_day_tue'] = "Ter";
$lang['report_call_calendar_week_day_wed'] = "Qua";
$lang['report_call_calendar_week_day_thu'] = "Qui";
$lang['report_call_calendar_week_day_fri'] = "Sex";
$lang['report_call_calendar_week_day_sat'] = "Sab";

$lang['report_call_calendar_month_january'] = "Janeiro";
$lang['report_call_calendar_month_february'] = "Fevereiro";
$lang['report_call_calendar_month_march'] = "Março";
$lang['report_call_calendar_month_april'] = "Abril";
$lang['report_call_calendar_month_may'] = "Maio";
$lang['report_call_calendar_month_june'] = "Junho";
$lang['report_call_calendar_month_july'] = "Julho";
$lang['report_call_calendar_month_august'] = "Agosto";
$lang['report_call_calendar_month_september'] = "Setembro";
$lang['report_call_calendar_month_october'] = "Outubro";
$lang['report_call_calendar_month_november'] = "Novembro";
$lang['report_call_calendar_month_december'] = "Dezembro";

$lang["report_call_document_message"] = "Documento";
$lang["report_call_story_mention"] = "Mencionou você no próprio story";
$lang["report_call_service_filter_categories_no_category"] = "Sem categoria";

$lang['report_call_template_flow_message_subtitle'] = "Resposta enviada";
$lang['report_call_template_flow_message_title_modal'] = "Detalhes da resposta";

$lang["report_call_modal_assessment"] = "Avaliação";
$lang["report_call_modal_comment"] = "Comentário";