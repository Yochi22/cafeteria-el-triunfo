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
        $v->destruyeSesion();
    } else {
        $nivel = $v->leeSesion();
    }
}

// Validar si el controlador existe
if (!is_file("controlador/" . $pagina . ".php")) {
    echo "PAGINA EN CONSTRUCCION";
    exit;
}

// Páginas públicas o de acceso libre
$paginasLibres = ['login', 'catalogo', 'bienvenida'];

if (in_array($pagina, $paginasLibres)) {
    if ($pagina == 'login' && !empty($nivel)) {
        header("Location: ?pagina=principal");
        exit;
    }
    require_once("controlador/" . $pagina . ".php");
    exit;
}

// Si la página no es libre, se requiere iniciar sesión obligatoriamente
if (empty($nivel)) {
    header("Location: ?pagina=login");
    exit;
}

// Control de acceso por roles
$accesoPermitido = false;

switch ($nivel) {
    case 'Administrador':
        $paginasAdmin = ['principal', 'pedidos', 'categorias', 'productos', 'ventas', 'reporte', 'cuentas', 'clientes', 'personal', 'usuarios'];
        if (in_array($pagina, $paginasAdmin)) {
            $accesoPermitido = true;
        }
        break;

    case 'Cajero':
        $paginasCajero = ['principal', 'pedidos', 'ventas', 'reporte', 'clientes'];
        if (in_array($pagina, $paginasCajero)) {
            $accesoPermitido = true;
        }
        break;

    case 'Mesero':
        $paginasMesero = ['principal', 'pedidos', 'clientes', 'productos', 'categorias'];
        if (in_array($pagina, $paginasMesero)) {
            $accesoPermitido = true;
        }
        break;
}

// Resultado final de la validación
if ($accesoPermitido) {
    require_once("controlador/" . $pagina . ".php");
} else {
    echo "<h3>Acceso denegado</h3>";
    echo "<p>No tienes permisos para acceder a este modulo.</p>";
}

?>