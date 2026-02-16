<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class PermissionController extends TA_Controller
{
	function __construct()
	{
		parent::__construct();
		parent::checkPermission("permission");
	}

	function Index()
	{
		$data['main_content'] = 'pages/permission/find';
		$data['view_name'] = $this->lang->line("permission_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("permission.js");

		$this->load->view('template',  $data);
	}

	function Get()
	{
		$param = $this->input->post();
		$result = $this->Permission_model->Get($param);

		$data  = array(
			"draw" => $param['draw'],
			"recordsTotal" => $result['count'],
			"recordsFiltered" => $result['count'],
			"data" => $result["query"]
		);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}

	function Save($key_id = 0)
	{
		$post = $this->input->post();

		$this->form_validation->set_rules('name', $this->lang->line("permission_name"), 'trim|required|min_length[3]|max_length[100]');

		if ($this->form_validation->run() === true) {

			$date = new DateTime();

			$data['creation'] = $date->getTimestamp();

			if ($key_id == 0) {

				$id_adm = $this->Permission_model->GetAdmIdPermission();

				$data = $this->Permission_model->GetById($id_adm[0]['id_permission']);

				foreach ($data as $key => &$value) {
					if ($key != 'id_permission' && $key != 'creation' && $key != 'adm' && $key != 'name' && $key != 'ip_list') {
						if (!isset($post[$key])) {
							$value = '2';
						} else {
							$value = '1';
						}
					}
				}

				unset($data['id_permission']);

				$data['adm'] = '2';
				$data['ip_list'] = $post['ip_list'] === "" ? null : $post['ip_list'];
				$data['name'] = trim($post['name']);

				$this->Permission_model->CreatePermission($data);
				return redirect('permission');
			} else {

				$data = $this->Permission_model->GetById($key_id);

				foreach ($data as $key => &$value) {
					if (($key != 'id_permission') && ($key != 'creation') && ($key != 'adm') && ($key != 'name') && ($key != 'ip_list')) {
						if (!isset($post[$key])) {
							$value = '2';
						} else {
							$value = '1';
						}
					} else {
						continue;
					}
				}

				$data['ip_list'] = $post['ip_list'] === "" ? null : $post['ip_list'];
				$data['name'] = trim($post['name']);

				$this->Permission_model->UpdatePermission($data);
				return redirect('permission');
			}
		} else {
			$this->Add();
		}
	}

	function Add()
	{
		$id_adm = $this->Permission_model->GetAdmIdPermission();

		$data['main_content'] = 'pages/permission/add';
		$data['view_name'] = $this->lang->line("permission_waba_create");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("permission.js");

		$data['data'] = $this->Permission_model->GetById($id_adm[0]['id_permission']);

		$this->load->view('template',  $data);
	}

	function Edit($id_permission)
	{
		$data['main_content'] = 'pages/permission/edit';
		$data['view_name'] = $this->lang->line("permission_waba_edit");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("permission.js");
		$data['data'] = $this->Permission_model->GetById($id_permission);

		//Se o cara for adm ele --->> não consegue remover a permisão dele mesmo
		if ($data['data']['adm'] == 1) {
			unset($data['data']['permission']);
		}

		$this->load->view('template',  $data);
	}

	function Delete($key_id)
	{
		$this->load->model('Permission_model', '', TRUE);
		$data = $this->Permission_model->Delete($key_id);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}
}
