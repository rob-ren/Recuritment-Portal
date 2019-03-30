<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Recruiting System Appscore') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/jquery-1.9.1.js') }}"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="{{ asset('js/typeahead.bundle.js') }}"></script>
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/js/bootstrap-datepicker.js"></script>--}}
    <script src="{{ asset('js/datatables.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>

    <!-- Styles -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.css"
          rel="stylesheet">
    <link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .anyClass {
            overflow-y: scroll;
            margin-top: 10px;
        }
    </style>

</head>
<body>
<div id="app">
    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/login') }}">
                {{ config('app.name', 'Appscore') }}
            </a>
            <div class="btn-toolbar">
                <a class="btn btn-link btn-sm"
                   href="{{ route('positionList') }}">{{ __('Position List') }}</a>
            </div>
            <div class="btn-toolbar">
                <a class="btn btn-link btn-sm"
                   href="{{ route('candidateList') }}">{{ __('Candidate List') }}</a>
            </div>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false"
                    aria-label="{{ __('Toggle navigation') }}">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left Side Of Navbar -->
                <ul class="navbar-nav mr-auto">

                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="navbar-nav ml-auto">
                    <!-- Authentication Links -->
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    {{--<li class="nav-item">--}}
                    {{--<a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>--}}
                    {{--</li>--}}
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                               data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item"
                                   href="{{ route('positionList') }}">{{ __('Position List') }}</a>
                                <a class="dropdown-item"
                                   href="{{ route('candidateList') }}">{{ __('Candidate List') }}</a>
                                <a class="dropdown-item"
                                   href="{{ route('clientList') }}">{{ __('Client List') }}</a>
                                <a class="dropdown-item"
                                   href="{{ route('recruiterList') }}">{{ __('Recruiter List') }}</a>
                                <a class="dropdown-item"
                                   href="{{ route('roleList') }}">{{ __('Role List') }}</a>
                                @if(Auth::user()->role =='admin')
                                    <a class="dropdown-item"
                                       href="{{ route('userList') }}">{{ __('User List') }}</a>
                                @endif
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                      style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                        @endguest
                </ul>
            </div>
        </div>
    </nav>
    <main class="py-4">
        @yield('content')
    </main>
</div>
</body>
</html>
