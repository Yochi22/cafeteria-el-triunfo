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
            case 'consultar_horario':
                echo json_encode($o->consultarHorario());
                break;
        }
        exit;
    }
    require_once("vista/" . $pagina . ".php");
} else {
    echo "PÁGINA EN CONSTRUCCIÓN";
}

?>