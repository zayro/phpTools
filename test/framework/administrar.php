<?php


/**
 * Class administrar |  administrar.php.
 *
 * @author      Marlon Zayro Arias Vargas <zayro8905@gmail.com>
 *
 * @version     v.1.0
 *
 * @copyright   Copyright (c) 2017
 */

namespace back;

//Import PHPMailer classes into the global namespace
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Class administrar.
 *
 * Clase bosque que permite administrar la table bosque
 */
class administrar extends conexion
{
    private $table;
    private $field;
    private $condition;

    public function __construct($table = '', $field = '', $condition = '')
    {
        $this->table = $table;

        $this->field = $field;

        $this->condition = $condition;

        parent::__construct();
    }

    /**
     * MOSTRAR.
     *
     * se muestran todos los registros de la tabla.
     * imprime json los datos procesados
     */
    public function todos()
    {
        $result = $this->select($this->table, '*');

        echo sistema::imprimir_json($result);
    }

    public function mostrar($data)
    {
        extract($data);

        //$table = 'tabla';

        //$field = ['campo1', 'campo2'];

        $result = $this->select($this->table, $field);

        echo sistema::imprimir_json($result);
    }

    public function filtrador($data)
    {
        //$table = 'tabla';

        //$field = ['campo1', 'campoe'];

        //$condition = ['campo1' => 'calor'];

        extract($data);

        $condicion = json_decode($condition, true);

        $campos = explode(',', $field);

        $result = $this->select($this->table, $campos, $condicion);

        echo sistema::imprimir_json($this->handlers($result));
    }

    /**
     * GUARDAR.
     *
     * Almacena los registros en la base de datos
     * devuelve json los datos procesados
     *
     * @param array $data se reciben los datos a guardar en bosque
     */
    public function guardar($data)
    {
        $parametros = sistema::validar_json($data);

        $parametros[$this->field] = $this->max($this->table, $this->field) + 1;

        $result = $this->insert($this->table, $parametros);

        echo sistema::imprimir_json($this->handlers($result));
    }

    /**
     * GUARDAR MASIVO.
     *
     * Almacena los registros en la base de datos
     * devuelve json los datos procesados
     *
     * @param array $data se reciben los datos a guardar en bosque
     */
    public function guardar_masivo($data)
    {
        $parametros = json_decode($data['records'], true);

        $masivo = array();

        $i = 0;

        foreach ($parametros as $key => $val) {
            ++$i;

            $val[$this->field] = $this->max($this->table, $this->field) + $i;

            array_push($masivo, $val);
        }

        $result = $this->insert($this->table, $masivo);

        echo system::imprimir_json($this->handlers($result));
    }

    /**
     * ACTUALIZAR.
     *
     * Almacena los registros en la base de datos
     * devuelve json los datos procesados
     *
     * @param array $data se reciben los datos a actualzar en bosque
     */
    public function actualizar($data)
    {
        $parametros = sistema::validar_json($data);

        $condicion[$this->field] = $this->condition;

        $result = $this->update($this->table, $parametros, $condicion);

        echo sistema::imprimir_json($this->handlers($result));
    }

    /**
     * ELIMINAR.
     *
     * Almacena los registros en la base de datos
     * devuelve json los datos procesados
     *
     * @param array $data se reciben los datos a eliminar en bosque
     */
    public function eliminar($data)
    {
        $condicion[$this->field] = $this->condition;

        $result = $this->delete($this->table, $condicion);

        echo sistema::imprimir_json($this->handlers($result));
    }

    /**
     * MANEJADOR.
     *
     * Almacena los registros en la base de datos
     * devuelve json los datos procesados
     *
     * @param array $data se reciben los datos a eliminar en bosque
     */
    public function handlers($result)
    {
        if ($this->error()[0] != 00000) {
            $msj['success'] = false;
            $msj['error'] = $this->error();
            $msj['sql'] = $this->log();
        } else {
            $msj['success'] = true;
            $msj['count'] = $result->rowCount();
        }

        return $msj;
    }

    public function cargar()
    {
        $storage = new \Upload\Storage\FileSystem('./'); //Lugar a donde se moveran los archivos

        $file = new \Upload\File('file', $storage); //proporcionas el nombre del campo

        /*
        $file->addValidations(array(
           new \Upload\Validation\Extension(array('jpg','png','gif','jpeg')), //validas que sea una extensión valida
           new \Upload\Validation\Mimetype(array('image/jpeg','image/png','image/gif')), //validas el tipo de imagen
           new \Upload\Validation\Size('20k'), //validas que no exceda el tamaño
           new \Ultra\Validation\Dimension(100,100), //comprobamos que la imagen no exceda de 100 x 100 px
        ));
        */

        try {
            $file->upload(); // mover el archivo

            echo 'Funciona!!!';
        } catch (\Upload\Exception $e) {
            //Solo si existe un error en la operación
            print_r($e->getMessage());
        }
    }

    public function carga_masivo()
    {
        // Simple validation (max file size 20MB and only two allowed mime types)
        $validator = new FileUpload\Validator\Simple('20M', ['image/png', 'image/jpg']);

        // Simple path resolver, where uploads will be put
        $pathresolver = new FileUpload\PathResolver\Simple('./');

        // The machine's filesystem
        $filesystem = new FileUpload\FileSystem\Simple();

        // FileUploader itself
        $fileupload = new FileUpload\FileUpload($_FILES['file'], $_SERVER);

        print_r($_FILES['file']);

        // Adding it all together. Note that you can use multiple validators or none at all
        $fileupload->setPathResolver($pathresolver);
        $fileupload->setFileSystem($filesystem);
        $fileupload->addValidator($validator);

        // Doing the deed
        list($files, $headers) = $fileupload->processAll();

        // Outputting it, for example like this
        foreach ($headers as $header => $value) {
            header($header.': '.$value);
        }

        echo json_encode(['files' => $files]);

        foreach ($files as $file) {
            //Remeber to check if the upload was completed
            if ($file->completed) {
                echo $file->getRealPath();

                // Call any method on an SplFileInfo instance
                var_dump($file->isFile());
            }
        }
    }

    public function enviarMailBienvenida($data)
    {
        $parametros = sistema::validar_json($data);

        print_r($parametros);

        $mail = new PHPMailer();
        $mail->Host = 'mail.gjwebserver.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'soporte@gjwebserver.com';
        $mail->Password = '@=x}6!i}D>a!;@';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 587;
        $mail->setFrom('soporte@gjwebserver.com', 'Equipo de Soporte');
        $mail->addAddress($parametros['email'], $parametros['nombre']);
        $mail->addReplyTo('soporte@gjwebserver.com', 'Equipo de Soporte');
        $mail->Subject = utf8_decode('Bienvenid@');
        $contenidos = file_get_contents(dirname(__FILE__).'./template.html');
        $contenidos = str_replace('{{informacion}}', $parametros['informacion'], $contenidos);
        $mail->msgHTML($contenidos, dirname(__FILE__));
        $mail->AltBody = 'Bienvenid@ a nuestra red.';
        /*
        if (!$mail->send()) {
            echo 'bien';
        } else {
            echo 'mal';
        }
        */
    }

    public function call_dinamyc($expiresAfter, callable $callback){
        if (isset($callback) && is_callable($callback)) {
			$callback($selector, $token);
		}
    }
    #PHPIDS
}
