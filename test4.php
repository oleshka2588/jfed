<html>
<head><script src="JFed.js"></script></head>
<body>

<script>
	window.onload = function (event) {
		//imag = new Image();
		//imag.src = "Images/imagetest.jpg";
		//var images = document.getElementById("12");
		path = "Images/imagetest.jpg";
		w = window.open("judoPage.php?imaginea=" + path);
		image = w.document.getElementById("imaginea");

		//setTimeout(getPr(w), 1000);
		//w.onload = function(event) { var images = document.getElementsByTagName("img"); document.write("test");  sessionStorage.setItem('width', images.length); sessionStorage.setItem('height', images[0].height);}
		document.write(image.width);
		//w.close();
	}
</script>
</body>
</html>