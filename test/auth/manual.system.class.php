<?php

$auth = new user('user', 'pass');

$email = 'demo@gmail.com';
$password = 'demo';
$usermame = 'demo';

$auth->registro($email, $password, $usermame);

$auth->verificacion($selector, $token);

$auth->login($email, $password);

if ($auth->isLoggedIn()) {
    // user is signed in
}
else {
    // user is *not* signed in yet
}
