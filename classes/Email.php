<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

//Helper class for send an email
//Not a model, not communicates with DB

class Email {

    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }
    
    public function enviarConfirmacion(){ // *Controller: LoginContoller.php // *View: crear-cuenta.php 

        //New email - gmail.smtp - (needs an account with 2 step verify & an aplicattion password for user&password)
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USER'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = $_ENV['MAIL_PORT'];

        //From to
        $mail->setFrom($_ENV['MAIL_USER'], "Barber's Lumina");
        $mail->addAddress($this->email);
        
        //Charconfig
        $mail->isHTML(true);
        $mail->Subject = 'Confirma tu cuenta';
        $mail->CharSet = 'UTF-8';

        //Email content
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . ",</strong></p>";
        $contenido .= "<p>Has creado tu cuenta en Barber's Lumina, solo debes confirmarla presionando el siguiente enlace:</p>";
        $contenido .= "<p><a href=" . $_ENV['SERVER_HOST'] . "/confirmar-cuenta?token=". $this->token .">Confirmar Cuenta </a> </p>";
        $contenido .= "<p>Si no has creado una cuenta, puedes ignorar este correo.</p>";
        $mail->Body = $contenido;

        //Send email
        $mail->send();
    }

    public function enviarInstrucciones(){ // *Controller: LoginContoller.php // *View: olvide-password.php 

        //New email - gmail.smtp - (needs an account with 2 step verify & an aplicattion password for user&password)
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USER'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = 'tls';
        $mail->Port = $_ENV['MAIL_PORT'];

        //From to
        $mail->setFrom($_ENV['MAIL_USER'], "Barber's Lumina");
        $mail->addAddress($this->email);
        
        //Charconfig
        $mail->isHTML(true);
        $mail->Subject = 'Confirma tu cuenta';
        $mail->CharSet = 'UTF-8';
        
        //Email content
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . ",</strong></p>";
        $contenido .= "<p>Puedes reestablecer tu contraseña desde el siguiente enlace: </p>";
        $contenido .= "<p><a href=". $_ENV['SERVER_HOST'] ."/recuperar?token=". $this->token .">Reestablecer tu contraseña</a> </p>";
        $contenido .= "<p>Si no has solicitado reestablecer tu contraseña, puedes ignorar este correo.</p>";
        $mail->Body = $contenido;

        //Send email
        $mail->send();
    }
}