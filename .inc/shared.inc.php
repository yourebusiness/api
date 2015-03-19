<?php

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

	$schema_file = "/usr/local/database/dev/0schema.sql";
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
