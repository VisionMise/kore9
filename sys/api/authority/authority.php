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
		    $email		= (isset($param['email'])) ? $param['email'] : null;
		    $password	= (isset($param['password'])) ? $param['password'] : null;
		    $token 		= $this->tokenHash($email, $password);
		    
		    $table 		= new table();
		    $data 		= $table->query("SELECT * FROM accounts WHERE token = '$token';");

		    
		    if (count($data) == 1) {
		    	$user		= $data[0];
		    	$gid 		= $user['group'];
				$grp 		= $table->query("SELECT * FROM groups WHERE id = $gid;");

				if (isset($grp[0]) and $grp[0]) {
					$grp 				= $grp[0];
					$user['group'] 		= $grp['name'];
					$user['group_id']	= $grp['id'];
				}

		    	$this->createAuthenticatedSession($user);
		    	$_SESSION['type'] 		= 'member';
		    	$_SESSION[$email]		= $user;
		    } else {
		    	$_SESSION['type'] 		= 'guest';
		    	$_SESSION['guest']		= array();
		    	$_SESSION['failed']		= true;
		    }

	    	return array(
	    		'reload'	=> (isset($_SERVER['HTTP_REFERER']) 
	    			? $_SERVER['HTTP_REFERER']
	    			: "./"
	    		),
	    		'session'	=> $token,
	    		'signed_in'	=> ($_SESSION['type'] != 'guest'),
	    		'username'	=> $user['username']
    		);	
		}

		public function put(	array $param = array()	) {
			return null;
		}

		public function delete(	array $param = array()	) {
			global $sessionId;
			if (isset($_SESSION[$sessionId])) unset($_SESSION[$sessionId]);
			session_unset();

			$_SESSION['type']   = 'guest';
			
			return array('reload'=>$_SERVER['HTTP_REFERER']);
		}
		
		public function signin() {
		    return $this->getPage(array('signin'), dirname(__FILE__));
		}
		
		public function register() {
		    return $this->getPage(array('register'), dirname(__FILE__));
		}

		private function createAuthenticatedSession(array $user) {
			global $mode;

	    	$table 		= new table();
	    	$dt 		= date("Y-m-d H:i:s");
	    	$logins		= (isset($user['logins'])) ?  ((int) $user['logins']) + 1 : 1;
	    	$token 		= $user['token'];
	    	$lastIp		= ($mode == 'shell') ? 'localhost' : $_SERVER['REMOTE_ADDR'];

		    $table->query("UPDATE accounts SET last_login = '$dt', logins = $logins, last_ip = '$lastIp' WHERE token = '$token';", true);
		}

		private function tokenHash($email, $password) {

			$parts	= array(
				0	=> md5($email),
				1	=> sha1($password),
				2	=> substr($password, 2),
				3 	=> substr($email, 2),
				4	=> strlen($email),
				5	=> strlen($password)
			);

			$strHash	= '_k9_';

			foreach ($parts as $partIndex => $part) {
				$strHash .= "<$partIndex>:$part;";
			}

			$token 		= md5(base64_encode($strHash));
			return $token;
		}

	}
	
?>
