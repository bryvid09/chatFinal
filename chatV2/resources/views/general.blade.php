<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ URL::asset('css/chat.css') }}" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.2/css/all.css"
        integrity="sha384-vSIIfh2YWi9wW0r9iZe7RJPrKwp6bG+s9QZMoITbCckVJqGCCRhc+ccxNcdpHuYu" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(function(){
            $("#message-contenedor").animate({ scrollTop: $('#message-contenedor')[0].scrollHeight}, 1000); 
        });        
    </script>
</head>

<body>
    <div class="d-flex justify-content-center ">
        <div class="container mt-2" id="contenedor">
            <div class="row b-superior">
                <div class="col-sm-3 border-right border-secondary d-flex align-items-center">
                    <img src={{ URL::asset('img/default.png') }} class="userImg rounded-circle"> 
                    <h6>BIENVENIDO <a href="{{route('logOut')}}"><i class="fas fa-sign-out-alt"></i></a></h6>
                </div>
                <div class="col-sm-9 d-flex align-items-center justify-content-center">
                    <h5 id="tituloMensaje">MENSAJES</h5>
                </div>
            </div>
            <div class="row" id="contact-container">
                <div class="col-sm-3 contactos">
                    <div class="contact-table">
                        <table class="table table-sm table-hover user-table">
                            <tbody>
                                <tr>
                                    <td colspan="2">
                                        <a href="{{ route('privado', 'gen') }}" <h6><i
                                                class="fas fa-globe-americas"></i> GENERAL</h6></a>
                                    </td>
                                </tr>
                                @forelse ($info[1] as $item)
                                    @if ($item->user != $_SESSION['user'])
                                        <tr>
                                            <td>
                                                <a href="{{ route('privado', $item->user) }}">
                                                    <div><img class="userImg rounded-circle"
                                                            src="{{ URL::asset('img/default.png') }}"><span>{{ $item->user }}</span>
                                                        @if ($item->online == 'yes')
                                                            <span class="green"></span>
                                                        @else
                                                            <span class="red"></span>
                                                        @endif
                                                    </div>
                                                </a>
                                            </td>
                                        </tr>
                                    @else

                                    @endif
                                @empty

                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-9 area-message">
                    <div class="message-table-cont" id="message-contenedor">
                        <table class="table">
                            <tbody>
                                @forelse ($info[0] as $key1 => $valor1)
                                    <tr>
                                        <th class="text-center" colspan="2"><span id="fecha">{{ $key1 }}</span></th>
                                    </tr>
                                    @foreach ($valor1 as $key2 => $valor2)
                                        @foreach ($valor2 as $key3 => $valor3)
                                            <tr>
                                                <td>
                                                    @if ($key3 == $_SESSION['user'])
                                                        <p class="bg-success mt-2 p-2 mr-5 text-white float-right rounded">
                                                            <b>{{ $key3 }}:</b>
                                                            @foreach ($valor3 as $key4 => $valor4)
                                                                {{ $valor4 }}
                                                        </p>
                                                    @endforeach
                                                @else
                                                    <p class="bg-primary mt-2 p-2 mr-5 text-white float-left rounded">
                                                        <b>{{ $key3 }}:</b>
                                                        @foreach ($valor3 as $key4 => $valor4)
                                                            {{ $valor4 }}
                                                    </p>
                                        @endforeach

                                    @endif
                                    </td>
                                    <td>
                                        <p class="p-1 mt-2 mr-3 shadow-sm"><small>{{ substr($key2, 0, 5) }}</small></p>
                                    </td>
                                    </tr>
                                    @endforeach

                                    @endforeach
                                @empty

                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="row send-message">
                        <div class="col-sm-12">
                            <form action="{{ route('insertar') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-9">
                                        <div class="form-group pt-3">
                                            <input name="mensaje" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <input type="submit" class="btn btn-success mt-3" value="enviar">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
