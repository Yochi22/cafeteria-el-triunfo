<?php

if (!is_file("modelo/" . $pagina . ".php")) {
    echo "Falta Definir la Clase " . $pagina;
    exit;
}

require_once("modelo/" . $pagina . ".php");

if (is_file("vista/" . $pagina . ".php")) {

    $o = new categorias();

    if (!empty($_POST)) {
        $accion = $_POST['accion'];

        switch ($accion) {
            case 'consultar':
                echo json_encode($o->consultar());
                break;

            case 'incluir':
                $o->set_nombre($_POST['nombre']);
                $o->set_descripcion($_POST['descripcion']);
                $o->set_foto($_POST['foto']);
                echo json_encode($o->incluir());
                break;

            case 'modificar':
                $o->set_idCategoria($_POST['idCategoria']);
                $o->set_nombre($_POST['nombre']);
                $o->set_descripcion($_POST['descripcion']);
                $o->set_foto($_POST['foto']);
                echo json_encode($o->modificar());
                break;

            case 'eliminar':
                $o->set_idCategoria($_POST['idCategoria']);
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