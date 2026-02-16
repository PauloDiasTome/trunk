<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class QuickRepliesController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("replies");

		$this->load->model('QuickReplies_model', '', TRUE);
		$this->lang->load('replies_lang', $this->session->userdata('language'));
	}


	function Index()
	{
		$data['main_content'] = 'pages/replies/find';
		$data['view_name'] = $this->lang->line("replies_topnav");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("replies.js");

		$this->load->view('template',  $data);
	}


	function Get()
	{
		$post = $this->input->post();

		$records = $this->QuickReplies_model->Count($post);
		$query = $this->QuickReplies_model->Get($post);

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


	function Save($key_id)
	{
		$data = $this->input->post();

		$this->form_validation->set_rules('input-title', $this->lang->line("replies_title"), 'trim|required|min_length[3]|max_length[100]');
		$this->form_validation->set_rules('input-tag', $this->lang->line("replies_tag"), 'trim|required|min_length[2]|max_length[25]');
		if (!$data['type_reply'] == "text") {
			$this->form_validation->set_rules('input-content', $this->lang->line("replies_text"), 'trim|required|min_length[3]|max_length[4096]');
		} else if ($data['type_reply'] == "media") {
			$this->form_validation->set_rules('file', $this->lang->line("replies_media"), 'trim|required');
		}

		if ($this->form_validation->run() == true) {

			if (isset($data['file'])) {
				$nameFile = explode("https://files.talkall.com.br:3000", $data['file'])[1];
				$type = explode(".", $nameFile)[1];

				switch ($type) {
					case "jpg":
					case "jpeg":
					case "jfif":
						$data['media_type'] = 3;
						break;
					case "ogg":
						$data['media_type'] = 2;
						break;
					case "pdf":
						$data['media_type'] = 4;
						break;
					case "mp4":
						$data['media_type'] = 5;
						break;
				}
			}

			if ($key_id > 0) {
				$this->QuickReplies_model->Edit($key_id, $data);
			} else {
				$this->QuickReplies_model->Add($data);
			}

			redirect("replies", 'refresh');
		} else {
			if ($key_id > 0) {
				$this->Edit($key_id);
			} else {
				$this->Add();
			}
		}
	}


	function Add()
	{
		$data = $this->input->post();

		$data['main_content'] = 'pages/replies/add';
		$data['view_name'] = $this->lang->line("replies_waba_add");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['css'] = array("replies.css");
		$data['js'] = array("replies.js");
		$data['data'] = $data;

		$this->load->view('template',  $data);
	}


	function Edit($key_id)
	{
		$data['title'] = "TalkAll | Editar";
		$data['id'] = ($key_id);

		$info = $this->QuickReplies_model->GetInf($key_id);
		$data['data'] = $info[0];

		$data['main_content'] = 'pages/replies/edit';
		$data['view_name'] = $this->lang->line("replies_waba_edit");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['css'] = array("replies.css");
		$data['js'] = array("replies.js");

		$this->load->view('template',  $data);
	}


	function Delete($key_id)
	{
		$this->load->model('QuickReplies_model', '', TRUE);
		$this->QuickReplies_model->Delete($key_id);
	}
}
