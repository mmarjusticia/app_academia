<?php

require '../clases/AutoCarga.php';
$bd=new DataBase();
$gestor=new ManageUsuario($bd);
$sesion = new Session();
$emailOld=$sesion->get('_Email');
$email=  Request::post('email');

$consulta=$gestor->get($email)->getEmail();
if($consulta!=null){
    echo 'No puede usar este de correo porque ya existe un usuario para este email';
}
else{  echo 'Ha recibido un email a su nuevo correo electronico. Por favor, reviselo para poder continuar' ;
        $origen="mmarjusticia@gmail.com";
        $destino=$email;
        
        $usuarioOld=$gestor->get($emailOld);
        $alias=$usuarioOld->getAlias();
        $claveCifrada=$usuarioOld->getClave();
        $sha1=  sha1($destino.Constant::SEMILLA);
        $fechaAlta=$usuarioOld->getFechaAlta();
        $administrador=$usuarioOld->getAdministrador();
        $personal=$usuarioOld->getPersonal();
//$sesion->set('_email', $destino);
        $usuario= new Usuario($email,$claveCifrada,$alias,$fechaAlta,0,$administrador,$personal);
        //$gestor->delete($emailOld);
        $gestor->set2($usuario);
        
        
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
                } catch (Exception $e) {
                        print($e->getMessage());
                }   
            }
        else{
            echo'error en el envio';
            }}

       
