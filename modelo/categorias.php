<?php

require_once("modelo/datos.php");

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

    // Función Existe
    function existe($codigoCat)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $resultado = $co->prepare("SELECT * FROM categorias WHERE codigoCat = :codigoCat");
            $resultado->bindParam(':codigoCat', $codigoCat);
            $resultado->execute();

            $fila = $resultado->fetch(PDO::FETCH_ASSOC);

            if ($fila) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    // Función Incluir
    function incluir()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!$this->existe($this->codigoCat)) {
            try {
                $i = $co->prepare("INSERT INTO categorias (codigoCat, nombreCat, descCat, fotoCat) VALUES (:codigoCat, :nombreCat, :descCat, :fotoCat)");
                $i->bindParam(":codigoCat", $this->codigoCat);
                $i->bindParam(":nombreCat", $this->nombreCat);
                $i->bindParam(":descCat", $this->descCat);
                $i->bindParam(":fotoCat", $this->fotoCat);
                $i->execute();

                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Categoría registrada.';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Ya existe una categoría con ese código.';
        }

        return $r;
    }

    // Función Consultar
    function consultar()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $resultado = $co->query("SELECT * FROM categorias");
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
                                <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="pone(\'' . $codigoCat . '\', \'' . $nombreCat . '\', \'' . $descCat . '\', \'' . $fotoCat . '\')">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </button>
                                <button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="eliminar(\'' . $codigoCat . '\')">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>';
            }

            if ($html == "") {
                $html = '<div class="col-12 text-center text-muted py-5"><span><i class="bi bi-tags-fill fs-1" style="color: #FF8C00"></i></span><h5 class="text-dashboard mt-2">No hay categorías registradas.</h5></div>';
            }

            $r['resultado'] = 'consultar';
            $r['mensaje'] = $html;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    // Función Modificar
    function modificar()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!$this->existe($this->codigoOriginal)) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'La categoría no existe.';
            return $r;
        }

        if ($this->codigoCat !== $this->codigoOriginal && $this->existe($this->codigoCat)) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Ya existe una categoría con ese código.';
            return $r;
        }

        try {
            $m = $co->prepare("UPDATE categorias SET codigoCat = :codigoCat, nombreCat = :nombreCat, descCat = :descCat, fotoCat = :fotoCat WHERE codigoCat = :codigoOriginal");
            $m->bindParam(":codigoOriginal", $this->codigoOriginal);
            $m->bindParam(":codigoCat", $this->codigoCat);
            $m->bindParam(":nombreCat", $this->nombreCat);
            $m->bindParam(":descCat", $this->descCat);
            $m->bindParam(":fotoCat", $this->fotoCat);
            $m->execute();

            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Categoría modificada.';
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    // Función Eliminar
    function eliminar()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if ($this->existe($this->codigoCat)) {
            try {
                $e = $co->prepare("DELETE FROM categorias WHERE codigoCat = :codigoCat");
                $e->bindParam(":codigoCat", $this->codigoCat);
                $e->execute();

                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Categoría eliminada.';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se puede eliminar esta categoría porque tiene productos asociados.';
            }
        } else {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'La categoría no existe.';
        }

        return $r;
    }

    // Función Buscar
    function buscar($valor)
    {
        $r = array();
        $busqueda = "%" . trim($valor) . "%";
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $b = $co->prepare("SELECT * FROM categorias WHERE (codigoCat LIKE :busqueda OR nombreCat LIKE :busqueda OR descCat LIKE :busqueda)");
            $b->bindParam(':busqueda', $busqueda);
            $b->execute();
            $resultado = $b->fetchAll(PDO::FETCH_ASSOC);
            $html = '';

            if ($resultado) {
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
                                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="pone(\'' . $codigoCat . '\', \'' . $nombreCat . '\', \'' . $descCat . '\', \'' . $fotoCat . '\')">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="eliminar(\'' . $codigoCat . '\')">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                $html = '
                <div class="col-12 text-center text-muted py-5">
                    <span><i class="bi bi-tag-fill fs-1" style="color: #FF8C00"></i></span>
                    <h5 class="text-dashboard mt-2">No se encuentran registros</h5>
                    <h6 class="text-dashboard text-secondary small">Intenta con otra búsqueda.</h6>
                </div>';
            }

            $r['resultado'] = 'buscar';
            $r['mensaje'] = $html;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }
}

?>