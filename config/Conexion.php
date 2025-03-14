<?php
class Conexion{
    // private $servername='10.35.50.118:3306';
    private $servername='localhost';
    private $username='clinicsys';
    private $password='_8Q(iaZL3y_HmMf7';
    private $dbname='clinicsys';
    private $conn;
    //constructor para establecer la conexion automatica al instanciar la clase
    public function __construct(){
        try {
        //crear la conexion usando PDO
        $this->conn = new PDO("mysql:host=$this->servername; dbname=$this->dbname;charset=utf8",$this->username,$this->password);
        //establecer el modo de error de PDO a excepción para manejar errores de manera adecuada.
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
        //Si ocurre un error de conexion, se captura aqui
        echo "conexión fallida: ". $e->getMessage();
        }
    }

    //metodo para obtener la conexión 
    public function getConexion(){
        return $this->conn;
    }
}