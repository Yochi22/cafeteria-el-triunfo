<!DOCTYPE html>
<html lang="es">
<?php require_once("comunes/encabezado.php"); ?>

<body class="login-bg">
    <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
        <div class="card border-1 shadow-lg rounded-5" style="max-width: 500px; width: 100%;">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <h2 class="fw-bold text-dashboard">INICIAR SESIÓN</h2>
                    <p class="text-uppercase" style="color: #9E5C31;">Ingresa tus Datos</p>
                    <hr class="border border-secondary">
                </div>

                <form id="f" class="small" autocomplete="off" style="color: #9E5C31;">
                    <input type="hidden" id="accion" name="accion" value="login">

                    <div class="mb-4">
                        <label for="username" class="form-label fw-bold text-uppercase">
                            <i class="bi bi-person-vcard"></i> Usuario
                        </label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Ingresa tu usuario">
                        <span id="susername" class="small text-danger"></span>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-bold text-uppercase">
                            <i class="bi bi-key-fill"></i> Contraseña
                        </label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Ingresa tu contraseña">
                        <span id="spassword" class="small text-danger"></span>
                    </div>

                    <div class="d-grid mt-5">
                        <button class="btn btn-crear py-2 fw-semibold rounded-3 w-100" type="submit" id="btnIngresar">INGRESAR</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Llamada de archivos JavaScript -->
    <script src="js/login.js"></script>
    <script src="js/sweetalert.js"></script>
</body>

</html>