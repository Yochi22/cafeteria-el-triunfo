<?php

if (!is_file("modelo/" . $pagina . ".php")) {
    echo "Falta definir la clase " . $pagina;
    exit;
}

require_once("modelo/" . $pagina . ".php");

if (is_file("vista/" . $pagina . ".php")) {
    $o = new clientes();
    if (!empty($_POST)) {
        $accion = $_POST['accion'];

        switch ($accion) {

            case 'consultar':
                echo json_encode($o->consultar());
                break;

            case 'eliminar':
                $o->set_cedulaCli(trim($_POST['cedulaCli']));
                echo json_encode($o->eliminar());
                break;

            case 'incluir':
                $o->set_cedulaCli($_POST['cedulaCli']);
                $o->set_nombreCli($_POST['nombreCli']);
                $o->set_apellidoCli($_POST['apellidoCli']);
                $o->set_tlfCli($_POST['tlfCli']);
                echo json_encode($o->incluir());
                break;

            case 'modificar':
                $o->set_cedulaCli($_POST['cedulaCli']);
                $o->set_nombreCli($_POST['nombreCli']);
                $o->set_apellidoCli($_POST['apellidoCli']);
                $o->set_tlfCli($_POST['tlfCli']);
                echo json_encode($o->modificar());
                break;
                
            case 'buscar':
                $valor = isset($_POST['valorBusqueda']) ? $_POST['valorBusqueda'] : '';
                echo json_encode($o->buscar($valor));
                break;
        }
        
        exit();
    }

    require_once("vista/" . $pagina . ".php");
} else {
    echo "PÁGINA EN CONSTRUCCIÓN";
}

?>