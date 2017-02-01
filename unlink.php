<?php
	
	if (isset($_GET['fileName']) and isset($_SESSION['allow'])) {
		try {
			$result = unlink($_GET['fileName']);
		} catch (Exception $e) {
			echo $e;
		}
		if ($result) {
			echo 'Operation succeeded.';
		}
	}
?>