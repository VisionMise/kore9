<?php

    class authority extends ui_api {
        
        public function __construct() {
            parent::__construct(__DIR__);
        }

		public function get(	array $param = array()	) {
		    if ($param) {
		        $method         = (isset($param[0])) ? $param[0] : null;
		        
		        if (method_exists($this, $method)) {
		            unset($param[0]);
		            $result     = $this->$method(array_values($param));
		            $this->json = true;
		        } else {
		            $result         = (isset($_SESSION['type'])) ? $_SESSION['type'] : false;
		        }
		    } else {
		        $result         = (isset($_SESSION['type'])) ? $_SESSION['type'] : false;
		    }
		    
			return $result;
		}

		public function post(	array $param = array()	) {
		    $_SESSION['type'] = 'member';
			return array('reload'=>(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : './'), $param);
		}

		public function put(	array $param = array()	) {
			return null;
		}

		public function delete(	array $param = array()	) {
			$_SESSION['type']   = 'guest';
			return array('reload'=>$_SERVER['HTTP_REFERER']);
		}
		
		public function signin() {
		    return $this->getPage(array('signin'), dirname(__FILE__));
		}
		
		public function register() {
		    return $this->getPage(array('register'), dirname(__FILE__));
		}

	}
	
?>
