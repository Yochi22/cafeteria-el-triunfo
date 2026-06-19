<?php

if (!is_file("modelo/" . $pagina . ".php")) {
    echo "Falta definir la clase " . $pagina;
    exit;
}

require_once("modelo/" . $pagina . ".php");

if (is_file("vista/" . $pagina . ".php")) {
    $o = new principal();
    $metricas = $o->obtenerMetricas();

    require_once("vista/" . $pagina . ".php");
} else {
    echo "PÁGINA EN CONSTRUCCIÓN";
}

?>