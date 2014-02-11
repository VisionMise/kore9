<?php

	class user extends ui_api {

		function __construct() {
			parent::__construct(__DIR__);
		}

		public function profile(	    array $param = array()	) {
			$this->getPage(array('profile'), $this->runtimeFolder, false);
		}

		public function character(	    array $param = array()	) {
			$this->getPage(array('character'), $this->runtimeFolder, false);
		}

		public function get(	    array $param = array()	) {}
		public function post(	    array $param = array()	) {}
		public function put(	    array $param = array()	) {}
		public function delete(	    array $param = array()	) {}

	}

?>