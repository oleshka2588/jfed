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
	include 'Func.php';
	include 'calendarFunctions.php';
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
	echo "<div class=\"backtop\">
			<img src=\"pictures/topban2.jpg\" position=\"relative\">
			<ul class=\"menutop\">
				<a href=\"index.php\" class=\"menuTabs1\"><span id=\"menuText\">Home</span></a>
				<a href=\"news.php\" class=\"menuTabs2\"><span id=\"menuText\">Noutati</span></a>
				<a href=\"Competitions.php\" class=\"menuTabs3\"><span id=\"menuText\">Evenimente</span></a>
				<a href=\"Judo.php\" class=\"menuTabs4\"><span id=\"menuText\">Judo</span></a>
				<a href=\"about.php\" class=\"menuTabs5\"><span id=\"menuText\">Contacte</span></a>
			</ul>
		</div>";
	*/
	ShowMenu();
	
	if (isset($_SESSION['allow'])) {
		echo '<div class="generalHome">';
		echo '<br><span id="FotoLable">Foto: </span><br>';	
		$sqlQuery = 'SELECT *
						FROM photo' or die("Error in the consult.." . mysqli_error($conn));
		$result = $conn->query($sqlQuery);
		echo mysqli_error($conn);
		ShowImagesOnFirst($result);
		
		echo '<br><span>Adauga imagine</span><input type="file" name="fileToUpload" id="fileToUpload"/><input type="button" name="loadImage" value="Save IMG" onclick="loadData()"/>';
		echo '<div style="width:960px; height:7px; border: 0px solid black; margin-top:3px;"><div id="indicator" style="width:0px; height:6px; background-color: green;"></div></div>';
		echo '<br><span id="response"></span>';
		echo '</div>';
		
		echo '<br><span id="FotoLable">Video: </span><br>';
		$sqlQuery = 'SELECT *
						FROM video' or die("Error in the consult.." . mysqli_error($conn));
		$result = $conn->query($sqlQuery);
		echo mysqli_error($conn);
		showThumbnailsOnFirst($result);
		echo '</div>';
	}
	else {
		echo '<div class="generalIndex">';
		//here we'll draw our calendar
		echo   '<span id="daysOfWeek">Mo</span>
				<span id="daysOfWeek">Tu</span>
				<span id="daysOfWeek">We</span>
				<span id="daysOfWeek">Th</span>
				<span id="daysOfWeek">Fr</span>
				<span id="daysOfWeek">Sa</span>
				<span id="daysOfWeek">Su</span>';
		//Calendar(date("d"), date("m"), date("y") + 2000);
		echo '<div id="calendarBlock">';
		echo '</div>';
		echo '<div id="dataBlock">';
		echo '</div>';
		echoData(date("Y/m/d"));
		echo '</div>';
	}
	
	function ShowImagesOnFirst($result) {
		$nr = (isset($_GET['nr'])) ? $_GET['nr'] : 0;
		$counter = 0;	
		$twelveImages = [];
		$imgPePag = 8;
		$imgPeRind = 4;
		echo '<div id="images">';
		
		while($row = mysqli_fetch_array($result)){
			if (($counter >= ($nr*$imgPePag)) and ($counter < ($nr + 1)*$imgPePag)) {
				array_push($twelveImages, $row['Place']);
			}		
			$counter++;
		}
		
		$twelveImagesLength = count($twelveImages);
		for ($i = 0; $i < $twelveImagesLength; $i++) {
			if ($i % $imgPeRind == 0){echo '<br>';}
			//echo '<div style="position:relative; float:left; padding:3px width:185px; align:center; ">';
			echo '<img src="Images/' .$twelveImages[$i] . '" width="185px" height="103px" style="margin:5px;">';
			echo '<img src="Pictures/deleteCross.png" width="18px" height="18px" style="position:relative; top:-15px; left:-30px" onclick="deleteImage(\'' .$twelveImages[$i] .'\')">';
			//echo '<div style="word-wrap: break-word; position:relative; float:right; padding:3px width:185px;"><pre>' .$twelveImages[$i] .'</pre></div>';
			//echo '</div>';
		}
		
		echo '<div>';		
		$nrPagini = $counter/$imgPePag;
		
		for ($i = 0; $i < $counter/$imgPePag and $i <= $nrPagini; $i++) {
			echo '<a href="index.php?nr=' .(($nr<=$nrPagini)?$i:$nr+$i) .'"><span>' .(($nr<=$nrPagini)?$i+1:($nr+$i+1)) .'</span></a>';
		}
		echo '</div>';
	}
	
	function showThumbnailsOnFirst($result) {
		$nr = (isset($_GET['nrT'])) ? $_GET['nrT'] : 0;
		$counter = 0;	
		$twelveImages = [];
		$imgPePag = 8;
		$imgPeRind = 4;
		echo '<div id="images">';
	
		while($row = mysqli_fetch_array($result)){
			if (($counter >= ($nr*$imgPePag)) and ($counter < ($nr + 1)*$imgPePag)) {
				array_push($twelveImages, $row['ThumbnailPlace']);
			}		
			$counter++;
		}
		
		$twelveImagesLength = count($twelveImages);
		for ($i = 0; $i < $twelveImagesLength; $i++) {
			if ($i % $imgPeRind == 0){echo '<br>';}
			//echo '<div style="position:relative; float:left; padding:3px width:185px; align:center; ">';
			echo '<img src="ThumbnailsForVideo/' .$twelveImages[$i] . '" width="185px" height="103px" style="margin:5px;">';
			echo '<img src="Pictures/deleteCross.png" width="18px" height="18px" style="position:relative; top:-15px; left:-30px" onclick="deleteThumbnail(\'' .$twelveImages[$i] .'\')">';
			//echo '<div style="word-wrap: break-word; position:relative; float:right; padding:3px width:185px;"><pre>' .$twelveImages[$i] .'</pre></div>';
			//echo '</div>';
		}
		
		echo '<div>';		
		$nrPagini = $counter/$imgPePag;
		
		for ($i = 0; $i < $counter/$imgPePag and $i <= $nrPagini; $i++) {
			echo '<a href="index.php?nrT=' .(($nr<=$nrPagini)?$i:$nr+$i) .'"><span>' .(($nr<=$nrPagini)?$i+1:($nr+$i+1)) .'</span></a>';
		}
		echo '</div>';
	}
?>

		<script>
			window.onload = function () {
				today 			= new Date();
				strOfDate 		= today.getDate() + "/" + (today.getMonth() + 1) + "/" + today.getFullYear();
				dateArr 		= strOfDate.split("/");
				cont 			= document.getElementById('calendarBlock');
				cont.innerHTML 	= drawCalendar(strOfDate);
				loadDataHomePage(dateArr[2] + '/' + (dateArr[1]) + '/' + dateArr[0]);
			}
		
			function deleteImage(name) {
				xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							 document.getElementById("response").innerHTML = xmlhttp.responseText;
							 if (xmlhttp.responseText == "Operation executed successfully") {location.reload();}
						}
					}
				xmlhttp.open("GET", "workWithTables.php?table=photo&operation=delete&place=" + name, true);
				xmlhttp.send();
			}
			
			function deleteThumbnail(name) {
				xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							 document.getElementById("response").innerHTML = xmlhttp.responseText;
							 if (xmlhttp.responseText == "Operation executed successfully") {location.reload();}
						}
					}
				xmlhttp.open("GET", "workWithTables.php?table=video&operation=delete&place=" +name, true);
				xmlhttp.send();
			}
			
			function aDayClicked(obj) {
				strDate 		= obj.getAttribute("date");
				dateArr 		= strDate.split("/");
				selectedDate 	= new Date();
				cont 			= document.getElementById('calendarBlock');
				cont.innerHTML 	= drawCalendar(strDate);
				selectedDate.setFullYear(dateArr[2], dateArr[1]-1, dateArr[0]);
				loadDataHomePage(dateArr[2] + '/' + (dateArr[1]) + '/' + dateArr[0]);
			}
			
			function loadDataHomePage(date) {
				xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
						if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
							 document.getElementById("dataBlock").innerHTML = xmlhttp.responseText;
						}
					}
				xmlhttp.open("GET", "calendar.php?f=echoData&date=\"" + date+"\"", true);
				xmlhttp.send();
			}
			
			function rightArrMonthClicked(calDate) {
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						cont = document.getElementById('calendarBlock');
						cont.innerHTML = xmlhttp.response;
					}
				}
				cont = document.getElementById('calendarBlock');
				cont.innerHTML = drawCalendar(calDate);
			}
			
			function leftArrMonthClicked(calDate) {
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function() {
					if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
						cont = document.getElementById('calendarBlock');
						cont.innerHTML = xmlhttp.response;
					}
				}
				cont = document.getElementById('calendarBlock');
				cont.innerHTML = drawCalendar(calDate);
			}			
			
			function drawCalendar(strDate) {
				dateArr 					= strDate.split("/");
				currentDate 				= new Date();
				theFirst 					= new Date();
				currentDate.setFullYear(dateArr[2], dateArr[1]-1, dateArr[0]);				
				theFirst.setFullYear(dateArr[2], dateArr[1]-1, 1);
				str 						= '';
				var monthname			 	= ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
				var currentDay				= currentDate.getDate();
				var currentMonth			= currentDate.getMonth() + 1;
				var currentYear				= currentDate.getFullYear();
				var daysNumber 				= daysInMonth(currentMonth, currentYear);
				var dayOfWeek 				= theFirst.getDay();
				var dayOfWeek				= (dayOfWeek == 0)? 7: dayOfWeek;
				var nrBoxesInMonth 			= dayOfWeek-1 + daysNumber;
				var drawNrBoxes 			= nrBoxesInMonth/7 - nrBoxesInMonth%7/7;
				var drawNrBoxes 			= (nrBoxesInMonth%7 == 0)? drawNrBoxes: drawNrBoxes + 1;
				var addAtEnd 				= drawNrBoxes * 7 - nrBoxesInMonth;
				var lastMonth 				= ((currentMonth - 1) < 1)	? 12 : (currentMonth - 1);
				var nextMonth 				= ((currentMonth + 1) > 12)	? 1  : (currentMonth + 1);
				var nextYear 				= ((currentMonth + 1) > 12)	? currentYear+1 : (currentYear);
				var lastYear 				= ((currentMonth - 1) < 1)	? currentYear-1 : (currentYear);
				var daysNumberLastMonth		= daysInMonth(lastMonth, lastYear);

				str += currentYear;
				str += '<br>';
				str += '<img src="calendarImgs/arrayCalLeft.png" style="position:relative;top:1px" onclick="leftArrMonthClicked(\'' + currentDay + '/' + lastMonth + '/' + lastYear + '\')"><div id="monthName"  style="display:inline-block; width:80px;">' + monthname[currentMonth - 1] + '</div><img src="calendarImgs/arrayCalRight.png" style="position:relative;top:1px" onclick="rightArrMonthClicked(\'' + currentDay +'/' + nextMonth +'/' + nextYear +'\')">';
				str += '<br>';
				
				for (i = 1; i < dayOfWeek; i++) {
					str += "<a id=\"calendarDayDifferentWeek\" date=\"" + (daysNumberLastMonth - dayOfWeek+1 + i) + "/" + lastMonth + "/" + lastYear + "\" onclick=\"aDayClicked(this)\">" + (daysNumberLastMonth - dayOfWeek+1 + i) + "</a>";
				}
				for (i = 0; i < daysNumber; i++) {
					if ((dayOfWeek - 1 + i) % 7 == 0) {str +='<br>';}
					if ((i+1) == currentDay) { current = "<span style=\"color:white;text-align: center; line-height: 30px;\">" +(i+1)+ "</span>"; } else { current = (i+1); }
					str += "<a id=\"calendarDay\" date=\"" + (i+1) + "/" + currentMonth + "/" + currentYear + "\" onclick=\"aDayClicked(this)\">" + current + "</a>";
				}
				for (i = 0; i < addAtEnd; i++) {
					str += "<a id=\"calendarDayDifferentWeek\" date=\"" + (i+1) + "/" + nextMonth + "/" + nextYear + "\" onclick=\"aDayClicked(this)\">" + (i+1) + "</a>";
				}
				return str;
			}
			
			function daysInMonth(month,year) {
				return new Date(year, month, 0).getDate();
			}
						
		</script>
	</body>
</html>
