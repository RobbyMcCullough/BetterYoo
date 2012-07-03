<?php
/**
 * Controls the user's control panel.
 * 
 * Processes updates, removal, addition of reminders
 * by a user.
 */
class UserController {
    public function __construct() { }
	
	/**
	 * Generates a user's control panel page
	 * 
	 * @param string $phoneNumber the users phone number
	 */
	public function makeUserPage($phoneNumber) {
		global $reminder;
		global $globals;
		global $view;
		global $user;
		global $reminderController;
		global $tmpl;
		
		// Fetch User Information from Phone Number
		$userResult = $user->readUserInfo($phoneNumber);
		if (is_object($userResult)) {
			while ($row = $userResult->fetch_assoc()) {
				foreach ($row as $key => $data) {
                    $tmpl[$key] = $view->xss_clean($data);
                }
			}
		} else {
			$view->errorPage('Error Fetching User Info');
		}
		
		// Fetch Reminder Information from User Id
		$reminderResult = $reminder->readUserReminders($tmpl['id']);
		if (is_object($reminderResult)) {
			while ($row = $reminderResult->fetch_assoc()) {
				foreach ($row as $key => $data) {
                    $tmpl[$key][] = $view->xss_clean($data);
                }
			}
		} else {
			$view->errorPage('Error Fetching Reminders');
		}
		
		
		// Calculate next interval
		for ($i=0;$i< count($tmpl['next_push']); $i++) {

			// Fix old PHP by parsing the date and adding the offset minuts by hand UGH		
			$timeArray = explode(' ', $tmpl['next_push'][$i]);
			$date = explode('-', $timeArray[0]);
			
			$hour = substr($timeArray[1], 0, 2);
			$minute = substr($timeArray[1], 3, 2);
			$ampm = $timeArray[2];
			
			$month = $date[1];
			$day = $date[2];
			$year = $date[0];
			
			if ($ampm == 'pm') $hour += 12;
			$minute = $minute - $_SESSION['time_offset'];

			$timestamp = mktime($hour,$minute,0,$month,$day, $year);
			$tmpl['next_push'][$i] = date(ENGLISHDATE, $timestamp);
						
			// $timeFix = DateTime::createFromFormat('m#d#Y h#i a', $args['startingOn']);
			// $timeFix->modify('+' . $_SESSION['time_offset'] . ' minutes');
			//$timeFix = date_create_from_format(SQLDATE, $tmpl['next_push'][$i]);
			//$timeFix->modify('-' . $_SESSION['time_offset'] . ' minutes');
			//$tmpl['next_push'][$i] = $timeFix->format(ENGLISHDATE);
		}
		
		// Clean up the phone number
		$tmpl['phone_number'] = $view->xss_clean($phoneNumber);
		// Set the english carrier name based on id
		$tmpl['carrier'] = $globals['carrierNames'][$tmpl['carrier_id']];
		// Check for e-mail address, set as 'unset' if unavailable
		$tmpl['email'] = (empty($tmpl['email'])) ? 'Empty' : $tmpl['email'];
		
		// Check user limit
		$tmpl['reminder_limit'] = REMINDERLIMIT;
		if ($_SESSION['pro_user'] == 1) $tmpl['reminder_limit'] = 'Unlimited';
		$tmpl['pro_user'] = $_SESSION['pro_user'];
		// Render the page
		$view->createPage('user', $tmpl);
	}
	
	/**
	 *  Removes non-numeric characters from a phone number
	 * 
	 * @param string $phoneNumber the user's phone number
	 * @return int users non-alpha phone number
	 */
	public function cleanPhoneNumber($phoneNumber) {
		// Remove 1 from the beginning of phonenumber
		if (substr($phoneNumber, 0 ,1) == '1') $phoneNumber = substr($phoneNumber, 1);
		// Strip non-numeric chars
		return preg_replace("/[^0-9]/",'', $phoneNumber);
	}
	
	/**
	 * Upgrades an account to pro level
	 */
	public function upgradeAccount($args) {
		global $user;
		global $sessionController;
		
		// PintPay API key check that requests come from PintPat
		$apiKey = 'a1bfc8e20972fc6bd700cb72fcf58797';
		$json = $args['json'];
		if (empty($json)) return false;
		
		// Grab pertinent JSON data
		$json = json_decode($json);
		$key =  $json->api_key;
		$uid = $user->readUserId($json->identifier);
		
		// If key matches and we find user from the phonenumber - upgrade the account
		if ($key == $apiKey && $uid != '') {
			$user->upgradeAccount($uid);
			$sessionController->upgradeAccount();
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Updates a users account settings
	 * 
	 * Processes POST data and updates a user's password &OR carrier
	 */
	public function updateSettings($args) {
		global $user;
		global $globals;
		
		// Update E-Mail
		if (isset($_POST['email'])) {
			// Validate email before updating database
			if (isValidEmail($_POST['email'])) {
				$user->update($_SESSION['uid'], array('email' => $_POST['email']));
			}
		}
		
		// Update Carrier
		if (isset($_POST['carrier'])) {
			for($i=0;$i<count($globals['carrierNames']);$i++) {
				// Grab carrier ID to update database
				if (strtolower($_POST['carrier']) == strtolower($globals['carrierNames'][$i])) {
					$user->update($_SESSION['uid'], array('carrier_id' => $i));
				}
			}
		}
		
		// Update Password
		if (isset($_POST['oldPassword']) && isset($_POST['newPassword']) && isset($_POST['newPassword2'])) {
			// Salt up passwords, and then verify old password before changing	
			$oldPassword = sha1($_POST['oldPassword'] . SALT);
			$dbPassword = $user->readUserPassword($_SESSION['uid']);
			
			// if old password doesn't match abort
			if ($oldPassword !=  $dbPassword) return false;
			
			// if new passwords don't match (typo) return
			if ($_POST['newPassword'] != $_POST['newPassword2']) return false;
			
			// Update database with salted password
			$newPassword = sha1($_POST['newPassword'] . SALT);
			$user->update($_SESSION['uid'], array('password' => $newPassword));
		}
	}
}

$userController = new UserController();
?>