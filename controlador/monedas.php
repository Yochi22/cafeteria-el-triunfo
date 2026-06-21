<?php

if (!is_file("modelo/" . $pagina . ".php")) {
    echo "Falta Definir la Clase " . $pagina;
    exit;
}

require_once("modelo/" . $pagina . ".php");

if (is_file("vista/" . $pagina . ".php")) {
    $o = new monedas();

    if (!empty($_POST)) {
        $accion = $_POST['accion'];

        switch ($accion) {
            case 'incluir':
                $o->set_codigoMon($_POST['codigoMon']);
                $o->set_nombreMon($_POST['nombreMon']);
                $o->set_simboloMon($_POST['simboloMon']);
                $o->set_tasaMon($_POST['tasaMon']);
                echo json_encode($o->incluir());
                break;

            case 'consultar':
                echo json_encode($o->consultar());
                break;

            case 'modificar':
                $o->set_codigoOriginal($_POST['codigoOriginal']);
                $o->set_codigoMon($_POST['codigoMon']);
                $o->set_nombreMon($_POST['nombreMon']);
                $o->set_simboloMon($_POST['simboloMon']);
                $o->set_tasaMon($_POST['tasaMon']);
                echo json_encode($o->modificar());
                break;

            case 'eliminar':
                $o->set_codigoMon($_POST['codigoMon']);
                echo json_encode($o->eliminar());
                break;

            case 'buscar':
                $valor = isset($_POST['valorBusqueda']) ? $_POST['valorBusqueda'] : '';
                echo json_encode($o->buscar($valor));
                break;
        }

        exit;
    }

    require_once("vista/" . $pagina . ".php");
} else {
    echo "PÁGINA EN CONSTRUCCIÓN";
}

?>