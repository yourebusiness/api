<?php

$schema_file = "/usr/local/database/dev/0schema.sql";

function mysqlDump() {
	global $schema_file;
	global $db;
	exec("mysqldump -h {$db['common_hostname']} -u {$db['common_username']} -p{$db['password']} {$db['common_database']} > $schema_file");

	return true;
}

function dropAndReloadDatabase() {
	global $db;

	$dbi = new mysqli($db['hostname'], $db['username'], $db['password']);
	$reset_db = "drop database if exists {$db['database']};";
	$reset_db .= "create database {$db['database']};";
	$result = $dbi->multi_query($reset_db);
	if ($result == false) {
		$msg = $dbi->error;
		$num = $dbi->errno;
		echo "Database error in " . __FUNCTION__ . " ($num: $msg)";
		return false;
	}

	while($dbi->more_results())
		if ($dbi->next_result() == false)
			return false;

	$dbi->close();

	global $schema_file;
	$dbi = new mysqli($db['hostname'], $db['username'], $db['password'], $db['database']);
	$reset_db = file_get_contents($schema_file);
	$result = $dbi->multi_query($reset_db);
	if ($result == false) {
		$msg = $dbi->error;
		$num = $dbi->errno;
		echo "Database error in " . __FUNCTION__ . " ($num: $msg)";
		return false;
	}

	while ($dbi->more_results())
		if ($dbi->next_result() == false)
			return false;

	$dbi->close();
	return true;
}
