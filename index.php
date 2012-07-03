<?php
require_once('config.php');
require_once('app/helpers/optimizerJavascript.php');

// Controller is configured in HTACCESS
// All URL data is routed to GET[controller]

if (is_numeric($_GET['controller'])) {
	$phoneNumber = $_GET['controller'];

	// Show user page for logged in users
	if ($_SESSION['logged'] == true && $_SESSION['phone'] == $phoneNumber)
	{
		$userController->makeUserPage($phoneNumber);
	} else {
		$view->createPage('login', $tmpl);
		exit();
	}
}

// Set splash page input values
if (isset($_GET['reminder']) && isset($_GET['interval'])) {
	$tmpl['reminderValue'] = $_GET['reminder'];
	$tmpl['intervalValue'] = $_GET['interval'];
	$tmpl['funnel'] = 0;
} else {
	$tmpl['reminderValue'] = 'Your Reminder';
	$tmpl['intervalValue'] = 'How Often';
}

// Process Other Pages:
switch ($_GET['controller']) {
	
	// Process Registration
	case 'register/':
		$registerController->registerUser($_POST);
		redirect(WEBROOT . 'registration-success/');
		break;
	
	// Send Verification
	case 'send-verification/';
		// echo $registerController->sendVerification($_POST);
		echo 1;
		exit();
		break;
	
	// Confirm verification
	case 'confirm-verification/';
		if ($registerController->processVerificationCode($_POST['confirmation']) !== false) {
			echo 1;
		} else {
			echo 0;
		}
		exit();
		break;
		
	// Process Login - Call sessionController->logIn to verify username/pw then redirect to user page.
	case 'login/':
		if ($sessionController->logIn($_POST['phone'],$_POST['password']) == true) {
			redirect(WEBROOT . $_SESSION['phone']);
			exit();
		} else {
			$view->createPage('login', $tmpl);
			exit();
		}
		break;
		
	// Process Logout
	case 'logout/':
		$sessionController->logOut();
		redirect(WEBROOT);
		exit();
		break;
	
	// Process Add New Reminder
	case 'add-reminder/':
		$reminderController->createReminder($_POST);
		redirect(WEBROOT . $_SESSION['phone']);
		exit();
		
	// Process Remove Reminder
	case 'remove-reminder/':
		echo $reminderController->removeReminder($_POST);
		exit();
		
	// Process Activate Reminder
	case 'activate-reminder/':
		$reminderController->activateReminder($_POST);
		redirect(WEBROOT . $_SESSION['phone']);
		exit();

	// Process Deactivate Reminder
	case 'deactivate-reminder/':
		$reminderController->deactivateReminder($_POST);
		redirect(WEBROOT . $_SESSION['phone']);
		exit();
		
	// Update User Settings
	case 'update-settings/':
		$userController->updateSettings($_POST);
		redirect(WEBROOT . $_SESSION['phone']);
		exit();
		break;	
	
	// Error Page	
	case 'error/':
		$view->errorPage('Why would you goto an error page?');
		die();
		break;

	// Payment
	case 'pintpay/':
		$userController->upgradeAccount($_POST);
		redirect(WEBROOT . $_SESSION['phone']);
		break;
		
	// Process Cron
	case 'process-cron/':
		$reminderController->processCron();
		die();
		break;
	
	// Admin dashboard is just for me!
	case 'dashboard/':
		if ($_SESSION['logged'] == true && $_SESSION['phone'] == '6502240444') {
			$dashboardController->makeDashboard();
		}
		die();
		break;
		
	case 'registration-success/':
		$tmpl['variation_js'] = $conversionPageJS;
		$view->createPage('registrationSuccess', $tmpl);
		exit();
		break;
	
	// Splash Page Variations
	case 'dos/':
		$tmpl['page_variation'] = 2;
		$tmpl['variation_js'] = $variationPageJS;
		$view->createPage('splash', $tmpl);
		die();
		break;
	case 'tres/':
		$tmpl['page_variation'] = 3;
		$tmpl['variation_js'] = $variationPageJS;
		$view->createPage('splash', $tmpl);
		die();
		break;
		
	// Default splash page
	default:
		if ($_SESSION['logged'] == true)
			redirect(WEBROOT . $_SESSION['phone']);
		else if (isset($_COOKIE['login'])) {
			if ($sessionController->cookieLogIn($_COOKIE['phone'], $_COOKIE['login']) == true) {
				redirect(WEBROOT . $_SESSION['phone']);
				exit();
			} else {
				$view->createPage('login', $tmpl);
				exit();
			}
		} else
			$tmpl['variation_js'] = $originalPageJS;
			$view->createPage('splash', $tmpl);
		break;
}

?>



