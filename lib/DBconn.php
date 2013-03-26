<?php
/* ************************************************************************
* AUTHOR:  Craig W. Christensen
* DATE:
* DESCRIPTION: Connection object to db for
************************************************************************ */

$server 	= 'localhost';
$user 		= 'cwcraigo_iAdmin';
$password = '***********';
$db 			= 'cwcraigo_intro2techdb';

$myConn = new mysqli($server, $user, $password, $db);

if(mysqli_connect_error()) {
  include $current_dir.'/views/errorDocs/500.php';
  exit;
}

?>
