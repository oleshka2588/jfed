<html>
<head><script src="JFed.js"></script></head>
<body>
<form action="loadData()">
	<div>
	<img id="testFk" src=""/>
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="button" name="clickTest" onclick="loadData()" value="push it">
	<div style="width:300px; height:10px; border: 1px solid black; margin-top:3px;"><div id="indicator" style="width:0px; height:10px; background-color: green;"></div></div>
	</div>
</form>
<script>
	function loadDataVeche() {
		var reader = new FileReader();
		var xmlhttp = new XMLHttpRequest();
		var counter = 0;
		file = document.getElementById('fileToUpload').files[0];
		step = file.size/300;
		chunkSize = 10000;
		var blob = file.slice(0, chunkSize);
		reader.onerror = function(event) {
			console.error("File could not be read! Code " + event.target.error.code);
		};
		
		reader.onloadend = function(event) {
			var fData = new FormData();
			BArr = new Int8Array(reader.result);
			fData.append('length', BArr.length);
			var dataStr = '';
			var i = 0;
			for (i = 0;i<BArr.length - 1;i++) {
				dataStr += (BArr[i] + ":");
			}
			dataStr += BArr[i+1];
			fData.append('data', dataStr);
			xmlhttp.open("POST",
						 "test2.php?"
						 + "fileName=" +(file.name)
						 + "&" + "fileSize=" +(file.size)
						 + "&" + "counter=" + "1"
						 , true);
			xmlhttp.send(fData);
		}
		
		xmlhttp.onreadystatechange = function() {
			counter = parseInt(sessionStorage.getItem('counter'));
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				begin = counter * chunkSize;
				end = ((counter + 1 * chunkSize) >= file.size) ? file.size : (counter + 1) * chunkSize;
				blob = file.slice(begin, end);
				if (counter * chunkSize < file.size) {
					reader.readAsArrayBuffer(blob);
					updateIndicator(end,step);
					sessionStorage.setItem('counter', ++counter);
				}
				else {
					//code to put my image in database and get its width and height
					xmlhttp.onreadystatechange = function() { document.getElementById("response").innerHTML = xmlhttp.responseText;}
					document.getElementById("images").innerHTML += '<img id="img"' + '" src="Images/' + file.name + '" style="margin:5px;" />';
					var images = $('#images').children('img');
					var clientwidth = images[images.length - 1].width;
					var clientheight = images[images.length - 1].height;
					images[images.length - 1].width = 184;
					images[images.length - 1].height = 103;
					/*
					var fData = new FormData();
					xmlhttp.open("POST",
						 "workWithTables.php?"
						 + "table=" + "photo"
						 + "&TableName="
						 + "&" + "Place=" + file.name
						 + "&" + "IdRecord=" + ""
						 + "&" + "width=" + clientwidth
						 + "&" + "height=" + clientheight
						 , true);
					xmlhttp.send(fData);
					*/
				}
			}
		}
		
		if (counter == 0) {
			reader.readAsArrayBuffer(blob);
			updateIndicator(chunkSize,step);
			sessionStorage.setItem('counter', ++counter);
		}
	}
	
	function updateIndicator(readedChunkSize, step) {
		document.getElementById("indicator").style.width = readedChunkSize/step;
	}
	
	window.onload = function showImageWidth() {
		image = document.getElementById("testFk");
		image.src = "Images/butterfly_77-wallpaper-1920x1080.jpg";
		
		document.write(image.width);
		
	}
</script>
</body>
</html>