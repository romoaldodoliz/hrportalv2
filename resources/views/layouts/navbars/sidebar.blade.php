<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-white" id="sidenav-main">
    <div class="container-fluid">
        <!-- Toggler -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Brand -->
        <a class="navbar-brand pt-3"><img src="{{ asset('/img/lfug.png') }}"></a>
        <h2>HR PORTAL</h2>
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
            <!-- Collapse header -->
            <div class="navbar-collapse-header d-md-none">
                <div class="row">
                    <div class="col-6 collapse-brand">
                        <a href="./index.html">
                            <img src="{{ asset('/img/lfug.png') }}">
                        </a>
                    </div>
                    <div class="col-6 collapse-close">
                        <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                            <span></span>
                            <span></span>
                        </button>
                    </div>
                </div>
            </div>


                
            <!-- Navigation -->
            <ul class="navbar-nav mt--4">
                @if(auth()->user()->roles[0]->name == "Administrator" || auth()->user()->roles[0]->name == "HR Staff")
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/home') }}">
                            <i class="fas fa-desktop text-primary" style="font-size: 15px;"></i> Dashboard
                        </a>
                    </li>
                @endif

                @if(auth()->user()->roles[0]->name == "Administrator" || auth()->user()->roles[0]->name == "HR Staff" || auth()->user()->roles[0]->name == "Cluster Head" || auth()->user()->roles[0]->name == "BU Head" || auth()->user()->roles[0]->name == "Immediate Superior")
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/employees') }}">
                            <i class="fas fa-users text-success" style="font-size: 15px;"></i> Employees
                        </a>
                    </li>
                @endif

                @if(auth()->user()->roles[0]->name == "Administrator")
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/users') }}">
                            <i class="fas fa-user-lock text-warning" style="font-size: 15px;"></i> Users
                        </a>
                    </li>
                @endif

                @if(auth()->user()->roles[0]->name == "Administrator")
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/settings') }}">
                            <i class="fas fa-cogs text-info" style="font-size: 15px;"></i> Settings
                        </a>
                    </li>
                @endif

                @if(auth()->user()->roles[0]->name == "Administrator")
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/activities') }}">
                            <i class="fas fa-clipboard-list text-default" style="font-size: 15px;"></i> Activity Logs
                        </a>
                    </li>
                @endif

                @if(auth()->user()->roles[0]->name == "Administrator Printer" || auth()->user()->roles[0]->name == "Administrator")
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/employee_ids') }}">
                            <i class="fas fa-id-card text-yellow" style="font-size: 15px;"></i> ID's
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/employee_rfids') }}">
                            <i class="fas fa-id-card text-green" style="font-size: 15px;"></i> RFID's
                        </a>
                    </li>
                @endif
                
                @if(auth()->user()->roles[0]->name == "HR Staff" || auth()->user()->roles[0]->name == "Administrator")
                    <li>
                        <a data-toggle="collapse" href="#Report" class="collapsed" aria-expanded="false">
                            <div class="nav-link">
                                <i class="fas fa-file text-pink"></i>
                                <span>Reports</span>
                            </div>
                        </a>
                        <div class="collapse space-left" id="Report" style="">
                            <ul class="nav" style="list-style-type: none;">
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/employee-dependents-reports') }}">
                                        <i class="fas fa-circle text-blue"></i>HMO Dependents
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/employee-transfer-reports') }}">
                                        <i class="fas fa-circle text-green"></i> Employee Transfer
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ url('/employee-npa-reports') }}">
                                        <i class="fas fa-circle text-red"></i> Employee NPA
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif
                @if(auth()->user()->roles[0]->name == "Administrator")
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/uploaded-pdf') }}">
                            <i class="fas fa-file text-default" style="font-size: 15px;"></i>Resignation Letters
                        </a>
                    </li>
                @endif

            </ul>
        </div>
    </div>
</nav>