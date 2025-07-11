<?php

require_once "../modelos/Autor.php";

$autor = new Autor();

$codigo = isset($_POST["codigo"]) ? $_POST["codigo"] : "";

switch ($_GET["op"]) {
    case 'mostrar':
        $rspta = $autor->mostrar($codigo);
        //Codificar el resultado utilizando json
        echo json_encode($rspta);
        break;
}
