<?php

/**
 * Handles the creation of views from template files
 */
class View {
    public function __construct() { }
	
	
	public function errorPage($errorMessage) {
		$tmpl['error'] = $errorMessage;
		$this->createPage('error', $tmpl);
	}
	
	public function createPage($pageName, $tmpl) {
		global $meta;
		$tmpl['bodyClass'] .= ' ' . $pageName;
		include(ROOT . 'app/views/templates/header.tpl.php');
		include(ROOT . 'app/views/templates/' . $pageName . 'Page.tpl.php');
		include(ROOT . 'app/views/templates/footer.tpl.php');
		exit();
	}
	
	/**
	 * Filters XSS attacks from user input
	 * 
	 * @param string $string is the string to clean
	 * @return string the string filtered through htmlspecialchars()
	 */
	public function xss_clean($string) {
		return htmlspecialchars($string);
	}
	
}
$view = new View();
?>
