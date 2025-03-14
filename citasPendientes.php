<?php
session_start();
require_once 'includes/header.php';
require_once 'includes/aside.php';
require_once 'config/Conexion.php';
require_once 'Maquina.php';
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
        <div class="container-fluid px-4">
            <!-- <h1 class="mt-4">Dashboard</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item active">Dashboard</li>
            </ol> -->
            <!-- INICIO TARJETAS -->
           
            <!-- FIN TARJETAS -->
             <div class="card-header mt-5">
                <?php
                    include "includes/tarjetas.php";
                ?>
             </div>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-table me-1"></i>
                    <strong>Listado de Citas</strong>
                </div>
                <div class="card-body">
                    <?php
                        try {
                            $fecha_hoy = new DateTime();
                            $fecha_hoy_formato = $fecha_hoy->format('Y-m-d');

                            $pdo = (new Conexion())->getConexion();

                            $query_dia = "SELECT id_dia from calendario where fecha = :fecha_dia ";
                            $stmt_dia = $pdo->prepare($query_dia);
                            $stmt_dia->bindParam(":fecha_dia", $fecha_hoy_formato);
                            $stmt_dia->execute();
                            $id_dia=$stmt_dia->fetchColumn();
                     

                            $query_cita = "SELECT ci.id_cita, do.nombre, ca.fecha, id_maquina, apto, ci.hora_inicio 
                                            FROM cita AS ci INNER JOIN donante AS do ON ci.id_donante = do.id_donante INNER JOIN calendario AS ca ON ci.id_dia = ca.id_dia   
                                            WHERE ci.id_dia = $id_dia";
                            $stmt = $pdo->prepare($query_cita);
                            $stmt->execute();
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            
                            // Iniciar tabla una sola vez antes del bucle
                            echo "<table class='table table-striped table-hover'>";
                            echo "<thead>";
                            echo "<th>Id cita</th>";
                            echo "<th>Nombre</th>";
                            echo "<th>Fecha</th>";
                            echo "<th>Nombre maquina</th>";
                            echo "<th>apto</th>";
                            echo "<th>Hora de inicio</th>";
                            echo "</thead>";
                            foreach ($result as $row) {
                                echo "<tr>";
                                echo "<td>" . $row['id_cita'] . "</td>";
                                echo "<td>" . $row['nombre'] . "</td>";
                                echo "<td>" . $row['fecha'] . "</td>";
                                echo "<td>" . $row['id_maquina'] . "</td>";
                                echo "<td>" . $row['apto'] . "</td>";
                                echo "<td>" . $row['hora_inicio'] . "</td>";
                                echo "</tr>";
                            }
                            // Cerrar la tabla fuera del bucle
                            echo "</table>";
                            
                        } catch (PDOException $e) {
                            echo "Error de conexion ".$e->getMessage();
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
 