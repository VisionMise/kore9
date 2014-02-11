<?php

	class character extends ui_api {

		function __construct() {
			parent::__construct(__DIR__);
		}

		public function inventory(	array $param = array()	) {
			$this->getPage(array('inventory'), $this->runtimeFolder, false);
		}

		public function skills(	    array $param = array()	) {
			$this->getPage(array('skills'), $this->runtimeFolder, false);
		}

		public function achievements(array $param = array()	) {
			$this->getPage(array('achievements'), $this->runtimeFolder, false);
		}

		public function get(	    array $param = array()	) {
			$this->getPage(array('character'), $this->runtimeFolder, false);
		}

		public function post(	    array $param = array()	) {}
		public function put(	    array $param = array()	) {}
		public function delete(	    array $param = array()	) {}

	}

?>