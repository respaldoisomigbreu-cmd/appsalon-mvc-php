<?php

namespace Model;

class Usuario extends ActiveRecord {
    // base de datos
    protected static $tabla = 'usuarios';
    protected static $columnasDB = [ 'id',
                                'nombre',
                                'apellido',
                                'email',
                                'password',
                                'telefono',
                                'admin',
                                'confirmado',
                                'token'
                                ];   
    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = []) {
        $this->id =$args['id'] ?? null;
        $this->nombre =$args['nombre'] ?? '';
        $this->apellido =$args['apellido'] ?? '';
        $this->email =$args['email'] ?? '';
        $this->password =$args['password'] ?? '';
        $this->telefono =$args['telefono'] ?? '';
        $this->admin =$args['admin'] ?? '0';
        $this->confirmado =$args['confirmado'] ?? '0';
        $this->token =$args['token'] ?? '';
    }

    //mensaje de validacion para la creacion de una cuenta

    public function validarNuevaCuenta(){
        if(!$this->nombre){
            self::$alertas['error'] [] = 'El Nombre Es Obligatorio.';
        }
        if(!$this->apellido){
            self::$alertas['error'] [] = 'El Apellido Es Obligatorio.';
        }
        if(!$this->telefono){
            self::$alertas['error'] [] = 'El Telefono Es Obligatorio.';
        }
        if(!$this->email){
            self::$alertas['error'] [] = 'El Email Es Obligatorio.';
        }
        if(!$this->password){
            self::$alertas['error'] [] = 'El Password Es Obligatorio.';
        }
        if(strlen($this->password) < 6){
            self::$alertas['error'] [] = 'El Password Debe contener Al Menos 6 Caracteres.';
        }
        return self::$alertas;
    }

    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'El Password es Obligatorio';
        }

        return self::$alertas;
    }
    
    public function validarEmail(){
        
        if(!$this->email){
            self::$alertas['error'][] = 'El Email es Obligatorio';
        }
        return self::$alertas;
    }

    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'][] = 'El Password Es Obligatorio';
        }
        if(strlen($this->password) < 6){
            self::$alertas['error'][] = 'El Password Debe Tener Al Menos 6 Caracteres';
        }
        return self::$alertas;
    }


    public function existeUsuario(){

    $query = " SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";

        $resultado = self::$db->query($query);

        if($resultado->num_rows){
            self::$alertas['error'] [] = 'El Usuario ya esta registrado';
        }

        return $resultado;
   // debuguear($resultado);
    }
    public function hashPassword(){
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken(){
        $this->token = uniqid();
    }

    public function comprobarPasswordAndVerificado($password){

        $resultado = password_verify($password, $this->password);

        if(!$resultado || !$this->confirmado){
            self::$alertas['error'][] = 'Password Incorrecto o Tu Cuenta No Ha Sido Confirmada';
            return false;
        }else{
            return true;
        }
    }


}
