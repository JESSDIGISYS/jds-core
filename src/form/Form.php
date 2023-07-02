<?php 
namespace JDS\CoreMVC\Form;

use JDS\CoreMVC\Model;

class Form {
	

	public function beginForm($action, $method) {
		echo sprintf('<form action="%s" method="%s">', $action, $method);
	}

	public function endForm() {
		echo '</form>';
	}

	public static function start() {
		return new Form();
	}

	public function openDiv($classes) {
		echo sprintf('<div class="%s">', $classes);
	}

	public function closeDiv() {
		echo '</div>';
	}

	public function field(Model $model, $attribute) {
		return new InputField($model, $attribute);
	}

	public function textarea(Model $model, $attribute) {
		return new TextareaField($model, $attribute);
	}

	public function elements(): array {
		return [
			'email' => [
				'autofocus',
			]
		];
	}
}