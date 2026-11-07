<?php

require_once("modelo/login.php");

if (!empty($_POST)) {
    $o = new login();
    $o->set_username($_POST['username']);
    $o->set_password($_POST['password']);
    $m = $o->existe();

    if ($m) {
        session_destroy();
        session_start();
        $_SESSION['nivel'] = $m['rol'];
        $_SESSION['idUsuario'] = $m['idUsuario'];
        header("Location: ?pagina=principal");

        exit;
    } else {
        $mensaje = "Usuario o contraseña incorrectos.";
    }
}

if (is_file("vista/" . $pagina . ".php")) {
    require_once("vista/" . $pagina . ".php");
} else {
    echo "PÁGINA EN CONSTRUCCIÓN";
}

?>