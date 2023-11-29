<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php include("head.php") ?>
    <title>Confirmar eliminación</title>
</head>

<body class="d-flex justify-content-center align-items-center" style="height:100vh">
    <?php
    // ELIMINAR CARPETA
    if (@$_GET['eliminar-directorio']) { ?>
        <div class="text-center text-muted border rounded shadow p-3">
            <h1><i class="bi bi-emoji-dizzy"></i></h1>
            <h4>Desea eliminar la carpeta <br>
                <span style="color:orange"><?php echo @$_POST['nombre'] ?></span><br>
                desea continuar?
            </h4>
            <p><b>Importante:</b> esta acción no tiene reverso.</p>
            <div class="text-center mt-3">
                <a href="main-app.php?acc=directorio&directorio=<?php echo @$_SESSION['directorio'] ?>" class="btn btn-success icon-btn">No</a>
                <a href="main-ctrl.php?acc=eliminar-directorio&directorio=<?php echo @$_POST['nombre'] ?>" class="btn btn-outline-danger icon-btn" title="Si">Si</a>
            </div>
        </div>
    <?php } ?>

    <?php
    // ELIMINAR ARCHIVO
    if (@$_GET['eliminar-archivo']) { ?>
        <div class="text-center text-muted border rounded shadow p-3">
            <h1><i class="bi bi-emoji-dizzy"></i></h1>
            <h4>Se eliminara el archivo <br>
                <span style="color:orange"><?php echo @$_GET['archivo'] ?></span><br>
                desea continuar?
            </h4>
            <p><b>Importante:</b> esta acción no tiene reverso.</p>
            <div class="text-center mt-3">
                <a href="main-app.php?acc=directorio&directorio=<?php echo @$_SESSION['directorio'] ?>" class="btn btn-success icon-btn">No</a>
                <a href="main-ctrl.php?acc=eliminar-archivo&archivo=<?php echo @$_GET['archivo'] ?>" class="btn btn-outline-danger icon-btn" title="Si">Si</a>
            </div>
        </div>
    <?php } ?>
</body>

</html>