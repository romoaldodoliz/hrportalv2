
<nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="/user_profile">
            <h2 class="text-white">HR PORTAL <span class="badge badge-danger">Beta</span></h2>
        </a>
      
        <!-- User -->
        <ul class="navbar-nav align-items-center d-none d-md-flex">
        <li class="nav-item dropdown">
            <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div class="media align-items-center">
                <span class="avatar avatar-sm rounded-circle">
                    @if(file_exists('storage/id_image/employee_image/' . session("employee_id") . '.png'))
                        <img id= "image_profile" alt="Image placeholder" src="{{ url('/storage/id_image/employee_image/') . '/' . session("employee_id"). '.png?v=' . mt_rand() }}">
                    @else 
                        <img id= "image_profile" alt="Image placeholder" src="{{ url('/storage/default.png') }} ">
                    @endif
                </span>
                <div class="media-body ml-2 d-none d-lg-block">
                <span class="mb-0 text-sm  font-weight-bold">{{ auth()->user()->name }}</span>
                </div>
            </div>
            </a>
            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
            <div class=" dropdown-header noti-title">
                <h6 class="text-overflow m-0">Welcome!</h6>
            </div>
            <a href="{{ url('/user_profile') }}" class="dropdown-item">
                <i class="fas fa-user"></i>
                <span>My profile</span>
            </a>
            @if(auth()->user()->roles[0]->name != "User")
                <a href="{{ url('/home') }}" class="dropdown-item">
                    <i class="fas fa-desktop"></i>
                    <span>Dashboard</span>
                </a>
            @endif
            <a href="{{ url('/change_password') }}" class="dropdown-item">
                <i class="fas fa-lock"></i>
                <span>Change Password</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i>
                <span>{{ __('Logout') }}</span>
            </a>
            </div>
        </li>
        </ul>
    </div>
</nav>
