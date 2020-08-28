<!DOCTYPE html>
<html>
<head>
</head>
<body id="frame_body" onLoad="onload()">

<script>
function onload() {
	var head = document.head || document.getElementsByTagName('head')[0];
	var style = document.createElement('style');
	var css = localStorage["css"];
	style.type = 'text/css';	
	
	if (style.styleSheet){
		style.styleSheet.cssText = css;
	} else {
		style.appendChild(document.createTextNode(css));
	}
	head.appendChild(style);
	document.getElementById("frame_body").innerHTML = localStorage.getItem("html");
	
	var body = document.body ||	document.getElementsByTagName('body')[0];
	var script = document.createElement('script');
	script.type = 'text/javascript';
	script.text = localStorage["js"];
	body.appendChild(script);
}
</script>
</body>
</html>