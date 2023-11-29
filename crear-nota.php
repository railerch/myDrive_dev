<?php session_start();

// VALIDAR ACCESO
if (@!$_COOKIE['auth']) {
    header("location:main-ctrl.php?acc=sesion-no-autorizada");
}

// GESTION DE VARIABLES DE NOTAS
switch (@$_GET['acc']) {
    case 'editar-nota':
        $ruta       = base64_decode(@$_GET['ruta']);
        $nombre     = isset($_SESSION['txt-nombre']) ? $_SESSION['txt-nombre'] : explode('.', $_GET['nombre'])[0];
        $contenido  = isset($_SESSION['txt-contenido']) ? $_SESSION['txt-contenido'] : fread(fopen($ruta, 'r'), filesize($ruta));
        break;
    default:
        $nombre = $contenido = NULL;
        break;
}
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
    <title>myDrive | Crear nota</title>
</head>

<body class="py-3">
    <div class="container">
        <!-- MENU HEADER -->
        <div class="d-flex justify-content-between align-items-center">
            <!-- ====================================================================== -->
            <!-- PHP -->
            <!-- ====================================================================== -->
            <?php include("nombre-logo.php") ?>
            <!-- ====================================================================== -->
            <!-- ====================================================================== -->
            <div class="col-4 d-flex justify-content-end d-grid gap-2">
                <a href="main-app.php?acc=directorio&directorio=<?php echo $_SESSION['directorio'] ?>" class="btn btn-outline-secondary icon-btn" title="Volver"><i class="bi bi-arrow-left-circle"></i></a>
                <a href="cuenta-usuario.php" class="btn btn-outline-secondary icon-btn" title="Mi cuenta"><i class="bi bi-file-earmark-person"></i></a>
                <a href="main-ctrl.php?acc=cerrar-sesion" class="btn btn-outline-danger icon-btn" title="Cerrar sesion"><i class="bi bi-power"></i></a>
            </div>
        </div>
        <hr>

        <h2 class="text-muted"><i class="bi bi-file-earmark-text"></i> Crear nota</h2>

        <form id="crearArchivoFrm" action="main-ctrl.php?acc=crear-nota" method="post" class="mt-3">
            <div class="input-group">
                <label class="input-group-text">Nombre</label>
                <input type="text" name="nombre" id="" class="form-control" placeholder="Nombre sin caracteres especiales ni Punto (.)" aria-describedby="helpId" value="<?php echo $nombre ?>" required>
            </div>
            <small id="helpId" class="text-info"><b>Importante:</b> cambiar el nombre de la nota generara un nuevo archivo.</small>

            <div class="mb-3">
                <label for="" class="form-label"></label>
                <textarea class="form-control" name="contenido" placeholder="Contenido aqui..." required><?php echo $contenido ?></textarea>
            </div>

            <div class="d-flex justify-content-center justify-content-md-end d-grid gap-1">
                <button type="reset" class="btn btn-secondary"><i class="bi bi-x-circle"></i> Cancelar edición</button>
                <a href="crear-nota.php" class="btn btn-primary"><i class="bi bi-file-earmark-plus"></i> Nueva nota</a>
                <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Guardar</button>
            </div>
        </form>
    </div>
</body>

</html>