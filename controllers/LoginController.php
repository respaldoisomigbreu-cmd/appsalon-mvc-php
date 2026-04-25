<?php 

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {

    public static function login(Router $router) {
    
    $alertas = [];

    $auth = new Usuario;

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $auth = new Usuario($_POST);

        $alertas = $auth->validarLogin();

        if(empty($alertas)){
            //comprobar que exista el usurio
              /** @var Usuario $usuario */
            $usuario = Usuario::where('email', $auth->email);

            if($usuario){
                //verificar el password
                if($usuario->comprobarPasswordAndVerificado($auth->password) ){
                    // autenticar el usuario
                    session_start();

                    $_SESSION['id'] = $usuario->id;
                    $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                    $_SESSION['email'] = $usuario->email;
                    $_SESSION['login'] = true;

                    //redirecionamiento
                    if($usuario->admin === "1"){
                        $_SESSION['admin'] = $usuario->admin ?? null;
                       // debuguear($_SESSION);
                        header('Location: /admin');
                    }else{
                        header('Location: /cita');
                    }

                }
            }else{
                Usuario::setAlerta('error','Usuario No Encontrado');
            }

            //debuguear($usuario);
        }

    }
        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas,
            'auth' => $auth
        ]);
    }    
    public static function logout() {
        session_start();
        $_SESSION = [];
        header('Location: /');
    }

    public static function olvide(Router $router) {
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
                        //comprobar que exista el usurio
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)){
             /** @var Usuario $usuario */
                $usuario = Usuario::where('email', $auth->email);

                if($usuario && $usuario->confirmado === "1");
                    //generar token
                    $usuario->crearToken();
                    $usuario->guardar();

                    // enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    //alerta de exito
                    Usuario::setAlerta('exito', 'revisa tu email');

            }else{
                Usuario::setAlerta('error', 'el usuario no exixte o no ha sido confirmado');
            
            }
        }
        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide-password',[
            'alertas' => $alertas
        ]);
    }
    public static function recuperar(Router $router) {

        $alertas = [];
        $error = false;
        $token = s($_GET['token']);

        //buscar usuarioopor su token 
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token No Valido');
            $error = true;
        }
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            //leer el nuevo password y guardarlo
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            /** @var Usuario $usuario */

            if(empty($alertas)){
        //  Sincronizar el nuevo password con el objeto usuario original
                $usuario->password = null; // Limpiamos el anterior
                $usuario->password = $password->password;
                //Hashear el nuevo password
                $usuario->hashPassword();
                //limpia el token
                $usuario->token = null;
                //guardamos en la base de dato
                $resultado = $usuario->guardar();

                if($resultado){
                    header('Location: /');
                }
            }            
        }

       // debuguear($usuario);
        
        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password',[
            'alertas' => $alertas,
            'error' => $error
        ]);

    }
    public static function crear(Router $router) {

        $usuario = new Usuario;

        //alertas vacias
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){            
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            //revisar que alerta este vacio
            if(empty($alertas)){
                // validar que el usuario no este registrado
                $resultado = $usuario->existeUsuario(); 

                if($resultado->num_rows){
                $alertas = Usuario::getAlertas();
                }else{
                    //HASCHEAR EL PASSWORD
                    $usuario->hashPassword();

                    //generar un token unico
                    $usuario->crearToken();

                    //enviar el Email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    $resultado = $usuario->guardar();

                    if($resultado){
                        header('Location: /mensaje');
                    }

                   // debuguear($email);
                }
            }

        }

        $router->render('auth/crear-cuenta',[
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
         /** @var Usuario $usuario */
        $usuario = Usuario::where('token', $token);
        
        if(empty($usuario)){
            //mostrar mensaje de error
            Usuario::setAlerta('error', 'Token no valido');
        }else{
            //usuario Confirmado
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();

            Usuario::setAlerta('exito', 'Token Valido, Confirmando Usuario');
        }


        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta',[
            'alertas' => $alertas
        ]);
    
    }
}