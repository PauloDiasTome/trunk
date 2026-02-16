<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class ProductController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();
		parent::checkPermission("product");

		$this->load->model('Product_model', '', TRUE);
		$this->lang->load('setting_catalog_lang', $this->session->userdata('language'));
	}


	function Index()
	{
		$data['main_content'] = 'pages/product/find';
		$data['view_name'] = $this->lang->line("catalog_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("product.js", "");

		$this->load->view('template',  $data);
	}


	function Get()
	{
		$post = $this->input->post();

		$records = $this->Product_model->Count($post['text']  ?? "", $post['situation']);
		$query = $this->Product_model->Get($post['text']  ?? "", $post['situation'], $post['start'], $post['length'], $post['order'][0]['column'], $post['order'][0]['dir']);

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


	function valid_image($text)
	{
		if ($text == "") {
			return false;
		} else {
			return true;
		}
	}


	public function save($key_id)
	{
		$data = $this->input->post();

		$this->form_validation->set_rules('input-title', $this->lang->line("setting_catalog_product_name"), 'trim|required|min_length[2]');
		$this->form_validation->set_rules('input-code', $this->lang->line("setting_catalog_code"), 'trim|required|min_length[2]');

		if (isset($data['addFile'])) {

			$this->form_validation->set_rules('cover', 'Imagem', 'callback_valid_image', array('valid_image' => 'Adicione pelo menos uma imagem'));
		} else if (isset($data['editFile'])) {

			$this->form_validation->set_rules('cover',  'Imagem', 'callback_valid_image', array('valid_image' => 'Adicione pelo menos uma imagem'));
		}


		if ($this->form_validation->run()) {

			if (isset($data['cover'])) {

				rename(FCPATH . "tmp/" . $data['cover'], FCPATH . "products/" . $data['cover']);

				$data['cover'] = $data['cover'];

				$thumbnail_img = FCPATH . 'preview/' . Token() . ".jpeg";
				$image = new \Imagick(FCPATH . 'products/' . $data['cover']);

				$image->thumbnailImage($image->getImageWidth(), $image->getImageHeight());
				$image->setCompressionQuality(100);
				$image->setFormat("jpeg");
				$image->writeImage($thumbnail_img);

				$thumbnail =  'data:image/jpeg;base64,' . base64_encode(file_get_contents($thumbnail_img));

				$data['thumbnail'] = $thumbnail;
			}

			if (isset($data['file1'])) {

				for ($i = 1; $i <= 9; $i++) {

					if (isset($data['file' . $i])) {

						rename(FCPATH . "tmp/" . $data['file' . $i], FCPATH . "products/" . $data['file' . $i]);
						$data['file' . $i] = $data['file' . $i];

						$thumbnail_img = FCPATH . 'preview/' . Token() . ".jpeg";
						$image = new \Imagick(FCPATH . 'products/' . $data['file' . $i]);

						$image->thumbnailImage($image->getImageWidth(), $image->getImageHeight());
						$image->setCompressionQuality(100);
						$image->setFormat("jpeg");
						$image->writeImage($thumbnail_img);

						$thumbnail =  'data:image/jpeg;base64,' . base64_encode(file_get_contents($thumbnail_img));

						$data['thumbnail' . $i] = $thumbnail;
					}
				}
			}

			if ($key_id > 0) {
				$this->Product_model->Edit($key_id, $data);
			} else {
				$this->Product_model->Add($data);
			}

			redirect("product", 'refresh');
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
		$data['main_content'] = 'pages/product/add';
		$data['view_name'] = $this->lang->line("catalog_waba_add");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("product.js");

		$data['data']['data'] = "";
		$data['data']['name'] = "";
		$data['data']['code'] = "";

		$this->load->view('template',  $data);
	}


	function Edit($key_id)
	{
		$data['title'] = "TalkAll | Editar";
		$data['id'] = ($key_id);

		$info = $this->Product_model->GetInf($key_id);

		$data['data'] = $info[0];

		$data['main_content'] = 'pages/product/edit';
		$data['sidenav'] = array('');
		$data['view_name'] = $this->lang->line("catalog_waba_edit");
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("product.js");

		$this->load->view('template',  $data);
	}


	private $tmp = './tmp';

	public function Upload()
	{
		if (!empty($_FILES)) {

			$config['upload_path'] =  $this->tmp;
			$config['allowed_types'] = 'jpg|jpeg';

			$file_ext = pathinfo($_FILES['file']["name"], PATHINFO_EXTENSION);
			$config['file_name'] = Token() . "." . $file_ext;

			$this->load->library('upload', $config);

			if (!$this->upload->do_upload('file')) {
				echo "Falha ao fazer upload";
			}

			return $this->output
				->set_content_type('application/json')
				->set_status_header(200)
				->set_output(json_encode($config['file_name']));
		}
	}


	public function List_files($key_id)
	{

		$data = $this->Product_model->List_files($key_id);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($data));
	}


	function RemoveUpload()
	{
		$file = $this->input->post('file');
		$id_product_picture = $this->input->post('id_product_picture');

		if (file_exists(FCPATH . $file)) {
			unlink(FCPATH . $file);
		}

		$this->Product_model->DeleteProductPicture($id_product_picture);
	}


	function Appeal($key_id)
	{
		$data['title'] = "TalkAll | teste";
		$data['id'] = ($key_id);

		$data['data'] = $this->Product_model->Appeal($key_id);

		$data['main_content'] = 'pages/product/appeal';
		$data['sidenav'] = array('');
		$data['view_name'] = "appeal";
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("product.js");

		$this->load->view('template',  $data);
	}


	function AppealSave($key_id)
	{
		$data = $this->input->post();

		$this->form_validation->set_rules('input-description', 'Descrição', 'trim|required|min_length[2]|max_length[1000]');

		if ($this->form_validation->run()) {

			$this->Product_model->AppealSave($key_id, $data);
			redirect("product", 'refresh');
		} else {
			$this->Appeal($key_id);
		}
	}


	function Delete($key_id)
	{
		$this->Product_model->Delete($key_id);
	}
}
