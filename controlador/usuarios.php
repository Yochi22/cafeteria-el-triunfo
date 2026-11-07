<?php

if (!is_file("modelo/" . $pagina . ".php")) {
    echo "Falta definir la clase " . $pagina;
    exit;
}

require_once("modelo/" . $pagina . ".php");

if (is_file("vista/" . $pagina . ".php")) {
    $o = new usuarios();

    if (!empty($_POST)) {
        $accion = $_POST['accion'];

        switch ($accion) {
            case 'incluir':
                $o->set_idPersonal($_POST['idPersonal']);
                $o->set_idRol($_POST['idRol']);
                $o->set_username($_POST['username']);
                $o->set_password($_POST['password']);
                echo json_encode($o->incluir());
                break;

            case 'consultar':
                echo json_encode($o->consultar());
                break;

            case 'consultarPersonal':
                echo json_encode($o->consultarPersonal());
                break;

            case 'consultarRoles':
                echo json_encode($o->consultarRoles());
                break;

            case 'modificar':
                $o->set_idUsuario($_POST['idUsuario']);
                $o->set_idPersonal($_POST['idPersonal']);
                $o->set_idRol($_POST['idRol']);
                $o->set_username($_POST['username']);
                $o->set_password($_POST['password']);
                echo json_encode($o->modificar());
                break;

            case 'eliminar':
                $o->set_idUsuario($_POST['idUsuario']);
                echo json_encode($o->eliminar());
                break;

            case 'buscar':
                $valor = trim($_POST['valorBusqueda'] ?? '');
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