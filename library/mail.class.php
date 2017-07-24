<?php

class Correo
{
    public function __construct()
    {
    }

    public static function configureMail()
    {
        $oMail = new PHPMailer();
        $oMail->PluginDir = "";
        $oMail->Mailer = "";
        $oMail->Host = "ssl://smtp.gmail.com";
        $oMail->Port = "465";
        $oMail->SMTPAuth = true;
        $oMail->Username = "no-reply@ambiensq.com";
        $oMail->password = "41t3_ambiensQ";

        return $oMail;
    }

    public static function enviarCorreo(string $sAsunto, string $sMensaje, string $sNombreOrigen, array $aInbox)
    {
        try {
            $oMail = Correo::configureMail();
            $oMail->From = $oMail->Username;
            $oMail->FromName = $sNombreOrigen;
            $oMail->Timeout = 10;
            $oMail->Subject = $sAsunto;
            $oMail->Body = $sMensaje;
            $oMail->AltBody = $sMensaje;

            foreach ($aInbox as $sMail) {
                $oMail->AddAddress($sMail);
            }

            $oMail->Send();
            $oMail->ClearAddresses();
            $oMail->ClearAttachments();
        } catch (phpmailerException $e) {
            echo $e->errorMessage();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public static function correoAlertaRegistroUsuario(array $aDataVars, array $aInbox)
    {
        $sPlantilla = str_replace(
            array_keys($aDataVars),
            array_values($aDataVars),
            file_get_contents(BASE_URL . 'template/correoAlertaRegistroUsuario.html')
        );

        Correo::enviarCorreo(
            'Su cuenta de ambiensqpro ha sido registrada',
            $sPlantilla,
            'ambiensqpro',
            $aInbox
        );
    }
}
