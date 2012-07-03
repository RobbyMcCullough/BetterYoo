<?php
/**
 * Sends and SMS message
 * 
 * @param string $phoneNumber a phone number in e-mail format i.e. 555555555@vtext.com
 * @param string $messgae the message to send as an SMS. 140 char limit.
 */
function send_sms($phoneNumber, $message) {
	$headers = 'From: ' . YOOEMAIL . "\r\n" .
    'Reply-To: ' . YOOEMAIL . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
	mail($phoneNumber, '', $message, $headers);
}

/**
 * Redirects the browser to a given page
 * 
 * @param string $page the page to redirect to
 */
function redirect($page) {
	header('Location: ' . $page);
}

/**
 * Validates and user-submitted e-mail address
 * 
 * @param $email the users e-mail
 */
function isValidEmail($email) {
	return filter_var($email, FILTER_VALIDATE_EMAIL);
}
?>