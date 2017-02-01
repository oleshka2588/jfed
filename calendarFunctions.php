<?php
	$servername 		= "localhost";
	$username 			= "root";
	$password 			= "";
	$dataBaseName 		= "jfed2";
	
	function Calendar($currentDay, $currentMonth, $currentYear) {
		echo '<div id="calendarBlock">';
		$daysNumber 			= cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
		$dayOfWeek 				= jddayofweek(gregoriantojd($currentMonth, 1, $currentYear), 0);
		$dayOfWeek				= ($dayOfWeek == 0)? 7: $dayOfWeek;
		$nrBoxesInMonth 		= $dayOfWeek-1 + $daysNumber;
		$drawNrBoxes 			= $nrBoxesInMonth/7 - $nrBoxesInMonth%7/7;
		$drawNrBoxes 			= ($nrBoxesInMonth%7 == 0)? $drawNrBoxes: $drawNrBoxes + 1;
		$addAtEnd 				= $drawNrBoxes * 7 - $nrBoxesInMonth;
		$lastMonth 				= (($currentMonth - 1) < 1)	? 12 : ($currentMonth - 1);
		$nextMonth 				= (($currentMonth + 1) > 12)	? 1  : ($currentMonth + 1);
		$nextYear 				= (($currentMonth + 1) > 12)	? $currentYear+1 : ($currentYear);
		$lastYear 				= (($currentMonth - 1) < 1)	? $currentYear-1 : ($currentYear);
		$daysNumberLastMonth	= cal_days_in_month(CAL_GREGORIAN, $lastMonth, $lastYear);

		
		echo $currentYear;
		echo '<br>';
		echo '<img src="calendarImgs/arrayCalLeft.png" style="position:relative;top:1px" onclick="leftArrMonthClicked(\'' .$currentDay .'/' .$lastMonth .'/' .$lastYear .'\')"><div id="monthName"  style="display:inline-block; width:80px;">'.jdmonthname(gregoriantojd($currentMonth, 1, $currentYear),1).'</div><img src="calendarImgs/arrayCalRight.png" style="position:relative;top:1px" onclick="rightArrMonthClicked(\'' .$currentDay .'/' .$nextMonth .'/' .$nextYear .'\')">';
		echo '<br>';
		
		for ($i = 1; $i < $dayOfWeek; $i++) {
			echo "<a id=\"calendarDayDifferentWeek\" date=\"" .$lastYear ."/" .$lastMonth ."/" .($daysNumberLastMonth - 7 + $i) ."\" onclick=\"aDayClicked(this)\">" .($daysNumberLastMonth - $dayOfWeek+1 + $i) ."</a>";
		}
		for ($i = 0; $i < $daysNumber; $i++) {
			if (($dayOfWeek - 1 + $i) % 7 == 0) {echo '<br>';}
			echo "<a id=\"calendarDay\" date=\"" .$currentYear ."/" .$currentMonth ."/" .($i+1) ."\" onclick=\"aDayClicked(this)\">" .($i+1) ."</a>";
		}
		for ($i = 0; $i < $addAtEnd; $i++) {
			echo "<a id=\"calendarDayDifferentWeek\" date=\"" .$nextYear ."/" .$nextMonth ."/" .($i+1) ."\" onclick=\"aDayClicked(this)\">" .($i+1) ."</a>";
		}
		echo '</div>';
	}
	
	function echoData($date) {
		global $servername, $username, $password, $dataBaseName;
		$conn = mysqli_connect($servername, $username, $password, $dataBaseName);
		mysqli_error($conn);
		
		$sqlQuery = 'SELECT *
					FROM events WHERE events.EvtDate = ' .$date .' Order by EvtDate DESC' or die("Error in the consult.." .mysqli_error($conn));
		$result = $conn->query($sqlQuery);
		echo mysqli_error($conn);
		if (mysqli_num_rows($result) > 0) { echo '<h4>Evenimente:</h4>'; }
		while($row = mysqli_fetch_array($result)) {
			echo "<pre class=\"textStyle\"><h3 id=\"topic\">" .$row['Topic'] ."</h3>" .$row['Description'];
			echo "<br><a href=\"competitionPage.php?id=" .$row['Id']. "\" class=\"readLink\">mai mult ...</a>";
			echo "</pre>";
		}
		
		$sqlQuery = 'SELECT *
						FROM news WHERE news.Date = ' .$date .' Order by Date DESC' or die("Error in the consult.." .mysqli_error($conn));
		
		$result = $conn->query($sqlQuery);
		echo mysqli_error($conn);
		if (mysqli_num_rows($result) > 0) { echo '<h4>Noutati:</h4>'; }
		while($row = mysqli_fetch_array($result)) {
			$numberOfChars = mystrpos($row['Text'] ," " ,600);
			if (!$numberOfChars) { $numberOfChars = 600;}
			echo "<pre class=\"textStyle\"><h3 id=\"topic\">" .$row['Topic'] ."</h3><div class=\"textStyle2\">" .substr($row['Text'] ,0 ,$numberOfChars);
			echo "<a href=\"newsPage.php?id=" .$row['Id']. "\" class=\"readLink\">...</a>";
			echo "</div></pre>";
		}
		
		mysqli_close($conn);
	}
	
	function mystrpos($str, $char, $nr) {
		$arr = str_split($str);
		$length = count($arr);
		for ($i = $nr;$i < $length; $i++) {
			if( $arr[$i] == $char) {
				return $i;
			}
		}
		return false;
	}
?>