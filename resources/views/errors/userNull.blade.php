@if(session('rank_null'))
toastr.error('', '설정된 사용자 등급이 없습니다.<br/>먼저 등급을 설정해 주세요.', {"positionClass": "toast-top-center", timeOut: 2000});
@endif