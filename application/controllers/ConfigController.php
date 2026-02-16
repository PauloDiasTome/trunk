<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class ConfigController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("integration");

		$this->load->model('Config_model', '', TRUE);
		$this->load->model('UserGroup_model', '', TRUE);
		$this->load->model('WorkTime_model', '', TRUE);
		$this->lang->load('config_lang', $this->session->userdata('language'));
	}

	function data_uri($file, $mime)
	{
		$contents = file_get_contents($file);
		$base64   = base64_encode($contents);
		return ('data:' . $mime . ';base64,' . $base64);
	}

	function Save()
	{
		$data = $this->input->post();

		if ($data["channel_id"] == 12) {
			$this->whatsapp12($data);
		} else if ($data["channel_id"] == 16) {
			$this->whatsapp16($data);
		}

		if ($data['type'] == 31) {
			$update_telegram_channel_name = $this->Config_model->updateTelegramChannelName($data);
			$this->session->set_flashdata('response', $update_telegram_channel_name);
			return redirect('integration');
		}

		$resProtocol = "ok";
		$resChatBot = "ok";
		$resAiEvaluation = "ok";
		$resAutomTransferTime = "ok";
		$resContainsBroadcastText = "ok";
		$resNoContainsBroadcastText = "ok";

		// VALIDAÇÃO PARA HABILITAR CHATBOT E PROTOCOLO
		if (array_key_exists("checkbox-on-off-protocol", $data)) {
			if ($data['checkbox-on-off-protocol']  == true) {
				$this->form_validation->set_rules('message_start_attendance', $this->lang->line("config_validation_call_start_text"), 'required|min_length[3]|max_length[1024]');
				$resProtocol = $this->Config_model->ValidationProtocol($data);
				$resProtocol =  $this->form_validation->run() == true ? "ok" : "protocolo";
			}
		}

		if (array_key_exists("checkbox-on-off-chatbot", $data)) {
			if ($data['checkbox-on-off-chatbot'] == true) {
				$this->form_validation->set_rules('welcome_message', $this->lang->line("config_validation_welcome_text"), 'required|min_length[3]|max_length[1024]');
				$resChatBot =  $this->form_validation->run() == true ? "ok" : "chatbot";
				$resChatBot = $this->Config_model->ValidationChatBot($data);
			}
		}

		if (array_key_exists("checkbox-on-off-aiEvaluation", $data)) {
			if ($data['checkbox-on-off-aiEvaluation'] == true) {
				$this->form_validation->set_rules('ai_options', $this->lang->line("config_ai_evaluation_service_selection"), 'required');
				$resAiEvaluation =  $this->form_validation->run() == true ? "ok" : $this->lang->line("config_ai_evaluation_alert_validation_select_option");
			}
		}

		$this->form_validation->set_rules('template_wa_business_contains_broadcast', $this->lang->line("config_validation_template_wa_business_contains_broadcast_text"), 'max_length[200]');
		$resContainsBroadcastText = $this->form_validation->run() == true ? "ok" : $this->lang->line("config_validation_have_current_offers");

		$this->form_validation->set_rules('template_wa_business_no_contains_broadcast', $this->lang->line("config_validation_template_wa_business_no_contains_broadcast_text"), 'max_length[200]');
		$resNoContainsBroadcastText = $this->form_validation->run() == true ? "ok" : $this->lang->line("config_validation_no_have_current_offers");

		if (array_key_exists("checkbox-on-off-automaticTransfer", $data)) {
			if ($data['checkbox-on-off-automaticTransfer'] == true) {
				$this->form_validation->set_rules('automatic_transfer_minute', $this->lang->line("config_validation_automatic_tranfer_time"), 'required|greater_than[1]');
				$resAutomTransferTime =  $this->form_validation->run() == true ? "ok" : "transferência automática";
				$resAutomTransferTime = $this->Config_model->ValidationAutomaticTransferTime($data);
			}
		}

		if ($resProtocol == "ok" && $resChatBot == "ok" && $resAutomTransferTime == "ok" && $resContainsBroadcastText == "ok" && $resNoContainsBroadcastText == "ok") {

			if ($data['type'] == 10) {
				$resp = $this->Config_model->updateAccountTelegram($data);
				$this->session->set_flashdata('response', $resp);
				return redirect('integration');
			}

			$this->session->set_flashdata('response', 'success');
			$this->Config_model->UpadateWaConfig($data, $this->lang->line("config_template_wa_business_contains_broadcast_message_default"), $this->lang->line("config_template_wa_business_no_contains_broadcast_message_default"));
			return redirect('integration');
		} else {
			if ($data["channel_id"] == 2) {
				$this->session->set_flashdata('error', $this->lang->line("config_validation_of_the_channel_default_message_for_business") . ' ' .
					($resContainsBroadcastText != "ok" ? $resContainsBroadcastText : "") . ($resNoContainsBroadcastText != "ok" ? $resNoContainsBroadcastText : ""));
			} else {
				$this->session->set_flashdata('error', $this->lang->line("config_validation_of_the_channel_default_message_other_than_business") . ' ' .
					($resProtocol != "ok" ? $resProtocol . " " : "") . ($resChatBot != "ok" ? $resChatBot : "") . ($resAutomTransferTime != "ok" ? $resAutomTransferTime : "") . ($resAiEvaluation != "ok" ? $resAiEvaluation : ""));
			}

			$this->Edit($data['id_channel']);
		}
	}

	function SaveWorktime()
	{
		$post = $this->input->post();

		$postback = array();

		foreach ($post as $key => $value) {
			if ((bool)strpos($key, 'start')) {
				$week = (int)$key[0];
				$postback[$week] = array("week" => $week, "start" => $value, "end" => "");
			}

			if ((bool)strpos($key, 'end')) {
				$postback[$week]["end"] = $value;
			}
		}

		$id_work_time = $this->WorkTime_model->AddWorkTime($post['name']);

		$this->WorkTime_model->UpdateWorkTime($id_work_time, $postback);

		$response = $this->WorkTime_model->List();
		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($response));
	}

	public function checkAiChannel()
	{
		$data = $this->Config_model->checkAiChannel();

		$this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode(['success' => $data]));
	}

	function Edit($key_id)
	{
		$data['view_name'] = $this->lang->line("config_waba_edit");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("lc_switch.js", "config.js");
		$data['data'] = $this->Config_model->WaChannelConfig($key_id);

		$data['data']['user_group'] = $this->UserGroup_model->List();
		$data['data']['work_time'] = $this->WorkTime_model->List();

		$social = $data["data"][0]['social_media'];

		if (!empty($social)) {
			$social_media = explode(",", $social, 2);
			$data['data']["social_media01"] = isset($social_media[0]) ? $social_media[0] : "";
			$data['data']["social_media02"] = isset($social_media[1]) ? $social_media[1] : "";
		}

		$type = $data['data'][0]['type'];

		switch ($type) {
			case '2':
			case '10':
			case '12':
			case '31':
				$data['main_content'] = 'pages/config/edit';
				break;
			case '8':
			case '9':
				$data['main_content'] = 'pages/config/facebook';
				$data['data']['isInstagram'] = $this->Config_model->isInstagram($data['data'][0]['pw']);
				break;
			case '16':
				$data['main_content'] = 'pages/config/edit';
				break;
			default:
				$data['main_content'] = 'pages/config/edit';
				break;
		};

		$this->load->view('template',  $data);
	}

	function ValidationOptionsChatBot()
	{
		$response = $this->Config_model->CheckIsWelcome();
		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($response));
	}

	public function whatsapp16($data)
	{
		$token = $this->Config_model->getChannelInfo($data["id_channel"]);

		$websites = [
			$data["input-website-social"],
			$data["input-website-social-second"]
		];

		$postFields = [
			"messaging_product" => "whatsapp",
			"address" 		=> $data['input-address'],
			"description" 	=> trim($data['textarea-description']),
			"email"			=> $data['input-email'],
			"websites"		=> $websites,
		];

		if ($data['url_picture'] != "NULL") {

			$size = getimagesize($data['url_picture']);

			$curlData = [
				"CURLOPT_URL" => "https://graph.facebook.com/v13.0/app/uploads?access_token={$token[0]['pw']}&file_length={$size['bits']}&file_type=image/jpeg",
				"CURLOPT_POSTFIELDS" => "",
				"CURLOPT_HTTPHEADER" => array(),
			];

			// Envia a primeira requisição CURL para obter um ID de imagem.
			$imageIdResponse = $this->curlSend($curlData);

			if (isset($imageIdResponse->id)) {
				// Prepara os dados para a segunda requisição CURL para enviar a imagem.
				$curlData = [
					"CURLOPT_URL" => "https://graph.facebook.com/v13.0/" . $imageIdResponse->id,
					"CURLOPT_POSTFIELDS" => $data['url_picture'],
					"CURLOPT_HTTPHEADER" => array(
						'Authorization: OAuth ' . $token[0]['pw'],
						'Content-Type: multipart/form-data'
					),
				];

				// Envia a segunda requisição CURL para enviar a imagem.
				$imageUploadResponse = $this->curlSend($curlData);
				// Atualiza os campos com o identificador da imagem.
				$postFields += [
					"profile_picture_handle" => $imageUploadResponse->h
				];
			}
		}

		// Prepara os dados para a requisição de edição do perfil.
		$curlData = [
			"CURLOPT_URL" => "https://graph.facebook.com/v13.0/{$token[0]['id']}/whatsapp_business_profile",
			"CURLOPT_POSTFIELDS" => $postFields,
			"CURLOPT_HTTPHEADER" => array(
				"Authorization: Bearer " . $token[0]['pw'],
				"Content-Type: application/json"
			),
		];

		// Envia a requisição para a API do Facebook e obtém a resposta.
		$profileEditResponse = $this->curlSend($curlData);

		if (!isset($profileEditResponse->success)) {

			switch ($profileEditResponse->error->message) {
				case "An unknown error occurred":
					// erro na imagem nao subiu a imagem ou nao e uma imagem
					$msg = $profileEditResponse->error->error_user_title . '\n' . $profileEditResponse->error->error_user_msg;
					break;
				case "Invalid OAuth access token - Cannot parse access token":

					$msg = "Token de acesso inválido";
					break;
				default:
					// algum parametro esta errado para inserir na api
					$msg = "Erro ao Inserir dados na API do WhatSapp";
			}

			$apiErrorMsg = [
				"validar" => false,
				"msg" => $msg
			];

			return $apiErrorMsg;
		} else {
			return '';
		}
	}

	function whatsapp12($data)
	{
		$token = $this->Config_model->getChannelInfo($data["id_channel"]);

		if ($data['url_picture'] != "NULL") {

			$curlData = [
				"CURLOPT_URL" => "https://waba.360dialog.io/v1/settings/profile/photo",
				"CURLOPT_POSTFIELDS" => $data['url_picture'],
				"CURLOPT_HTTPHEADER" => array(
					"D360-API-KEY: {$token[0]['pw']}",
					'Content-Type: image/jpg'
				),
			];

			$this->curlSend($curlData);
		}

		$websites = [
			$data["input-website-social"],
			$data["input-website-social-second"]
		];

		if (!empty($data["input-website-social"]) && empty($data["input-website-social-second"])) {
			$websites = '["' . $data["input-website-social"] . '"]';
		}

		if (empty($data["input-website-social"]) && !empty($data["input-website-social-second"])) {
			$websites = '["' . $data["input-website-social-second"] . '"]';
		}

		if (!empty($data["input-website-social"]) && !empty($data["input-website-social-second"])) {
			$websites = '["' . $data["input-website-social"] . '"' . ',' . '"' . $data["input-website-social-second"] . '"]';
		}

		$postFields = [
			"description" 	=> trim($data['textarea-description']),
			"address" 		=> $data['input-address'],
			"email"			=> $data['input-email'],

		];

		if ($websites[0] != "" || $websites[1] != "") {
			$postFields["websites"] = $websites;
		}

		$curlData = [
			"CURLOPT_URL" => "https://waba.360dialog.io/v1/settings/business/profile",
			"CURLOPT_POSTFIELDS" => $postFields,
			"CURLOPT_HTTPHEADER" => array(
				"D360-API-KEY: {$token[0]['pw']}",
				"Content-Type: application/json"
			),
		];

		$this->curlSend($curlData);
	}

	/** 
	 * Envio do Curl
	 * $dados em array
	 * @param	array $dados = [ 
	 *	"CURLOPT_URL" => "URL",
	 *	"CURLOPT_POSTFIELDS" => <filter sera convertido em JSON>,
	 *	"CURLOPT_HTTPHEADER" => array(
	 *			"Authorization: Bearer OU  OAuth para o token,
	 *			"Content-Type: tipo de envio  application/json ou multipart/form-data ou image/jpg"
	 *			),
	 *	]
	 * @return msg da api
	 */

	public function curlSend($data)
	{
		$curl = curl_init();

		if (is_array($data["CURLOPT_POSTFIELDS"])) {
			$data["CURLOPT_POSTFIELDS"] = json_encode($data["CURLOPT_POSTFIELDS"]);
		}

		curl_setopt_array($curl, array(
			CURLOPT_URL => $data["CURLOPT_URL"],
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => strpos($data["CURLOPT_POSTFIELDS"], "files.talkall") > 0 ? file_get_contents($data["CURLOPT_POSTFIELDS"]) : $data["CURLOPT_POSTFIELDS"],
			CURLOPT_HTTPHEADER => $data["CURLOPT_HTTPHEADER"],
		));

		$response = json_decode(curl_exec($curl));

		curl_exec($curl);

		curl_close($curl);

		return $response;
	}
}
