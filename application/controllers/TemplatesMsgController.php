<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class TemplatesMsgController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("templates");

		$this->load->helper('text');
		$this->load->model('TemplatesMsg_model', '', TRUE);
		$this->lang->load('template_lang', $this->session->userdata('language'));
	}


	function Index()
	{
		$data['main_content'] = 'pages/template_msg/find';
		$data['view_name'] = $this->lang->line("template_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("vue.min.js", "template_msg.js");

		$this->load->view('template',  $data);
	}


	function ListTemplete()
	{
		$this->load->model('TemplatesMsg_model', '', TRUE);
		$data = $this->TemplatesMsg_model->List(2);
		echo json_encode($data);
	}


	function GetTemplate()
	{
		$post = $this->input->post();

		$records = $this->TemplatesMsg_model->Count($post['text']  ?? "");
		$query = $this->TemplatesMsg_model->GetTemplate($post['text'] ?? "", $post['start'], $post['length'], $post['order'][0]['column'], $post['order'][0]['dir']);

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


	function GetData()
	{
		$this->load->model('TemplatesMsg_model', '', TRUE);
		$info = $this->TemplatesMsg_model->GetData();

		echo json_encode($info);
	}


	function GetDataCloud()
	{
		$this->load->model('TemplatesMsg_model', '', TRUE);
		$info = $this->TemplatesMsg_model->GetDataCloud();

		echo json_encode($info);
	}


	function Save($data)
	{
		// Transformando json em array
		$template_json = $data['data'];
		$data['data'] = json_decode($data['data']);
		$data['contentToSave'] = json_decode($data['contentToSave']);
		$data['response'] = json_decode($data['response']);

		$data['data'] = json_decode(json_encode($data['data']), true);
		$data['contentToSave'] = json_decode(json_encode($data['contentToSave']), true);
		$data['response'] = json_decode(json_encode($data['response']), true);

		$dataToSave = [];
		$channel_type = $data['contentToSave']['channel_type'];

		// Monta o Array para gravar no banco
		$dataToSave['name_to_request'] = $data['data']['name'];
		$dataToSave['name'] = $data['contentToSave']['name'];
		$dataToSave['language'] = $data['data']['language'];
		$dataToSave['category'] = $data['data']['category'];
		$dataToSave['namespace'] = $channel_type == 12 ? $data['response']['namespace'] : null;
		$dataToSave['rejected_reason'] =  $channel_type == 12 ?  $data['response']['rejected_reason'] : null;
		$dataToSave['status'] = 1;
		$dataToSave['account_key_remote_id'] = $data['contentToSave']['channel_id'];
		$dataToSave['template_id'] = $channel_type == 16 ? $data['response']['id'] : null;
		$dataToSave['template_json'] = $template_json;

		$dataToSave['header'] = null;
		$dataToSave['header_type'] = null;
		$dataToSave['text_footer'] = null;
		$dataToSave['buttons'] = null;

		for ($i = 0; $i < count($data["data"]['components']); $i++) {

			switch ($data['data']['components'][$i]['type']) {

				case 'BODY':
					$dataToSave['text_body'] = $data['data']['components'][$i]['text'];
					break;
				case 'HEADER':

					switch ($data['data']['components'][$i]['format']) {
						case 'TEXT':
							$dataToSave['header'] = $data['data']['components'][$i]['text'];
							$dataToSave['header_type'] = 1;
							break;
						case 'IMAGE':
							$dataToSave['header'] = null;
							$dataToSave['header_type'] = 3;
							break;
						case 'VIDEO':
							$dataToSave['header'] = null;
							$dataToSave['header_type'] = 5;
							break;
						case 'DOCUMENT':
							$dataToSave['header'] = null;
							$dataToSave['header_type'] = 10;
							break;
					}

					break;
				case 'FOOTER':
					$dataToSave['text_footer'] = $data['data']['components'][$i]['text'];
					break;
				case 'BUTTONS':
					$dataToSave['buttons'] = json_encode($data['data']['components'][$i]['buttons']);
					break;
				default:
					break;
			}
		}

		return $this->TemplatesMsg_model->Add($dataToSave);
	}


	function submitTemplateWaba()
	{
		$post = $this->input->post();

		$dataBody = json_decode($post['contentToSave']);

		$token = $this->TemplatesMsg_model->GetToken(12, $dataBody->channel_id);
		$token_360 = $token[0]['pw'];
		$newPostData =  str_replace('1&&&', '%', $post['data']);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://waba.360dialog.io/v1/configs/templates',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $newPostData,
			CURLOPT_HTTPHEADER => array(
				"D360-API-KEY: {$token_360}",
				"Content-Type: application/json"
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);

		$data['data'] = $newPostData;
		$data['contentToSave'] = $post['contentToSave'];
		$data['response'] = $response;

		$response_obj = json_decode($response);

		if (!isset($response_obj->meta)) {

			$ret = $this->save($data);

			if ($ret == true) {

				return $this->output
					->set_content_type('application/json')
					->set_status_header(200)
					->set_output(json_encode($ret));
			} else {

				return $this->output
					->set_content_type('application/json')
					->set_status_header(200)
					->set_output(json_encode($response_obj));
			}
		} else {

			return $this->output
				->set_content_type('application/json')
				->set_status_header(400)
				->set_output(json_encode($response_obj));
		}
	}


	function submitTemplateCloud()
	{
		$post = $this->input->post();

		$dataBody = json_decode($post['contentToSave']);

		$token = $this->TemplatesMsg_model->GetToken(16, $dataBody->channel_id);

		$pw = $token[0]['pw'];
		$whatsapp_business_messaging = $token[0]['whatsapp_business_messaging'];
		$newPostData =  str_replace('1&&&', '%', $post['data']);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://graph.facebook.com/v16.0/{$whatsapp_business_messaging}/message_templates?access_token={$pw}",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $newPostData,
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json',
				'Cookie: datr=sF96Yke2LIh-vYiprkzzvA-u; sb=sV96YqXmInzsNVB_0v0eUwsa'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);

		$data['data'] = $newPostData;
		$data['contentToSave'] = $post['contentToSave'];
		$data['response'] = $response;

		$response_obj = json_decode($response);

		if (!isset($response_obj->meta) && !isset($response_obj->error)) {

			$ret = $this->save($data);

			if ($ret == true) {

				return $this->output
					->set_content_type('application/json')
					->set_status_header(200)
					->set_output(json_encode($ret));
			} else {

				return $this->output
					->set_content_type('application/json')
					->set_status_header(200)
					->set_output(json_encode($response_obj));
			}
		} else if (isset($response_obj->error)) {

			return $this->output
				->set_content_type('application/json')
				->set_status_header(200)
				->set_output(json_encode($response_obj));
		} else {

			return $this->output
				->set_content_type('application/json')
				->set_status_header(400)
				->set_output(json_encode($response_obj));
		}
	}


	function getChannelIdByType($channel_type)
	{
		return $this->TemplatesMsg_model->getChannelIdByType($channel_type);
	}


	function ReplaceSimbolsAndSpaceTitle($data)
	{
		$data = str_replace(["(", ")", "~", "^", "*", "#", "º", "ª", "´", "`", "¹", "²", "³", "%", "+", "§", "$", "¨", "£", "¢", "¬", "!", "|", "{", "}", "[", "]", "/", "\\", "<", ">", "&lt;"], "", $data);
		$data = str_replace([" ", ":", ";", "=", "-"], "_", $data);
		$data = str_replace(["Ç", "ç"], "c", $data);
		$data = str_replace("&", "e", $data);
		$data = convert_accented_characters($data);
		$data = strtolower($data);

		return $data;
	}


	function ReplaceSimbolsAndSpaceCategory($data)
	{
		$data = strtoupper($data);
		$data = str_replace([" "], "_", $data);
		return $data;
	}


	function ReplaceBreakLineBody($data)
	{
		$data = trim(preg_replace('/\s\s+/', ' \\n ', $data));
		$data = str_replace('"', "'", $data);

		return $data;
	}

	function ReplaceQuotesFooter($data)
	{
		$data = str_replace(['"', "'"], '', $data);

		return $data;
	}


	function Add($recover = "null")
	{

		$data['channels'] = $this->TemplatesMsg_model->getChannels();
		$categories = $this->TemplatesMsg_model->getTemplateTypes();

		$data['categories']  = [];

		foreach ($categories as $idx => $elm) {

			$temp = [];

			$id = str_replace(" ", "_", $elm['en_us']);

			array_push($temp, $id);
			array_push($temp, $elm['en_us']);
			array_push($temp, $elm['pt_br']);
			array_push($data['categories'], $temp);
		}

		$data['main_content'] = 'pages/template_msg/add';
		$data['view_name'] = $this->lang->line("template_waba_add");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("template_msg.js");
		$data['css'] = array("templateMsg.css");

		if ($recover != "null") {
			$data['apiError'] = $recover['apiError'];

			if (isset($recover['repeated_name'])) {
				$data['repeated_name'] = $recover['repeated_name'];
			}

			$data['name'] = $recover['name'];
			$data['lang'] = $recover['lang'];
			$data['category'] = $recover['category'];
			$data['text_body'] = $recover['text_body'];
			$data['text_footer'] = $recover['text_footer'];
		}

		$this->load->view('template',  $data);
	}


	function View($id)
	{
		$data['main_content'] = 'pages/template_msg/view';
		$data['view_name'] = $this->lang->line("template_waba_view");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("vue.min.js", "template_msg.js");
		$data['css'] = array("templateMsg.css");

		$data['id'] = ($id);

		$this->load->model('TemplatesMsg_model', '', TRUE);
		$info = $this->TemplatesMsg_model->GetInf($id);

		if ($info[0]['id_template'] != $id) {
			header('Location: ../../templates');
		}

		$category = str_replace('_', ' ', $info[0]['category']);

		if ($info[0]['language'] == 'pt_BR') {
			$lang = $this->TemplatesMsg_model->GetData();
			foreach ($lang as $key => $value) {

				if ($value['category'] == $category) {
					$category_pt_br = $value['pt_BR'];
				}
			}
			$data['category'] = $category_pt_br;
		} else {

			$data['category'] = $category;
		}

		switch ($info[0]['status']) {
			case 1:
				$status = "Em análise";
				break;
			case 2:
				$status = "Aprovado";
				break;
			case 3:
				$status = "Rejeitado";
				break;
		}

		$data['id'] = $info[0]['id_template'];
		$data['name'] = $info[0]['name'];
		$data['header_type'] = $info[0]['header_type'];
		$data['header'] = $info[0]['header'];
		$data['channel'] = $info[0]['channel_name'];
		$data['text_body'] = $info[0]['text_body'];
		$data['text_footer'] = $info[0]['text_footer'];
		$data['buttons'] = json_decode($info[0]['buttons']);
		$data['language'] = $info[0]['language'];
		$data['creation'] = $info[0]['creation'];
		$data['status'] = $status;
		if ($info[0]['status'] == 3) {

			$data['rejected_reason'] = $info[0]['rejected_reason'];
		}

		$this->load->view('template',  $data);
	}


	function Edit($id)
	{
		$data['main_content'] = 'pages/template_msg/edit';
		$data['view_name'] = $this->lang->line("template_waba_edit");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("vue.min.js", "template_msg.js");
		$data['css'] = array("templateMsg.css");

		$data['id'] = ($id);

		$this->load->model('TemplatesMsg_model', '', TRUE);
		$info = $this->TemplatesMsg_model->GetInf($id);

		if ($info[0]['id_template'] != $id) {
			header('Location: ../../templates');
		}

		$data['id'] = $info[0]['id_template'];
		$data['name'] = $info[0]['name'];
		$data['channel_name'] = $info[0]['channel_name'];
		$data['channel_type'] = $info[0]['channel_type'];

		switch ($info[0]['header_type']) {

			case '0':
				$data['header_type_option_value'] = '';
				$data['header_type'] = null;
				$data['header_option_text'] =  $this->lang->line("template_select_option_none");
				break;
			case '1':
				$data['header_type_option_value'] = 'option_header_text';
				$data['header_type'] = 1;
				$data['header_option_text'] =  $this->lang->line("template_header_option_text");
				break;
			case '3':
				$data['header_type_option_value'] = 'option_header_media';
				$data['header_type'] = 3;
				$data['header_option_text'] =  $this->lang->line("template_header_option_media");
				break;
			case '5':
				$data['header_type_option_value'] = 'option_header_media';
				$data['header_type'] = 5;
				$data['header_option_text'] =  $this->lang->line("template_header_option_media");
				break;
			case '10':
				$data['header_type_option_value'] = 'option_header_media';
				$data['header_type'] = 10;
				$data['header_option_text'] =  $this->lang->line("template_header_option_media");
				break;
		}

		$data['buttons'] = json_decode($info[0]['buttons']);

		$data['header'] = $info[0]['header'];
		$data['text_body'] = $info[0]['text_body'];
		$data['category'] = $info[0]['category'];
		$data['text_footer'] = $info[0]['text_footer'];
		$data['status'] = $info[0]['status'];

		$this->load->view('template',  $data);
	}


	function updateName()
	{
		$post = $this->input->post();

		$this->form_validation->set_rules('name', $this->lang->line("template_name"), 'trim|required|min_length[2]');

		if ($this->form_validation->run() == true) {

			$data['name'] = $post['name'];
			$data['id_template'] = $post['id_template'];

			$this->TemplatesMsg_model->updateName($data);

			redirect("templates", 'refresh');
		}
	}


	function RevertBreakLine($data)
	{
		$data = trim(str_replace(' \\n ', ' ."<br>". ', $data));
		return $data;
	}


	function UpdateStatus()
	{
		$post = $this->input->post();

		foreach ($post as $name => $status) {
			echo "The template <b>" . $name . "</b> has status <b>" . $status . "</b><br/>";
			$this->TemplatesMsg_model->UpdateStatus($name, $status);
		}
	}


	function Delete($key)
	{
		$info = $this->TemplatesMsg_model->GetInfoTemplate($key);

		if ($info[0]['template_id'] == null) {

			$ret = $this->deleteTemplateWaba($info);
		} else {

			$ret = $this->deleteTemplateCloud($info);
		}
	}


	function deleteTemplateWaba($info)
	{
		$token = $this->TemplatesMsg_model->GetToken(12, $info[0]['account_key_remote_id']);

		$data['key_d360'] = $token[0]['pw'];

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://waba.360dialog.io/v1/configs/templates/{$info[0]['name_to_request']}",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "DELETE",
			CURLOPT_HTTPHEADER => array(
				"D360-API-KEY: {$data['key_d360']}"
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		$res = json_decode($response, true);

		if ($res['meta']['success'] == true || $res['meta']['developer_message'] = 'Object not found') {

			$data['status'] = 4;
			$data['name_to_request'] = $info[0]['name_to_request'];

			$ret = $this->TemplatesMsg_model->ChangeStatus($data, $info[0]['id_template']);

			if ($ret == true) {
				header('Content-Type: application/json');
				echo json_encode(['msg' => $res['meta']['developer_message']]);
			}
		} else {
			die;
		}
	}


	function deleteTemplateCloud($info)
	{
		$token = $this->TemplatesMsg_model->GetToken(16, $info[0]['account_key_remote_id']);

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => "https://graph.facebook.com/v16.0/{$token[0]['whatsapp_business_messaging']}/message_templates?access_token={$token[0]['pw']}",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'DELETE',
			CURLOPT_POSTFIELDS => '{
    			"name": "' . "{$info[0]['name_to_request']}" . '" 
			}',
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json',
				'Cookie: datr=sF96Yke2LIh-vYiprkzzvA-u; sb=sV96YqXmInzsNVB_0v0eUwsa'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);

		$res = json_decode($response, true);

		if ($res['success'] == true || $res['error']['error_user_title'] == 'Message Template Not Found') {

			$data['status'] = 4;
			$data['name_to_request'] = $info[0]['name_to_request'];

			$ret = $this->TemplatesMsg_model->ChangeStatus($data, $info[0]['id_template']);

			if ($ret == true) {
				header('Content-Type: application/json');
				echo json_encode(['msg' => $res['success']]);
			}
		} else {
			die;
		}
	}
}
