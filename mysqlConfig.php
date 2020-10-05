<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */

define('DB_SERVER', 'piyush.crhyw7aw5mof.ap-south-1.rds.amazonaws.com:3333');
define('DB_USERNAME', 'admin');
define('DB_PASSWORD', 'piyush3t');
define('DB_NAME', 'scads');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

?>