<?php
require "../config/Conexion.php";

class Producto
{

    public function __construct()
    {
    }

    public function insertar($id, $nombre, $precio, $idCategoria)
    {
        try {
            $sql_check = "SELECT nombre FROM producto WHERE nombre = '$nombre' OR id = '$id'";
            $res_check = ejecutarConsulta($sql_check);

            if ($res_check->num_rows > 0) {
                return 1062;
            } else {
                $sql = "INSERT INTO producto(id, nombre, precio, idCategoria)
                        VALUES ('$id', '$nombre', '$precio', '$idCategoria')";
                return ejecutarConsulta($sql);
            }
        } catch (Exception $e) {
            return $e->getCode();
        }
    }


    public function editar($id, $nombre, $precio, $idCategoria)
    {
        $sql = "UPDATE producto SET nombre='$nombre', precio='$precio', idCategoria='$idCategoria' WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    public function eliminar($id)
    {
        $sql = "DELETE FROM producto WHERE id='$id'";
        return ejecutarConsulta($sql);
    }

    public function mostrar($id)
    {
        $sql = "SELECT * FROM producto WHERE id='$id'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function seleccionar($id)
    {
        $sql = "SELECT p.id, p.nombre, p.precio, c.nombre as categoria, c.id as idCategoria 
                FROM producto p 
                JOIN categoria c ON p.idCategoria = c.id 
                WHERE p.id = '$id'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function listar()
    {
        $sql = "SELECT p.id, p.nombre, p.precio, c.nombre as categoria 
                FROM producto p 
                JOIN categoria c ON p.idCategoria = c.id";
        return ejecutarConsulta($sql);
    }

    public function buscar_categoria($idCategoria)
    {
        $sql = "SELECT * FROM categoria WHERE id = '$idCategoria'";
        return ejecutarConsulta($sql);
    }


    public function listar_categorias()
    {
        $sql = "SELECT * FROM categoria";
        return ejecutarConsulta($sql);
    }

    public function consultar($campo, $dato)
    {
        // En tu BD no hay 'codigo', usamos 'id' como código
        $permitidos = ['id', 'nombre'];
        if (!in_array($campo, $permitidos, true)) {
            $sql = "SELECT p.id, p.nombre, p.precio, p.idCategoria, c.nombre AS categoria
                    FROM producto p LEFT JOIN categoria c ON c.id = p.idCategoria
                    WHERE 1=0";
            return ejecutarConsulta($sql);
        }

        $base = "SELECT p.id, p.nombre, p.precio, p.idCategoria, c.nombre AS categoria
                 FROM producto p LEFT JOIN categoria c ON c.id = p.idCategoria ";

        if ($campo === 'nombre') {
            $sql = $base . "WHERE p.nombre LIKE '%$dato%'";
        } else { // id
            $sql = $base . "WHERE p.id = '$dato'";
        }
        return ejecutarConsulta($sql);
    }
    public function productoMasCaro()
    {
        $sql = "SELECT p.id, p.nombre, p.precio, p.idCategoria,
                   c.nombre AS categoria
            FROM producto p
            LEFT JOIN categoria c ON c.id = p.idCategoria
            ORDER BY p.precio DESC
            LIMIT 1";
        return ejecutarConsultaSimpleFila($sql);
    }

}
