<?php

namespace library;

/**
 * CLASE SiSTEMA.
 *
 * permite gestionar metodos que nos ayudaran a construir un api
 *
 * @author MARLON ZAYRO ARIAS VARGAS <zayro8905@gmail.com>
 */
class email
{
    /**
     * ENVIO DE CORREOS.
     *
     * @method enviar_email
     *
     * @param type $recibe       recibe correos
     * @param type $envia        envio correos
     * @param type $mensaje_html contenido html del correo
     * @param type $correos      correos al enviar
     *
     * @return string mensaje exitoso o no
     */
    public function enviar_email($recibe, $envia, $mensaje_html, $correos)
    {
        //cabeceras del correo
        $headers = 'MIME-Version: 1.0'."\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
        $headers .= "From: $envia < $envia >"."\r\n";

        //$headers .= 'Cc: birthdayarchive@example.com' . "\r\n";
        //$headers .= 'Bcc: birthdaycheck@example.com' . "\r\n";
        //enviando correos
        if (mail($correos, $recibe, $mensaje_html, $headers)) {
            return 'enviado emails';
        } else {
            return 'No enviado los email: ';
        }
    }
}

use PHPMailer;

class Correo {
    public function __construct() {
        
    }

    public static function configureMail() {
        $oMail = new PHPMailer();
        $oMail->PluginDir = "";
        $oMail->Mailer = "";
        $oMail->Host = "ssl://smtp.gmail.com";
        $oMail->Port = "465";
        $oMail->SMTPAuth = true;
        $oMail->Username = "no-reply@ambiensq.com";
        $oMail->password = "41t3_ambiensQ";

        return $oMail;
    }

    public static function enviarCorreo(string $sAsunto, string $sMensaje, string $sNombreOrigen, array $aInbox) {
        try {
            $oMail = Correo::configureMail();
            $oMail->From = $oMail->Username;
            $oMail->FromName = $sNombreOrigen;
            $oMail->Timeout = 10;
            $oMail->Subject = $sAsunto;
            $oMail->Body = $sMensaje;
            $oMail->AltBody = $sMensaje;

            foreach ($aInbox as $sMail) {
                $oMail->AddAddress($sMail);
            }

            $oMail->Send();
            $oMail->ClearAddresses();
            $oMail->ClearAttachments();
        } catch (phpmailerException $e) {
            echo $e->errorMessage();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
