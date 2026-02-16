<?php

defined('BASEPATH') or exit('No direct script access allowed');

// View login
$lang['account_title'] = "Talkall";
$lang['account_subtitle'] = "Entre com suas credenciais";

$lang['account_form_email'] = "E-mail";
$lang['account_form_email_placeholder'] = "Informe e-mail por favor";
$lang['account_form_password'] = "Senha";
$lang['account_form_password_placeholder'] = "Informe senha por favor";

$lang['account_form_btn_enter'] = "Entrar";
$lang['account_form_btn_enter_text'] = "Entrando, senhor(a) um momento por favor";
$lang['account_form_forgot_password_text'] = "Esqueceu sua senha?";


// Controller //

$lang['account_alert_invalid_email_title'] = "Erro de login";
$lang['account_alert_invalid_email_message'] = "O usuário ou senha que você digitou não está conectado a uma conta, por favor informe um usuário e senha valido!";
$lang['account_alert_invalid_email_btn'] = "Voltar";

$lang['account_alert_invalid_forgot_email_title'] = "Erro de login";
$lang['account_alert_invalid_forgot_email_message'] = "O email que você digitou é invalido, por favor insira um email válido para alterar sua senha!";
$lang['account_alert_invalid_forgot_email_btn'] = "Voltar";

$lang['account_alert_block_user_title'] = "Credencial Bloqueada";
$lang['account_alert_block_user_message'] = "Você errou em realizar o login três vezes consecutivas e sua credencial foi bloqueada. Entre em contato com o suporte técnico para desbloquear a conta: ";
$lang['account_alert_block_user_btn'] = "Esqueceu senha";

$lang['account_alert_warnig_block_user_title'] = "Erro de login";
$lang['account_alert_warnig_block_user_subtitle'] = "Email ou senha inválidos";
$lang['account_alert_warnig_block_user_message1'] = "Voce tem mais";
$lang['account_alert_warnig_block_user_message2'] = "chances para acessar o sistema. caso contrário, sua conta será bloqueada!";
$lang['account_alert_warnig_block_user_btn'] = "Voltar";

$lang['account_alert_invalid_caracteres_email_title'] = "Erro de login";
$lang['account_alert_invalid_caracteres_email_message'] = "O e-mail que você digitou excedeu a quantidade de caracteres permitido pela plataforma!";
$lang['account_alert_invalid_caracteres_email_btn'] = "Voltar";

$lang['account_alert_block_access_work_time_title'] = "Erro de login";
$lang['account_alert_block_access_work_time_message'] = "Horário não permitido para acesso.";
$lang['account_alert_block_access_work_time_btn'] = "Voltar";

$lang['account_alert_authentication_2fa_title'] = "Identificação por dois fatores";
$lang['account_alert_authentication_2fa_message'] = "Insira o código de 6 dígitos enviado via sms para o número: ";

$lang['account_alert_required_intranet_title'] = "Atenção";
$lang['account_alert_required_intranet_messge'] = "É necessário gerar uma senha de suporte pela intranet!";
$lang['account_alert_required_intranet_btn'] = "Voltar";

$lang['account_alert_external_access_not_allwed_title'] = "Atenção";
$lang['account_alert_external_access_not_allwed_title'] = "Acesso externo não está permitido para seu usuário!";
$lang['account_alert_external_access_not_allwed_btn'] = "Voltar";

$lang['account_alert_expired_code_attempts_title'] = "Atenção";
$lang['account_alert_expired_code_attempts_message'] = "Você excedeu o limite de tentativas de login, por favor tente mais tarde!";
$lang['account_alert_expired_code_attempts_btn'] = "Voltar";

$lang['account_alert_2fa_warning_block_user_message1'] = "Voce tem mais";
$lang['account_alert_2fa_warning_block_user_message2'] = "chances para acessar o sistema. caso contrário, sua conta será bloqueada!";

$lang['account_alert_2fa_blocked_user_message'] = "Você errou em realizar o login três vezes consecutivas e sua credencial foi bloqueada. Entre em contato com o suporte técnico para desbloquear a conta: ";

$lang['account_alert_2fa_unexpected_error_message'] = "Erro de login, por favor tente mais tarde!";

$lang['account_alert_2fa_expired_message'] = "Código expirado por favor reenvie o código!";

$lang['account_alert_2fa_default_message'] = "Algo deu errado tente mais tarde!";

$lang['account_alert_new_password_error_title'] = "Erro";
$lang['account_alert_new_password_error_btn'] = "Voltar";
$lang['account_alert_new_password_error_message'] = "";

$lang['account_alert_new_password_enter_password'] = "Digite sua nova senha"; 
$lang['account_alert_new_password_placeholder'] = "Nova senha";
$lang['account_alert_new_password_placeholder_repeat'] = "Repetir nova senha";
$lang['account_alert_new_password_button'] = "Definir nova senha";


$lang['account_alert_new_password_success_title'] = "Tudo certo!";
$lang['account_alert_new_password_success_btn'] = "Ir para Login";
$lang['account_alert_new_password_success_message'] = "Sua senha foi redefinida com sucesso, acesse <b>app.talkall.com.br</b> para fazer login e entrar no sistema.";

$lang['account_alert_forgot_password_success_title'] = "Tudo certo!";
$lang['account_alert_forgot_password_success_btn'] = "Ir para Login";
$lang['account_alert_forgot_password_success_message1'] = "Se o e-mail: <b>";
$lang['account_alert_forgot_password_success_message2'] = "</b> existir em nosso banco de dados, você receberá um link para redefinir a senha.";

$lang['account_alert_form_validation_password_changed_password'] = "Senha";
$lang['account_alert_form_validation_password_changed_confirm'] = "Confirmação de senha";
// ----------------------------------------------------------------------------------------

$lang['account_alert_email_title'] = "Enviamos um e-mail para: <b>";
$lang['account_alert_email_two_title'] = "</b> com o link para redefinir a senha.";

$lang['account_alert_password_changed'] = "Senha alterada para senha padrão!";

$lang['account_alert_password_changed_expired'] = "Link expirado ou url inválida.<br>Entre em contato com responsável pela administração para pedir um novo link. 404 Um erro foi encontrado";

$lang['account_alert_sendemail_title'] = "Senha TalkAll";
$lang['account_alert_sendemail_desciption'] = "Para alterar sua senha por favor";

$lang['account_alert_request'] = "A requisição do sms falhou!";

// Account without permission
$lang['account_without_permission_view_name'] = "Sem Permissão";
$lang['account_without_permission_img_title'] = "Imagem de uma mão segurando um cadeado.";
$lang['account_without_permission_img_alt'] = "Imagem de uma mão segurando um cadeado fechado, representando que o usuário não tem permissão para acessar a funcionalidade.";
$lang['account_without_permission_text_title'] = "Ops! Você não tem permissão para acessar esta funcionalidade.";
$lang['account_without_permission_text_body'] = "Verifique seu plano de assinatura ou entre em contato com o gestor do sistema para obter acesso.";
