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
			//include 'MyClasses.php';
			$servername = "localhost";
			$username = "root";
			$password = "";
			$dataBaseName = "jfed2";
			$conn = mysqli_connect($servername, $username, $password, $dataBaseName);
			mysqli_error($conn);
			
			session_start();
			if (isset($_SESSION['allow'])){
				//parte admin
				header("Location:news.php");
				exit();
			}
			elseif (isset($_POST['log']) or isset($_POST['pass'])){
				$sqlQuery = 'SELECT Id,Rights 
						FROM users where Name =\''.$_POST['log'].'\' and password=\''.$_POST['pass'].'\'' or die("Error in the consult.." . mysqli_error($conn));
				$result = $conn->query($sqlQuery);
				echo mysqli_error($conn);
				
				$row = mysqli_fetch_array($result);								
				if ($row["Rights"] == 'Admin' and $result){
					$_SESSION['allow'] = true;
				}
				else{					
					echo"
					<div class=\"backtop\">
					</div>";
					echo "<form action=\"dev.php\" method=\"POST\">
					<br><br><div><span>Login:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span><input type=\"text\" name=\"log\" /></div> 
					<div><br><span>Password:&nbsp </span><input type=\"password\" name=\"pass\" /></div>
					<div><br><button type=\"submit\" name=\"Login\" >Log in</button></div>
					<div><br><span id=\"ErrorStyle\">User name or password is wrong.</span></div>
					</form>";
				}
				mysqli_close($conn);			
			}
			else{				
				echo"
				<div class=\"backtop\">
				<img src=\"pictures\\topban2.jpg\" position=\"relative\">
				</div>";
				echo "<form action=\"dev.php\" method=\"POST\">
				<br><br><div><span>Login:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span><input type=\"text\" name=\"log\" value=\"Admin\"/></div> 
				<div><br><span>Password:&nbsp </span><input type=\"password\" name=\"pass\" value=\"123\"/></div>
				<div><br><button type=\"submit\" name=\"Login\" >Log in</button></div>
				</form>";
			}
		?>
		
		
	</body>
</html>
