<?php
#define('BASE_URL', 'http://' . filter_input(INPUT_SERVER, 'SERVER_NAME', FILTER_SANITIZE_STRING) . '/ambiensqpro/');

class Correo
{
    public function __construct()
    {
        $this->oMail = new PHPMailer(true);
        $this->oMail->isSMTP();
        $this->oMail->Mailer = 'smtp';
        $this->oMail->SMTPSecure = 'ssl';
        $this->oMail->Host = 'ssl://smtp.gmail.com';
        $this->oMail->Port = 465;
        $this->oMail->SMTPAuth = true;
        $this->oMail->IsHTML(true);
        $this->oMail->Username = 'no-reply@ambiensq.com';
        $this->oMail->Password = '41t3_ambiensQ';
        $this->oMail->Timeout = 10;
        $this->oMail->From = $this->oMail->Username;
    }

    public function enviarCorreo(string $sAsunto, string $sMensaje, string $sNombreOrigen, array $aInbox)
    {
        try {
            $this->oMail->FromName = $sNombreOrigen;
            $this->oMail->Subject = $sAsunto;
            $this->oMail->Body = $sMensaje;
            $this->oMail->AltBody = $sMensaje;

            foreach ($aInbox as $sMail) {
                $this->oMail->AddAddress($sMail);
            }

            $this->oMail->Send();
            $this->oMail->ClearAddresses();
            $this->oMail->ClearAttachments();
        } catch (phpmailerException $e) {
            echo $e->errorMessage();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    

    public function correoAlertaRegistroUsuario(array $aDataVars, array $aInbox, string $sUrl)
    {
        $sPlantilla = str_replace(
            array_keys($aDataVars),
            array_values($aDataVars),
            file_get_contents($sUrl)
        );

        $this->enviarCorreo(
            'Su cuenta de  ha sido registrada',
            $sPlantilla,
            'software',
            $aInbox
        );
    }
}

    #$this->oCorreo->correoAlertaRegistroUsuario(array('$_sNombreUsuario_' => $this->oDataRequest->nombre, '$_sApellidoUsuario_' => $this->oDataRequest->apellido, '$_sCorreoUsuario_' => $this->oDataRequest->correo, '$_sContraseniaUsuario_' => $sPassword), array($this->oDataRequest->correo));
