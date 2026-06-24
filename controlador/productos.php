<?php

if (!is_file("modelo/" . $pagina . ".php")) {
    echo "Falta definir la clase " . $pagina;
    exit;
}

require_once("modelo/" . $pagina . ".php");

if (is_file("vista/" . $pagina . ".php")) {
    $o = new productos();

    if (!empty($_POST)) {
        $accion = $_POST['accion'];

        switch ($accion) {
            case 'listar_categorias':
                echo json_encode($o->listar_categorias());
                break;

            case 'listar_productos':
                $o->set_idCategoria($_POST['idCategoria']);
                echo json_encode($o->listar_productos());
                break;

            case 'incluir':
                $o->set_codigoProd($_POST['codigoProd']);
                $o->set_nombreProd($_POST['nombreProd']);
                $o->set_precioProd($_POST['precioProd']);
                $o->set_descProd($_POST['descProd']);
                $o->set_fotoProd($_POST['fotoProd']);
                $o->set_idCategoria($_POST['idCategoria']);
                echo json_encode($o->incluir());
                break;

            case 'consultar':
                $o->set_codigoProd($_POST['codigoProd']);
                echo json_encode($o->consultar());
                break;

            case 'modificar':
                $o->set_codigoOrig($_POST['codigoOrig']);
                $o->set_codigoProd($_POST['codigoProd']);
                $o->set_nombreProd($_POST['nombreProd']);
                $o->set_precioProd($_POST['precioProd']);
                $o->set_descProd($_POST['descProd']);
                $o->set_fotoProd($_POST['fotoProd']);
                $o->set_idCategoria($_POST['idCategoria']);
                echo json_encode($o->modificar());
                break;

            case 'eliminar':
                $o->set_codigoProd($_POST['codigoProd']);
                echo json_encode($o->eliminar());
                break;

            case 'cargar_select_categorias':
                echo json_encode($o->cargar_select_categorias());
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