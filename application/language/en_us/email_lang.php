<?php
/**
 * System messages translation for CodeIgniter(tm)
 *
 * @author	CodeIgniter community
 * @copyright	Copyright (c) 2014 - 2015, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');

$lang['email_must_be_array'] = 'O método de validação de email deve ser passado um array.';
$lang['email_invalid_address'] = 'Endereço de email inválido: %s';
$lang['email_attachment_missing'] = 'Não é possível localizar o seguinte arquivo em anexo: %s';
$lang['email_attachment_unreadable'] = 'Não é possível abrir o anexo: %s';
$lang['email_no_from'] = 'Não é possivel enviar email sem email de origem.';
$lang['email_no_recipients'] = 'Você deve incluir os destinatários: To(para), Cc(cópia), ou Bcc(cópia oculta)';
$lang['email_send_failure_phpmail'] = 'Não é possível enviar email usando PHP mail(). Seu servidor talvez não esteja configurado para enviar email usando este método.';
$lang['email_send_failure_sendmail'] = 'Não é possível enviar email usando PHP Sendmail. Seu servidor talvez não esteja configurado para enviar email usando este método.';
$lang['email_send_failure_smtp'] = 'Não é possível enviar email usando PHP SMTP. Seu servidor talvez não esteja configurado para enviar email usando este método.';
$lang['email_sent'] = 'Sua mensagem foi enviada com sucesso usando o seguinte protocolo: %s';
$lang['email_no_socket'] = 'Não é possível abrir um socket para o Sendmail. Por favor verifique as configurações.';
$lang['email_no_hostname'] = 'Você não especificou um endereço SMTP.';
$lang['email_smtp_error'] = 'Os seguintes erros SMTP ocorreram: %s';
$lang['email_no_smtp_unpw'] = 'Erro: Você deve atribuir um usuário e senha do SMTP.';
$lang['email_failed_smtp_login'] = 'Falha ao enviar comando AUTH LOGIN. Erro: %s';
$lang['email_smtp_auth_un'] = 'Falha ao autenticar usuário. Erro: %s';
$lang['email_smtp_auth_pw'] = 'Falha ao autenticar senha. Erro: %s';
$lang['email_smtp_data_failure'] = 'Não foi possível enviar dados: %s';
$lang['email_exit_status'] = 'Código de status de saída: %s';

// lang for folder views/email/*
$lang['email_confirmation'] = "Your email has been confirmed";
$lang["email_will_recieve_access"] = "In a few moments you will receive your access credentials by email!";
$lang["email_thanks_for_using"] = "Thank you for using TalkAll";
$lang["email_confirm_btn"] = "Confirm email";
$lang["email_privacy_policy"] = "Privacy Policy";
$lang["email_use_terms"] = "Terms of Use";
$lang["email_contact_us"] = "Contact Us";
$lang["email_rights_reserved"] = "All Rights Reserved.";

$lang["email_almost_ready"] = "You're almost ready to get started";
$lang["email_store_securely"] = "Store this information securely";
$lang["email_acces_panel"] = "Access Panel";
$lang["email_just_contact"] = "Contact";
$lang["email_or_click_here"] = "OR CLICK HERE";
