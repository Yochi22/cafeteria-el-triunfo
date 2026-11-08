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

                echo json_encode($o->incluir($idPersonal, $idCliente, $tipoPago, $cedulaPago, $tlfPago, $referencia, $idCuenta, $productos, $cantidades, $precios, $zonaDelivery, $calleDelivery, $numCasaDelivery));
                break;

            case 'consultar':
                echo json_encode($o->consultar());
                break;

            case 'consultar_uno':
                echo json_encode($o->consultarUno($_POST['idVenta'] ?? ''));
                break;

            case 'consultar_detalle':
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

                echo json_encode($o->modificar($idVenta, $idPersonal, $idCliente, $tipoPago, $idDetalle, $idPedido, $cedulaPago, $tlfPago, $referencia, $idCuenta, $productos, $cantidades, $precios, $zonaDelivery, $calleDelivery, $numCasaDelivery));
                break;

            case 'eliminar':
                echo json_encode($o->eliminar($_POST['idVenta'] ?? ''));
                break;

            case 'modal_personal':
                echo json_encode($o->listadoDePersonal());
                break;

            case 'modal_clientes':
                echo json_encode($o->listadoDeClientes());
                break;

            case 'modal_productos':
                echo json_encode($o->listadoDeProductos());
                break;

            case 'modal_cuentas':
                echo json_encode($o->listadoDeCuentas());
                break;

            case 'buscar':
                $valor = trim($_POST['valorBusqueda'] ?? '');
                echo json_encode($o->buscar($valor));
                break;

            case 'buscar_fecha':
                $fecha = trim($_POST['fecha'] ?? '');
                echo json_encode($o->buscarPorFecha($fecha));
                break;
        }

        exit();
    }

    require_once("vista/" . $pagina . ".php");
}

?>