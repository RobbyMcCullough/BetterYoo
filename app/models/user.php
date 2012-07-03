<?php

/**
 * CRUD functions for the User table
 */
class User {
    public function __construct() { }
	
	/**
	 * Creates a new users account
	 * 
	 * @param string $phoneNumber user's phone number
	 * @param string $password md5 encrypted password
	 * @param int $carrierId the phone carriers id see config file for base
	 * @param int $activeReminders number of active reminders
	 * @param int $verified whether user's phone is verified
	 * @param string $email user's e-mail address
	 * @param int $timeOffset the user's GMT offset in minutes
	 * @return user_id
	 */
	public function create($phoneNumber, $password, $carrierId, $activeReminders, $verified, $email, $timeOffset) {
		global $db;
		
		$queryStr = sprintf(
			"INSERT INTO  `users` (
				`id` ,
				`phone_number` ,
				`carrier_id` ,
				`active_reminders` ,
				`pro_user` ,
				`password` ,
				`verified` ,
				`email` ,
				`ip`,
				`time_offset`
			) VALUES (
				NULL , '%s',  '%s',  '%s', '0' , '%s',  '%s',  '%s', '%s', '%s'
			);",
			$db->real_escape_string($phoneNumber),
			$db->real_escape_string($carrierId),
			$db->real_escape_string($activeReminders),
			$db->real_escape_string($password),
			$db->real_escape_string($verified),
			$db->real_escape_string($email),
			$db->real_escape_string($_SERVER['REMOTE_ADDR']),
			$db->real_escape_string($timeOffset)
		);
		

		if ($db->query($queryStr)) {
			return $db->insert_id;
		}
		
		return false;
	}
	
	/**
	 * Updates a users information
	 * 
	 * @param integer $userId the user's ID to modify
	 * @param array $updates an array containing key => values to update
	 * @return boolean whether the update was successful
	 */
	public function update($userId, $updates) {
		global $db;
		
		// Escape all values & split keys and values
		$values = array_map(array($db,'real_escape_string'), array_values($updates));
    	$keys = array_keys($updates);
		
		// Build the query
		$queryStr = 'UPDATE `users` SET ';
		for ($i=0; $i<count($keys);$i++) {
			$queryStr .= "`$keys[$i]` = '$values[$i]', ";
		}
		$queryStr = substr($queryStr, 0, strlen($queryStr) -2);
		$queryStr .= " WHERE `id` =$userId LIMIT 1;";

		return $db->query($queryStr);
	}
	
	/**
	 * Increments the user's number of active reminders
	 * 
	 * @param $userId the users ID
	 * @return boolean $db->query
	 */
	public function incrementActiveReminders($userId) {
		global $db;
		$queryStr = sprintf(
			"UPDATE `users`
			 SET `active_reminders` = `active_reminders` + 1
			 WHERE `id` = '%s' LIMIT 1",
			 $db->real_escape_string($userId)
		);
		
		return $db->query($queryStr);
	}
	
	/**
	 * Reduces the user's number of active reminders
	 * 
	 * @param $userId the users ID
	 * @return boolean $db->query
	 */
	public function reduceActiveReminders($userId) {
		global $db;
		$queryStr = sprintf(
			"UPDATE `users`
			 SET `active_reminders` = `active_reminders` - 1
			 WHERE `id` = '%s' LIMIT 1",
			 $db->real_escape_string($userId)
		);
		
		return $db->query($queryStr);
	}
	
	
	/**
	 * Reads a user carrier
	 * 
	 * @param string $uid the users id
	 * @return the users ID or false on fail
	 */
	public function readActiveReminders($uid) {
		global $db;
		
		$queryStr = sprintf(
			"SELECT `active_reminders` 
			FROM `users` 
			WHERE `id` = '%s'
			LIMIT 1",
			$db->real_escape_string($uid)
		);
		
		$result = $db->query($queryStr);
		
		if (is_object($result)) {
			$result = $result->fetch_assoc();
			return $result['active_reminders'];
		}
		
		return false;
	}
	
	/**
	 * Reads a user carrier
	 * 
	 * @param string $uid the users id
	 * @return the users ID or false on fail
	 */
	public function readUserTimeOffset($uid) {
		global $db;
		
		$queryStr = sprintf(
			"SELECT `time_offset` 
			FROM `users` 
			WHERE `id` = '%s'
			LIMIT 1",
			$db->real_escape_string($uid)
		);
		
		$result = $db->query($queryStr);
		
		if (is_object($result)) {
			$result = $result->fetch_assoc();
			return $result['time_offset'];
		}
		
		return false;
	}
	
	/**
	 * Reads a user carrier
	 * 
	 * @param string $uid the users id
	 * @return the users ID or false on fail
	 */
	public function readUserCarrier($uid) {
		global $db;
		
		$queryStr = sprintf(
			"SELECT `carrier_id` 
			FROM `users` 
			WHERE `id` = '%s'
			LIMIT 1",
			$db->real_escape_string($uid)
		);
		
		$result = $db->query($queryStr);
		
		if (is_object($result)) {
			$result = $result->fetch_assoc();
			return $result['carrier_id'];
		}
		
		return false;
	}
	
	
	/**
	 * Reads a user setting information for their control panel page
	 * 
	 * @param string $phoneNumber the users phone number
	 * @return the users ID or false on fail
	 */
	public function readProUser($uid) {
		global $db;
		
		$queryStr = sprintf(
			"SELECT
				`pro_user`
			FROM `users` 
			WHERE `id` = %s
			LIMIT 1",
			$db->real_escape_string($uid)
		);
		
		$result = $db->query($queryStr);
		if (is_object($result)) {
			$result = $result->fetch_assoc();
			return $result['pro_user'];
		} else {
			return false;
		}
	}
	
	/**
	 * Reads a user setting information for their control panel page
	 * 
	 * @param string $phoneNumber the users phone number
	 * @return the users ID or false on fail
	 */
	public function readUserInfo($phoneNumber) {
		global $db;
		
		$queryStr = sprintf(
			"SELECT
				`id`,
				`carrier_id`,
				`email`,
				`active_reminders`,
				`pro_user`
			FROM `users` 
			WHERE `phone_number` = '%s'
			LIMIT 1",
			$db->real_escape_string($phoneNumber)
		);
		
		$result = $db->query($queryStr);
		if (is_object($result)) {
			return $result;
		} else {
			return false;
		}
	}
	
	/**
	 * Reads a user ID from a cleaned phone number
	 * 
	 * @param string $phoneNumber the users phone number
	 * @return the users ID or false on fail
	 */
	public function readUserId($phoneNumber) {
		global $db;
		
		$queryStr = sprintf(
			"SELECT `id` 
			FROM `users` 
			WHERE `phone_number` = '%s'
			LIMIT 1",
			$db->real_escape_string($phoneNumber)
		);
		
		$result = $db->query($queryStr);
		
		if (is_object($result)) {
			$result = $result->fetch_assoc();
			return $result['id'];
		}
		
		return false;
	}
	
	/**
	 * Reads a user ID from an e-mail
	 * 
	 * @param string $phoneNumber the users phone number
	 * @return the users ID or false on fail
	 */
	public function upgradeAccount($uid) {
		global $db;
		
		$queryStr = sprintf(
			"UPDATE `users` SET
				`pro_user` = 1
			WHERE `id` = '%s' LIMIT 1",
			$db->real_escape_string($uid)
		);
		
		return($db->query($queryStr));
	}
	
	/**
	 * Reads user's password from database by User Id
	 * 
	 * @param int $uid the users ID
	 * @return password or false on failure
	 */
	public function readUserPassword($uid) {
		global $db;
		$queryStr = sprintf(
			"SELECT `password` 
			FROM `users` 
			WHERE `id` = '%s'
			LIMIT 1",
			$db->real_escape_string($uid)
		);
		
		$result = $db->query($queryStr);
		
		if (is_object($result)) {
			$result = $result->fetch_assoc();
			return $result['password'];
		}
		
		return false;
	}
	
	
	/**
	 * Reads a users phone email
	 * 
	 * phonenumber + @ + carrier
	 */
	public function readPhoneEmail ($uid) {
		global $globals;
		global $db;
		
		$queryStr = sprintf(
			"SELECT `phone_number`, `carrier_id` FROM `users` WHERE `id` =%s LIMIT 1",
			$db->real_escape_string($uid)
		);
		
		$result = $db->query($queryStr);
		
		if (is_object($result)) {
			$result = $result->fetch_assoc();

			return $result['phone_number'] . '@' . $globals['carriers'][$result['carrier_id']]; 
		} else {
			return false;
		}
	}
	
	/**
	 * Deletes a user table by ID
	 * 
	 * @param int $userId the users Id
	 * @return boolean $db->query
	 */
	public function delete($userId) {
		$queryStr = sprintf(
			"DELETE FROM `users` WHERE `id` = %s",
			$db->real_escape_string($userId)
		);
		
		return $db->query($queryStr);
	}
}

$user = new User();
?>