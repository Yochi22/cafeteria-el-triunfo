<?php

if (!is_file("modelo/" . $pagina . ".php")) {
    echo "Falta definir la clase " . $pagina;
    exit;
}

require_once("modelo/" . $pagina . ".php");

if (is_file("vista/" . $pagina . ".php")) {
    $o = new menu();

    if (!empty($_POST)) {
        $accion = $_POST['accion'];

        switch ($accion) {
            case 'cargarProductos':
                echo json_encode($o->cargarProductos());
                break;

            case 'obtener_datos_categorias':
                echo json_encode($o->obtener_datos_categorias());
                break;

            case 'listar_productos':
                $idCategoria = $_POST['idCategoria'];
                echo json_encode($o->listar_productos($idCategoria));
                break;

            case 'buscar':
                $valor = trim($_POST['valorBusqueda']);
                echo json_encode($o->buscar($valor));
                break;

            case 'cargarCarrito':
                $productos = isset($_POST['carrito']) ? $_POST['carrito'] : '[]';
                echo json_encode($o->cargarCarrito($productos));
                break;

            case 'cargarCuentas':
                echo json_encode($o->cargarCuentas());
                break;

            case 'detalleCuenta':
                $idCuenta = $_POST['idCuenta'];
                $montoTotal = $_POST['montoTotal'];
                echo json_encode($o->detalleCuenta($idCuenta, $montoTotal));
                break;
        }

        exit;
    }

    require_once("vista/" . $pagina . ".php");
} else {
    echo "PÁGINA EN CONSTRUCCIÓN";
}

?>