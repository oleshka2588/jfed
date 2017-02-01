<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title></title>
		<link href="JFedStyles.css" rel="stylesheet" type="text/css">
		<script src="jquery-1.11.3.js"></script>
	</head>
	<body>
		<?php
			include 'Variables.php';
			include 'Func.php';
			session_start();
			$servername = "localhost";
			$username = "root";
			$password = "";
			$dataBaseName = "jfed2";
			$numberResults = (isset($_GET['numberResults']))?$_GET['numberResults']:0;
			$counter = 0;
			$max = 0;
			// Create connection
			$conn = mysqli_connect($servername, $username, $password, $dataBaseName);
			mysqli_error($conn);
			ShowMenu();
			
			
			echo "<div class=\"generalIndex\"><pre class=\"textStyle\">" .$judoText ."</pre></div>";
		?>
	</body>
</html>
