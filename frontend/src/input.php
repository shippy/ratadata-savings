<?php
namespace Inputs;

class Collection {
	private $session;
	private $inputs;
	
	public setSession($session) {
		$this->session = $session;
	}
	
	public setInput(\Inputs\Input $input) {
		$this->inputs[$input->getName] = $input;
	}
	
	public getInput($inputName) {
		return $this->inputs[$inputName];
	}
	
	public delInput($inputName) {
		if (isset($this->inputs[$inputName])) {
			unset($this->inputs[$inputName]);
			return true;
		} else {
			return false;
		}
	}
}

class Input {
	private $content, // user input
			$name, // input name
			$validation, // validation rule
			$why, // rationale for collecting datapoint
			$connections; // datasets referenced by this question
	
	function __construct($foo = null) {
		$this->foo = $foo;
	}
}

class Datapoint

?>