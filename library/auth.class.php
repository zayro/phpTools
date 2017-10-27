<?php

namespace PhPdOrm;

class auth
{
    private $db;
    public $auth;

    public function __construct($user, $pass)
    {
        $this->db = new PDO('mysql:dbname=demo;host=127.0.0.1;port=3307;charset=utf8mb4', "$user", "$pass");
        $this->auth = new \Delight\Auth\Auth($this->db);
    }

    public function registro($email, $password, $usermame)
    {
        try {
            $userId = $this->auth->register($email, $password, $usermame, function ($selector, $token) {
                // send `$selector` and `$token` to the user (e.g. via email)
                echo $token;
                echo '<br>';
                echo $selector;
            });

            // we have signed up a new user with the ID `$userId`
        } catch (\Delight\Auth\InvalidEmailException $e) {
            // invalid email address
            echo $e;
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            // invalid password
            echo $e;
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            // user already exists
            echo 'usuario ya existe';
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            // too many requests
            echo $e;
        }
    }

    public function login($email, $password)
    {
        try {
            $this->auth->login($email, $password);

            // user is logged in
        } catch (\Delight\Auth\InvalidEmailException $e) {
            // wrong email address
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            // wrong password
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            // email not verified
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            // too many requests
        }
    }

    public function verificacion($selector, $token)
    {
        try {
            $this->auth->confirmEmail($selector, $token);

            // email address has been verified
            echo 'email verificado';
        } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            // invalid token
            echo 'token invalido';
        } catch (\Delight\Auth\TokenExpiredException $e) {
            // token expired
            echo 'token expiro';
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            // email address already exists
            echo 'email no existe';
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            // too many requests
        }
    }

    public function olvido_clave($email)
    {
        try {
            $this->auth->forgotPassword($email, function ($selector, $token) {
                // send `$selector` and `$token` to the user (e.g. via email)
                $url = 'https://www.example.com/reset_password?selector='.urlencode($selector).'&token='.urlencode($token);
            });

            // request has been generated
        } catch (\Delight\Auth\InvalidEmailException $e) {
            // invalid email address
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            // email not verified
        } catch (\Delight\Auth\ResetDisabledException $e) {
            // password reset is disabled
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            // too many requests
        }
    }

    public function reiniciar_clave($selector, $token, $password)
    {
        try {
            $this->auth->resetPassword($selector, $token, $password);

            // password has been reset
        } catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            // invalid token
        } catch (\Delight\Auth\TokenExpiredException $e) {
            // token expired
        } catch (\Delight\Auth\ResetDisabledException $e) {
            // password reset is disabled
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            // invalid password
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            // too many requests
        }
    }

    public function cambiar_clave($oldPassword, $newPassword)
    {
        try {
            $this->auth->changePassword($oldPassword, $newPassword);

            // password has been changed
        } catch (\Delight\Auth\NotLoggedInException $e) {
            // not logged in
        } catch (\Delight\Auth\InvalidPasswordException $e) {
            // invalid password(s)
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            // too many requests
        }
    }

    public function cambiar_correo($email)
    {
        try {
            $auth->changeEmail($email, function ($selector, $token) {
                // send `$selector` and `$token` to the user (e.g. via email)
            });

            // the change will take effect as soon as the email address has been confirmed
        } catch (\Delight\Auth\InvalidEmailException $e) {
            // invalid email address
        } catch (\Delight\Auth\UserAlreadyExistsException $e) {
            // email address already exists
        } catch (\Delight\Auth\EmailNotVerifiedException $e) {
            // account not verified
        } catch (\Delight\Auth\NotLoggedInException $e) {
            // not logged in
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            // too many requests
        }
    }

    public function reenviar_confirmar_correo($email)
    {
        try {
            $this->auth->resendConfirmationForEmail($email, function ($selector, $token) {
                // send `$selector` and `$token` to the user (e.g. via email)
            });

            // the user may now respond to the confirmation request (usually by clicking a link)
        } catch (\Delight\Auth\ConfirmationRequestNotFound $e) {
            // no earlier request found that could be re-sent
        } catch (\Delight\Auth\TooManyRequestsException $e) {
            // there have been too many requests -- try again later
        }
    }

    public function asignar_rol_email($userEmail)
    {
        try {
            $auth->admin()->addRoleForUserByEmail($userEmail, \Delight\Auth\Role::ADMIN);
        } catch (\Delight\Auth\InvalidEmailException $e) {
            // unknown email address
        }
    }

    public function revocar_rol_email($userEmail)
    {
        try {
            $auth->admin()->removeRoleForUserByEmail($userEmail, \Delight\Auth\Role::ADMIN);
        } catch (\Delight\Auth\InvalidEmailException $e) {
            // unknown email address
        }
    }

    public function desactivar_usuario_email()
    {
    }

    public function eliminar_usuario_email()
    {
    }

    public function chequear_rol($userId)
    {
        try {
            if ($auth->admin()->doesUserHaveRole($userId, \Delight\Auth\Role::ADMIN)) {
                // the specified user is an administrator
            } else {
                // the specified user is *not* an administrator
            }
        } catch (\Delight\Auth\UnknownIdException $e) {
            // unknown user ID
        }
    }

    public function isPasswordAllowed($password)
    {
        if (strlen($password) < 8) {
            return false;
        }

        $blacklist = ['password1', '123456', 'qwerty'];

        if (in_array($password, $blacklist)) {
            return false;
        }

        return true;
    }

    public function permisos(\Delight\Auth\Auth $auth) {
        return $auth->hasAnyRole(
            \Delight\Auth\Role::MODERATOR,
            \Delight\Auth\Role::SUPER_MODERATOR,
            \Delight\Auth\Role::ADMIN,
            \Delight\Auth\Role::SUPER_ADMIN
        );
    }    
}

