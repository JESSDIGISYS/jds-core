<?php

namespace App\Core\Form;

use App\Core\Model;

class InputField extends BaseField {
	public const TYPE_BUTTON = 'button';
	public const TYPE_CHECKBOX = 'checkbox';
	public const TYPE_COLOR = 'color';
	public const TYPE_DATE = 'date';
	public const TYPE_LOCAL = 'datetime-local';
	public const TYPE_EMAIL = 'email';
	public const TYPE_FILE = 'file';
	public const TYPE_HIDDEN = 'hidden';
	public const TYPE_IMAGE = 'image';
	public const TYPE_MONTH = 'month';
	public const TYPE_NUMBER = 'number';
	public const TYPE_PASSWORD = 'password';
	public const TYPE_RADIO = 'radio';
	public const TYPE_RANGE = 'range';
	public const TYPE_RESET = 'reset';
	public const TYPE_SEARCH = 'search';
	public const TYPE_SUBMIT = 'submit';
	public const TYPE_TEL = 'tel';
	public const TYPE_TEXT = 'text';
	public const TYPE_TIME = 'time';
	public const TYPE_URL = 'url';
	public const TYPE_WEEK = 'week';

	public string $type;

	public function __construct(Model $model, string $attribute) {
		$this->type = self::TYPE_TEXT;
		parent::__construct($model, $attribute);
	}

	public function __toString() {
		return sprintf('
			<label for="%s" class="%s">%s</label>
			%s
			<div id="%s" class="is-invalid-label help-text">%s</div>
			', 
			$this->attribute,
			$this->model->hasError($this->attribute) ? 'is-invalid-label' : '',			
			$this->model->labels()[$this->attribute] ?? ucfirst($this->attribute), 
			$this->renderInput(),
			'error'.$this->attribute,
			$this->model->getFirstError($this->attribute)
		);
	}

	public function passwordField() {
		$this->type = self::TYPE_PASSWORD;
		return $this;
	}

	public function renderInput() : string {
		return sprintf('<input type="%s" name="%s" id="%s" value="%s" class="%s" aria-describedby="%s">',			$this->type,
			$this->attribute,
			$this->attribute,
			$this->model->{$this->attribute} ,
			$this->model->hasError($this->attribute) ? 'is-invalid-input' : '',
			'error'.$this->attribute,
);


	}

	// public function input() {
	// 	return sprintf(
	// 		'
	// 		<label for="%s" class="%s">%s</label>
	// 		<input type="%s" name="%s" id="%s" value="%s" class="%s" aria-describedby="%s">
	// 		<div id="%s" class="is-invalid-label help-text">%s</div>
	// 		',
	// 		$this->attribute,
	// 		$this->model->hasError($this->attribute) ? 'is-invalid-label' : '',
	// 		$this->model->labels()[$this->attribute] ?? ucfirst($this->attribute), 
	// 		$this->type,
	// 		$this->attribute,
	// 		$this->attribute,
	// 		$this->model->{$this->attribute},
	// 		$this->model->hasError($this->attribute) ? 'is-invalid-input' : '',
	// 		'error' . $this->attribute,
	// 		'error' . $this->attribute,
	// 		$this->model->getFirstError($this->attribute)
	// 	);
	// }

	// public function textarea() {
	// 	return sprintf(
	// 		/*html*/'<label for="%s" class="%s">%s</label><textarea name="%s" id="%s" title="%s" col="%s" row="%s" aria-describedby="%s">%s</textarea>
	// 		<div id="%s" class="is_invalid-label%s">%s</div>',
	// 		$this->attribute,
	// 		$this->model->hasError($this->attribute) ? 'is-invalid-label' : '',
	// 		$this->model->labels()[$this->attribute] ?? ucfirst($this->attribute), 
	// 		$this->attribute,
	// 		$this->attribute,
	// 		'Enter text for ' . ucfirst($this->attribute),
	// 		'',
	// 		'4',
	// 		'error'.$this->attribute,
	// 		$this->model->{$this->attribute},
	// 		'error'.$this->attribute,
	// 		'',
	// 		$this->model->getFirstError($this->attribute)
	// 	);
	// }
}
