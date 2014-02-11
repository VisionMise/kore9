<?php

	class world extends ui_api {

		function __construct() {
			parent::__construct(__DIR__);
		}

		public function map(	    array $param = array()	) {
			$this->getPage(array('map'), $this->runtimeFolder, false);
		}

		public function get(	    array $param = array()	) {}
		public function post(	    array $param = array()	) {}
		public function put(	    array $param = array()	) {}
		public function delete(	    array $param = array()	) {}

	}

?>