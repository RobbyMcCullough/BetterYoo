<?php

/**
 * Controls sessions and login/logout function
 */
class SessionController {
    public function __construct() { }
	
	/**
	 * Start PHP Sessions
	 * 
	 * called in config.php
	 */
	public function start() {
		session_start();
		
		// if session isn't already active, set the default values.
		if (!isset($_SESSION['uid'])) {
			$this->setDefaults();
		}
	}
	
	/**
	 * Upgrade User Account
	 */
	public function  upgradeAccount() {
		$_SESSION['pro_user'] = 1;
	}
	
	
	/**
	 * Instantiates Session Variables
	 */
	private function startSession($uid, $phoneNumber, $timeOffset) {
		global $user;
		
		// Number of active reminders
		$_SESSION['active_reminder'] = $user->readActiveReminders($uid);
		
		// Reminder limit
		$_SESSION['pro_user']  = $user->readProUser($uid);
		
		// Logged in = true
		$_SESSION['logged'] = true;
		
		// User ID
		$_SESSION['uid']    = $uid;
		
		// User phonenumber
		$_SESSION['phone']  = $phoneNumber;
		
		// User Time Offset
		$_SESSION['time_offset'] = $timeOffset;
	}
	
	/**
	 * Log User In
	 * 
	 * Checks users password and creates session if true
	 * @param string $phoneNumber the users phone number (
	 * @param string $passWord the users password
	 */
	public function logIn($phoneNumber, $passWord) {
		global $user;
		global $userController;
		global $cookieController;
		
		// Remove non-numeric chars
		$phoneNumber = $userController->cleanPhoneNumber($phoneNumber);
		
		// Get the user's ID
		$uid = $user->readUserId($phoneNumber);
		
		// Grab SHA1 Password from the ID
		$dbPassword = $user->readUserPassword($uid);
		
		// User's time offset
		$timeOffset = $user->readUserTimeOffset($uid);
		
		// If password check is OK return true other wise return false!
		if ($dbPassword == sha1($passWord . SALT)) {
			$cookieController->setCookie($phoneNumber, $dbPassword);	
			
			// Instantiate session variables
			$this->startSession($uid, $phoneNumber, $timeOffset);
			return true;
		} else {
			return false;
		}
		
	}
	
		/**
	 * Log User In From A cookie
	 * 
	 * Checks users password and creates session if true
	 * @param string $phoneNumber the users phone number (
	 * @param string $passWord the users password
	 */
	public function cookieLogIn($phoneNumber, $loginHash) {
		global $user;
		global $userController;
		
		// Clean up the phone number
		$phoneNumber = $userController->cleanPhoneNumber($phoneNumber);
		
		// Get the users ID
		$uid = $user->readUserId($phoneNumber);
		
		// User's time offset
		$timeOffset = $user->readUserTimeOffset($uid);
		
		// Grab the SHA1 password by the user ID
		$dbPassword = $user->readUserPassword($uid);
		
		// Create a cookie login hash: sha1(Phone Number + Sha1(password) + IP Address)
		$dbLoginHash = sha1($phoneNumber . $dbPassword . $_SERVER['REMOTE_ADDR']);
		
		// Check the login hash we created against the cookie
		if ($dbLoginHash == $loginHash) {
			
			// Success, generated login hash matches the cookie. So, set session vars.
			$this->startSession($uid, $phoneNumber, $timeOffset);
			return true;
		} else {
			
			// Failure, do nothing
			return false;
		}
	}
	
	
	/**
	 * Logs User Out
	 * 
	 * Clears php session for user logout.
	 */
	public function logOut() {
		global $cookieController;
		
		// Unset user-specific session vars
		$this->setDefaults();
		
		// Clear the login cookie
		$cookieController->clearCookie();
	}
	
	
	/**
	 * Verifies that a user's ID matches the logged in user
	 * 
	 * this prevents anyone from trying to create POST requests
	 * that effect other user's data. Creates an error on fail.
	 * 
	 * @param int $userId the ID of the user
	 * @return boolean
	 */
	public function verifyUserId($userId) {
		global $db;
		global $view;
		
		// Checks for session hijacking
		if ($_SESSION['uid'] != $userId) {
			$view->errorPage('I appologize, something has gone terribly wrong.');
			die();
		} else {
			return true;
		}
	}
	
	
	/**
	 * Verifies that a reminder ID matches the logged in user
	 * 
	 * this prevents anyone from trying to create POST requests
	 * that effect other user's data. Creates an error on fail.
	 * 
	 * @param int $reminderId the ID of the reminder
	 * @return boolean
	 */
	public function verifyReminderId($reminderId) {
		global $db;
		global $view;
		
		// Grab the reminder ID then check it agains the session uid
		$queryStr = sprintf(
			"SELECT `user_id` FROM `reminders` WHERE `reminder_id` =%s",
			$db->real_escape_string($reminderId)
		);
		
		$dbResult = $db->query($queryStr);
		$dbResult = $dbResult->fetch_assoc();
		
		if ($_SESSION['uid'] != $dbResult['user_id']) {
			$view->errorPage('I appologize, something has gone terribly wrong.');
			die();
		} else {
			return true;
		}
	}
	
	/**
	 * Resets or sets sessions to default values
	 */
	private function setDefaults() {
		$_SESSION['active_reminder'] = 0;
		$_SESSION['pro_user']        = 0;
		$_SESSION['logged']          = false;
		$_SESSION['uid']             = 0;
		$_SESSION['phone']           = '';
		$_SESSION['cookie']          = 0;
		$_SESSION['remember']        = false;
		$_SESSION['time_offset']     = 0;
	}
	
}

$sessionController = new SessionController();
?>