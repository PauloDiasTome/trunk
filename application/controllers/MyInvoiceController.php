<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class MyInvoiceController extends TA_Controller
{
	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("myinvoice");

		$this->load->model('MyInvoice_model', '', TRUE);
		$this->lang->load('myinvoice_lang', $this->session->userdata('language'));
	}


	function Index()
	{
		$data['main_content'] = 'pages/myinvoice/find';
		$data['view_name'] = $this->lang->line("myinvoice_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("myinvoice.js");

		$this->load->view('template',  $data);
	}

	function Get()
	{
		$param = $this->input->post();
		$result = $this->MyInvoice_model->Get($param);

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

	function Save($key_id)
	{
		$data = $this->input->post();

		$this->form_validation->set_rules('input-date-payment', $this->lang->line("myinvoice_addfile_payment_date"), 'trim|required');
		$this->form_validation->set_rules('file', $this->lang->line("myinvoice_addfile_payment"), 'trim|required');

		if ($this->form_validation->run() == true) {

			$config = array(
				'upload_path'   => FCPATH . '/invoices',
				'allowed_types' => 'jpg|jpeg|pdf',
				'max_size'      => '10000'
			);

			if (!empty($_FILES['file']["name"])) {

				$file_ext = pathinfo($_FILES['file']["name"], PATHINFO_EXTENSION);
				$config['file_name'] = Token() . "." . $file_ext;

				$this->load->library('upload', $config);

				if ($this->upload->do_upload('file')) {
					$upload_data = $this->upload->data();
					$data['file']	=  "/invoices/" . $upload_data['file_name'];
				}
			}

			if ($key_id > 0) {
				$this->MyInvoice_model->Edit($key_id, $data);
			}

			redirect("myinvoice", 'refresh');
		} else {
			$this->AddFile($key_id);
		}
	}


	function AddFile($key_id)
	{
		$data['title'] = "TalkAll | Add File";
		$data['id'] = ($key_id);

		$data['main_content'] = 'pages/myinvoice/addFile';
		$data['sidenav'] = array('');
		$data['view_name'] = $this->lang->line("myinvoice_waba_addfile");
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("myinvoice.js");

		$this->load->view('template',  $data);
	}


	function CheckMyInvoice()
	{
		$this->load->model('MyInvoice_model', '', true);
		$data = $this->MyInvoice_model->CheckMyInvoice();

		echo json_encode($data);
		exit();
	}
}
