<?php
session_start();
if(!isset($_SESSION['usuario'])){
    header("Location: index.php");
    exit();
}
require_once "includes/header.php";
require_once "includes/aside.php";
require_once "config/Conexion.php";

// Obtener los datos de las citas desde la base de datos
$pdo = (new Conexion())->getConexion();
$query = "
    SELECT calendario.fecha, COUNT(cita.id_cita) as total 
    FROM cita 
    JOIN calendario ON cita.id_dia = calendario.id_dia 
    GROUP BY calendario.fecha
";
$stmt = $pdo->query($query);
$citas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Preparar los datos para pasarlos a JavaScript
$fechas = [];
$totales = [];
foreach ($citas as $cita) {
    $fechas[] = $cita['fecha'];
    $totales[] = $cita['total'];
}
?>
<div id="layoutSidenav_content">
    <main>
        <div class="container-fluid px-4">
            <h1 class="mt-4">Grafico citas</h1>
            <ol class="breadcrumb mb-4">
                <li class="breadcrumb-item"><a href="inicio.php">Dashboard</a></li>
                <li class="breadcrumb-item active">Graficos</li>
            </ol>
            <div class="card mb-4">
                <!-- <div class="card-body">
                Chart.js es un complemento de terceros que se utiliza para generar los gráficos de esta plantilla. Los gráficos que aparecen a continuación se han personalizado; para obtener más opciones de personalización, visite el sitio web oficial
                    <a target="_blank" href="https://www.chartjs.org/docs/latest/">Chart.js documentation</a>.
                </div> -->
                <div class="card-body">
                    Si se desea se pueden añadir mas campos de medicion como por ejemplo los donantes que no asistieron a las citas.
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-header">
                    <i class="fas fa-chart-area me-1"></i>
                    Grafico de citas en area
                </div>
                <div class="card-body"><canvas id="myAreaChart" width="100%" height="30"></canvas></div>
                <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-chart-bar me-1"></i>
                            Grafico de citas barras
                        </div>
                        <div class="card-body"><canvas id="myBarChart" width="100%" height="50"></canvas></div>
                        <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-chart-pie me-1"></i>
                            Grafico de citas circular
                        </div>
                        <div class="card-body"><canvas id="myPieChart" width="100%" height="50"></canvas></div>
                        <div class="card-footer small text-muted">Updated yesterday at 11:59 PM</div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- Bootstrap JS y Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Obtener los datos de PHP
        const fechas = <?php echo json_encode($fechas); ?>;
        const totales = <?php echo json_encode($totales); ?>;

        // Configurar el gráfico de área
        const ctxArea = document.getElementById('myAreaChart').getContext('2d');
        const myAreaChart = new Chart(ctxArea, {
            type: 'line',
            data: {
                labels: fechas,
                datasets: [{
                    label: 'Citas',
                    data: totales,
                    backgroundColor: 'rgba(2, 117, 216, 0.2)',
                    borderColor: 'rgba(2, 117, 216, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Configurar el gráfico de barras
        const ctxBar = document.getElementById('myBarChart').getContext('2d');
        const myBarChart = new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: fechas,
                datasets: [{
                    label: 'Citas',
                    data: totales,
                    backgroundColor: 'rgba(2, 117, 216, 0.2)',
                    borderColor: 'rgba(2, 117, 216, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Configurar el gráfico de pastel
        const ctxPie = document.getElementById('myPieChart').getContext('2d');
        const myPieChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: fechas,
                datasets: [{
                    label: 'Citas',
                    data: totales,
                    backgroundColor: [
                        'rgba(2, 117, 216, 0.2)',
                        'rgba(92, 184, 92, 0.2)',
                        'rgba(240, 173, 78, 0.2)',
                        'rgba(217, 83, 79, 0.2)'
                    ],
                    borderColor: [
                        'rgba(2, 117, 216, 1)',
                        'rgba(92, 184, 92, 1)',
                        'rgba(240, 173, 78, 1)',
                        'rgba(217, 83, 79, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ': ' + tooltipItem.raw;
                            }
                        }
                    }
                }
            }
        });
    });
</script>