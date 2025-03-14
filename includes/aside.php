<!-- ASIDE INICIO -->
<!-- <style>
    /* Cambiar color de todo el texto en el menú lateral */
    #layoutSidenav_nav .sb-sidenav .nav-link {
        color: green !important; /* Ajusta el color a tu gusto */
    }

    #layoutSidenav_nav .sb-sidenav .sb-sidenav-menu-heading{
        color: red !important; /* Ajusta el color a tu gusto */
    }

    #layoutSidenav_nav .sb-sidenav-footer {
        color: yellow !important; /* Ajusta el color a tu gusto */
    }

    /* Cambiar color al pasar el mouse */
    #layoutSidenav_nav .sb-sidenav .nav-link:hover {
        color: orangered !important;
    }

</style>
 -->


<div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">

                            <!------- primer bloque ------->
                            <div class="sb-sidenav-menu-heading">Inicio</div>
                            <a class="nav-link" href="inicio.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Inicio
                            </a>
                            <!-- Añadir Donantes y usuario -->
                            


                            <!------- Segundo bloque ------->
                            <div class="sb-sidenav-menu-heading">Interface</div>


                            <!-- <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Diseño
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>

                            <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="layout-static.html">Modo oscuro</a>
                                    <a class="nav-link" href="layout-sidenav-light.html">Modo claro</a>
                                </nav>
                            </div> -->




                            <!------------ INICIO NUEVOS AGREGADOS ------------>
                             <!-- Nuevo 1 -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseDonante" aria-expanded="false" aria-controls="collapseDonante">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Donante
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>

                            <div class="collapse" id="collapseDonante" aria-labelledby="headingThree" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="altaDonante.php">Alta donante</a>
                                    <a class="nav-link" href="listarDonantes1.php">Lista de donantes</a>
                                    <a class="nav-link" href="editarDonante.php">Modificar donantes</a>
                                    <a class="nav-link" href="eliminarDonante.php">Eliminar donantes</a>
                                </nav>
                            </div>

                            <!-- Nuevo 2 -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseUsuario" aria-expanded="false" aria-controls="collapseUsuario">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Usuarios
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>

                            <div class="collapse" id="collapseUsuario" aria-labelledby="headingThree" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="altaUsuario.php">Alta usuario</a>
                                    <a class="nav-link" href="listarUsuarios3.php">Lista de Usuarios</a>
                                    <a class="nav-link" href="editarUsuarios.php">Modificar rol</a>
                                    <a class="nav-link" href="eliminarUsuarios.php">Eliminar Usuarios</a>
                                </nav>
                            </div>
                            <!------------ FIN NUEVOS AGREGADOS ------------>

                                                                                                                <!-- id unico -->                                      <!-- id unico -->
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                                                                            <!-- icono -->
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                Pages
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>

                                                    <!-- id unico -->                  <!-- unico -->
                            <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">

                                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                    <!-- <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                        Authentication
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="index.php">Login</a>
                                            <a class="nav-link" href="register.html">Register</a>
                                            <a class="nav-link" href="password.html">Forgot Password</a>
                                        </nav>
                                    </div> -->
                                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                                        Error
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                        <nav class="sb-sidenav-menu-nested nav">
                                            <a class="nav-link" href="401.html">401 Page</a>
                                            <a class="nav-link" href="404.html">404 Page</a>
                                            <a class="nav-link" href="500.html">500 Page</a>
                                        </nav>
                                    </div>
                                </nav>
                            </div>



                            <!------- Tercer bloque ------->
                            <div class="sb-sidenav-menu-heading">Complementos</div>
                            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseGestionarCita" aria-expanded="false" aria-controls="collapseGestionarCita">
                                <div class="sb-nav-link-icon"><i class="fas fa-calendar-check"></i></div>
                                Gestionar Citas
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>

                            <div class="collapse" id="collapseGestionarCita" aria-labelledby="headingThree" data-bs-parent="#sidenavAccordion">
                                <nav class="sb-sidenav-menu-nested nav">
                                    <a class="nav-link" href="horarios_disponibles1.php">Pedir cita</a>
                                    <a class="nav-link" href="listarCitas.php">Listar cita</a>
                                    <a class="nav-link" href="modificarCita.php">Modificar cita</a>
                                    <a class="nav-link" href="eliminarCita.php">Eliminar cita</a>
                                </nav>
                            </div>





                                <!-- <a class="nav-link" href="horarios_disponibles1.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-calendar-check"></i></div>
                                    Gestionar Citas
                                </a> -->
                                <a class="nav-link" href="opcionesMaquina.php">
                                    <div class="sb-nav-link-icon"><i class="fas fa-cogs"></i></div>
                                    Máquinas
                                </a>

                                <a class="nav-link" href="charts.php">
                                    <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                    Gráficos
                                </a>
                                <!-- <a class="nav-link" href="tables.php">
                                    <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                    Tables
                                </a> -->
                            </div>
                        </div>
                    
                        <!-- Saludo y hora de sesion -->
                        <div class="sb-sidenav-footer">
                            <!-- <div class="small">Logged in as:</div>
                            Start Bootstrap -->
                            <div class="small">Bienvenido
                                <?php 
                                echo $_SESSION['nombre'] ?>
                                <?php
                                // Obtener la fecha desde el timestamp
                                $hora = $_SESSION['ultimaCon'];
                                $fechaFormateada = date("Y-m-d", strtotime($hora));

                                // Imprimir la fecha formateada
                                echo "<br>Ultima conexion: " . $fechaFormateada;
                                $hora = $_SESSION['ultimaCon'];
                                $horaFormateada = date("H:i:s", strtotime($hora));

                                // Imprimir la hora formateada
                                echo "<br> a las: " . $horaFormateada;
                                ?>
                        </div>
                    </div>
                </nav>
            </div>
            <!-- ASIDE FINAL -->