<?php
/*No direct script access allowed*/

require_once __DIR__.'/server.php';

/*$fileLocation = "/tmp/registration.txt";
$file = fopen($fileLocation, "w");
fwrite($file, print_r($_SERVER, true));
fclose($file);*/

// Handle a request for an OAuth2.0 Access Token and send the response to the client
$server->handleTokenRequest(OAuth2\Request::createFromGlobals())->send();