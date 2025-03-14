<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header("Location: index.php");
    exit();
};
$id_usuario = $_SESSION['usuario'];
require_once 'includes/header.php';
require_once 'includes/aside.php';
// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';
?>
<style>
    /* azul brillante =#00ABE4 */ 
    /* azul claro =#E9F1FA */
    /* blanco =#FFFFFF */

        thead{
            background-color: #00ABE4 ;
        }
        tr:hover{
            background-color: #D6EAF8  ;
          
        }
        td{
            background-color: #E9F1FA  ;
        }
    </style>

<div id="layoutSidenav_content">
<!-- MAIN INICIO -->
    <main>
        <div class="container-fluid px-4 my-5">
            <!-- <h1 class="mt-4">Dashboard</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol> -->
                 <!-- Tarjetas -->
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    <strong>Lista de usuarios</strong>
                </div>
                <div class="card-body">
                    <div class="container mt-5">
                        <h4>Tabla de busqueda</h4>
                        <input class="form-control mb-3" id="buscar" type="text" placeholder="Buscar en la tabla">

                    </div>
                    <?php
                    include 'config/Conexion.php';
                    $conexion = new Conexion();
                    // Obtener la conexión PDO
                    $conn = $conexion->getConexion();
                    
                    try {
                        // Preparar la consulta
                        $sql = "SELECT id_usuario, nombre, apellido1, apellido2, id_rol FROM usuario";
                        // Prepara la consulta
                        $stm = $conn->prepare($sql);
                        // Ejecuta la consulta
                        $stm->execute();
                    
                        // Configurar el modo de obtención de resultados como un array asociativo
                        $stm->setFetchMode(PDO::FETCH_ASSOC);
                    
                        // Verificar si hay filas
                        if ($stm->rowCount() > 0) {
                            // Comienza la tabla HTML
                            echo "<table class='table table-bordered table-striped table-hover'>
                            <thead>
                            <tr class=''>
                                <th>Id usuario</th>
                                <th>Nombre</th>
                                <th>Primer apellido</th>
                                <th>Segundo apellido</th>
                                <th>Rol</th>
                                <th>Ultima conexion</th>
                            </tr>
                            </thead>";
                            echo "<tbody>";
                            // Recorrer los resultados y generar las filas de la tabla
                            foreach ($stm->fetchAll() as $row) {
                                // Obtener la descripcion_rol
                                try {
                                    $query_rol = "SELECT descripcion_rol FROM rol WHERE id_rol = :id_rol";
                                    $stmt_rol = $conn->prepare($query_rol);
                                    $stmt_rol->bindParam(':id_rol', $row['id_rol']);
                                    $stmt_rol->execute();
                                    $rol = $stmt_rol->fetch(PDO::FETCH_ASSOC);
                                    $descripcion_rol = $rol['descripcion_rol'];
                                } catch (PDOException $e) {
                                    $descripcion_rol = "Error al obtener rol";
                                }
                    
                                // Obtener la última conexión
                                try {
                                    $query_conexion = "SELECT fecha FROM acceso WHERE id_usuario = :id_usuario ORDER BY fecha DESC LIMIT 1";
                                    $stmt_conexion = $conn->prepare($query_conexion);
                                    $stmt_conexion->bindParam(':id_usuario', $row['id_usuario']);
                                    $stmt_conexion->execute();
                                    $fecha = $stmt_conexion->fetch(PDO::FETCH_COLUMN);
                                } catch (PDOException $e) {
                                    $fecha = "Error al obtener fecha";
                                }
                    
                                echo "<tr>
                                        <td>" . $row['id_usuario'] . "</td>
                                        <td>" . $row['nombre'] . "</td>
                                        <td>" . $row['apellido1'] . "</td>
                                        <td>" . $row['apellido2'] . "</td>
                                        <td>" . $descripcion_rol . "</td>
                                        <td>" . $fecha . "</td>
                                    </tr>";
                            }
                            echo "</tbody>";
                            // Cerrar la tabla
                            echo "</table>";
                        } else {
                            echo "No se encontraron usuarios.";
                        }
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>
<!-- MAIN FINAL -->
 <?php
 require_once 'includes/footer.php' ?>
</div>
<script>
    document.getElementById("buscar").addEventListener("keyup", function(){
        //obtener el valor del campo de busqueda y convertirlo a minuscula
        let valorBusqueda = this.value.toLowerCase();
        //seleccionar todas las filas de la tablas
        let fila = document.querySelectorAll("tbody tr");

        //recorrer las filas de tablas
        fila.forEach(function(fila){
            //obtener el texto de la fila y convertirlo a minuscula
            let textoFila = fila.textContent.toLowerCase();
            //mostrar la fila si coincide con al busqueda, ocultarla si no 
            fila.style.display= textoFila.includes(valorBusqueda)? "": "none";
         });
        });
</script>