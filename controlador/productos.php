<?php

if (!is_file("modelo/" . $pagina . ".php")) {
    echo "Falta Definir la Clase " . $pagina;
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
                $o->set_nombre($_POST['nombre']);
                $o->set_precio($_POST['precio']);
                $o->set_descripcion($_POST['descripcion']);
                $o->set_foto($_POST['foto']);
                $o->set_idCategoria($_POST['idCategoria']); 
                echo json_encode($o->incluir());
                break;

            case 'modificar':
                $o->set_idProducto($_POST['idProducto']);
                $o->set_nombre($_POST['nombre']);
                $o->set_precio($_POST['precio']);
                $o->set_descripcion($_POST['descripcion']);
                $o->set_foto($_POST['foto']);
                $o->set_idCategoria($_POST['idCategoria']);
                echo json_encode($o->modificar());
                break;

            case 'eliminar':
                $o->set_idProducto($_POST['idProducto']);
                echo json_encode($o->eliminar());
                break;

            case 'consultar_id':
                $o->set_idProducto($_POST['idProducto']);
                echo json_encode($o->consultar_id());
                break;

            case 'cargar_select_categorias':
                echo json_encode($o->cargar_select_categorias());
                break;
        }

        exit;
    }
    
    require_once("vista/" . $pagina . ".php");
} else {
    echo "PÁGINA EN CONSTRUCCIÓN";
}
?>