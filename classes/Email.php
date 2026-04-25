<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email{

    public $email;
    public $nombre;
    public $token;


    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion(){
    //creas la instacia del correo
    $mail = new PHPMailer();

    //crear el objeto del email
    $mail->isSMTP();                                           
    $mail->Host       = $_ENV['EMAIL_HOST'];                     
    $mail->SMTPAuth   = true;    
    $mail->Port       = $_ENV['EMAIL_PORT'];                               
    $mail->Username   = $_ENV['EMAIL_USER'];                     
    $mail->Password   = $_ENV['EMAIL_PASS'];                              
    
    $mail->setFrom('cuentas@appsalon.com');
    $mail->addAddress($this->email, $this->nombre);
    $mail->Subject ='Confirma Tu Cuenta';

    //set Html
    $mail->isHTML(TRUE);
    $mail->CharSet = 'UTF-8';

    $contenido = "<html>";
    $contenido .= "<p><strong> Hola " . $this->nombre ." </strong> Has creado tu cuenta en app Salon, debes de confirmarla sigue el enlace </p>";
    $contenido .= "<p> Presiona aquí : <a href='" . $_ENV['APP_URL'] . "/confirmar-cuenta?token=" .$this->token . "'>Confirmar Cuenta</a></p>";
    $contenido .= "<p>si tu no solicitaste esta cuenta, ignora el mensaje</p>";
    $contenido .= "</html>";

    $mail->Body = $contenido;

    //enviar el mail.
    $mail->send();
    }

    public function enviarInstrucciones(){
           //creas la instacia del correo
    $mail = new PHPMailer();

    //crear el objeto del email
    $mail->isSMTP();                                           
    $mail->Host       = $_ENV['EMAIL_HOST'];                     
    $mail->SMTPAuth   = true;    
    $mail->Port       = $_ENV['EMAIL_PORT'];                               
    $mail->Username   = $_ENV['EMAIL_USER'];                     
    $mail->Password   = $_ENV['EMAIL_PASS'];                              
    
    $mail->setFrom('cuentas@appsalon.com');
    $mail->addAddress($this->email, $this->nombre);
    $mail->Subject ='Restablece Tu Password';

    //set Html
    $mail->isHTML(TRUE);
    $mail->CharSet = 'UTF-8';

    $contenido = "<html>";
    $contenido .= "<p><strong> Hola " . $this->nombre ." </strong> Has Solicitado Restablecer tu Password, sigue el enlace para hacerlo </p>";
    $contenido .= "<p> Presiona aquí : <a href='" . $_ENV['APP_URL'] . "/recuperar?token=" .$this->token . "'>Restablecer Password</a></p>";
    $contenido .= "<p>si tu no solicitaste el Restablecimiento de tu Password, ignora el mensaje</p>";
    $contenido .= "</html>";

    $mail->Body = $contenido;

    //enviar el mail.
    $mail->send();
    }
}