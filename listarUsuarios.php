<?php
session_start();
require_once 'includes/header.php';
require_once 'includes/aside.php';
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
                            <?php
                            include 'config/Conexion.php';
                            $conexion = new Conexion();
                            //obtener la conexión PDO
                            $conn = $conexion->getConexion();

                            try {
                                //preparar la consulta
                                $sql = "SELECT id_usuario, nombre, apellido1, apellido2, id_rol, ultima_conexion FROM usuario";
                                //prepara la consulta
                                $stm = $conn->prepare($sql);
                                //ejecuta la consulta
                                $stm->execute();

                                //configurar el modo de aobtencion de resultados comouna array asociativo
                                $stm->setFetchMode(PDO::FETCH_ASSOC);

                                //verificar si hay filas
                                if($stm->rowCount() > 0){
                                    //comienza la tabla HTML
                                    echo "<table class='table-striped' id='datatablesSimple'>
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
                                
                                    //recorrer los resultados y generar las filas de la tabla
                                    foreach ($stm->fetchAll() as $row) {
                                        echo"<tr>
                                                    <td>".$row['id_usuario']."</td>
                                                    <td>".$row['nombre']."</td>
                                                    <td>".$row['apellido1']."</td>
                                                    <td>".$row['apellido2']."</td>
                                                    <td>".$row['id_rol']."</td>
                                                    <td>".$row['ultima_conexion']."</td>
                                            </tr>";
                                    }
                                    //cerrar la tabla
                                    echo "</table>";
                                }else{
                                    echo "No hay datos disponibles";
                                }
                            } catch (PDOException $e) {
                                echo "Error en la consulta: ". $e->getMessage();
                            }

                                ?>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
<!-- MAIN FINAL -->
 <?php
 require_once 'includes/footer.php' ?>