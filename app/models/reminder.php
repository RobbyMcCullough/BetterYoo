<?php

/**
 * CRUD functions for Reminder/Chron database tables
 * 
 * Simultaneously updates Reminder Table and Chron Table
 */
class Reminder {
    public function __construct() { }
	
	
	/**
	 * Deletes a previously set reminder from the database
	 * 
	 * @param int $reminderId the reminders ID
	 * @return boolean whether or not the process was sucessful
	 */
	public function delete($reminderId) {
		global $db;
		global $user;
		
		$isActive = $this->readIsActive($reminderId);
		
		$queryStr = sprintf(
			"DELETE FROM `reminders` WHERE `reminder_id` = %s",
			$db->real_escape_string(($reminderId))
		);
		
		if ($db->query($queryStr)) {
			if ($isActive == 1) $user->reduceActiveReminders($_SESSION['uid']);	
			return true;
		} else {
			return false;
		}
	}
	
	
		
	/**
	 * Updates a reminders information
	 * 
	 * @param integer $reminderId the user's ID to modify
	 * @param array $updates an array containing key => values to update
	 * @return boolean whether the update was successful
	 */
	public function update($reminderId, $updates) {
		global $db;
		
		// Escape all values & split keys and values
		$values = array_map(array($db,'real_escape_string'), array_values($updates));
    	$keys = array_keys($updates);
		
		// Build the query
		$queryStr = 'UPDATE `reminders` SET ';
		for ($i=0; $i<count($keys);$i++) {
			$queryStr .= "`$keys[$i]` = '$values[$i]', ";
		}
		$queryStr = substr($queryStr, 0, strlen($queryStr) -2);
		$queryStr .= " WHERE `reminder_id` =$reminderId LIMIT 1;";

		return $db->query($queryStr);
	}
	
	
	/**
	 * Creates a new reminder
	 * 
	 * @param int $userId the users ID
	 * @param int $interval the number of seconds for the interval
	 * @param string $phoneticInterval the english description of the interval time
	 * @param string $startTime when the reminder should start
	 * @param datetime $nextPush the date of the next alert
	 * @param string $reminderMessage the message for the reminder
	 * @param boolean $active whether the reminder is active
	 */
	public function create($userId, $interval, $phoneticInterval, $startTime, $nextPush, $reminderMessage, $active) {
		global $db;
		global $user;
		
		$queryStr = sprintf(
			"INSERT INTO  `reminders` (
				`reminder_id` ,
				`user_id` ,
				`interval` ,
				`phonetic_interval`,
				`start_time`,
				`last_push`,
				`next_push`,
				`reminder_message`,
				`active`
			) VALUES ( 
				NULL ,  '%s',  '%s', '%s', '%s', NULL , '%s', '%s',  '%s');",
			$db->real_escape_string($userId),
			$db->real_escape_string($interval),
			$db->real_escape_string($phoneticInterval),
			$db->real_escape_string($startTime),
			$db->real_escape_string($nextPush),
			$db->real_escape_string($reminderMessage),
			$db->real_escape_string($active)			
		);
		
		// Added the reminder
		if ($db->query($queryStr) === TRUE) {
			$user->incrementActiveReminders($userId);
			return true;
		}
		
		return false;
	}
	
	
	/**
	 * Read whether a reminder is active
	 * 
	 * @param int $reminderId the reminder's ID
	 * @return boolean whether reminder is active
	 */
	public function readIsActive($reminderId) {
		global $db;
		
		$queryStr = sprintf(
			"SELECT `active` FROM `reminders` WHERE `reminder_id`=%s",
			$db->real_escape_string($reminderId)
		);
		
		$dbResult = $db->query($queryStr);
		if (is_object($dbResult)) {
			$dbResult = $dbResult->fetch_assoc();
			return $dbResult['active'];
		}
		
		return false;		
	}
			
		
	/**
	 * Reads a list of reminders from the database
	 * 
	 * @param int $userId the user's ID
	 * @return object MySQL database query object
	 */
	public function readUserReminders($userId) {
		global $db;
		$queryStr = sprintf(
			"SELECT
				`reminder_id`,
				`phonetic_interval`,
				`interval`,
				`start_time`,
				`next_push`,
				`last_push`,
				`reminder_message`,
				`active`
			FROM  `reminders` 
			WHERE user_id =%s
			ORDER BY `next_push` ASC",
			$db->real_escape_string($userId)
		);
		
		return $db->query($queryStr);		
	}
	
	
	/**
	 * Activates a reminder
	 * 
	 * @param integer $reminderId the user's ID to modify
	 * @return boolean whether the update was successful
	 */
	public function activateReminder($reminderId) {
		global $db;
		global $user;
		
		
		// Build the query
		$queryStr = sprintf(
			"UPDATE `reminders` SET  `active` =  '1' WHERE  `reminder_id` =%s",
			$db->real_escape_string($reminderId));
		
		if ($db->query($queryStr)) {
				$user->incrementActiveReminders($_SESSION['uid']);
				return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Deactivates a reminder
	 * 
	 * @param integer $reminderId the user's ID to modify
	 * @return boolean whether the update was successful
	 */
	public function deactivateReminder($reminderId) {
		global $db;
		global $user;
		
		// Build the query
		$queryStr = sprintf(
			"UPDATE `reminders` SET  `active` =  '0' WHERE  `reminder_id` =%s",
			$db->real_escape_string($reminderId));
		
		if ($db->query($queryStr)) { 
			$user->reduceActiveReminders($_SESSION['uid']);
			return true;
		} else {
			return false;
		}
	}
	
	
	/**
	 *  Reads overdue reminders to send in the next cron job
	 * 
	 */
	public function readCronReminders() {
		global $db;
		
		$now = date(SQLDATE, time());
		$queryStr = sprintf(
			"SELECT
				`reminder_id`,
				`user_id`,
				`interval`,
				`next_push`,
				`reminder_message`
			FROM  `reminders` 
			WHERE  `next_push` <  '%s'
			AND	`active`=1",
			$db->real_escape_string($now)
		);
		
		return $db->query($queryStr);
	}
	
	
	/**
	 * Updates a reminder with the last push information
	 */
	public function updateLastPush($reminderId, $nextPush) {
		global $db;
		
		$currentTime = date(SQLDATE, time());
		$queryStr = sprintf(
			"UPDATE  `reminders` SET
				`last_push` = '%s',
				`next_push` = '%s'
			WHERE  `reminder_id` =%s;",
			$db->real_escape_string($currentTime),
			$db->real_escape_string($nextPush),
			$db->real_escape_string($reminderId)
		);
		
		return $db->query($queryStr);
	}
}

$reminder = new Reminder();

?>
