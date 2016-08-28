<?php
/*No direct script access allowed*/
//http://bshaffer.github.io/oauth2-server-php-docs/cookbook/

include_once('../.inc/globals.inc.php');

global $db;
		
$dsn      = "mysql:dbname={$db['database']};host={$db['hostname']}";
$username = $db["username"];
$password = $db["password"];

// error reporting (this is a demo, after all!)
ini_set('display_errors',1);error_reporting(E_ALL);

// Autoloading (composer is preferred, but for this example let's just do this)
require_once('../restService/OAuth2/src/OAuth2/Autoloader.php');
OAuth2\Autoloader::register();

$dsn = array('dsn' => $dsn, 'username' => $username, 'password' => $password);
$config = array('user_table' => 'users');

// $dsn is the Data Source Name for your database, for example "mysql:dbname=my_oauth2_db;host=localhost"
$storage = new OAuth2\Storage\My_Pdo($dsn, $config);

// Pass a storage object or array of storage objects to the OAuth2 server class
$server = new OAuth2\Server($storage);