<?php namespace Core\Conf\Kyaaaa;

class CSRF {
	private $id_length = 10;

	/**
	 * @param boolean $deleteExpiredTokens
	 * @return void
	 */
	public function __construct(bool $deleteExpiredTokens = false) {
		if (!isset($_SESSION["security"]["csrf"])) {
			$_SESSION["security"]["csrf"] = [];
		}
		if($deleteExpiredTokens) {
			foreach ($_SESSION["security"]["csrf"] as $key => $value) {
				if(isset($_SESSION["security"]["csrf"])) {
					if(@$_SESSION["security"]["csrf"][$key]) {
						if(time() >= $_SESSION["security"]["csrf"][$key]["time"] && $_SESSION["security"]["csrf"][$key]["token"]) {
							$this->delete($key);
						}
					}
				}
			}
		}
	}

	/**
	 * @param int $time
	 * @param int $length
	 * @return array
	*/
	public function generate(int $time = 3600, int $length = 36) {
		if(isset($_SESSION["security"]["csrf"])) {
			$token_id = "csrf_".$this->random($length);
            //$token_id = hash('sha256', $this->random(1337));
			$token = $this->set_token();
			$_SESSION["security"]["csrf"][$token_id] = array("token" => $token, "time" => (time()+$time));
			return array("key" => $token_id, "token" => $token);
		}
	}

    /**
     * @return array
     */

    public function get() {
        return $_SESSION["security"]["csrf"];
    }

    /**
     * @return array
     */

    public function get_last() {
        return [
            "key" => key(array_slice($_SESSION["security"]["csrf"], -1, 1, true)),
            "token" => $_SESSION["security"]["csrf"][key(array_slice($_SESSION["security"]["csrf"], -1, 1, true))]["token"],
            "time" => $_SESSION["security"]["csrf"][key(array_slice($_SESSION["security"]["csrf"], -1, 1, true))]["time"]
        ];
        
        //return @end($_SESSION["security"]["csrf"]);
    }

	/**
	 * @return string
	*/
	public function set_token() {
		if(isset($_SESSION["security"]["csrf"])) {
			$token = base64_encode(hash('sha256', $this->random(500)));
			return $token;
		}
	}

	/**
	 * @param array $array
	 * @return boolean
	*/
	public function check_valid(array $array) {
		foreach ($array as $key => $value) {
			if(isset($_SESSION["security"]["csrf"])) {
				if(@$_SESSION["security"]["csrf"][$key]) {
					if(time() <= $_SESSION["security"]["csrf"][$key]["time"] && $_SESSION["security"]["csrf"][$key]["token"] == $value) {
						return true;
					}
					$this->delete($key);
					return false;
				}
				return false;
			}
			return false;
		}
		return false;
	}

	/**
	 * @param string $key
	 * @return void
	*/
	public function delete(string $key) {
		if(isset($_SESSION["security"]["csrf"])) {
			if(@$_SESSION["security"]["csrf"][$key]) {
				unset($_SESSION["security"]["csrf"][$key]);
			}
		}
	}

	/**
	 * @param int $len
	 * @return string
	*/
	private function random(int $len) {
        if (function_exists('openssl_random_pseudo_bytes')) {
                $byteLen = intval(($len / 2) + 1);
                $return = substr(bin2hex(openssl_random_pseudo_bytes($byteLen)), 0, $len);
        } elseif (@is_readable('/dev/urandom')) {
                $f=fopen('/dev/urandom', 'r');
                $urandom=fread($f, $len);
                fclose($f);
                $return = '';
        }
        if (empty($return)) {
                for ($i=0;$i<$len;++$i) {
                        if (!isset($urandom)) {
                                if ($i%2==0) {
                                             mt_srand(time()%2147 * 1000000 + (double)microtime() * 1000000);
                                }
                                $rand=48+mt_rand()%64;
                        } else {
                                $rand=48+ord($urandom[$i])%64;
                        }
                        if ($rand>57)
                                $rand+=7;
                        if ($rand>90)
                                $rand+=6;
 
                        if ($rand==123) $rand=52;
                        if ($rand==124) $rand=53;
                        $return.=chr($rand);
                }
        }
        return $return;
	}
}

?>
