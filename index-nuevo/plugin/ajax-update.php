<?php

/*
 * DATABASE CONNECTION
 */

// DATABASE: Connection variables
$db_host		= "127.0.0.1";
$db_name		= "ex_database";
$db_username	= "ex_user";
$db_password	= "ex_pass";

// DATABASE: Try to connect
if (!$db_connect = mysqli_connect($db_host, $db_username, $db_password))
	die('Unable to connect to MySQL.');
if (!$db_select = mysql_select_db($db_name, $db_connect))
	die('Unable to select database');

// DATABASE: Clean data before use
function clean($value)
{
	return mysql_real_escape_string($value);
}

/*
 * FORM PARSING
 */

// FORM: Variables were posted
if (count($_POST))
{
	// Prepare form variables for database
	foreach($_POST as $column => $value)
		${$column} = clean($value);

	// Perform MySQL UPDATE
	$result = mysql_query("UPDATE user_table SET ".$col."='".$val."'
		WHERE ".$w_col."='".$w_val."'")
		or die ('Unable to update row.');
}

?>