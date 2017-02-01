<?php
	session_start();
	$dir = '';
	try {
		if (!isset($_SESSION['counter'])) {
			$_SESSION['counter'] = 1;
		}
		else {
			$_SESSION['counter'] += 1;
		}
		
		if (isset($_GET['fileName'])) {
			if (isset($_GET['fileType'])) {
				if ($_GET['fileType'] == 'video') { $dir = 'Video/'; }
				elseif ($_GET['fileType'] == 'thumbnail') { $dir = 'ThumbnailsForVideo/'; }
				elseif ($_GET['fileType'] == 'image') { $dir = 'Images/'; }
				elseif ($_GET['fileType'] == 'portrait') { $dir = 'Portraits/'; }
			}
			$file = fopen($dir .$_GET['fileName'], 'a+');
			
			$length = $_POST['length'];
			for ($i = 0; $i < $length; $i++) {
				$variable = chr((int)$_POST["data" .$i]);
				fwrite($file, $variable);
			}
			fclose($file);
		}
	}
	catch(Exception $e) {
		echo 'Message: ' .$e->getMessage();
	}
?>