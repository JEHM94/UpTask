<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email
{
    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion($tipoContenido)
    {
        // Create a new PHPMailer instance
        $mail = new PHPMailer();
        // Config SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Username = '7b056232a0401d';
        $mail->Password = '6b23b3db884e65';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 2525;

        // Config email Content
        $mail->setFrom('cuentas@uptask.com');
        $receptor = $this->nombre;
        $mail->addAddress($this->email, $receptor);


        // Enable HTML
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        switch ($tipoContenido) {

            case CUENTA_NUEVA:
                $mail->Subject = 'UpTask - Confirma tu cuenta';
                // Email content para Cuentas Nueva
                $contenido =  '<html>';
                $contenido .= '<p><strong>Bienvenido/a ' . $receptor . '.</strong></p>';
                $contenido .= '<p>¡Su cuenta en UpTask ha sido creada exitosamente!.</p>';
                $contenido .= '<p>Haga click en el siguiente enlace para confirmar su E-mail y poder acceder a su cuenta.</p>';
                $contenido .= '<p><a href="http://127.0.0.1:3000/confirmar?token=';
                $contenido .= $this->token;
                $contenido .= '">Confirmar E-mail</a></p>';
                $contenido .= '<p><strong>Si usted no solicitó la información anterior, puede ignorar este mensaje.</strong></p>';
                $contenido .= '</html>';

                // Texto plano alternativo
                $contenidoAlt = 'Bienvenido ' . $receptor . '. Visite el siguiente enlace para poder verificar su cuenta en AppSalon: http://127.0.0.1:3000/confirmar?token=' . $this->token . ' | Si usted no solicitó la información anterior, puede ignorar este mensaje.';

                break;
            case RECUPERAR_CUENTA:
                $mail->Subject = 'UpTask - Reestablece tu contraseña';
                // Email content para Recuperar Contraseña
                $contenido =  '<html>';
                $contenido .= '<p><strong>Hola ' . $receptor . '.</strong></p>';
                $contenido .= '<p>Se ha solicitado el reestablecimiento de tu Contraseña.</p>';
                $contenido .= '<p>Haga click en el siguiente enlace para reestablecer su contraseña y poder acceder a su cuenta.</p>';
                $contenido .= '<p><a href="http://127.0.0.1:3000/reestablecer?token=';
                $contenido .= $this->token;
                $contenido .= '">Reestablecer Contraseña</a></p>';
                $contenido .= '<p><strong>Si usted no solicitó la información anterior, puede ignorar este mensaje.</strong></p>';
                $contenido .= '</html>';

                // Texto plano alternativo
                $contenidoAlt = 'Hola ' . $receptor . '. Visite el siguiente enlace para reestablecer su contraseña en AppSalon: http://127.0.0.1:3000/reestablecer?token=' . $this->token . ' | Si usted no solicitó la información anterior, puede ignorar este mensaje.';
                break;
            default:
                $contenido = '';
                $contenidoAlt = '';
                break;
        }

        $mail->Body = $contenido;
        $mail->AltBody = $contenidoAlt;

        // Send the Email
        return $mail->send();
    }
}
