<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{ asset('css/app.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="{{ asset('css/style.css')}}" rel="stylesheet">
    <script src="https://kit.fontawesome.com/3daacf6a8c.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script src="https://unpkg.com/jquery@2.2.4/dist/jquery.js"></script>
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <link href="https://code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css"/>
</head>
<body>
@guest
@else
<div class="container top-header">
    <div class="d-flex">
        <img class="logo" src="{{ asset('img/logo.png')}}" alt="logo">
        <div class="ml-auto  u-info">
            <a><i class="fas fa-bell"></i></a>
            <a href="/profile/{{ Auth::user()->username }}" ><strong>{{ Auth::user()->username }}</strong></a>
            <!-- <img class="icon" src="{{ URL::asset('img/icon.png')}}" alt="icon"> -->
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </div>
</div>
@endguest

<nav class="navbar navbar-expand-lg">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">

                @can("view own tickets")
                    <li class="nav-item ">
                        <a class="nav-link" href="{{url('/')}}">My submitted tickets</a>
                    </li>
                @endcan

                @can("view own department tickets")
                    <li class="nav-item ">
                        <a class="nav-link" href="{{url('/?dashType=myDepartment')}}">My department tickets</a>
                    </li>
                @endcan

                @can("view assigned tickets")
                    <li class="nav-item ">
                        <a class="nav-link" href="{{url('/?dashType=myAssigned')}}">Assigned tickets</a>
                    </li>
                @endcan

                @can("view all tickets")
                    <li class="nav-item ">
                        <a class="nav-link" href="{{url('/?dashType=allTickets')}}">All tickets</a>
                    </li>
                @endcan

                @can("view archived tickets")
                    <li class="nav-item ">
                        <a class="nav-link" href="{{url('/?dashType=archive')}}">Archive</a>
                    </li>
                @endcan

                @can("admin panel")
                    <li class="nav-item ">
                        <a class="nav-link" href="{{url('/admin')}}">Admin panel</a>
                    </li>
                @endcan
            </ul>

            @can("ticket input")
            <div class="ml-auto">
                <a class="nav-link  btn btn-secondary" href="{{url('/ticketInput')}}">Submit ticket</a>
            </div>
            @endcan
        </div>
    </div>
</nav>
<div class="container content">

