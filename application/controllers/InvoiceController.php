<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class InvoiceController extends TA_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("invoice");

		$this->load->model('Company_model', '', TRUE);
	}


	function Index()
	{
		$data['main_content'] = 'pages/invoice/find';
		$data['view_name'] = 'Invoice';
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("invoice.js");

		$this->load->view('template',  $data);
	}


	function Get()
	{
		$post = $this->input->post();

		$this->load->model('Invoice_model', '', TRUE);

		$records = $this->Invoice_model->Count($post['text']  ?? "");
		$query = $this->Invoice_model->Get($post['text']  ?? "", $post['start'], $post['length']);

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

		$this->load->model('Invoice_model', '', TRUE);

		$config = array(
			'upload_path'   => FCPATH . '/invoices',
			'allowed_types' => 'jpg|jpeg|pdf',
			'max_size'      => '10000'
		);

		for ($i = 1; $i <= 3; $i++) {
			if (!empty($_FILES['file' . $i]["name"])) {

				$file_ext = pathinfo($_FILES['file' . $i]["name"], PATHINFO_EXTENSION);
				$config['file_name'] = Token() . "." . $file_ext;

				$this->load->library('upload', $config);

				if ($this->upload->do_upload('file' . $i)) {
					$upload_data = $this->upload->data();

					switch ($i) {
						case 1:
							$data['file1']	=  $config['upload_path'] . "/" . $upload_data['file_name'];
							break;
						case 2:
							$data['file2']	= $config['upload_path'] . "/" . $upload_data['file_name'];
							break;
						case 3:
							$data['file3']	= $config['upload_path'] . "/" . $upload_data['file_name'];
							break;
					}
				}
			}
		}

		if ($key_id > 0) {
			$this->Invoice_model->Edit($key_id, $data);
		} else {
			$this->Invoice_model->Add($data);
		}

		redirect("invoice", 'refresh');
	}


	function Add()
	{
		$data['main_content'] = 'pages/invoice/add';
		$data['view_name'] = "Add";
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("invoice.js");
		$data['data']['company'] =  $this->Company_model->List();

		$this->load->view('template',  $data);
	}


	function AddFile($key_id)
	{
		$data['title'] = "TalkAll | Add File";
		$data['id'] = ($key_id);

		$data['main_content'] = 'pages/invoice/addFile';
		$data['sidenav'] = array('');
		$data['view_name'] = "Add File";
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("invoice.js");

		$this->load->view('template',  $data);
	}


	function Edit($key_id)
	{
		$data['title'] = "TalkAll | Editar";
		$data['id'] = ($key_id);

		$this->load->model('Invoice_model', '', TRUE);
		$info = $this->Invoice_model->GetInf($key_id);
		$data['data'] = $info[0];

		$this->load->model('UserGroup_model', '', true);
		$data['data']['company'] = $this->Company_model->List();


		$data['main_content'] = 'pages/invoice/edit';
		$data['view_name'] = "Edit";
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("invoice.js");


		$this->load->view('template',  $data);
	}


	function Delete($key_id)
	{
		$this->load->model('Invoice_model', '', TRUE);
		$this->Invoice_model->Delete($key_id);
	}
}
