<?php

namespace App\Core\Form;

use App\Core\Model;

class TextareaField extends BaseField {

	public const AUTO_FOCUS = 'autofocus';
	public const DISABLED = 'disabled';
	public const READONLY = 'readonly';
	public const REQUIRED = 'required';
	public const WRAP_HARD = 'hard';
	public const WRAP_SOFT = 'soft';

	// public string $element;

	public function __construct(Model $model, string $attribute) { // string $element
		// $this->element = $element;
		parent::__construct($model, $attribute);
	}

	public function __toString() {
		return sprintf(
			/*html*/
			'<label for="%s" class="%s">%s</label>
			%s
			<div id="%s" class="%s">%s</div>',
			$this->attribute,
			$this->model->hasError($this->attribute) ? 'is-invalid-label' : '',
			$this->model->labels()[$this->attribute] ?? ucfirst($this->attribute),
			$this->renderInput(),
			'error' . $this->attribute,
			$this->model->hasError($this->attribute) ? 'help-text is_invalid-label' : 'help-text',
			$this->model->getFirstError($this->attribute)
		);
	}

	public function renderInput(): string {
		return sprintf('<textarea name="%s" id="%s" title="%s" col="%s" row="%s" aria-describedby="%s">%s</textarea>',			$this->attribute,
			$this->attribute,
			'Enter text for ' . ucfirst($this->attribute),
			'',
			'4',
			'error' . $this->attribute,
			$this->model->{$this->attribute}
		);
	}

	// private function buildElementString() {
	// 	if (!empty($this->element)) {
	// 	}
	// }

	// private function buildElementValue() {
	// 	if (!empty($this->element)) {
	// 	}
	// }
}
