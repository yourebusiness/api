<?php
/*No direct script access allowed*/
//http://bshaffer.github.io/oauth2-server-php-docs/cookbook/

include_once('../../yourebusiness.com/.inc/globals.inc.php');

global $db;
		
$dsn      = "mysql:dbname={$db['database']};host={$db['hostname']}";
$username = $db["username"];
$password = $db["password"];

// error reporting (this is a demo, after all!)
ini_set('display_errors',1);error_reporting(E_ALL);

// Autoloading (composer is preferred, but for this example let's just do this)
require_once('../OAuth2/src/OAuth2/Autoloader.php');
OAuth2\Autoloader::register();

// $dsn is the Data Source Name for your database, for exmaple "mysql:dbname=my_oauth2_db;host=localhost"
$storage = new OAuth2\Storage\Pdo(array('dsn' => $dsn, 'username' => $username, 'password' => $password));

// Pass a storage object or array of storage objects to the OAuth2 server class
$server = new OAuth2\Server($storage);

// Add the "Client Credentials" grant type (it is the simplest of the grant types)
//$server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));

// Add the "Authorization Code" grant type (this is where the oauth magic happens)
//$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));

// create some users in memory
$users = array('bshaffer' => array('password' => 'brent123', 'first_name' => 'Brent', 'last_name' => 'Shaffer'));

try {
	$DBH = new PDO("mysql:host={$db['hostname']};dbname={$db['database']}", $db["username"], $db["password"]);
	$DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

	$DBH->prepare('SELECT username, psswd from users');
}
catch(PDOException $e) {
    error_log($e->getMessage());
    die;
}



// create a storage object
$storage = new OAuth2\Storage\Memory(array('user_credentials' => $users));

// create the grant type
$grantType = new OAuth2\GrantType\UserCredentials($storage);

// add the grant type to your OAuth server
$server->addGrantType($grantType);