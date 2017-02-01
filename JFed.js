function loadData(id, table, fileType) {
	var reader = new FileReader();
	var xmlhttp = new XMLHttpRequest();
	var counter = 0;
	file = document.getElementById(fileType).files[0];
	chunkSize = 800;
	var blob = file.slice(0, chunkSize);
	step = file.size/960;
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
					 + "fileName=" + (file.name)
					 + "&" + "fileType=" + (fileType)
					 + "&" + "fileSize=" + (file.size)
					 + "&" + "counter=" + "1"
					 , true);
		xmlhttp.send(fData);
	}
	
	xmlhttp.onreadystatechange = function() {
		counter = parseInt(sessionStorage.getItem('counter'));
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			var begin = counter * chunkSize;
			var end = ((counter + 1 * chunkSize) >= file.size) ? file.size : (counter + 1) * chunkSize;
			var blob = file.slice(begin, end);
			if (counter * chunkSize < file.size) {
				reader.readAsArrayBuffer(blob);
				updateIndicator(end, step);
				sessionStorage.setItem('counter', ++counter);
			}
			else {
				//code to put my image in database and get its width and height
				//document.getElementById("uploadedImage").innerHTML = "<img id=\"uploadedImg\" src=\"Images/" +file.name +"\">";
				xmlhttp.onreadystatechange = function() { document.getElementById("showResponse").innerHTML = xmlhttp.responseText; 
				}
				imag = new Image();
				imag.src = file.name;
				//var images = document.getElementById("uploadedImg");
				//var cwidth = images.width;
				//var cheight = images.height;
				
				if (fileType == 'image') {
					xmlhttp.open("GET",
					 "workWithTables.php?"
					 + "table=photo"
					 + "&TableName=" + table
					 + "&operation=insert"
					 + "&" + "Place=" + file.name
					 + "&" + "IdRecord=" + id
					 + "&" + "width=" + cwidth
					 + "&" + "height=" + cheight
					 , true);
					xmlhttp.send();
					if (xmlhttp.responseText == "Operation executed successfully") {location.reload();}
				}
				else if (fileType == "video") {
					var nameVideo 	= document.getElementById("videoName");
					var thumbnail	= document.getElementById("thumbnail").files[0];
					var fd 			= new FormData();
					fd.append('Name' ,nameVideo.value);
					
					xmlhttp.open("GET",
					 "workWithTables.php?"
					 + "table=video"
					 + "&TableName=" + table
					 + "&operation=insert"
					 + "&" + "Place=" + file.name
					 + "&" + "IdRecord=" + id
					 + "&" + "ThumbnailPlace=" + thumbnail.name
					 //+ "&" + "height=" + clientheight
					 , true);
					xmlhttp.send(fd);
					if (xmlhttp.responseText == "Operation executed successfully") {location.reload();}
					
				}
				else if (fileType == "thumbnail") {
					loadData(id, table, "video");
				}
				else if (fileType == "portrait") {
					xmlhttp.open("GET",
					 "workWithTables.php?"
					 + "table=persons"
					 + "&operation=update"
					 + "&" + "place=" + file.name
					 + "&" + "Id=" + id
					 , true);
					xmlhttp.send();
					if (xmlhttp.responseText == "Operation executed successfully") {location.reload();}				
				}
			}
		}
	}
	
	if (counter == 0) {
		reader.readAsArrayBuffer(blob);
		updateIndicator(chunkSize, step);
		sessionStorage.setItem('counter', ++counter);
	}
}

function updateIndicator(readedChunkSize, step) {
	document.getElementById("indicator").style.width = (readedChunkSize/step+"px");
}

function deleteImage(name) {
	xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
			if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
				 document.getElementById("response").innerHTML = xmlhttp.responseText;
				 if (xmlhttp.responseText == "Operation executed successfully") {location.reload();}
			}
		}
	xmlhttp.open("GET", "workWithTables.php?table=photo&operation=delete&place=" +name, true);
	xmlhttp.send();
}

function showBigPicture(arr ,path) {
	body = document.getElementsByTagName("body")[0];
	body.innerHTML += "<div id=\"removeThatDiv\">"
	+ "<div id=\"coveringDiv\"></div>"
	+ "<img id=\"closingCross\" src=\"Pictures/whiteCross.png\" onclick=\"hideBigPicture()\">"
	+ "<img id=\"leftArrow\" src=\"Pictures/leftWhiteArrow.png\" onclick=\"leftArrClicked('" + path + "')\">"
	+ "<img id=\"coveringImg\" src=\"Images/" + path + "\">"
	+ "<img id=\"rightArrow\" src=\"Pictures/rightWhiteArrow.png\" onclick=\"rightArrClicked('" + path + "')\">"
	+ "</div>";
}

function playVideo(arr ,path) {
	body = document.getElementsByTagName("body")[0];
	xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		counter = parseInt(sessionStorage.getItem('counter'));
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
			foundVideo = xmlhttp.responseText;
			body.innerHTML += "<div id=\"removeThatDiv\">"
							+ "<div id=\"coveringDiv\"></div>"
							+ "<img id=\"closingCross\" src=\"Pictures/whiteCross.png\" onclick=\"hideBigPicture()\">"
							+ "<img id=\"leftArrow\" src=\"Pictures/leftWhiteArrow.png\" onclick=\"leftArrClicked('" + path + "')\">"
							+ "<video  id=\"coveringImg\" controls><source src=\"Video/" + foundVideo + "\" type=\"video/ogg\"></video>"
							+ "<img id=\"rightArrow\" src=\"Pictures/rightWhiteArrow.png\" onclick=\"rightArrClicked('" + path + "')\">"
							+ "</div>";
		}
	}
	xmlhttp.open("GET",
					 "getPathToVideo.php?"
					 + "path=" + path
					 , true);
					xmlhttp.send();	
}

function hideBigPicture() {
	divToRemove = document.getElementById("removeThatDiv");
	divToRemove.parentElement.removeChild(divToRemove);
}

function leftArrClicked(path) {
	i = findElementInArr(images, path);
	obj = document.getElementById("coveringImg");
	lArr = document.getElementById("leftArrow");
	rArr = document.getElementById("rightArrow");
	if (i != -1) {
		if (i-1 < 0) {
			obj.src = "Images/" + images[images.length-2];
			lArr.setAttribute( "onClick", "leftArrClicked('" + images[images.length-2] + "')" );
			rArr.setAttribute( "onClick", "rightArrClicked('" + images[images.length-2] + "')" );
		}
		else {
			obj.src = "Images/" + images[i-1];
			lArr.setAttribute( "onClick", "leftArrClicked('" + images[i-1] + "')" );
			rArr.setAttribute( "onClick", "rightArrClicked('" + images[i-1] + "')" );
		}
	}
}

function rightArrClicked(path) {
	i = findElementInArr(images, path);
	obj = document.getElementById("coveringImg");
	lArr = document.getElementById("leftArrow");
	rArr = document.getElementById("rightArrow");
	if (i != -1) {
		if (i+1 > images.length-2) {
			obj.src = "Images/" + images[0];
			lArr.setAttribute( "onClick", "leftArrClicked('" + images[0] + "')" );
			rArr.setAttribute( "onClick", "rightArrClicked('" + images[0] + "')" );
		}
		else {
			obj.src = "Images/" + images[i+1];
			lArr.setAttribute( "onClick", "leftArrClicked('" + images[i+1] + "')" );
			rArr.setAttribute( "onClick", "rightArrClicked('" + images[i+1] + "')" );
		}
	}
}

function findElementInArr(arr, path) {
	for (i = 0; i < arr.length; i++) {
		if(arr[i] == path) return i;
	}
	return -1;
}