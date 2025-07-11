<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Libro
{
    //Implementamos nuestro constructor
    public function __construct()
    {
    }

    //Implementamos un método para insertar registros
    public function insertar($cod_libro, $titulo, $genero, $cod_autor)
    {
        try {
            $sql_check = "SELECT titulo FROM libro where titulo = '$titulo' OR codigo = '$cod_libro'";
            $res_check = ejecutarConsulta($sql_check);

            if ($res_check->num_rows > 0) {
                return 1062;
            } else {
                $sql = "INSERT INTO libro(codigo, titulo, genero, codigo_autor)
                        VALUES ('$cod_libro','$titulo','$genero', '$cod_autor')";
                return ejecutarConsulta($sql);
            }
        } catch (Exception $e) {
            return $e->getCode(); // Devuelve el código de error de la excepción
        }
    }

    //Implementamos un método para editar registros
    public function editar($cod_libro, $titulo, $genero, $cod_autor)
    {
        $sql = "UPDATE libro SET titulo='$titulo', genero='$genero', codigo_autor='$cod_autor' WHERE codigo='$cod_libro'";
        return ejecutarConsulta($sql);
    }

    //Implementamos un método para eliminar registros
    public function eliminar($codigo)
    {
        $sql = "DELETE FROM libro WHERE codigo='$codigo'";
        return ejecutarConsulta($sql);
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($cod_libro)
    {
        $sql = "SELECT * FROM libro WHERE codigo='$cod_libro'";
        return ejecutarConsultaSimpleFila($sql);
    }


   
    //Implementar un método para mostrar los datos de un registro a modificar
    public function seleccionar($codigo)
    {
        $sql = "SELECT l.codigo, l.titulo, l.genero, a.nombre as autor, a.codigo as cod_autor FROM libro l join autor a on l.codigo_autor = a.codigo WHERE l.codigo='$codigo'";
        return ejecutarConsultaSimpleFila($sql);
    }


    //Implementar un método para listar los registros
    public function listar()
    {
        $sql = "SELECT l.codigo, l.titulo, l.genero, a.nombre as autor FROM libro l join autor a on l.codigo_autor = a.codigo";
        return ejecutarConsulta($sql);
    }

    // Busca un autor por código
    public function buscar_autor($cod_autor)
    {
        $sql = "SELECT * FROM autor WHERE codigo = '$cod_autor'";
  
        return ejecutarConsulta($sql);
    }

    // Listar autores
    public function listar_autores()
    {
        $sql = "SELECT * FROM autor";
        return ejecutarConsulta($sql);
    }
}
