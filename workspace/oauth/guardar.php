<?php

session_start();

require_once '../clases/Google/autoload.php';

$cliente = new Google_Client();

$cliente->setApplicationName('enviarduda');

$cliente->setClientId('526797281130-1nmul2s6boved7ctbqa0f4s768ner78c.apps.googleusercontent.com'); 

$cliente->setClientSecret('hTHy64Z7J-9LwqgGBUo4jQl_');

$cliente->setRedirectUri('https://app-clasesparticulares-mmarjusticia.c9users.io/oauth/guardar.php');



$cliente->setScopes('https://mail.google.com/');

$cliente->setAccessType('offline');


if (isset($_GET['code'])) {

   $cliente->authenticate($_GET['code']);

   $_SESSION['token'] = $cliente->getAccessToken();

   $archivo = "token.conf";

   $fh = fopen($archivo, 'w') or die("error");

   fwrite($fh, $cliente->getAccessToken()); 
   fclose($fh);

}