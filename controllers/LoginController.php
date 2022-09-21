<?php


namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;


class LoginController {
    public static function login(Router $router){ // From "/"
        
        $auth = new Usuario();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            //Create new object getting data from POST
            $auth = new Usuario($_POST);

            //Validate data get from user
            $alertas = $auth->validarLogin();

            if(empty($alertas)){

                //Find user e-mail at DB
                $usuario = Usuario::findValue('email', s($auth->email)); 

                //If gets user email:
                if($usuario){

                    //Is email verified?
                    $alertas = $usuario->comprobarUsuarioVerificado(); //Generate an alert if not

                    //If not alerts
                    if(empty($alertas)){

                        //Check if password matchs 
                        if($usuario->comprobarPasswordCorrecto($auth->password)){

                            //Save necesary data, and starts session
                            session_start();

                            $_SESSION['id'] = $usuario->id;
                            $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                            $_SESSION['email'] = $usuario->email;
                            $_SESSION['login'] = true;
                            
                            //Is admin or user? Redirect to correct page.
                            if($usuario->admin === "1"){
                                $_SESSION['admin'] = $usuario->admin ?? null;
                                header('Location: /admin');
                            }else{
                                header('Location: /cita');
                            }

                        }else{
                            $alertas = Usuario::setAlerta('error', 'El password es incorrecto');
                        }
                    }
                }else{
                    $alertas = Usuario::setAlerta('error', 'El usuario no existe');
                }
            }
        }
        
        $alertas = Usuario::getAlertas();//Get all alerts that model has generated

        //Render view
        $router->render('auth/login', [
            'alertas' => $alertas ,
            'auth' => $auth
        ]);

    }

    public static function logout(){ // cerrar-session button
        //Clear session and redirect
        $_SESSION = []; 
        header('Location: /');
    }
    
    public static function olvide(Router $router){ //*View:olvide-password.php
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $auth = new Usuario($_POST);//New user with POST data
            $alertas = $auth->validarEmail();//Return alerts if field is empty

            if(empty($alertas)){
                //Check if e-mail exist.
                $usuario = Usuario::findValue('email', $auth->email); //Encuentra el valor y genera un objeto completo con los datos

                //If exists and is confirmed
                if($usuario && $usuario->confirmado === "1"){
                    
                    //Generates a new token, and save it in db
                    $usuario->crearToken();
                    $usuario->guardar();

                    //Generates and email with the token url
                    $email = new Email($usuario->email , $usuario->nombre , $usuario->token);
                    $email->enviarInstrucciones();

                    Usuario::setAlerta('exito', 'Te hemos enviado un email para resetear tu contraseña');

                }else{
                    $auth->setAlerta('error', 'Usuario no encontrado o no confirmado'); //Genera alertas en el modelo
                }
            }
        }
        $alertas = Usuario::getAlertas(); //Get all alerts that model has generated (with setAlerta function)

        //Render view
        $router->render('auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    public static function recuperar(Router $router){ //From (E-mail sent to user) recuperar-password
        $error = false;
        $alertas = [];
        $reestablecido = false;
        $token = s($_GET['token']);

        //If token exist in DB, get all user data
        $usuario = Usuario::findValue('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error' , 'Token no válido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){

            $password = new Usuario($_POST); //Get password write by user (POST)

            $alertas = $password->validarPassword(); //Validate the password

            if(empty($alertas)){

                $usuario->password = null; //Delete password obtained when used findValue method 
                $usuario->password = $password->password; //Put new password in object
                $usuario->hashPassword(); //Hash the password
                $usuario->token = null; //Delete the token

                $guardar = $usuario->guardar();//Save in DB

                //Show success or error alerts
                if($guardar){
                    $alertas = Usuario::setAlerta('exito', 'Contraseña reeestablecida correctamente');
                    $reestablecido = true;
                }else{
                    $alertas = Usuario::setAlerta('error', 'Ha habido un problema al reestablecer el password');
                }
            }

        }

        $alertas = Usuario::getAlertas();//Get all alerts that model has generated (with setAlerta function)
        
        //Render view
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas ,
            'error' => $error ,
            'reestablecido' => $reestablecido
        ]);
    }

    public static function crear(Router $router){ //*View:crear-cuenta.php
        
        $usuario = new Usuario();
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            //Put POST values to Object
            $usuario->sincronizar($_POST);

            //Field validations
            $alertas = $usuario->validarNuevaCuenta();

            if(empty($alertas)){

                //User exist?
                $alertas = $usuario->existeUsuario();
                
                if(empty($alertas)){

                    //Hash password
                    $usuario->hashPassword();
                    
                    //Generates a token
                    $usuario->crearToken();

                    //Generates a email object
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);

                    //Generates and send an email with confirm url
                    $email->enviarConfirmacion();
                    
                    //Save the user in DB
                    $resultado = $usuario->guardar();

                    //Redirect to mensaje
                    if($resultado){
                        header('Location: /mensaje');
                    }
                }
            }
        }

        //Render view
        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario ,
            'alertas' => $alertas
        ]);
    }

    public static function mensaje(Router $router){ //*View:auth/mensaje
        $router->render('auth/mensaje', []);
    }

    public static function confirmar(Router $router){ // (email sent with token) *View:confirmar-cuenta
        $alertas = [];
        $confirmado = false;

        //Sanitize token
        $token = s($_GET['token']);

        //Find the token in DB and get user data
        $usuario = Usuario::findValue('token', $token);

        if(empty($usuario)){

            Usuario::setAlerta('error', 'Token No Valido');

        }else{
            //Delete the token, confirm the user in db, save changes.
            $usuario->confirmado = "1";
            $usuario->token = null;
            $usuario->guardar();
            Usuario::setAlerta('exito', 'Bienvenido '. $usuario->nombre .' tu cuenta se ha activado correctamente');
            $confirmado = true;
        }
        
        $alertas = Usuario::getAlertas(); //Get alerts

        //Render view
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas ,
            'confirmado' => $confirmado
        ]);
    }
}