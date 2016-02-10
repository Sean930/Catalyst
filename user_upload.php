<?php
do {
	fwrite ( STDOUT, "Enter a command(e to exit; --help for help): " );
	
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
		default :
			echo "Undefined command, please enter again.\n";
	}
} while ( $input != "e" );

function showFileName() {
	echo "file name\n";
}

function createTable() {
	echo "file name\n";
}

function dryRun() {
	echo "file name\n";
}

function MySQLUsername() {
	echo "file name\n";
}

function MySQLPassword() {
	echo "file name\n";
}

function MySQLHost() {
	echo "file name\n";
}

function help() {
	echo "file name\n";
}

?>