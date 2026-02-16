<?php

defined('BASEPATH') or exit('No direct script access allowed');

// Title waba
$lang['contact_waba_header'] = "Contatos";
$lang['contact_waba_add'] = "Novo contato";
$lang['contact_waba_edit'] = "Editar contato";

// View find 
$lang['contact_topnav'] = "Contatos";
$lang['contact_btn_new'] = "Novo";
$lang['contact_btn_filter'] = "Filtrar";
$lang['contact_btn_consolidate_base'] = "Consolidar base";
$lang['contact_btn_import'] = "Importar arquivo txt";
$lang['contact_btn_export'] = "Exportar";
$lang['contact_btn_delete'] = "Deletar";
$lang['contact_btn_block'] = "Bloquear";
$lang['contact_btn_unblock'] = "Desbloquear";

$lang['contact_column_contact'] = "Contato";
$lang['contact_column_order'] = "Nº Fila";
$lang['contact_column_channel'] = "Canal";
$lang['contact_column_labels'] = "Etiquetas";
$lang['contact_column_date'] = "Data";
$lang['contact_column_creation_date'] = "Data de criação";
$lang['contact_column_status'] = "Situação";

$lang['contact_filter_title'] = "Filtrar contatos";
$lang['contact_filter_search'] = "Contato";
$lang['contact_filter_search_placeholder'] = "Pesquisar...";
$lang['contact_filter_search_option_starts_with'] = "Começa com";
$lang['contact_filter_search_option_contains'] = "Contém";
$lang['contact_filter_channel'] = "Canal";
$lang['contact_filter_channel_placeholder'] = "Selecionar...";
$lang['contact_filter_persona'] = "Personas";
$lang['contact_filter_persona_placeholder'] = "Selecionar...";
$lang['contact_filter_tag'] = "Etiquetas";
$lang['contact_filter_tag_placeholder'] = "Selecionar...";
$lang['contact_filter_responsible'] = "Responsável";
$lang['contact_filter_responsible_select'] = "Selecionar...";
$lang['contact_filter_situation'] = "Situação";
$lang['contact_filter_situation_select'] = "Selecionar...";
$lang['contact_filter_situation_verified'] = "Verificado";
$lang['contact_filter_situation_not_verified'] = "Não verificado";
$lang['contact_filter_situation_no_whatsapp_account'] = "Sem conta de whatsapp";
$lang['contact_filter_situation_spam'] = "Bloqueado";
$lang['contact_filter_period'] = "Período";
$lang['contact_filter_period_placeholder_date_start'] = "Data inicial";
$lang['contact_filter_period_placeholder_date_end'] = "Data final";
$lang['contact_filter_last_received_campaign'] = "Data da Última Campanha Recebida";
$lang['contact_filter_last_received_campaign_placeholder_date_start'] = "De";
$lang['contact_filter_last_received_campaign_placeholder_date_end'] = "Até";
$lang['contact_filter_btn_return'] = "Voltar";
$lang['contact_filter_btn_search'] = "Buscar";

$lang['contact_export_title'] = "Exportação de dados";
$lang['contact_export_email'] = "Deseja enviar os dados para o email:";
$lang['contact_export_email_placeholder'] = "Informe o e-mail para exportação";
$lang['contact_export_btn_confirm'] = "Confirmar";
$lang['contact_export_btn_return'] = "Voltar";

$lang['contact_messenger_open'] = "Abrir Messenger";
$lang['contact_messenger_btn_cancel'] = "Cancelar";
$lang['contact_messenger_btn_ok'] = "Ok";

$lang['contact_dropdown_menu_all'] = "Todos";
$lang['contact_dropdown_menu_empty'] = "Nenhum";

// Persona
$lang['contact_btn_add_persona'] = "Adicionar Persona";

$lang['contact_persona_modal_action_title'] = "Criando persona";
$lang['contact_persona_modal_action_info'] = "Adicionando contatos: ";

$lang['contact_modal_persona_title'] = "Adicionar nova persona";
$lang['contact_modal_persona_picture'] = "Adicionar uma imagem";
$lang['contact_modal_persona_name'] = "Nome";
$lang['contact_modal_persona_name_placeholder'] = "Informe nome";
$lang['contact_modal_persona_channel'] = "Canal selecionado";
$lang['contact_modal_persona_btn_return'] = "Voltar";
$lang['contact_modal_persona_btn_save'] = "Salvar";

$lang['contact_alert_title'] = "Atenção!";
$lang['contact_alert_confirmButtonText'] = "Ok";

$lang['contact_alert_dropzone_maxSize_text'] = "Limite máximo de 10 MB";
$lang['contact_alert_dropzone_archives_text'] = "São aceitos somente arquivos (jpeg, jpg ou png).";
$lang['contact_alert_contact_blocked_text'] = "Contatos bloqueados foram selecionados. Desmarque os contatos bloqueados para prosseguir.";

$lang['contact_alert_created_persona_title'] = "Adicionado!";
$lang['contact_alert_created_persona_text'] = "Persona criada com sucesso!";
$lang['contact_alert_persona_multiple_channels_text'] = "Adicionar personas está desabilitado porque este contato está vinculado a vários canais. Para gerenciar personas, certifique-se de que o contato esteja associado a apenas um canal.";

// Generic add e edit 
$lang['contact_record'] = "Registro";
$lang['contact_name'] = "Nome";
$lang['contact_name_placeholder'] = "Informe nome";
$lang['contact_channel'] = "Canal";
$lang['contact_talkall'] = "TalkAll ID";
$lang['contact_email'] = "E-mail";
$lang['contact_order'] = "Nº Fila";
$lang['contact_email_placeholder'] = "Informe E-mail";
$lang['contact_order_placeholder'] = "Informe Nº";
$lang['contact_label'] = "Etiquetas";
$lang['contact_label_placeholder'] = "Nenhum";
$lang['contact_service_internship'] = "Estágio do Atendimento";
$lang['contact_responsible'] = "Responsável";
$lang['contact_responsible_placeholder'] = "Nenhum";
$lang['contact_notes'] = "Notas";
$lang['contact_notes_placeholder'] = "Digite aqui...";
$lang['contact_notes_character'] = "(Até 550 caracteres) | Caracteres restantes:";
$lang['contact_btn_save'] = "Salvar";
$lang['contact_btn_return'] = "Voltar";
$lang['contact_delete_block_list'] = "Desbloquear";
$lang['contact_all_checkbox'] = "Todos os ";

// View edit
$lang['contact_edit_title'] = "Editar";
$lang['contact_edit_information'] = "Informações de contato";
$lang['contact_edit_input_order_tooltip'] = "Insira um número para alterar a ordem de recebimento da campanha para este contato.";

// JS variaves globais
$lang['contact_datatable_column_situation_user_verify'] = "Contato não verificado";
$lang['contact_datatable_column_situation_user_verified'] = "Contato verificado e sem conta no Whatsapp!";
$lang['contact_datatable_column_situation_user_verified_whatsapp'] = "Contato verificado e tem conta no Whatsapp!";
$lang['contact_datatable_column_situation_user_spam'] = "Contato bloqueado!";
$lang['contact_datatable_dropdown_menu_edit'] = "Editar";
$lang['contact_datatable_dropdown_menu_delete'] = "Deletar";
$lang['contact_datatable_dropdown_menu_block_list'] = "Bloquear";
$lang['contact_js_replace_data'] = "Nenhum";
$lang['contact_js_let_text_hello'] = "Olá";
$lang['contact_js_label_info_contact_one'] = "Deseja abrir conversa com o";
$lang['contact_js_label_info_contact_two'] = "no messenger?";

// alert delete
$lang['contact_alert_delete_title'] = "Você tem certeza?";
$lang['contact_alert_delete_text_contact'] = "Deletar este(s) {{number}} contato(s)?";
$lang['contact_alert_delete_cancelButtonText'] = "Não";
$lang['contact_alert_delete_confirmButtonText'] = "Sim";

// alert block
$lang['contact_alert_block_title'] = "Você tem certeza?";
$lang['contact_alert_block_text_contact'] = "Bloquear este(s) {{number}} contato(s)?";
$lang['contact_alert_block_confirmButtonText'] = "Sim";
$lang['contact_alert_block_cancelButtonText'] = "Não";

// alert unblock
$lang['contact_alert_unblock_title'] = "Você tem certeza?";
$lang['contact_alert_unblock_text_contact'] = "Desbloquear este(s) {{number}} contato(s)?";
$lang['contact_alert_unblock_confirmButtonText'] = "Sim";
$lang['contact_alert_unblock_cancelButtonText'] = "Não";

// alert contact in attendance
$lang['contact_alert_attendance_title'] = "Atenção!";
$lang['contact_alert_attendance_text'] = "A ação não foi concluída, pois há {{number}} contato(s) em atendimento";
$lang['contact_alert_attendance_confirmButtonText'] = "Ok!";

// alert export
$lang['contact_alert_export_title'] = "Lista enviada!";
$lang['contact_alert_export_text'] = "O e-mail pode demorar até uma hora para chegar.";
$lang['contact_alert_export_confirmButtonText'] = "Ok!";

// alert info
$lang['contact_info_all'] = "<b>{{number}} contato(s) selecionado(s)</b>";
$lang['contact_info_description'] = "Por favor aguarde, não feche esta aba do navegador.";
$lang['contact_info_processing'] = "Processando";
$lang['contact_info_the'] = "de";

// modal action 
$lang['contact_modal_action_title_delete'] = "Deletando contato(s)";
$lang['contact_modal_action_title_block'] = "Bloqueando contato(s)";
$lang['contact_modal_action_title_unblock'] = "Desbloqueando contato(s)";
$lang['contact_modal_action_delete'] = "Deletando";
$lang['contact_modal_action_block'] = "Bloqueando";
$lang['contact_modal_action_unblock'] = "Desbloqueando";

$lang['contact_loading'] = "Carregando...";
$lang['contact_modal_label_title'] = "Adicionar etiqueta";
$lang['contact_modal_label_btn_save'] = "Salvar";
$lang['contact_modal_label_btn_cancel'] = "Cancelar";
