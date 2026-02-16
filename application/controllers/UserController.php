<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class UserController extends TA_Controller
{
	public $Permission;

	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("user");

		$this->load->model('WorkTime_model', '', TRUE);
		$this->load->model('UserGroup_model', '', TRUE);
		$this->load->model('UserCall_model', '', TRUE);
		$this->load->model('User_model', '', TRUE);
		$this->lang->load('user_lang', $this->session->userdata('language'));
	}


	function Index()
	{
		$data['department'] = $this->User_model->ListDepartment();
		$data['user'] = $this->User_model->ListUser();

		$data['main_content'] = 'pages/user/find';
		$data['view_name'] = $this->lang->line("user_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("user.js");
		$data['css'] = array("user.css");

		$this->load->view('template',  $data);
	}


	function Get()
	{
		$post = $this->input->post();

		$records = $this->User_model->Count($post);
		$query = $this->User_model->Get($post);

		foreach ($query as &$row) {
			$path = "https://files.talkall.com.br:3000/p/" . $row['key_remote_id'] . ".jpeg";
			$type = pathinfo($path, PATHINFO_EXTENSION);
			$data = file_get_contents($path);
			$row['profile'] = 'data:image/' . $type . ';base64,' . base64_encode($data);
		}

		$data = array(
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


	public function chk_password_expression($email)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return TRUE;
		} else {
			return FALSE;
		}
	}


	function validade_email($email)
	{
		$verify =  $this->User_model->EmailExist($email);
		if (!$verify) {
			$email_valid = $this->chk_password_expression($email);
			if ($email_valid) {
				return true;
			} else {
				$this->form_validation->set_message('validade_email', $this->lang->line("user_validation_email_invalid"));
				return false;
			}
		} else {
			$this->form_validation->set_message('validade_email', $this->lang->line("user_validation_email"));
			return false;
		}
	}

	function validation_email_user()
	{
		$data = $this->input->post();
		$user = $this->User_model->GetUserById($data['user_id']);
		$token = $this->User_model->GetTokenUserValid($user['key_remote_id']);

		$this->User_model->SendEmail([
			'input-email' => $user['email'],
			'sector_language' => $user['language'],
		], "email_confirm_{$user['language']}", base_url("email/confirm/{$token}"));

		$msg = $this->lang->line("user_alert_email_modal_title") . $user['email'] . $this->lang->line("user_alert_email_resend_title");

		return $this->output
			->set_status_header(200)
			->set_output($msg);
	}

	function isEmailRegistered()
	{
		$email = $this->input->post();

		$data =  $this->User_model->EmailExist($email['email']);

		return $this->output
			->set_status_header(200)
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}


	public function valid_sector_language($value)
	{
		$valid_values = ['pt_br', 'en_us', 'es'];

		if (in_array($value, $valid_values)) {
			return true;
		} else {
			$this->form_validation->set_message('valid_sector_language', 'El campo {field} debe ser uno de los siguientes valores: ' . implode(', ', $valid_values));
			return false;
		}
	}



	function Save($key_id)
	{
		parent::checkPermission("user");

		$data = $this->input->post();

		$this->form_validation->set_rules('input-name', $this->lang->line("user_first_name"), 'trim|required|min_length[3]|max_length[30]');
		$this->form_validation->set_rules('input-last-name', $this->lang->line("user_full_name"), 'trim|required|min_length[3]|max_length[100]');
		$this->form_validation->set_rules('user_call', $this->lang->line("user_service_profile"), 'trim|required');
		$this->form_validation->set_rules('user_group', $this->lang->line("user_sector"), 'trim|required');
		$this->form_validation->set_rules('visible_widget', $this->lang->line("user_widget"), 'trim|required');
		// $this->form_validation->set_rules('sector_language', $this->lang->line("user_sector_language"), 'trim|required');
		// $this->form_validation->set_rules('sector_language', $this->lang->line("user_sector_language"), 'trim|required|callback_valid_sector_language');

		if ($key_id == 0) {
			$this->form_validation->set_rules('input-email', "Email", 'trim|required|callback_validade_email|max_length[55]');
		}

		if ($this->form_validation->run() == false) {
			if ($key_id > 0) {
				$this->Edit($key_id);
			} else {
				$this->Add();
			}
		} else {
			if ($key_id > 0) {
				$this->User_model->Edit($key_id, $data);
				redirect("user", 'refresh');
			} else {
				$this->User_model->Add($data);
				$this->session->set_flashdata('alert',  $data['input-email']);
				redirect("user/add", 'refresh');
			}
		}
	}


	function SaveUserCall()
	{
		$data = $this->input->post();
		$this->load->model('UserCall_model', '', TRUE);

		$this->form_validation->set_rules('input-name', $this->lang->line("usercall_nome"), 'trim|required|min_length[3]');
		$this->form_validation->set_rules('input-limit', $this->lang->line("usercall_simultaneous_service_limit"), 'trim|required|min_length[1]');

		if ($this->form_validation->run() == true) {

			$reponse = $this->UserCall_model->Add($data);

			return $this->output
				->set_content_type('application/json')
				->set_status_header(200)
				->set_output(json_encode($reponse));
		}
	}


	function SaveUserSector()
	{
		$data = $this->input->post();
		$this->load->model('UserGroup_model', '', TRUE);

		$this->form_validation->set_rules('input-name', $this->lang->line("user_sector"), 'trim|required|min_length[3]');

		if ($this->form_validation->run() == true) {

			$reponse = $this->UserGroup_model->Add($data);

			return $this->output
				->set_content_type('application/json')
				->set_status_header(200)
				->set_output(json_encode($reponse));
		}
	}


	function Add()
	{
		parent::checkPermission("user");

		$data = $this->input->post();

		$this->Permission = $this->Permission_model->GetAllPermissions();

		$data['UserGroup'] = $this->UserGroup_model->List();
		$data['Permission'] = $this->Permission;
		$data['UserCall'] =  $this->UserCall_model->List();
		$data['main_content'] = 'pages/user/add';
		$data['view_name'] = $this->lang->line("user_waba_add");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("user.js");
		$data['css'] = array("user.css");
		$data['data']['work_time'] =  $this->WorkTime_model->List();

		$this->load->view('template',  $data);
	}


	function Edit($id)
	{
		parent::checkPermission("user");

		$this->Permission = $this->Permission_model->GetAllPermissions();

		$data['data'] =  $this->User_model->GetUserById($id);
		$data['data']['picture'] =  $this->User_model->GetProfilePicture($data['data']['key_remote_id']);
		$data['Permission'] = $this->Permission;
		$data['UserGroup'] = $this->UserGroup_model->List();
		$data['UserCall'] = $this->UserCall_model->List();
		// $data['UserLog'] = $this->UserLog_model->Get($id);
		$data['data']['work_time'] =  $this->WorkTime_model->List();

		$data['main_content'] = 'pages/user/edit';
		$data['view_name'] = $this->lang->line("user_waba_edit");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("user.js");
		$data['css'] = array("user.css");

		$this->load->view('template',  $data);
	}


	function Delete($key_id)
	{
		parent::checkPermission("user");
		$data = $this->User_model->Delete($key_id);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}


	function DeleteUser()
	{
		$data = $this->input->post();
		$this->User_model->DeleteUser($data);
	}
}
