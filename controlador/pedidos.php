<?php

if (!is_file("modelo/" . $pagina . ".php")) {
    echo "Falta definir la clase " . $pagina;
    exit;
}

require_once("modelo/" . $pagina . ".php");

if (is_file("vista/" . $pagina . ".php")) {
    $o = new pedidos();

    if (!empty($_POST['accion'])) {
        $accion = $_POST['accion'];

        switch ($accion) {
            case 'incluir':
                echo json_encode($o->incluir(
                    $_POST['idCliente'],
                    $_POST['idCuenta'],
                    $_POST['idProducto'],
                    $_POST['cantidad'],
                    $_POST['precio'],
                    $_POST['numComprobante'],
                    $_POST['zonaDelivery'] ?? '',
                    $_POST['calleDelivery'] ?? '',
                    $_POST['numCasaDelivery'] ?? ''
                ));
                break;

            case 'consultar':
                $estadoFiltro = $_POST['estadoFiltro'] ?? 'Todos';
                echo json_encode($o->consultar($estadoFiltro));
                break;

            case 'consultarUno':
                echo json_encode($o->consultarUno($_POST['idPedido']));
                break;

            case 'listarClientes':
                echo json_encode($o->listadoDeClientes());
                break;

            case 'listarCuentas':
                echo json_encode($o->listadoDeCuentas());
                break;

            case 'listarProductos':
                echo json_encode($o->listadoDeProductos());
                break;

            case 'modificar':
                echo json_encode($o->modificar(
                    $_POST['idPedido'],
                    $_POST['idCliente'],
                    $_POST['idCuenta'],
                    $_POST['estadoPedido'],
                    $_POST['idProducto'],
                    $_POST['cantidad'],
                    $_POST['precio'],
                    (array_key_exists('referenciaPago', $_POST) ? $_POST['referenciaPago'] : ($_POST['numComprobante'] ?? '')),
                    $_POST['zonaDelivery'] ?? '',
                    $_POST['calleDelivery'] ?? '',
                    $_POST['numCasaDelivery'] ?? '',
                    $_POST['tipoPago'] ?? '',
                    $_POST['cedulaPago'] ?? '',
                    $_POST['tlfPago'] ?? ''
                ));
                break;

            case 'eliminar':
                echo json_encode($o->eliminar($_POST['idPedido']));
                break;
        }

        exit;
    }

    require_once("vista/" . $pagina . ".php");
} else {
    echo "PÁGINA EN CONSTRUCCIÓN";
}

?>