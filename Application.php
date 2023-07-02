<?php

namespace App\Core;
use App\Core\DB\Database;


/**
 * [Description for Application]
 *
 * 
 * Created at: 7/2/2023, 12:58:42 AM (America/Indianapolis)
 * @author     Mr J 
 * @see       {@link https://jessdigisys.com} 
 * @see       {@link Jessop Digital Systems (JDS)} 
 * @copyright Jessop Digital Systems (JDS) 
 */
class Application {
	public static string $ROOT_DIR;

	public string $layout = 'main';
	public string $userClass;
	public View $view;
	public Router $router;
	public Request $request;
	public Response $response;
	public Session $session;
	public static Application $app;
	public Controller $controller;
	public Database $db;
	public ?UserModel $user;

	/**
	 * [Description for __construct]
	 *
	 * @param mixed $rootPath
	 * @param array $config
	 * 
	 * Created at: 7/2/2023, 12:58:42 AM (America/Indianapolis)
	 * @author     Mr J 
	 * @see       {@link https://jessdigisys.com} 
	 * @see       {@link Jessop Digital Systems (JDS)} 
	 * @copyright Jessop Digital Systems (JDS) 
	 */
	public function __construct($rootPath, array $config) {
		$this->userClass = $config['userClass'];
		self::$ROOT_DIR = $rootPath;
		self::$app = $this;
		$this->view = new View();
		$this->request = new Request();
		$this->response = new Response();
		$this->session = new Session();
		$this->router = new Router($this->request, $this->response);
		// $config comes from index
		$this->db = new Database($config['db']);
		// if user is logged in get the user primary key from the session

		$primaryValue = $this->session->user;
		if ($primaryValue) {
			$primaryKey = $this->userClass::primaryKey();
			$this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
		} else {
			$this->user = null;
		}
	}

	/**
	 * [Description for run]
	 *
	 * @return [type]
	 * 
	 * Created at: 7/2/2023, 12:59:34 AM (America/Indianapolis)
	 * @author     Mr J 
	 * @see       {@link https://jessdigisys.com} 
	 * @see       {@link Jessop Digital Systems (JDS)} 
	 * @copyright Jessop Digital Systems (JDS) 
	 */
	public function run() {
		try {
			echo $this->router->resolve();
		} catch (\Exception $e) {
			$this->response->setStatusCode($e->getCode());
			echo $this->view->renderView('_error', [
				'exception' => $e
			]);
		}
	}

	/**
	 * Get the value of controller
	 */ 
	public function getController()
	{
		return $this->controller;
	}

	/**
	 * Set the value of controller
	 *
	 * @return  self
	 */ 
	public function setController($controller)
	{
		$this->controller = $controller;

		return $this;
	}
	

	/**
	 * [Description for login]
	 *
	 * @param DBModel $user
	 * 
	 * @return [type]
	 * 
	 * Created at: 7/2/2023, 1:00:26 AM (America/Indianapolis)
	 * @author     Mr J 
	 * @see       {@link https://jessdigisys.com} 
	 * @see       {@link Jessop Digital Systems (JDS)} 
	 * @copyright Jessop Digital Systems (JDS) 
	 */
	public function login(UserModel $user) {
		$this->user = $user;
		$primaryKey = $user->primaryKey();
		$primaryValue = $this->user->{$primaryKey};
		$this->session->user = $primaryValue;
		return true;
	}

		
	/**
	 * [Description for logout]
	 *
	 * @return [type]
	 * 
	 * Created at: 7/2/2023, 1:00:31 AM (America/Indianapolis)
	 * @author     Mr J 
	 * @see       {@link https://jessdigisys.com} 
	 * @see       {@link Jessop Digital Systems (JDS)} 
	 * @copyright Jessop Digital Systems (JDS) 
	 */
	public function logout() {
		$this->user = null;
		$this->session->remove('user');
	}

	/**
	 * [Description for isGuest]
	 *
	 * @return [type]
	 * 
	 * Created at: 7/2/2023, 1:00:35 AM (America/Indianapolis)
	 * @author     Mr J 
	 * @see       {@link https://jessdigisys.com} 
	 * @see       {@link Jessop Digital Systems (JDS)} 
	 * @copyright Jessop Digital Systems (JDS) 
	 */
	public static function isGuest() {
		return !self::$app->user;
	}

	/**
	 * [Description for debugger]
	 *
	 * @param mixed $files
	 * 
	 * @return [type]
	 * 
	 * Created at: 7/2/2023, 1:05:58 AM (America/Indianapolis)
	 * @author     Mr J 
	 * @see       {@link https://jessdigisys.com} 
	 * @see       {@link Jessop Digital Systems (JDS)} 
	 * @copyright Jessop Digital Systems (JDS) 
	 */
	public static function debugger($files) {
		echo '<pre>';
		var_dump($files);
		echo '</pre>';
		exit;
	}

	/**
	 * [Description for debuggerNoExit]
	 *
	 * @param mixed $files
	 * 
	 * @return [type]
	 * 
	 * Created at: 7/2/2023, 1:06:07 AM (America/Indianapolis)
	 * @author     Mr J 
	 * @see       {@link https://jessdigisys.com} 
	 * @see       {@link Jessop Digital Systems (JDS)} 
	 * @copyright Jessop Digital Systems (JDS) 
	 */
	public static function debuggerNoExit($files) {
		echo '<pre>';
		var_dump($files);
		echo '</pre>';
	}

}
