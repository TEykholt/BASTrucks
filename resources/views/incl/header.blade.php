<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <title>Dashboard</title>
    <link href="{{ URL::asset('css/app.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('css/style.css')}}" rel="stylesheet">
    <script src="https://kit.fontawesome.com/3daacf6a8c.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="container top-header">
    <div class="d-flex">
        <img class="logo" src="{{ URL::asset('img/logo.png')}}" alt="logo">
        <div class="ml-auto  u-info">
            <a><i class="fas fa-bell"></i></a>
            <a >Tom Eykholt</a>
            <img class="icon" src="{{ URL::asset('img/icon.png')}}" alt="icon">
        </div>
    </div>
</div>
<nav class="navbar navbar-expand-lg">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="{{url('/')}}">Dashboard</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="{{url('/ticketInput')}}">Ticket invoer</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container content">
