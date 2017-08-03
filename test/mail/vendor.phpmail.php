<?php
require '../../vendor/autoload.php';

class Correo extends PHPMailer
{
    public function __construct()
    {
        $this->isSMTP();
        $this->Mailer = 'smtp';
        $this->SMTPSecure = 'ssl';
        $this->Host = 'ssl://smtp.gmail.com';
        $this->Port = 465;
        $this->SMTPAuth = true;
        $this->IsHTML(true);
        $this->Username = 'no-reply@ambiensq.com';
        $this->Password = '41t3_ambiensQ';
        $this->Timeout = 10;
        $this->From = $this->Username;
    }

    public function enviarCorreo(string $sAsunto, string $sMensaje, string $sNombreOrigen, array $aInbox)
    {
        try {
            $this->FromName = $sNombreOrigen;
            $this->Subject = $sAsunto;
            $this->Body = $sMensaje;
            $this->AltBody = $sMensaje;
            #$this->addAttachment('/var/tmp/file.tar.gz');


            foreach ($aInbox as $sMail) {
                $this->AddAddress($sMail);
            }

            $this->Send();
            $this->ClearAddresses();
            $this->ClearAttachments();
        } catch (phpmailerException $e) {
            echo $e->errorMessage();
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    public function correoAlertaRegistroUsuario(array $aDataVars, array $aInbox)
    {
        $sPlantilla = str_replace(
            array_keys($aDataVars),
            array_values($aDataVars),
            file_get_contents(BASE_URL . 'template/correoAlertaRegistroUsuario.html')
        );

        $this->enviarCorreo(
            'Su cuenta de ambiensqpro ha sido registrada',
            $sPlantilla,
            'ambiensqpro',
            $aInbox
        );
    }
}
