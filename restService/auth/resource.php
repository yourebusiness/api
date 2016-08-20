<?php
/*No direct script access allowed*/
//http://bshaffer.github.io/oauth2-server-php-docs/cookbook/

include_once('./server.php');

// Pass a storage object or array of storage objects to the OAuth2 server class
$server = new OAuth2\Server($storage);

if (!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
    $server->getResponse()->send();
    die;
}
echo json_encode(array('success' => true, 'message' => 'You accessed my APIs!'));