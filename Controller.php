<?php
namespace JDS\CoreMVC;
use JDS\CoreMVC\Application;
use JDS\CoreMVC\Middlewares\BaseMiddleware;

class Controller {

	public string $layout = 'main';
	public string $action = '';

	/**
	 * 
	 * @var JDS\CoreMVC\Middlewares\BaseMiddleware[]
	 */
	protected array $middlewares;
	
	public function render($view, $params=[]) {
		return Application::$app->view->renderView($view, $params);
	}

	public function setLayout($layout) {
		$this->layout = $layout;
	}

	public function registerMiddleware(BaseMiddleware $middleware) {
		$this->middlewares[] = $middleware;
	}


	/**
	 * Get the value of middlewares
	 *
	 * @return  JDS\CoreMVC\Middlewares\BaseMiddleware[]
	 */ 
	public function getMiddlewares()
	{
		return $this->middlewares ?? [];
	}
}