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
		ShowMenu();
			
			// Create connection
			$conn = mysqli_connect($servername, $username, $password, $dataBaseName);
			mysqli_error($conn); 
			// Check connection
			$result = null;
			if(isset($_GET['id'])) {
				$sqlQuery = 'SELECT Description, Topic, EvtDate
					FROM events
					where Id='.$_GET['id'].' ORDER BY ID LIMIT 1'
					or die("Error in the consult.." . mysqli_error($conn));
				$result = $conn->query($sqlQuery);
			}
			$row =[];
			if ($result) {
				$row = mysqli_fetch_array($result);
			}			
			
			$topic 	= (isset($row['Topic'])) ? $row['Topic'] : "";
			$text 	= (isset($row['Description'])) ? $row['Description'] : "";
			$date 	= (isset($row['EvtDate'])) ? $row['EvtDate'] : date("Y/m/d");
			if (count($row) == 0) {
				echo "<div class=\"\">
				<form action=\"showResponse()\" method=\"POST\">
					<span>Data: </span><input id=\"dataInput\" type=\"text\" name=\"data\" value=\"" .$date ."\" />
					<br><span>Tema: </span><input id=\"topic\" type=\"text\" name=\"tema\" value=\"" .$topic ."\"/>
					<br><textarea id=\"textArea\" name=\"TextInfo\" cols=\"120\" rows=\"25\">" .$text ."</textarea>
					<br></span>Adauga imagine </span><input type=\"file\" name=\"fileImage\"/>
					<br><input id=\"SaveButton\" type=\"button\" name=\"Save\" value=\"Save\" onclick=\"saveData()\">			
				</form>
				<span id=\"showResponse\"><span>";
			}
			else {
				echo "<div class=\"general\">";
				echo "<pre class=\"textStyle\"><h3 id=\"topic\">" .$topic ."</h3>" .$text;
				echo "</pre>";
				echo "</div>";
			}
		mysqli_close($conn);
	?>
	
	<script>
		function saveData() {
			var topic 		= document.getElementById("topic");
			var textArea 	= document.getElementById("textArea");
			var dataInput	= document.getElementById("dataInput");
			var xmlhttp 	= new XMLHttpRequest();
			fData 			= new FormData();
			fData.append('topic'	,topic.value);
			fData.append('text'		,textArea.value);
			fData.append('date'		,dataInput.value);
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				 document.getElementById("showResponse").innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("POST" ,"workWithTables.php?table=events&operation=insert" ,true);
			xmlhttp.send(fData);
		}
	</script>
	</body>
</html>