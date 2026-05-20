<?php
session_start();
include "settings.php";
$string = strtoupper($_SESSION['string']);
$userstring = strtoupper($_POST['userstring']); 
if (($string == $userstring) && (strlen($string) > 4)) {
	echo 'true';
} else {
	echo 'false';
}
?>