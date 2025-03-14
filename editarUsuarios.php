<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header("Location: index.php");
    exit();
}
require_once "includes/header.php";
require_once "includes/aside.php";
$title = "Editar usuarios";
require_once "config/Conexion.php";
require "Usuario.php";
$usuario = new Usuario();
?>
<!DOCTYPE html>
<html lang="en">
<style>
     /* azul brillante =#00ABE4 */ 
    /* azul claro =#E9F1FA */
    /* blanco =#FFFFFF */
    thead{
        background-color: #00ABE4 ;
    }
    
</style>

<!-- <div class="sb-nav-fixed"> -->
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <?php
                    if (isset($_SESSION['usuario'])) {
                ?>
                <body>
                    <div class="container p-0 my-5" style="max-width: 96%;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-table me-1"></i>
                                            <strong>Listado de usuarios</strong>
                                        </div>
                                        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'];?>" class="d-inline-block">
                                            <div class="input-group">
                                                <input class="form-control" type="text" placeholder="Busqueda de donantes..." name="busqueda"
                                                    id="inputBusqueda" aria-label="Buscar por..." aria-describedby="btnNavbarSearch" />
                                                <button class="btn btn-primary" type="submit" id="btnNavbarSearch">
                                                    <i class="bi bi-search"></i> Buscar
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <?php
                                            
                                            // $pdo = (new Conexion())->getConexion();
                                            // $query = "SELECT id_usuario, nombre, apellido1, apellido2, id_rol, ultima_conexion from usuario";
                                            // $stmt = $pdo->prepare($query);
                                            // $stmt->execute();
                                            // $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            // echo "<table class='table table-striped table-hover'>";
                                            // echo "<thead>
                                            //     <th>id_usuario</th>
                                            //     <th>nombre</th>
                                            //     <th>apellido1</th>
                                            //     <th>apellido2</th>
                                            //     <th>id_rol</th>
                                            //     <th>ultima_conexion</th>
                                            // </thead>";
                                            // foreach ($result as $row) {
                                            //     echo "<tbody>";
                                            //     echo "<tr class='clickable-row' data-id='".$row['id_usuario']."'>";
                                            //     echo "<td>".$row['id_usuario']."</td>";
                                            //     echo "<td>".$row['nombre']."</td>";
                                            //     echo "<td>".$row['apellido1']."</td>";
                                            //     echo "<td>".$row['apellido2']."</td>";
                                            //     echo "<td>".$row['id_rol']."</td>";
                                            //     echo "<td>".$row['ultima_conexion']."</td>";
                                            //     echo "</tr></tbody>";
                                            //     // echo $row['id_usuario'].''.$row['nombre'].''.$row['apellido1'].''.$row['apellido2'].''.$row['ultima_conexion'];
                                            // }
                                            // echo "</table>";
                                           echo $usuario->buscarUsuario(!empty($_POST['busqueda']) ? $_POST['busqueda'] : "");
                                        
                                            
                                            ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </body>
                    <?php
                    } else {
                        // Si no hay sesión de usuario, mostrar mensaje de error
                        echo "No se ha iniciado sesión.";
                    }
                    ?>
            </div>
        </main>
        <?php
        require_once "includes/footer.php";
        ?>
    </div>
<!-- </div> -->
</body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var rows = document.querySelectorAll("tr.clickable-row");

        rows.forEach(function (row) {
            row.addEventListener("click", function () {
                var idUsuario = row.getAttribute("data-id");

                // Redirigir a la página de modificación de datos con el ID del usuario
                window.location.href = "modificarDatosUsuario.php?id=" + idUsuario;
                console.log(idUsuario);
                
            });
        });
    });
</script>
</html>