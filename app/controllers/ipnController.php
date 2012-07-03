<?php
/**
 * Controls the logic behind creating reminders
 */
class IpnController {
    public function __construct() { }

	
	/**
	 * Processes an IPN POST request
	 * 
	 * @param array $args the POST args from PayPal
	 */
	public function proccessPayment($args) {
		
		global $db;
		global $user;
		
		// read the post from PayPal system and add 'cmd'
		$req = 'cmd=_notify-validate';
		
		// Look through POST args
		foreach ($args as $key => $value) {
			$value = urlencode(stripslashes($value));
			$req .= "&$key=$value";
		}
		
		// post back to PayPal system to validate
		$header = "POST /cgi-bin/webscr HTTP/1.0\r\n";
		$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
		$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
		
		// Amount paid
		$paid = round($args['mc_gross_1']);
		
		// Determin how many reminders to add based on the amount paid.
		switch ($paid) {
			case PRICEONE:
				$remindersToAdd = PRICEOPTIONONE;
				break;
			case PRICETWO:
				$remindersToAdd = PRICEOPTIONTWO;
				break;
			case PRICETHREE:
				$remindersToAdd = PRICEOPTIONTHREE;
				break;
		}
		
		// Grab users e-mail from PayPal args
		$userEmail = $args['payer_email'];
		
		// Open connection to paypal to verify payment
		$fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);
		
		if (!$fp) {
			// HTTP error
		} else {
			// Post data to connection
			fputs ($fp, $header . $req);
			while (!feof($fp)) {
				$res = fgets ($fp, 1024);
				if (strcmp ($res, "VERIFIED") == 0) {
					// PAYMENT VALIDATED & VERIFIED! So, add reminders
					$user->updateBonusReminders($userEmail, $remindersToAdd);
				}
				else if (strcmp ($res, "INVALID") == 0) {
					// PAYMENT INVALID & INVESTIGATE MANUALY!
				}
			}
		
		// Close connection
		fclose ($fp);
		}
	}
}

$ipnController = new IpnController();
?>
