<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class LastCurrentBroadcastController extends TA_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	function Index($waba, $bot_id)
	{
		$curl = curl_init();

		$data = $this->input->post();

		$this->load->model('LastCurrentBroadcast_model', '', TRUE);
		$info['CurrentBroadcast'] = $this->LastCurrentBroadcast_model->Get($bot_id);

		$this->load->model('Channel_model', '', TRUE);
		$info['Channel'] = $this->Channel_model->GetToken($waba);

		if ($info['CurrentBroadcast'] != null) {

			foreach ($info['CurrentBroadcast'] as $row) {

				$text = $row['media_caption'];

				$text = str_replace("\n\r", "\\n\\r", $text);
				$text = str_replace("\n", "\\n", $text);
				$text = str_replace("\r", "\\r", $text);
				$text = str_replace("\n\t", "\\n\\t", $text);

				if ($row['media_type'] == 1) {

					curl_setopt_array($curl, array(
						CURLOPT_URL => "https://waba.messagepipe.io/v1/messages",
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => "",
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 0,
						CURLOPT_FOLLOWLOCATION => true,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => "POST",
						CURLOPT_POSTFIELDS => "{\n  \"to\": \"" . $data['from'] . "\",\n  \"type\": \"text\",\n  \"recipient_type\": \"individual\",\n  \"text\": {\n    \"body\": \"$text\"\n  }\n}",
						CURLOPT_HTTPHEADER => array(
							"Content-Type: application/json",
							"D360-API-KEY: " . $info['Channel'][0]['pw']
						),
					));

					$response = curl_exec($curl);

					curl_close($curl);
					echo $response;
				}

				if ($row['media_type'] == 2) {

					curl_setopt_array($curl, array(
						CURLOPT_URL => "https://waba.messagepipe.io/v1/messages",
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => "",
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 0,
						CURLOPT_FOLLOWLOCATION => true,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => "POST",
						CURLOPT_POSTFIELDS => "{\n\t\"to\": \"" . $data['from'] . "\",\n\t\"type\": \"image\",\n\t\"recipient_type\": \"individual\",\n\t\"image\": {\n\t\t\"link\": \"" . $row['media_url'] . "\",\n\t\t\"caption\": \"" . $text . "\"\n\t}\n}\n",
						CURLOPT_HTTPHEADER => array(
							"Content-Type: application/json",
							"D360-API-KEY: " . $info['Channel'][0]['pw']
						),
					));

					$response = curl_exec($curl);

					curl_close($curl);
					echo $response;
				}

				if ($row['media_type'] == 3) {

					curl_setopt_array($curl, array(
						CURLOPT_URL => "https://waba.messagepipe.io/v1/messages",
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_ENCODING => "",
						CURLOPT_MAXREDIRS => 10,
						CURLOPT_TIMEOUT => 0,
						CURLOPT_FOLLOWLOCATION => true,
						CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
						CURLOPT_CUSTOMREQUEST => "POST",
						CURLOPT_POSTFIELDS => "{\n\t\"to\": \"" . $data['from'] . "\",\n\t\"type\": \"document\",\n\t\"recipient_type\": \"individual\",\n\t\"document\": {\n\t\t\"link\": \"" . $row['media_url'] . "\",\n\t\t\"caption\": \"" . $text . "\"\n\t}\n}\n",
						CURLOPT_HTTPHEADER => array(
							"Content-Type: application/json",
							"D360-API-KEY: " . $info['Channel'][0]['pw']
						),
					));

					$response = curl_exec($curl);

					curl_close($curl);
					echo $response;
				}
			}
		} else {

			$text = "Nesse momento não temos nenhuma promoção vigente!";

			curl_setopt_array($curl, array(
				CURLOPT_URL => "https://waba.messagepipe.io/v1/messages",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => "{\n  \"to\": \"" . $data['from'] . "\",\n  \"type\": \"text\",\n  \"recipient_type\": \"individual\",\n  \"text\": {\n    \"body\": \"$text\"\n  }\n}",
				CURLOPT_HTTPHEADER => array(
					"Content-Type: application/json",
					"D360-API-KEY: " . $info['Channel'][0]['pw']
				),
			));

			$response = curl_exec($curl);

			curl_close($curl);
			echo $response;
		}

		//$this->load->view('pages/widget/widget.php',$info);
	}
}
