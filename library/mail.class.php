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
