<!-- NavBar For Authenticated Users -->
<nav class="navbar navbar-light navbar-expand-md navbar-spark">
    <div class="container" v-if="user">
        <!-- Branding Image -->
        @include('spark::nav.brand')

        <div class="navbar-controls">
            <nav class="navbar-desktop">
                <ul class="nav">
                    <li><a href="/login">Login</a></li>
                    <li><a href="/register">Register</a></li>
                </ul>
            </nav>
            <button type="button" class="navbar-toggle navbar-toggle-login">
                <a href="/login">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon-user" viewBox="0 0 29 30">
                    <path class="user-fill"
                    d="M15,25.1503125 C18.4941797,25.1503125 21.5917969,23.3046094 23.4375,20.6014453 C23.3710547,17.8328906 17.7685547,16.2508594 15,16.2508594 C12.165,16.2508594 6.62789062,17.8328906 6.5625,20.6014453 C8.40820312,23.3046094 11.506875,25.1503125 15,25.1503125 Z M15,5.17769531 C12.6923437,5.17769531 10.78125,7.08984375 10.78125,9.39644531 C10.78125,11.7030469 12.6933984,13.6151953 15,13.6151953 C17.3066016,13.6151953 19.21875,11.7030469 19.21875,9.39644531 C19.21875,7.08984375 17.3066016,5.17769531 15,5.17769531 Z M15,0.958945312 C22.7783203,0.958945312 29.0410547,7.22167969 29.0410547,15 C29.0410547,22.7783203 22.7783203,29.0410547 15,29.0410547 C7.22167969,29.0410547 0.958945312,22.7783203 0.958945312,15 C0.958945312,7.22167969 7.22167969,0.958945312 15,0.958945312 Z"
                    transform="translate(-.5)" />
                </svg>
                </a>
            </button>

            <button type="button" class="navbar-toggle navbar-toggle-menu collapsed" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar top"></span>
                <span class="icon-bar mid"></span>
                <span class="icon-bar bottom"></span>
            </button>
        </div>

        <div id="navbarSupportedContent" class="collapse navbar-collapse">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                @includeIf('spark::nav.user-left')
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-4">
                <li class="nav-item dropdown">
                    <!-- User Photo / Name -->
                    <a href="#" class="d-block d-md-flex text-center nav-link dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown"
                       aria-haspopup="true" aria-expanded="false">
                        <img src="{{ Auth::user()->photo_url }}" class="dropdown-toggle-image spark-nav-profile-photo" alt="{{__('User Photo')}}" />
                        <span class="d-none d-md-block">{{ auth()->user()->name }}</span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <!-- Impersonation -->
                        @if (session('spark:impersonator'))
                            <h6 class="dropdown-header">{{__('Impersonation')}}</h6>

                            <!-- Stop Impersonating -->
                            <a class="dropdown-item" href="/spark/kiosk/users/stop-impersonating">
                                <i class="fa fa-fw text-left fa-btn fa-user-secret"></i> {{__('Back To My Account')}}
                            </a>

                            <div class="dropdown-divider"></div>
                        @endif

                        <!-- Developer -->
                        @if (Spark::developer(Auth::user()->email))
                            @include('spark::nav.developer')
                        @endif

                        <!-- Subscription Reminders -->
                        @include('spark::nav.subscriptions')

                        <!-- Settings -->
                        <h6 class="dropdown-header">{{__('Settings')}}</h6>

                        <!-- Your Settings -->
                        <a class="dropdown-item" href="/settings">
                            <i class="fa fa-fw text-left fa-btn fa-cog"></i> {{__('Your Settings')}}
                        </a>

                        @if (Spark::usesTeams() && (Spark::createsAdditionalTeams() || Spark::showsTeamSwitcher()))
                            <!-- Team Settings -->
                            @include('spark::nav.blade.teams')
                        @endif

                        <div class="dropdown-divider"></div>

                        <!-- Logout -->
                        <a class="dropdown-item" href="/logout">
                            <i class="fa fa-fw text-left fa-btn fa-sign-out"></i> {{__('Logout')}}
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
