<?php
namespace App\Core\Form;
use App\Core\Model;

abstract class BaseField {
	abstract public function renderInput() : string ;

	public Model $model;
	public string $attribute;

	public function __construct(Model $model, string $attribute) {
		$this->model = $model;
		$this->attribute = $attribute;
	}


	public function __toString() {
		return sprintf(
			'
			<label for="%s" class="%s">%s</label>
			%s
			<div id="%s" class="is-invalid-label help-text">%s</div>
			',
			$this->attribute,
			$this->model->hasError($this->attribute) ? 'is-invalid-label' : '',
			$this->model->labels()[$this->attribute] ?? ucfirst($this->attribute),
			$this->renderInput(),
			'error' . $this->attribute,
			$this->model->getFirstError($this->attribute)
		);
	}

}