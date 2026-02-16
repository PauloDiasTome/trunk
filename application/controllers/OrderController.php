<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class OrderController extends TA_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model('Order_model', '', TRUE);
		$this->load->model('OrderStatus_model', '', TRUE);
		$this->load->model('PaymentMethod_model', '', TRUE);
		$this->lang->load('order_lang', $this->session->userdata('language'));
	}


	function Index()
	{
		$status = $this->OrderStatus_model->List();

		$data['main_content'] = 'pages/order/find';
		$data['view_name'] = $this->lang->line("order_waba_header");
		$data['sidenav'] = array('');
		$data['topnav'] = array('search' => true);
		$data['js'] = array("order.js");
		$data['order_status'] = $status;

		$this->load->view('template',  $data);
	}


	function Get()
	{
		$post = $this->input->post();

		$records = $this->Order_model->Count($post['text']  ?? "");
		$query = $this->Order_model->Get($post['text']  ?? "", $post['status'], $post['dt_start'] ?? "", $post['dt_end'] ?? "", $post['start'], $post['length'], $post['order'][0]['column'], $post['order'][0]['dir']);

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

		if ($key_id > 0) {
			$this->Order_model->Edit($key_id, $data);
		} else {
			$this->Order_model->Add($data);
		}

		redirect("order", 'refresh');
	}


	function Edit($key_id)
	{
		$data['title'] = "TalkAll | Editar";
		$data['id'] = ($key_id);

		$info = $this->Order_model->GetInf($key_id);
		$status = $this->OrderStatus_model->List();
		$payment = $this->PaymentMethod_model->List();

		$data['data'] = $info[0];

		$data['main_content'] = 'pages/order/edit';
		$data['sidenav'] = array('');
		$data['view_name'] = $this->lang->line("order_waba_edit");
		$data['topnav'] = array('search' => false, 'header' => true);
		$data['js'] = array("order.js");
		$data['order_status'] = $status;
		$data['payment'] = $payment;

		$this->load->view('template',  $data);
	}


	function GetItems()
	{
		$post = $this->input->post();

		$records = $this->Order_model->CountItems($post['order_id']);
		$query = $this->Order_model->GetItems($post['order_id'], $post['order'][0]['column'], $post['order'][0]['dir']);

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


	function GoogleMapsCalculate($origin, $destination)
	{
		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => 'https://maps.googleapis.com/maps/api/distancematrix/json?origins=' . $origin . '&destinations=' . $destination . '&mode=driving&language=pt-BR&sensor=false&key=AIzaSyAOdIOaQI8c7GETC3kj0C0N8bxsm8Dw6uA',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'GET',
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/json',
			),
		));

		$response = curl_exec($curl);
		curl_close($curl);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output($response);
	}


	function GetInfoItems($key_id)
	{
		$records = $this->Order_model->GetInfoItems($key_id);

		return $this->output
			->set_content_type('application/json')
			->set_status_header(200)
			->set_output(json_encode($records));
	}


	function Delete($key_id)
	{
		$this->load->model('Order_model', '', TRUE);
		$this->Order_model->Delete($key_id);
	}


	function DeleteItems($key_id)
	{
		$this->load->model('Order_model', '', TRUE);
		$this->Order_model->DeleteItems($key_id);
	}
}
