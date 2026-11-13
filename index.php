<?php

$pagina = "principal";

if (!empty($_GET['pagina'])) {
    $pagina = $_GET['pagina'];
}

$nivel = "";

if (is_file("modelo/verifica.php")) {
    require_once("modelo/verifica.php");
    $v = new verifica();

    if ($pagina == 'salida') {
        $v->destruyesesion();
    } else {
        $nivel = $v->leesesion();
    }
}

if (is_file("controlador/" . $pagina . ".php")) {
    if ($pagina == 'login' or $pagina == 'catalogo' or $pagina == 'bienvenida') {
        if ($pagina == 'login' and !empty($nivel)) {
            header("Location: ?pagina=principal");
            exit;
        } else {
            require_once("controlador/" . $pagina . ".php");
        }
    } else {
        if (empty($nivel)) {
            header("Location: ?pagina=login");
            exit;
        } else {
            if (
                $nivel == 'Administrador' and
                ($pagina == 'principal' or
                    $pagina == 'pedidos' or
                    $pagina == 'categorias' or
                    $pagina == 'productos' or
                    $pagina == 'ventas' or
                    $pagina == 'reporte' or
                    $pagina == 'cuentas' or
                    $pagina == 'clientes' or
                    $pagina == 'personal' or
                    $pagina == 'usuarios')
            ) {
                require_once("controlador/" . $pagina . ".php");
            } else if (
                $nivel == 'Cajero' and
                ($pagina == 'principal' or
                    $pagina == 'pedidos' or
                    $pagina == 'ventas' or
                    $pagina == 'reporte' or
                    $pagina == 'clientes')
            ) {
                require_once("controlador/" . $pagina . ".php");
            } else if (
                $nivel == 'Mesero' and
                ($pagina == 'principal' or
                    $pagina == 'pedidos' or
                    $pagina == 'clientes' or
                    $pagina == 'productos' or
                    $pagina == 'categorias')
            ) {
                require_once("controlador/" . $pagina . ".php");
            } else {
                echo "<h3>Acceso denegado</h3>";
                echo "<p>No tienes permisos para acceder a este modulo.</p>";
            }
        }
    }
} else {
    echo "PAGINA EN CONSTRUCCION";
}

?>