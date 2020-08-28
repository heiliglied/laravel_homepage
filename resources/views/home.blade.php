@extends('layouts.layout')

@section('title')
Idea Factory
@endsection

@section('heads')
<link rel="stylesheet" type="text/css" href="/mix/css/app.css" />
<link rel="stylesheet" type="text/css" href="/mix/css/main.css" />
<style>
.titlebox {
	width: 100%;
	height: 100%;
	display: table;
}

.welcome {
	position: relative;
	width: 320px;
	height: 240px;
	color: #cecece;
	font-size: 36pt;
	text-align: center;
	vertical-align: middle;
	display: table-cell;
	top: -55px;
}
</style>
@endsection

@section('contents')
<div class="wrapper bg-white">
	<div class="navigation bg-aliceblue">
	@include('layouts.main.nav')
	</div>
	<div class="contents bg-white">
		<div class="titlebox">
			<div class="welcome">
				Welcome<br/> To Idea Factory
			</div>
		</div>
	</div>
</div>
@endsection

@section('scripts')
<script src="/mix/js/manifest.js"></script>
<script src="/mix/js/vendor.js"></script>
<script src="/mix/js/app.js"></script>
<script src="/mix/js/bootstrap.bundle.min.js"></script>
<script>

</script>
@endsection

