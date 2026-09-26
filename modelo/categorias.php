<?php

require_once('modelo/datos.php');

class categorias extends datos
{
    // Atributos
    private $codigoOriginal;
    private $codigoCat;
    private $nombreCat;
    private $descCat;
    private $fotoCat;

    // Setters
    function set_codigoOriginal($valor) { $this->codigoOriginal = $valor; }
    function set_codigoCat($valor) { $this->codigoCat = $valor; }
    function set_nombreCat($valor) { $this->nombreCat = $valor; }
    function set_descCat($valor) { $this->descCat = $valor; }
    function set_fotoCat($valor) { $this->fotoCat = $valor; }

    // Getters
    function get_codigoOriginal() { return $this->codigoOriginal; }
    function get_codigoCat() { return $this->codigoCat; }
    function get_nombreCat() { return $this->nombreCat; }
    function get_descCat() { return $this->descCat; }
    function get_fotoCat() { return $this->fotoCat; }

    // Función para verificar si la Categoría ya está registrada
    function existe($codigoCat)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->prepare("SELECT * FROM categorias WHERE codigoCat = :codigoCat");
            $resultado->bindParam(':codigoCat', $codigoCat);
            $resultado->execute();
            $fila = $resultado->fetchAll(PDO::FETCH_BOTH);

            if ($fila) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    // Función para Registrar Categoría
    function incluir()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        if (!$this->existe($this->codigoCat)) {
            try {
                $inc = $co->prepare("INSERT INTO categorias(codigoCat, nombreCat, descCat, fotoCat)
                VALUES (:codigoCat, :nombreCat, :descCat, :fotoCat)");

                $inc->bindParam(':codigoCat', $this->codigoCat);
                $inc->bindParam(':nombreCat', $this->nombreCat);
                $inc->bindParam(':descCat', $this->descCat);
                $inc->bindParam(':fotoCat', $this->fotoCat);
                $inc->execute();

                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Categoría registrada con éxito.';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Ya existe el código de categoría a registrar.';
        }

        return $r;
    }

    // Función para Consultar Categorías
    function consultar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $resultado = $co->query("SELECT * FROM categorias");
            if ($resultado) {
                $html = '';
                foreach ($resultado as $fila) {
                    $codigoCat = $fila['codigoCat'];
                    $nombreCat = $fila['nombreCat'];
                    $descCat = $fila['descCat'];
                    $fotoCat = $fila['fotoCat'];

                    $img_render = !empty($fotoCat) ? '<img src="' . $fotoCat . '" class="w-100 h-100 object-fit-cover" alt="' . $nombreCat . '">' : '<i class="bi bi-tags-fill fs-1"></i>';

                    $html .= '
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                            <div class="card-header bg-light text-center border-0 d-flex align-items-center justify-content-center p-0" style="color: #FF8C00; height: 150px; overflow: hidden;">
                                ' . $img_render . '
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="text-dashboard fw-bold mb-1">' . $nombreCat . '</h5>
                                <p class="text-muted small mb-3">' . $descCat . '</p>
                                
                                <div class="d-flex justify-content-end gap-2 mt-auto">
                                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3" data-codigo="' . $codigoCat . '" data-nombre="' . $nombreCat . '" data-descripcion="' . $descCat . '" data-foto="' . $fotoCat . '" onclick="pone(this)">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="eliminar(this)">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>';
                }

                if ($html == "") {
                    $html = '
                    <div class="col-12 text-center text-muted py-5">
                        <span><i class="bi bi-tags-fill fs-1" style="color: #FF8C00"></i></span>
                        <h5 class="text-dashboard mt-2">No hay categorías registradas.</h5>
                    </div>';
                }

                $r['resultado'] = 'consultar';
                $r['mensaje'] = $html;
            } else {
                $r['resultado'] = 'consultar';
                $r['mensaje'] = '';
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    // Función para Modificar Categoría
    function modificar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        if ($this->existe($this->codigoOriginal)) {
            try {
                $mod = $co->prepare("UPDATE categorias
                    SET codigoCat = :codigoCat, nombreCat = :nombreCat, descCat = :descCat, fotoCat = :fotoCat 
                    WHERE codigoCat = :codigoOriginal");

                $mod->bindParam(':codigoOriginal', $this->codigoOriginal);
                $mod->bindParam(':codigoCat', $this->codigoCat);
                $mod->bindParam(':nombreCat', $this->nombreCat);
                $mod->bindParam(':descCat', $this->descCat);
                $mod->bindParam(':fotoCat', $this->fotoCat);
                $mod->execute();

                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Categoría modificada con éxito.';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'El código de la categoría no existe.';
        }

        return $r;
    }

    // Función para Eliminar Categoría
    function eliminar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        if ($this->existe($this->codigoCat)) {
            try {
                $eli = $co->prepare("DELETE FROM categorias WHERE codigoCat = :codigoCat");
                $eli->bindParam(':codigoCat', $this->codigoCat);
                $eli->execute();

                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Categoría eliminada con éxito.';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se puede eliminar la categoría porque tiene productos asociados.';
            }
        } else {
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'El código de la categoría no existe.';
        }

        return $r;
    }

    // Función para Buscar
    function buscar($valor)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $valor = trim($valor);
        $busqueda = "%" . $valor . "%";
        $r = array();

        try {
            if ($busqueda) {
                $bus = $co->prepare("SELECT codigoCat, nombreCat, descCat, fotoCat 
                                    FROM categorias
                                    WHERE codigoCat LIKE :busqueda
                                    OR nombreCat LIKE :busqueda
                                    OR descCat LIKE :busqueda");

                $bus->bindParam(':busqueda', $busqueda);
                $bus->execute();
                $resultado = $bus->fetchAll();

                $html = "";
                foreach ($resultado as $fila) {
                    $codigoCat = $fila['codigoCat'];
                    $nombreCat = $fila['nombreCat'];
                    $descCat = $fila['descCat'];
                    $fotoCat = $fila['fotoCat'];

                    $img_render = !empty($fotoCat) ? '<img src="' . $fotoCat . '" class="w-100 h-100 object-fit-cover" alt="' . $nombreCat . '">' : '<i class="bi bi-tags-fill fs-1"></i>';

                    $html .= '
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                            <div class="card-header bg-light text-center border-0 d-flex align-items-center justify-content-center p-0" style="color: #FF8C00; height: 150px; overflow: hidden;">
                                ' . $img_render . '
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="text-dashboard fw-bold mb-1">' . $nombreCat . '</h5>
                                <p class="text-muted small mb-3">' . $descCat . '</p>
                                
                                <div class="d-flex justify-content-end gap-2 mt-auto">
                                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3" data-codigo="' . $codigoCat . '" data-nombre="' . $nombreCat . '" data-descripcion="' . $descCat . '" data-foto="' . $fotoCat . '" onclick="pone(this)">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="eliminar(this)">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>';
                }

                if ($html == "") {
                    $html = '
                    <div class="col-12 text-center text-muted py-5">
                        <span><i class="bi bi-person-fill-slash fs-1" style="color: #FF8C00"></i></span>
                        <h5 class="text-dashboard mt-2">No se encontraron registros</h5>
                        <h6 class="text-secondary">Intenta de nuevo.</h6>
                    </div>';
                }

                $r['resultado'] = 'buscar';
                $r['mensaje'] = $html;
            } else {
                $r['resultado'] = 'consultar';
                $r['mensaje'] = '';
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }
}

?>