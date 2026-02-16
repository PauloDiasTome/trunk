<?php

defined('BASEPATH') or exit('No direct script access allowed');

// View Waba
$lang['ticket_waba_header'] = "Tickets";
$lang['ticket_waba_add'] = "Novo Ticket";
$lang['ticket_waba_edit'] = "Editar Ticket";
$lang['ticket_waba_history'] = "Histórico do Ticket";

// View find 
$lang['ticket_topnav'] = "Tickets";
$lang['ticket_btn_new'] = "Novo";
$lang['ticket_btn_filter'] = "Filtrar";
$lang['ticket_btn_export'] = "Exportar";

$lang['ticket_column_creation'] = "Data de Criação";
$lang['ticket_column_contact'] = "Contato";
$lang['ticket_column_company'] = "Empresa";
$lang['ticket_column_user'] = "Usuário";
$lang['ticket_column_ticket_type'] = "Tipo de ticket";
$lang['ticket_column_ticket_status'] = "Status do ticket";

$lang['ticket_filter_title'] = "Filtrar ticket";
$lang['ticket_filter_search'] = "Contato ou usuário";
$lang['ticket_filter_search_placeholder'] = "Pesquisar...";
$lang['ticket_filter_type_ticket'] = "Tipo de ticket";
$lang['ticket_filter_type_ticket_placeholder'] = "Selecinonar...";
$lang['ticket_filter_status_ticket'] = "Status do ticket";
$lang['ticket_filter_period'] = "Período";
$lang['ticket_filter_period_notify'] = "Período selecionado indisponível. Dados armazenados por 90 dias";
$lang['ticket_filter_period_placeholder_date_start'] = "Data inicial";
$lang['ticket_filter_period_placeholder_date_end'] = "Data final";
$lang['ticket_filter_btn_return'] = "Voltar";
$lang['ticket_filter_btn_search'] = "Buscar";

$lang['ticket_export_title'] = "Exportação de dados";
$lang['ticket_export_email'] = "Deseja enviar os dados para o email:";
$lang['ticket_export_email_placeholder'] = "Informe o e-mail para exportação?";
$lang['ticket_export_btn_confirm'] = "Confirmar";
$lang['ticket_export_btn_return'] = "Voltar";

// Generic add e edit e history
$lang['ticket_history'] = "Histórico";
$lang['ticket_creation'] = "Criação";
$lang['ticket_contact'] = "Contato";
$lang['ticket_search'] = "Pesquisar...";
$lang['ticket_company'] = "Empresa";
$lang['ticket_users'] = "Usuários";
$lang['ticket_type'] = "Tipo de ticket";
$lang['ticket_subtype'] = "Subtipo de ticket";
$lang['ticket_select'] = "Selecionar";
$lang['ticket_status'] = "Status do ticket";
$lang['ticket_comment'] = "Comentário: (Até 1500 caracteres) | Caracteres restantes:";
$lang['ticket_btn_return'] = "Voltar";
$lang['ticket_btn_save'] = "Salvar";
$lang['ticket_edit_title'] = "Editar";
$lang["ticket_edit_information"] = "Informações do ticket";
$lang["ticket_add_information"] = "Adicionar ticket";
$lang['ticket_add_title'] = "Novo";
$lang['ticket_dropdown_add_new_ticket_type'] = "Adicionar novo tipo de ticket";
$lang['ticket_dropdown_add_new_ticket_company'] = "Adicionar nova empresa";
$lang['ticket_dropdown_add_new_ticket_status'] = "Adicionar novo status";
$lang['ticket_dropdown_add_new_ticket_type_title'] = "Clique aqui para adicionar novo";
$lang['ticket_dropdown_add_new_ticket_company_title'] = "Clique aqui para adicionar nova";
$lang['ticket_dropdown_add_new_ticket_status_title'] = "Clique aqui para adicionar novo";
$lang['ticket_status_ticket_info'] = "Esse status significa que o ticket está?";
$lang['ticket_status_open'] = "Aberto";
$lang['ticket_status_closed'] = "Fechado";
$lang['ticket_status_name'] = "Nome";


// modal ticket company /// 
$lang['ticket_modal_company_title'] = "Adicionar empresa";
$lang['ticket_modal_company_creation'] = "Criação";
$lang['ticket_modal_company_corporate_name'] = "Razão Social";
$lang['ticket_modal_company_corporate_name_placeholder'] = "Informe Razão Social";
$lang['ticket_modal_company_cnpj'] = "CNPJ";
$lang['ticket_modal_company_cnpj_placeholder'] = "Informe cnpj";
$lang['ticket_modal_company_fantasy_name'] = "Nome fantasia";
$lang['ticket_modal_company_fantasy_name_placeholder'] = "Informe nome Fantasia";

$lang['ticket_modal_company_btn_save'] = "Salvar";
$lang['ticket_modal_company_btn_return'] = "Voltar";

// modal ticket type /// 
$lang['ticket_modal_type_title'] = "Adicionar tipo de ticket";
$lang['ticket_modal_type_name'] = "Nome";
$lang['ticket_modal_type_name_placeholder'] = "Informe nome";
$lang['ticket_modal_type_sector'] = "Setor";
$lang['ticket_modal_type_sla'] = "SLA do ticket";
$lang['ticket_modal_type_sla_null'] = "Não há SLA do ticket cadastrado";

$lang['ticket_modal_type_btn_save'] = "Salvar";
$lang['ticket_modal_type_btn_return'] = "Voltar";

// modal ticket status /// 
$lang['ticket_modal_status_title'] = "Adicionar status do ticket";
$lang['ticket_modal_status_name'] = "Nome";
$lang['ticket_modal_status_name_placeholder'] = "Informe nome";
$lang['ticket_modal_status_cor'] = "Cor";

$lang['ticket_modal_status_btn_save'] = "Salvar";
$lang['ticket_modal_status_btn_return'] = "Voltar";

// JS

// js global variables
$lang['ticket_dt_columndefs_target5_title_history'] = "Histórico";
$lang['ticket_dt_columndefs_target5_title_edit'] = "Editar";
$lang['ticket_dt_columndefs_target5_title_delete'] = "Deletar";

$lang['ticket_modal_filter_dt_start_placeholder_initial_date'] = "Data Inicial";

// alert delete
$lang['ticket_alert_delete_title'] = "Você tem certeza?";
$lang['ticket_alert_delete_text'] = "Quer realmente apagar este ticket?";
$lang['ticket_alert_delete_confirmButtonText'] = "Sim";
$lang['ticket_alert_delete_cancelButtonText'] = "Não";

$lang['ticket_alert_delete_two_title'] = "Atenção";
$lang['ticket_alert_delete_two_text'] = "O ticket não pode ser removido!";

$lang['ticket_alert_delete_three_title'] = "Deletado!";
$lang['ticket_alert_delete_three_text'] = "Ticket removido com sucesso!";

// alert export
$lang['ticket_alert_export_title'] = "Lista enviada!";
$lang['ticket_alert_export_text'] = "O e-mail pode demorar até uma hora para chegar.";
$lang['ticket_alert_export_confirmButtonText'] = "Ok!";

// alert validation modal company
$lang['ticket_validation_modal_corporate_name_required'] = "O campo Nome Corporativo é obrigatório.";
$lang['ticket_validation_modal_corporate_name_min_length'] = "O campo Nome Corporativo deve ter pelo menos {param} caractere(s).";
$lang['ticket_validation_modal_corporate_name_max_length'] = "O campo Nome Corporativo ultrapassou o limte de {param} caractere(s).";
$lang['ticket_validation_modal_cnpj_required'] = "O campo CNPJ é obrigatório.";
$lang['ticket_validation_modal_cnpj_length'] = "O campo CNPJ deve ter {param} caractere(s).";
$lang['ticket_validation_modal_fantasy_name_required'] = "O campo Nome Fantasia é obrigatório.";
$lang['ticket_validation_modal_fantasy_name_min_length'] = "O campo Nome Fantasia deve ter pelo menos {param} caractere(s).";
$lang['ticket_validation_modal_fantasy_name_max_length'] = "O campo Nome Fantasia ultrapassou o limte de {param} caractere(s).";

// alert validation modal ticket type
$lang['ticket_validation_modal_ticket_type_name_required'] = "O campo Nome é obrigatório.";
$lang['ticket_validation_modal_ticket_type_name_min_length'] = "O campo Nome deve ter pelo menos {param} caractere(s).";
$lang['ticket_validation_modal_ticket_type_name_max_length'] = "O campo Nome ultrapassou o limte de {param} caractere(s).";
$lang['ticket_validation_modal_ticket_type_sla_required'] = "O campo SLA do ticket é obrigatório.";
$lang['ticket_validation_modal_ticket_type_group_required'] = "O campo Setor é obrigatório.";

// alert validation modal ticket status
$lang['ticket_validation_modal_status_name_required'] = "O campo Nome é obrigatório.";
$lang['ticket_validation_modal_status_name_min_length'] = "O campo Nome deve ter pelo menos {param} caractere(s).";
$lang['ticket_validation_modal_status_name_max_length'] = "O campo Nome ultrapassou o limte de {param} caractere(s).";
