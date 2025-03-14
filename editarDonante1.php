<!DOCTYPE html>
<html lang="en">
<?php
session_start();
$title = "Editar Donantes";
require_once "config/Conexion.php";
require "Donante.php";
require_once "includes/header.php";
require_once "includes/aside.php";
$donante = new Donante();

?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css"> <!-- Asegúrate de que la URL esté correcta -->
<style>
     /* azul brillante =#00ABE4 */ 
    /* azul claro =#E9F1FA */
    /* blanco =#FFFFFF */
    thead{
        background-color: #00ABE4 ;
    }
    .container-fluid {
    width: 100%;
    padding: 0;
    overflow-x: hidden; /* Evita scroll horizontal */
}
    
</style>

<div class="sb-nav-fixed">
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <?php
                    if (isset($_SESSION['usuario'])) {
                ?>
                <body>
                    <div class="container p-0 my-5" style="max-width: 98%;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-table me-1"></i>
                                            <strong>Listado de donantes a modificar</strong>
                                        </div>
                                        <!-- <form method="POST" action="<?php //echo $_SERVER['PHP_SELF'];?>" class="d-inline-block">
                                            <div class="input-group">
                                                <input class="form-control" type="text" placeholder="Busqueda de donantes..." name="busqueda"
                                                    id="inputBusqueda" aria-label="Buscar por..." aria-describedby="btnNavbarSearch" />
                                                <button class="btn btn-primary" type="submit" id="btnNavbarSearch">
                                                    <i class="bi bi-search"></i> Buscar
                                                </button>
                                            </div>
                                        </form> -->
                                    </div>
                                    <div class="card-body">
                                        <!-- <div class="container mt-5">
                                            <h4>Tabla de busqueda</h4>
                                            <input class="form-control mb-3" id="buscar" type="text" placeholder="Buscar en la tabla">
                                        </div> -->
                                        <div class="table-responsive">
                                            <?php
                                            // echo $donante->buscarPaciente(!empty($_POST['busqueda']) ? $_POST['busqueda'] : " ");
                                            echo $donante->listarDonantesNew();
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
</div>
</body>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        var rows = document.querySelectorAll("tr.clickable-row");

        rows.forEach(function (row) {
            row.addEventListener("click", function () {
                var idDonante = row.getAttribute("data-id");

                // Redirigir a la página de modificación de datos con el ID del donante
                window.location.href = "modificarDatosDonante.php?id=" + idDonante;
                console.log(idDonante);
                
            });
        });
    });
    
    // //buscador input grande
    // document.getElementById("buscar").addEventListener("keyup", function(){
    //     //obtener el valor del campo de busqueda y convertirlo a minuscula
    //     let valorBusqueda = this.value.toLowerCase();
    //     //seleccionar todas las filas de la tablas
    //     let fila = document.querySelectorAll("tbody tr");

    //     //recorrer las filas de tablas
    //     fila.forEach(function(fila){
    //         //obtener el texto de la fila y convertirlo a minuscula
    //         let textoFila = fila.textContent.toLowerCase();
    //         //mostrar la fila si coincide con al busqueda, ocultarla si no 
    //         fila.style.display= textoFila.includes(valorBusqueda)? "": "none";
    //      });
    //     });


</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('#mitablaDonanteBuscar').DataTable({
            "paging": true,
            "lengthMenu": [5, 10, 15, 20],
            "language": {
                "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
            }
        });
    });
</script>
</html>