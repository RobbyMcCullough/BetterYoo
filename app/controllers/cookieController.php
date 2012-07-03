<?php

/**
 * Controls cookies
 * 
 * Handles the setting and removal of login cookies.
 */
class CookieController {
	public $cookieTime;
	
	// Initate Cookie Time variable
    public function __construct() {
    	$this->cookieTime = time() + SECONDS_MONTH;
    }
	
	/**
	 * Sets a login cookie
	 * 
	 * @param string $phoneNumber the user's phone number
	 * @param string $passWord the user's encrypted password
	 */
	public function setCookie($phoneNumber, $password) {
		global $user;
		
		$loginHash = sha1($phoneNumber . $password . $_SERVER['REMOTE_ADDR']);
		
		setcookie ('phone', $phoneNumber, $this->cookieTime, '/');
		setcookie ('login', $loginHash, $this->cookieTime, '/'); 
	}
	
	/**
	 * Removes a login cookie
	 * 
	 * this is part of the logout process
	 */
	public function clearCookie() {
		setcookie ('phone', '', time() - 2600, '/');
		setcookie ('login', '', time() - 2600, '/'); 
	}
}

$cookieController = new CookieController();
?>