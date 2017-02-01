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
			/*
			isset($_SESSION['allow']
				*/
				ShowMenu();
				$sqlQuery = 'SELECT *
					FROM events' or die("Error in the consult.." . mysqli_error($conn));
				$result = $conn->query($sqlQuery);
				
				echo mysqli_error($conn);
				if(isset($_SESSION['allow'])) {
					echo "<button  type=\"button\" onclick=\"location.href='competitionPage.php?Id='\"><img src=\"Pictures/plus.png\" width=\"20px\" height=\"20px\"></button>"; //knopka
				}
				
				echo "<div class=\"general\">";
				while ($row = mysqli_fetch_array($result)) {
					if ($counter >= 10*$numberResults and $counter < 10*($numberResults + 1)){
						echo "<pre class=\"textStyle\"><h3 id=\"topic\">" .$row['Topic'] ."</h3>" .$row['Description'];
						echo "<br><a href=\"competitionPage.php?id=" .$row['Id']. "\" class=\"readLink\">mai mult ...</a>";
						echo "</pre>";						
					}
					$counter++;
					$max++;					
				}
				if ($counter == 0) { echo "Nu sunt evenimente."; }
				$displayStr = "<div>";
				$max = $max/10;
				for ($i = 0; $i < $max; $i++) {
					$displayStr = $displayStr ."<a href=\"competitions.php?numberResults=" .$i ."\" id=\"numeration\">" .($i + 1) ."</a>";
				}
				$displayStr = $displayStr ."</div>";
				echo $displayStr;
				echo "</div>";
			//}
			mysqli_close($conn);
		?>
	</body>
</html>
