<?php

namespace clase;

class Cifrado
{
    public function __construct()
    {
    }

    public static function hashSha1(string $sContrasenia): string
    {
        return sha1($sContrasenia);
    }

    public static function generateSalt()
    {
        $iSaltLength = 10;
        $sCharsetSalt = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789][{};:?.>,<!@#$%^&*()-_=+|';
        $sRandString = '';

        for ($i = 0; $i < $iSaltLength; ++$i) {
            $sRandString .= $sCharsetSalt[mt_rand(0, strlen($sCharsetSalt) - 1)];
        }

        return $sRandString;
    }

    public static function generatePassword(): string
    {
        $iPasswordLength = 8;
        $sCharsetPassword = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789][{};:?.>,<!@#$%^&*()-_=+|';
        $sRandString = '';

        for ($i = 0; $i < $iPasswordLength; ++$i) {
            $sRandString .= $sCharsetPassword[mt_rand(0, strlen($sCharsetPassword) - 1)];
        }

        return $sRandString;
    }
}
