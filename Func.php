<?php
	function ShowMenu() {
		echo "<div class=\"backtop\" style=\"background-color:black;\">
			<a href=\"index.php\"><img src=\"Pictures/stema.png\"/ height=\"100px\"></a>
			<ul class=\"menutop\">
				<a href=\"index.php\" class=\"menuTabs1\"><span id=\"menuText\">Home</span></a>
				<a href=\"news.php\" class=\"menuTabs2\"><span id=\"menuText\">Noutati</span></a>
				<a href=\"Competitions.php\" class=\"menuTabs3\"><span id=\"menuText\">Evenimente</span></a>
				<a href=\"Judo.php\" class=\"menuTabs4\"><span id=\"menuText\">Judo</span></a>
				<a href=\"about.php\" class=\"menuTabs5\"><span id=\"menuText\">Contacte</span></a>
			</ul>
		</div>";
	}
	
	function ShowImages($result) {
		$nr = (isset($_GET['nr'])) ? $_GET['nr'] : 0;
		$totalImagini = 0;
		$arrImages = [];
		$imgPePag = 3;
		$imgPeRind = 3;
		echo '<div id="images">';
		echo '<div id="imgsForJS" style="visibility:hidden;  height:0px;">';
		while($row = mysqli_fetch_array($result)){
			array_push($arrImages, $row['Place']);
			echo $row['Place'] .';';
			$totalImagini++;
		}
		echo '</div>';
		echo '<div id="centerImages">';
		if ($totalImagini) {echo '<h4>Foto:</h4>';}
		for ($i = $nr*$imgPePag; $i < ($imgPePag * ($nr+1)) and $i < $totalImagini; $i++) {
			if ($i % $imgPeRind == 0 and $imgPePag > $imgPeRind){echo '<br>';}
				echo '<img id="imgToMove" src="Images/' .$arrImages[$i] . '" height="103px" style="margin:5px;" onclick="showBigPicture(images ,\'' .$arrImages[$i] .'\')">';
				if(isset($_SESSION['allow']) and $_SESSION['allow'] == true){echo '<img id="1cngV" src="Pictures/deleteCross.png" width="18px" height="18px" style="position:relative; top:-15px; left:-30px" onclick="deleteImage("' .$arrImages[$i] .'")">';}
		}
		echo '</div>';
		echo '<div style="width:' .($totalImagini/$imgPePag*13+13) .'px; margin-left:auto;	margin-right:auto; height:20px;">';
		$nrPagini = $totalImagini/$imgPePag;
		
		for ($i = 0; $i < $totalImagini/$imgPePag and $i <= $nrPagini; $i++) {
			$circleColor = ($i == 0)?'circleGray':'circleBlack';
			echo '<a><div id="' .$circleColor .'" onclick="showImages(this ,' .(($nr<=$nrPagini)?$i:$nr+$i) .')"></div></a>';
		}
		echo '</div>';
	}
	
	function showThumbnails($result) {
		$nr = (isset($_GET['nr'])) ? $_GET['nr'] : 0;
		$totalImagini = 0;
		$arrImages = [];
		$imgPePag = 3;
		$imgPeRind = 3;
		echo '<div id="images">';
		echo '<div id="thumbnlsForJS">';
		while($row = mysqli_fetch_array($result)){
			array_push($arrImages, $row['ThumbnailPlace']);
			echo $row['ThumbnailPlace'] .';';
			$totalImagini++;
		}
		echo '</div>';
		echo '<div id="centerTumbnails">';
		if ($totalImagini) {echo '<h4>Video:</h4>';}
		for ($i = $nr*$imgPePag; $i < ($imgPePag * ($nr+1)) and $i < $totalImagini; $i++) {
			if ($i % $imgPeRind == 0 and $imgPePag > $imgPeRind){echo '<br>';}
				echo '<img id="imgToMove" src="ThumbnailsForVideo/' .$arrImages[$i] . '" height="103px" style="margin:5px;" onclick="playVideo(thumbnls ,\'' .$arrImages[$i] .'\')">';
				if(isset($_SESSION['allow']) and $_SESSION['allow'] == true){echo '<img id="1cngV" src="Pictures/deleteCross.png" width="18px" height="18px" style="position:relative; top:-15px; left:-30px" onclick="deleteImage("' .$arrImages[$i] .'")">';}
		}
		echo '</div>';
		echo '<div style="width:' .($totalImagini/$imgPePag*12+12) .'px; margin-left:auto;	margin-right:auto; height:20px;">';
		$nrPagini = $totalImagini/$imgPePag;
		
		for ($i = 0; $i < $totalImagini/$imgPePag and $i <= $nrPagini; $i++) {
			$circleColor = ($i == 0)?'circleGray':'circleBlack';
			echo '<a><div id="' .$circleColor .'" onclick="showThumbnails(this ,' .(($nr<=$nrPagini)?$i:$nr+$i) .')"></div></a>';
		}
		echo '</div>';
	}
?>