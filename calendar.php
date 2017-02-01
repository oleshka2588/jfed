
<?php
	include 'calendarFunctions.php';
	session_start();
	if(function_exists($_GET['f'])) {
		$_GET['f']($_GET['date']);
	}	
?>