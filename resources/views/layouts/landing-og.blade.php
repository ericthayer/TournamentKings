<!DOCTYPE html>
<html lang="en">
    <head>
       <!--- Required meta tags --->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Tournament Kings</title>
	    <link rel='shortcut icon' type='image/x-icon' href='favicon.ico' />

	<!--- bootstrap.min.css --->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet">
        <!--- font-awesome.min.css --->
        <link href="assets/css/font-awesome.min.css" rel="stylesheet">
        <!--- owl.carousel.css --->
        <link href="assets/css/owl.carousel.css" rel="stylesheet">
        <!--- style.css --->
        <link href="assets/css/style.css?u" rel="stylesheet">
        <!--- responsive.css --->
        <link href="assets/css/responsive.css?l" rel="stylesheet">

    </head>
    <body style="background-color:#000000;">
        <!--====================================================================
                                Header-top-area-start-here
        =======================================================================-->
        <div class="header-top-area">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-4">
                        <div class="header-top-logo text-left">
                            <a href=""><img src="assets/img/top-logo.png" alt="Tournament Kings"></a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="content-left">
                            <h3>PUT YOUR</h3>
                            <h1 class="mon">MONEY</h1>
                            <h3 class="wh-element">WHERE YOUR</h3>
                            <h1 class="out">MOUTH IS</h1>
                            <p>{{__('landing.tk-is')}}</p>
                        </div>
                        <div class="content-left">
                           <a class="btn btn-tk btn-lg btn-tk-lrg" href="/early-access/register" role="button">{{__('landing.early-reg-btn')}}</a>
                        </div>
                    </div>
                    <div class="col-md-6 col-md-offset-2">
                        <div class="content-right">
                            <strong>1 <p>{{__('landing.join-paid-or-free')}}</p></strong>
                            <strong>2 <p>{{__('landing.compete-online')}}</p></strong>
                            <strong>3 <p>{{__('landing.win-cash')}}</p></strong>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!--====================================================================
                                Join-area-start-here
        =======================================================================-->

        <!--====================================================================
                                Footer-area-start-here
        =======================================================================-->


        <div class="container-fluid footer-area">
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <div class="footer-content">
                        <p>Got something (meaningful) to say? Drop us a line.</p>
                        <a href="mailto:info@tournamentkings.com?Subject=Hello%20again" target="_top">info@tournamentkings.com</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <img src="images/tk-original-logo-tm.png" alt="Tournament Kings" style="height:5%; width:5%">
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="copyright text-center">
                        <p>Copyright &copy; 2019 <a href="mailto:info@tournamentkings.com?Subject=Hello%20again" target="_top"">Tournamentkings.com.</a> All right reserved.</p>
                        <p>We made this shit. Dont steal GLHF</p>
                    </div>
                </div>
            </div>
        </div>

            <!--- jquery.min.js --->
            <script src="assets/js/jquery.min.js"></script>
            <!--- bootstrap.min.js --->
            <script src="assets/js/bootstrap.min.js"></script>
            <!--- owl.carousel.min.js --->
            <script src="assets/js/owl.carousel.min.js"></script>
            <!--- main.js --->
            <script src="assets/js/main.js"></script>
    </body>
</html>
