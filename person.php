<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<title></title>
		<link href="JFedStyles.css" rel="stylesheet" type="text/css">
		<script src="jquery-1.11.3.js"></script>
		<script src="JFed.js"></script>
	</head>
	<body>
	<?php
		include("Func.php");
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
			if(isset($_GET['id'])){
				$sqlQuery = 'SELECT Name, Surname, FathersName,BirthDate,Text
					FROM persons
					where Id="'.$_GET['id'].'" ORDER BY ID LIMIT 1'
					or die("Error in the consult.." . mysqli_error($conn));
				$result = $conn->query($sqlQuery);
			}
			$row =[];
			if ($result) {
				$row = mysqli_fetch_array($result);
			}
			
			$Name 			= (isset($row['Name'])) 		? $row['Name'] 			: "";
			$Surname		= (isset($row['Surname'])) 		? $row['Surname'] 		: "";
			$FathersName	= (isset($row['FathersName'])) 	? $row['FathersName'] 	: "";
			$BirthDate		= (isset($row['BirthDate'])) 	? $row['BirthDate'] 	: date("Y/m/d");
			$Text			= (isset($row['Text'])) 		? $row['Text'] 			: "";
			
			if (count($row) == 0) {
				echo '<div class="general">
				<form>
					<span>Data nasterii: </span><input id="BirthDate" type="text" name="BirthDate" value="' .$BirthDate .'" />
					<br><span>Numele,de familie, dupa tata: </span>
					<input id="Name" type="text" name="Name" value="' .$Name .'"/>
					<input id="Surname" type="text" name="Surname" value="' .$Surname .'"/>
					<input id="FathersName" type="text" name="FathersName" value="' .$FathersName .'"/>
					<br><textarea id="Text" name="TextInfo" cols="120" rows="25">' .$Text .'</textarea>	
					<br><input id="SaveButton" type="button" name="Save" value="Save" onclick="saveData()">
				</form>
				
				<span id="showResponse"><span>
				<br><div id="images"></div>';
			}
			else {
				echo "<div class=\"general\">";
				echo "<pre class=\"textStyle\"><h3 id=\"topic\">" .$Name .' '.$Surname .' '.$FathersName ."</h3>" .$Text;
				
				if (isset($_GET['id'])) {
					$sqlQuery = 'SELECT *
					FROM photo inner join persons on photo.IdRecord = persons.Id where persons.Id="' .$_GET['id'] .'"'  or die("Error in the consult.." . mysqli_error($conn));
					$result = $conn->query($sqlQuery);
					echo mysqli_error($conn);
					ShowImages($result);
					
					$sqlQuery = 'SELECT *
					FROM video inner join persons on video.IdRecord = persons.Id where persons.Id="' .$_GET['id'] .'"'  or die("Error in the consult.." . mysqli_error($conn));
					$result = $conn->query($sqlQuery);
					echo mysqli_error($conn);
					showThumbnails($result);
				}
				
				echo "</pre>";
				echo '<div id="uploadedImage"></div>';
				echo '<br><span>Adauga imagine </span><input type="file" name="fileToUpload" id="image"/><input type="button" name="loadImage" value="Save IMG" onclick="loadData(\'' .$_GET['id'] .'\' ,\'persons\', \'image\')"/>';
				echo '<br><span>Adauga portret </span><input type="file" name="fileToUpload" id="portrait"/><input type="button" name="loadImage" value="Save IMG" onclick="loadData(\'' .$_GET['id'] .'\' ,\'persons\', \'portrait\')"/>';
				echo '<br><span>Nume pentru video </span>
				<textArea name="fileToUpload" id="videoName" rows="4" cols="50"></textArea>
				<br><span>Thumbnail pentru video </span><input type="file" name="fileToUpload" id="thumbnail"/>
				<span>Adauga video </span>
				<input type="file" name="fileToUpload" id="video"/>
				<input type="button" name="loadVideo" value="Save Video" onclick="loadData(\'' .$_GET['id'] .'\' ,\'persons\', \'thumbnail\')"/>';
				echo '<div id="indicatorContainer" style="width:960px; height:7px; border: 0px solid black; margin-top:3px;"><div id="indicator" style="width:0px; height:6px; background-color: green;"></div></div>';
				echo '<span id="showResponse"><span>';
				echo "</div>";
			}
		mysqli_close($conn);

	?>
	
		<script>
			var images 		= [];
			var thumbnls 	= [];

			window.onload = function(event) {
				elem = document.getElementById("imgsForJS");
				if (elem) {
					images = elem.innerHTML.split(";");
					elem.parentNode.removeChild(elem);
				}
				elem = document.getElementById("thumbnlsForJS");
				if (elem) {
					thumbnls = elem.innerHTML.split(";");
					elem.parentNode.removeChild(elem);
				}
			}
		
			function saveData() {
				var BirthDate 		= document.getElementById("BirthDate");
				var Name 			= document.getElementById("Name");
				var Surname			= document.getElementById("Surname");
				var FathersName		= document.getElementById("FathersName");
				var Text			= document.getElementById("Text");
				var xmlhttp 		= new XMLHttpRequest();
				fData 				= new FormData();
				fData.append('BirthDate'	,BirthDate.value);
				fData.append('Name' 		,Name.value);
				fData.append('Surname' 		,Surname.value);
				fData.append('FathersName'	,FathersName.value);
				fData.append('Text' 		,Text.value);
				
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("showResponse").innerHTML = xmlhttp.responseText;
					}
				}
				xmlhttp.open("POST" ,"workWithTables.php?table=persons&operation=insert" ,true);
				xmlhttp.send(fData);
			}
			
			function showImages(element ,number) {
				imgPePag 		= 3;
				imgPeRind 		= 3;
				totalImagini 	= images.length-1;
				imgDiv 			= document.getElementById("centerImages");
				imgDiv.innerHTML = "";
				document.getElementById("circleGray").id = "circleBlack";
				
				for (i = number*imgPePag; i < (imgPePag * (number+1)) && i < totalImagini; i++) {
					if (i % imgPeRind == 0 && imgPePag > imgPeRind){imgDiv.innerHTML += '<br>';}
						imgDiv.innerHTML += '<img id="imgToMove" src="Images/' +images[i] +'" height="103px" style="margin:5px;" onclick="showBigPicture(images ,\'' +images[i] +'\')">';
						//if(isset($_SESSION['allow']) and $_SESSION['allow'] == true){echo '<img id="1cngV" src="Pictures/deleteCross.png" width="18px" height="18px" style="position:relative; top:-15px; left:-30px" onclick="deleteImage("' .$arrImages[$i] .'")">';}
						element.id = "circleGray";
				}
			}
			
			function showThumbnails(element ,number) {
				imgPePag = 3;
				imgPeRind = 3;
				totalImagini = thumbnls.length-1;
				imgDiv = document.getElementById("centerTumbnails");
				document.getElementById("circleGray").id = "circleBlack";
				imgDiv.innerHTML = "";
				for (i = number*imgPePag; i < (imgPePag * (number+1)) && i < totalImagini; i++) {
					if (i % imgPeRind == 0 && imgPePag > imgPeRind){imgDiv.innerHTML += '<br>';}
						imgDiv.innerHTML += '<img id="imgToMove" src="ThumbnailsForVideo/' +thumbnls[i] +'" height="103px" style="margin:5px;">';
						//if(isset($_SESSION['allow']) and $_SESSION['allow'] == true){echo '<img id="1cngV" src="Pictures/deleteCross.png" width="18px" height="18px" style="position:relative; top:-15px; left:-30px" onclick="deleteImage("' .$arrImages[$i] .'")">';}
						element.id = "circleGray";
				}
			}
			
		</script>
	</body>
</html>