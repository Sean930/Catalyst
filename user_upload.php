<?php
$users = array ();
$usr;
$pwd;
$host;
$con;

do {
	fwrite ( STDOUT, "Enter a command(-e to exit; --help for help): " );
	
	$input = trim ( fgets ( STDIN ) );
	
	switch ($input) {
		case "--file" :
			parseData ();
			insertData ();
			break;
		case "--create_table" :
			createTable ();
			break;
		case "--dry_run" :
			parseData ();
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
function connection() {
	global $usr, $pwd, $host, $con;
	// if ($usr != null && $host != null) {
	// $con = mysqli_connect ( $host, $usr, $pwd );
	if (1) {
		$con = mysqli_connect ( "localhost:3306", "root", "root" );
		if (! $con) {
			fwrite ( STDOUT, "Failed to connect to MySql: " . mysql_error () );
			return false;
		}
		echo "Successfully connected.\n";
		return true;
	}
	else 
		return false;
}
function parseData() {
	fwrite ( STDOUT, "Enter file name: " );
	
	$input = trim ( fgets ( STDIN ) );
	if (! ($myFile = fopen ( "./" . $input, "r" ))) {
		fwrite ( STDOUT, "Unable to open file.\n" );
	} else {
		while ( ! feof ( $myFile ) ) {
			$row = fgets ( $myFile );
			global $users;
			$users [] = explode ( ",", $row );
		}
		fclose ( $myFile );
		
		for($i = 1; $i < count ( $users ); $i ++) {
			$users [$i] [0] = ucwords ( strtolower ( $users [$i] [0] ) );
			$users [$i] [1] = ucwords ( strtolower ( $users [$i] [1] ) );
			$users [$i] [2] = strtolower ( $users [$i] [2] );
		}
		fwrite ( STDOUT, "File successfully parsed.\n" );
	}
}
function insertData() {
	if (connection ()) {
		global $con, $users;
		$sql = "SELECT name FROM users";
		$result = mysqli_query ( $con, $sql );
		print_r ( $result );
		echo mysqli_num_rows ( $result );
		if ($result == null) {
			for($i = 1; $i < count ( $users ); $i ++) {
				$users [$i] [2] = trim ( $users [$i] [2] );
				if (filter_var ( $users [$i] [2], FILTER_VALIDATE_EMAIL )) {
					mysqli_select_db ( $con, "my_db" );
					$sql = "INSERT INTO users (name, surname, email) VALUES ('" . addslashes ( $users [$i] [0] ) . "', '" . addslashes ( $users [$i] [1] ) . "', '" . addslashes ( $users [$i] [2] ) . "')";
					if (! mysqli_query ( $con, $sql )) {
						fwrite ( STDOUT, "Error to insert data: " . mysqli_error ( $con ) . ".\n" );
					}
				} else {
					fwrite ( STDOUT, $users [$i] [2] . " is not a valid email address.\n" );
				}
			}
		} else {
			fwrite ( STDOUT, "Table 'users' is not exist, please create table first.\n" );
		}
	}
}
function createTable() {
	if (connection ()) {
		global $con;
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