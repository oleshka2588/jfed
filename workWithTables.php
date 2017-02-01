<?php
	session_start();
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dataBaseName = "jfed2";
	$conn = mysqli_connect($servername, $username, $password, $dataBaseName);
	mysqli_error($conn);
	$sqlQuery ='';
	
	if (isset($_GET['operation']) and $_GET['operation'] == "insert") {
		$Id = generateIdForTable($_GET['table'], $conn);
		if ($_GET['table'] == "news") {
			$sqlQuery ='INSERT
						INTO news (Id, Topic, Date, Text)
						VALUES (' .$Id .',"'. $_POST["topic"] . '","'. $_POST["date"] .'","' .$_POST["text"] .'")';
		}
		elseif ($_GET['table'] == "events") {
			$sqlQuery ='INSERT
						INTO events (Id, Topic, EvtDate, Description)
						VALUES (' .$Id .',"'. $_POST["topic"] . '","'. $_POST["date"] .'","' .$_POST["text"] .'")';
		}
		elseif ($_GET['table'] == "photo") {
			$sqlQuery ='INSERT
						INTO photo (Id, TableName, Place, IdRecord, width, height, Date)
						VALUES (' .$Id .',"'. $_GET["TableName"] . '","'. $_GET["Place"] .'","' .$_GET["IdRecord"] .'","' .$_GET["width"] .'","' .$_GET["height"] .'","' .date("Y/m/d") .'")';
		}
		elseif ($_GET['table'] == "video") {
			$sqlQuery ='INSERT
						INTO video (Id, TableName, Place, IdRecord, Date, Name, ThumbnailPlace)
						VALUES (' .$Id .',"'. $_GET["TableName"] . '","'. $_GET["Place"] .'","' .$_GET["IdRecord"] .'","' .date("Y/m/d") .'","' .$_POST["Name"] .'","' .$_GET["ThumbnailPlace"] .'")';
		}
		elseif ($_GET['table'] == "persons") {
			$sqlQuery ='INSERT
						INTO persons (Id, Name, Surname, FathersName, BirthDate, Text)
						VALUES (' .$Id .',"'. $_POST["Name"] . '","'. $_POST["Surname"] .'","' .$_POST["FathersName"] .'","' .$_POST["BirthDate"] .'","' .$_POST["Text"] .'")';
		}
	}
	elseif (isset($_GET['operation']) and $_GET['operation'] == "delete") {
		if ($_GET['table'] == "photo") {
			$sqlQuery ='DELETE
						From photo Where photo.Place ="' .$_GET['place'] .'"';
		}
		elseif ($_GET['table'] == "video") {
			$sqlQuery ='DELETE
						From video Where video.ThumbnailPlace ="' .$_GET['place'] .'"';
		}
	}
	elseif (isset($_GET['operation']) and $_GET['operation'] == "update") {
		if ($_GET['table'] == "persons") {
			$sqlQuery ='Update persons
						set persons.Portrait="' .$_GET['place'] .'" Where persons.Id="' .$_GET['Id'].'"';
		}
	}
	
	if ($conn->query($sqlQuery) === TRUE) {
		echo "Operation executed successfully";
	}
	else {
		echo "Error: " . $sqlQuery . "<br>" . $conn->error;
	}
	
	mysqli_close($conn);
	
	function generateIdForTable($table, $conn) {
		$counter = 0;
		$text = "";
		$text = makeId();
		$sqlQuery ='SELECT Id
					FROM ' .$table .'
					Where' .$table .'.Id='.$text;
		$result = $conn->query($sqlQuery);
		while($result or ($counter<20)) {
			$text = makeId();
			$sqlQuery ='SELECT Id
						FROM ' .$table .'
						Where' .$table .'.Id='.$text;
			$result = $conn->query($sqlQuery);
			$counter++;
		}
		return '"'.$text.'"';
	}
	
	function makeId() {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$randstring = "";
		for ($i = 0; $i < 16; $i++) {
			$randstring .= (string)$characters[rand(0, strlen($characters)-1)];
		}
		return $randstring;
	}
	
?>