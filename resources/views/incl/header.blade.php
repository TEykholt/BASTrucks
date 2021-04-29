<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Dashboard</title>
    <link href="{{ URL::asset('css/app.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('css/style.css')}}" rel="stylesheet">
    <script src="https://kit.fontawesome.com/3daacf6a8c.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</head>
<body>
@guest
@else
<div class="container top-header">
    <div class="d-flex">
        <img class="logo" src="{{ URL::asset('img/logo.png')}}" alt="logo">
        <div class="ml-auto  u-info">
            <a><i class="fas fa-bell"></i></a>
            <a >{{ Auth::user()->name }}</a>
            <img class="icon" src="{{ URL::asset('img/icon.png')}}" alt="icon">
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
                <li class="nav-item active">
                    <a class="nav-link" href="{{url('/')}}">My Submitted tickets</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="{{url('/')}}">Assined tickets</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="{{url('/')}}">All tickets</a>
                </li>
            </ul>
            <div class="ml-auto">
                <a class="nav-link  btn btn-secondary" href="{{url('/ticketInput')}}">Submit ticket</a>
            </div>
        </div>
    </div>
</nav>
<div class="container content">

