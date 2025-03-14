<?php
class Maquina
{
    private $pdo;

    // public function __construct()
    // {
    //     $host = '10.35.50.118'; // Quita el número de puerto aquí
    //     $port = '3306'; // Agrega el número de puerto aquí
    //     $dbname = 'aferesis';
    //     $username = 'root';
    //     $password = '12mariadb';

    //     try {
    //         // Conexión a la base de datos utilizando PDO
    //         $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8";
    //         $this->pdo = new PDO($dsn, $username, $password);
    //         $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //     } catch (PDOException $e) {
    //         // Manejo de errores en caso de fallo en la conexión o consulta
    //         echo "Error: en la conexión a la base de datos " . $e->getMessage();
    //     }
    // }

    public function __construct(){
        
        $this->pdo = (new Conexion())->getConexion();
    }

    public function altaMaquina($descripcion, $estado)
    {
        try {
            // Consulta SQL para insertar una nueva máquina
            $query = "INSERT INTO maquina (descripcion_maquina, activa) VALUES (:descripcion, :estado)";

            // Preparar la consulta
            $stmt = $this->pdo->prepare($query);

            // Vincular los parámetros
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':estado', $estado);

            // Ejecutar la consulta
            $stmt->execute();

            // Devolver un mensaje de éxito
            return "La máquina se agregó correctamente a la base de datos.";
        } catch (PDOException $e) {
            // Manejar errores en caso de problemas de conexión o consulta
            throw new Exception("Error: en la conexión a la base de datos " . $e->getMessage());
        }
    }

    public function listarMaquinas()
    {
        try {
            // Consulta SQL para seleccionar todas las máquinas
            $query = "SELECT * FROM maquina";

            // Preparar la consulta
            $stmt = $this->pdo->prepare($query);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener los resultados de la consulta
            $maquinas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Iniciar la tabla HTML
            $output = '<table class="table table-striped table-hover">';
            $output .= '<thead><tr><th>ID</th><th>Descripción</th><th>Activa</th><th>Estado</th></tr></thead>';
            $output .= '<tbody>';

            // Iterar sobre cada máquina y crear filas de tabla
            // $alternate = true;
            foreach ($maquinas as $maquina) {
                // Alternar entre clases de colores para filas
                $output .= '<tr class="clickable-row" data-id="' . $maquina['id_maquina'] . '">';
                // Mostrar solo los últimos cuatro dígitos del ID
                $id_maquina = substr($maquina['id_maquina'], -5);
                $output .= '<td>' . $id_maquina . '</td>';
                $output .= '<td>' . $maquina['descripcion_maquina'] . '</td>';
                $output .= '<td>' . $maquina['activa'] . '</td>';
                // Incluir el estado actual de la máquina en el formulario
                $output .= '<td>';
                $output .= '<form method="post" id="formulario_estado">';
                $output .= '<input type="hidden" name="id_maquina" value="' . $maquina['id_maquina'] . '">';
                $output .= '<input type="hidden" class="cambiarColor" id="inputIdEstado" name="estado_actual" value="' . $maquina['activa'] . '">';
                $output .= '<button type="submit" class="btn btn-sm btn-danger cambiar_estado" name="boton_presionado" value="true">Cambiar Estado</button>';
                $output .= '</form>';
                $output .= '</td>';
                $output .= '</tr>';
                // Alternar para la próxima fila
                // $alternate = !$alternate;
            }

            // Cerrar la tabla HTML
            $output .= '</tbody></table>';

            // Devolver los resultados
            return $output;

        } catch (PDOException $e) {
            // Manejar errores en caso de problemas de conexión o consulta
            return "Error: en la conexión a la base de datos " . $e->getMessage();
        }
    }

    public function actualizarEstadoMaquina($id_maquina, $estado_maquina)
    {
        try {
            // Invertir el estado de la máquina
            $nuevo_estado = ($estado_maquina == "SI") ? "NO" : "SI";

            // Consulta SQL para actualizar el estado de la máquina
            $query = "UPDATE maquina SET activa = :nuevo_estado WHERE id_maquina = :id_maquina";

            // Preparar la consulta
            $stmt = $this->pdo->prepare($query);

            // Vincular los parámetros
            $stmt->bindParam(':nuevo_estado', $nuevo_estado);
            $stmt->bindParam(':id_maquina', $id_maquina);

            // Ejecutar la consulta
            $stmt->execute();

            // Devolver el nuevo estado de la máquina
            return $nuevo_estado;
        } catch (PDOException $e) {
            // Manejar errores en caso de problemas de conexión o consulta
            throw new Exception("Error: en la conexión a la base de datos " . $e->getMessage());
        }
    }

    public function eliminarMaquina()
    {
        try {
            // Consulta SQL para seleccionar todas las máquinas
            $query = "SELECT * FROM maquina";

            // Preparar la consulta
            $stmt = $this->pdo->prepare($query);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener los resultados de la consulta
            $maquinas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Iniciar la tabla HTML
            $output = '<table class="table table-striped table-hover">';
            $output .= '<thead><tr><th>ID</th><th>Descripción</th><th>Activa</th><th>Estado</th></tr></thead>';
            $output .= '<tbody>';

            // Iterar sobre cada máquina y crear filas de tabla
            // $alternate = true;
            foreach ($maquinas as $maquina) {
                // Alternar entre clases de colores para filas
                // $row_class = $alternate ? 'table-primary' : 'table-secondary';
                $output .= '<tr class="clickable-row" data-id="' . $maquina['id_maquina'] . '">';
                // Mostrar solo los últimos cuatro dígitos del ID
                $id_maquina = substr($maquina['id_maquina'], -5);
                $output .= '<td>' . $id_maquina . '</td>';
                $output .= '<td>' . $maquina['descripcion_maquina'] . '</td>';
                $output .= '<td>' . $maquina['activa'] . '</td>';
                // Incluir el estado actual de la máquina en el formulario
                $output .= '<td>';
                $output .= '<form method="post" onsubmit="return confirm(\'¿Estás seguro de que deseas eliminar esta máquina ' . $maquina['id_maquina'] . '?\');">';
                $output .= '<input type="hidden" name="id_maquina" value="' . $maquina['id_maquina'] . '">';
                $output .= '<button type="submit" class="btn btn-sm btn-danger" name="eliminar_maquina" value="true">Eliminar maquina</button>';
                $output .= '</form>';
                $output .= '</td>';
                $output .= '</tr>';
                // // Alternar para la próxima fila
                // $alternate = !$alternate;
            }

            // Cerrar la tabla HTML
            $output .= '</tbody></table>';

            // Devolver los resultados
            return $output;

        } catch (PDOException $e) {
            // Manejar errores en caso de problemas de conexión o consulta
            return "Error: en la conexión a la base de datos " . $e->getMessage();
        }
    }
}