<?php

namespace library;

use Firebase\JWT\JWT;

class auth
{
    private static $secret_key = 'Sdw1s9x8@?';
    private static $encrypt = ['HS256'];
    private static $aud = null;

/**
* CREA EL TOKEN
*/
    public static function SignIn($data)
    {
        $time = time();

        $token = array(
            'exp' => $time + (60 * 60),
            'aud' => self::Aud(),
            'data' => $data,
        );

        return JWT::encode($token, self::$secret_key);
    }

/**
* CHEKEA EL TOKEN SI ES VALIDO
*/
    public static function Check($token)
    {
        if (empty($token)) {
            throw new Exception('Invalid token is empty.');
        }

        $decode = JWT::decode(
            $token,
            self::$secret_key,
            self::$encrypt
        );



        if ($decode->aud !== self::Aud()) {
            throw new Exception('Invalid user logged in.');
        }

return  $decode;


    }

/**
* DESENCRIPTA LOS DATOS DEL TOKEN
*/
    public static function GetData($token)
    {
        return JWT::decode(
            $token,
            self::$secret_key,
            self::$encrypt
        )->data;
    }

    private static function Aud()
    {
        $aud = '';

        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }

        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();

        return sha1($aud);
    }

/**
* Get hearder Authorization.
*/
public function getAuthorizationHeader()
{
    $headers = null;
    if (isset($_SERVER['Authorization'])) {
        $headers = trim($_SERVER['Authorization']);
    } elseif (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER['HTTP_AUTHORIZATION']);
    } elseif (function_exists('apache_request_headers')) {
        $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
    }

    return $headers;
}

/**
 * get access token from header.
 */
public function getBearerToken()
{
    $headers = $this->getAuthorizationHeader();
    // HEADER: Get the access token from the header
    if (!empty($headers)) {
        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
    }

    return null;
}

}
