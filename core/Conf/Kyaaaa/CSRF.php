<?php namespace Core\Conf\Kyaaaa;

class CSRF {
	private $token_expiry=7200;
	private $field_name='kyaaaaaaaKyaaa_csrf';
	private $token_key='$tui*&_-lpnwj%';


	public function __construct(){
		$this->Init();
	}

	public function setExpiry($expiry){
		$this->token_expiry=$expiry;
	}

	public function setTokenName($tokenname){
		$this->field_name=$tokenname;
	}

	public function setTokenKey($key){
		$this->token_key=$key;
	}

	public function isValidRequest(){
		if (empty($_POST[$this->field_name]) || empty($_SESSION['csrf']['token'])) {
			return false;
		}
		if(!hash_equals($_POST[$this->field_name],$_SESSION['csrf']['token']) || $_SESSION['csrf']['expiry'] < time()){
			return false;
		} else {
			return true;
		}
	}

	public function tokenField(){
		$token=$this->field_name;
		$token_value=$_SESSION['csrf']['token'];
		$html="<input type='hidden' name='$token' value='$token_value'>";
		echo $html;
	}

	private function Init(){
		if(array_key_exists('csrf',$_SESSION)){
			if(empty($_SESSION['csrf']['token']) || $_SESSION['csrf']['expiry'] < time()){
				$this->generateToken();
			}
		}
		else {
			$this->generateToken();
		}
	}

	protected function generateToken() {
		$token=hash_hmac('sha256',session_id().time(),$this->token_key);
		$token=substr($token,0,32);
		$_SESSION['csrf']=array(
			'token' => $token ,
			'expiry' => time() + $this->token_expiry
		);
	}
}