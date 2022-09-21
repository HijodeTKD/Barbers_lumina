<?php

namespace Model;

//SELF Hace referencia a la clase - Se utiliza sobretodo con static
//THIS Hace referencia al objeto en memoria

class Usuario extends ActiveRecord{
    //BASE DE DATOS
    protected static $tabla = 'usuarios'; //Que tabla es? Pasará a la variable de ActiveRecord para realizar la consulta correspondiente
    protected static $columnasDB = ['id','nombre','apellido','telefono','email','password','admin','confirmado','token']; //Que estructura tiene? Pasará a la variable de ActiveRecord para realizar la consulta correspondiente

    //Debemos crear un atributo por cada columna de la tabla. Al ser variables publicas podemos acceder a ellas en la clase, o fuera cuando el objeto sea instanciado
    public $id;
    public $nombre;
    public $apellido;
    public $telefono;
    public $email;
    public $password;
    public $admin;
    public $confirmado;
    public $token;

    //El constructor va a tomar los atributos que hemos creado arriba como key, y va a asignar los valores que le pasemos desde fuera(args) como value
    public function __construct($args =[]){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    //Error messages creating an account

    public function validarNuevaCuenta(){ //*View:crear-cuenta

        if(!$this->nombre){
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }
        if(!$this->apellido){
            self::$alertas['error'][] = 'El apellido es obligatorio';
        }

        if(!$this->telefono){
            self::$alertas['error'][] = 'El telefono es obligatorio';
        }else if(!preg_match('/^[0-9]{9}+$/', $this->telefono)){ //Only 0-9 characters, must be 9 characters (Phone number structure in spain)
            self::$alertas['error'][] = 'El número de telefono debe ser válido';
        }

        if(!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }else if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][] = 'El email debe ser válido';
        }


        //Password security check

        $uppercase = preg_match('@[A-Z]@', $this->password);
        $lowercase = preg_match('@[a-z]@', $this->password);
        $number    = preg_match('@[0-9]@', $this->password);
        //$specialChars = preg_match('@[^\w]@', $this->password);
        
        if(!$this->password){
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }else if(strlen($this->password) < 8){
            self::$alertas['error'][] = 'La contraseña debe tener al menos 8 caracteres';
        }else if(!$uppercase){
            self::$alertas['error'][] = 'La contraseña debe tener al menos una mayúscula';
        }else if(!$lowercase){
            self::$alertas['error'][] = 'La contraseña debe tener al menos una minúscula';
        }else if(!$number){
            self::$alertas['error'][] = 'La contraseña debe tener al menos un número';
        }

        //Password fields match? Password/Confirm password

        if($this->password != $_POST['confirmar-password']){
            self::$alertas['error'][] = 'Las contraseñas no coinciden';
        }

        return self::$alertas;
    }

    //Are login fields empty?  *View:/

    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }

        return self::$alertas;
    }

    //Is olvide-password field empty?  *View:olvide-password

    public function validarEmail(){
        if(!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        return self::$alertas;
    }

    public function validarPassword(){

        //New password security check

        $uppercase = preg_match('@[A-Z]@', $this->password);
        $lowercase = preg_match('@[a-z]@', $this->password);
        $number    = preg_match('@[0-9]@', $this->password);
        //$specialChars = preg_match('@[^\w]@', $this->password);
        
        if(!$this->password){
            self::$alertas['error'][] = 'La contraseña es obligatoria';
        }else if(strlen($this->password) < 8){
            self::$alertas['error'][] = 'La contraseña debe tener al menos 8 caracteres';
        }else if(!$uppercase){
            self::$alertas['error'][] = 'La contraseña debe tener al menos una mayúscula';
        }else if(!$lowercase){
            self::$alertas['error'][] = 'La contraseña debe tener al menos una minúscula';
        }else if(!$number){
            self::$alertas['error'][] = 'La contraseña debe tener al menos un número';
        }

        //Password fields match? Password/Confirm password 

        if($this->password != $_POST['confirmar-password']){
            self::$alertas['error'][] = 'Las contraseñas no coinciden';
        }
        return self::$alertas;
        
    }

    //User exist in DB?

    public function existeUsuario() {
        $query = " SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";
        
        $resultado = self::$db->query($query);

        if($resultado->num_rows){ //$resultado from query is an object
            self::$alertas['error'][] = 'El usuario ya esta registrado';
        }

        return self::$alertas;
    }

    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT); //Transforms password into hash password (ej 2$/xGx9j02X...) executing PASSWORD_BCRIPT
    }

    public function crearToken(){
        $this->token = uniqid(); //Generate 13 random chars
    }

    public function comprobarUsuarioVerificado(){ // *View:login

        if(!$this->confirmado || $this->confirmado == '0'){
            self::$alertas['error'][] = 'Esta cuenta no esta confirmada';
        }

        return self::$alertas;
    }

    public function comprobarPasswordCorrecto($password){ // *View:login
        $resultado = password_verify($password, $this->password);
        
        return $resultado;
    }
}