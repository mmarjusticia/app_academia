<?php
echo 'llegue';
require '../clases/AutoCarga.php';
$bd=new DataBase();
$gestor=new ManageUsuario($bd);
$sesion = new Session();
$email=  Request::post('email');
$clave=  Request::post('clave');
$claveR= Request::post('claveR');
echo $clave;
echo '___________________-'.$claveR;
$consulta=$gestor->get($email)->getEmail();
if($consulta!=null){
      header("Location:../php/yaexiste.php");
  
}
else{
      if($clave===$claveR){
            $origen="mmarjusticia@gmail.com";
            $alias=$email;
            $destino=$email;
            $claveCifrada=sha1($clave.Constant::SEMILLA);
            $sha1=  sha1($destino.Constant::SEMILLA);
            $fechaAlta=date('Y-m-d');
//$sesion->set('_email', $destino);
            $usuario= new Usuario($email,$claveCifrada,$alias,$fechaAlta,0,0,0);
            $gestor->insert($usuario);
            $asunto="Validación";
            $mensaje="Confirme su registro a BD_MMar pulsando el siguiente enlace: "."https://gestiondeusuarios-mmarjusticia.c9users.io/oauth/activar.php?email=$destino&sha1=$sha1";
            require_once '../clases/Google/autoload.php';
            require_once '../clases/class.phpmailer.php';  //las últimas versiones también vienen con autoload
            $cliente = new Google_Client();
            $cliente->setApplicationName('enviarCorreoDesdeGmail');
            $cliente->setClientId('505098225843-sdiumqfakj929lle3rugldjv72ojkpgi.apps.googleusercontent.com');
            $cliente->setClientSecret('dvjJ435G5shs2um5ZG_vVeBs');
            $cliente->setRedirectUri('https://practicausuario-mmarjusticia.c9users.io/oauth/guardar.php');
            $cliente->setScopes('https://www.googleapis.com/auth/gmail.compose');
            $cliente->setAccessToken(file_get_contents('token.conf'));
        if ($cliente->getAccessToken()) {
             $service = new Google_Service_Gmail($cliente);
            try {
                $mail = new PHPMailer();
                $mail->CharSet = "UTF-8";
                $mail->From = $origen;
                $mail->FromName = $alias;
                $mail->AddAddress($destino);
                $mail->AddReplyTo($origen, $alias);
                $mail->Subject = $asunto;
                $mail->Body = $mensaje;
                $mail->preSend();
                $mime = $mail->getSentMIMEMessage();
                $mime = rtrim(strtr(base64_encode($mime), '+/', '-_'), '=');
                $mensaje = new Google_Service_Gmail_Message();
                $mensaje->setRaw($mime);
                $service->users_messages->send('me', $mensaje);
                 header("Location:../php/registroCorrecto.php");
                } catch (Exception $e) {
                        print($e->getMessage());
                }   
            }
        else{
            echo'error en el envio';
            }

            }
        else{
            header("Location:../php/errorDatos.php");
            
           }
        }
