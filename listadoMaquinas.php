<?php
session_start();
require_once 'includes/header.php';
require_once 'includes/aside.php';
require_once 'config/Conexion.php';
require_once 'Maquina.php';
?>

<div id="layoutSidenav_content">
<!-- MAIN INICIO -->
    <main>
        <div class="container-fluid px-4">
            <!-- <h1 class="mt-4">Dashboard</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol> -->
            <!-- INICIO TARJETAS -->
            
            <!-- FIN TARJETAS -->

            
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    Listado de maquinas
                </div>
                <div class="card-body">
                    <?php
                        try {
                            $pdo = (new Conexion())->getConexion();
                            $query = "SELECT id_maquina, descripcion_maquina, activa FROM maquina";
                            $stmt = $pdo->prepare($query);
                            $stmt->execute();
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                            // Iniciar tabla una sola vez antes del bucle
                            echo "<table class='table table-striped table-hover'>";
                            echo "<th>Id maquina</th>";
                            echo "<th>Nombre maquina</th>";
                            echo "<th>Estado</th>";
                            foreach ($result as $row) {
                                echo "<tr>";
                                echo "<td>" . $row['id_maquina'] . "</td>";
                                echo "<td>" . $row['descripcion_maquina'] . "</td>";
                                echo "<td>" . $row['activa'] . "</td>";
                                echo "</tr>";
                            }
                            // Cerrar la tabla fuera del bucle
                            echo "</table>";
                            
                        } catch (\Throwable $th) {
                            //throw $th;
                        }          
                    ?>
                </div>
            </div>
        </div>
    </main>
    <?php
 require_once 'includes/footer.php' ?>
</div>
<!-- MAIN FINAL -->
