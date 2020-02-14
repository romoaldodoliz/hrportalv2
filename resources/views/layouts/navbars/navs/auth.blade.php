<!-- Top navbar -->

<nav class="navbar navbar-top navbar-expand-md" id="navbar-main">
    <div class="container-fluid">
        <!-- Brand -->
        <a class="h4 mb-0 text-white text-uppercase d-none d-lg-inline-block" href="/home">{{ session('header_text') }}</a>
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
                        <span class="mb-0 text-sm  font-weight-bold text-white">{{ auth()->user()->name }} <i class="fas fa-caret-down"></i></span>
                      </div>
                    </div>
                  </a>

                <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
                    <div class=" dropdown-header noti-title">
                        <h6 class="text-overflow m-0">{{ __('Welcome!') }}</h6>
                    </div>
                    <div class="dropdown-divider"></div>
                    @if(session("employee_id"))
                        <a href="{{ url('/user_profile') }}" class="dropdown-item">
                            <i class="fas fa-user"></i>
                            <span>{{ __('My Profile') }}</span>
                        </a>
                    @endif
                    <a href="{{ url('/change_password') }}" class="dropdown-item">
                        <i class="fas fa-lock"></i>
                        <span>Change Password</span>
                    </a>
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
