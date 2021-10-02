	<!-- Main Sidebar Container -->
	<aside class="main-sidebar sidebar-dark-primary elevation-4">
	<!-- Brand Logo -->
		<a href="index3.html" class="brand-link">
			<img src="/plugin/adminlte/dist/img/AdminLTELogo.png" class="brand-image img-circle elevation-3" style="opacity: .8">
			<span class="brand-text font-weight-light">Administrator</span>
		</a>

		<!-- Sidebar -->
		<div class="sidebar">
			<!-- Sidebar user panel (optional) -->
			<div class="user-panel mt-3 pb-3 mb-3 d-flex">
				<div class="image">
					<img src="" class="img-circle elevation-2" alt="User Image">
				</div>
				<div class="info">
					<a href="#" class="d-block">{{ Auth::user()->name }}</a>
				</div>
			</div>

			<!-- Sidebar Menu -->
			<nav class="mt-2">
				<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
					<!-- Add icons to the links using the .nav-icon class with font-awesome or any other icon font library -->
					<li class="nav-item has-treeview @if($menu['open'] == 'dashboard') menu-open @endif">
						<a href="/admin" class="nav-link">
							<i class="nav-icon fas fa-tachometer-alt"></i>
							<p>
								Dashboard
							</p>
						</a>
					</li>
					<li class="nav-item has-treeview @if($menu['open'] == 'contents') menu-open @endif">
						<a href="#" class="nav-link">
							<i class="nav-icon fas fa-users"></i>
							<p>
								컨텐츠 관리
								<i class="right fas fa-angle-left"></i>
							</p>
						</a>
						<ul class="nav nav-treeview">
							<li class="nav-item">
								<a href="/admin/contents/fiddler" class="nav-link @if($menu['active'] == 'zzapfiddler') active @endif">
									<i class="fas fa-layer-group nav-icon"></i>
									<p>짭피들러</p>
								</a>
								<a href="/admin/contents/ideaBoard/list" class="nav-link @if($menu['active'] == 'ideaBoard') active @endif">
									<i class="fas fa-layer-group nav-icon"></i>
									<p>아이디어 보드</p>
								</a>
							</li>
						</ul>
					</li>
					<li class="nav-item has-treeview @if($menu['open'] == 'test') menu-open @endif">
						<a href="#" class="nav-link">
							<i class="nav-icon fas fa-users"></i>
							<p>
								테스트
								<i class="right fas fa-angle-left"></i>
							</p>
						</a>
						<ul class="nav nav-treeview">
							<li class="nav-item">
								<a href="/admin/test/cast" class="nav-link @if($menu['active'] == 'cast') active @endif">
									<i class="fas fa-layer-group nav-icon"></i>
									<p>Model Cast 테스트</p>
								</a>
							</li>
						</ul>
					</li>
					<li class="nav-item has-treeview @if($menu['open'] == 'users') menu-open @endif">
						<a href="#" class="nav-link">
							<i class="nav-icon fas fa-users"></i>
							<p>
								사용자 관리
								<i class="right fas fa-angle-left"></i>
							</p>
						</a>
						<ul class="nav nav-treeview">
							<li class="nav-item">
								<a href="/admin/users/rank" class="nav-link @if($menu['active'] == 'user_rank') active @endif">
									<i class="fas fa-layer-group nav-icon"></i>
									<p>사용자 등급설정</p>
								</a>
								<a href="/admin/users/users" class="nav-link @if($menu['active'] == 'users') active @endif">
									<i class="fas fa-layer-group nav-icon"></i>
									<p>사용자 관리</p>
								</a>
							</li>
						</ul>
					</li>
					@if(Auth::user()->rank == 0)
					<li class="nav-item has-treeview @if($menu['open'] == 'settings') menu-open @endif">
						<a href="#" class="nav-link">
							<i class="nav-icon fas fa-cog"></i>
							<p>
								환경설정
								<i class="right fas fa-angle-left"></i>
							</p>
						</a>
						<ul class="nav nav-treeview">
							<li class="nav-item">
								<a href="/admin/settings/site" class="nav-link @if($menu['active'] == 'site_setting') active @endif">
									<i class="fas fa-layer-group nav-icon"></i>
									<p>사이트 설정</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="/admin/settings/rank" class="nav-link @if($menu['active'] == 'admin_rank') active @endif">
									<i class="fas fa-layer-group nav-icon"></i>
									<p>관리자 등급설정</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="/admin/settings/member" class="nav-link @if($menu['active'] == 'admin_member') active @endif">
									<i class="fas fa-layer-group nav-icon"></i>
									<p>관리자 등록/수정</p>
								</a>
							</li>
							<li class="nav-item">
								<a href="/admin/settings/permission" class="nav-link @if($menu['active'] == 'admin_permission') active @endif">
									<i class="fas fa-layer-group nav-icon"></i>
									<p>접근권한 관리</p>
								</a>
							</li>
						</ul>
					</li>
					@endif
				</ul>
			</nav>
		</div>
		<!-- /.sidebar -->
	</aside>