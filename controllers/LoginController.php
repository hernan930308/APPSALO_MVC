<?php
namespace Controllers;

use Classes\Email;
use MVC\Router;
use Model\Usuario;


class LoginController {
    public static function login(Router $router) {
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            if(empty($alertas)) {
                // Comprovar que exista el usuario
                $usuario = Usuario::where('email', $auth->email);

                if ($usuario) {
                    // Verificar el password
                    if($usuario->comprobarPasswordAndVerificado($auth->password)){
                        // Autenticar el usuario
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionamiento
                        if($usuario->admin === "1") {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }

                        // debuguear($_SESSION);
                    }
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/login', [
            'alertas' => $alertas ]);
    }

    public static function logout() {

        session_start();

        $_SESSION = [];

        header('Location: /');
    }

    public static function olvide(Router $router) {

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)) {
                $usuario = Usuario::where('email', $auth->email);
                
                
                if($usuario && $usuario->confirmado === '1') {
                    // Generar un Token
                    $usuario->crearToken();
                    $usuario->guardar();
                    
                    // Enviar email
                    $email = New Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    // Alexta de exito
                    usuario::setAlerta('exito', 'Revisa tu email');
                    
                } else {
                    Usuario::setAlerta('error', 'El usuario no existe o no está confirmado');
                   
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);

    }

    public static function recuperar(Router $router) {

        $alertas = [];
        $error = false;

        $token = s($_GET['token']); 

       $usuario =  Usuario::where('token', $token);

       if(empty($usuario)) {
           // Mostrar mensaje de error
           Usuario::setAlerta('error', 'Token No Válido');
           $error = true;
       }

       if($_SERVER['REQUEST_METHOD'] === 'POST') {
           // leer el nuevo password y guardarlo
           $password= new Usuario($_POST);
           $alertas = $password->validarPassword();

           if(empty($alertas)) {
               $usuario->password = null;
               $usuario->password = $password->password;
               $usuario->hashPassword();
               $usuario->token = '';

               $resultado = $usuario->guardar();
               if($resultado) {
                   header('Location: /');
               }
           }
       }

       $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas, 
            'error' => $error
        ]);
    }
    
    public static function crear(Router $router) {
        $usuario = new Usuario();

        // Alertas vacias
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            // Revisar que alertas este vacio
            if(empty($alertas)) {
                // Verificar que el usuario no esté registrado
               $resultado = $usuario->existeUsuario();

               if($resultado->num_rows) {
                   $alertas = Usuario::getAlertas();
               } else {
                    // Hashear el Password
                    $usuario->hashPassword();

                    // Generar un Token único
                    $usuario->crearToken();

                    // Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre,  $usuario->token); 
                    $email->enviarConfirmacion();

                   // Crear el usuario
                   $resultado = $usuario->guardar();
                    if($resultado) {
                       header('Location: /mensaje');
                    }
               }
            }

        }

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router) {
        $router->render('auth/mensaje');
    }

    public static function confirmar(Router $router) {
       $alertas = [];

       $token = s($_GET['token']);

       $usuario =  Usuario::where('token', $token);

       if(empty($usuario)) {
           // Mostrar mensaje de error
           Usuario::setAlerta('error', 'Token No Válido');
       } else {
           // Modificar a usuario confirmado
           $usuario->confirmado = "1";
           $usuario->token = '';
           $usuario->guardar();
           Usuario::setAlerta('exito', 'Cuenta Comprobada correctamente');
       }

        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }    


}
