<!DOCTYPE html>
<html lang="es">

<body class="login-bg">
    <?php require_once("comunes/encabezado.php"); ?>

    <div>
        <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
            <div class="card border-1 shadow-lg rounded-5" style="max-width: 500px; width: 100%;">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold text-dashboard">INICIAR SESIÓN</h2>
                        <p class="text-uppercase" style="color: #9E5C31;">Ingresa tus Datos</p>
                        <hr class="border border-secundary">
                    </div>
                    <form class="needs-validation small" action="?pagina=login" method="POST" novalidate style="color: #9E5C31;">
                        <?php if (!empty($mensaje)) { ?>
                            <div class="alert alert-danger text-center">
                                <?php echo $mensaje; ?>
                            </div>
                        <?php } ?>

                        <div class="mb-4">
                            <label for="username" class="form-label fw-bold text-uppercase">
                                <i class="bi bi-person-vcard"></i> Usuario
                            </label>
                            <input type="text" class="form-control" id="username" name="username" required>
                            <div class="invalid-feedback">
                                Por favor, ingresa tu usuario.
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold text-uppercase">
                                <i class="bi bi-key-fill"></i> Contraseña
                            </label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="invalid-feedback">
                                Por favor, ingresa tu contraseña.
                            </div>
                        </div>
                        <div class="d-grid mt-5">
                            <button class="btn btn-crear" type="submit">INGRESAR</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="js/login.js"></script>
    </div>
</body>

</html>