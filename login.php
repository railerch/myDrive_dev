<?php session_start();

// NOMBRE Y LOGO DE LA EMPRESA
$config = json_decode(file_get_contents('./config.json'));

$app = $config[0]->app;
$_SESSION['nombre-app']     = $app->name;
$_SESSION['logo-app']       = $app->logo;
$_SESSION['favicon-app']    = $app->favicon;
$_SESSION['titulo-app']     = $app->title;

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php include("head.php") ?>
    <title>myDrive | Iniciar sesión</title>
</head>

<body>
    <div id="loginContainer">
        <form class="formulariosPpl" action="main-ctrl.php?acc=iniciar-sesion" method="post">
            <div class="d-flex flex-column text-muted mb-2">
                <div class="text-center mb-2">
                    <img class="logo" src="<?php echo @$_SESSION['logo-app'] ?>" alt="Logo">
                </div>
                <h4><?php echo @$_SESSION['nombre-app'] ?></h4>
                <p><?php echo @$_SESSION['titulo-app'] ?></p>

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
                    <input type="text" name="usuario" class="form-control" placeholder="Usuario" autocomplete="username" required>
                </div>

                <div class="mb-3">
                    <input type="password" name="clave" class="form-control" placeholder="Clave" autocomplete="current-password" required>
                </div>

                <button type="submit" class="btn btn-outline-primary">Entrar</button>
            </div>

            <a href="crear-cuenta.php"><i class="bi bi-person-plus-fill"></i> Crear cuenta</a>
        </form>
    </div>
</body>

</html>