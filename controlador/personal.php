<?php
if (!is_file("modelo/".$pagina.".php")){
    echo "Falta definir la clase ".$pagina;
    exit;
}
require_once("modelo/".$pagina.".php");

if(is_file("vista/".$pagina.".php")){
    $o = new personal();
    if(!empty($_POST)){
        $accion = $_POST['accion'];

        switch($accion){

            case 'consultar':
                echo json_encode($o->consultar());
                break;

            case 'eliminar':
                $o->set_cedulaPer($_POST['cedulaPer']);
                echo json_encode($o->eliminar());
                break;

            case 'incluir':
                $o->set_cedulaPer($_POST['cedulaPer']);
                $o->set_nombre($_POST['nombre']);
                $o->set_apellido($_POST['apellido']);
                $o->set_password($_POST['password']);
                $o->set_rol($_POST['rol']);
                echo json_encode($o->incluir());
                break;

            case 'modificar':
                $o->set_cedulaPer($_POST['cedulaPer']);
                $o->set_nombre($_POST['nombre']);
                $o->set_apellido($_POST['apellido']);
                $o->set_password($_POST['password']);
                $o->set_rol($_POST['rol']);
                echo json_encode($o->modificar());
                break;

            case 'buscar':
                $valor = isset($_POST['valorBusqueda']) ? $_POST['valorBusqueda'] : '';
                echo json_encode($o->buscar($valor));
                break;
        }
        exit();
    }
    require_once("vista/".$pagina.".php"); 
} else {
    echo "pagina en construccion";
}
?>