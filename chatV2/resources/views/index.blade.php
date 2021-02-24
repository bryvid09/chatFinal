<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css"
        integrity="sha384-vSIIfh2YWi9wW0r9iZe7RJPrKwp6bG+s9QZMoITbCckVJqGCCRhc+ccxNcdpHuYu" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <title>Inicio</title>
</head>

<body>
    <div class="container-fluid">
        <div class="d-flex justify-content-center main">
            <div class="row main-content text-center">
                <div class="col-md-4 text-center logo_info">
                    <span class="logo"><i class="fas fa-users"></i></span>
                    <h4 class="logo_title">COMMUNITY CHAT</h4>
                </div>
                <div class="col-md-8 col-xs-12 login_form">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12 login_title">
                                <h2>Log in</h2>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <form action="{{ route('entrar') }}" method="post" class="form-group">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12 form-group">
                                            <input type="text" name="user" placeholder="Username" id="user"
                                                class="form_control input-lg">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input type="submit" class="btn btn-success" value="Acceder">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
@if (session('error'))
    <script>
        swal({
            title: "USUARIO CONECTADO",
            icon: "warning",
            dangerMode: true,
        });

    </script>
@elseif ($errors->any())
    <script>
        swal({
            title: "INTRODUCE UN USUARIO",
            icon: "warning",
            dangerMode: true,
        });

    </script>
@endif

</html>
