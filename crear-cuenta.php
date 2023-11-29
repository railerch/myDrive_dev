<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php include("head.php") ?>
    <title>myDrive | Crear cuenta</title>
</head>

<body>
    <div id="loginContainer">
        <form class="formulariosPpl" action="main-ctrl.php?acc=crear-cuenta" method="post">
            <div class="d-flex flex-column mb-3">
                <p class="icono-grande m-0"><i class="bi bi-person-plus-fill"></i></p>
                <p>Nuevo usuario</p>

                <!-- MENSAJE DE ERROR -->
                <!-- ====================================================================== -->
                <!-- PHP -->
                <!-- ====================================================================== -->
                <?php
                if (isset($_SESSION['error'])) { ?>
                    <div class="alert alert-danger p-1" role="alert">
                        <small>
                            <?php echo $_SESSION['error'];
                            $_SESSION['error'] = NULL; ?>
                        </small>
                    </div>

                    <script>
                        setTimeout(function() {
                            document.querySelector("div.alert-danger")
                                .style.display = "none";
                        }, 3000)
                    </script>
                <?php } ?>
                <!-- ====================================================================== -->
                <!-- ====================================================================== -->

                <div class="mb-3">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre y apellido" autocomplete="username" required>
                </div>

                <div class="mb-3">
                    <input type="mail" name="correo" class="form-control" placeholder="Correo" autocomplete="username" required>
                </div>

                <div class="mb-3">
                    <input type="text" name="usuario" class="form-control" placeholder="Usuario" autocomplete="username" required>
                </div>

                <div class="mb-3">
                    <input type="password" name="clave" class="form-control" placeholder="Clave" autocomplete="current-password" required>
                </div>

                <button type="submit" class="btn btn-outline-primary">Crear cuenta</button>
            </div>

            <a href="login.php"><i class="bi bi-arrow-left-circle"></i> Volver</a>
        </form>
    </div>
</body>

</html>