<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="/plugin/tui-calander/toastui-calendar.css"/>
</head>
<body>
<div id="calendar" style="width: 600px; height: 480px;"><div>
</body>
<script src="/plugin/tui-calander/toastui-calendar.js"></script>
<script>
var calendar = new tui.Calendar('#calendar', {
	defaultView: 'month',
	startDayOfWeek: 0,
	month: {
		dayNames: [
			'일', '월', '화', '수', '목', '금', '토',
		]
	},
	isReadOnly: true,
	useFormPopup: true,
    useDetailPopup: true,
	taskView: true,
});

calendar.createEvents([
  {
    id: 'event1',
    calendarId: 'cal1',
    title: 'Weekly Meeting',
    start: '2022-05-30T09:00:00',
    end: '2022-05-30T10:00:00',
  },
]);

const firstEvent = calendar.getEvent('event1', 'cal1');
console.log(firstEvent.title); // 'Weekly Meeting'
</script>
</html>