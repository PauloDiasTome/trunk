<?php

defined('BASEPATH') or exit('No direct script access allowed');

class CategoryController extends TA_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Category_model');
		$this->load->model('UserGroup_model');
		$this->lang->load('category_lang', $this->session->userdata('language'));
	}

	public function Index()
	{
		$data = [
			'main_content' => 'pages/category/find',
			'view_name' => $this->lang->line("category_waba_header"),
			'sidenav' => [''],
			'topnav' => ['search' => true],
			'css' => ['category.css'],
			'js' => ['category.js']
		];

		$this->load->view('template', $data);
	}

	public function get()
	{
		$postData = $this->input->post();

		// Verificação mínima para evitar erros
		if (!isset($postData['draw'])) {
			return $this->output
				->set_content_type('application/json')
				->set_status_header(400)
				->set_output(json_encode(['error' => 'Parâmetro "draw" ausente.']));
		}

		// Busca os dados e total
		$result = $this->Category_model->get($postData);

		$response = [
			'draw' => (int) $postData['draw'],
			'recordsTotal' => (int) $result['count'],     
			'recordsFiltered' => (int) $result['count'],  
			'data' => $result['query']
		];


		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($response));
	}

	public function Save($key_id)
	{
		$data = $this->input->post();

		$this->form_validation->set_rules(
			'name',
			$this->lang->line("category_nome"),
			'trim|required|min_length[3]|max_length[30]'
		);

		if ($this->form_validation->run()) {
			if ($key_id > 0) {
				$this->Category_model->Edit($key_id, $data);
			} else {
				$this->Category_model->Add($data);
			}
			redirect("category", 'refresh');
		} else {
			if ($key_id > 0) {
				$this->edit($key_id);
			} else {
				$this->add();
			}
		}
	}

	public function Add()
	{
		$data = [
			'main_content' => 'pages/category/add',
			'view_name'    => $this->lang->line("category_waba_add"),
			'sidenav'      => [''],
			'topnav'       => ['search' => false, 'header' => true],
			'css'          => [
				"select" => "msFmultiSelect.css"
			],
			'js'           => ['category.js'],
			'userGroups'   => $this->UserGroup_model->List()
		];

		$this->load->view('template', $data);
	}

	public function Edit($key_id)
	{
		$info = $this->Category_model->GetInf($key_id);

		$data = [
			'id' => $key_id,
			'category' => $info[0], // nome ajustado para bater com a view
			'userGroups' => $this->UserGroup_model->List(), // nome ajustado
			'selectedGroups' => $this->Category_model->GetUserGroups($key_id), // nome ajustado
			'main_content' => 'pages/category/edit',
			'view_name' => $this->lang->line("category_waba_edit"),
			'sidenav' => [''],
			'topnav' => ['search' => false, 'header' => true],
			'css'          => [
				"select" => "msFmultiSelect.css"
			],
			'js' => ['category.js']
		];

		$this->load->view('template', $data);
	}

	public function Delete($key_id)
	{
		$result = $this->Category_model->Delete($key_id);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($result));
	}
}
