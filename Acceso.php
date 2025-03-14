<?php

class Acceso
{
    
    private $pdo;

    // public function __construct()
    // {
    //     $host = '10.35.50.118:3306';
    //     $dbname = 'aferesis';
    //     $username = 'root';
    //     $password = '12mariadb';

    //     try {
    //         // Conexión a la base de datos utilizando PDO
    //         $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    //         $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //     } catch (PDOException $e) {
    //         // Manejo de errores en caso de fallo en la conexión o consulta
    //         echo "Error: en la conexion a la base de datos " . $e->getMessage();
    //     }
    // }


    public function __construct(){
        $this->pdo = (new Conexion())->getConexion();
    }


    public function acceso($id_usuario)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO acceso (id_usuario, fecha) VALUES (:id_usuario, NOW())");
            $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
            $stmt->execute();
            // Confirmamos la inserción
            return true; // O un mensaje de éxito
        } catch (PDOException $e) {
            // Manejo adecuado de errores en producción (registro de errores en un archivo)
            error_log("Error al registrar la navegación: " . $e->getMessage()); // Registra el error en el archivo de log
            return false; // O un mensaje de error
        }
    }

    public function consultarFechaUltimoAcceso($idUsuario)    
    {
        try {
            $stmt = $this->pdo->prepare("SELECT MAX(fecha) AS fecha_ultimo_acceso FROM acceso WHERE id_usuario = :id_usuario");
            $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($resultado) {
                return $resultado['fecha_ultimo_acceso'];
            } else {
                return null; // El usuario no tiene registros de acceso
            }
        } catch (PDOException $e) {
            // Manejo de errores en caso de fallo en la consulta
            echo "Error al consultar la fecha de último acceso: " . $e->getMessage();
            return null;
        }
    }

    public function obtenerCantidadDonantes()
    {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) AS cantidad_donantes FROM donante");
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($resultado) {
                return $resultado['cantidad_donantes'];
            } else {
                return 0; // No hay donantes registrados
            }
        } catch (PDOException $e) {
            // Manejo de errores en caso de fallo en la consulta
            echo "Error al consultar la cantidad de donantes: " . $e->getMessage();
            return 0;
        }
    }

    public function obtenerCantidadCitasFuturas()
    {
        try {
            // Obtener la fecha actual
            $fechaActual = date('Y-m-d');

            // Consultar el id_dia correspondiente a la fecha actual en la tabla calendario
            $stmt = $this->pdo->prepare("SELECT id_dia FROM calendario WHERE fecha = ?");
            $stmt->execute([$fechaActual]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado) {
                $idDiaActual = $resultado['id_dia'];

                // Contar las citas cuyo id_dia sea mayor o igual al id_dia actual
                $stmtCitas = $this->pdo->prepare("SELECT COUNT(*) AS cantidad_citas FROM cita WHERE id_dia >= ?");
                $stmtCitas->execute([$idDiaActual]);
                $resultadoCitas = $stmtCitas->fetch(PDO::FETCH_ASSOC);

                if ($resultadoCitas) {
                    return $resultadoCitas['cantidad_citas'];
                } else {
                    return 0; // No hay citas futuras
                }
            } else {
                return 0; // No se encontró el id_dia para la fecha actual
            }
        } catch (PDOException $e) {
            // Manejo de errores en caso de fallo en la consulta
            echo "Error al consultar la cantidad de citas futuras: " . $e->getMessage();
            return 0;
        }
    }

    public function obtenerCantidadMaquinasActivas()
    {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) AS cantidad_maquinas_activas FROM maquina WHERE activa = 'SI'");
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($resultado) {
                return $resultado['cantidad_maquinas_activas'];
            } else {
                return 0; // No hay máquinas activas
            }
        } catch (PDOException $e) {
            // Manejo de errores en caso de fallo en la consulta
            echo "Error al consultar la cantidad de máquinas activas: " . $e->getMessage();
            return 0;
        }
    }

    public function obtenerCantidadUsuariosRegistrados()
    {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) AS cantidad_usuarios FROM usuario");
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($resultado) {
                return $resultado['cantidad_usuarios'];
            } else {
                return 0; // No hay usuarios registrados
            }
        } catch (PDOException $e) {
            // Manejo de errores en caso de fallo en la consulta
            echo "Error al consultar la cantidad de usuarios registrados: " . $e->getMessage();
            return 0;
        }
    }

    // public fucntion detectarIpUsuariosRegistrados(){
    //     $mi = getenv("REMOTE_ADDR");
    //     echo "Tu IP es ".$mi;
    // }
}

