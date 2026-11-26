<?php

if (!is_file("modelo/" . $pagina . ".php")) {
    echo "Falta definir la clase " . $pagina;
    exit;
}

require_once("modelo/" . $pagina . ".php");

if (is_file("vista/" . $pagina . ".php")) {
    $o = new bienvenida();

    if (!empty($_POST['accion'])) {
        $accion = $_POST['accion'];

        switch ($accion) {
            case 'consultarHorarios':
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($o->consultarHorarios());
                break;
        }

        exit;
    }

    require_once("vista/" . $pagina . ".php");
} else {
    echo "PÁGINA EN CONSTRUCCIÓN";
}

?>