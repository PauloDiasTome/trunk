<?php

defined('BASEPATH') or exit('No direct script access allowed');

// View Waba
$lang['template_waba_header'] = "Templates";
$lang['template_waba_add'] = "Novo template";
$lang['template_waba_edit'] = "Editar template";
$lang['template_waba_view'] = "Visualizar template";

// View find 
$lang['template_topnav'] = "Templates";
$lang['template_btn_new'] = "Novo";
$lang['template_btn_export'] = "Exportar";

$lang['template_column_creation'] = "Data de criação";
$lang['template_column_name'] = "Nome";
$lang['template_column_channel'] = "Canal";
$lang['template_column_category'] = "Categoria";
$lang['template_column_language'] = "Linguagem";
$lang['template_column_status'] = "Status";
$lang['template_column_actions'] = "Ações";

$lang['template_export_title'] = "Exportação de dados";
$lang['template_export_email'] = "Deseja enviar os dados para o email:";
$lang['template_export_email_placeholder'] = "Informe o e-mail para exportação";
$lang['template_export_btn_confirm'] = "Confirmar";
$lang['template_export_btn_return'] = "Voltar";

// Generic edit and add
$lang['template_select_option_none'] = "Nenhum";

$lang['template_creation'] = "Criação";
$lang['template_channel'] = "Canal";

$lang['template_name'] = "Nome do template";
$lang['template_name_placeholder'] = "Insira o nome do template";

$lang['template_lang'] = "Linguagem";
$lang['template_port'] = "Português";
$lang['template_en'] = "Inglês";

$lang['template_header'] = "Cabeçalho";
$lang['template_header_placeholder'] = "Insira o texto do cabeçalho";
$lang['template_header_option_text'] = "Texto";
$lang['template_header_option_media'] = "Media";
$lang['template_header_media_image'] = "Imagem";
$lang['template_header_media_video'] = "Vídeo";
$lang['template_header_media_document'] = "Documento";

$lang['template_body_label'] = "Corpo";
$lang['template_body_description'] = "Descrição: (Até 1024 caracteres) | Caracteres restantes:";
$lang['template_body_description_validation'] = "Descrição";
$lang['template_body_button_add_variable'] = "+ Adicionar variável";
$lang['template_body_button_variable_first_last_error'] = "O texto de corpo contém parâmetros variáveis no início ou no fim.";
$lang['template_body_button_variable_qtd_error'] = "O texto de corpo contém muitos parâmetros variáveis em relação ao comprimento da mensagem.";

$lang['template_footer_label'] = "Rodapé";
$lang['template_footer_placeholder'] = "Insira o texto do rodapé";

$lang['template_button_label'] = "Botões";
$lang['template_button_option_cta'] = "Call To Action";
$lang['template_button_option_quick_answer'] = "Resposta rápida";
$lang['template_button_cta_type'] = "Tipo do botão";
$lang['template_button_cta_type_call'] = "Ligar";
$lang['template_button_cta_type_url'] = "Acessar o site";
$lang['template_button_text_placeholder'] = "Insira o texto do botão";
$lang['template_button_country_label'] = "País";
$lang['template_button_phone_label'] = "Telefone";
$lang['template_button_url_type_label'] = "Tipo da URL";
$lang['template_button_url_type_option_static'] = "Estática";
$lang['template_button_url_type_option_dynamic'] = "Dinâmica";
$lang['template_button_url_label'] = "URL do site";
$lang['template_button_text_label'] = "Texto do botão";
$lang['template_button_add_button'] = "+ Adicionar mais um botão";

$lang['template_api_validation'] = "Ocorreu um erro ma criação do template. Tente novamente.";
$lang['template_api_validation_repeated_name'] = "O nome não pode ser o mesmo de um template já existente";
$lang['template_number'] = "1024";
$lang['template_baseboard'] = "Rodapé";

$lang['template_btn_cancel'] = "Cancelar";
$lang['template_btn_save'] = "Salvar";
$lang['template_btn_return'] = "Voltar";

// Add validation 

$lang['template_validation_name'] = "O campo nome é obrigatório";
$lang['template_validation_same_name'] = "Um template com o mesmo nome já foi cadastrado";
$lang['template_validation_same_name_deleted'] = "Um template com este nome está sendo deletado";
$lang['template_validation_invalid_parameter_header'] = "O cabeçalho da mensagem não pode ter novas linhas, caracteres de formatação, emojis ou asteriscos.";
$lang['template_validation_invalid_parameter_button'] = "Os botões não podem conter links diretos para o WhatsApp.";
$lang['template_validation_header_text'] = "O texto do header é obrigatório";
$lang['template_validation_header_media'] = "Selecione um tipo de media para o header";
$lang['template_validation_body'] = "O campo corpo é obrigatório";
$lang['template_validation_button'] = "Preencha todos os campos dos botões";
$lang['template_validation_url_button'] = "Insira uma URL válida";
$lang['template_validation_url_button_phone'] = "Insira um número válido";
$lang['template_validation_general_error'] = "Ocorreu um erro na requisição. Tente novamente mais tarde";

// View add 
$lang["template_add_title"] = "Novo";
$lang["template_add_information"] = "Adicionar template de mensagem";

// View view
$lang["template_view_title"] = "Visualização do Template de Mensagem";
$lang["template_view_information"] = "Informações do template";
$lang['template_view_name'] = "Título";
$lang['template_view_channel'] = "Canal";
$lang['template_view_category'] = "Categoria";
$lang['template_view_language'] = "Linguagem";
$lang['template_view_status'] = "Status";
$lang['template_view_rejected_reason'] = "Motivo da rejeição";

// View edit
$lang["template_edit_title"] = "Editar";
$lang["template_edit_information"] = "Editar template de mensagem";

// alert delete
$lang['template_alert_delete_title'] = "Você tem certeza?";
$lang['template_alert_delete_text'] = "Quer realmente apagar este template?";
$lang['template_alert_delete_confirmButtonText'] = "Sim";
$lang['template_alert_delete_cancelButtonText'] = "Não";

$lang['template_alert_delete_two_title'] = "Deletado!";
$lang['template_alert_delete_two_text'] = "Template removido com sucesso!";

$lang['template_alert_delete_two_title_error'] = "Erro!";
$lang['template_alert_delete_two_text_error'] = "Ocorreu um erro ao tentar remover o template";

// alert export
$lang['template_alert_export_title'] = "Atenção";
$lang['template_alert_export_text'] = "O e-mail pode demorar até uma hora para chegar.";
$lang['template_alert_export_confirmButtonText'] = "Ok!";

$lang['template_alert_export_two_title'] = "Lista enviada!";
$lang['template_alert_export_two_text'] = "O e-mail pode demorar até uma hora para chegar.";
$lang['template_alert_export_two_confirmButtonText'] = "Ok!";

// JS
$lang['template_datatable_column_status_title_review'] = "Em análise";
$lang['template_datatable_column_status_title_approved'] = "Aprovado";
$lang['template_datatable_column_status_title_rejected'] = "Rejeitado";
$lang['template_datatable_column_status_title_called'] = "Enviado";
$lang['template_datatable_column_status_title_delete'] = "Deletar";
$lang['template_datatable_column_status_title_view'] = "Visualizar";
$lang['template_datatable_column_status_title_edit'] = "Editar";
