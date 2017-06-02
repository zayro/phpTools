<?php

namespace library;

/**
 * CLASE SiSTEMA.
 *
 * permite gestionar metodos que nos ayudaran a construir un api
 *
 * @author MARLON ZAYRO ARIAS VARGAS <zayro8905@gmail.com>
 */
class system
{
    /**
     * IMPLEMENTA CABECERAS EN EL CODIGO PHP.
     *
     * @method  getCurrentUri
     *
     * @return type string uri
     */
    public function getCurrentUri()
    {
        $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)).'/';

        $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));

        if (strstr($uri, '?')) {
            $uri = substr($uri, 0, strpos($uri, '?'));
        }

        $uri = '/'.trim($uri, '/');

        return $uri;
    }

/**
 * IMPLEMENTA CABECERAS EN EL CODIGO PHP.
 *
 *
 * @method  cabeceras
 *
 * @param type number $estado
 */
public static function cabeceras($estado)
{
    $verificar_estado = array(
                        200 => 'OK',
                        201 => 'Created',
                        202 => 'Accepted',
                        204 => 'No Content',
                        301 => 'Moved Permanently',
                        302 => 'Found',
                        303 => 'See Other',
                        304 => 'Not Modified',
                        400 => 'Bad Request',
                        401 => 'Unauthorized',
                        403 => 'Forbidden',
                        404 => 'Not Found',
                        405 => 'Method Not Allowed',
                        500 => 'Internal Server Error', );

    $respuesta = ($verificar_estado[$estado]) ? $verificar_estado[$estado] : $estado[500];

    header("HTTP/1.1 $estado $respuesta ");
    header('Content-Type: application/html');
}

    public function cabecera_cors()
    {
        header('Access-Control-Allow-Origin:  *');
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
    }

    public  static function cabecera_html()
    {
        header('Content-Type:text/html');
    }

    protected function cabecera_csv()
    {
        header('Content-Type: application/csv');
    }

    protected function cabecera_txt()
    {
        header('Content-Type:text/plain');
    }

    protected function cabecera_pdf()
    {
        header('Content-type:application/pdf');
    }

    public static function cabecera_json()
    {
        header('Content-Type: application/json');
    }

    protected function cabecera_word()
    {
        header('Content-type: application/vnd.ms-word');
        header('Content-Type: application/msword');
    }

    protected function cabecera_excel()
    {
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-type: application/x-msexcel; charset=utf-8');
    }

    protected function cabecera_descarga($nombre, $extension)
    {
        header("Content-Disposition:attachment;filename='$nombre.$extencion'");
        header('Pragma: no-cache');
        header('Expires: 0');
    }

/**
 * SOLO IMPRIME JSON.
 *
 * @method imprime_json
 *
 * @param type $array
 *
 * @return json devuelve un array como json
 */
public function imprime_json($array)
{
    return json_encode($array, JSON_PRETTY_PRINT);
}

/**
 * VERIFICA E IMPRIMER ERRORES DE IMPRESION DE JSON.
 *
 * @method verificar_json
 *
 * @return type PHP_EOL
 */
public function verificar_json()
{
    switch (json_last_error()) {
        case JSON_ERROR_NONE:
        echo ' - Sin errores';
        break;
        case JSON_ERROR_DEPTH:
        echo ' - Excedido tamaño máximo de la pila';
        break;
        case JSON_ERROR_STATE_MISMATCH:
        echo ' - Desbordamiento de buffer o los modos no coinciden';
        break;
        case JSON_ERROR_CTRL_CHAR:
        echo ' - Encontrado carácter de control no esperado';
        break;
        case JSON_ERROR_SYNTAX:
        echo ' - Error de sintaxis, JSON mal formado';
        break;
        case JSON_ERROR_UTF8:
        echo ' - Caracteres UTF-8 malformados, posiblemente están mal codificados';
        break;
        default:
        echo ' - Error desconocido';
        break;
        }

    return PHP_EOL;
}

/**
 * Reemplaza todos los acentos por sus equivalentes sin ellos.
 *
 * @method limpiar_caracteres
 *
 * @param type $string string la cadena a sanear
 *
 * @return type $string string saneada
 */
public function limpiar_caracteres($string)
{
    $string = trim($string);

    $string = str_replace(
array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'), array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'), $string
);

    $string = str_replace(
array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'), array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'), $string
);

    $string = str_replace(
array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'), array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'), $string
);

    $string = str_replace(
array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'), array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'), $string
);

    $string = str_replace(
array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'), array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'), $string
);

    $string = str_replace(
array('ñ', 'Ñ', 'ç', 'Ç'), array('n', 'N', 'c', 'C'), $string
);

//Esta parte se encarga de eliminar cualquier caracter extraño
$string = str_replace(
array('\\', '¨', 'º', '-', '~',
'#', '@', '|', '!', '"',
'·', '$', '%', '&', '/',
'(', ')', '?', "'", '¡',
'¿', '[', '^', '`', ']',
'+', '}', '{', '¨', '´',
'>', '< ', ';', ',', ':',
'.', ' DE', ' de', '<', '>', '  ', ), ' ', $string
);

    return $string;
}

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

/**
 * OBTENER IP DE UN EQUIPO.
 *
 * @return string retorna ip
 */
public static function obtener_ip()
{
    if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
        $ip = getenv('HTTP_CLIENT_IP');
    } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
        $ip = getenv('REMOTE_ADDR');
    } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
        $ip = $_SERVER['REMOTE_ADDR'];
    } else {
        $ip = 'IP desconocida';
    }

    return $ip;
}

/**
 * OBTENER RUTA ACTUAL DE UN ARCHIVO.
 *
 * @return string retorna ruta
 */
public function ruta_actual()
{
    $ruta = getcwd();
    $raiz = $_SERVER['DOCUMENT_ROOT'];
    $script_nombre = $_SERVER['SCRIPT_FILENAME'];

    return $script_nombre;
}

/**
 * OBTIENE LAS VARIABLES DE SESSION ACTIVAS.
 *
 * @return string retorna ruta
 */
public function session_active()
{
    $datos_respuesta = array();
    $numero = count($_SESSION);
    $tags = array_keys($_SESSION);
    $valores = array_values($_SESSION);

    $datos_respuesta['cantidad_variables_session'] = count($_SESSION);

    if ($numero > 0) {
        for ($i = 0; $i < $numero; ++$i) {
            $valor_session = (!empty($valores[$i]) and isset($valores[$i]) and !is_null($valores[$i]) and !is_array($valores[$i])) ? utf8_encode($valores[$i]) : $valores[$i];

            $datos_respuesta[$tags[$i]] = $valor_session;
        }

        $datos_respuesta['session'] = true;
    } else {
        $datos_respuesta['session'] = false;
        @session_destroy();
    }

    $this->cabecera_json();

    return json_encode($datos_respuesta);
}

/**
 * VALIDA SI EXISTE SESSION.
 */
public function validar_session()
{
    if (empty($_SESSION) or empty($_SESSION['datos']) or !isset($_SESSION['datos'])) {
        echo 'sin acceso al sistema ingrese a la plataforma';
        exit();
    } else {
        return $this->session_active();
    }
}

    public function iniciar_buffer()
    {
        ob_start();
    }

    public function termina_buffer()
    {
        ob_end_flush();
    }
}
