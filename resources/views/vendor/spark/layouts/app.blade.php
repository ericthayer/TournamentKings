<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Information -->
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <title>@yield('title', config('app.name'))</title>

    <!-- Fonts -->
    {{-- <link href='https://fonts.googleapis.com/css?family=Open+Sans:300,400,600' rel='stylesheet' type='text/css'> --}}
    {{-- <link href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons' rel="stylesheet"> --}}
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' rel='stylesheet' type='text/css'>

    <!-- Plugins -->
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
    {{-- <link href="https://cdn.jsdelivr.net/npm/vuetify/dist/vuetify.min.css" rel="stylesheet"> --}}

    <!-- Styles -->
    <link href="{{ mix(Spark::usesRightToLeftTheme() ? 'css/app-rtl.css' : 'css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    @stack('scripts')

    <!-- Global Spark Object -->
    <script>
        window.Spark = @json(array_merge(Spark::scriptVariables(), []));
    </script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-140672869-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-140672869-1');
    </script>
</head>
<body>
    <!-- SVG icon sprite -->
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none">

        <!-- Mail -->
        <symbol id="icon-email" viewBox="0 0 32 32">
            <title>envelope</title>
            <path d="M28.8 3.2c1.767 0 3.2 1.433 3.2 3.2v0 19.2c0 1.767-1.433 3.2-3.2 3.2v0h-25.6c-1.767 0-3.2-1.433-3.2-3.2v0-19.2c0-1.76 1.44-3.2 3.2-3.2h25.6zM21.808 17.76l10.192 7.84v-3.2l-8.192-6.24 8.192-6.56v-3.2l-16 12.8-16-12.8v3.2l8.192 6.56-8.192 6.24v3.2l10.192-7.84 5.808 4.64 5.808-4.64z"></path>
        </symbol>

        <!-- Discord -->
        <symbol id="icon-discord" viewBox="0 0 33 24">
                <path fill="#F7BE00" d="M127.5,4 C127.5,4 124.060547,1.307617 120,1 L119.633789,1.73242225 C123.304688,2.63183575 124.989258,3.91796875 126.75,5.5 C123.714844,3.9501955 120.720704,2.5 115.5,2.5 C110.279297,2.5 107.285156,3.9501955 104.25,5.5 C106.010742,3.91796875 108.014648,2.48828125 111.366211,1.73242225 L111,1 C106.740235,1.401367 103.5,4 103.5,4 C103.5,4 99.6591795,9.56933575 99,20.5 C102.870117,24.9648438 108.75,25 108.75,25 L109.980469,23.3623045 C107.891602,22.635742 105.536133,21.3408205 103.5,19 C105.928711,20.8369143 109.59375,22.75 115.5,22.75 C121.40625,22.75 125.071289,20.8369143 127.5,19 C125.463867,21.3408205 123.108398,22.635742 121.019531,23.3623045 L122.25,25 C122.25,25 128.129883,24.9648438 132,20.5 C131.340821,9.56933575 127.5,4 127.5,4 Z M110.625,17.5 C109.174804,17.5 108,16.1582035 108,14.5 C108,12.8417972 109.174804,11.5 110.625,11.5 C112.075195,11.5 113.25,12.8417972 113.25,14.5 C113.25,16.1582035 112.075195,17.5 110.625,17.5 Z M120.375,17.5 C118.924804,17.5 117.75,16.1582035 117.75,14.5 C117.75,12.8417972 118.924804,11.5 120.375,11.5 C121.825195,11.5 123,12.8417972 123,14.5 C123,16.1582035 121.825195,17.5 120.375,17.5 Z" transform="translate(-99 -1)"/>
            </symbol>

        <!-- Twitter -->
        <symbol id="icon-twitter" viewBox="0 0 30 24">
        <path d="M75.4838299,7.2085 C75.4965843,7.45825 75.5014898,7.71185714 75.5014898,7.96160714 C75.5014898,15.6604643 69.5412791,24.5347857 58.6421875,24.5347857 C55.2936773,24.5347857 52.1806323,23.5705 49.5581395,21.91675 C50.0231831,21.97075 50.4941134,21.9987143 50.9709302,21.9987143 C53.7494186,21.9987143 56.303234,21.0672143 58.3301962,19.507 C55.7381177,19.4587857 53.5482922,17.7770714 52.7948038,15.4627857 C53.1558503,15.5293214 53.527689,15.565 53.9093387,15.565 C54.4509084,15.565 54.9748183,15.4955714 55.4712573,15.3625 C52.7604651,14.8253929 50.7178052,12.4725357 50.7178052,9.64910714 L50.7178052,9.57678571 C51.5405879,10.0269099 52.4609339,10.2771871 53.4021076,10.30675 C51.8117369,9.2605 50.7648983,7.48042857 50.7648983,5.45735714 C50.7648983,4.39085714 51.0572674,3.38992857 51.568423,2.52882143 C54.4901526,6.05425 58.8580305,8.37142857 63.7831759,8.61539286 C63.6814331,8.17955661 63.6304154,7.73376762 63.6311047,7.28660714 C63.6311047,4.07167857 66.2820494,1.46521429 69.5550145,1.46521429 C71.2601744,1.46521429 72.7995276,2.17010714 73.881686,3.30314286 C75.2297238,3.04085714 76.4982922,2.55678571 77.6442224,1.8895 C77.2017442,3.24914286 76.2608648,4.39085714 75.0384084,5.11117857 C76.2373183,4.96942857 77.3773619,4.65892857 78.4418605,4.19510714 C77.6470894,5.36500249 76.6456022,6.38557198 75.484811,7.2085 L75.4838299,7.2085 Z"
            transform="translate(-49 -1)" />
        </symbol>

        <!-- Facebook -->
        <symbol id="icon-facebook" viewBox="0 0 24 24">
        <path d="M20.00025,0 L3.99975,0 C1.791,0 0,1.791 0,4.0005 L0,19.9995 C0,22.209 1.79025,24 3.99975,24 L12,24 L12,13.5 L9,13.5 L9,10.5 L12,10.5 L12,8.25 C12,6.17925 13.67925,4.5 15.75,4.5 L19.5,4.5 L19.5,7.5 L15.75,7.5 C15.336,7.5 15,7.836 15,8.25 L15,10.5 L19.125,10.5 L18.375,13.5 L15,13.5 L15,24 L20.00025,24 C22.209,24 24,22.209 24,19.9995 L24,4.0005 C24,1.791 22.20975,0 20.00025,0 Z" />
        </symbol>

        <!-- Twitch -->
        <symbol id="icon-twitch" viewBox="0 0 24 25">
        <path d="M2.57314142,0 L0.209603659,3.90625 L0.209603659,21.875 L6.51237101,21.875 L6.51237101,25 L9.66375469,25 L12.8151384,21.875 L16.754368,21.875 L23.8449812,14.84375 L23.8449812,0 L2.57314142,0 Z M20.6935976,13.28125 L16.754368,17.1875 L12.8151384,17.1875 L9.66375469,20.3125 L9.66375469,17.1875 L4.93667917,17.1875 L4.93667917,3.125 L20.6935976,3.125 L20.6935976,13.28125 Z M15.1786761,6.25 L17.5422139,6.25 L17.5422139,12.5 L15.1786761,12.5 L15.1786761,6.25 Z M10.4516006,6.25 L12.8151384,6.25 L12.8151384,12.5 L10.4516006,12.5 L10.4516006,6.25 Z" />
        </symbol>
    </svg>

    <div id="spark-app" class="app-wrap" v-cloak>
        <header class="site-header" role="banner">
            <!-- Navigation -->
            @if (Auth::check())
                @include('spark::nav.user')
            @else
                @include('spark::nav.guest')
            @endif
        </header>

        <!-- Main Content -->
        <main id="#main" class="main-content" role="main">
            @yield('content')
            <div class="footer-desktop">
                    <a class="logo" href="/">
                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="48" viewBox="0 0 101 136">
                            <path fill="#F7BE00" fill-rule="evenodd" d="M58.5359,94.1956 L73.0479,70.4216 L88.9589,70.4216 L71.6779,98.9686 L77.8649,106.6396 L52.0269,135.8046 L59.4479,108.1746 L56.5129,104.5756 L48.2189,135.6466 L35.7789,122.3076 L49.8079,70.4216 L65.5719,70.4216 L58.5359,94.1956 Z M50.338,-3.55271368e-15 L62.571,29.068 C60.348,30.614 58.892,33.186 58.892,36.1 C58.892,40.828 62.724,44.66 67.452,44.66 C72.179,44.66 76.012,40.828 76.012,36.1 C76.012,34.602 75.627,33.195 74.951,31.97 L100.677,18.779 L88.096,56.045 L50.338,56.045 L12.58,56.045 L7.10542736e-15,18.779 L25.725,31.97 C25.049,33.195 24.665,34.602 24.665,36.1 C24.665,40.828 28.497,44.66 33.224,44.66 C37.952,44.66 41.784,40.828 41.784,36.1 C41.784,33.186 40.328,30.614 38.105,29.068 L50.338,-3.55271368e-15 Z M47.0634,66.9781 L32.5644,119.2531 L20.6854,105.2781 L27.8534,78.5421 L7.3424,78.5421 L12.6224,59.8471 L87.1584,59.8471 L88.9764,66.9781 L47.0634,66.9781 Z"/>
                        </svg>
                    </a>
                    <p class="copyrights">© 2019 Tournament Kings &nbsp; • &nbsp; <a href="/terms">Terms of Service</a> &nbsp; • &nbsp; <a href="mailto:info@tournamentkings.com?Subject=Hello%20again">Contact Us</a>
                    <ul class="icon-list">
                        <li class="has-tooltip">
                            <a href="//discord.gg/h5B5Qzq">
                                <svg class="icon">
                                    <use xlink:href="#icon-discord"></use>
                                </svg>
                                <span class="sr-only">Chat with us on Discord</span>
                                <span class="tooltip">Discord</span>
                            </a>
                        </li>
                        <li class="has-tooltip">
                            <a href="//twitter.com/TKpays">
                                <svg class="icon">
                                    <use xlink:href="#icon-twitter"></use>
                                </svg>
                                <span class="sr-only">Follow us on Twitter</span>
                                <span class="tooltip">Twitter</span>
                            </a>
                        </li>
                        <li class="has-tooltip">
                            <a href="//www.facebook.com/tkpays/">
                            <svg class="icon icon-fb">
                                <use xlink:href="#icon-facebook"></use>
                            </svg>
                            <span class="sr-only">Find us on Facebook</span>
                            <span class="tooltip flush-r">Facebook</span>
                            </a>
                        </li>
                    </ul>
                </div>
        </main>

        <!-- Application Level Modals -->
        @if (Auth::check())
            @include('spark::modals.notifications')
            @include('spark::modals.support')
            @include('spark::modals.session-expired')
        @endif
    </div>

    {{-- BG Image --}}
    <div class="fixed-img-overlay">
        <img src="media/images/content/img-tk-gamer-04.jpg" alt="" style="object-position: left center;">
    </div>

    <!-- JavaScript -->
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="/js/sweetalert.min.js"></script>

</body>
</html>
