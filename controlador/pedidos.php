<?php

if (!is_file("modelo/" . $pagina . ".php")) {
    echo "Falta definir la clase " . $pagina;
    exit;
}

require_once("modelo/" . $pagina . ".php");

if (is_file("vista/" . $pagina . ".php")) {
    $o = new pedidos();

    if (!empty($_POST)) {
        $accion = $_POST['accion'];

        switch ($accion) {
            case 'incluir':
                $idCliente = $_POST['idCliente'] ?? '';
                $idProducto = $_POST['idProducto'] ?? [];
                $cantidad = $_POST['cantidad'] ?? [];
                $precio = $_POST['precio'] ?? [];
                echo json_encode($o->incluir($idCliente, $idProducto, $cantidad, $precio));
                break;

            case 'consultar':
                echo json_encode($o->consultar());
                break;

            case 'consultar_uno':
                echo json_encode($o->consultarUno($_POST['numPedido']));
                break;

            case 'modalclientes':
                echo json_encode($o->listadoDeClientes());
                break;

            case 'modalproductos':
                echo json_encode($o->listadoDeProductos());
                break;

            case 'modificar':
                $numPedido = $_POST['numPedido'] ?? '';
                $idCliente = $_POST['idCliente'] ?? '';
                $idProducto = $_POST['idProducto'] ?? [];
                $cantidad = $_POST['cantidad'] ?? [];
                $precio = $_POST['precio'] ?? [];
                echo json_encode($o->modificar($numPedido, $idCliente, $idProducto, $cantidad, $precio));
                break;
    
            case 'eliminar':
                echo json_encode($o->eliminar($_POST['numPedido']));
                break;
        }

        exit;
    }

    require_once("vista/" . $pagina . ".php");
} else {
    echo "PÁGINA EN CONSTRUCCIÓN";
}

?>