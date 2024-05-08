<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="/plugin/tui-calander/toastui-calendar.css"/>
</head>
<body>
<button type="button" onclick="getBuffer()">버퍼링테스트</button>
</body>
<script src="/mix/js/app.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
function getBuffer() {
	axios.get("/getBuffer").then(response => {
		console.log(response.data);
		console.log('work?');
	});
}
</script>
</html>