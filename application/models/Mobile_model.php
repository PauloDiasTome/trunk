<?php

class Mobile_model extends TA_model
{

	public function __construct()
	{
		parent::__construct();
	}


	function AddContactOnMobileDB($data)
	{
		$params = [
			'id_channel' => 2, 'id_user_group' => 8, 'deleted' => 1, 'spam' => 1, 'sex' => 1,
			'verify' => 2, 'exist' => 1, 'is_private' => 1, 'is_group' => 1
		];

		$date = new DateTime();
		$creation = $date->getTimestamp();

		$phone_num = $data['key_remote_id'];
		if (strlen($data['key_remote_id']) > 12) {
			$pos = strpos($data['key_remote_id'], '9', 4);
			if ($pos == true)
				$phone_num = str_replace($phone_num[$pos], '', $phone_num);
		}

		$name = trim(str_replace('%20', ' ', $data['name']), ' ');

		$sql = "INSERT into {$data['db']}.contact \n";
		$sql .= "( creation, id_channel, key_remote_id, full_name, id_user_group, user_key_remote_id, \n";
		$sql .= "deleted, spam, sex, verify, exist, is_private, is_group ) \n";
		$sql .= "values ( {$creation}, {$params['id_channel']}, {$phone_num}, '{$name}', {$params['id_user_group']}, \n";
		$sql .= "'0', {$params['deleted']}, {$params['spam']}, {$params['sex']}, {$params['verify']}, \n";
		$sql .= "{$params['exist']}, {$params['is_private']}, {$params['is_group']} );";

		$ret = ['msg' => null];

		if ($this->db->query($sql)) {
			$ret['msg'] = 'ok';
			$ret['data'] = [
				'creation' => $creation,
				'id_channel' => $params['id_channel'],
				'key_remote_id' => $phone_num,
				'full_name' => $name,
				'id_user_group' => $params['id_user_group'],
				'user_key_remote_id' => $data['user_key_remote_id'],
				'deleted' => $params['deleted'],
				'spam' => $params['spam'],
				'sex' => $params['sex'],
				'verify' => $params['verify'],
				'exist' => $params['exist'],
				'is_private' => $params['is_private'],
				'is_group' => $params['is_group']
			];
		}

		return json_encode($ret);
	}
	

	function checkUserToAddOnMobileDB($data)
	{
		$sql = "SELECT ch.id_channel, user.*, cp.db \n";
		$sql .= "FROM talkall_admin.user \n";
		$sql .= "inner join talkall_admin.company as cp \n";
		$sql .= "on cp.id_company = user.id_company \n";
		$sql .= "inner join talkall_admin.channel as ch \n";
		$sql .= "on ch.id_company = cp.id_company \n";
		$sql .= "where talkall_admin.user.key_remote_id like '%{$data['key_remote_id']}%' and talkall_admin.user.status = 1 limit 1";

		$res = $this->db->query($sql);
		$res_data = $res->result_array();

		if ($res_data[0] != null) {

			$this->db = $this->load->database(Setdatabase($res_data[0]['db']), TRUE);

			$data_to_add = [
				'db' => $res_data[0]['db'],
				'key_remote_id' => $data['phone'],
				'name' => $data['name'],
				'user_key_remote_id' => $data['key_remote_id']
			];

			$ret = $this->AddContactOnMobileDB($data_to_add);

			// $sql_check = "select * FROM {$res_data[0]['db']}.contact \n";
			// $sql_check .= "where ( key_remote_id like '%{$data['phone']}%' or key_remote_id like '%{$phone_num}%' ) \n";
			// $sql_check .= "and is_group = 1 limit 1";

			// $result = $this->db->query($sql_check);						

			return $ret;
		} else {
			return print_r(json_encode(['msg' => 'number not avalible!']));
		}
	}
}
