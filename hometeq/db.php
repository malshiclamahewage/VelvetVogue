<?php
//global variables
//procedural sql
$dbhost = 'localhost'; // hostname or server address where the MySQL database is hosted
$dbuser = 'root';
$dbpass = '';
$dbname = 'w2051583_0';
//create a DB connection
//accept 4 parameters
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
//mysqli_connect() is a built-in PHP function that establishes a connection to the MySQL database.

//if the DB connection fails, display an error message and exit
if (!$conn) 
{    die('Could not connect: ' . mysqli_connect_error()); //if the table doesn't exist it will display an error message
//die()-a built-in PHP function (Immediately stops execution when connection fails and prints the error message.)
}
//select the database
mysqli_select_db($conn, $dbname);
?>


