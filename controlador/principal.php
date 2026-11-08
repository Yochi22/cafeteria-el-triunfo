<?php

if (!is_file("modelo/" . $pagina . ".php")) {
    echo "Falta definir la clase " . $pagina;
    exit;
}

require_once("modelo/" . $pagina . ".php");

if (is_file("vista/" . $pagina . ".php")) {
    $o = new principal();

    if (!empty($_POST['accion'])) {
        $accion = $_POST['accion'];

        switch ($accion) {
            case 'consultar_horarios':
                echo json_encode($o->consultarHorarios());
                break;
            case 'modificar_horario':
                echo json_encode($o->modificarHorario($_POST['idHorario'], $_POST['horaApertura'], $_POST['horaCierre']));
                break;
            case 'consultar_precios':
                echo json_encode($o->consultarPreciosDelivery());
                break;
            case 'modificar_precio':
                echo json_encode($o->modificarPrecioDelivery($_POST['zona'], $_POST['precio']));
                break;
        }

        exit;
    }

    require_once("vista/" . $pagina . ".php");
} else {
    echo "PÁGINA EN CONSTRUCCIÓN";
}

?>