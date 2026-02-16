<?php

defined('BASEPATH') or exit('No direct script access allowed');

// View Waba
$lang['persona_waba_header'] = "Personas";
$lang['persona_waba_edit'] = "Editar Persona";
$lang['persona_waba_add'] = "Nova Persona";

// View find
$lang['persona_topnav'] = "Personas";

$lang['persona_btn_new'] = "Novo";
$lang['persona_btn_export'] = "Exportar";

$lang['persona_column_name'] = "Nome";
$lang['persona_column_channel'] = "Canal";
$lang['persona_column_creation'] = "Data de criação";
$lang['persona_column_subscribers'] = "Inscritos";
$lang['persona_column_action_export'] = "Exportar";
$lang['persona_column_action_edit'] = "Editar";
$lang['persona_column_action_delete'] = "Deletar";

$lang['persona_tooltip_update'] = "Atualiza a cada 7 dias";
$lang['persona_row_subscriber'] = " Inscrito";
$lang['persona_row_subscribers'] = " Inscritos";

$lang['persona_export_title'] = "Exportação de dados";
$lang['persona_export_email'] = "Deseja enviar os dados para o email:";
$lang['persona_export_btn_confirm'] = "Confirmar";
$lang['persona_export_btn_return'] = "Voltar";

$lang['persona_export_contacts_title'] = "Exportação de dados";
$lang['persona_export_contacts_email'] = "Deseja enviar os dados para o email:";
$lang['persona_export_contacts_btn_confirm'] = "Confirmar";
$lang['persona_export_contacts_btn_return'] = "Voltar";
$lang['persona_export_contacts_no_permit_title'] = "Limite de exportações atingido!";
$lang['persona_export_contacts_no_permit_content'] = "Muitas exportações realizadas por esse usuário, tente novamente mais tarde";

// View add
$lang['persona_add_title'] = "Novo";
$lang['persona_add_select_channel'] = "Selecionar canal";
$lang['persona_add_select_channel_placeholder'] = "Selecionar";
$lang['persona_add_btn_cancel'] = "Cancelar";

// View edit
$lang["persona_edit_title"] = "Editar";
$lang['persona_edit_btn_return'] = "Voltar";
$lang['persona_edit_selected_channel'] = "Canal selecionado";

// View add | edit
$lang['persona_name'] = "Nome";
$lang['persona_name_placeholder'] = "Informe nome";

$lang['persona_link_add_contacts'] = "Adicionar contatos";
$lang['persona_link_import'] = "Importar";

$lang['persona_btn_save'] = "Salvar";
$lang['persona_number_of_contacts'] = "Número de contatos: ";
$lang['persona_add_picture'] = "ADICIONAR UMA IMAGEM";

// Modal add participants
$lang['persona_participants_title'] = "Adicionar Participantes";
$lang['persona_participants_placeholder'] = "Informe o número ou nome";
$lang['persona_participants_btn_confirm'] = "Confirmar";
$lang['persona_participants_btn_cancel'] = "Limpar";

// Modal add participants - JS
$lang['persona_participants_select_all'] = "Selecionar todos";
$lang['persona_participants_no_contacts'] = "Nenhum contato encontrado";

// Modal import
$lang['persona_import_title'] = "Importar contatos";
$lang['persona_import_alert_import'] = "Atenção aos padrões de inserção de dados.";
$lang['persona_import_obs'] = "Copie sua lista de contatos (Excel ou Google Planilhas) e cole abaixo, na sequência, clique em avançar.";
$lang['persona_import_btn_import_advance'] = "Avançar";
$lang['persona_import_column_relate_data'] = "Relacione as colunas com os tipos de dados";
$lang['persona_import_btn_import_contacts'] = "Importar contatos";

// Modal import - JS
$lang['persona_import_select_data_type'] = "Selecione o tipo de dado";
$lang['persona_import_option_name'] = "Nome";
$lang['persona_import_option_phone'] = "Telefone";
$lang['persona_import_option_email'] = "Email";

// Modal progress
$lang['persona_progress_title_save'] = "Salvando dados";
$lang['persona_progress_title_import'] = "Importando dados";
$lang['persona_progress_body'] = "Por favor aguarde, não feche esta aba do navegador.";

// Modal progress - JS
$lang['persona_progress_default'] = "Processando...";
$lang['persona_progress_import'] = "Importados {1} de {2} contatos";
$lang['persona_progress_save'] = "Salvando...";

//Alert save - JS
$lang['persona_alert_empty_title'] = "Atenção!";
$lang['persona_alert_empty_text'] = "Informe ao menos um participante.";
$lang['persona_alert_empty_text_confirmButtonText'] = "Ok";

// Alert cancel persona | change channel
$lang['persona_alert_title'] = "Atenção!";
$lang['persona_alert_text'] = "Toda a seleção de participantes será perdida";
$lang['persona_alert_btn_confirm'] = "Confirmar";
$lang['persona_alert_btn_cancel'] = "Cancelar";

// Alert delete
$lang['persona_alert_delete_title'] = "Você tem certeza?";
$lang['persona_alert_delete_text'] = "Quer realmente remover esta persona?";
$lang['persona_alert_delete_confirmButtonText'] = "Sim";
$lang['persona_alert_delete_cancelButtonText'] = "Não";
$lang['persona_alert_delete_sucess'] = "Excluído com sucesso!";
$lang['persona_alert_delete_two_confirmButtonTextt'] = "Ok";

//Alert dropzone - JS
$lang['persona_alert_dropzone_maxSize_title'] = "Atenção!";
$lang['persona_alert_dropzone_maxSize_text'] = "Limite máximo de 10 MB";
$lang['persona_alert_dropzone_maxSize_confirmButtonText'] = "Ok";

$lang['persona_alert_dropzone_archives_title'] = "Atenção!";
$lang['persona_alert_dropzone_archives_text'] = "São aceitos somente arquivos (jpeg, jpg ou png).";
$lang['persona_alert_dropzone_archives_confirmButtonText'] = "Ok";

// Alert export
$lang['persona_alert_export_title'] = "Lista Enviada!";
$lang['persona_alert_export_text'] = "O e-mail pode levar até uma hora para chegar.";
$lang['persona_alert_export_confirmButtonText'] = "Ok";

// Alert import
$lang['persona_alert_import_info'] = "Cada importação está sujeita a um limite máximo de 10 mil contatos.\nNúmeros duplicados não serão importados.\nO número deve ter DDI e DDD.\nO nome deve ter até 100 caracteres.\nO email deve ter até 55 caracteres.";
$lang['persona_alert_import_info_preview'] = "Os dados destacados em vermelho indicam informações duplicadas.\nNúmeros duplicados não serão importados.\nO número deve ter DDI e DDD.\nO nome deve ter até 100 caracteres.\nO email deve ter até 55 caracteres.";

// Alert import - JS
$lang['persona_alert_import_title'] = "Atenção!";
$lang['persona_alert_import_phone_select'] = "Ao menos uma coluna deve conter o número do telefone.";
$lang['persona_alert_import_contact_number'] = "Os números não seguem os padrões de inserção de dados.";
$lang['persona_alert_import_contact_limit_exceeded'] = "Cada importação está sujeita a um limite máximo de 10 mil contatos.";

// Alert info channel  - JS
$lang['persona_alert_channel_btn_import_contacts'] = "Selecione um canal para importar";
$lang['persona_alert_channel_btn_add_contacts'] = "Selecione um canal para obter contatos";

// form validation - JS
$lang['persona_alert_name'] = "O campo Nome é obrigatório.";
$lang['persona_alert_channel'] = "O campo Canal é obrigatório.";
$lang['persona_alert_name_min_length'] = "O campo Nome deve conter pelo menos 3 caracteres.";
