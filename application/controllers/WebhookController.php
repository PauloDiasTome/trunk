<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class WebhookController extends CI_Controller
{

	protected $webhookHandler;

	public function __construct()
	{
		parent::__construct();

		// $this->webhookHandler = new Vindi\WebhookHandler;
	}


	function onMessageTelegram()
	{
		$content = file_get_contents("php://input");
		$this->load->model('Webhook_model', '', TRUE);
		$this->Webhook_model->Add($this->input->get('access_token', TRUE), $content);
	}


	function onMessageMercadoLivre()
	{
		$data = $this->input->post();
		$content = file_get_contents("php://input");
		$update = json_decode($content, true);
		$this->load->model('Webhook_model', '', TRUE);
		$this->Webhook_model->Add($update['application_id'], $content);
	}


	function onMessageWhatsApp($waba)
	{
		$content = file_get_contents("php://input");
		$this->load->model('Webhook_model', '', TRUE);
		$this->Webhook_model->Add($waba, $content);
	}


	function onMessageWhatsappBusiness($waba)
	{
		$content = file_get_contents("php://input");
		$this->load->model('Webhook_model', '', TRUE);
		$this->Webhook_model->Send($waba, $content);
	}


	function onMessageFacebook()
	{
		$content = file_get_contents("php://input");

		if (isset($_REQUEST['hub_challenge'])) {
			$challenge = $_REQUEST['hub_challenge'];
			$hub_verify_token = $_REQUEST['hub_verify_token'];
		}

		if ($hub_verify_token === "TalkAll") {
			echo $challenge;
		}

		$update = json_decode($content, true);

		if (isset($update['entry'][0]['messaging'][0])) {
			$this->load->model('Webhook_model', '', TRUE);
			$this->Webhook_model->Add($update['entry'][0]['id'], $content);
		} else {
			$this->load->model('Webhook_model', '', TRUE);
			if ($update['entry'][0]['changes'][0]['value']['verb'] == 'add') {
				$this->Webhook_model->Add($update['entry'][0]['id'] . '-F', $content);
			}
		}
	}


	function onMessageInstagram()
	{
		$content = file_get_contents("php://input");

		if (isset($_REQUEST['hub_challenge'])) {
			$challenge = $_REQUEST['hub_challenge'];
			$hub_verify_token = $_REQUEST['hub_verify_token'];
		}

		if ($hub_verify_token === "TalkAll") {
			echo $challenge;
		}

		$update = json_decode($content, true);

		//$file = fopen('/var/www/html/instagram.txt', 'w+');
		//fwrite($file, $content);
		//fclose($file);

		if (isset($update['entry'][0]['id'][0])) {
			$this->load->model('Webhook_model', '', TRUE);
			$this->Webhook_model->Add($update['entry'][0]['id'], $content);
		}
	}


	function onMessagePartner()
	{
		echo "talkall";
	}


	function onMessageVindi()
	{
		$event = file_get_contents("php://input");
		$content = json_decode($event);

		$this->load->model('Webhook_model', '', TRUE);

		switch ($content->event->type) {

			case 'bill_created':

				try {
					$res = $this->Webhook_model->createBill($content);

					if ($res == true) {
						return;
					}

					throw new Exception('Erro ao tentar inserir uma fatura no banco de dados!');
				} catch (Exception $e) {
					echo $e->getMessage();
				}

				break;
			case 'bill_paid':

				try {
					$res = $this->Webhook_model->payBill($content);

					if ($res == true) {

						$res = $this->Webhook_model->payCharge($content);

						if ($res == true) {
							return;
						}
						throw new Exception('Erro ao tentar atualizar o status da cobrança!');
					}
					throw new Exception('Erro ao tentar atualizar o status da fatura!');
				} catch (Exception $e) {
					echo $e->getMessage();
				}
				break;
			case 'bill_canceled':
				$this->Webhook_model->cancelBill($content);
				break;
			case 'charge_created':

				try {

					$res = $this->Webhook_model->createCharge($content);

					if ($res == true) {
						return;
					}

					throw new Exception('O payment_method não existe em nosso banco de dados!');
				} catch (Exception $e) {
					echo $e->getMessage();
				}
				break;
			case 'charge_refunded':
				break;
			case 'charge_rejected':
				break;

			case 'issue_created':

				$res = $this->Webhook_model->createIssue($event);
				var_dump($res);
				break;
			case 'test':
				// Lidar com o evento de Teste da URL
				break;
			default:
				// Lidar com falhas e eventos novos ou desconhecidos
				break;
		}
	}
}
