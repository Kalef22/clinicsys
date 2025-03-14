<!DOCTYPE html>
<html lang="en">
<?php
session_start();
$title = "Mi Cuenta";

require "Usuario.php";
?>

<body class="sb-nav-fixed">
    <?php
    require_once "includes/header.php";
    require_once "includes/aside.php";

    ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Mi cuenta</h1>
                <?php
                if (isset($_SESSION['usuario'])) {
                    // Obtener los datos de la sesiÃ³n
                    $usuario = $_SESSION['usuario'];
                    //$rol = $_SESSION['rol'];
                    $ip = $_SESSION['ip'];
                    $nombre = $_SESSION['nombre'];
                    $apellidos = $_SESSION['apellidos'];
                    $timeout = $_SESSION['timeout'];
                    ?>

                    <body>
                        <div class="container">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Usuario:</strong>
                                        <?php echo $usuario; ?>
                                    </p>
                                    <p><strong>Rol:</strong>
                                        <?php //echo $rol; ?>
                                    </p>
                                    <p><strong>IP:</strong>
                                        <?php echo $ip; ?>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Nombre:</strong>
                                        <?php echo $nombre; ?>
                                    </p>
                                    <p><strong>Apellidos:</strong>
                                        <?php echo $apellidos; ?>
                                    </p>
                                    <p><strong>Hora de inicio de sesión:</strong>
                                        <?php // Establecer la hora de inicio de sesión
                                            // if (!isset($_SESSION['timeout'])) {
                                            //     $_SESSION['timeout'] = date('Y-m-d H:i:s'); // Establecer como cadena de texto
                                            //     // $_SESSION['timeout'] = time(); // Alternativamente, establecer como marca de tiempo
                                            // }
                                            echo date('Y-m-d H:i:s', $_SESSION['timeout']);
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php
                } else {
                    // Si no hay sesiÃ³n de usuario, mostrar mensaje de error
                    echo "No se ha iniciado sesión.";
                }
                ?>


            </div>
        </main>
        <?php
        require_once "includes/footer.php";
        ?>
    </div>
    </div>
</body>

</html>