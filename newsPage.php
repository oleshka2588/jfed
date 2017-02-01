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
				$sqlQuery = 'SELECT text, topic, date
					FROM news
					where Id="'.$_GET['id'].'" ORDER BY ID LIMIT 1'
					or die("Error in the consult.." . mysqli_error($conn));
				$result = $conn->query($sqlQuery);
			}
			$row =[];
			if ($result) {
				$row = mysqli_fetch_array($result);
			}
			
			$topic 	= (isset($row['topic'])) ? $row['topic'] : "";
			$text 	= (isset($row['text'])) ? $row['text'] : "";
			$date 	= (isset($row['date'])) ? $row['date'] : date("Y/m/d");
			
			if (count($row) == 0) {
				echo '<div class="general">
				<form>
					<span>Data: </span><input id="dataInput" type="text" name="data" value="' .$date .'" />
					<br><span>Tema: </span><input id="topic" type="text" name="tema" value="' .$topic .'"/>
					<br><textarea id="textArea" name="TextInfo" cols="120" rows="25">' .$text .'</textarea>	
					<br><input id="SaveButton" type="button" name="Save" value="Save" onclick="saveData()">
				</form>
				
				<span id="showResponse"><span>
				<br><div id="images"></div>';
			}
			else {
				echo "<div class=\"general\">";
				echo "<pre class=\"textStyle\"><h3 id=\"topic\">" .$topic ."</h3>" .$text;
				
				if (isset($_GET['id'])) {
					$sqlQuery = 'SELECT *
					FROM photo inner join news on photo.IdRecord = news.Id where news.Id="' .$_GET['id'] .'"'  or die("Error in the consult.." . mysqli_error($conn));
					$result = $conn->query($sqlQuery);
					echo mysqli_error($conn);
					ShowImages($result);
					
					$sqlQuery = 'SELECT *
					FROM video inner join news on video.IdRecord = news.Id where news.Id="' .$_GET['id'] .'"'  or die("Error in the consult.." . mysqli_error($conn));
					$result = $conn->query($sqlQuery);
					echo mysqli_error($conn);
					showThumbnails($result);
				}				
				echo "</pre>";
				
				if(isset($_SESSION['allow'])) {
					echo '<div id="uploadedImage"></div>';
					echo '<br><span>Adauga imagine </span><input type="file" name="fileToUpload" id="image"/><input type="button" name="loadImage" value="Save IMG" onclick="loadData(\'' .$_GET['id'] .'\' ,\'news\', \'image\')"/>';
					echo '<br><span>Nume pentru video </span><textArea name="fileToUpload" id="videoName" rows="4" cols="50"></textArea><br><span>Thumbnail pentru video </span><input type="file" name="fileToUpload" id="thumbnail"/><span>Adauga video </span><input type="file" name="fileToUpload" id="video"/><input type="button" name="loadVideo" value="Save Video" onclick="loadData(\'' .$_GET['id'] .'\' ,\'news\', \'thumbnail\')"/>';
					echo '<div id="indicatorContainer" style="width:960px; height:7px; border: 0px solid black; margin-top:3px;"><div id="indicator" style="width:0px; height:6px; background-color: green;"></div></div>';
					echo '<span id="showResponse"><span>';
					echo "</div>";
				}				
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
				var topic 		= document.getElementById("topic");
				var textArea 	= document.getElementById("textArea");
				var dataInput	= document.getElementById("dataInput");
				var xmlhttp 	= new XMLHttpRequest();
				fData 			= new FormData();
				fData.append('topic' 	,topic.value);
				fData.append('text' 	,textArea.value);
				fData.append('date' 	,dataInput.value);
				
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						document.getElementById("showResponse").innerHTML = xmlhttp.responseText;
					}
				}
				xmlhttp.open("POST" ,"workWithTables.php?table=news&operation=insert" ,true);
				xmlhttp.send(fData);
			}
			
			function showImages(element ,number) {
				imgPePag 		= 3;
				imgPeRind 		= 3;
				totalImagini 	= images.length-1;
				imgDiv 			= document.getElementById("centerImages");
				imgDiv.innerHTML = "";
				document.getElementById("circleGray").id = "circleBlack";
				imgDiv.innerHTML += "<h4>Foto:</h4>";
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
				imgDiv.innerHTML += "<h4>Video:</h4>";
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