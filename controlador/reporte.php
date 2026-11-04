<?php

if (!is_file("modelo/" . $pagina . ".php")) {
    echo "Falta definir la clase " . $pagina;
    exit;
}

require_once("modelo/" . $pagina . ".php");

if (is_file("vista/" . $pagina . ".php")) {
    $o = new rreportes();

    if (!empty($_POST)) {
        $accion = $_POST['accion'] ?? '';

        switch ($accion) {
            case 'filtrar':
                $o->set_fecha_inicio($_POST['fecha_inicio'] ?? '');
                $o->set_fecha_fin($_POST['fecha_fin'] ?? '');
                echo json_encode($o->consultarDatosReporte());
                break;

            case 'generar_pdf':
                $o->set_fecha_inicio($_POST['fecha_inicio'] ?? '');
                $o->set_fecha_fin($_POST['fecha_fin'] ?? '');
                $o->generarPDF();
                break;
        }

        exit;
    }

    require_once("vista/" . $pagina . ".php");
} else {
    echo "PÁGINA EN CONSTRUCCIÓN";
}

?>