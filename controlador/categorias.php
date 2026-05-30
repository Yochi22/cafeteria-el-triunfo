<?php

require_once("modelo/" . $pagina . ".php");

if (!is_file("modelo/" . $pagina . ".php")) {
    echo "Falta Definir la Clase " . $pagina;
    exit;
}

if (is_file("vista/" . $pagina . ".php")) {
    $o = new categorias();

    if (!empty($_POST)) {
        $accion = $_POST['accion'];

        switch ($accion) {
            case 'consultar':
                echo $o->consultar();
                break;
                
            case 'incluir':
                $o->set_nombre($_POST['nombre']);
                $o->set_descripcion($_POST['descripcion']);
                $o->set_foto($_POST['foto']);
                echo $o->incluir();
                break;
                
            case 'modificar':
                $o->set_idCategoria($_POST['idCategoria']);
                $o->set_nombre($_POST['nombre']);
                $o->set_descripcion($_POST['descripcion']);
                $o->set_foto($_POST['foto']);
                echo $o->modificar();
                break;
                
            case 'eliminar':
                $o->set_idCategoria($_POST['idCategoria']);
                echo $o->eliminar();
                break;
                
            case 'consultar_id':
                $o->set_idCategoria($_POST['idCategoria']);
                echo $o->consultar_id();
                break;
        }
        exit;
    }
    
    require_once("vista/" . $pagina . ".php");
} else {
    echo "Página en Construcción";
}
?>