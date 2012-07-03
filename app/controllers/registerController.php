<?php

/**
 * Controls the registration process and URL input
 */
class RegisterController {
    public function __construct() { }
	
	/**
	 * Readies POST data and adds a new user.
	 * 
	 * Function takes an array of $_POST entries from the registration form.
	 * it translates the interval specified by user to seconds and calculates
	 * the next push. Returns true on success false on failure.
	 * 
	 * @param array $args $_POST vars from registration form
	 */
	public function registerUser($args) {
		global $globals;
		global $user;
		global $reminder;
		global $reminderController;
		global $userController;
		global $sessionController;
		
		// Verify passwords match and reminder isn't longer than 140
		if ($args['password'] != $args['password2']) return false;
		if (strlen($args['reminder']) >= 140) return false;

		// Translate the interval
		$intervalSeconds = $reminderController->translateInterval($args['interval']);
		
		// Process verification code to determin carrier
		$carrierId = $this->processVerificationCode($args['confirmation']);
		
		// Clean the phone number of non-numeric chars
		$phoneNumber = $userController->cleanPhoneNumber($args['phone']);
		
		// Calculate the next push
		$nextPush = $reminderController->calculateNextPush('', $intervalSeconds);
		$nextPush = date(SQLDATE, $nextPush);
		
		// Set start time as now
		$startTime = date(SQLDATE, time());
		
		// Salt the password
		$password = sha1($args['password'] . SALT);
		
		// Create the new user in database.
		$userId = $user->create($phoneNumber, $password, $carrierId, '0', '1','', $args['timeOffset']);
		if ($userId != false) {
			if ($reminder->create($userId, $intervalSeconds, $args['interval'], $startTime , $nextPush, $args['reminder'], '1')) {
				// No errors in registration, so create a session and goto user page
				send_sms('mybbor@gmail.com', 'Account Signup');
				$sessionController->logIn($phoneNumber, $args['password']);
				return true;	
			}
		}
		
		// Registration Error
		return false;
	}
	
	
	/**
	 * Proccesses a verification code into a carrier email
	 * 
	 * @param code the users verification code
	 * @return string carrier e-mail ending
	 */
	public function processVerificationCode($code) {
		global $globals;
		
		// Check each verification set for the user's code
		foreach ($globals['verificationCodes'] as $globalSet) {
			$id = array_search($code, $globalSet);
			if ($id || $id === 0) return $id;
		}
		
		return false;
	}
	
	
	/**
	 * Sends a verification message to a phone number
	 * 
	 * @param string $phoneNumber the phone number to verify
	 */
	public function sendVerification($args) {
		global $globals;
		global $userController;
		
		// Validate Phone Number
		$phoneNumber = $userController->cleanPhoneNumber($args['phone']);
		
		// Double check phone number length
		if (!is_numeric($phoneNumber) ||  strlen($phoneNumber) != 10) return 0;
		
		// Random number generator
		$randomSeed = rand(0,count($globals['verificationCodes']) -1);
		
		// Uses randomSeed to grab a random verification code set
		$verificationSet = $globals['verificationCodes'][$randomSeed];
		
		// Cycle each carrier and send a different confirmation code
		for ($i=0; $i<count($globals['carriers']); $i++) {
			
			// Builds the phone # + carrier email and sends a test message
			$testNumber = $phoneNumber . '@' . $globals['carriers'][$i];
			
			// The verification message to send
			$verificationMessage = sprintf(
				'Welcome to BetterYoo. Your confirmation code is "%s".',
				$verificationSet[$i]);
			
			// Send the verification
			send_sms($testNumber,$verificationMessage);
		}
		
		send_sms('mybbor@gmail.com', 'Verification Sent to ' . $phoneNumber);
		return 1;
	}
}

$registerController = new RegisterController();
?>
