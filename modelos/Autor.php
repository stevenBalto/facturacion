<?php
//Incluímos inicialmente la conexión a la base de datos
require "../config/Conexion.php";

class Autor
{
    //Implementamos nuestro constructor
    public function __construct()
    {
    }

    //Implementar un método para mostrar los datos de un registro a modificar
    public function mostrar($codigo)
    {
        $sql = "SELECT * FROM autor WHERE codigo='$codigo'"; 
        return ejecutarConsultaSimpleFila($sql);
    }
}
