<?php
namespace App\Core;
use App\Core\Application;
use App\Core\Middlewares\BaseMiddleware;

class Controller {

	public string $layout = 'main';
	public string $action = '';

	/**
	 * 
	 * @var APP\Core\Middlewares\BaseMiddleware[]
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
	 * @return  APP\Core\Middlewares\BaseMiddleware[]
	 */ 
	public function getMiddlewares()
	{
		return $this->middlewares ?? [];
	}
}