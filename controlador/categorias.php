<?php

if (!is_file("modelo/" . $pagina . ".php")) {
    echo "Falta definir la clase " . $pagina;
    exit;
}

require_once("modelo/" . $pagina . ".php");

if (is_file("vista/" . $pagina . ".php")) {
    $o = new categorias();

    if (!empty($_POST)) {
        $accion = $_POST['accion'];

        switch ($accion) {
            case 'incluir':
                $o->set_codigoCat($_POST['codigoCat']);
                $o->set_nombreCat($_POST['nombreCat']);
                $o->set_descCat($_POST['descCat']);
                $o->set_fotoCat($_POST['fotoCat']);
                echo json_encode($o->incluir());
                break;

            case 'consultar':
                echo json_encode($o->consultar());
                break;

            case 'modificar':
                $o->set_codigoOriginal($_POST['codigoOriginal']);
                $o->set_codigoCat($_POST['codigoCat']);
                $o->set_nombreCat($_POST['nombreCat']);
                $o->set_descCat($_POST['descCat']);
                $o->set_fotoCat($_POST['fotoCat']);
                echo json_encode($o->modificar());
                break;

            case 'eliminar':
                $o->set_codigoCat($_POST['codigoCat']);
                echo json_encode($o->eliminar());
                break;

            case 'buscar':
                $valor = trim($_POST['valorBusqueda'] ?? '');
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