<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>nmbley</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700,800" rel="stylesheet">
    <link href="../libs/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="../css/app.css" rel="stylesheet" type="text/css">
    <link href="../css/main.css" rel="stylesheet" type="text/css">
</head>

    <body>
        <div class="container-fluid no-padding">
            <nav class="navbar navbar-inverse">
                <div class="navbar-header">
                    <h1>nmbley</h1>
                </div>
                @php
                    if(session('id')){
                        $id = session('id');
                        echo'
                        <ul class="nav navbar-nav">
                            <li><a href="/signout" title="Sign Out"><i class="fa fa-sign-out" aria-hidden="true"></i></a></li>
                            <li><a href="/home" title="Home"><i class="fa fa-home" aria-hidden="true"></i></a></li>
                            <li><a href="/messages/'.$id.'" title="Messages"><i class="fa fa-envelope-o" aria-hidden="true"></i></a></li>
                        </ul>


                        ';
                    }
                    else{

                    }
                @endphp
            </nav>
        </div>

        @yield('content')
        <script src="../js/app.js"></script>
        <script src="../js/main.js"></script>
    </body>
</html>
