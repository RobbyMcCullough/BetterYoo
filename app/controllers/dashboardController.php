<?php

/**
 * Controls the registration process and URL input
 */
class DashboardController {
    public function __construct() { }
	
	/**
	 * Generates a user's control panel page
	 * 
	 * @param string $phoneNumber the users phone number
	 */
	public function makeDashboard() {
		global $globals;
		global $view;
		global $dashboard;
		
		$tmpl = $dashboard->readDashboard();
		
		// Render the page
		$view->createPage('dashboard', $tmpl);
	}
}

$dashboardController = new DashboardController();
?>
