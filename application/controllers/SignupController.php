<?php

class SignupController extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Company_model', '', TRUE);
	}

	function register()
	{

		$data['main_content'] = 'pages/signup/register';
		$data['view_name'] = 'Registro';
		$data['js'] = array("register.js");
		$data['css'] = array("register.css");

		$this->load->view('template',  $data);
	}

	function success()
	{
		$email = $this->input->get('email');

		$data['main_content'] = 'pages/signup/success';
		$data['view_name'] = 'Success';
		$data['js'] = array("success.js");
		$data['css'] = array("success.css");
		$data['email'] = $email;

		$this->load->view('template',  $data);
	}

	function company($token)
	{
		if (!$token) {
			redirect('signup/success/company');
			return;
		}

		$company = $this->Company_model->GetCompanyValidByToken($token);

		if ($company) {
			redirect('signup/success/company');
			return;
		}

		$user = $this->Company_model->GetUserByToken($token);

		if (!$user) {
			redirect('https://app.talkall.com.br');
			return;
		}

		$this->Company_model->ActivateUser($user['id_user']);

		$data['main_content'] = 'pages/signup/company';
		$data['view_name'] = 'Company';
		$data['js'] = array("company.js");
		$data['css'] = array("company.css");

		$this->load->view('template', $data);
	}

	function companySuccess()
	{
		$data['main_content'] = 'pages/signup/successCompany';
		$data['view_name'] = 'Company Success';
		$data['css'] = array("successCompany.css");
		$data['js'] = array("successCompany.js");

		$this->load->view('template', $data);
	}

	function code($token)
	{
		$token = $this->input->get("token");

		$company = $this->Company_model->GetCompanyByToken($token);

		if (!empty($company['phone_responsible1'])) {
			$digits = preg_replace('/\D/', '', $company['phone_responsible1']);

			if (substr($digits, 0, 2) === '55') {
				$digits = substr($digits, 2);
			}

			if (strlen($digits) === 11) {
				$company['phone_responsible1'] = sprintf(
					'(%s) %s-%s',
					substr($digits, 0, 2),
					substr($digits, 2, 5),
					substr($digits, 7)
				);
			} elseif (strlen($digits) === 10) {
				$company['phone_responsible1'] = sprintf(
					'(%s) %s-%s',
					substr($digits, 0, 2),
					substr($digits, 2, 4),
					substr($digits, 6)
				);
			}
		}

		$data['main_content'] = 'pages/signup/code';
		$data['view_name'] = 'Code';
		$data['js'] = array("code.js");
		$data['css'] = array("code.css");
		$data['token'] = $token;
		$data['company'] = $company;

		$this->load->view('template',  $data);
	}

	public function validateCode($token = null)
	{
		$code = trim($this->input->post('code'));

		if (!$code || !$token) {
			echo json_encode(['success' => false, 'message' => 'Código ou token inválido.']);
			return;
		}

		$this->load->model('Company_model');
		$is_valid = $this->Company_model->check_code($code, $token);

		if ($is_valid) {
			echo json_encode(['success' => true]);
		} else {
			echo json_encode(['success' => false, 'message' => 'Código incorreto.']);
		}
	}

	public function updatePhone($token)
	{
		$this->load->model('Company_model');

		$company = $this->Company_model->GetCompanyByToken($token);
		if (!$company) {
			echo json_encode(['success' => false, 'message' => 'Empresa não encontrada.']);
			return;
		}

		$phone = $this->input->post('phone');
		if (empty($phone)) {
			echo json_encode(['success' => false, 'message' => 'Telefone não informado.']);
			return;
		}

		$phone = preg_replace('/\D/', '', $phone);

		if (strlen($phone) < 10 || strlen($phone) > 11) {
			echo json_encode(['success' => false, 'message' => 'Telefone inválido.']);
			return;
		}

		$updated = $this->Company_model->UpdatePhone($company['id_company'], $phone);

		if ($updated) {
			echo json_encode(['success' => true]);
		} else {
			echo json_encode(['success' => false, 'message' => 'Erro ao atualizar o telefone.']);
		}
	}
}
