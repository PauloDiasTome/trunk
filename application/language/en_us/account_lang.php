<?php

defined('BASEPATH') or exit('No direct script access allowed');

// View login
$lang['account_title'] = "Talkall";
$lang['account_subtitle'] = "Enter with your informations";

$lang['account_form_email'] = "E-mail";
$lang['account_form_email_placeholder'] = "Inform a e-mail please";
$lang['account_form_password'] = "Password";
$lang['account_form_password_placeholder'] = "Inform a password please";

$lang['account_form_btn_enter'] = "Enter";
$lang['account_form_btn_enter_text'] = "Coming in, sir(lady) a moment please";
$lang['account_form_forgot_password_text'] = "You forgot your password?";

// Controller

$lang['account_alert_invalid_email_title'] = "Login error";
$lang['account_alert_invalid_email_message'] = "The username or password you entered is not connected to an account, please enter a valid username and password!";
$lang['account_alert_invalid_email_btn'] = "Return";

$lang['account_alert_invalid_forgot_email_title'] = "Login error";
$lang['account_alert_invalid_forgot_email_message'] = "The email you entered is invalid, please enter a valid email address to change your password!";
$lang['account_alert_invalid_forgot_email_btn'] = "Return";

$lang['account_alert_block_user_title'] = "Credential Blocked";
$lang['account_alert_block_user_message'] = "You failed to login three times in a row and your credential was blocked. Contact technical support to unlock the account:</b>";
$lang['account_alert_block_user_btn'] = "Forgot password";

$lang['account_alert_warnig_block_user_title'] = "Login error";
$lang['account_alert_warnig_block_user_subtitle'] = "Invalid email or password";
$lang['account_alert_warnig_block_user_message1'] = "You have more";
$lang['account_alert_warnig_block_user_message2'] = "";
$lang['account_alert_warnig_block_user_btn'] = "Return";

$lang['account_alert_invalid_caracteres_email_title'] = "Login error";
$lang['account_alert_invalid_caracteres_email_message'] = "The email you entered exceeded the number of characters allowed by the platform!";
$lang['account_alert_invalid_caracteres_email_btn'] = "Return";

$lang['account_alert_block_access_work_time_title'] = "Login error";
$lang['account_alert_block_access_work_time_message'] = "Time not allowed for access.";
$lang['account_alert_block_access_work_time_btn'] = "Return";

$lang['account_alert_authentication_2fa_title'] = "Identification by two factors";
$lang['account_alert_authentication_2fa_message'] = "Enter the 6-digit code sent via sms to the number: ";

$lang['account_alert_required_intranet_title'] = "Warning";
$lang['account_alert_required_intranet_messge'] = "It is necessary to generate a support password via the intranet!";
$lang['account_alert_required_intranet_btn'] = "Return";

$lang['account_alert_external_access_not_allwed_title'] = "Warning";
$lang['account_alert_external_access_not_allwed_title'] = "External access is not allowed for your user!";
$lang['account_alert_external_access_not_allwed_btn'] = "Return";

$lang['account_alert_expired_code_attempts_title'] = "Warning";
$lang['account_alert_expired_code_attempts_message'] = "You have exceeded the login attempt limit, please try again later!";
$lang['account_alert_expired_code_attempts_btn'] = "Return";

$lang['account_alert_2fa_warning_block_user_message1'] = "You have more";
$lang['account_alert_2fa_warning_block_user_message2'] = "chances to access the system. otherwise your account will be blocked!";

$lang['account_alert_2fa_blocked_user_message'] = "You failed to login three times in a row and your credential was blocked. Contact technical support to unlock the account: ";

$lang['account_alert_2fa_unexpected_error_message'] = "Login error, please try later!";

$lang['account_alert_2fa_expired_message'] = "Expired code please resend the code!";

$lang['account_alert_2fa_default_message'] = "Something went wrong, try later!";

$lang['account_alert_new_password_error_title'] = "Error";
$lang['account_alert_new_password_error_btn'] = "Return";
$lang['account_alert_new_password_error_message'] = "";

$lang['account_alert_new_password_enter_password'] = "Type new password";
$lang['account_alert_new_password_placeholder'] = "New password";
$lang['account_alert_new_password_placeholder_repeat'] = "Repeat new password";
$lang['account_alert_new_password_button'] = "Set New Password";

$lang['account_alert_new_password_success_title'] = "All right!";
$lang['account_alert_new_password_success_btn'] = "Go to Login";
$lang['account_alert_new_password_success_message'] = "Sua senha foi redefinida com sucesso, acesse <b>app.talkall.com.br</b> para fazer login e entrar no sistema.";

$lang['account_alert_forgot_password_success_title'] = "All right!";
$lang['account_alert_forgot_password_success_btn'] = "Go to Login";
$lang['account_alert_forgot_password_success_message1'] = "If the email: <b>";
$lang['account_alert_forgot_password_success_message2'] = "</b> exists in our database, you will receive a link to reset the password.";

$lang['account_alert_form_validation_password_changed_password'] = "Password";
$lang['account_alert_form_validation_password_changed_confirm'] = "Password Confirmation";
// ----------------------------------------------------------------------------------------

$lang['account_alert_email_title'] = "We sent an email to: <b>";
$lang['account_alert_email_two_title'] = "</b> with the link to reset the password.";

$lang['account_alert_password_changed'] = "Password changed to default password!";

$lang['account_alert_password_changed_expired'] = "Expired link or invalid url.<br>Contact responsible administration to request a new link. 404 An error was encountered";

$lang['account_alert_sendemail_title'] = "TalkAll password";
$lang['account_alert_sendemail_desciption'] = "To change your password please";

$lang['account_alert_request'] = "The sms request failed!";

// Account without permission
$lang['account_without_permission_view_name'] = "Without Permission";
$lang['account_without_permission_img_title'] = "Image of a hand holding a padlock.";
$lang['account_without_permission_img_alt'] = "Image of a hand holding a closed padlock, symbolizing that the user does not have permission to access the feature.";
$lang['account_without_permission_text_title'] = "Oops! You do not have permission to access this feature.";
$lang['account_without_permission_text_body'] = "Please check your subscription plan or contact the system administrator to gain access.";
