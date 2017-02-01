<html>
<body>
<form action="loadData()">
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="button" name="clickTest" onclick="loadData()" value="push it">
</form>
<script>
	function loadData() {
		var reader = new FileReader();
		var xmlhttp = new XMLHttpRequest();
		var counter = 0;
		file = document.getElementById('fileToUpload').files[0];
		chunkSize = 500;
		var blob = file.slice(0, chunkSize);
		reader.onerror = function(event) {
			console.error("File could not be read! Code " + event.target.error.code);
		};
		
		reader.onloadend = function(event) {
			var fData = new FormData();
			BArr = new Int8Array(reader.result);
			fData.append('length', BArr.length);
			for (i=0;i<BArr.length;i++) {
				fData.append('data' + i, BArr[i]);
			}
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
				document.write("<br>  --" + counter + " -- " + chunkSize * counter + " -- " +  (counter+1)*chunkSize);
				if (counter * chunkSize < file.size) {
					reader.readAsArrayBuffer(blob);
					sessionStorage.setItem('counter', ++counter);
				}
			}
		}
		
		if (counter == 0) {
			reader.readAsArrayBuffer(blob);
			sessionStorage.setItem('counter', ++counter);
		}
	}
</script>
</body>
</html>