<!-- need to remove -->
<li class="nav-item">
    <a href="{{ route('dashboard.index') }}" class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}">
        <i class="nav-icon fas fa-home"></i>
        <p>ড্যাশবোর্ড</p>
    </a>
</li>

@if(Auth::user()->role == 'admin')
<li class="nav-item">
    <a href="{{ route('dashboard.users') }}" class="nav-link {{ Request::is('dashboard/users') ? 'active' : '' }} {{ Request::is('dashboard/users/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-users"></i>
        <p>ব্যবহারকারীগণ</p>
    </a>
</li>
@endif


@if(Auth::user()->role == 'admin' || Auth::user()->role == 'manager')
<li class="nav-item">
    <a href="{{ route('files.index') }}" class="nav-link {{ Request::is('dashboard/files') ? 'active' : '' }} {{ Request::is('dashboard/files/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-layer-group"></i>
        <p>ফাইলসমূহ</p>
    </a>
</li>
@endif

{{-- @if(Auth::user()->role == 'admin' || Auth::user()->role == 'manager')
<li class="nav-item">
    <a href="{{ route('dashboard.exams.landing') }}" class="nav-link {{ Request::is('dashboard/landing/exams') ? 'active' : '' }} {{ Request::is('dashboard/exams') ? 'active' : '' }} {{ Request::is('dashboard/exams/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tasks"></i>
        <p>পরীক্ষাসমূহ</p>
    </a>
</li>
@endif --}}


@if(Auth::user()->role == 'admin')
<li class="nav-item">
    <a href="{{ route('dashboard.messages') }}" class="nav-link {{ Request::is('dashboard/messages') ? 'active' : '' }} {{ Request::is('dashboard/messages/*') ? 'active' : '' }}">
        <i class="nav-icon far fa-envelope"></i>
        <p>
            মেসেজসমূহ
            @if($unresolvedmessagecount > 0)
            ({{ $unresolvedmessagecount ?? '' }})
            @endif
        </p>
    </a>
</li>
@endif

@if(Auth::user()->role == 'admin')
<li class="nav-item">
    <a href="{{ route('dashboard.notifications') }}" class="nav-link {{ Request::is('dashboard/notifications') ? 'active' : '' }} {{ Request::is('dashboard/notification/*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-bell"></i>
        <p>নোটিফিকেশন</p>
    </a>
</li>
@endif


<li class="nav-item">
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">
        <i class="nav-icon fas fa-sign-out-alt"></i>
        <p>লগআউট</p>
    </a>
</li>
