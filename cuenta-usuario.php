<?php session_start();

// VALIDAR ACCESO
if (@!$_COOKIE['auth']) {
    header("location:main-ctrl.php?acc=sesion-no-autorizada");
}

// CONEXION BD
try {
    $conn = new PDO("sqlite:myDrive_db.db");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "ERROR EN CONEXION BD: " . $e->getMessage();
}

// DATOS DEL USUARIO
$usuario = $_SESSION['usuario'];
$stmt = $conn->query("SELECT * from usuarios WHERE usuario = '$usuario'");
$row = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <!-- ====================================================================== -->
    <!-- PHP -->
    <!-- ====================================================================== -->
    <?php include("head.php") ?>
    <!-- ====================================================================== -->
    <!-- ====================================================================== -->
    <title>myDrive | Perfil</title>
</head>

<body class="py-3">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <!-- ====================================================================== -->
            <!-- PHP -->
            <!-- ====================================================================== -->
            <?php include("nombre-logo.php") ?>
            <!-- ====================================================================== -->
            <!-- ====================================================================== -->
            <div class="col-4 d-flex justify-content-end d-grid gap-2">
                <a href="main-app.php?acc=directorio&directorio=<?php echo $_SESSION['directorio'] ?>" class="btn btn-outline-secondary icon-btn" title="Volver"><i class="bi bi-arrow-left-circle"></i></a>
                <a href="main-ctrl.php?acc=cerrar-sesion" class="btn btn-outline-danger icon-btn" title="Cerrar sesion"><i class="bi bi-power"></i></a>
            </div>
        </div>
        <hr>
        <?php
        // NO MOSTRAR FORMULARIO DE CUENTA SI EL USUARIO ES ROOT
        if ($_SESSION['usuario'] != 'root') { ?>
            <form class="mt-3" action="main-ctrl.php?acc=actualizar-datos-cuenta" method="post">
                <div class="d-flex flex-column mb-3">
                    <div class="input-group mb-3">
                        <label class="col-3 col-md-2 col-lg-1 input-group-text">Nombre</label>
                        <input type="text" name="nombre" class="form-control" placeholder="Nombre y apellido" autocomplete="username" value="<?php echo $row['nombre'] ?>" required>
                    </div>

                    <div class="input-group mb-3">
                        <label class="col-3 col-md-2 col-lg-1 input-group-text">Email</label>
                        <input type="mail" name="correo" class="form-control" placeholder="Correo" autocomplete="username" value="<?php echo $row['correo'] ?>" required>
                    </div>

                    <div class="input-group mb-3">
                        <label class="col-3 col-md-2 col-lg-1 input-group-text">Usuario</label>
                        <input type="text" name="usuario" class="form-control" placeholder="Usuario" autocomplete="username" value="<?php echo $row['usuario'] ?>" disabled>
                    </div>

                    <div class="input-group mb-3">
                        <label class="col-3 col-md-2 col-lg-1 input-group-text">Clave</label>
                        <input type="password" name="clave" class="form-control" placeholder="Dejar en blanco para usar la clave actual.">
                    </div>

                    <div class="text-end mt-3">
                        <button type="submit" class="btn btn-success"><i class="bi bi-person-check"></i> Actualizar datos</button>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#eliminar-cuenta-modal"><i class="bi bi-trash"></i> Eliminar cuenta</button>
                    </div>
                </div>

            </form>
        <?php } else { ?>
            <div class="alert alert-warning" role="alert">
                <strong><i class="bi bi-exclamation-diamond-fill"></i> Acceso restringido</strong> <br>Esta cuenta no es administrable.
            </div>

        <?php } ?>
    </div>

    <!-- ====================================================================== -->
    <!-- PHP -->
    <!-- ====================================================================== -->
    <?php include('ventanas-modal.php') ?>
    <!-- ====================================================================== -->
    <!-- ====================================================================== -->
</body>

</html>