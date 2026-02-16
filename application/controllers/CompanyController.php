<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class CompanyController extends TA_Controller
{

	function __construct()
	{
		parent::__construct();

		$this->load->helper('whatsapp_preview');
		$this->load->model('Company_model', '', TRUE);
	}

	function Index()
	{
		if ($this->input->server('REQUEST_METHOD') == 'GET') {

			$data['main_content'] = 'intranet/company/find';
			$data['view_name'] = 'Company';
			$data['sidenav'] = array('');
			$data['topnav'] = array('search' => true);
			$data['js'] = array("intranet/company.js");

			$this->load->view('template',  $data);
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$post = $this->input->post();

			$records = $this->Company_model->Count($post['text']  ?? "");
			$query = $this->Company_model->Get($post['text']  ?? "", $post['start'], $post['length']);

			$data  = array(
				"draw" => $post['draw'],
				"recordsTotal" => $records[0]['count'],
				"recordsFiltered" => $records[0]['count'],
				"data" => $query
			);

			return $this->output
				->set_content_type('application/json')
				->set_status_header(200)
				->set_output(json_encode($data));
		}
	}

	function chk_password_expression($email)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function validade_email($email)
	{
		$this->load->model('User_model', '', TRUE);
		$verify =  $this->User_model->EmailExist($email);

		if (!$verify) {

			$email_valid = $this->chk_password_expression($email);
			if ($email_valid) {
				return true;
			} else {
				$this->form_validation->set_message('validade_email', "O campo Email é inválido.");
				return false;
			}
		} else {
			$this->form_validation->set_message('validade_email', "O email já está cadastrado no TalkAll.");
			return false;
		}
	}

	function register()
	{
		$data = $this->input->post();

		$this->form_validation->set_rules('email', "Email", 'trim|required|callback_validade_email|max_length[55]');
		$this->form_validation->set_rules('password', "Senha", 'trim|required|min_length[6]');

		if ($this->form_validation->run()) {

			$result = $this->Company_model->Register($data);

			if (isset($result['errors'])) {
				return $this->output
					->set_content_type('application/json')
					->set_status_header(200)
					->set_output(json_encode(["errors" => ["code" => $result['errors']["code"]]]));
			}

			$url = base_url("signup/company/{$result['token']}");

			$this->Company_model->SendEmail([
				'input-email' => strtolower($data['email']),
				'sector_language' => 'pt-br'
			], 'email_complete_company_pt_br', $url);

			return $this->output
				->set_content_type('application/json')
				->set_status_header(200)
				->set_output(json_encode([
					"success" => ["status" => true]
				]));
		} else {

			return $this->output
				->set_content_type('application/json')
				->set_status_header(400)
				->set_output(json_encode([]));
		}
	}

	function add()
	{
		$data = $this->input->post(NULL, TRUE);

		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('token', "Token", 'trim|required');
		$this->form_validation->set_rules('name', "Nome", 'trim|required|max_length[100]');
		$this->form_validation->set_rules('company', "Empresa", 'trim|required|max_length[100]');
		$this->form_validation->set_rules('phone', "Telefone", 'trim|required|max_length[20]');

		if ($this->form_validation->run()) {

			$user = $this->Company_model->GetUserByToken($data['token']);

			if (!$user) {
				return $this->output
					->set_content_type('application/json')
					->set_status_header(400)
					->set_output(json_encode([
						'status' => 'error',
						'message' => 'Token inválido ou expirado.'
					]));
			}

			$data['id_company'] = $user['id_company'];

			$result = $this->Company_model->Add($data);

			if (isset($result['status']) && $result['status'] === 'error') {
				return $this->output
					->set_content_type('application/json')
					->set_status_header(400)
					->set_output(json_encode([
						'status' => 'error',
						'message' => $result['message'] ?? 'Falha ao registrar os dados.'
					]));
			}

			$response = $this->sendTemplateCode($data['token']);

			if (!$response['status']) {
				return $this->output
					->set_content_type('application/json')
					->set_status_header(400)
					->set_output(json_encode([
						'status' => 'error',
						'message' => 'Falha ao enviar o código pelo WhatsApp.'
					]));
			}

			return $this->output
				->set_content_type('application/json')
				->set_status_header(200)
				->set_output(json_encode(['status' => 'success']));
		} else {

			$errors = [];
			foreach (['name', 'company', 'phone', 'token'] as $field) {
				if (form_error($field)) {
					$errors[$field] = strip_tags(form_error($field));
				}
			}

			return $this->output
				->set_content_type('application/json')
				->set_status_header(400)
				->set_output(json_encode([
					'status' => 'error',
					'message' => !empty($errors) ? reset($errors) : 'Verifique os campos obrigatórios.',
					'errors' => $errors
				]));
		}
	}

	function company($token)
	{
		$data['token'] = $token;

		$this->load->view('pages/signup/confirm.php', $data);
	}

	function post()
	{
		$data = $this->input->post();

		$result = $this->Company_model->Edit($data);
		if ($result == true) {
		}
	}

	function confirm($token)
	{
		$result = $this->Company_model->confirm($token);
		if ($result == true) {
			redirect("signup/company/" . $token, 'refresh');
		}
	}

	function Edit($id_company = null)
	{
		$data['main_content'] = 'intranet/company/edit';
		$data['view_name'] = 'Editar ' . lang('company');
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("intranet/company.js");

		if ($this->input->server('REQUEST_METHOD') == 'GET') {

			if ($id_company != null) {
				$data['data'] = $this->Company_model->GetById($id_company);
			}

			$this->load->view('template',  $data);
		}

		if ($this->input->server('REQUEST_METHOD') == 'POST') {

			$post = $this->input->post();

			$this->form_validation->set_rules('input-corporate-name', 'Nome', 'required');
			$this->form_validation->set_rules('input-fantasy-name', 'Nome Fantasia', 'required');
			$this->form_validation->set_rules('input-cnpj', 'CNPJ', 'required');
			$this->form_validation->set_rules('input-db', 'DB', 'required');

			$this->load->library('form_validation');

			if ($this->form_validation->run() == FALSE) {

				if ($id_company != null) {
					$data['data'] = $this->Company_model->GetById($id_company);
				} else {
					$data['data']['company'] = $post;
				}

				$this->load->view('template',  $data);
			} else {

				if ($id_company != null) {
					$this->Company_model->UpdateCompany($id_company, $post);
				} else {
					$this->Company_model->AddCompany($post);
				}

				return redirect('company');
			}
		}
	}

	function sendTemplateCode($data)
	{
		$date = new DateTime();
		$timestamp = $date->getTimestamp();

		$info = $this->Company_model->GetCompanyByToken($data);

		if (empty($info['phone_responsible1'])) {
			return ['status' => false, 'error' => 'Telefone não encontrado'];
		}

		$to = $info['phone_responsible1'] . '-332128976653772';

		$this->load->helper('string');
		$token = strtoupper(random_string('alnum', 32));

		$msg = new stdClass();
		$msg->Cmd = "TemplateMessage";
		$msg->event = "PreviewMessage";
		$msg->key_id = $token;
		$msg->ta_key_id = $token;
		$msg->to = $to;
		$msg->fromMe = true;
		$msg->timestamp = $timestamp;
		$msg->namespace = "whatsapp_namespace_exemplo";
		$msg->policy = "deterministic";
		$msg->language = "pt_BR";
		$msg->category = "marketing";
		$msg->name = "verificar_telefone_cadastrado";
		$msg->text_body = "Código de verificação: 123456";
		$msg->text_footer = "";
		$msg->buttons = [];
		$msg->header = null;

		$msg->component = [
			[
				"type" => "body",
				"parameters" => [
					["type" => "text", "text" => $info['code']]
				]
			],
			[
				"type" => "button",
				"sub_type" => "url",
				"index" => "0",
				"parameters" => [
					["type" => "text", "text" => $info['code']]
				]
			]
		];

		$data = [
			'id_channel' => '332128976653772',
			'json' => json_encode($msg)
		];

		return sendTemplateCode($data);
	}

	function sendSmsCode($data)
	{
		$info = $this->Company_model->GetCompanyByToken($data);

		if (empty($info['phone_responsible1'])) {
			return ['status' => false, 'error' => 'Telefone não encontrado'];
		}

		$code = $info['code'];

		$msg = "Seu código Talkall é {$code}";

		$this->load->model('Sms_model', '', TRUE);

		$this->Sms_model->SendSms($info['id_company'], $info['phone_responsible1'], $msg);

		return [
			'status' => true
		];
	}
}
