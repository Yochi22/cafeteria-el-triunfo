<?php

require_once("modelo/datos.php");

class categorias extends datos
{
    private $idCategoria;
    private $nombre;
    private $descripcion;
    private $foto;
    private $estado;

    function set_idCategoria($valor) { $this->idCategoria = $valor; }
    function set_nombre($valor) { $this->nombre = $valor; }
    function set_descripcion($valor) { $this->descripcion = $valor; }
    function set_foto($valor) { $this->foto = $valor; }
    function set_estado($valor) { $this->estado = $valor; }

    function get_idCategoria() { return $this->idCategoria; }
    function get_nombre() { return $this->nombre; }
    function get_descripcion() { return $this->descripcion; }
    function get_foto() { return $this->foto; }
    function get_estado() { return $this->estado; }

    private function existe($idCategoria)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        try {
            $st = $co->prepare("SELECT idCategoria FROM categorias WHERE idCategoria = :idCategoria AND estado = 1");
            $st->bindParam(':idCategoria', $idCategoria);
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
            $st = $co->prepare("SELECT idCategoria, estado FROM categorias WHERE nombre = :nombre");
            $st->bindParam(':nombre', $this->nombre);
            $st->execute();
            $categoria_existente = $st->fetch(PDO::FETCH_ASSOC);

            if ($categoria_existente) {
                if ($categoria_existente['estado'] == 1) {
                    $r['resultado'] = 'error';
                    $r['mensaje'] = 'Ya existe una categoría activa con ese nombre.';
                } else {
                    $m = $co->prepare("UPDATE categorias SET descripcion = :descripcion, foto = :foto, estado = 1 WHERE idCategoria = :idCategoria");
                    $m->bindParam(':idCategoria', $categoria_existente['idCategoria']);
                    $m->bindParam(':descripcion', $this->descripcion);
                    $m->bindParam(':foto', $this->foto);
                    $m->execute();

                    $r['resultado'] = 'incluir';
                    $r['mensaje'] = 'Categoría Registrada';
                }
            } else {
                $m = $co->prepare("INSERT INTO categorias(nombre, descripcion, foto, estado) VALUES(:nombre, :descripcion, :foto, 1)");
                $m->bindParam(':nombre', $this->nombre);
                $m->bindParam(':descripcion', $this->descripcion);
                $m->bindParam(':foto', $this->foto);
                $m->execute();

                $r['resultado'] = 'incluir';
                $r['mensaje'] = 'Categoría Registrada';
            }
        } catch (Exception $e) {
            $r['resultado'] = 'error';
            $r['mensaje'] = $e->getMessage();
        }
        return $r;
    }

    function modificar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        if ($this->existe($this->idCategoria)) {
            try {
                $m = $co->prepare("UPDATE categorias SET nombre = :nombre, descripcion = :descripcion, foto = :foto WHERE idCategoria = :idCategoria");
                $m->bindParam(':idCategoria', $this->idCategoria);
                $m->bindParam(':nombre', $this->nombre);
                $m->bindParam(':descripcion', $this->descripcion);
                $m->bindParam(':foto', $this->foto);
                $m->execute();

                $r['resultado'] = 'modificar';
                $r['mensaje'] = 'Categoría Modificada';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] =  $e->getMessage();
            }
        } else {
            $r['resultado'] = 'error';
            $r['mensaje'] =  'Categoría no registrada.';
        }
        return $r;
    }

    function eliminar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();

        if ($this->existe($this->idCategoria)) {
            try {
                $e = $co->prepare("UPDATE categorias SET estado = 0 WHERE idCategoria = :idCategoria");
                $e->bindParam(':idCategoria', $this->idCategoria);
                $e->execute();

                $r['resultado'] = 'eliminar';
                $r['mensaje'] = 'Categoría Eliminada';
            } catch (Exception $e) {
                $r['resultado'] = 'error';
                $r['mensaje'] =  $e->getMessage();
            }
        } else {
            $r['resultado'] = 'error';
            $r['mensaje'] = 'Categoría no registrada.';
        }
        return $r;
    }

    function consultar()
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $r = array();
        try {
            $resultado = $co->query("SELECT * FROM categorias WHERE estado = 1");
            $html = '';

            if ($resultado) {
                foreach ($resultado as $fila) {
                    $id = $fila['idCategoria'];
                    $nombre = $fila['nombre'];
                    $desc = $fila['descripcion'];
                    $foto = $fila['foto'];

                    $img_render = !empty($foto) ? '<img src="' . $foto . '" class="w-100 h-100 object-fit-cover" alt="' . $nombre . '">' : '<i class="bi bi-tags-fill fs-1"></i>';

                    $html .= '
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                            <div class="card-header bg-light text-center border-0 d-flex align-items-center justify-content-center p-0" style="color: #FF8C00; height: 140px; overflow: hidden;">
                                ' . $img_render . '
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="text-dashboard fw-bold mb-1">' . $nombre . '</h5>
                                <p class="text-muted small mb-3">' . $desc . '</p>
                                
                                <div class="d-flex justify-content-end gap-2 mt-auto">
                                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="pone(\'' . $id . '\', \'' . $nombre . '\', \'' . $desc . '\', \'' . $foto . '\')">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="eliminar(\'' . $id . '\')">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </button>
                                </div>
                            </div>
                        </div>
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

    function buscar($valor)
    {
        $co = $this->conecta();
        $co->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $busqueda = "%" . trim($valor) . "%";
        $r = array();
        try {
            $bus = $co->prepare("SELECT * FROM categorias WHERE (nombre LIKE :busqueda OR descripcion LIKE :busqueda) AND estado = 1");
            $bus->bindParam(':busqueda', $busqueda);
            $bus->execute();
            $resultado = $bus->fetchAll(PDO::FETCH_ASSOC);

            $html = '';

            if ($resultado) {
                foreach ($resultado as $fila) {
                    $id = $fila['idCategoria'];
                    $nombre = $fila['nombre'];
                    $desc = $fila['descripcion'];
                    $foto = $fila['foto'];

                    $img_render = !empty($foto) ? '<img src="' . $foto . '" class="w-100 h-100 object-fit-cover" alt="' . $nombre . '">' : '<i class="bi bi-tags-fill fs-1"></i>';

                    $html .= '
                    <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-4">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100">
                            <div class="card-header bg-light text-center border-0 d-flex align-items-center justify-content-center p-0" style="color: #FF8C00; height: 140px; overflow: hidden;">
                                ' . $img_render . '
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="text-dashboard fw-bold mb-1">' . $nombre . '</h5>
                                <p class="text-muted small mb-3">' . $desc . '</p>
                                
                                <div class="d-flex justify-content-end gap-2 mt-auto">
                                    <button class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="pone(\'' . $id . '\', \'' . $nombre . '\', \'' . $desc . '\', \'' . $foto . '\')">
                                        <i class="bi bi-pencil-square"></i> Editar
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger rounded-pill px-3" onclick="eliminar(\'' . $id . '\')">
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
                    <h6 class="text-dashboard text-secondary small">Intenta con otra búsqueda</h6>
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