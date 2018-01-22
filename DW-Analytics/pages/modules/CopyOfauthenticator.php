<?php 
    /* Init parse connection */
    require_once ($_SERVER["DOCUMENT_ROOT"] ."/pages/modules/parseInit.php");
    /* Included namespaces */
    use Parse\ParseObject;
    use Parse\ParseQuery;
    use Parse\ParseUser;
    use Parse\ParseFile;
    use Parse\ParseException;

    class Authenticator {
        /* Functions and Variables for local Authentication */
    	private $rand_key;
        private $current_user;
        private $error_message;
        private $sitename;
    	//-----Initialization -------
        function Authenticator() {
            $this->sitename = 'beepcms.com';
            $this->rand_key = '0iQx5oBk66oVZep';
        }
        function SetRandomKey($key) {
            $this->rand_key = $key;
        }
        function SetSiteName($name) {
            $this->sitename = $name;
        }
        function UserFullName() {
            return isset($_SESSION['name_of_user'])?$_SESSION['name_of_user']:'';
        }
        function UserFirstName() {
            return isset($_SESSION['first_name'])?$_SESSION['first_name']:'';
        }
        function UserLastName() {
            return isset($_SESSION['last_name'])?$_SESSION['last_name']:'';
        }
        function UserEmail() {
            return isset($_SESSION['email_of_user'])?$_SESSION['email_of_user']:'';
        }
        public function getUserInfo() {
            return $this->current_user;
        }
    	public function RedirectToURL($url) {
            header("Location: $url"); 
            exit;
        }
        public function Login() {
            $isLogin = false;
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            if(!isset($_SESSION)){ session_start(); }
            $_SESSION[$this->GetLoginSessionVar()] = $username;
            $_SESSION['username'] = $username;
            try {
                $user = ParseUser::logIn($username, $password);
                $_SESSION['userID'] = $user->getObjectId();
                $_SESSION['userEmail'] = $user->getEmail();
                $isLogin = true;
            } catch (ParseException $ex) {
                 if($ex->getCode() == '101') {
                    $this->HandleError('Incorrect Username or Password');
                 }
            }
            return $isLogin;    
        }
        public function CheckLogin() { 
             if(!isset($_SESSION)){ session_start(); }
             $sessionvar = $this->GetLoginSessionVar(); 
             if($_SESSION[$sessionvar] == NULL) {
                return false;
             }
             return true;
        }
        public function LogOut() {
            session_start();
            $sessionvar = $this->GetLoginSessionVar();
            $_SESSION[$sessionvar]=NULL;
            unset($_SESSION[$sessionvar]);
            $_SESSION['eventId'] = NULL;
            ParseUser::logOut();
        }
        public function GetLoginSessionVar() {
            $retvar = md5($this->rand_key);
            $retvar = 'usr_'.substr($retvar,0,10);
            return $retvar;
        }
        function HandleError($err) {
            $this->error_message .= $err."\r\n";
        }
        function getErrorMessage() {
            if(empty($this->error_message)) {
                return '';
            }
            $errormsg = nl2br(htmlentities($this->error_message));
            return $errormsg;
        }
        /* Parse Integration functions*/
        private function isUsernameTaken($name) {
            $query = ParseUser::query();
            $query->equalTo("username", $name);
            return ($query->count());
        }

        public function submitNewUser($name, $company, $email, $phone, $username, $pass1, $pass2, $logo) {
            if($this->isUsernameTaken($username)) {
               $this->HandleError("Username is taken");
               return $this->getErrorMessage();
            } else {
               $user = new ParseUser();
               $company_user = new ParseObject("Company");
               $user->set("name", $name);
               $user->set("company", $company);
               $user->set("email", $email);
               $user->set("phoneNumber", $phone);
               $user->set("username", $username);
               $user->set("password", $pass1);
               if($logo != null) 
                    $file = ParseFile::createFromData(file_get_contents($logo['tmp_name']), $logo['name']);
               $company_user->set("name", $company);
               try {
                    if($logo != null) {
                        $file->save();
                        $company_user->set("logo", $file);
                    }
                    $user->signUp();
                    $company_user->save();
               } catch (ParseException $ex) {
                    echo ($ex->getCode() . ' ' . $ex->getMessage());
               }
            }
        }
        
        public function submitNewExhibitorUser($name, $company, $username, $pass1, $pass2, $logo) {
        	if($this->isUsernameTaken($username)) {
        		$this->HandleError("Username is taken");
        		return $this->getErrorMessage();
        	} else {
        		
        		$query = ParseUser::query();
        		$query->equalTo("objectId", $_SESSION['reg_user_id']);
			    $query->limit(1);
			    $user = $query->first();
			    
			    $roles_array = array();
			    $roles_array = $user->get("roles");
			    
			    $isExhRoleSet = false;
			    
			    foreach ($roles_array as $role) {
			    	if ($role == 'e') {
			    	    $isExhRoleSet = true;	
			    	    break;
			    	}
			    }
			    
			    if (!$isExhRoleSet) {
			    	array_push($roles_array, "e");
			    	$user->setArray("roles", $roles_array);
			    }
        		
        		$company_user = new ParseObject("Company");
        		$user->set("name", $name);
        		$user->set("company", $company);
        		$user->set("username", $username);
        		$user->set("password", $pass1);
        		$user->set("isVerified", true);
        		
        		//if($logo != null)
        		//$file = ParseFile::createFromData(file_get_contents($logo['tmp_name']), $logo['name']);
        		$company_user->set("name", $company);
        		
        		try {
        			//if($logo != null) {
        				//$file->save();
        				//$company_user->set("logo", $file);
        			//}
        			//$user->signUp();
        			$user->save(true);
        			$company_user->save();
        		} catch (ParseException $ex) {
        			echo ($ex->getCode() . ' ' . $ex->getMessage());
        		}  		
        	}
        }
        
        public function isEventCode() {
            $regex = '/\/(.+).html+/';
            preg_match($regex, $_SERVER["REQUEST_URI"], $matches);
            if($matches){
                $uri = ($matches[1]); $_SESSION['eventCode'] = $matches[1];
            } else {
                $regex = '/\/(.+)/';
                preg_match($regex, $_SERVER["REQUEST_URI"], $matches);
                $uri = ($matches[1]); $_SESSION['eventCode'] = $matches[1];
            }
            $query = new ParseQuery("Event");
            $query->limit(1);
            $query->containedIn("eventCode", [$uri]);
            return ($query->count() > 0);
        }
    }