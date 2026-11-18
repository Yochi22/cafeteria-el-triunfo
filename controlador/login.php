<?php

if (!is_file("modelo/" . $pagina . ".php")) {
    echo "Falta definir la clase " . $pagina;
    exit;
}

require_once("modelo/" . $pagina . ".php");

if (is_file("vista/" . $pagina . ".php")) {
    $o = new login();

    if (!empty($_POST)) {
        $accion = $_POST['accion'] ?? '';

        if ($accion === 'login') {
            $o->set_username($_POST['username']);
            $o->set_password($_POST['password']);
            $resultado = $o->existe();

            if ($resultado['resultado'] === 'login' && isset($resultado['estatus']) && $resultado['estatus'] === 'exito') {
                if (session_status() == PHP_SESSION_NONE) {
                    session_start();
                }

                $_SESSION['nivel'] = $resultado['datos']['rol'];
                $_SESSION['idUsuario'] = $resultado['datos']['idUsuario'];
                $_SESSION['username'] = $resultado['datos']['username'];
            }

            echo json_encode($resultado);
        }

        exit();
    }

    require_once("vista/" . $pagina . ".php");
} else {
    echo "PÁGINA EN CONSTRUCCIÓN";
}

?>