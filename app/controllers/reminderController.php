<?php
/**
 * Controls the logic behind creating reminders
 */
class ReminderController {
    public function __construct() { }
	
	/**
	 * Deactivates a users reminder
	 * 
	 * AJAX call
	 */
	public function deactivateReminder() {
		global $reminder;
		global $sessionController;
		
		// Ensure that user is setting reminders for themselves
		$sessionController->verifyReminderId($_POST['reminder_id']);
		
		// Deactivate the reminder.
		$reminder->deactivateReminder($_POST['reminder_id']);
	}
	
	/**
	 * Activates a user's reminder
	 * 
	 * AJAX call
	 */
	public function activateReminder() {
		global $reminder;
		global $user;
		global $sessionController;
		
		// Ensure that user is setting reminders for themselves
		$sessionController->verifyReminderId($_POST['reminder_id']);
		

		// Check reminder limit for free users
		if ($_SESSION['pro_user'] == 0) {
			if ($user->readActiveReminders($args['userId']) + 1 > REMINDERLIMIT) return false;
		}
		
		// Activate the reminder
		$reminder->activateReminder($_POST['reminder_id']);
	}
	
	
	/**
	 * Removes a previously set reminder.
	 * 
	 */
	public function removeReminder() {
		global $reminder;
		global $sessionController;
		
		// Ensure that user is setting reminders for themselves
		$sessionController->verifyReminderId($_POST['reminder_id']);
		
		// Remove it
		$reminder->delete($_POST['reminder_id']);
	}
	
	
	/**
	 * Readies POST data and adds a new reminder.
	 * 
	 * Function takes an array of $_POST entries from the registration form.
	 * it translates the interval spcified by user to seconds and calculates
	 * the next push. Returns true on success false on failure.
	 * 
	 * @param array $args $_POST vars from registration form
	 */
	public function createReminder($args) {
		global $globals;
		global $reminder;
		global $reminderController;
		global $user;
		global $sessionController;

		// LCase Interval Text
		$args['interval'] = strtolower($args['interval']);
		
		// Ensure that user is setting reminders for themselves
		$sessionController->verifyUserId($args['userId']);
		
		// Keep reminder length under 140 chars
		if (strlen($args['reminder']) >= 140) return false;
		
		// Check reminder limit for free users
		if ($_SESSION['pro_user'] == 0) {
			if ($user->readActiveReminders($args['userId']) + 1 > REMINDERLIMIT) return false;
		}

		// Create strtotime friendly interval
		$interval = $reminderController->translateInterval($args['interval']);

		// Make sure user entered a valid interval.
		if ($interval == '') return false;
		
		// If the user provides a start date, we translate that to GMT time
		// Then, we set the nextPush var to the start date
		if (!empty($args['startingOn']) && $args['startingOn'] != 'Starting Now') {
			// Fix old PHP by parsing the date and adding the offset minuts by hand UGH			
			$startingOn = $this->addUserTimeOffset($args['startingOn']);
			$nextPush = $startingOn;
			
			//echo $args['startingOn'] . '<br />';
			//echo "Hour:$hour-Minue:$minute-Month:$month-Day:$day-Year:$year<br />";		
			//$timeFix = DateTime::createFromFormat('m#d#Y h#i a', $args['startingOn']);
			//$timeFix->modify('+' . $_SESSION['time_offset'] . ' minutes');

		// No start date provided, so we start the interval now!
		} else {
			$startingOn = date(SQLDATE, time());
			// Calculate next push
			$nextPush = $reminderController->calculateNextPush(strtotime($startingOn), $interval);
			$nextPush =  date(SQLDATE, $nextPush);
		}
		

		// Create the reminder
		if ($reminder->create($args['userId'], $interval, $args['interval'], $startingOn, $nextPush, $args['reminder'], '1')) {
			return true;	
		}

		return false;
	}
	
	private function addUserTimeOffset($datetime) {
		$timeArray = explode(' ', $datetime);
		$date = explode('/', $timeArray[0]);
		
		$hour = substr($timeArray[1], 0, 2);
		$minute = substr($timeArray[1], 3, 2);
		$ampm = $timeArray[2];
		
		$month = $date[0];
		$day = $date[1];
		$year = $date[2];
		
		if ($ampm == 'pm' && $hour != 12) $hour += 12;
		$minute += $_SESSION['time_offset'];
		
		$timestamp = mktime($hour,$minute,0,$month,$day, $year);
		return date(SQLDATE, $timestamp);
	}
	/**
	 * Translates user's interval from text to seconds
	 * 
	 * for example: Every 3 weeks becomes 2340000
	 * @param string $userInput the users input
	 * @return int the number of seconds implied by the user's query
	 */
	public function translateInterval($userInput) {
		global $wordToNumber;
		
		
		// Needs to begin with "Every"
		$userInput = ucwords($userInput);
		if (strpos($userInput, 'very ') != 1)	return 0;
		
		// remove every from user input
		$userInput = strtolower(str_replace('Every ', '', $userInput));
		
		if ($userInput == 'day')   return '+1 day';
		if ($userInput == 'week')  return '+1 week';
		if ($userInput == 'month') return '+1 month';
		if ($userInput == 'year')  return '+1 year';
		
		// Not as simple, parse the words, translate words to numbers
		// and calculates the number of seconds
		$space = strpos($userInput,' ');
		$numberOf = trim(substr($userInput,0, $space));
		$timePeriod  = trim(substr($userInput, $space));
		
		// Remove S of the end of time period if it exists (i.e. Weeks = Week)
		if (substr(strrev($timePeriod),0,1) == 's')
			$timePeriod = substr($timePeriod,0, strlen($timePeriod) -1);
		
		// Parse words into numbers
		if (!is_numeric($numberOf))
			$numberOf = $wordToNumber->parse($numberOf);

		// Return appropriate # of seconds
		if ($timePeriod == 'day')   return "+$numberOf day";
		if ($timePeriod == 'week')  return "+$numberOf week";
		if ($timePeriod== 'month')  return "+$numberOf month";
		if ($timePeriod == 'year')  return '+$numberOf year';
		
		return false;
	}
	
	/**
	 * Calculates date of next push.
	 * 
	 * Adjusts for leap year!
	 */
	public function calculateNextPush($startTime, $interval) {
		
		// OLD
		// if (empty($startTime)) $startTime = time();
		// return  strtotime($interval, $startTime);

		// If we are not checking a specific datetime, datetime=now
		$startTime = (empty($startTime)) ? time() : $startTime;

		// Check for a timestamp otherwise translate strtotime()
		$startTime = (is_numeric($startTime)) ? $startTime : strtotime($startTime);
		$startTime = strtotime($interval, $startTime);
		
		return $startTime;
	}
	
	/**
	 * Processes reminders that need to be sent
	 * 
	 * Should be called by cron script ~5 minutes
	 * updates reminders with the new time
	 */
	public function processCron() {
		global $reminder;
		global $user;
		
		// Grab the overdue reminders from the database		
		$reminderResult = $reminder->readCronReminders();
		if ($reminderResult->num_rows <= 0) return false;
		
		// Loop through reminders and send text messages and update database
		while ($result = $reminderResult->fetch_assoc()) {
			
			// Send the message
			send_sms($user->readPhoneEmail($result['user_id']), $result['reminder_message']) ;
			
			// Calculate the next push
			$nextPush = date(SQLDATE, $this->calculateNextPush($result['next_push'], $result['interval']));
			
			// Calculate the next push OLD
			//$nextPush = date(SQLDATE, $this->calculateNextPush('', $result['interval']));
			

			// Update the database with the next push and last push info
			$reminder->updateLastPush($result['reminder_id'], $nextPush);
			
			
		}
		
		return;
	}
}

$reminderController = new ReminderController();
?>