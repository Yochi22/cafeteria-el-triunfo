<?php

if (!is_file("modelo/" . $pagina . ".php")) {
    exit;
}

require_once("modelo/" . $pagina . ".php");

if (is_file("vista/" . $pagina . ".php")) {
    $o = new ventas();

    if (!empty($_POST)) {
        $accion = $_POST['accion'];

        switch ($accion) {
            case 'incluir':
                $idPersonal  = $_POST['idpersonal'] ?? '';
                $idCliente   = $_POST['idCliente'] ?? '';
                $idCuenta    = $_POST['idCuenta'] ?? '';
                $tipoPago    = $_POST['tipoPago'] ?? '';
                $cedulaPago  = $_POST['cedulaPago'] ?? '';
                $tlfPago     = $_POST['tlfPago'] ?? '';
                $referencia  = $_POST['refPago'] ?? '';
                $productos   = $_POST['idProducto'] ?? [];
                $cantidades  = $_POST['cantidad'] ?? [];
                $precios     = $_POST['precio'] ?? [];
                $zonaDelivery    = $_POST['zonaDelivery'] ?? '';
                $calleDelivery   = $_POST['calleDelivery'] ?? '';
                $numCasaDelivery = $_POST['numCasaDelivery'] ?? '';

                echo json_encode($o->incluir($idPersonal, $idCliente, $tipoPago, $cedulaPago, $tlfPago, $referencia, 
                                            $idCuenta, $productos, $cantidades, $precios, $zonaDelivery, $calleDelivery, 
                                            $numCasaDelivery));
                break;

            case 'consultar':
                echo json_encode($o->consultar());
                break;

            case 'consultarUno':
                echo json_encode($o->consultarUno($_POST['idVenta'] ?? ''));
                break;

            case 'consultarDetalle':
                echo json_encode($o->consultarDetalle($_POST['idVenta'] ?? ''));
                break;

            case 'modificar':
                $idVenta     = $_POST['idVenta'] ?? '';
                $idDetalle   = $_POST['idDetalle'] ?? '';
                $idPedido    = $_POST['idPedido'] ?? '';
                $idPersonal  = $_POST['idpersonal'] ?? '';
                $idCliente   = $_POST['idCliente'] ?? '';
                $idCuenta    = $_POST['idCuenta'] ?? '';
                $tipoPago    = $_POST['tipoPago'] ?? '';
                $cedulaPago  = $_POST['cedulaPago'] ?? '';
                $tlfPago     = $_POST['tlfPago'] ?? '';
                $referencia  = $_POST['refPago'] ?? '';
                $productos   = $_POST['idProducto'] ?? [];
                $cantidades  = $_POST['cantidad'] ?? [];
                $precios     = $_POST['precio'] ?? [];
                $zonaDelivery    = $_POST['zonaDelivery'] ?? '';
                $calleDelivery   = $_POST['calleDelivery'] ?? '';
                $numCasaDelivery = $_POST['numCasaDelivery'] ?? '';

                echo json_encode($o->modificar($idVenta, $idPersonal, $idCliente, $tipoPago, $idDetalle, $idPedido, 
                                                $cedulaPago, $tlfPago, $referencia, $idCuenta, $productos, $cantidades, 
                                                $precios, $zonaDelivery, $calleDelivery, $numCasaDelivery));
                break;

            case 'eliminar':
                echo json_encode($o->eliminar($_POST['idVenta'] ?? ''));
                break;

            case 'listarPersonal':
                echo json_encode($o->listadoDePersonal());
                break;

            case 'listarClientes':
                echo json_encode($o->listadoDeClientes());
                break;

            case 'listarProductos':
                echo json_encode($o->listadoDeProductos());
                break;

            case 'listarCuentas':
                echo json_encode($o->listadoDeCuentas());
                break;
        }

        exit();
    }

    require_once("vista/" . $pagina . ".php");
}

?>