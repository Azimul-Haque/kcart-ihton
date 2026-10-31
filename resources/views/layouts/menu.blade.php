<!-- ড্যাশবোর্ড (সবার জন্য) -->
<li class="nav-item">
    <a href="{{ route('dashboard.index') }}" class="nav-link {{ Route::is('dashboard.index') ? 'active' : '' }}">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>ড্যাশবোর্ড</p>
    </a>
</li>

<!-- আমার ডেস্ক (সবার জন্য অত্যন্ত জরুরি) -->
<li class="nav-item">
    <a href="{{ route('files.my_desk') }}" class="nav-link {{ Route::is('files.my_desk') ? 'active' : '' }}">
        <i class="nav-icon fas fa-inbox text-warning"></i>
        <p>আমার ডেস্ক (My Desk)</p>
    </a>
</li>

<!-- আজকের শুনানি (এসি ল্যান্ড বা দায়িত্বপ্রাপ্তদের জন্য) -->
<li class="nav-item">
    <a href="{{ route('hearings.today') }}" class="nav-link {{ Route::is('hearings.today') ? 'active' : '' }}">
        <i class="nav-icon fas fa-gavel text-success"></i>
        <p>আজকের শুনানি</p>
    </a>
</li>

<!-- নথি ব্যবস্থাপনা (ট্রি-ভিউ / ড্রপডাউন) -->
<li class="nav-item {{ Request::is('/dashboard/files*') && !Route::is('files.my_desk') ? 'menu-open' : 'menu-open' }}">
    <a href="#" class="nav-link {{ Request::is('/dashboard/files*') && !Route::is('files.my_desk') ? 'active' : '' }}">
        <i class="nav-icon fas fa-folder-open"></i>
        <p>
            নথি ব্যবস্থাপনা
            <i class="right fas fa-angle-left"></i>
        </p>
    </a>
    <ul class="nav nav-treeview" style="margin-left: 10px;">
        @if(Auth::user()->role == 'admin' || Auth::user()->designation == 'পেশকার')
        <li class="nav-item">
            <a href="{{ route('files.create') }}" class="nav-link {{ Route::is('files.create') ? 'active' : '' }}">
                <i class="fas fa-plus-circle nav-icon"></i>
                <p>নতুন নথি এন্ট্রি</p>
            </a>
        </li>
        @endif
        
        <li class="nav-item">
            <a href="{{ route('files.index') }}" class="nav-link {{ Route::is('files.index') ? 'active' : '' }}">
                <i class="fas fa-list nav-icon"></i>
                <p>সকল নথি</p>
            </a>
        </li>
        
        @if(Auth::user()->role == 'admin')
        <li class="nav-item">
            <a href="{{ route('files.transit') }}" class="nav-link {{ Route::is('files.transit') ? 'active' : '' }}">
                <i class="fas fa-exclamation-triangle nav-icon text-danger"></i>
                <p>ট্রানজিটে থাকা নথি</p>
            </a>
        </li>
        @endif
    </ul>
</li>

<!-- ব্যবহারকারীগণ (শুধুমাত্র অ্যাডমিন/এসি ল্যান্ডের জন্য) -->
@if(Auth::user()->role == 'admin')
<li class="nav-header">সিস্টেম ম্যানেজমেন্ট</li>
<li class="nav-item">
    <a href="{{ route('dashboard.users') ?? '#' }}" class="nav-link {{ Request::is('dashboard/users*') ? 'active' : '' }}">
        <i class="nav-icon fas fa-users"></i>
        <p>অফিস স্টাফগণ</p>
    </a>
</li>
@endif

<!-- লগআউট -->
<li class="nav-header">অ্যাকাউন্ট</li>
<li class="nav-item">
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">
        <i class="nav-icon fas fa-sign-out-alt text-danger"></i>
        <p>লগআউট</p>
    </a>
</li>