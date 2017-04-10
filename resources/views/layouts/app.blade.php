<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!--meta name="viewport" content="width=device-width, initial-scale=1"-->

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <!-- Styles -->
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">

    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>

	<style>
		html {
			/* min-height:1080px;
			min-width:1920px; */
		}
	</style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}" style="color:red">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                    @if(Auth::check())
                      @if(Request::segment(1))
                        <a class="navbar-brand mn" href="{{url('/')}}">Overview</a>
                        <a class="navbar-brand active-mn" href="{{url('/projects')}}">Project</a>
                      @else
                        <a class="navbar-brand active-mn" href="{{url('/')}}">Overview</a>
                        <a class="navbar-brand mn" href="{{url('/projects')}}">Project</a>
                      @endif
                  @endif
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-left">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <!-- <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li> -->
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false" class="navbar-brand mn">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/jquery.tabletoCSV.js') }}"></script>
    <script>
    $(document).ready(function(){
        $('#myTable').DataTable({ paging: false});
        $('.month_select').click(function() {
          var month, year;
          var date = $('.month').text().split('-');
            year = date[0];

          if($(this).attr('data-type') == 'asc') {
            if(parseInt(date[1]) >= 12) {
                year = parseInt(year) + 1;
                month = 01;
            } else {
                month = parseInt(date[1]) + 1;
            }

          } else {
            if(parseInt(date[1]) <= 1) {
                year = parseInt(year) - 1;
                month = 01;
            } else {
                month = parseInt(date[1]) - 1;
            }
          }
          window.location.href='?range='+ year +'-'+month;
        });

        $("#export").click(function(){
            $("#myTable").tableToCSV();
        });

    });
    </script>
</body>
</html>
