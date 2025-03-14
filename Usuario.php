<?php

class Usuario
{
    private $pdo;

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
        $this->pdo = (new Conexion())->getConexion();
    }
    public function editarUsuario($id_usuario, $nombre, $apellido1, $apellido2, $id_rol){
        try {
            // $this->pdo->beginTransaction(); //se usa para modificaciones atomicas en la base de datos, todo o nada.
            //preparar la consulta
            $query_editarUsuario = "UPDATE usuario 
                                    SET id_usuario =:id_usuario, nombre =:nombre, apellido1 = :apellido1, apellido2= :apellido2, id_rol= :id_rol 
                                    WHERE id_usuario =:id_usuario ";
            //prepararla delcaracion
            $stmt_editarUsuario = $this->pdo->prepare($query_editarUsuario);
            $stmt_editarUsuario->bindParam(":id_usuario", $id_usuario);
            $stmt_editarUsuario->bindParam(":nombre", $nombre);
            $stmt_editarUsuario->bindParam(":apellido1", $apellido1);
            $stmt_editarUsuario->bindParam(":apellido2", $apellido2);
            $stmt_editarUsuario->bindParam(":id_rol", $id_rol);
            $stmt_editarUsuario->execute();
            return true;
        } catch (PDOException $e) {
            $error_editar= "Error en editar usuario". $e->getMessage();
            return false;
        }
    }


    public function cambiarRolUsuario($id_usuario, $rol)
    {
        // Validar el rol recibido
        switch ($rol) {
            case 'Administrador':
                $id_rol = 1;
                break;
            case 'Super':
                $id_rol = 2;
                break;
            case 'Usuario':
                $id_rol = 3;
                break;
            default:
                // Si el rol no es válido, devolver falso
                $id_rol = 0;
        }

        try {
            // Preparar la consulta SQL para actualizar el id_rol del usuario
            $query = "UPDATE usuario SET id_rol = :id_rol WHERE id_usuario = :id_usuario";

            // Preparar la declaración
            $statement = $this->pdo->prepare($query);

            // Bind de parámetros
            $statement->bindParam(':id_rol', $id_rol);
            $statement->bindParam(':id_usuario', $id_usuario);

            // Ejecutar la consulta
            $statement->execute();

            // Devolver true para indicar que se actualizó correctamente
            return true;

        } catch (PDOException $e) {
            // Manejar errores en caso de problemas de conexión o consulta
            echo "Error: en la conexión a la base de datos " . $e->getMessage();
            return false; // Devolver false para indicar que ocurrió un error
        }
    }


    public function listarUsuariosSolicitantes()
    {
        try {
            // Configurar PDO para que lance excepciones en caso de errores
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Consulta SQL para seleccionar todos los usuarios solicitantes
            $query = "SELECT * FROM usuario WHERE id_rol IS NULL OR id_rol = ''";

            // Preparar la consulta
            $stmt = $this->pdo->prepare($query);

            // Ejecutar la consulta
            $stmt->execute();

            // Obtener los resultados de la consulta
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Iniciar la tabla HTML
            $output = '<table class="table">';
            $output .= '<thead><tr><th>ID</th><th>Nombre</th><th>Apellidos</th></tr></thead>';
            $output .= '<tbody>';

            // Iterar sobre cada usuario y crear filas de tabla
            $alternate = true;
            foreach ($usuarios as $usuario) {
                // Alternar entre clases de colores para filas
                $row_class = $alternate ? 'table-primary' : 'table-secondary';
                $output .= '<tr class="' . $row_class . ' clickable-row" data-id="' . $usuario['id_usuario'] . '">';
                $output .= '<td>' . $usuario['id_usuario'] . '</td>';
                $output .= '<td>' . $usuario['nombre'] . '</td>';
                // Concatenar apellidos
                $apellidos = $usuario['apellido1'] . ' ' . $usuario['apellido2'];
                $output .= '<td>' . $apellidos . '</td>';
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
            echo "Error: en la conexión a la base de datos " . $e->getMessage();
            return false; // Devuelve falso para indicar que ocurrió un error
        }
    }

    public function obtenerUsuarioPorId($id_usuario)
    {
        try {
            // Preparar la consulta SQL
            $query = "SELECT * FROM usuario WHERE id_usuario = :id_usuario";

            // Preparar la declaración
            $statement = $this->pdo->prepare($query);

            // Bind de parámetros
            $statement->bindParam(':id_usuario', $id_usuario);

            // Ejecutar la consulta
            $statement->execute();

            // Obtener los datos del usuario
            $usuario = $statement->fetch(PDO::FETCH_ASSOC);

            // Devolver los datos del usuario
            return $usuario;

        } catch (PDOException $e) {
            // Manejar errores en caso de problemas de conexión o consulta
            echo "Error: en la conexión a la base de datos " . $e->getMessage();
            return false; // Devuelve falso para indicar que ocurrió un error
        }
    }


    // public function listarUsuarios()
    // {
    //     try {
    //         // Configurar PDO para que lance excepciones en caso de errores
    //         $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //         // Consulta SQL para seleccionar todos los usuarios con sus roles
    //         $query = "SELECT usuario.*, rol.descripcion_rol AS descripcion_rol 
    //               FROM usuario 
    //               LEFT JOIN rol ON usuario.id_rol = rol.id_rol";

    //         // Preparar la consulta
    //         $stmt = $this->pdo->prepare($query);

    //         // Ejecutar la consulta
    //         $stmt->execute();

    //         // Obtener los resultados de la consulta
    //         $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    //         // Iniciar la tabla HTML
    //         $output = '<table class="table">';
    //         $output .= '<thead><tr><th>ID</th><th>Nombre</th><th>Apellidos</th><th>Rol</th></tr></thead>';
    //         $output .= '<tbody>';

    //         // Iterar sobre cada usuario y crear filas de tabla
    //         $alternate = true;
    //         foreach ($usuarios as $usuario) {
    //             // Alternar entre clases de colores para filas
    //             $row_class = $alternate ? 'table-primary' : 'table-secondary';
    //             $output .= '<tr class="' . $row_class . ' clickable-row" data-id="' . $usuario['id_usuario'] . '">';
    //             // Mostrar solo los últimos cuatro dígitos del ID
    //             $id_usuario = substr($usuario['id_usuario'], -5);
    //             $output .= '<td>' . $id_usuario . '</td>';
    //             $output .= '<td><a href="perfil.php?id=' . $usuario['id_usuario'] . '">' . $usuario['nombre'] . '</a></td>';
    //             // Concatenar apellidos
    //             $apellidos = $usuario['apellido1'] . ' ' . $usuario['apellido2'];
    //             $output .= '<td>' . $apellidos . '</td>';
    //             // Mostrar el nombre del rol en lugar del ID
    //             $output .= '<td>' . $usuario['descripcion_rol'] . '</td>';
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



    public function crearUsuario($id_usuario, $nombre, $apellido1, $apellido2, $id_rol=null)
    {
        //El id del usuario seria el DNI
        // Limpiar los datos para evitar inyección de SQL
        $dni = htmlspecialchars($id_usuario);
        $nomb = htmlspecialchars($nombre);
        $ape1 = htmlspecialchars($apellido1);
        $ape2 = htmlspecialchars($apellido2);
        $id_rol = htmlspecialchars($id_rol);

        // Preparar la consulta SQL
        $query = "INSERT INTO usuario (id_usuario, nombre, apellido1, apellido2, id_rol)
              VALUES (:id_usuario, :nombre, :apellido1, :apellido2, :id_rol)";

        try {
            // Obtener la conexión a la base de datos (reemplaza 'conn' con tu variable de conexión)
            $stmt = $this->pdo->prepare($query);

            // Asignar los valores a los parámetros
            $stmt->bindParam(':id_usuario', $dni);
            $stmt->bindParam(':nombre', $nomb);
            $stmt->bindParam(':apellido1', $ape1);
            $stmt->bindParam(':apellido2', $ape2);
            $stmt->bindParam(':id_rol', $id_rol);

            // Ejecutar la consulta
            $stmt->execute();

            // Opcional: devolver el ID del nuevo usuario insertado
            // return $this->pdo->lastInsertId();
            return true;
        } catch (PDOException $e) {
            // Manejar la excepción (puedes registrarla, mostrar un mensaje de error, etc.)
            echo "Error al crear usuario: " . $e->getMessage();
            // Opcional: devolver false u otro valor para indicar que hubo un error
            return false;
        }
    }


public function buscarUsuario($filtro)
{
    try {
        // // Preparar la consulta SQL con o sin filtro
        // if ($filtro !== null) {
        //     $query = "SELECT id_usuario, nombre, apellido1, apellido2, id_rol FROM usuario WHERE id_usuario LIKE :filtro OR nombre LIKE :filtro OR apellido1 LIKE :filtro OR apellido2 LIKE :filtro OR id_rol LIKE :filtro";
        // } else {
        //     $query = "SELECT id_usuario, nombre, apellido1, apellido2, id_rol FROM usuario";
        // }



        // Preparar la consulta SQL para buscar al paciente por nombre, apellidos, NHC, CIPA y DNI
        $query = "SELECT id_usuario, nombre, apellido1, apellido2, id_rol FROM usuario WHERE id_usuario LIKE :filtro OR nombre LIKE :filtro OR apellido1 LIKE :filtro OR apellido2 LIKE :filtro OR id_rol LIKE :filtro";

        // Preparar la consulta
        $stmt = $this->pdo->prepare($query);

        // Bind the parameter
        $filtro = '%' . $filtro . '%'; // Agregar los comodines '%' para buscar coincidencias parciales
        $stmt->bindParam(':filtro', $filtro, PDO::PARAM_STR);

        // Ejecutar la consulta
        $stmt->execute();

        // Obtener los resultados de la consulta
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Construir la tabla HTML para mostrar los resultados
        $output = '<table class="table table-striped table-hover table-bordered">';
        $output .= '<thead>
                        <tr>
                            <th>Id usuario</th>
                            <th>Nombre</th>
                            <th>Primer apellido</th>
                            <th>Segundo apellido</th>
                            <th>Id rol</th>
                        </tr>
                    </thead>';
        $output .= '<tbody>';

        // Iterar sobre cada resultado y agregarlo a la tabla
        foreach ($resultados as $resultado) {
            $output .= '<tr class="clickable-row" data-id="' .$resultado['id_usuario'] . '">';
            $output .= '<td>' . $resultado['id_usuario'] . '</td>';
            $output .= '<td>' . $resultado['nombre'] . '</td>';
            $output .= '<td>' . $resultado['apellido1'] . '</td>';
            $output .= '<td>' . $resultado['apellido2'] . '</td>';
            $output .= '<td>' . $resultado['id_rol'] . '</td>';
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


}