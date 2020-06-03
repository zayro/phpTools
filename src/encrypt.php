<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of encrypt
 *
 * @author zayro
 */
/*
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
 */


class encrypt
{
    const SALT = 'EstoEsUnSalt';

    private $today = '';

    public function __construct()
    {
        $this->today = date("Ymd");
    }

    public static function hash($password)
    {
        //return hash('sha512', self::SALT . $password);
               
        return hash('crc32b', $password);
    }

    public static function verify($password, $hash)
    {
        return ($hash == self::hash($password));
    }

    public function randomPassword($length, $count, $characters)
    {

// $length - the length of the generated password
        // $count - number of passwords to be generated
        // $characters - types of characters to be used in the password
        // define variables used within the function
        $symbols = array();
        $passwords = array();
        $used_symbols = '';
        $pass = '';

        // an array of different character types
        $symbols["lower_case"] = 'abcdefghijklmnopqrstuvwxyz';
        $symbols["upper_case"] = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $symbols["numbers"] = '1234567890';
        $symbols["special_symbols"] = '!?~@#-_+<>[]{}';

        $characters = split(",", $characters); // get characters types to be used for the passsword
        foreach ($characters as $key => $value) {
            $used_symbols .= $symbols[$value]; // build a string with all characters
        }
        $symbols_length = strlen($used_symbols) - 1; //strlen starts from 0 so to get number of characters deduct 1

        for ($p = 0; $p < $count; $p++) {
            $pass = '';
            for ($i = 0; $i < $length; $i++) {
                $n = rand(0, $symbols_length); // get a random character from the string with all characters
                $pass .= $used_symbols[$n]; // add the character to the password string
            }
            $passwords[] = $pass;
        }

        return $passwords; // return the generated password

        /*
          $my_passwords = randomPassword(10, 1, "lower_case,upper_case,numbers,special_symbols");

          print_r($my_passwords);
         *
         */
    }
    
    public static function passwordRandom($length)
    {
        //$length = 78 etc
        return  bin2hex(random_bytes($length));
    }
}

/*
  // Crear la contraseña:



  $interval = date_diff($datetime1, $datetime2);

  $hash = encrypt::hash('micontrase');

  echo '<br>';

  if (encrypt::verify('micontrase', $hash)) {
  echo 'Contraseña correcta!';
  } else {
  echo "Contraseña incorrecta!";
  }

 */
