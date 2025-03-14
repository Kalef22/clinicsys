<?php

class Donante
{
    
    private $pdo;

    public function __construct() {
        require_once "config/Conexion.php";
        $this->pdo = (new Conexion())->getConexion();
    }


    /*******************************Funciones modificadas inicio***********************************/
    public function listarDonantesNew(){
        try {
            //preparar la consulta
            $sql = "SELECT id_donante, nombre, apellido1, apellido2, nhc, telef1, telef2, dni, ultima_donacion, recordatorio, observaciones, llamar, cipa, acepto_comunicacion, fecha_nacimiento, citable FROM donante";
            //prepara la consulta
            $stm = $this->pdo->prepare($sql);
            //ejecuta la consulta
            $stm->execute();

            //configurar el modo de aobtencion de resultados comouna array asociativo
            $stm->setFetchMode(PDO::FETCH_ASSOC);

            //verificar si hay filas
            if($stm->rowCount() > 0){
                //comienza la tabla HTML
                echo "<table id='mitablaDonanteListar' class='table table-striped table-hover table-bordered'>
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Primer apellido</th>
                    <th>Segundo apellido</th>
                    <th>NHC</th>
                    <th>Tel. 1</th>
                    <th>Tel. 2</th>
                    <th>DNI</th>
                    <th>Ultima donación</th>
                    <th>Recordatorio</th>
                    <th>Observaciones</th>
                    <th>Llamar</th>
                    <th>Cipa</th>
                    <th>Acepto comunicación</th>
                    <th>Fecha nacimiento</th>
                    <th>Citable</th>
                </tr>
                </thead>";
               
                //recorrer los resultados y generar las filas de la tabla
                foreach ($stm->fetchAll() as $row) {
                    echo"<tr>
                                <td>".$row['id_donante']."</td>
                                <td>".$row['nombre']."</td>
                                <td>".$row['apellido1']."</td>
                                <td>".$row['apellido2']."</td>
                                <td>".$row['nhc']."</td>
                                <td>".$row['telef1']."</td>
                                <td>".$row['telef2']."</td>
                                <td>".$row['dni']."</td>
                                <td>".$row['ultima_donacion']."</td>
                                <td>".$row['recordatorio']."</td>
                                <td>".$row['observaciones']."</td>
                                <td>".$row['llamar']."</td>
                                <td>".$row['cipa']."</td>
                                <td>".$row['acepto_comunicacion']."</td>
                                <td>".$row['fecha_nacimiento']."</td>
                                <td>".$row['citable']."</td>
                        </tr>";
                }
                //cerrar la tabla
                echo "</table>";
                require_once("Log.php");
                $log_usuario = new Logs();
                $evento = "Listado de donantes";
                $log_usuario->crear_log($_SESSION['usuario'],$evento);

                $id_usuario =$_SESSION['usuario'];
                $operacion = "Listar usuarios";
                $ip = $_SERVER['REMOTE_ADDR'];
                $observacion = "Se ha listado los usuarios";
                $url =  $_SERVER['REQUEST_URI'];
                
                $query_log_alta = "INSERT INTO log (id_usuario, operacion, ip, observacion, url) VALUES (:id_usuario, :operacion, :ip, :observacion, :url)";
                $statement_log_alta = $this->pdo->prepare($query_log_alta);
                $statement_log_alta->bindParam(':id_usuario', $id_usuario);
                $statement_log_alta->bindParam(':operacion', $operacion);
                $statement_log_alta->bindParam(':ip', $ip);
                $statement_log_alta->bindParam(':observacion', $observacion);
                $statement_log_alta->bindParam(':url', $url);
                $statement_log_alta->execute();

            }else{
                echo "No hay datos disponibles";
            }
           } catch (PDOException $e) {
            echo "Error en la consulta: ". $e->getMessage();
           }
    }


    public function altaDonante($nombre, $apellido1, $apellido2, $nhc = null, $telefono1, $telefono2, $dni, $ultimaDonacion, $recordatorio, $observaciones, $llamar, $cipa = null, $aceptaComunicacion, $fechaNacimiento, $citable)
    {
        try {
            // Verificar si nhc o cipa son nulos o vacíos y asignarles valor NULL si es necesario
            $nhc = !empty($nhc) ? $nhc : null;
            $cipa = !empty($cipa) ? $cipa : null;
            
            // Preparar la consulta SQL
            $query = "INSERT INTO donante (nombre, apellido1, apellido2, nhc, telef1, telef2, dni, ultima_donacion ,recordatorio, observaciones, llamar, cipa, acepto_comunicacion, fecha_nacimiento, citable ) 
                  VALUES (:nombreD, :apellido1D, :apellido2D, :nhcD, :telef1D, :telef2D, :dniD, :ultimaDonacionD, :recordatorioD, :observacionesD, :llamarD, :cipaD, :aceptaComunicacionD, :fechaNacimientoD, :citableD)";
            $statement = $this->pdo->prepare($query);

            // Bind de parámetros
            $statement->bindParam(':nombreD', $nombre);
            $statement->bindParam(':apellido1D', $apellido1);
            $statement->bindParam(':apellido2D', $apellido2);
            $statement->bindParam(':nhcD', $nhc);
            $statement->bindParam(':telef1D', $telefono1);
            $statement->bindParam(':telef2D', $telefono2);
            $statement->bindParam(':dniD', $dni);
            $statement->bindParam(':ultimaDonacionD', $ultimaDonacion);
            $statement->bindParam(':recordatorioD', $recordatorio);
            $statement->bindParam(':observacionesD', $observaciones);
            $statement->bindParam(':llamarD', $llamar);
            $statement->bindParam(':cipaD', $cipa);
            $statement->bindParam(':aceptaComunicacionD', $aceptaComunicacion);
            $statement->bindParam(':fechaNacimientoD', $fechaNacimiento);
            $statement->bindParam(':citableD', $citable);

            // Ejecutar la consulta
            $statement->execute();

            // Obtener el ID del usuario recién insertado
            $id_usuario = $this->pdo->lastInsertId();

            
            require_once("Log.php");
            $log_usuario = new Logs();
            $evento = "Alta donante";
            $log_usuario->crear_log($_SESSION['usuario'],$evento);

            //Insertar log en la base de datos
            
            // Si se inserta correctamente, retornar verdadero
            return true;
        } catch (PDOException $e) {
            // Manejo de errores en caso de fallo en la consulta
            echo "Error: " . $e->getMessage();
            // Retornar falso para indicar que la inserción falló
            return false;
        }
    }

    
    /**********************************Funciones modificadas fin**********************************/



    public function obtenerDonantePorId($id)
    {
        try {
            // Consulta SQL para seleccionar un donante por su ID
            $query = "SELECT * FROM donante WHERE id_donante = :id";

            // Preparar la consulta
            $stmt = $this->pdo->prepare($query);

            // Bind de parámetros
            $stmt->bindParam(':id', $id);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener el resultado de la consulta
            $donante = $stmt->fetch(PDO::FETCH_ASSOC);

            // Devolver el resultado
            return $donante;

        } catch (PDOException $e) {
            // Manejar errores en caso de problemas de conexión o consulta
            return null; // o podrías lanzar una excepción aquí si prefieres
        }
    }

    public function EditarDonante($id_donante, $nombre, $apellido1, $apellido2, $nhc, $telef1, $telef2, $dni, $ultima_donacion, $recordatorio, $observaciones, $llamar, $cipa, $acepto_comunicacion, $fecha_nacimiento, $citable)
    {
        try {
            // Preparar la consulta SQL
            $query = "UPDATE donante SET 
            nombre = :nombre, apellido1 = :apellido1, apellido2 = :apellido2, nhc = :nhc, telef1 = :telef1 , 
            telef2 = :telef2, dni = :dni, ultima_donacion=:ultima_donacion, recordatorio=:recordatorio, 
            observaciones=:observaciones, llamar=:llamar, cipa = :cipa, acepto_comunicacion = :acepto_comunicacion, 
            fecha_nacimiento = :fecha_nacimiento, citable=:citable WHERE id_donante = :id_donante";

            $statement = $this->pdo->prepare($query);

            // Bind de parámetros
            $statement->bindParam(':id_donante', $id_donante);
            $statement->bindParam(':nombre', $nombre);
            $statement->bindParam(':apellido1', $apellido1);
            $statement->bindParam(':apellido2', $apellido2);
            $statement->bindParam(':nhc', $nhc);
            $statement->bindParam(':telef1', $telef1);
            $statement->bindParam(':telef2', $telef2);
            $statement->bindParam(':dni', $dni);
            $statement->bindParam(':ultima_donacion', $ultima_donacion);
            $statement->bindParam(':recordatorio', $recordatorio);
            $statement->bindParam(':observaciones', $observaciones);
            $statement->bindParam(':llamar', $llamar);
            $statement->bindParam(':cipa', $cipa);
            $statement->bindParam(':acepto_comunicacion', $acepto_comunicacion);
            $statement->bindParam(':fecha_nacimiento', $fecha_nacimiento);
            $statement->bindParam(':citable', $citable);

            // Ejecutar la consulta
            $statement->execute();

            // Verificar si se actualizó al menos una fila
            if ($statement->rowCount() > 0) {
                // Si se actualiza correctamente, retornar verdadero
                $id_usuario =$_SESSION['usuario'];
                $operacion = "Modificar donante";
                $ip = $_SERVER['REMOTE_ADDR'];
                $observacion = "Se ha modificado el donante con id: $id_donante";
                $url =  $_SERVER['REQUEST_URI'];
                
                $query_log_alta = "INSERT INTO log (id_usuario, operacion, ip, observacion, url) VALUES (:id_usuario, :operacion, :ip, :observacion, :url)";
                $statement_log_alta = $this->pdo->prepare($query_log_alta);
                $statement_log_alta->bindParam(':id_usuario', $id_usuario);
                $statement_log_alta->bindParam(':operacion', $operacion);
                $statement_log_alta->bindParam(':ip', $ip);
                $statement_log_alta->bindParam(':observacion', $observacion);
                $statement_log_alta->bindParam(':url', $url);
                $statement_log_alta->execute();
                return true;
            } else {
                // Si no se actualiza ninguna fila, retornar falso
                return false;
            }
        } catch (PDOException $e) {
            // Manejo de errores en caso de fallo en la consulta
            echo "Error: " . $e->getMessage();
            // Retornar falso para indicar que la actualización falló
            return false;
        }
    }

    // public function buscarPacienteDinamico($filtros)
    // {
    //     try {
    //         // Construir la consulta SQL con los filtros proporcionados
    //         $query = "SELECT nombre, apellido1, apellido2, nhc, telef1, dni, fecha_nacimiento, acepto_comunicacion FROM donante WHERE ";
    //         $conditions = []; //para almacenar las condiciones de búsqueda
    //         $params = []; //para los parámetros vinculados.

    //         // Agregar condiciones para cada filtro
    //         foreach ($filtros as $campo => $valor) {
    //             $conditions[] = "$campo LIKE :$campo";
    //             $params[":$campo"] = '%' . $valor . '%'; // Agregar los comodines '%' para buscar coincidencias parciales
    //         }

    //         // Unir las condiciones con 'OR'
    //         $query .= implode(' OR ', $conditions);

    //         // Preparar la consulta
    //         $stmt = $this->pdo->prepare($query);

    //         // Vincular los parámetros
    //         foreach ($params as $param => $value) {
    //             $stmt->bindValue($param, $value);
    //         }

    //         // Ejecutar la consulta
    //         $stmt->execute();

    //         // Obtener los resultados de la consulta
    //         $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //         // Construir la tabla HTML para mostrar los resultados
    //         $output = '<table class="table">';
    //         $output .= '<thead><tr><th>Nombre</th><th>Apellidos</th><th>NHC</th><th>Teléfono</th><th>DNI</th><th>Fecha de Nacimiento</th><th>Acepta Comunicaciones</th></tr></thead>';
    //         $output .= '<tbody>';

    //         // Iterar sobre cada resultado y agregarlo a la tabla
    //         foreach ($resultados as $resultado) {
    //             $output .= '<tr>';
    //             $output .= '<td>' . $resultado['nombre'] . '</td>';
    //             $output .= '<td>' . $resultado['apellido1'] . ' ' . $resultado['apellido2'] . '</td>';
    //             $output .= '<td>' . $resultado['nhc'] . '</td>';
    //             $output .= '<td>' . $resultado['telef1'] . '</td>';
    //             $output .= '<td>' . $resultado['dni'] . '</td>';
    //             $output .= '<td>' . $resultado['fecha_nacimiento'] . '</td>';
    //             $output .= '<td>' . $resultado['acepto_comunicacion'] . '</td>';
    //             $output .= '</tr>';
    //         }

    //         // Cerrar la tabla HTML
    //         $output .= '</tbody></table>';

    //         // Devolver la tabla HTML con los resultados
    //         return $output;

    //     } catch (PDOException $e) {
    //         // Manejar errores en caso de problemas de conexión o consulta
    //         return "Error: en la conexión a la base de datos " . $e->getMessage();
    //     }
    // }

    

    

    public function listarDonantesEditar()
    {
        try {
            // Configurar PDO para que lance excepciones en caso de errores
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Consulta SQL para seleccionar todos los donantes
            $query = "SELECT 
            id_donante, 
            nombre, 
            apellido1, 
            apellido2, 
            nhc, 
            telef1, 
            dni, 
            fecha_nacimiento, 
            acepto_comunicacion
            
        FROM 
            donante 
        ORDER BY 
            apellido1 ASC; ";

            // Preparar la consulta
            $stmt = $this->pdo->prepare($query);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener los resultados de la consulta
            $donantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Iniciar la tabla HTML
            $output = '<table class="table table-striped table-hover">';
            $output .= '<thead>
                            <tr>
                                <th scope="col">Nombre</th>
                                <th scope="col">Apellidos</th>
                                <th scope="col">NHC</th>
                                <th scope="col">Teléfono</th>
                                <th scope="col">DNI</th>
                                <th scope="col">Fecha de Nacimiento</th>
                                <th scope="col">Acepta Comunicaciones</th>
                            </tr>
                        </thead>';
            $output .= '<tbody>';

            // Iterar sobre cada donante y crear filas de tabla
            $alternate = true;
            foreach ($donantes as $donante) {
                // Alternar entre clases de colores para filas
                // $row_class = $alternate ? 'table-primary' : 'table-secondary';
                $output .= '<tr class="clickable-row" data-id="' . $donante['id_donante'] . '">';
                // Mostrar solo los últimos cuatro dígitos del ID
                $id_donante = substr($donante['id_donante'], -5);
                $output .= '<td>' . $donante['nombre'] . '</td>';
                // Concatenar apellidos
                $apellidos = $donante['apellido1'] . ' ' . $donante['apellido2'];
                $output .= '<td>' . $apellidos . '</td>';
                $output .= '<td>' . $donante['nhc'] . '</td>';
                $output .= '<td>' . $donante['telef1'] . '</td>';
                $output .= '<td>' . $donante['dni'] . '</td>';
                $output .= '<td>' . $donante['fecha_nacimiento'] . '</td>';
                $output .= '<td>' . $donante['acepto_comunicacion'] . '</td>';
                $output .= '</tr>';
                // Alternar para la próxima fila
                $alternate = !$alternate;
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






    // public function listarDonantes()
    // {
    //     try {
    //         // Configurar PDO para que lance excepciones en caso de errores
    //         $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //         // Consulta SQL para seleccionar todos los donantes
    //         $query = "SELECT * FROM donante";

    //         // Preparar la consulta
    //         $stmt = $this->pdo->prepare($query);

    //         // Ejecutar la consulta
    //         $stmt->execute();

    //         // Obtener los resultados de la consulta
    //         $donantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //         // Iniciar la tabla HTML
    //         $output = '<table class="table">';
    //         $output .= '<thead><tr><th>ID</th><th>Nombre</th><th>Apellidos</th><th>NHC</th><th>Teléfono</th><th>DNI</th><th>Fecha de Nacimiento</th><th>Acepta Comunicaciones</th></tr></thead>';
    //         $output .= '<tbody>';

    //         // Iterar sobre cada donante y crear filas de tabla
    //         $alternate = true;
    //         foreach ($donantes as $donante) {
    //             // Alternar entre clases de colores para filas
    //             $row_class = $alternate ? 'table-primary' : 'table-secondary';
    //             $output .= '<tr class="' . $row_class . ' clickable-row" data-id="' . $donante['id_donante'] . '">';
    //             // Mostrar solo los últimos cuatro dígitos del ID
    //             $id_donante = substr($donante['id_donante'], -5);
    //             $output .= '<td>' . $id_donante . '</td>';
    //             $output .= '<td>' . $donante['nombre'] . '</td>';
    //             // Concatenar apellidos
    //             $apellidos = $donante['apellido1'] . ' ' . $donante['apellido2'];
    //             $output .= '<td>' . $apellidos . '</td>';
    //             $output .= '<td>' . $donante['nhc'] . '</td>';
    //             $output .= '<td>' . $donante['telef1'] . '</td>';
    //             $output .= '<td>' . $donante['dni'] . '</td>';
    //             $output .= '<td>' . $donante['fecha_nacimiento'] . '</td>';
    //             $output .= '<td>' . $donante['acepto_comunicacion'] . '</td>';
    //             $output .= '</tr>';
    //             // Alternar para la próxima fila
    //             $alternate = !$alternate;
    //         }

    //         // Cerrar la tabla HTML
    //         $output .= '</tbody></table>';

    //         // Devolver los resultados
    //         return $output;

    //     } catch (PDOException $e) {
    //         // Manejar errores en caso de problemas de conexión o consulta
    //         return "Error: en la conexión a la base de datos " . $e->getMessage();
    //     }
    // }

    // public function listarPacientesParaSelect($filtro)
    // {
    //     try {
    //         // Preparar la consulta SQL para buscar pacientes por nombre, apellidos, NHC, CIPA y DNI
    //         $query = "SELECT id_donante, telef1,CONCAT(nombre, ' ', apellido1, ' ', apellido2) AS nombre_completo FROM donante WHERE nombre LIKE :filtro OR apellido1 LIKE :filtro OR apellido2 LIKE :filtro OR nhc LIKE :filtro OR cipa LIKE :filtro OR dni LIKE :filtro";

    //         // Preparar la consulta
    //         $stmt = $this->pdo->prepare($query);

    //         // Bind del parámetro
    //         $filtro = '%' . $filtro . '%'; // Agregar los comodines '%' para buscar coincidencias parciales
    //         $stmt->bindParam(':filtro', $filtro, PDO::PARAM_STR);

    //         // Ejecutar la consulta
    //         $stmt->execute();

    //         // Obtener los resultados de la consulta
    //         $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //         // Construir la lista de opciones para seleccionar
    //         $options = '';

    //         // Iterar sobre cada resultado y agregarlo a la lista de opciones
    //         foreach ($resultados as $resultado) {
    //             $options .= '<option value="' . $resultado['id_donante'] . '">' . $resultado['nombre_completo'] . "--" . $resultado['telef1'] . '</option>';
    //         }

    //         // Devolver la lista de opciones
    //         return $options;

    //     } catch (PDOException $e) {
    //         // Manejar errores en caso de problemas de conexión o consulta
    //         return "Error: en la conexión a la base de datos " . $e->getMessage();
    //     }
    // }

  
        public function buscarPaciente($filtro = null)
        {
            try {
                // Construir la consulta SQL de acuerdo a si hay un filtro o no
                if (!empty(trim($filtro))) {
                    // Si hay filtro, construir la consulta con LIKE
                    $query = "SELECT id_donante, nombre, apellido1, apellido2, nhc, telef1, telef2, dni, ultima_donacion, recordatorio, observaciones, llamar, cipa, acepto_comunicacion, fecha_nacimiento, citable 
                            FROM donante 
                            WHERE CAST(id_donante AS CHAR) LIKE :filtro OR nombre LIKE :filtro OR apellido1 LIKE :filtro OR apellido2 LIKE :filtro OR nhc LIKE :filtro OR cipa LIKE :filtro OR dni LIKE :filtro";
                    $filtro = '%' . trim($filtro) . '%'; // Eliminar espacios en blanco y agregar comodines
                } else {
                    // Si no hay filtro, seleccionar todos los registros
                    $query = "SELECT id_donante, nombre, apellido1, apellido2, nhc, telef1, telef2, dni, ultima_donacion, recordatorio, observaciones, llamar, cipa, acepto_comunicacion, fecha_nacimiento, citable 
                            FROM donante";
                }

                // Preparar la consulta
                $stmt = $this->pdo->prepare($query);

                // Si hay un filtro, aplicarlo
                if (!empty(trim($filtro))) {
                    $stmt->bindValue(':filtro', $filtro, PDO::PARAM_STR);
                }

                // Ejecutar la consulta
                $stmt->execute();

                // Obtener los resultados de la consulta
                $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Verificar si realmente se obtuvieron todos los registros
                error_log("Número de donantes encontrados: " . count($resultados));

                // Construir la tabla HTML para mostrar los resultados
                $output = '<table id="mitablaDonanteBuscar" class="table table-striped table-hover table-bordered">';
                $output .= '<thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Nombre</th>
                                    <th>Primer apellido</th>
                                    <th>Segundo apellido</th>
                                    <th>NHC</th>
                                    <th>Tel. 1</th>
                                    <th>Tel. 2</th>
                                    <th>DNI</th>
                                    <th>Ultima donación</th>
                                    <th>Recordatorio</th>
                                    <th>Observaciones</th>
                                    <th>Llamar</th>
                                    <th>CIPA</th>
                                    <th>Acepta comunicación</th>
                                    <th>Fecha de nacimiento</th>
                                    <th>Citable</th>
                                </tr>
                            </thead>';
                $output .= '<tbody>';

                // Iterar sobre cada resultado y agregarlo a la tabla
                foreach ($resultados as $resultado) {
                    $output .= '<tr class="clickable-row" data-id="' . $resultado['id_donante'] . '">';
                    $output .= '<td>' . $resultado['id_donante'] . '</td>';
                    $output .= '<td>' . $resultado['nombre'] . '</td>';
                    $output .= '<td>' . $resultado['apellido1'] . '</td>';
                    $output .= '<td>' . $resultado['apellido2'] . '</td>';
                    $output .= '<td>' . $resultado['nhc'] . '</td>';
                    $output .= '<td>' . $resultado['telef1'] . '</td>';
                    $output .= '<td>' . $resultado['telef2'] . '</td>';
                    $output .= '<td>' . $resultado['dni'] . '</td>';
                    $output .= '<td>' . $resultado['ultima_donacion'] . '</td>';
                    $output .= '<td>' . $resultado['recordatorio'] . '</td>';
                    $output .= '<td>' . $resultado['observaciones'] . '</td>';
                    $output .= '<td>' . $resultado['llamar'] . '</td>';
                    $output .= '<td>' . $resultado['cipa'] . '</td>';
                    $output .= '<td>' . $resultado['acepto_comunicacion'] . '</td>';
                    $output .= '<td>' . $resultado['fecha_nacimiento'] . '</td>';
                    $output .= '<td>' . $resultado['citable'] . '</td>';
                    $output .= '</tr>';
                }

                // Cerrar la tabla HTML
                $output .= '</tbody></table>';

                // Devolver la tabla HTML con los resultados
                return $output;

            } catch (PDOException $e) {
                // Manejar errores en caso de problemas de conexión o consulta
                return "Error: en la conexión a la base de datos " . $e->getMessage();
            }
        }
        public function cerrarconexion(){
            $this->pdo = null;
        }
}