<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class AccountController extends TA_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->model('Sms_model', '', TRUE);
		$this->load->model('intranet/User_intranet_model', '', TRUE);

		$this->lang->load('user_lang', $this->session->userdata('language'));
		$this->lang->load('account_lang', $this->session->userdata('language'));
		$this->lang->load('email_lang', $this->session->userdata('language'));
	}

	public function Login($view = array())
	{
		parent::checkLogin();

		$data['data'] = $view;

		$this->load->view('pages/account/login', $data);
	}


	public function ConfirmEmail($token)
	{
		$result = $this->User_model->confirm($token);
		if ($result == true) {
			redirect("email/confirm_success/" . $token, 'refresh');
		} else {
			$this->load->view('email/email_confirmed');
		}
	}


	function ConfirmSuccess()
	{
		$this->load->view('email/confirm_success');
	}


	public function callbackError2FA($error, $num = 0, $email = "")
	{
		$callback = array();

		switch ($error) {
			case "WARNING_BLOCK_USER":
				$callback["type"] = "alert";
				$callback["message"] = $this->lang->line("account_alert_2fa_warning_block_user_message1") . " <b>{$num}</b> " . $this->lang->line("account_alert_2fa_warning_block_user_message2");

				break;
			case "BLOCKED_USER":
				$callback["type"] = "user_block";
				$callback["message"] = $this->lang->line("account_alert_2fa_blocked_user_message") . "<b>" . $email . "</b>";

				break;
			case "LOGIN_SUCCESS":
				$callback["type"] = "success";
				$callback["message"] = "";

				break;
			case "LOGIN_UNEXPECTED_ERROR":
				$callback["type"] = "alert";
				$callback["message"] = $this->lang->line("account_alert_2fa_unexpected_error_message");

				break;
			case "LOGIN_EXPIRED_CODE":
				$callback["type"] = "alert";
				$callback["message"] = $this->lang->line("account_alert_2fa_expired_message");

				break;
			case "LOGIN_EXPIRED_CODE_ATTEMPTS":
				$callback["type"] = "expired";
				$callback["message"] = "";

				break;
			default:
				$callback["type"] = "error";
				$callback["message"] = $this->lang->line("account_alert_2fa_default_message");

				break;
		}

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($callback));
	}


	public function callbackError($error, $view, $num = 0, $email = "", $password = "")
	{
		$callback = array();

		switch ($error) {
			case 'INVALID_EMAIL':
				$callback = array(
					"type" => "email",
					"title" => $this->lang->line("account_alert_invalid_email_title"),
					"subtitle" => "",
					"message" => $this->lang->line("account_alert_invalid_email_message"),
					"btn" => $this->lang->line("account_alert_invalid_email_btn")
				);

				break;
			case 'INVALID_FORGOT_EMAIL':
				$callback = array(
					"type" => "email",
					"title" => $this->lang->line("account_alert_invalid_forgot_email_title"),
					"subtitle" => "",
					"message" => $this->lang->line("account_alert_invalid_forgot_email_message"),
					"btn" => $this->lang->line("account_alert_invalid_forgot_email_btn")
				);

				break;
			case 'BLOCKED_USER':
				$callback = array(
					"type" => "block",
					"title" => $this->lang->line("account_alert_block_user_title"),
					"subtitle" => "",
					"message" => $this->lang->line("account_alert_block_user_message") . "<b>" . $email . "</b>",
					"btn" => $this->lang->line("account_alert_block_user_btn")
				);

				break;
			case 'WARNING_BLOCK_USER':
				$callback = array(
					"type" => "error",
					"title" => $this->lang->line("account_alert_warnig_block_user_title"),
					"subtitle" => $this->lang->line("account_alert_warnig_block_user_subtitle"),
					"message" => $this->lang->line("account_alert_warnig_block_user_message1") . " <b>{$num}</b> " . $this->lang->line("account_alert_warnig_block_user_message2"),
					"btn" => $this->lang->line("account_alert_warnig_block_user_btn")
				);

				break;
			case 'INVALID_CARACTERES_EMAIL':
				$callback = array(
					"type" => "error",
					"title" => $this->lang->line("account_alert_invalid_caracteres_email_title"),
					"subtitle" => "",
					"message" =>   $this->lang->line("account_alert_invalid_caracteres_email_message"),
					"btn" =>  $this->lang->line("account_alert_invalid_caracteres_email_btn")
				);

				break;
			case 'BLOCK_ACCESS_WORK_TIME':
				$callback = array(
					"type" => "error",
					"title" => $this->lang->line("account_alert_block_access_work_time_title"),
					"subtitle" => "",
					"message" => $this->lang->line("account_alert_block_access_work_time_message"),
					"btn" => $this->lang->line("account_alert_block_access_work_time_btn")
				);

				break;
			case 'AUTHENTICATION_2FA':
				$callback = array(
					"type" => "2fa",
					"title" => $this->lang->line("account_alert_authentication_2fa_title"),
					"subtitle" => "",
					"email" => $email,
					"password" => $password,
					"message" =>  $this->lang->line("account_alert_authentication_2fa_message") . $num,
					"btn" => ""
				);

				break;
			case 'REQUIRED_INTRANET_PASSWORD':
				$callback = array(
					"type" => "error",
					"title" => $this->lang->line("account_alert_required_intranet_title"),
					"subtitle" => "",
					"message" => $this->lang->line("account_alert_required_intranet_messge"),
					"btn" =>  $this->lang->line("account_alert_required_intranet_btn")
				);

				break;
			case 'EXTERNAL_ACCESS_NOT_ALLOWED':
				$callback = array(
					"type" => "error",
					"title" => $this->lang->line("account_alert_external_access_not_allwed_title"),
					"subtitle" => "",
					"message" =>  $this->lang->line("account_alert_external_access_not_allwed_title"),
					"btn" => $this->lang->line("account_alert_external_access_not_allwed_btn")
				);

				break;
			case 'LOGIN_EXPIRED_CODE_ATTEMPTS':
				$callback = array(
					"type" => "error",
					"title" => $this->lang->line("account_alert_expired_code_attempts_title"),
					"subtitle" => "",
					"message" =>  $this->lang->line("account_alert_expired_code_attempts_message"),
					"btn" => $this->lang->line("account_alert_expired_code_attempts_btn")
				);

				break;
			default:
				break;
		}


		if ($view == "login") {
			return $this->Login($callback);
		} else if ($view == "forgotPassword") {
			return	$this->load->view('pages/account/forgotPassword',  $callback);
		}
	}


	public function Logon()
	{
		$post = $this->input->post();

		if (empty($post['email'])) {
			return $this->callbackError("INVALID_EMAIL", "login");
		}

		$post['type'] = isset($post['type']) ? $post['type'] : null;
		$post['code'] = isset($post['code']) ? $post['code'] : null;

		$user = null;
		$qtdeError = 0;
		$ip_enabled = ['187.18.106.9', '179.185.193.252', '187.62.51.63', '127.0.0.1', '::1'];

		$access = null;

		if (in_array($_SERVER['REMOTE_ADDR'], $ip_enabled)) {
			$access = 'enabled';
		} else {
			$getType = $this->User_model->GetTypeAccess($post['email']);
			if ($getType != null) {
				if ($getType['accessType'] == 0) {
					$access = 'enabled';
				}
			} else {
				// Não exite o email
				return $this->callbackError("INVALID_EMAIL", "login");
			}
		}

		if ($access == 'enabled') {

			if (strlen($post['email']) > 90) {
				return $this->callbackError("INVALID_CARACTERES_EMAIL", "login");
			}

			//verifica se usuário e senha existe//
			if (!empty($post['password'])) {

				$this->session->set_userdata('email_user', $post['email']);
				$this->session->set_tempdata('email', $post['email'], 3600);
				$this->session->set_tempdata('password', $post['password'], 300);

				$user = $this->User_model->GetUserData($this->session->tempdata('email'), $this->session->tempdata('password'));
			} else {
				return $this->callbackError("INVALID_EMAIL", "login");
			}

			//verificação do usuário suporte
			if ($this->StrLike('suporte.%', $post['email']) && $this->StrLike('%@talkall.com.br', $post['email'])) {

				if ($user === null) {
					return $this->callbackError("REQUIRED_INTRANET_PASSWORD", "login");
				} else {
					$user_key_remote_id = $this->User_model->getSupportPasswordCreatorKeyRemoteId($user['email']);
					$this->session->set_userdata('key_remote_id_login_support', $user_key_remote_id);

					$upg = $this->db->get_where('talkall_admin.user_password_generator', [
						'email' => $user['email'],
						'password' => $user['password']
					])->first_row();

					if (!$upg) {
						return $this->callbackError("REQUIRED_INTRANET_PASSWORD", "login");
					}

					$now = new DateTime();
					$diff = round(abs(intval($upg->creation) - $now->getTimestamp()) / 60);

					if ($diff >= 30) {
						return $this->callbackError("REQUIRED_INTRANET_PASSWORD", "login");
					}
				}
			}

			//registra tentativas de login//
			if ($user === null) {

				$id_user = $this->User_model->setUserErrorPassWord($post['email']);

				if ($id_user !== null) {

					$qtdeError = $this->User_model->errorPassWord($id_user);

					if ($qtdeError === "1") {
						return $this->callbackError("WARNING_BLOCK_USER", "login", 2);
					} else if ($qtdeError === "2") {
						return $this->callbackError("WARNING_BLOCK_USER", "login", 1);
					} else if ($qtdeError === "3") {
						return $this->callbackError("BLOCKED_USER", "login", 0, $post['email']);
					} else {
						return $this->callbackError("INVALID_EMAIL", "login");
					}
				} else {
					return $this->callbackError("INVALID_EMAIL", "login");
				}
			} else {

				if (isset($user['id_user'])) {
					$validAccess = $this->User_model->valid_access($user['id_user']);

					if ($validAccess) {
						return $this->callbackError("BLOCKED_USER", "login", 0, $post['email']);
					} else {
						$this->User_model->resetLoginRetry($user['id_user']);
					}
				} else {
					return $this->callbackError("INVALID_EMAIL", "login");
				}
			}

			//bloqueio fora do horário de atendimento//
			// if ($this->User_model->Is_Blocking($user['id_user'])) {

			// 	$workTime = $this->User_model->GetUserWorkTime($user['id_user']);

			// 	// se wortime for true, acesso bloqueado//
			// 	if ($workTime) {
			// 		return $this->callbackError("BLOCK_ACCESS_WORK_TIME", "login");
			// 	}
			// }

			// Antiga função para não bloquear usuário do suporte //
			// if ($this->StrLike('suporte.%', $post['email']) && $this->StrLike('%@talkall.com.br', $post['email'])) {

			// 	$upg = $this->db->get_where('talkall_admin.user_password_generator', [
			// 		'email' => $user['email'],
			// 		'password' => $user['password']
			// 	])->first_row();

			// 	if (!$upg) {
			// 		return $this->callbackError("REQUIRED_INTRANET_PASSWORD", "login");
			// 	}

			// 	$now = new DateTime();
			// 	$diff = round(abs(intval($upg->creation) - $now->getTimestamp()) / 60);

			// 	if ($diff >= 30) {
			// 		return $this->callbackError("REQUIRED_INTRANET_PASSWORD", "login");
			// 	}
			// }

			if ($_SERVER['HTTP_HOST'] != "intranet.talkall.com.br:8443") {
				// User has two factor authentication enabled

				if ((bool) $user['2fa']) {

					$send =  $this->User_model->RequestTwofa($user['phone'], $user['email'], $user['key_remote_id'], false);

					if ($send === TRUE) {

						$phone = hiddenString($user['phone'], 0, 4);
						return $this->callbackError("AUTHENTICATION_2FA", "login", $phone, $post["email"], $post["password"]);
					} else if ($send == "LOGIN_EXPIRED_CODE_ATTEMPTS") {

						return $this->callbackError("LOGIN_EXPIRED_CODE_ATTEMPTS", "login");
					} else {
						return;
					}
				}
			}

			if ($user['password'] == $this->config->item('default_password')) {
				return $this->securityPassword($user);
			}

			$user['WebSessionToken'] = Token();
			CreateWebSessionToken($user);
			$this->User_model->setUserSession($user);

			if ($this->isLoginApp() == false) {
				return $this->Login();
			}
		} else {
			// Usuário existe mas não possui acesso
			return $this->callbackError("EXTERNAL_ACCESS_NOT_ALLOWED", "login");
		}
	}


	function isLoginApp()
	{
		$is_login_app = explode("response_type=", $_SERVER['HTTP_REFERER']);

		if (!empty($is_login_app[1]) == "code") {
			$url_callback = explode("url_callback=", $_SERVER['HTTP_REFERER'])[1];
			header("Location: $url_callback");
			exit();
		} else {
			return false;
		}
	}


	public function Valid2fa()
	{
		$post = $this->input->post();

		$user = $this->User_model->GetUserData($post['email'], $post['password']);
		$validAccess = $this->User_model->valid_access($user['id_user']);

		if ($post['type'] == "twofa-form") {

			$data = $this->User_model->CheckTwofa($post['code'], $user['key_remote_id']);

			if ($data["block"] === TRUE || $validAccess === TRUE) {
				return $this->callbackError2FA("BLOCKED_USER", 0, $post['email']);
			}

			if ($data["expired_code"] === TRUE) {
				return $this->callbackError2FA("LOGIN_EXPIRED_CODE");
			}

			if ($data["2fa"] === false) {

				if ($data["qtde"] === 1) {
					return $this->callbackError2FA("WARNING_BLOCK_USER", 2);
				}

				if ($data["qtde"] === 2) {
					return $this->callbackError2FA("WARNING_BLOCK_USER", 1);
				}
			} else {
				$user['WebSessionToken'] = Token();

				CreateWebSessionToken($user);

				$this->User_model->setUserSession($user);
				return $this->callbackError2FA("LOGIN_SUCCESS");
			}

			if ($data["error"]) {
				return $this->callbackError2FA("LOGIN_UNEXPECTED_ERROR");
			}
		} else if ($post['type'] == "resend-code") {

			$send = $this->User_model->RequestTwofa($user['phone'], $user['email'], $user['key_remote_id'], true);

			if ($send === "LOGIN_EXPIRED_CODE_ATTEMPTS") {
				return $this->callbackError2FA("LOGIN_EXPIRED_CODE_ATTEMPTS");
			}

			if ($send == TRUE) {
				return $this->output
					->set_content_type('application/json')
					->set_status_header(200)
					->set_output(json_encode($data["status"] = 200));
			}
		}
	}


	function Logoff()
	{
		session_destroy();
		return redirect('account/login');
	}


	function ForgotPassword()
	{
		parent::checkLogin();
		$data['type'] = "";
		$this->load->view('pages/account/forgotPassword', $data);
	}


	function ResetPassword()
	{
		$post = $this->input->post();

		if (empty($post["email"])) {
			$this->output->set_status_header(500);
			return $this->callbackError("INVALID_FORGOT_EMAIL", "forgotPassword");
		}

		// Intranet //
		if (isset($post['id']) == true) {
			$post['key_remote_id'] = $this->User_intranet_model->GetKeyRemoteId($post['id']);
		}

		if (isset($post['origin_key_remote_id']) == true && isset($post['key_remote_id']) == true) {

			$user = $this->User_model->GetByToken($post['key_remote_id']);

			$id_company = isset($post['company']) ? $post['company'] : $this->session->userdata('id_company');

			$user_reset_password = $this->User_model->SetPasswordRecovery($post['origin_key_remote_id'], $post['key_remote_id'], $id_company);

			$this->SendEmail($user['email'], $user_reset_password['token'], $user['language']);

			$msg = $this->lang->line("account_alert_email_title") . $user['email'] . $this->lang->line("account_alert_email_two_title");

			return $this->output
				->set_status_header(200)
				->set_output($msg);
		}

		if (strlen($post['email']) > 90) {

			$this->output->set_status_header(500);
			return $this->callbackError("INVALID_CARACTERES_EMAIL", "forgotPassword");
		}

		if (!empty(($post['email']))) {

			if (filter_var(($post['email']), FILTER_VALIDATE_EMAIL)) {

				$user = $this->User_model->GetUserByEmail($post['email']);

				$data['type'] = "success";
				$data['title'] = $this->lang->line("account_alert_forgot_password_success_title");
				$data['subtitle'] = "";
				$data['message'] =  $this->lang->line("account_alert_forgot_password_success_message1") . $post['email'] . $this->lang->line("account_alert_forgot_password_success_message2");
				$data['btn'] = $this->lang->line("account_alert_forgot_password_success_btn");

				if (count($user) > 0) {
					$user_reset_password = $this->User_model->SetPasswordRecovery($user['key_remote_id'], $user['key_remote_id'], $user['id_company']); // ## 

					$this->SendEmail($post['email'], $user_reset_password['token'], $user['language']);
				}

				if (isset($post['no_redirect'])) {
					return $this->output
						->set_content_type('application/json')
						->set_status_header(200)
						->set_output(json_encode($data));
				}
			} else {

				$this->output->set_status_header(500);
				return $this->callbackError("INVALID_FORGOT_EMAIL", "forgotPassword");
			}
			return $this->load->view('pages/account/forgotPassword',  $data);
		} else {

			$this->output->set_status_header(500);
			return $this->callbackError("INVALID_FORGOT_EMAIL", "forgotPassword");
		}
	}


	function ResetPassDefault()
	{
		$post = $this->input->post();

		$key_remote_id = $this->User_intranet_model->GetKeyRemoteId($post['id']);

		if ($key_remote_id != "user_not_exist") {

			$this->User_model->SetPasswordDefault($key_remote_id);

			$msg = $this->lang->line("account_alert_password_changed");

			return $this->output
				->set_status_header(200)
				->set_output($msg);
		} else {
			$msg = "Usuário não foi encontrado!";

			return $this->output
				->set_status_header(500)
				->set_output($msg);
		}
	}


	function ResetPasswordUser()
	{
		$post = $this->input->post();

		// Intranet //
		if (isset($post['id']) == true) {
			$post['key_remote_id'] = $this->User_intranet_model->GetKeyRemoteId($post['id']);
		}

		if (isset($post['origin_key_remote_id']) == true && isset($post['key_remote_id']) == true) {

			$user = $this->User_model->GetByToken($post['key_remote_id']);

			if ($user == null) {
				$msg = "Usuário não foi encontrado!";

				return $this->output
					->set_status_header(500)
					->set_output($msg);
			}

			$id_company = isset($post['company']) ? $post['company'] : $this->session->userdata('id_company');

			$user_reset_password = $this->User_model->SetPasswordRecovery($post['origin_key_remote_id'], $post['key_remote_id'], $id_company);

			$this->SendEmail($user['email'], $user_reset_password['token'], $user['language']);

			$msg = $this->lang->line("account_alert_email_title") . $user['email'] . $this->lang->line("account_alert_email_two_title");

			return $this->output
				->set_status_header(200)
				->set_output($msg);
		} else {
			return $this->output
				->set_status_header(500);
		}
	}


	function NewPassword($token = NULL)
	{
		session_destroy();

		$post = $this->input->post();

		$this->form_validation->set_rules('password', $this->lang->line("account_alert_form_validation_password_changed_password"), 'trim|required|min_length[6]');
		$this->form_validation->set_rules('passconf', $this->lang->line("account_alert_form_validation_password_changed_confirm"), 'trim|required|matches[password]');

		if (!empty($token) && !is_null($token)) {
			$user_reset_password = $this->User_model->GetPasswordRecovery($token);

			if (!is_null($user_reset_password)) {

				$data['main_content'] = 'pages/account/newPassword';
				$data['view_name'] = 'Recuperação de senha';
				$data['js'] = array();
				$data['token'] = $token;
				$data['type'] = "";

				if ($this->form_validation->run() == FALSE) {

					$message = validation_errors();

					if (!empty($message)) {
						$data['type'] = "error";
					}

					$data['title'] =  $this->lang->line("account_alert_new_password_error_title");
					$data['subtitle'] = "";
					$data['message'] = $message;
					$data['btn'] =  $this->lang->line("account_alert_new_password_error_btn");
				} else {
					$data['type'] = "success";
					$data['title'] =  $this->lang->line("account_alert_new_password_success_title");
					$data['subtitle'] = "";
					$data['message'] =  $this->lang->line("account_alert_new_password_success_message");
					$data['btn'] =  $this->lang->line("account_alert_new_password_success_btn");

					$this->User_model->SetNewPassword($user_reset_password['key_remote_id'], $post['password']);
					return	$this->load->view('pages/account/newPassword', $data);
				}
				return $this->load->view('pages/account/newPassword', $data);
			}
		}

		return	$this->load->view('pages/account/errorPassword');
	}


	function SendEmail($email, $token, $language)
	{
		$url =  $this->config->config['base_url'] . "NewPassword/" . $token;

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://services.talkall.com.br:4004/',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => '{
						"to": "' . $email . '",
						"subject": "Talkall",
						"template": "reset_password_' . $language . '",
						"context": {
							"hostname": "https://app.talkall.com.br/",
							"url": "' . $url . '"
						}

					}',
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		$this->output->set_status_header(200);
	}


	function AddTwoFa()
	{
		parent::checkSession();

		$checkPhone = true;

		$data = $this->input->post();
		$data = $this->security->xss_clean($data);

		if ($data["setTwoFa"] == "false") {
			$checkPhone = $this->User_model->checkPhone($data['key_remote_id'], $data['phone']);
		}

		if ($checkPhone) {
			$sendCode = $this->User_model->ConfirmPhone($data['phone'], null, $data['key_remote_id'], $data['resend']);

			if ($sendCode === true) {

				$this->User_model->SetUserPhone($data['phone'], $data['key_remote_id']);

				return $this->output
					->set_content_type('application/json')
					->set_status_header(200)
					->set_output(json_encode(array(
						"type" => "success",
						"{$this->security->get_csrf_token_name()}" => "{$this->security->get_csrf_hash()}"
					)));
			} else if ($sendCode == "LOGIN_EXPIRED_CODE_ATTEMPTS") {

				return $this->output
					->set_content_type('application/json')
					->set_status_header(200)
					->set_output(json_encode(array(
						"type" => "expired_attempts",
						"{$this->security->get_csrf_token_name()}" => "{$this->security->get_csrf_hash()}"
					)));
			} else {

				return $this->output
					->set_content_type('application/json')
					->set_status_header(500)
					->set_output(json_encode(array(
						"type" => "error",
						"mensage" => $this->lang->line("account_alert_request"),
						"{$this->security->get_csrf_token_name()}" => "{$this->security->get_csrf_hash()}"
					)));
			}
		} else {
			return $this->output
				->set_content_type('application/json')
				->set_status_header(200)
				->set_output(json_encode(array(
					"type" => "error",
					"mensage" => "Esse número não está castrado",
					"{$this->security->get_csrf_token_name()}" => "{$this->security->get_csrf_hash()}"
				)));
		}
	}


	// Mobile requests by type ['OPTIONS']
	function SmsVerify($tel)
	{
		$data = $this->User_model->CheckTel($tel);

		$ret = [];
		$ret_status = 200;

		if (isset($data[0]) and $data[0] != '') {
			$ret = [
				"type" => "sucess",
				"id" => $data[0]['key_remote_id'],
				"email" => $data[0]['email'],
			];

			if ($data[0]['sms_code'] == "") {
				$code = mt_rand(100000, 999999);
			} else {
				$code = $data[0]['sms_code'];
			}

			$this->User_model->InsertVerifyCode($code, $ret['id']);

			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => "http://sms-talkall.brazilsouth.cloudapp.azure.com/api/v1/send_verification_code/{$tel}",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => array('msg' => "Talkall, código de verificação: {$code}"),
				CURLOPT_HTTPHEADER => array(
					"access_token: MYJkQ5UN5pzdaBVfKzDbhKUQPBQ0lkiT"
				),
			));

			$response = curl_exec($curl);

			curl_close($curl);
			$ret['server_sms'] = json_decode($response, true);
		} else {
			$ret_status = 500;
			$ret = [
				"type" => "error",
				"message" => "A requisição do sms falhou!",
			];
		}

		return $this->output
			->set_content_type('application/json')
			->set_status_header($ret_status)
			->set_output(json_encode($ret));
	}


	function SmsVerifyConfirm($tel, $sms_code)
	{
		$data = $this->User_model->CheckTel($tel, $sms_code);
		$ret = [];
		$ret_status = 200;

		if (isset($data[0]) and $data[0] != '') {

			$this->User_model->VerifyCode($sms_code, $tel);
			$ret = [
				"type" => "success",
				'message' => 'verified'
			];
		} else {
			$ret_status = 500;
			$ret = [
				"type" => "error",
				"message" => "A requisição do sms falhou!",
			];
		}

		return $this->output
			->set_content_type('application/json')
			->set_status_header($ret_status)
			->set_output(json_encode($ret));
	}


	//ISSO AQUI É PARA ADICONAR 2FA NA CONTA DO USUÁRIO
	function CheckTwoFa()
	{
		parent::checkSession();

		$data = $this->input->post();
		$data = $this->security->xss_clean($data);

		$CheckTwofa = $this->User_model->CheckTwofaAdd($data['code'], $data['key_remote_id']);

		if ($CheckTwofa === TRUE) {

			$status = $this->User_model->SetTwoFa($data['key_remote_id'], $data["setTwoFa"]);

			if ($status == "active") {
				return $this->output
					->set_content_type('application/json')
					->set_status_header(200)
					->set_output(json_encode(array(
						"type" => "active"
					)));
			} else {
				return $this->output
					->set_content_type('application/json')
					->set_status_header(200)
					->set_output(json_encode(array(
						"type" => "inactive"
					)));
			}
		} else {
			return $this->output
				->set_content_type('application/json')
				->set_status_header(500)
				->set_output(json_encode(array(
					"type" => "error",
					"mensage" => "A requisição do sms falhou!"
				)));
		}
	}


	function Security($id)
	{
		parent::checkSession();

		if ($this->session->userdata('id_user') != $id) {
			parent::checkPermission("user");
		}

		if ($this->input->server('REQUEST_METHOD') == 'GET') {

			$data['main_content'] = 'pages/account/security';
			$data['view_name'] = 'Segurança';
			$data['sidenav'] = array('');
			$data['topnav'] = array('search' => false, 'header' => false);
			$data['js'] = array("user.js");
			$data['user'] =  $this->User_model->GetUserById($id);
			$data['login'] =  $this->User_model->GetUserLoginLog($id, 0, 2);

			$this->load->view('template',  $data);
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$post = $this->input->post();
			$post = $this->security->xss_clean($post);

			$data  = array(
				"draw" => (int) $post['draw'],
				"recordsTotal" => $this->User_model->CountAllUserLog($id),
				"recordsFiltered" => $this->User_model->CountAllUserLog($id),
				"data" => $this->User_model->GetAllUserLog($id, (int) $post['start'], (int) $post['length']) ?? ""
			);

			return $this->output
				->set_content_type('application/json')
				->set_status_header(200)
				->set_output(json_encode($data));
		}
	}


	function StrLike($needle, $haystack)
	{
		$regex = '/' . str_replace('%', '.*?', $needle) . '/';

		return preg_match($regex, $haystack) > 0;
	}


	function ChangeLanguage()
	{
		$language = $this->input->post('language');
		$id_user = $this->session->get_userdata()['id_user'];
		$db = $this->session->get_userdata()['db'];

		$this->session->set_userdata('language', $language);

		$this->db->where('id_user', $id_user);
		$this->db->set('language', $language);
		$this->db->update("$db.user");

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode([
				'language' => $language
			]));
	}


	function securityPassword($user)
	{
		$result = $this->User_model->SetPasswordRecovery($user['key_remote_id'], $user['key_remote_id'], $user['id_company']);

		$data["type"] = "";
		$data["token"] = $result["token"];
		$data["message"] = "Redefina sua senha";

		$this->load->view('pages/account/newPassword', $data);
	}

	function WithoutPermission()
	{
		$data['main_content'] = 'pages/account/withoutPermission';
		$data['view_name'] = $this->lang->line("account_without_permission_view_name");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false);
		$data['js'] = array('');

		$this->load->view('template',  $data);
	}

	function trialPeriod()
	{
		$this->load->model('Permission_model', '', TRUE);
		$this->Permission_model->syncCompanyData();

		if (!$this->session->userdata('is_in_trial_period')) {
			$data['main_content'] = 'pages/account/trialPeriod';
			$data['view_name'] = $this->lang->line("account_without_permission_view_name");
			$data['sidenav'] = array('');
			$data['topnav'] = array('search' => false);
			$data['js'] = array('');

			$this->load->view('template',  $data);
		} else {
			return redirect('contact');
		}
	}
}
