<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>@yield('title', 'hungrysorrow')</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="stylesheet" type="text/css" href="/mix/css/app.css" />
	<link rel="stylesheet" type="text/css" href="/mix/css/toastr.css" />
	@yield('heads')
</head>
<body @yield('body_class')>
@yield('contents')

<!--<script src="//factory.hungrysorrow.com:6001/socket.io/socket.io.js"></script>-->
<script src="/mix/js/manifest.js"></script>
<script src="/mix/js/vendor.js"></script>
<script src="/mix/js/app.js"></script>
<script src="/mix/js/bootstrap.bundle.min.js"></script>
<script src="/mix/js/axiosOption.js"></script>
@yield('scripts')
</body>

</html>