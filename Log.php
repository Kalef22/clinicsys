<?php
class Logs{
    

    public function __consruct(){
    }


    public function crear_log($usuario, $evento){
        try {
            // ruta del archivo log
            $log_file = 'logs/navegacion_usuarios.txt';

            // verificar si el directorio "log" existe y crearlo si es necesario
            if(!file_exists('logs')){
                // crear directorio con permisos de escritura
                mkdir('logs', 0777, true);      
            }

            // OBTENER LA INFORMACION DE NAVEGACION DEL USUARIO

            //Direccion IP del usuario
            $ip_usuario = $_SERVER['REMOTE_ADDR'];

            //URL visitada
            $url_visitada = $_SERVER['REQUEST_URI'];

            //Agente de usuario(El navegador del usuario)
            $navegador = $_SERVER['HTTP_USER_AGENT'];

            //pagina de referencia si existe 
            // $referencia = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Directorio' ;

            $fecha = date ('Y-m-d H:i:s');

            //Mensaje de escribir en el log
            $mensaje_log = "[$fecha] - Usuario: $usuario - Evento: $evento - IP: $ip_usuario - Página: $url_visitada - Navegador: $navegador \n ";

            //Abrir el archivo log en modo de "agregar"
            $log = fopen($log_file, 'a');
            

            //Escribir el mensaje en el archivo log
            fwrite($log, $mensaje_log);

            //Cerrar el archivo log
            fclose($log);

            //Confirmacion de que el archivo log se guardo
            // echo "navegacion registrada correctamente";


        } catch (PDOException $e) {
            echo " No se ha podido capturar el log del usuario ". $e->getMessage();
        }

    }

}