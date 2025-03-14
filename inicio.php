<?php
    session_start();
    
// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {
    // Redirigir al usuario a la página de inicio de sesión
    header("Location: index.php");
    exit();
}
    require_once "includes/header.php";
    require_once "includes/aside.php";
    require_once "Acceso.php";
    require_once "config/Conexion.php"

?>
        
            <div id="layoutSidenav_content">

                 <!-- MAIN INICIO -->
                <main>
                    <div class="container-fluid px-4">
                        <div class="px-3 py-4">
                        <img src="assets/img/logo_12octubre.png" alt="">
                        </div>
                        
                        <!-- <h1 class="mt-4">Dashboard</h1> -->
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                        <!-- INICIO TARJETAS -->
                        <div class="row">
                            <?php
                            $acceso = new Acceso();
                            $cantidadDonantes = $acceso->obtenerCantidadDonantes();
                             ?>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-primary text-white mb-4">
                                    <div class="card-body">Donantes registrados
                                        <h2 class="card-text">
                                            <?php echo $cantidadDonantes; ?>
                                        </h2>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        
                                        <a class="small text-white stretched-link" href="listarDonantes.php">Ver detalles</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                $cantidadCitasFuturas = $acceso->obtenerCantidadCitasFuturas();
                            ?>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-warning text-white mb-4">
                                    <div class="card-body">Citas pendientes
                                        <h2 class="card-text">
                                            <?php echo $cantidadCitasFuturas; ?>
                                        </h2>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="citasPendientes.php">Ver detalles</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                $cantidadMaquinasActivas = $acceso->obtenerCantidadMaquinasActivas();
                            ?>
                               
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-success text-white mb-4">
                                    <div class="card-body">Maquinas activas
                                        <h2 class="card-text">
                                            <?php echo $cantidadMaquinasActivas; ?>
                                        </h2>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="listadoMaquinas.php">Ver detalles</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                            <?php
                                $cantidadUsuarios = $acceso->obtenerCantidadUsuariosRegistrados();
                            ?>
                            <div class="col-xl-3 col-md-6">
                                <div class="card bg-danger text-white mb-4">
                                    <div class="card-body">Usuarios registrados
                                        <h2 class="card-text">
                                            <?php echo $cantidadUsuarios; ?>
                                        </h2>
                                    </div>
                                    <div class="card-footer d-flex align-items-center justify-content-between">
                                        <a class="small text-white stretched-link" href="listarUsuarios.php">Ver detalles</a>
                                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <table>
                                <thead>
                                    <tr>
                                        <th></th>
                                    </tr>

                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot>

                                </tfoot>
                            </table>
                        </div>

                        <!-- FIN TARJETAS -->

                        <!-- INICIO TARJETAS DE BALANCE -->
                        <!-- <div class="row">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-area me-1"></i>
                                        Area Chart Example
                                    </div>
                                    <div class="card-body"><canvas id="myAreaChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Bar Chart Example
                                    </div>
                                    <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                        </div> -->
                        <!-- FIN TARJETAS DE BALANCE -->
                        
                        <!-- <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                DataTable Example
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Office</th>
                                            <th>Age</th>
                                            <th>Start date</th>
                                            <th>Salary</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>Position</th>
                                            <th>Office</th>
                                            <th>Age</th>
                                            <th>Start date</th>
                                            <th>Salary</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <tr>
                                            <td>Tiger Nixon</td>
                                            <td>System Architect</td>
                                            <td>Edinburgh</td>
                                            <td>61</td>
                                            <td>2011/04/25</td>
                                            <td>$320,800</td>
                                        </tr>
                                        <tr>
                                            <td>Garrett Winters</td>
                                            <td>Accountant</td>
                                            <td>Tokyo</td>
                                            <td>63</td>
                                            <td>2011/07/25</td>
                                            <td>$170,750</td>
                                        </tr>
                                        <tr>
                                            <td>Ashton Cox</td>
                                            <td>Junior Technical Author</td>
                                            <td>San Francisco</td>
                                            <td>66</td>
                                            <td>2009/01/12</td>
                                            <td>$86,000</td>
                                        </tr>
                                        
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div> -->
                    </div>
                </main>
                 <!-- MAIN FINAL -->

<?php 
    require_once "includes/footer.php";
?>