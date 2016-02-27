<?php

// create some users in memory
$users = array('bshaffer' => array('password' => 'brent123', 'first_name' => 'Brent', 'last_name' => 'Shaffer'));

// create a storage object
$storage = new OAuth2\Storage\Memory(array('user_credentials' => $users));

// create the grant type
$grantType = new OAuth2\GrantType\UserCredentials($storage);

// add the grant type to your OAuth server
$server->addGrantType($grantType);