@if(session('permission_denied'))
toastr.error('', '접근 권한이 없습니다.', {"positionClass": "toast-top-center", timeOut: 2000});
@endif