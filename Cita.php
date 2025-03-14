<?php
class Cita
{
    // Propiedades
    private $pdo;

    // Constructor
    // public function __construct()
    // {
    //     $host = '10.35.50.118:3306';
    //     $dbname = 'aferesis';
    //     $username = 'root';
    //     $password = '12mariadb';

    //     try {
    //         // Conexión a la base de datos utilizando PDO
    //         $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    //         $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //     } catch (PDOException $e) {
    //         // Manejo de errores en caso de fallo en la conexión o consulta
    //         echo "Error: en la conexion a la base de datos " . $e->getMessage();
    //     }
    // }
    public function __construct(){
        require_once "config/Conexion.php";
        $this->pdo = (new Conexion())->getConexion();
    }


    // Destructor
    public function __destruct()
    {
        $this->pdo = null;
    }

    // Método para crear una nueva cita
    public function crearCita($idDonante, $idDia, $idMaquina, $apto, $horaInicio)
    {
        try {
            // Preparar la consulta
            $query = "INSERT INTO cita (id_donante, id_dia, id_maquina, apto, hora_inicio) VALUES (:idDonante, :idDia, :idMaquina, :apto, :horaInicio)";
            $statement = $this->pdo->prepare($query);

            // Asignar los valores de los parámetros
            $statement->bindParam(':idDonante', $idDonante, PDO::PARAM_INT);
            $statement->bindParam(':idDia', $idDia, PDO::PARAM_INT);
            $statement->bindParam(':idMaquina', $idMaquina, PDO::PARAM_INT);
            $statement->bindParam(':apto', $apto, PDO::PARAM_STR);
            $statement->bindParam(':horaInicio', $horaInicio, PDO::PARAM_STR);

            // Ejecutar la consulta
            $statement->execute();

            // Mostrar mensaje de éxito
            $mensaje = '<div class="alert alert-success" role="alert">Cita creada con éxito.</div>';
            return $mensaje;
        } catch (PDOException $e) {
            // Mostrar mensaje de error
            $mensaje = '<div class="alert alert-danger" role="alert">Error al crear la cita: ' . $e->getMessage() . '</div>';
            return $mensaje;
        }
    }


    // Método para borrar una cita por su ID
    public function borrarCita($idCita)
    {
        try {
            // Preparar la consulta
            $query = "DELETE FROM cita WHERE id_cita = :idCita";
            $statement = $this->pdo->prepare($query);

            // Asignar el valor del parámetro
            $statement->bindParam(':idCita', $idCita, PDO::PARAM_INT);

            // Ejecutar la consulta
            $statement->execute();

            return true; // Cita borrada con éxito
        } catch (PDOException $e) {
            // Manejar errores
            return false; // Error al borrar la cita
        }
    }

    // Método para editar una cita por su ID
    public function editarCita($idCita)
    {
        try {
            // Obtener los datos de la cita
            $query = "SELECT * FROM cita WHERE id_cita = :idCita";
            $statement = $this->pdo->prepare($query);
            $statement->bindParam(':idCita', $idCita, PDO::PARAM_INT);
            $statement->execute();
            $cita = $statement->fetch(PDO::FETCH_ASSOC);

            // Mostrar el formulario con los datos de la cita para editar
            if ($cita) {
                ?>
                <form method="POST" action="procesar_edicion.php">
                    <!-- Campos del formulario con los datos de la cita -->
                    <input type="hidden" name="id_cita" value="<?php echo $cita['id_cita']; ?>">
                    <label for="id_donante">ID Donante:</label>
                    <input type="text" name="id_donante" value="<?php echo $cita['id_donante']; ?>"><br>
                    <label for="id_dia">ID Día:</label>
                    <input type="text" name="id_dia" value="<?php echo $cita['id_dia']; ?>"><br>
                    <label for="id_maquina">ID Máquina:</label>
                    <input type="text" name="id_maquina" value="<?php echo $cita['id_maquina']; ?>"><br>
                    <label for="apto">Apto:</label>
                    <input type="text" name="apto" value="<?php echo $cita['apto']; ?>"><br>
                    <label for="hora_inicio">Hora de inicio:</label>
                    <input type="text" name="hora_inicio" value="<?php echo $cita['hora_inicio']; ?>"><br>
                    <!-- Botón para enviar el formulario -->
                    <input type="submit" value="Guardar cambios">
                </form>
                <?php
            } else {
                echo "La cita no existe.";
            }
        } catch (PDOException $e) {
            // Manejar errores
            echo "Error: " . $e->getMessage();
        }
    }

    public function listarCitas($fecha)
    {
        try {
            // Definir la consulta SQL
            $queryLista = "
                SELECT 
                    c.id_dia, 
                    c.descripcion_dia, 
                    c.id_horario, 
                    c.fecha, 
                    c.hora_inicio AS calendario_hora_inicio, 
                    c.hora_fin AS calendario_hora_fin,
                    ci.id_donante,
                    ci.id_maquina,
                    ci.apto,
                    ci.id_cita,
                    ci.hora_inicio AS cita_hora_inicio,
                    d.*
                FROM 
                    calendario c
                JOIN 
                    cita ci ON c.id_dia = ci.id_dia
                JOIN 
                    donante d ON ci.id_donante = d.id_donante
                WHERE 
                    c.fecha = :fecha
                ORDER BY 
                    ci.id_maquina
            ";

            // Preparar la consulta
            $stmt = $this->pdo->prepare($queryLista);

            // Vincular el parámetro de fecha
            $stmt->bindParam(':fecha', $fecha);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener todos los resultados
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Inicializar el array para agrupar por id_maquina
            $citasPorMaquina = [];

            // Agrupar los resultados por id_maquina
            foreach ($resultados as $fila) {
                $citasPorMaquina[$fila['id_maquina']][] = $fila;
            }

            return $citasPorMaquina;

        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

}

?>