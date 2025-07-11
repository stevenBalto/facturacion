<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Estudiante
{
    public function __construct()
    {
    }

    // Busca un estudiante por cédula
    public function buscar($cedula)
    {
        $sql = "SELECT * FROM estudiante WHERE cedula='$cedula'";
        return ejecutarConsultaSimpleFila($sql);
    }

    // Lista todos los estudiantes
    public function listar()
    {
        $sql = "SELECT * FROM estudiante";
        return ejecutarConsulta($sql);
    }

    // Busca un estudiante por Id
    public function mostrar($cedula)
    {
        $sql = "SELECT * FROM estudiante WHERE cedula='$cedula'";
        return ejecutarConsultaSimpleFila($sql);
    }
}
