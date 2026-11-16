<?php

require_once('modelo/datos.php');

class productos extends datos
{
    // Atributos
    private $codigoOriginal;
    private $codigoProd;
    private $nombreProd;
    private $precioProd;
    private $descProd;
    private $fotoProd;
    private $idCategoria;

    // Setters
    function set_codigoOriginal($valor) { $this->codigoOriginal = $valor; }
    function set_codigoProd($valor) { $this->codigoProd = $valor; }
    function set_nombreProd($valor) { $this->nombreProd = $valor; }
    function set_precioProd($valor) { $this->precioProd = $valor; }
    function set_descProd($valor) { $this->descProd = $valor; }
    function set_fotoProd($valor) { $this->fotoProd = $valor; }
    function set_idCategoria($valor) { $this->idCategoria = $valor; }

    // Getters
    function get_codigoOriginal() { return $this->codigoOriginal; }
    function get_codigoProd() { return $this->codigoProd; }
    function get_nombreProd() { return $this->nombreProd; }
    function get_precioProd() { return $this->precioProd; }
    function get_descProd() { return $this->descProd; }
    function get_fotoProd() { return $this->fotoProd; }
    function get_idCategoria() { return $this->idCategoria; }

    // Función para verificar si el Producto ya está registrado
    function existe($codigoProd)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $resultado = $co->prepare("SELECT * FROM productos WHERE codigoProd = :codigoProd");
            $resultado->bindParam(':codigoProd', $codigoProd);
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

    // Función para Registrar Producto
    function incluir()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        if (!$this->existe($this->codigoProd)) {
            try {
                $inc = $co->prepare("INSERT INTO productos(codigoProd, nombreProd, precioProd, descProd, fotoProd, idCategoria)
                VALUES (:codigoProd, :nombreProd, :precioProd, :descProd, :fotoProd, :idCategoria)");

                $inc->bindParam(':codigoProd', $this->codigoProd);
                $inc->bindParam(':nombreProd', $this->nombreProd);
                $inc->bindParam(':precioProd', $this->precioProd);
                $inc->bindParam(':descProd', $this->descProd);
                $inc->bindParam(':fotoProd', $this->fotoProd);
                $inc->bindParam(':idCategoria', $this->idCategoria);
                $inc->execute();

                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Producto registrado con éxito.';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'incluir';
            $r['mensaje'] = 'Ya existe el código de producto a registrar.';
        }

        return $r;
    }

    // Función para Consultar Categorías (Pantalla Inicial)
    function consultarCategorias()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $resultado = $co->query("SELECT * FROM categorias");
            $htmlCards = '';
            $htmlSelect = '';

            if ($resultado) {
                foreach ($resultado as $c) {
                    $htmlSelect .= '<option value="' . $c['idCategoria'] . '">' . $c['nombreCat'] . '</option>';

                    $fotoCat = !empty($c['fotoCat']) ? $c['fotoCat'] : '';
                    $img_render = !empty($fotoCat) ? '<img src="' . $fotoCat . '" class="w-100 h-100 object-fit-cover" alt="' . $c['nombreCat'] . '">' : '<i class="bi bi-tags-fill fs-1"></i>';

                    $htmlCards .= '
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100" style="cursor: pointer;" onclick="verCategoria(' . $c['idCategoria'] . ', \'' . addslashes($c['nombreCat']) . '\')">
                            <div class="card-header bg-light text-center border-0 d-flex align-items-center justify-content-center p-0" style="color: #FF8C00; height: 150px; overflow: hidden;">
                                ' . $img_render . '
                            </div>
                            <div class="card-body d-flex flex-column text-center">
                                <h5 class="text-dashboard fw-bold mb-1">' . $c['nombreCat'] . '</h5>
                                <p class="text-muted small mb-0">' . ($c['descCat'] ?? '') . '</p>
                            </div>
                        </div>
                    </div>';
                }
            }

            if ($htmlCards == "") {
                $htmlCards = '
                <div class="col-12 text-center text-muted py-5">
                    <span><i class="bi bi-tags-fill fs-1" style="color: #FF8C00"></i></span>
                    <h5 class="text-dashboard mt-2">No hay categorías registradas.</h5>
                </div>';
            }

            $btnGestionar = '
            <a href="?pagina=categorias" class="btn btn-crear d-flex align-items-center gap-2 shadow-sm py-2 px-3 rounded-3" style="background-color: #198754 !important; border-color: #198754 !important;">
                <i class="bi bi-tags fs-5"></i>
                <span class="d-none d-sm-inline">Gestionar Categorías</span>
            </a>';

            $r['resultado'] = 'consultarCategorias';
            $r['cards'] = $htmlCards;
            $r['select'] = $htmlSelect;
            $r['boton'] = $btnGestionar;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    // Función para Consultar Productos de una Categoría
    function consultarProductos()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        try {
            $stmt = $co->prepare("SELECT p.*, c.nombreCat FROM productos p LEFT JOIN categorias c ON p.idCategoria = c.idCategoria WHERE p.idCategoria = ?");
            $stmt->execute([$this->idCategoria]);
            $prods = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $html = '';

            if ($prods) {
                foreach ($prods as $p) {
                    $codigoProd = $p['codigoProd'];
                    $nombreProd = $p['nombreProd'];
                    $precioProd = $p['precioProd'];
                    $descProd = $p['descProd'];
                    $fotoProd = $p['fotoProd'];
                    $idCat = $p['idCategoria'];
                    $nombreCat = $p['nombreCat'] ?? 'Sin categoría';

                    $img_render = !empty($fotoProd) ? '<img src="' . $fotoProd . '" class="w-100 h-100 object-fit-cover" alt="' . $nombreProd . '">' : '<i class="bi bi-box-seam fs-1"></i>';

                    $html .= '
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                            <div class="card-header bg-light text-center border-0 d-flex align-items-center justify-content-center p-0" style="color: #FF8C00; height: 150px; overflow: hidden;">
                                ' . $img_render . '
                            </div>
                            <div class="card-body d-flex flex-column">
                                <span class="badge bg-light text-dark align-self-start mb-2 border"><i class="bi bi-tag"></i> ' . $nombreCat . '</span>
                                <h5 class="text-dashboard fw-bold mb-1">' . $nombreProd . '</h5>
                                <h6 class="text-success fw-bold mb-2">$' . number_format($precioProd, 2) . '</h6>
                                <p class="text-muted small mb-3">' . $descProd . '</p>
                                
                                <div class="d-flex justify-content-end gap-2 mt-auto">
                                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3" data-codigo="' . $codigoProd . '" data-nombre="' . addslashes($nombreProd) . '" data-precio="' . $precioProd . '" data-descripcion="' . addslashes($descProd) . '" data-foto="' . $fotoProd . '" data-idcategoria="' . $idCat . '" onclick="pone(this)">
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
            } else {
                $html = '
                <div class="col-12 text-center text-muted py-5">
                    <span><i class="bi bi-box-seam fs-1" style="color: #FF8C00"></i></span>
                    <h5 class="text-dashboard mt-2">No hay productos en esta categoría.</h5>
                </div>';
            }

            $btnRegresar = '
            <button class="btn btn-crear d-flex align-items-center gap-2 shadow-sm py-2 px-3 rounded-3" style="background-color: #0d6efd !important; border-color: #0d6efd !important;" onclick="consultar()">
                <i class="bi bi-arrow-left-circle fs-5"></i>
                <span class="d-none d-sm-inline">Regresar</span>
            </button>';

            $r['resultado'] = 'consultarProductos';
            $r['mensaje'] = $html;
            $r['boton'] = $btnRegresar;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }

        return $r;
    }

    // Función para Modificar Producto
    function modificar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        if ($this->existe($this->codigoOriginal)) {
            try {
                $mod = $co->prepare("UPDATE productos
                    SET codigoProd = :codigoProd, nombreProd = :nombreProd, precioProd = :precioProd, descProd = :descProd, fotoProd = :fotoProd, idCategoria = :idCategoria 
                    WHERE codigoProd = :codigoOriginal");

                $mod->bindParam(':codigoOriginal', $this->codigoOriginal);
                $mod->bindParam(':codigoProd', $this->codigoProd);
                $mod->bindParam(':nombreProd', $this->nombreProd);
                $mod->bindParam(':precioProd', $this->precioProd);
                $mod->bindParam(':descProd', $this->descProd);
                $mod->bindParam(':fotoProd', $this->fotoProd);
                $mod->bindParam(':idCategoria', $this->idCategoria);
                $mod->execute();

                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Producto modificado con éxito.';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = $e->getMessage();
            }
        } else {
            $r['resultado'] = 'modificar';
            $r['mensaje'] = 'El código del producto no existe.';
        }

        return $r;
    }

    // Función para Eliminar Producto
    function eliminar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        if ($this->existe($this->codigoProd)) {
            try {
                $eli = $co->prepare("DELETE FROM productos WHERE codigoProd = :codigoProd");
                $eli->bindParam(':codigoProd', $this->codigoProd);
                $eli->execute();

                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Producto eliminado con éxito.';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'No se puede eliminar el producto porque tiene registros asociados.';
            }
        } else {
            $r['resultado'] = 'eliminar';
            $r['mensaje'] = 'El código del producto no existe.';
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
                $bus = $co->prepare("SELECT p.*, c.nombreCat 
                                    FROM productos p 
                                    LEFT JOIN categorias c ON p.idCategoria = c.idCategoria
                                    WHERE p.codigoProd LIKE :busqueda
                                    OR p.nombreProd LIKE :busqueda
                                    OR p.descProd LIKE :busqueda
                                    OR c.nombreCat LIKE :busqueda");

                $bus->bindParam(':busqueda', $busqueda);
                $bus->execute();
                $resultado = $bus->fetchAll();

                $html = "";
                foreach ($resultado as $fila) {
                    $codigoProd = $fila['codigoProd'];
                    $nombreProd = $fila['nombreProd'];
                    $precioProd = $fila['precioProd'];
                    $descProd = $fila['descProd'];
                    $fotoProd = $fila['fotoProd'];
                    $idCategoria = $fila['idCategoria'];
                    $nombreCat = $fila['nombreCat'] ?? 'Sin categoría';

                    $img_render = !empty($fotoProd) ? '<img src="' . $fotoProd . '" class="w-100 h-100 object-fit-cover" alt="' . $nombreProd . '">' : '<i class="bi bi-box-seam fs-1"></i>';

                    $html .= '
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                            <div class="card-header bg-light text-center border-0 d-flex align-items-center justify-content-center p-0" style="color: #FF8C00; height: 150px; overflow: hidden;">
                                ' . $img_render . '
                            </div>
                            <div class="card-body d-flex flex-column">
                                <span class="badge bg-light text-dark align-self-start mb-2 border"><i class="bi bi-tag"></i> ' . $nombreCat . '</span>
                                <h5 class="text-dashboard fw-bold mb-1">' . $nombreProd . '</h5>
                                <h6 class="text-success fw-bold mb-2">$' . number_format($precioProd, 2) . '</h6>
                                <p class="text-muted small mb-3">' . $descProd . '</p>
                                
                                <div class="d-flex justify-content-end gap-2 mt-auto">
                                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3" data-codigo="' . $codigoProd . '" data-nombre="' . addslashes($nombreProd) . '" data-precio="' . $precioProd . '" data-descripcion="' . addslashes($descProd) . '" data-foto="' . $fotoProd . '" data-idcategoria="' . $idCategoria . '" onclick="pone(this)">
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
                        <span><i class="bi bi-box-seam fs-1" style="color: #FF8C00"></i></span>
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