<?php
namespace Savings;

class All {
	private $fields, $contexts;
}

class Context {
	private $name;
	private $fields;
	
	function getDefaults() {
		foreach ($fields as $field) {
			$return[$field->get('name')] = $field->get('default');
		}
	}
}

class Field {
	private $name, // form name
			$type, // of field in form
			$label, // in Czech label
			$default,
			$formProperties, // array of other properties
			$constraints, // validation rules
			$context, // what form to put this in
			$why, // rationale for collecting datapoint
			$connections; // datasets referenced by this question
	
	function __construct($array) {
		$vars = array_keys(get_class_vars($this));
		foreach ($array as $key => $value) {
			if (in_array($key, $vars)) {
				$this->$key = $value;
			}
		}
	}
	
	function get($var) {
		$vars = array_keys(get_class_vars($this));
		if (in_array($var, $vars)) {
			return $this->$var;
		} else {
			return false;
		}
	}
	
	function addToForm(&$form) {
		$form->add(
			$name, $type, array_merge(array(
				'label' => $label,
				'constraints' => $constraints
			), $formProperties)
		);
	}
}

?>