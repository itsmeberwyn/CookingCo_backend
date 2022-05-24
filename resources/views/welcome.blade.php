<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cooking Co</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
        }

        .logo img {
            display: flex;
            height: 60px;
            width: 100px;
            margin: 40px;
            margin-top: 20px;
        }

        .nav_bar {
            display: inline-block !important;
            float: right !important;
            margin: 40px !important;
            margin-top: -80px !important;
        }

        .card {
            margin: 90px !important;
            margin-top: 120px !important;
        }

        .card .card-title {
            font-family: 'poppins' !important;
            font-size: 35px !important;
            font-weight: bold !important;
            color: #3B3C36;
        }

        .card .card-text {
            font-family: 'poppins' !important;
            font-size: 12px !important;
            color: #3B3C36;
        }

        .navbar {
            margin: 90px !important;
            margin-top: -50px !important;
        }

        .navbar .btn {
            font-family: 'poppins' !important;
            font-size: 12px !important;
            border-radius: 20px !important;
        }

        .navbar .form-control {
            border-radius: 20px !important;
            padding-right: 40px !important;
        }

        .chef img {
            height: 100%;
            width: 42.25%;
            float: right;
            margin-top: -40.80rem;
            margin-right: 9rem;
            z-index: 100000;
        }

        .background_chef img {
            height: 100%;
            width: 50%;
            float: right;
            margin-top: -37.1rem;
            margin-right: 8rem;
            z-index: 100000;
        }

        .phflag img {
            height: 100%;
            width: 24%;
            float: right;
            margin-top: -26rem;
            margin-right: -19rem;
        }

        .search_icon img {
            height: 100%;
            width: 24%;
            float: right;
            margin-top: -37rem;
            margin-right: 35rem;
        }

        .share_icon img {
            height: 100%;
            width: 24%;
            float: right;
            margin-top: -10rem;
            margin-right: 35rem;
        }

        .capture_icon img {
            height: 100%;
            width: 24%;
            float: right;
            margin-top: -25rem;
            margin-right: .03rem;
        }

    </style>
</head>

<body class="antialiased">
    <!--Logo-->
    <div class="logo">
        <img src="{{ asset('/assets/logo.png') }}" alt="">
    </div>
    <!--logo end-->


    <!--Navigation-->
    <div class="nav_bar">
        <ul>
            @if (Route::has('login'))
                @auth
                    <a class="btn btn-success" href="{{ route('home') }}" role="button">Home</a>
                @else
                    <a class="btn btn-success" href="{{ route('login') }}" role="button">Login</a>
                    @if (Route::has('register'))
                        <a class="btn btn-light" href="{{ route('register') }}" type="submit">Register</a>
                    @endif
                @endauth
            @endif
        </ul>
    </div>
    <!--Navigation end-->

    <!--Main content-->
    <div class="card" style="width: 18rem; border: hidden;">
        <div class="card-body">
            <h5 class="card-title">Find and Share your Filipino Food.</h5>
            <p class="card-text">Cooking Co. is a platform that help International People or Filipinos to get close
                with Filipino Foods. </p>
        </div>
    </div>

    <!--searchbar-->
    {{-- <nav class="navbar navbar-light">
        <div class="container-fluid">
            <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-success" type="submit">Search</button>
            </form>
        </div>
    </nav> --}}
    <!--searchbar end-->

    <!---Displays-->
    <div class="container">
        <div class="background_chef">
            <img src="{{ asset('/assets/Union.png') }}" alt="">
        </div>
        <div class="phflag">
            <img src="{{ asset('/assets/phflag.png') }}" alt="">
        </div>
        <div class="chef">
            <img src="{{ asset('/assets/chef.png') }}" alt="">
        </div>
        <div class="search_icon">
            <img src="{{ asset('/assets/searchicon.png') }}" alt="">
        </div>
        <div class="share_icon">
            <img src="{{ asset('/assets/shareicon.png') }}" alt="">
        </div>
        <div class="capture_icon">
            <img src="{{ asset('/assets/capture.png') }}" alt="">
        </div>
    </div>



</body>

</html>
