<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// http://bshaffer.github.io/oauth2-server-php-docs/cookbook/

include_once (".inc/globals.inc.php");

class OAuth2 {
	public function OAuth2() {
		// error reporting (this is a demo, after all!)
		// this will be changed on production
		ini_set('display_errors',1);
		error_reporting(E_ALL);

		// Autoloading (composer is preferred, but for this example let's just do this)
		require_once('OAuth2/src/OAuth2/Autoloader.php');
	}
}