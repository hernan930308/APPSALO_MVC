<?php


namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

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

    public function enviarConfirmacion() {
        // Crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '38c851e15a4c23';
        $mail->Password = '0fdde1762bedca';

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'Appsalon.com');
        $mail->Subject = 'Confirma tu Cuenta';

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has creado tu cuenta en Appsalon, debes confirmarla presionando en el siguiente enlace</p>";
        $contenido .= "<p>Presiona aquí: <a href='https://polar-plateau-80567.herokuapp.com/confirmar-cuenta?token=" . 
        $this->token . "'>Confirmar Cuenta</a></p>";
        $contenido .= "<p>Si tú no solicitaste renovar esta cuenta, ignora este mensaje</p>";
        $contenido .= "</html>";
        $mail->Body = $contenido;

        // Enviar el mail
        $mail->send();
    }

    public function enviarInstrucciones() {
        // Crear el objeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = '38c851e15a4c23';
        $mail->Password = '0fdde1762bedca';

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'Appsalon.com');
        $mail->Subject = 'Restablece tu password';

        // Set HTML
        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = "<html>";
        $contenido .= "<p><strong>Hola " . $this->nombre . "</strong> Has solicitado restablecer tu password, sigue eel siguiente enlace.</p>";
        $contenido .= "<p>Presiona aquí: <a href='https://polar-plateau-80567.herokuapp.com/recuperar?token=" . 
        $this->token . "'>Restablecer Pasword</a></p>";
        $contenido .= "<p>Si tú no solicitaste renovar esta cuenta, ignora este mensaje</p>";
        $contenido .= "</html>";
        $mail->Body = $contenido;

        // Enviar el mail
        $mail->send();
}


}