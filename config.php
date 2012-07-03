<?php

// MySQL DATABASE INFO
define(DBHOST, '');
define(DBUSER, '');
define(DBPASS, '');
define(DBNAME, '');

//SCRIPT ROOT DIR
define(WEBROOT, '/betteryoo/');					// Web Root

// DEBUGING CONFIGURATION
//define(DEBUG, 1);
//error_reporting(E_ALL);

// ----- NO NEED TO EDIT BELOW -----


date_default_timezone_set('GMT');
define(ROOT, dirname(__FILE__) . '/');      	// Root directory

/* Globals
-----------------------------*/
define(REMINDERLIMIT, 3);						// Limit for free acount reminders
define(PROLIMIT, 2000);							// Limit for pro account
define(YOOEMAIL, 'reminder@BetterYoo.com');		// BetterYoo E-Mail Addy
define(SQLDATE, 'Y-m-d H:i:s');					// MySQL date format
define(ENGLISHDATE, 'D M jS Y @ g:i a');
define(LEAPYEAR, date('L', time()));			// Whether it is a leap year

define(SECONDS_HOUR, 3600);						// Seconds in a hour
define(SECONDS_DAY, 86400);						// "         "  day
define(SECONDS_WEEK, 604800);					// "         "  week
define(SECONDS_MONTH, 2629743);					// "         "  month
define(SECONDS_YEAR, 31536000);					// "         "  year

define(COOKIE_EXPIRE, 60*60*24*7);  			//7 days by default
define(SALT, '$%ad34ADSFE32#fae$$%#adfEREa');	// Password salt


/* Faux Global Arrays
-----------------------------*/
$globals['account_levels'] = array(
	'Free',	'Pro'
);

$globals['carrierNames'] = array(
	'Verizon', 'ATT', 'Sprint',	'tmomail.net', 'Nextel',
	'Cingular', 'Virgin Mobile', 'AllTel', 'Cell One', 'Omni Point',
	'Qwest', '3 River Wireless', 'ACS Wireless', 'Bell Canada', 'Bell Canada 2',
	'Bell Mobility', 'Blue Sky Frog', 'Bluegrass Cellular', 'BPL Mobile'
	/*
	'Carolina West Wireless', 'Cellular South', 'CenturyTel', 'Comcast', 'Corr Wireless Communications',
	'Dobson', 'Edge Wireless', 'Fido', 'Golden Telecom', 'Houston Cellular',
	'Idea Cellular', 'Illinois Valley Cellular', 'Inland Cellular Telephone', 'MCI', 'Metrocall',
	'Midwest Wireless', 'Mobilcomm', 'MTS', 'OnlineBeep', 'PCS One',
	'Public Service Cellular', 'Rogers AT&T Wireless', 'Satellink', 'Southwestern Bell', 'Sumcom',
	'Surewest Communicaitons', 'Telus', 'Triton', 'Unicel', 'US Cellular',
	'Solo Mobile', 'Sumcom', 'Surewest Communicaitons', 'Telus', 'Triton',
	'Unicel', 'US West', 'Virgin Mobile Canada', 'West Central Wireless', 'Western Wireless'
	 */
);

$globals['carriers'] = array(
	'vtext.com', 'txt.att.net', 'messaging.sprintpcs.com', 'tmomail.net', 'messaging.nextel.com',
	'cingularme.com', 'vmobl.com', 'message.alltel.com', 'mobile.celloneusa.com', 'omnipointpcs.com',
	'qwestmp.com', 'sms.3rivers.net', 'paging.acswireless.com', 'txt.bellmobility.ca', 'bellmobility.ca'
	/*
	'txt.bell.ca', 'blueskyfrog.com', 'sms.bluecell.com', 'bplmobile.com',
	'cwwsms.com', 'csouth1.com', 'cwemail.com', 'comcastpcs.textmsg.com', 'corrwireless.net',
	'mobile.dobson.net', 'sms.edgewireless.com', 'fido.ca', 'sms.goldentele.com', 'text.houstoncellular.net',
	'ideacellular.net', 'ivctext.com', 'inlandlink.com', 'pagemci.com', 'page.metrocall.com', 
	'earlydigital.com', 'mobilecomm.net', 'text.mtsmobility.com', 'onlinebeep.net', 'pcsone.net',
	'sms.pscel.com', 'pcs.rogers.com', 'satellink.net', 'email.swbw.com', 'tms.suncom.com',
	'mobile.surewest.com', 'msg.telus.com', 'tms.suncom.com', 'utext.com', 'email.uscc.net',
	'txt.bell.ca', 'tms.suncom.com', 'mobile.surewest.com', 'msg.telus.com', 'tms.suncom.com',
	'utext.com', 'uswestdatamail.com', 'vmobile.ca', 'sms.wcc.net', 'cellularonewest.com'
	 */
);

$globals['verificationCodes'] = array(
	array(
		'apple', 'pear', 'raisin', 'burger', 'grains','popcorn', 'cake',   'muffin', 'cookie', 'cupcake', 'cheese',
		'1', '2', '3', '4', '5', '6', '7', '8', '9', '10',
		'11', '12', '13', '14', '15', '16', '17', '18', '19', '20',
		'21', '22', '23', '24', '25', '26', '27', '28', '29', '30',
		'31', '32', '33', '34', '35', '36', '37', '38', '39', '40',
		'41', '42', '43', '44', '45', '46'
	),
	array(
	'red',   'blue', 'green',  'yellow', 'white',  'black',   'orange', 'purple', 'pink',   'gray',    'teal',
		'1', '2', '3', '4', '5', '6', '7', '8', '9', '10',
		'11', '12', '13', '14', '15', '16', '17', '18', '19', '20',
		'21', '22', '23', '24', '25', '26', '27', '28', '29', '30',
		'31', '32', '33', '34', '35', '36', '37', '38', '39', '40',
		'41', '42', '43', '44', '45', '46'
	),
	array(
		'dog',   'cat',  'horse',  'pig',    'mouse',  'eagle',  'lion',   'zebra',  'kowala', 'lizard',  'fish',
		'1', '2', '3', '4', '5', '6', '7', '8', '9', '10',
		'11', '12', '13', '14', '15', '16', '17', '18', '19', '20',
		'21', '22', '23', '24', '25', '26', '27', '28', '29', '30',
		'31', '32', '33', '34', '35', '36', '37', '38', '39', '40',
		'41', '42', '43', '44', '45', '46'
	),
);


/* Includes
-----------------------------*/

/* Models */
require(ROOT . 'app/models/user.php');
require(ROOT . 'app/models/reminder.php');
require(ROOT . 'app/models/dashboard.php');

/* Controllers */
require(ROOT . 'app/controllers/registerController.php');
require(ROOT . 'app/controllers/reminderController.php');
require(ROOT . 'app/controllers/userController.php');
require(ROOT . 'app/controllers/sessionController.php');
require(ROOT . 'app/controllers/cookieController.php');
require(ROOT . 'app/controllers/dashboardController.php');

/* View */
require(ROOT . 'app/views/view.php');

/** Include Helpers */
require(ROOT . 'app/helpers/functions.php');
/* Libraries */
require(ROOT . 'app/libraries/wordToNumber.php');
require(ROOT . 'app/libraries/Mobile_Detect.php');
// Set MOBILE
define(ISMOBILE,$mobileDetect->isMobile());

// Start PHP Session
$sessionController->start();

// Set some initial meta & title values
$meta['meta_title'] = 'Free Recurring Text Reminders - BetterYoo';
$meta['meta_desc'] = 'BetterYoo sends text message reminders on a schedule.';
$meta['meta_keywords'] = 'text message reminders, text message, betteryoo';


// Open database connection
$db = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);
$tmpl['funnel'] = 0;

?>
