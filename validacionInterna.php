<!DOCTYPE html>
<html lang="es">
<?php
session_start();
require "config/Conexion.php";
$title = "Validacion Interna";
//require "head.php";
?>

<body>
    <?php
    //require "./nav.php";
    
    if ($_SESSION['valid'] == true) {
        try {
            if ($_SESSION['usuario']) {
                
                $pdo = (new Conexion())->getConexion();
                /*VERIFICACION SI LA CLAVE PUESTA POR EL USUARIO ES CORRECTA. */
                $user = $_SESSION['usuario'];
                $query = "SELECT * FROM usuario WHERE id_usuario = ?";
                $stmt = $pdo->prepare($query);
                $stmt->execute([$user]);
                $num_rows = $stmt->rowCount();
                $resultado_asociativo = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $fila = $resultado_asociativo[0];
                $rol = $fila["id_rol"];
                if ($num_rows > 0) {
                    if (empty($rol)) {
                        echo "Te debe autorizar un usuario administrador";
                    } else {
                        // Redirige a loginOk por que todo va OK...!
                        header('Refresh: 2; URL = inicio.php');
                    }

                } else {
                    try {
                        // Preparar los datos de la sesión
                        $dni = $_SESSION['usuario'];
                        $nombre = $_SESSION['nombre'];
                        $apellido1 = $_SESSION['apellido1'];
                        $apellido2 = $_SESSION['apellido2'];

                        // Consulta SQL para insertar datos en la tabla de usuarios
                        $consulta = $pdo->prepare("INSERT INTO usuario (id_usuario, nombre, apellido1, apellido2) VALUES (:dni, :nombre, :apellido1, :apellido2)");
                        $consulta->bindParam(':dni', $dni);
                        $consulta->bindParam(':nombre', $nombre);
                        $consulta->bindParam(':apellido1', $apellido1);
                        $consulta->bindParam(':apellido2', $apellido2);
                        $consulta->execute();

                        echo "Te debe autorizar un usuario administrador";
                    } catch (PDOException $e) {
                        // Manejo de errores en caso de fallo en la conexión o consulta
                        echo "Error al insertar datos en la base de datos: " . $e->getMessage();
                    }

                }

            } else {
                // Si no se encuentra ninguna fila que coincida con el DNI
                echo "El usuario con DNI " . $_SESSION['usuario'] . " no existe en la base de datos. Un administrador debe confirmar que perteneces a este grupo</br>";

                // Preparar los datos de la sesiÃ³n
                $dni = $_SESSION['usuario'];
                $nombre = $_SESSION['nombre'];
                $apellidos = $_SESSION['apellido1'] . " " . $_SESSION['apellidos2'];
                $ultimaCon = $_SESSION['ultima_conexion'];
                $administrador = $_SESSION['id_rol'];
                // if (isset($resultado['ultima_conexion'])) {
                //     $_SESSION['ultima_conexion'] = $resultado['ultima_conexion'];
                // }
    
                // Consulta SQL para verificar si el usuario existe en la base de datos
                $consulta = $pdo->prepare("SELECT COUNT(*) AS count FROM personal WHERE dni = :dni");
                $consulta->bindParam(':dni', $dni);
                $consulta->execute();

                // Obtener el resultado de la consulta
                $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

                // Verificar si el usuario existe en la base de datos
                if ($resultado['count'] == 0) {
                    // El usuario no existe, insertar los datos de la sesiÃ³n en la base de datos
    


                } else {
                    echo "El usuario ya existe en la base de datos.";
                }
            }
        } catch (PDOException $e) {
            // Manejo de errores en caso de fallo en la conexiÃ³n o consulta
            echo "Error: " . $e->getMessage();
        }



    } else {
        echo "Debes Iniciar Session para poder ver el contenido";
        ?>
        <a href="index.php">Inicio</a>
        <?php
    } ?>
</body>