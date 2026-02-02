<?php 
use App\Models\Setting;
$data =  Setting::first();
?>

<div class="page-content">
  <div class="navbar-custom">
    <div class="topbar">
      <div class="topbar-menu d-flex align-items-center gap-lg-2 gap-1">
        <!-- Brand Logo -->
        <div class="logo-box">

          @if(!empty($data->company_image))
          <!-- Brand Logo Light -->
          <a href="{{ route('admin.dashboard') }}" class="logo-light">
            <img src="{{asset('admin/images/company_setting/'.$data->company_image)}}" alt="logo" class="logo-lg ps-4" height="50">
            <img src="{{asset('admin/images/company_setting/'.$data->company_image)}}" alt="small logo" class="logo-sm" height="50">
          </a>
          @else
          <a href="{{ route('admin.dashboard') }}" class="logo-light">
            <img src="{{asset('admin/images/consumer-affairs-logo.png')}}" alt="logo" class="logo-lg" height="50">
            <img src="{{asset('admin/images/consumer-affairs-logo.png')}}" alt="small logo" class="logo-sm" height="50">
          </a>
          @endif

          @if(!empty($data->company_image))
          <!-- Brand Logo Dark -->
          <a href="{{ route('admin.dashboard') }}" class="logo-dark">
            <img src="{{asset('admin/images/company_setting/'.$data->company_image)}}" alt="dark logo" class="logo-lg" height="32">
            <img src="{{asset('admin/images/company_setting/'.$data->company_image)}}" alt="small logo" class="logo-sm" height="32">
          </a>
          @else
          <a href="{{ route('admin.dashboard') }}" class="logo-dark">
            <img src="{{asset('admin/images/consumer-affairs-logo.png')}}" alt="dark logo" class="logo-lg" height="32">
            <img src="{{asset('admin/images/consumer-affairs-logo.png')}}" alt="small logo" class="logo-sm" height="32">
          </a>
          @endif
        </div>
        <!-- Sidebar Menu Toggle Button -->
        <button class="button-toggle-menu waves-effect waves-light rounded-circle">
          <i class="mdi mdi-menu"></i>
        </button>
      </div>
      <ul class="topbar-menu d-flex align-items-center gap-2">
        <!-- admin/images/user/ -->
        <li class="dropdown">
          <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false"> 
            <?php 
            $image = '';
            if(Auth::user() && Auth::user()->image)
            {
               $image = 'admin/images/user/'.Auth::user()->image;
            }
            else
            {
               $image = 'admin/images/users/avatar-3.jpg';
            }
            ?>
            <img src="{{asset($image)}}" alt="user-image">
            <div class="head-profile">
              <div class="head-profile-c">
                <h6>{{(Auth::user())?Auth::user()->name:'Eleanor Pena'}}</h6>
                <p> @if (Auth::user()->roles->isNotEmpty()) {{ Auth::user()->roles->pluck('name')->implode(', ') }} @endif </p>
              </div>
              <div>
                <i class="mdi mdi-chevron-down"></i>
              </div>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
            <!-- item-->
            <div class="dropdown-header noti-title">
              <h6 class="text-overflow m-0">Welcome !</h6>
            </div>
            <!-- item-->
            <a href="{{route('admin.profile.view')}}" class="dropdown-item notify-item">
              <i data-lucide="user" class="font-size-16 me-2"></i>
              <span>My Profile</span>
            </a>
            <!-- item-->
            <a href="{{route('admin.change.password')}}" class="dropdown-item notify-item">
              <i data-lucide="settings" class="font-size-16 me-2"></i>
              <span>Change Password</span>
            </a>
            <!-- item-->
            <div class="dropdown-divider"></div>
            <!-- item-->
            <a href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item notify-item">
              <i data-lucide="log-out" class="font-size-16 me-2"></i>
              <span>Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none"> @csrf </form>
          </div>
        </li>
      </ul>
    </div>
  </div>
