<?php

class UniaoBot_model extends TA_model
{
	public $database;

	function __construct()
	{
		$this->database = 'db297';
	}

	function CheckingIfIsInAttendance ($data) {
		$db = $this->load->database(Setdatabase($this->database), TRUE);

		$sql = "select * from chat_list inner join contact on chat_list.id_contact = contact.id_contact\n";
		$sql .= "where ( chat_list.key_remote_id is not null and chat_list.key_remote_id != '0' ) and contact.key_remote_id = '".$data."'\n";

		$result = $db->query($sql);

		if ($result->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	function AddTheWaitList ($data) { // Envia para o atendimento adicionando na fila de espera(table = wait_list)
		$date = new DateTime();

		$db = $this->load->database(Setdatabase($this->database), TRUE);

		$values = [
            'creation' => $date->getTimestamp(),
            'key_remote_id' => $data['key_remote_id'],
            'id_user_group' => $data['id_user_group'],
			'account_key_remote_id' => $data['account_key_remote_id'],
            'status' => 1
		];

		$db->insert('wait_list', $values);
	}

	function CheckWaitList ($data) { // Confirma presença na fila
		
		$db = $this->load->database(Setdatabase($this->database), TRUE);
		
		$sql = "select * from wait_list where wait_list.key_remote_id = '". $data ."' and status = 1\n";
		
		$result = $db->query($sql);

		if ($result->num_rows() > 0) {
			return true;
        } else {
			return false;
		}
	}

	function WriteCache($key_remote_id, $json) {
		$db = $this->load->database(Setdatabase($this->database), TRUE);
		$sql = "select id_contact from contact where contact.key_remote_id = '". $key_remote_id ."'\n";

		$result = $db->query($sql);
		$id_contact = $result->result_array()[0]['id_contact'];

		$db->set('json_cache', $json);
		$db->where('id_contact', $id_contact);
		$db->update('contact');
	}

	function ReadCache($key_remote_id) {
		$db = $this->load->database(Setdatabase($this->database), TRUE);
		$sql = "select json_cache from contact where contact.key_remote_id = '". $key_remote_id ."'\n";

		$result = $db->query($sql);
		$json_cache = $result->result_array()[0]['json_cache'];

		return $json_cache;
	}

	function DeleteCache($key_remote_id) {
		$db = $this->load->database(Setdatabase($this->database), TRUE);
		$sql = "select id_contact from contact where contact.key_remote_id = '". $key_remote_id ."'\n";

		$result = $db->query($sql);
		$id_contact = $result->result_array()[0]['id_contact'];

		$db->set('json_cache', null);
		$db->where('id_contact', $id_contact);
		$db->update('contact');
	}

	function Office_Hours_End($id) {
		$db = $this->load->database(Setdatabase($this->database), TRUE);
		$sql = "select id_work_time from channel where id = '". $id ."'";

		$result = $db->query($sql);
		$id_work_time = $result->result_array()[0]['id_work_time'];

		unset($sql);
		unset($result);

		$sql = "select start, end from work_time_week where id_work_time = '". $id_work_time ."' LIMIT 1";

		$result = $db->query($sql);
		$start = $result->result_array()[0]['start'];
		$end = $result->result_array()[0]['end'];

		date_default_timezone_set('America/Sao_Paulo');

		$startHour = strtotime($start);
		$endHour = strtotime($end);
		$currentHour = strtotime("now");

		if(date('l') === 'Saturday' || date('l') === 'Sunday' || $currentHour < $startHour || $currentHour > $endHour) {
			unset($sql);
			unset($result);

			$sql = "select office_hours_end from config where id_channel = 336";

			$result = $db->query($sql);
			$text = $result->result_array()[0]['office_hours_end'];

			$obj = new stdClass;
			$obj->status = true;
			$obj->text = $text;

			return $obj;
		}

		return false;
	}
}
