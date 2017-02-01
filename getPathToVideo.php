<?php
	$servername = "localhost";
	$username = "root";
	$password = "";
	$dataBaseName = "jfed2";
	$conn = mysqli_connect($servername, $username, $password, $dataBaseName);
	mysqli_error($conn);
	$sqlQuery = 'SELECT *
					FROM video WHERE video.ThumbnailPlace="'.$_GET['path'].'" Order by Id Limit 1' or die("Error in the consult.." . mysqli_error($conn));
	$result = $conn->query($sqlQuery);

	if ($row = mysqli_fetch_array($result)){
		echo $row['Place'];
	}
?>