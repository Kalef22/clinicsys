<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header("Location: index.php");
    exit();
}
require_once 'includes/header.php';
require_once 'includes/aside.php';
include 'config/Conexion.php';

// Mensaje de eliminación, inicializado vacío
$aviso_eliminacion = "";

// Procesar la eliminación del usuario si se envía el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_usuario'])) {
    $idUsuario = $_POST['id_usuario'];
    // echo "<pre>";
    // print_r($idUsuario);
    // echo "</pre>";
    $conexion = new Conexion();
    $conn = $conexion->getConexion();

    try {
        // Eliminar solo el usuario específico usando su ID
        $sql = "DELETE FROM usuario WHERE id_usuario = :id";
        $stm = $conn->prepare($sql);
        $stm->bindParam(':id', $idUsuario);

        if ($stm->execute()) {
            $aviso_eliminacion = "<div class='alert alert-success'>Usuario eliminado correctamente.</div>";
        } else {
            $aviso_eliminacion = "<div class='alert alert-danger'>Error al eliminar el usuario.</div>";
        }
    } catch (PDOException $e) {
        $aviso_eliminacion = "<div class='alert alert-danger'>Error en la eliminación: " . $e->getMessage() . "</div>";
    }
}
?>

<style>
    thead {
        background-color: #00ABE4;
    }
    tr:hover {
        background-color: #D6EAF8;
    }
    td {
        background-color: #E9F1FA;
    }
</style>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4 my-5">
            <?php
            // Mostrar mensaje de éxito o error si se ha realizado una acción de eliminación
            echo $aviso_eliminacion;
            ?>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    <strong>Lista de usuarios</strong>
                </div>
                <div class="card-body">
                    <div class="container mt-5">
                        <h4>Tabla de búsqueda</h4>
                        <!-- input que ira enlazado con el js para buscar usuario -->
                        <input class="form-control mb-3" id="buscar" type="text" placeholder="Buscar en la tabla">
                    </div>
                    <?php
                    // Obtener la lista de usuarios
                    $conexion = new Conexion();
                    $conn = $conexion->getConexion();

                    try {
                        $sql = "SELECT id_usuario, nombre, apellido1, apellido2, id_rol, ultima_conexion FROM usuario";
                        $stm = $conn->prepare($sql);
                        $stm->execute();
                        $stm->setFetchMode(PDO::FETCH_ASSOC);

                        if ($stm->rowCount() > 0) {
                            echo "<table class='table table-bordered table-striped table-hover'>
                                <thead>
                                <tr>
                                    <th>Id usuario</th>
                                    <th>Nombre</th>
                                    <th>Primer apellido</th>
                                    <th>Segundo apellido</th>
                                    <th>Rol</th>
                                    <th>Última conexión</th>
                                    <th>Acción</th>
                                </tr>
                                </thead>";
                            echo "<tbody>";

                            foreach ($stm->fetchAll() as $row) {
                                // Cada fila tiene su propio formulario para enviar el ID único
                                echo "<tr>
                                        <td>".$row['id_usuario']."</td>
                                        <td>".$row['nombre']."</td>
                                        <td>".$row['apellido1']."</td>
                                        <td>".$row['apellido2']."</td>
                                        <td>".$row['id_rol']."</td>
                                        <td>".$row['ultima_conexion']."</td>
                                        <td>
                                            <form method='POST' action='' style='display:inline;' onsubmit='return confirm(\"¿Estás seguro de que deseas eliminar este usuario ".$row['id_usuario']." ?\");'>
                                                <input type='hidden' name='id_usuario' value='".$row['id_usuario']."'>
                                                <button type='submit' class='btn btn-danger btn-sm'>Eliminar</button>
                                            </form>
                                        </td>
                                      </tr>";
                            }
                            echo "</tbody>";
                            echo "</table>";
                        } else {
                            echo "No hay datos disponibles";
                        }
                    } catch (PDOException $e) {
                        echo "Error en la consulta: " . $e->getMessage();
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>
    <?php require_once 'includes/footer.php'; ?>
</div>

<script>
    // js para buscar un usuario
    document.getElementById("buscar").addEventListener("keyup", function() {
        let valorBusqueda = this.value.toLowerCase();
        let fila = document.querySelectorAll("tbody tr");
        fila.forEach(function(fila) {
            let textoFila = fila.textContent.toLowerCase();
            fila.style.display = textoFila.includes(valorBusqueda) ? "" : "none";
        });
    });
</script>
