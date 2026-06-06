<?php
require_once('modelo/metodos_pago.php');

if (!is_file("modelo/" . $pagina . ".php")) {
    echo "Falta Definir la Clase" . $pagina;
    exit;
}

if (is_file("vista/" . $pagina . ".php")) {
    $o = new metodospago();

    if (!empty($_POST)) {
        $accion = $_POST['accion'];

        switch ($accion) {
            case 'consultar':
                echo json_encode($o->consultar());
                break;
                
            case 'incluir':
                $o->set_nombreBanco($_POST['nombreBanco']);
                $o->set_cedulaTitular($_POST['cedulaTitular']);
                $o->set_telefono($_POST['telefono']);
                $o->set_cuenta($_POST['cuenta']);
                echo json_encode($o->incluir());
                break;
                
            case 'modificar':
                $o->set_idMetodo($_POST['idMetodo']);
                $o->set_nombreBanco($_POST['nombreBanco']);
                $o->set_cedulaTitular($_POST['cedulaTitular']);
                $o->set_telefono($_POST['telefono']);
                $o->set_cuenta($_POST['cuenta']);
                echo json_encode($o->modificar());
                break;
                
            case 'eliminar':
                $o->set_idMetodo($_POST['idMetodo']);
                echo json_encode($o->eliminar());
                break;

            case 'consultar_id':
                $o->set_idMetodo($_POST['idMetodo']);
                echo json_encode($o->consultar_id());
                break;
        }
        exit;
    }
    
    require_once("vista/" . $pagina . ".php");
} else {
    echo "Página en Construcción";
}
?>