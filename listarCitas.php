<?php
session_start();
if(!isset($_SESSION['usuario'])){
header("Location: index.php");
exit();
}
require_once "includes/header.php";
require_once "includes/aside.php";
?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css"> <!-- Asegúrate de que la URL esté correcta -->
<style>
    /* azul brillante =#00ABE4 */ 
    /* azul claro =#E9F1FA */
    /* blanco =#FFFFFF */

    thead {
        background-color: #00ABE4;
    }
    tr:hover {
        background-color: #D6EAF8;
    }
</style>

<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4 my-5">
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    <strong>Listado de citas</strong>
                </div>
                <div class="card-body">
                    <?php
                    include 'config/Conexion.php';
                    $conexion = new Conexion();
                    $conn = $conexion->getConexion();

                    try {
                        $sql = "SELECT cita.id_cita, donante.nombre, donante.apellido1, donante.apellido2, donante.dni, 
                                maquina.descripcion_maquina, calendario.fecha, cita.hora_inicio  
                                FROM cita 
                                LEFT JOIN donante ON cita.id_donante = donante.id_donante 
                                LEFT JOIN maquina ON cita.id_maquina = maquina.id_maquina 
                                LEFT JOIN calendario ON cita.id_dia = calendario.id_dia";
                    
                        $stm = $conn->prepare($sql);
                        $stm->execute();
                        $stm->setFetchMode(PDO::FETCH_ASSOC);
                        $rows = $stm->fetchAll();
                    
                        // echo "<pre>";
                        // var_dump($rows);
                        // echo "</pre>";
                        
                        if (count($rows) > 0) {
                            echo "<table id='mitabla' class='table table-striped table-hover table-bordered'>
                                    <thead>
                                        <tr>
                                            <th>Id cita</th>
                                            <th>Nombre</th>
                                            <th>Primer apellido</th>
                                            <th>Segundo apellido</th>
                                            <th>DNI</th>
                                            <th>Nombre maquina</th>
                                            <th>Fecha</th>
                                            <th>Hora inicio</th>
                                        </tr>
                                    </thead>
                                    <tbody>";
                            foreach ($rows as $row) {
                                echo "<tr>
                                        <td>".$row['id_cita']."</td>
                                        <td>".$row['nombre']."</td>
                                        <td>".$row['apellido1']."</td>
                                        <td>".$row['apellido2']."</td>
                                        <td>".$row['dni']."</td>
                                        <td>".$row['descripcion_maquina']."</td>
                                        <td>".$row['fecha']."</td>
                                        <td>".$row['hora_inicio']."</td>
                                    </tr>";
                            }
                            echo "</tbody></table>";
                        } else {
                            echo "No hay datos disponibles";
                        }
                    } catch (PDOException $e) {
                        echo "Error en la consulta: ". $e->getMessage();
                    }
                    
                    ?>
                </div>
            </div>
        </div>
    </main>
<?php
require_once 'includes/footer.php';
?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('#mitabla').DataTable({
            "paging": true,
            "lengthMenu": [5, 10, 15, 20],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
            }
        });
    });
</script>
