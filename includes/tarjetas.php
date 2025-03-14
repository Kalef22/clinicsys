 <!-- INICIO TARJETAS -->
 <div class="row">
                            <?php
                            require_once "Acceso.php";
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

                        <!-- FIN TARJETAS -->