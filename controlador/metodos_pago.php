<?php

if (!is_file("modelo/" . $pagina . ".php")) {
    echo "Falta definir la clase " . $pagina;
    exit;
}

require_once("modelo/" . $pagina . ".php");

if (is_file("vista/" . $pagina . ".php")) {
    $o = new metodopago();
    
    if (!empty($_POST)) {
        $accion = $_POST['accion'];
        
        if ($accion == 'consultar') {
            $datos = $o->consultar();
            $tabla = '';
            foreach ($datos as $r) {
                $tabla .= '
                <tr class="border-bottom">
                    <td class="py-4 text-dashboard">'.$r['nombre_pago'].'</td>
                    <td class="py-4">'.$r['nombreBanco'].'</td>
                    <td class="py-4">'.$r['cedulaTitular'].'</td>
					<td class="py-4">'.$r['telefono'].'</td>
                    <td class="py-4">'.$r['cuenta'].'</td>
                    <td class="py-4">
                        <div class="d-flex gap-4 fs-4">
                            <i class="bi bi-pencil-square text-primary" style="cursor:pointer" onclick="editar('.$r['idMetodo'].')"></i>
                            <i class="bi bi-trash text-danger" style="cursor:pointer" onclick="eliminar('.$r['idMetodo'].')"></i>
                        </div>
                    </td>
                </tr>';
            }
            echo $tabla;
        } elseif ($accion == 'incluir' || $accion == 'modificar') {
            $o->set_idMetodo($_POST['idMetodo'] ?? '');
            $o->set_nombreBanco($_POST['nombreBanco']);
            $o->set_cedulaTitular($_POST['cedulaTitular']);
            $o->set_telefono($_POST['telefono']);
            $o->set_cuenta($_POST['cuenta']);
            
            if ($accion == 'incluir') echo $o->incluir();
            else echo $o->modificar();
        } elseif ($accion == 'eliminar') {
            $o->set_idMetodo($_POST['idMetodo']);
            echo $o->eliminar();
        }
        exit;
    }
    
    require_once("vista/" . $pagina . ".php");
} else {
    echo "Pagina en construccion";
}
?>