<?php

namespace App\Http\Controllers;

use App\Http\Traits\Usuarios;
use Exception;
use Illuminate\Http\Request;

class chatController extends Controller
{
    use usuarios;

    public function verIndex()
    {
        if (isset($_SESSION['user'])) {
            $this->salir();
        }
        return view('index');
    }

    public function obtenerDatos()
    {
        $listaDatos = [usuarios::leerMensajes(), usuarios::listaUsuarios()];
        return $listaDatos;
    }

    public function obtenerDatosPrivado($user)
    {
        $listaDatos = [usuarios::consultarPrivado($user), usuarios::listaUsuarios()];
        return $listaDatos;
    }

    /**
     * Comprueba si podemos acceder a la vista
     * o no
     */
    public function ingresar(Request $request)
    {
        if (!isset($_SESSION['user'])) {
            $datos = request()->validate([
                'user' => 'required',
            ]);
            $datos['online'] = 'yes';
            if (usuarios::comprobarUser($datos)) {
                $_SESSION['user'] = $datos['user'];
                $info = $this->obtenerDatos();
                return view('general', compact('info'));
            } else {
                return back()->with('error', 'El usuario ya existe');
            }
        } else {
            $info = $this->obtenerDatos();
            return view('general', compact('info'));
        }
    }


    public function enviarMensaje(Request $request)
    {
        try {
            $mensaje = request()->validate([
                'mensaje' => 'min:1',
            ]);
            usuarios::registrarMensaje($mensaje);
            $info = $this->obtenerDatos();
            return view('general', compact('info'));
        } catch (Exception $exce) {
            if (isset($_SESSION['user'])) {
                $info = $this->obtenerDatos();
                return view('general', compact('info'));
            } else {
                return redirect()->route('home');
            }
        }
    }

    public function verPrivado($user)
    {
        if (isset($_SESSION['user'])) {
            if ($user != 'gen') {
                $info = $this->obtenerDatosPrivado($user);
                return view('privado', compact('info', 'user'));
            } else {
                $info = $this->obtenerDatos();
                return view('general', compact('info'));
            }
        } else {
            return redirect()->route('home');
        }
    }

    public function enviarPrivado(Request $request, $user)
    {
        if (isset($_SESSION['user'])) {
            $mensaje = request()->validate([
                'mensaje' => 'min:1',
            ]);
            if (isset($mensaje['mensaje'])) {
                usuarios::registrarPrivado($mensaje, $user);                
            }
            $info = $this->obtenerDatosPrivado($user);
            return view('privado', compact('info', 'user'));
        } else {
            return redirect()->route('home');
        }
    }

    public function salir()
    {
        if (isset($_SESSION['user'])) {
            usuarios::desconectar();
            unset($_SESSION['user']);
        }
        return redirect()->route('home');
    }
}
