<?php

class Dashboard {
    public function __construct() { }
	
	/**
	 * Updates the dashboard
	 */
	public function update() {
		global $db;
		
		$queryStr = "UPDATE  
						`dashboard`
					SET  
						`set_reminders` =  (SELECT count(*) FROM `reminders`),
						`registered_users` =  (SELECT count(*) FROM `users`)
					WHERE  `dashboard`.`id` =1;";
					
		$db->query($queryStr);
	}
	
	/**
	 * Reads dashboard information from the database
	 */
	public function readDashboard() {
		global $db;
		
		$this->update();
		
		$queryStr = sprintf(
			"SELECT
				`set_reminders`,
				`registered_users`
			FROM `dashboard` WHERE `id`=1"
		);
		
		$dbResult = $db->query($queryStr);
		if (is_object($dbResult)) {
			$dbResult = $dbResult->fetch_assoc();
			return $dbResult;
		}
		
		return false;		
	}
}

$dashboard = new Dashboard();

?>
