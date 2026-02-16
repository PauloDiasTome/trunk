<?php
date_default_timezone_set('America/Sao_Paulo');
/*
|--------------------------------------------------------------------------
| TALKALL AUTH SERVER
|--------------------------------------------------------------------------
*/

error_reporting(-1);
ini_set('display_errors', 1);

class Server
{
	public $CI;
	public $id_company;
	public $webhooks;
	public $db;

	function __construct($config = array())
	{
		$this->CI = &get_instance();						//database config
		require_once(__DIR__ . '/../third_party/Oauth2/src/OAuth2/Autoloader.php');	//oauth library
		$config = Setdatabase('talkall_admin');


		OAuth2\Autoloader::register();

		$this->storage = new OAuth2\Storage\Pdo(
			array(
				'dsn' => $config['dsn'],
				'username' => $config["username"],
				'password' => $config["password"]
			)
		);

		//ISSO AQUI TEM AS FUNÇÕES
		$this->server = new OAuth2\Server($this->storage, array(
			'always_issue_new_refresh_token' => true,
			'refresh_token_lifetime'         => 2419200,
			'access_lifetime' => 172800
		));


		//ISSO AQUI PEGA O REQUEST DE GET E POST
		$this->request = OAuth2\Request::createFromGlobals();

		//ISSO AQUI DEVOLVE O JSON
		$this->response = new OAuth2\Response();
	}

	/**
	 * client_credentials, for more see: http://tools.ietf.org/html/rfc6749#section-4.3
	 */
	public function client_credentials()
	{
		$this->server->addGrantType(new OAuth2\GrantType\ClientCredentials($this->storage, array("allow_credentials_in_request_body" => true)));
		$this->server->handleTokenRequest($this->request)->send();
	}

	/**
	 * password_credentials, for more see: http://tools.ietf.org/html/rfc6749#section-4.3
	 */
	public function password_credentials()
	{
		$users = array("user" => array("password" => 'pass', 'first_name' => 'homeway', 'last_name' => 'yao'));
		$storage = new OAuth2\Storage\Memory(array('user_credentials' => $users));
		$this->server->addGrantType(new OAuth2\GrantType\UserCredentials($storage));
		$this->server->handleTokenRequest($this->request)->send();
	}

	/**
	 * refresh_token, for more see: http://tools.ietf.org/html/rfc6749#page-74
	 */
	public function refresh_token()
	{
		$this->server->addGrantType(new OAuth2\GrantType\RefreshToken($this->storage, array(
			"always_issue_new_refresh_token" => true,
			"unset_refresh_token_after_use" => true,
			"refresh_token_lifetime" => 2419200,
		)));
		$this->server->handleTokenRequest($this->request)->send();
	}

	/**
	 * limit scpoe here
	 */
	public function require_scope($scope = "")
	{
		if (!$this->server->verifyResourceRequest($this->request, $this->response, $scope)) {
			$this->server->getResponse()->send();
			die;
		}
	}

	public function check_client_id()
	{
		if (!$this->server->validateAuthorizeRequest($this->request, $this->response)) {
			$this->response->send();
			die;
		}
	}

	public function UserInfo()
	{
		return $this->server->getAccessTokenData($this->request, $this->response);
	}

	public function SetTalkallUser()
	{
		$this->CI->load->model('Api/Auth_model', 'user', TRUE);

		$token = $this->UserInfo();
		$data = $this->CI->user->Auth($token['user_id'], $token['client_id']);

		$this->id_company = $data['id_company'];
		$this->database = $data['db'];
		$this->webhooks = $data['webhooks'];
	}

	public function SetUserDatabase()
	{
		$this->SetTalkallUser();
		$this->CI->db = $this->CI->load->database(Setdatabase($this->database), TRUE);
	}

	public function authorize($is_authorized)
	{
		$this->server->addGrantType(new OAuth2\GrantType\AuthorizationCode($this->storage));
		$this->server->handleAuthorizeRequest($this->request, $this->response, $is_authorized, $this->CI->session->userdata('id_user_talkall_admin'));
		if ($is_authorized) {
			$code = substr($this->response->getHttpHeader('Location'), strpos($this->response->getHttpHeader('Location'), 'code=') + 5, 40);
			header("Location: " . $this->response->getHttpHeader('Location'));
		}
		$this->response->send();
	}

	public function authorization_code()
	{
		$this->server->addGrantType(new OAuth2\GrantType\AuthorizationCode($this->storage));
		$this->server->handleTokenRequest($this->request)->send();
	}

	public function Token()
	{
		// AUTENTICAÇÃO DE APP
		//$this->server->addGrantType(new OAuth2\GrantType\ClientCredentials($this->storage, array("allow_credentials_in_request_body" => true)));

		//AUTENTICAÇÃO POR USUARIO
		//$this->server->addGrantType(new OAuth2\GrantType\UserCredentials($this->storage));

		// RENOVAÇÃO DE TOKEN
		$this->server->addGrantType(new OAuth2\GrantType\RefreshToken($this->storage, array(
			"always_issue_new_refresh_token" => true,
			"unset_refresh_token_after_use" => true,
			"refresh_token_lifetime" => 2419200,
		)));


		$this->server->addGrantType(new OAuth2\GrantType\AuthorizationCode($this->storage));


		$this->server->handleTokenRequest($this->request)->send();
	}
}
