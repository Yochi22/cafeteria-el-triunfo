<?php
require_once('modelo/datos.php');

class productos extends datos 
{
    private $idProducto;
    private $nombre;
    private $precio;
    private $descripcion;
    private $foto;
    private $idCategoria;
    private $estado;

    function set_idProducto($valor) { $this->idProducto = $valor; }
    function set_nombre($valor) { $this->nombre = $valor; }
    function set_precio($valor) { $this->precio = $valor; }
    function set_descripcion($valor) { $this->descripcion = $valor; }
    function set_foto($valor) { $this->foto = $valor; }
    function set_idCategoria($valor) { $this->idCategoria = $valor; }
    function set_estado($valor) { $this->estado = $valor; }

    function get_idProducto() { return $this->idProducto; }
    function get_nombre() { return $this->nombre; }
    function get_precio() { return $this->precio; }
    function get_descripcion() { return $this->descripcion; }
    function get_foto() { return $this->foto; }
    function get_idCategoria()  { return $this->idCategoria; }
    function get_estado() { return $this->estado; }

    private function existe_id($idProducto)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $st = $co->prepare("SELECT idProducto FROM productos WHERE idProducto = :idProducto AND estado = 1");
            $st->bindParam(':idProducto', $idProducto);
            $st->execute();
            
            $fila = $st->fetch(PDO::FETCH_ASSOC);
            return $fila ? true : false;
        } catch (Exception $e) {
            return false;
        }
    }

    function incluir()
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $st = $co->prepare("SELECT idProducto, estado FROM productos WHERE nombre = :nombre AND descripcion = :descripcion");
            $st->bindParam(':nombre', $this->nombre);
            $st->bindParam(':descripcion', $this->descripcion);
            $st->execute();
            $producto_existente = $st->fetch(PDO::FETCH_ASSOC);

            if ($producto_existente) {
                if ($producto_existente['estado'] == 1) {
                    $r['resultado'] = 'error';
                    $r['mensaje'] = 'Ya existe un producto con ese nombre y descripción.';
                } else {
                    $m = $co->prepare("UPDATE productos SET precio = :precio, foto = :foto, idCategoria = :idCategoria, estado = 1 WHERE idProducto = :idProducto");
                    $m->bindParam(':idProducto', $producto_existente['idProducto']);
                    $m->bindParam(':precio', $this->precio);
                    $m->bindParam(':foto', $this->foto);
                    $m->bindParam(':idCategoria', $this->idCategoria);
                    $m->execute();

                    $r['resultado'] = 'incluir';
                    $r['mensaje'] = 'Registro Incluido';
                }
            } else {
                $m = $co->prepare("INSERT INTO productos(nombre, precio, descripcion, foto, idCategoria) VALUES(:nombre, :precio, :descripcion, :foto, :idCategoria)");
                $m->bindParam(':nombre', $this->nombre);
                $m->bindParam(':precio', $this->precio);
                $m->bindParam(':descripcion', $this->descripcion);
                $m->bindParam(':foto', $this->foto);
                $m->bindParam(':idCategoria', $this->idCategoria);
                $m->execute();

                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Registro Incluido';
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    function modificar() 
    {
        $r = array();
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            if ($this->existe_id($this->idProducto)) {
                $m = $co->prepare("UPDATE productos SET nombre = :nombre, precio = :precio, descripcion = :descripcion, foto = :foto, idCategoria = :idCategoria WHERE idProducto = :idProducto");
                $m->bindParam(':idProducto', $this->idProducto);
                $m->bindParam(':nombre', $this->nombre);
                $m->bindParam(':precio', $this->precio);
                $m->bindParam(':descripcion', $this->descripcion);
                $m->bindParam(':foto', $this->foto);
                $m->bindParam(':idCategoria', $this->idCategoria);
                $m->execute();
                
                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Registro Modificado';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'Producto no registrado.';
            }
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
        try {
            if ($this->existe_id($this->idProducto)) {
                $e = $co->prepare("UPDATE productos SET estado = 0 WHERE idProducto = :idProducto");
                $e->bindParam(':idProducto', $this->idProducto);
                $e->execute();
                
                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Registro Eliminado';
            } else {
                $r['resultado'] = 'error';
                $r['mensaje'] = 'Producto no registrado.';
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    function consultar_id()
    {
        $r = array();
        try {
            $co = $this->conecta();
            $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $co->prepare("SELECT * FROM productos WHERE idProducto = ?");
            $stmt->execute([$this->idProducto]);
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($fila) {
                $r['resultado'] = 'consultar_id';
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

    function listar_categorias() {
        $r = array();
        $co = $this->conecta();
        try {
            $stmt = $co->query("SELECT * FROM categorias WHERE estado = 1");
            $cats = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $html = '';
            foreach ($cats as $c) {
                $foto = $c['foto'] ? $c['foto'] : 'img/principal.jpg';
                $html .= '
                <div class="col-6 col-md-4 col-lg-3 mb-4">
                    <div class="card border-0 rounded-4 shadow-sm text-center h-100 p-2 position-relative dynamic-card" style="cursor:pointer" onclick="verCategoria('.$c['idCategoria'].', \''.addslashes($c['nombre']).'\')">
                        <img src="'.$foto.'" class="card-img-top rounded-4" style="height:140px; object-fit:cover;">
                        <div class="card-body px-1 py-3">
                            <h6 class="text-dashboard fw-bold mb-0">'.$c['nombre'].'</h6>
                        </div>
                    </div>
                </div>';
            }
            $r['resultado'] = 'listar_categorias';
            $r['mensaje'] = $html;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    function listar_productos() {
        $r = array();
        $co = $this->conecta();
        try {
            $stmt = $co->prepare("SELECT * FROM productos WHERE idCategoria = ? AND estado = 1");
            $stmt->execute([$this->idCategoria]);
            $prods = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $html = '';
            foreach ($prods as $p) {
                $foto = $p['foto'] ? $p['foto'] : 'img/principal.jpg';
                $nombre_js = addslashes($p['nombre']);
                $desc_js = addslashes($p['descripcion']);
                $foto_js = addslashes($p['foto']);
                $precio_js = $p['precio'];
                $idCat_js = $p['idCategoria'];
                $id = $p['idProducto'];

                $html .= '
                <div class="col-12 col-md-6 col-lg-4 mb-4">
                    <div class="card border-0 rounded-4 shadow-sm h-100 dynamic-card">
                        <div class="row g-0 h-100 align-items-center">
                            <div class="col-4 p-2 text-center">
                                <img src="'.$foto.'" class="img-fluid rounded-3" style="height:90px; width:90px; object-fit:cover;">
                            </div>
                            <div class="col-8">
                                <div class="card-body py-3 pe-3 ps-2 d-flex flex-column justify-content-between h-100">
                                    <div>
                                        <h6 class="text-dashboard fw-bold mb-1 text-truncate">'.$p['nombre'].'</h6>
                                        <p class="text-success fw-bold small mb-2">$'.number_format($p['precio'], 2).'</p>
                                    </div>
                                    <div class="d-flex justify-content-end gap-2">
                                        <button class="btn btn-sm btn-light rounded-3 text-primary shadow-2-strong" onclick="pone('.$id.', \''.$nombre_js.'\', '.$precio_js.', \''.$desc_js.'\', \''.$foto_js.'\', '.$idCat_js.', 0)">
                                            <i class="bi bi-pencil-square">Editar</i>
                                        </button>
                                        <button class="btn btn-sm btn-light rounded-3 text-danger shadow-2-strong" onclick="pone('.$id.', \''.$nombre_js.'\', '.$precio_js.', \''.$desc_js.'\', \''.$foto_js.'\', '.$idCat_js.', 1)">
                                            <i class="bi bi-trash">Eliminar</i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
            }
            $r['resultado'] = 'listar_productos';
            $r['mensaje'] = $html;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    function cargar_select_categorias() {
        $r = array();
        $co = $this->conecta();
        try {
            $stmt = $co->query("SELECT * FROM categorias WHERE estado = 1");
            $cats = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $html = '';
            foreach ($cats as $c) {
                $html .= '<option value="'.$c['idCategoria'].'">'.$c['nombre'].'</option>';
            }
            $r['resultado'] = 'cargar_select_categorias';
            $r['mensaje'] = $html;
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }
}
?>