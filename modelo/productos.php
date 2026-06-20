<?php

require_once("modelo/datos.php");

class productos extends datos
{
    private $codigoOrig;
    private $codigoProd;
    private $nombreProd;
    private $precioProd;
    private $descProd;
    private $fotoProd;
    private $idCategoria;

    function set_codigoOrig($valor) { $this->codigoOrig = $valor; }
    function set_codigoProd($valor) { $this->codigoProd = $valor; }
    function set_nombreProd($valor) { $this->nombreProd = $valor; }
    function set_precioProd($valor) { $this->precioProd = $valor; }
    function set_descProd($valor) { $this->descProd = $valor; }
    function set_fotoProd($valor) { $this->fotoProd = $valor; }
    function set_idCategoria($valor) { $this->idCategoria = $valor; }

    function get_codigoOrig() { return $this->codigoOrig; }
    function get_codigoProd() { return $this->codigoProd; }
    function get_nombreProd() { return $this->nombreProd; }
    function get_precioProd() { return $this->precioProd; }
    function get_descProd() { return $this->descProd; }
    function get_fotoProd() { return $this->fotoProd; }
    function get_idCategoria()  { return $this->idCategoria; }

    function existe($codigoProd)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $resultado = $co->prepare("SELECT * FROM productos WHERE codigoProd = :codigoProd");
            $resultado->bindParam(':codigoProd', $codigoProd);
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

    function incluir()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!$this->existe($this->codigoProd)) {
            try {
                $i = $co->prepare("INSERT INTO productos (codigoProd, nombreProd, precioProd, descProd, fotoProd, idCategoria) VALUES (:codigoProd, :nombreProd, :precioProd, :descProd, :fotoProd, :idCategoria)");
                $i->bindParam(':codigoProd', $this->codigoProd);
                $i->bindParam(':nombreProd', $this->nombreProd);
                $i->bindParam(':precioProd', $this->precioProd);
                $i->bindParam(':descProd', $this->descProd);
                $i->bindParam(':fotoProd', $this->fotoProd);
                $i->bindParam(':idCategoria', $this->idCategoria);
                $i->execute();

                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Producto Registrado';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Ya existe un Producto con ese código.';
        }

        return $r;
    }

    function modificar()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!$this->existe($this->codigoOrig)) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'El Producto a modificar no existe.';
            return $r;
        }

        if ($this->codigoProd !== $this->codigoOrig && $this->existe($this->codigoProd)) {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Ya existe un Producto con el nuevo código.';
            return $r;
        }

        try {
            $m = $co->prepare("UPDATE productos SET codigoProd = :codigoProd, nombreProd = :nombreProd, precioProd = :precioProd, descProd = :descProd, fotoProd = :fotoProd, idCategoria = :idCategoria WHERE codigoProd = :codigoOrig");
            $m->bindParam(":codigoOrig", $this->codigoOrig);
            $m->bindParam(":codigoProd", $this->codigoProd);
            $m->bindParam(":nombreProd", $this->nombreProd);
            $m->bindParam(":precioProd", $this->precioProd);
            $m->bindParam(":descProd", $this->descProd);
            $m->bindParam(":fotoProd", $this->fotoProd);
            $m->bindParam(":idCategoria", $this->idCategoria);
            $m->execute();

            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'Producto Modificado';
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    function eliminar()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        if ($this->existe($this->codigoProd)) {
            try {
                $e = $co->prepare("DELETE FROM productos WHERE codigoProd = :codigoProd");
                $e->bindParam(":codigoProd", $this->codigoProd);
                $e->execute();

                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Producto Eliminado';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'El Producto no existe.';
        }

        return $r;
    }

    function consultar()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $co->prepare("SELECT * FROM productos WHERE codigoProd = ?");
            $stmt->execute([$this->codigoProd]);
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($fila) {
                $r['resultado'] = 'consultar';
                $r['mensaje'] = $fila;
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'Producto no encontrado.';
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    function listar_categorias()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $co->query("SELECT * FROM categorias");
            $cats = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $html = '';

            if ($cats) {
                foreach ($cats as $c) {
                    $fotoCat = !empty($c['fotoCat']) ? $c['fotoCat'] : 'img/principal.jpg';

                    $html .= '
                    <div class="col-6 col-md-4 col-lg-3 mb-4">
                        <div class="card border-0 rounded-4 shadow-sm text-center h-100 p-2 position-relative dynamic-card" style="cursor:pointer" onclick="verCategoria('.$c['idCategoria'].', \''.$c['nombreCat'].'\')">
                            <img src="'.$fotoCat.'" class="card-img-top rounded-4" style="height:140px; object-fit:cover;">
                            <div class="card-body px-1 py-3">
                                <h6 class="text-dashboard fw-bold mb-0">'.$c['nombreCat'].'</h6>
                            </div>
                        </div>
                    </div>';
                }
            } else {

                $html = '<div class="col-12 text-center text-muted py-5"><h5 class="text-dashboard mt-2">No hay categorías registradas</h5></div>';

            }

            $r['resultado'] = 'listar_categorias';
            $r['mensaje'] = $html;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
    function cargar_select_categorias()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $stmt = $co->query("SELECT * FROM categorias");
            $cats = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $html = '';
            
            if ($cats) {
                foreach ($cats as $c) {
                    $html .= '<option value="'.$c['idCategoria'].'">'.$c['nombreCat'].'</option>';
                }
            }

            $r['resultado'] = 'cargar_select_categorias';
            $r['mensaje'] = $html;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    function buscar($valor)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $busqueda = "%" . trim($valor) . "%";
        $r = array();

        try {
            $b = $co->prepare("SELECT * FROM productos WHERE (nombreProd LIKE :busqueda OR descProd LIKE :busqueda)");
            $b->bindParam(':busqueda', $busqueda);
            $b->execute();
            $prods = $b->fetchAll(PDO::FETCH_ASSOC);
            $html = '';

            if ($prods) {
                foreach ($prods as $p) {
                    $fotoProd = $p['fotoProd'] ? $p['fotoProd'] : 'img/principal.jpg';
                    $nombreProd = $p['nombreProd'];
                    $descProd= $p['descProd'];
                    $fotoProd= $p['fotoProd'];
                    $precioProd = $p['precioProd'];
                    $idCat = $p['idCategoria'];
                    $codigoProd = $p['codigoProd'];

                    $html .= '
                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                        <div class="card border-0 rounded-4 shadow-sm h-100 dynamic-card">
                            <div class="row g-0 h-100 align-items-center">
                                <div class="col-4 p-2 text-center">
                                    <img src="'.$fotoProd.'" class="img-fluid rounded-3" style="height:90px; width:90px; object-fit:cover;">
                                </div>
                                <div class="col-8">
                                    <div class="card-body py-3 pe-3 ps-2 d-flex flex-column justify-content-between h-100">
                                        <div>
                                            <h6 class="text-dashboard fw-bold mb-1 text-truncate">'.$p['nombreProd'].'</h6>
                                            <p class="text-success fw-bold small mb-2">$'.number_format($p['precioProd'], 2).'</p>
                                        </div>
                                        <div class="d-flex justify-content-end gap-2">
                                            <button class="btn btn-sm btn-light rounded-3 text-primary shadow-2-strong" onclick="pone(\''.$codigoProd.'\', \''.$nombreProd.'\', '.$precioProd.', \''.$descProd.'\', \''.$fotoProd.'\', '.$idCat.')">
                                                <i class="bi bi-pencil-square">Editar</i>
                                            </button>
                                            <button class="btn btn-sm btn-light rounded-3 text-danger shadow-2-strong" onclick="eliminar(\''.$codigoProd.'\')">
                                                <i class="bi bi-trash">Eliminar</i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                $html = '
                <div class="col-12 text-center text-muted py-5">
                    <span><i class="bi bi-box-seam fs-1" style="color: #FF8C00"></i></span>
                    <h5 class="text-dashboard mt-2">No se encuentran productos</h5>
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

    function listar_productos()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt = $co->prepare("SELECT * FROM productos WHERE idCategoria = ?");
            $stmt->execute([$this->idCategoria]);
            $prods = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $html = '';

            if ($prods) {
                foreach ($prods as $p) {
                    $fotoProd = $p['fotoProd'] ? $p['fotoProd'] : 'img/principal.jpg';
                    $nombreProd = $p['nombreProd'];
                    $descProd = $p['descProd'];
                    $fotoProd = $p['fotoProd'];
                    $precioProd = $p['precioProd'];
                    $idCat = $p['idCategoria'];
                    $codigoProd = $p['codigoProd'];

                    $html .= '
                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                        <div class="card border-0 rounded-4 shadow-sm h-100 dynamic-card">
                            <div class="row g-0 h-100 align-items-center">
                                <div class="col-4 p-2 text-center">
                                    <img src="' . $fotoProd . '" class="img-fluid rounded-3" style="height:90px; width:90px; object-fit:cover;">
                                </div>
                                <div class="col-8">
                                    <div class="card-body py-3 pe-3 ps-2 d-flex flex-column justify-content-between h-100">
                                        <div>
                                            <h6 class="text-dashboard fw-bold mb-1 text-truncate">' . $p['nombreProd'] . '</h6>
                                            <p class="text-success fw-bold small mb-2">$' . number_format($p['precioProd'], 2) . '</p>
                                        </div>
                                        <div class="d-flex justify-content-end gap-2">
                                            <button class="btn btn-sm btn-light rounded-3 text-primary shadow-2-strong" onclick="pone(\'' . $codigoProd . '\', \'' . $nombreProd . '\', ' . $precioProd . ', \'' . $descProd . '\', \'' . $fotoProd . '\', ' . $idCat . ')">
                                                <i class="bi bi-pencil-square">Editar</i>
                                            </button>
                                            <button class="btn btn-sm btn-light rounded-3 text-danger shadow-2-strong" onclick="eliminar(\'' . $codigoProd . '\')">
                                                <i class="bi bi-trash">Eliminar</i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                $html = '<div class="col-12 text-center text-muted py-5"><h5 class="text-dashboard mt-2">No hay productos en esta categoría.</h5></div>';
            }

            $r['resultado'] = 'listar_productos';
            $r['mensaje'] = $html;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
}

?>