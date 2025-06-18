<?php

namespace App\Http\Traits;

session_start();

trait usuarios
{
    private static $usuarios = array();

    private static $conversaciones;

    private static $users;

    public static function comprobarUser($datos)
    {
        //Si no existe el archivo "usuarios.json" lo crea junto con el primer usuario
        if (!file_exists(storage_path('app/public/usuarios.json'))) {
            array_push(self::$usuarios, $datos);
        } else {
            $insertar = true;
            self::$usuarios = json_decode(file_get_contents(storage_path('app/public/usuarios.json')));

            //recorre el array de objetos
            foreach (self::$usuarios as $usuarios) {

                //comprueba si hay algun usuario con el mismo nombre
                if ($datos['user'] == $usuarios->user) {

                    //comprueba si esta conectado, si esta devuelve un false
                    if ($usuarios->online == 'yes') {
                        if (isset($_SESSION['user']) && $usuarios->user == $_SESSION['user']) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                    /**si el usuario ya existe pero no esta conectado, no creamos una nueva entrada
                     *pero si modificamos la existente colocando la variable "online" a "yes" para que
                     *nadie mas pueda acceder con el mismo nombre de usuario mientras este conectado.
                     **/
                    else {
                        $usuarios->online = 'yes';
                    }

                    //como el usuario existe no hace falta insertarlo en el array $usuarios
                    $insertar = false;
                }
            }

            //comprobamos si hace falta insertarlo
            if ($insertar) {
                array_push(self::$usuarios, $datos);
            }
        }

        /**volvemos a insertar el array en el archivo "usuarios.json" y devolvemos
         *  un true para permitir el acceso al controlador         
         **/
        file_put_contents(storage_path('app/public/usuarios.json'), json_encode(self::$usuarios));
        return true;
    }

    public static function registrarMensaje($mensaje)
    {
        $fecha = date("d/m/Y");
        $hora = date("H:i:s");
        if (!file_exists(storage_path('app/public/historial.json'))) {
            self::$conversaciones[$fecha] = array($hora => array($_SESSION['user'] => array($mensaje['mensaje'])));
            file_put_contents(storage_path('app/public/historial.json'), json_encode(self::$conversaciones));
        } else {
            self::$conversaciones = json_decode(file_get_contents(storage_path('app/public/historial.json')), true);
            if (array_key_exists($fecha, self::$conversaciones)) {
                if (array_key_exists($hora, self::$conversaciones[$fecha])) {
                    if (array_key_exists($_SESSION['user'], self::$conversaciones[$fecha][$hora])) {
                        array_push(self::$conversaciones[$fecha][$hora][$_SESSION['user']], $mensaje['mensaje']);
                    } else {
                        self::$conversaciones[$fecha][$hora][$_SESSION['user']] = array($mensaje['mensaje']);
                    }
                } else {
                    self::$conversaciones[$fecha][$hora] = array($_SESSION['user'] => array($mensaje['mensaje']));
                }
            } else {
                self::$conversaciones[$fecha] = array($hora => array($_SESSION['user'] => array($mensaje['mensaje'])));
            }
            file_put_contents(storage_path('app/public/historial.json'), json_encode(self::$conversaciones));
        }
    }

    public static function leerMensajes()
    {
        if (file_exists(storage_path('app/public/historial.json'))) {
            self::$conversaciones = json_decode(file_get_contents(storage_path('app/public/historial.json')), true);
        } else {
            self::$conversaciones = [];
        }
        return self::$conversaciones;
    }

    public static function listaUsuarios()
    {
        if (file_exists(storage_path('app/public/usuarios.json'))) {
            self::$users = json_decode(file_get_contents(storage_path('app/public/usuarios.json')));
        } else {
            self::$users = [];
        }
        return self::$users;
    }

    public static function registrarPrivado($mensaje, $user)
    {
        $fecha = date("d/m/Y");
        $hora = date("H:i:s");
        $userConver = [];

        if (file_exists(storage_path('app/public/') . $_SESSION['user'] . '.json')) {
            $userConver = json_decode(file_get_contents((storage_path('app/public/') . $_SESSION['user'] . '.json')), true);
            if (array_key_exists($user, $userConver)) {
                if (array_key_exists($fecha, $userConver[$user])) {
                    if (array_key_exists($hora, $userConver[$user][$fecha])) {
                        array_push($userConver[$user][$fecha][$hora], array($_SESSION['user'] => $mensaje['mensaje']));
                    } else {
                        $userConver[$user][$fecha][$hora] = array($_SESSION['user'] => $mensaje['mensaje']);
                    }
                } else {
                    $userConver[$user][$fecha] = array($hora => array($_SESSION['user'] => $mensaje['mensaje']));
                }
            } else {
                $userConver[$user] = array($fecha => array($hora => array($_SESSION['user'] => $mensaje['mensaje'])));
            }
        } else {
            $userConver = array($user => array($fecha => array($hora => array($_SESSION['user'] => $mensaje['mensaje']))));
        }

        file_put_contents(storage_path('app/public/') . $_SESSION['user'] . '.json', json_encode($userConver));

        if (file_exists(storage_path('app/public/') . $user . '.json')) {
            $userConver = json_decode(file_get_contents((storage_path('app/public/') . $user . '.json')), true);
            if (array_key_exists($_SESSION['user'], $userConver)) {
                if (array_key_exists($fecha, $userConver[$_SESSION['user']])) {
                    if (array_key_exists($hora, $userConver[$_SESSION['user']][$fecha])) {
                        array_push($userConver[$_SESSION['user']][$fecha][$hora], array($_SESSION['user'] => $mensaje['mensaje']));
                    } else {
                        $userConver[$_SESSION['user']][$fecha][$hora] = array($_SESSION['user'] => $mensaje['mensaje']);
                    }
                } else {
                    $userConver[$_SESSION['user']][$fecha] = array($hora => array($_SESSION['user'] => $mensaje['mensaje']));
                }
            } else {
                $userConver[$_SESSION['user']] = array($fecha => array($hora => array($_SESSION['user'] => $mensaje['mensaje'])));
            }
        } else {
            $userConver = array($_SESSION['user'] => array($fecha => array($hora => array($_SESSION['user'] => $mensaje['mensaje']))));
        }

        file_put_contents(storage_path('app/public/') . $user . '.json', json_encode($userConver));
    }

    public static function consultarPrivado($user)
    {
        if (file_exists(storage_path('app/public/') . $_SESSION['user'] . '.json')) {
            $converPrivada = json_decode(file_get_contents(storage_path('app/public/') . $_SESSION['user'] . '.json'), true);
            if (array_key_exists($user, $converPrivada)) {
                return $converPrivada[$user];
            }
        }

        return [];
    }

    public static function desconectar()
    {
        if (file_exists(storage_path('app/public/usuarios.json'))) {
            $users = json_decode(file_get_contents(storage_path('app/public/usuarios.json')));
            foreach ($users as $user) {
                if ($user->user == $_SESSION['user']) {
                    $user->online = 'no';
                }
            }
            file_put_contents(storage_path('app/public/usuarios.json'), json_encode($users));
        }
    }
}
