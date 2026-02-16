<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class BotTrainerController extends TA_Controller
{
	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("chatbot");

		$this->load->model('BotTrainer_model', '', TRUE);
		$this->lang->load('setting_bot_trainer_lang', $this->session->userdata('language'));
	}

	function Index()
	{
		$data['main_content'] = 'pages/bot/find';
		$data['view_name'] = $this->lang->line("bot_trainer_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("bot.js");
		$data['css'] = array("bot.css");

		$this->load->view('template',  $data);
	}

	function Get()
	{
		$param = $this->input->post();
		$result = $this->BotTrainer_model->Get($param);

		$data  = array(
			"draw" => $param['draw'],
			"recordsTotal" => $result['count'],
			"recordsFiltered" => $result['count'],
			"data" => $result['query']
		);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}

	function GetAccess($key_id)
	{
		$param = $this->input->post();
		$result = $this->BotTrainer_model->GetAccess($key_id, $param);

		$data  = array(
			"draw" => $param['draw'],
			"recordsTotal" => $result['count'],
			"recordsFiltered" => $result['count'],
			"data" => $result['query']
		);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}

	function Save($key_id)
	{
		$data = $this->input->post();
		$id_submenu = $data['id_submenu'] ?? null;

		$this->form_validation->set_rules('input_option', $this->lang->line("setting_bot_trainer_option"), 'trim|required|min_length[1]|max_length[2]');
		$this->form_validation->set_rules('input_content', $this->lang->line("setting_bot_trainer_text"), 'trim|required|min_length[3]|max_length[100]');

		$required = isset($data["chatbot_type"]) && !$id_submenu ? "|required" : "";

		if (isset($data["chatbot_type"])) {
			switch ($data["chatbot_type"]) {
				case "text":
					$this->form_validation->set_rules('input_text', $this->lang->line("setting_bot_trainer_column_description"), "trim$required|min_length[3]|max_length[3000]");
					break;
				case "media":
					$this->form_validation->set_rules('file_hidden', $this->lang->line("setting_bot_trainer_add_choose_type"), "trim$required");

					if (!empty($data["media_type"])) {
						$max_length = ($data["media_type"] == 4) ? 700 : (($data["media_type"] == 3 || $data["media_type"] == 5) ? 1024 : null);
						if ($max_length) {
							$this->form_validation->set_rules('media_description', $this->lang->line("setting_bot_trainer_column_description"), "trim|max_length[$max_length]");
						}
					}
					break;
				case "contact":
					$this->form_validation->set_rules('input_name', $this->lang->line("setting_bot_trainer_contact_name"), "trim$required|min_length[3]|max_length[100]");
					$this->form_validation->set_rules('input_phone', $this->lang->line("setting_bot_trainer_contact_phone_number"), "trim$required|min_length[6]|max_length[15]");
					$this->form_validation->set_rules('input_text_contact', $this->lang->line("setting_bot_trainer_column_description"), "trim|max_length[3000]");
					break;
			}
		}

		if ($this->form_validation->run() == true) {
			if ($key_id > 0) {
				$this->BotTrainer_model->Edit($key_id, $data);
			} else {
				$this->BotTrainer_model->Add($data);
			}

			redirect($id_submenu ? "bot/trainer/{$id_submenu}" : "bot/trainer", 'refresh');
		} else {
			if ($key_id > 0) {
				$this->Edit($key_id);
			} else {
				$id_submenu ? $this->Add($id_submenu) : $this->Add();
			}
		}
	}

	function Add($id = 0)
	{
		$data['submenu'] = $this->BotTrainer_model->GetUserGroupInf();

		if (intval($id) > 0) {
			$info = $this->BotTrainer_model->GetInf($id);
			$data['id'] = $id;
			$data['id_submenu'] = $info[0]['option'];
			$data['id_primary'] = $info[0]['is_primary'];
		}

		$data['main_content'] = 'pages/bot/trainer/add';
		$data['view_name'] = $this->lang->line("bot_trainer_waba_add");
		$data['sidenav'] = array('');
		$data['js'] = array("bot.js");
		$data['css'] = array("bot.css");
		$data['topnav'] = array('search' => false, 'header' => true);

		$this->load->view('template',  $data);
	}

	function Acesso($key_id)
	{
		$info = $this->BotTrainer_model->GetInf($key_id);

		$data['data'] = $info[0];

		$data['id'] = $key_id;
		$data['option'] = $info[0]['option'];
		$data['id_submenu'] = $info[0]['id_submenu'];
		$data['id_primary'] = $info[0]['is_primary'];

		$data['main_content'] = 'pages/bot/find';
		$data['view_name'] = 'bot acesso';
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("bot_access.js");

		$this->load->view('template',  $data);
	}

	function Edit($key_id)
	{
		$data['title'] = "TalkAll | Editar";
		$data['id'] = ($key_id);

		$data['submenu'] = $this->BotTrainer_model->GetUserGroupInf();
		$info = $this->BotTrainer_model->GetInf($key_id);

		$data['data'] = $info[0];

		if ($info[0]['id_submenu'] != '') {
			$data['id_nav'] = $data['data']['id_submenu'];
		}

		if ($info[0]['id_user_group'] != '') {
			$data['data']['id_user_group'];
		}

		$data['main_content'] = 'pages/bot/trainer/edit';
		$data['view_name'] = $this->lang->line("bot_trainer_waba_edit");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("bot.js");
		$data['css'] = array("bot.css");

		$this->load->view('template',  $data);
	}

	function Delete($key_id)
	{
		$this->load->model('BotTrainer_model', '', TRUE);
		$this->BotTrainer_model->Delete($key_id);
	}
}
