<?php
$users = array ();
$usr;
$pwd;
$host;

do {
	fwrite ( STDOUT, "Enter a command(-e to exit; --help for help): " );
	
	$input = trim ( fgets ( STDIN ) );
	
	switch ($input) {
		case "--file" :
			showFileName ();
			break;
		case "--create_table" :
			createTable ();
			break;
		case "--dry_run" :
			dryRun ();
			break;
		case "-u" :
			MySQLUsername ();
			break;
		case "-p" :
			MySQLPassword ();
			break;
		case "-h" :
			MySQLHost ();
			break;
		case "--help" :
			help ();
			break;
		case "-e" :
			break;
		default :
			echo "Undefined command, please enter again.\n";
	}
} while ( $input != "e" );
function showFileName() {
	$myFile = fopen ( "./users.csv", "r" ) or die ( "Unable to open file." );
	while ( ! feof ( $myFile ) ) {
		$row = fgets ( $myFile );
		global $users;
		$users [] = explode ( ",", $row );
	}
	print_r ( $users );
	fclose ( $myFile );
}
function createTable() {
	global $usr, $pwd, $host;
	// if ($usr != null && $host != null) {
	// $con = mysqli_connect ( $host, $usr, $pwd );
	if (1) {
		$con = mysqli_connect ( "localhost:3306", "root", "root" );
		if (! $con) {
			die ( 'Failed to connect to MySql: ' . mysql_error () );
		}
		echo "Successfully connected.\n";
		
		if (mysqli_query ( $con, "CREATE DATABASE my_db" )) {
			fwrite ( STDOUT, "Database created.\n" );
		} else {
			fwrite ( STDOUT, "Error to create database: " . mysqli_error ( $con ) . ".\n" );
		}
		
		mysqli_select_db ( $con, "my_db" );
		$sql = "CREATE TABLE users (name varchar(50), surname varchar(50), email varchar(50) NOT NULL, PRIMARY KEY(email))";
		if (mysqli_query ( $con, $sql )) {
			fwrite ( STDOUT, "Table created.\n" );
		} else {
			fwrite ( STDOUT, "Error to create table: " . mysqli_error ( $con ) . ".\n" );
		}
		
		mysqli_close ( $con );
	} else
		fwrite ( STDOUT, "Please enter MySql username, password and host address first!\n" );
}
function dryRun() {
	$myFile = fopen ( "./users.csv", "r" ) or die ( "Unable to open file." );
	while ( ! feof ( $myFile ) ) {
		$row = fgets ( $myFile );
		global $users;
		$users [] = explode ( ",", trim ( $row ) );
	}
	fclose ( $myFile );
	
	for($i = 0; $i < count ( $users ); $i ++) {
		echo ucwords ( strtolower ( $users [$i] [0] ) );
		echo ucwords ( strtolower ( $users [$i] [1] ) );
		if (preg_match ( "/([\D]+\@{1}[\w]+\.{1}[\w]+)/", strtolower ( $users [$i] [2] ) )) {
			echo strtolower ( $users [$i] [2] ) . "\n";
		} else {
			fwrite ( STDOUT, "\n" . $users [$i] [2] . " is not a valid email address.\n" );
		}
	}
}
function MySQLUsername() {
	fwrite ( STDOUT, "Please enter MySql username: " );
	global $usr;
	$usr = trim ( fgets ( STDIN ) );
}
function MySQLPassword() {
	fwrite ( STDOUT, "Please enter MySql password: " );
	global $pwd;
	$pwd = trim ( fgets ( STDIN ) );
}
function MySQLHost() {
	fwrite ( STDOUT, "Please enter MySql host address: " );
	global $host;
	$host = trim ( fgets ( STDIN ) );
}
function help() {
	echo "-- file: this is the name of the CSV to be parsed\n";
	echo "--create_table: this will cause the MySQL users table to be built (and no further action will be taken)\n";
	echo "--dry_run: this will be used with the -- file directive in the instance that we want to run the script but not insert into the DB. All other functions will be executed, but the database won't be altered.\n";
	echo "-u: MySQL username\n";
	echo "-p: MySQL password\n";
	echo "-h: MySQL host\n";
}

?>