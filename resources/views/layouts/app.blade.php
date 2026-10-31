<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="mobile-web-app-capable" content="yes">
    <link rel="icon" sizes="192x192" href="{{ asset('images/favicon.png') }}">
    <meta name="theme-color" content="#FF550C">
    <meta name="msapplication-navbutton-color" content="#FF550C">
    <meta name="apple-mobile-web-app-status-bar-style" content="#FF550C">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css"
          integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog=="
          crossorigin="anonymous"/>

    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/pace.min.css') }}" rel="stylesheet">
    @yield('third_party_stylesheets')

    @stack('page_css')
</head>

<body class="hold-transition sidebar-mini layout-fixed pace-primary layout-navbar-fixed">
<div class="wrapper">
    <!-- Main Header -->
    <nav class="main-header navbar navbar-expand navbar-green navbar-dark">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="{{ route('index.index') }}"  target="_blank">
              <i class="fas fa-globe"></i>
            </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
              <i class="far fa-bell"></i>
              <span class="badge badge-warning navbar-badge">@if($unresolvedmessagecount > 0) {{ $unresolvedmessagecount }} @endif</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
              <span class="dropdown-header">{{ $unresolvedmessagecount }} টি নোটিফিকেশন</span>
              <div class="dropdown-divider"></div>
              <a href="{{ route('dashboard.messages') }}" class="dropdown-item">
                <i class="fas fa-envelope mr-2"></i> 
                {{ $unresolvedmessagecount }} টি নতুন মেসেজ
                <span class="float-right text-muted text-sm">3 mins</span>
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <i class="fas fa-hand-holding-usd mr-2"></i> 0 টি পে-আউট রিকুয়েস্ট
                <span class="float-right text-muted text-sm">12 hours</span>
              </a>
              {{-- <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <i class="fas fa-file mr-2"></i> 0 new reports
                <span class="float-right text-muted text-sm">2 days</span>
              </a> --}}
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item dropdown-footer">সকল নোটিফিকেশন দেখুন</a>
            </div>
          </li> 
          {{-- <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button"><i
                class="fas fa-th-large"></i></a>
          </li> --}}
        </ul>

        <ul class="navbar-nav">
            <li class="nav-item dropdown user-menu">
                <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
                    <img src="{{ asset('images/user.png') }}"
                         class="user-image img-circle elevation-1" alt="User Image">
                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <!-- User image -->
                    <li class="user-header bg-primary">
                        <img src="{{ asset('images/user.png') }}"
                             class="img-circle elevation-1"
                             alt="User Image">
                        <p>
                            {{ Auth::user()->name }}
                            <small>যোগদানঃ {{ bangla(Auth::user()->created_at->format('F Y')) }}</small>
                        </p>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        {{-- <a href="#" class="btn btn-default btn-flat">Profile</a> --}}
                        <a href="#" class="btn btn-default btn-flat float-right"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            লগআউট
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>

    <!-- Left side column. contains the logo and sidebar -->
@include('layouts.sidebar')

<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h4 class="m-0">@yield('page-header')</h4>
              </div>
              <div class="col-sm-6">
                <div class="float-sm-right">
                  @yield('page-header-right')
                </div>
              </div>
            </div>
          </div>
        </div>
        <section class="content">
            @yield('content')
        </section>
    </div>

    <!-- Main Footer -->
    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
            <b>ভার্শন</b> ১.০.০
        </div>
        <strong>Copyright &copy; {{ date('Y') }} All rights
        reserved.
    </footer>
</div>

<script src="{{ mix('js/app.js') }}" defer></script>
<script src="{{ asset('js/pace.min.js') }}"></script>
<script>
 Pace.restart();
 $(document).ready(function(){
      $('[data-toggle="tooltip"]').tooltip();
      $("[rel='tooltip']").tooltip();
  });
</script>

@yield('third_party_scripts')

@stack('page_scripts')
@include('partials._messages')
</body>
</html>
