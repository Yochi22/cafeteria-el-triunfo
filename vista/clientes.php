<!DOCTYPE html>
<html lang="es">
<?php require_once("comunes/encabezado.php"); ?>
<body>
    <div class="d-flex">
        <?php require_once("comunes/sidebar.php"); ?>

        <!--encabezado de bienvenida -->
        <main class="main-content">
            <h2 class="text-dashboard"><i class="bi bi-file-person"></i> Clientes</h2>
            <p class="text-muted">Bienvenido al apartado de Clientes de la Cafeteria El Triunfo!</p>

            <div class="row">
                <div class="col">
                    <hr class="border border-secundary">
                </div>
            </div>

            <!--boton para registrar.-->
           <div class="col-12 d-flex justify-content-end mb-3">
           <button type="button" id="registrar" class="btn text-white rounded fw-bold py-2 px-4" style="background-color: #FF8C00;" data-bs-toggle="modal" data-bs-target="#modal_cliente">
           Añadir Cliente
           </button>
           </div>

            <!--contenido -->
                <table class="table table-hover align-items-center text-center">
                    <thead class="text-dashboard border-bottom">
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Cédula</th>
                            <th>Teléfono</th>
                            <th>Dirección</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="listaClientes" class="text-center">
                        
                    </tbody>
                </table>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div id="paginas" class="text-muted">Pagina 1 de 5</div>
                    <div class="d-flex gap-4">
                        <button class="btn btn-link text-decoration-none text-muted"><i class="bi bi-arrow-left-circle"></i> Anterior</button>
                        <button class="btn btn-link text-decoration-none text-muted">Siguiente <i class="bi bi-arrow-right-circle"></i></button>
                    </div>
                </div>
            </div>

<!-- seccion del modal -->
        <div class="modal fade" id="modal_cliente" tabindex="-1" aria-labelledby="modal_cliente_label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 rounded-4 shadow">
        <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold text-dashboard" id="modal_cliente_label">Añadir Cliente</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body pt-3">
        <form id="f">
        <input type="hidden" id="idMetodoPago" name="idMetodoPago">
        <input type="hidden" id="accion" name="accion">

                <div class="mb-3">
                <label for="nombre" class="form-label text-muted small">Nombre</label>
                <input type="text" class="form-control rounded-3" id="nombre" name="nombre" required>
                </div>

                <div class="mb-3">
                <label for="apellido" class="form-label text-muted small">Apellido</label>
                <input type="text" class="form-control rounded-3" id="apellido" name="apellido" required>
                </div>

                <div class="mb-3">
                    <label for="cedula" class="form-label text-muted small">Cédula</label>
                    <input type="text" class="form-control rounded-3" id="cedula" name="cedula" required>
                </div>

                <div class="mb-3">
                    <label for="telefono" class="form-label text-muted small">Teléfono</label>
                    <input type="text" class="form-control rounded-3" id="telefono" name="telefono" required>
                </div>

                <div class="mb-3">
                    <label for="direccion" class="form-label text-muted small">Dirección</label>
                    <input type="text" class="form-control rounded-3" id="direccion" name="direccion" required>
                </div>

                <div class="d-grid mt-4">
                    <button type="button" class="btn btn-crear w-100 mt-3" id="btnGuardar">Guardar Cliente</button>
                </div>
                </form>
                </div>
        </div>
        </div>
        </div>
<!--fin de seccion modal-->
<!--Llamada a archivo modal.php, dentro de el hay una sección modal-->
<?php require_once("comunes/modal.php"); ?>

    <script src="js/metodospago.js"></script>
</body>
</html>