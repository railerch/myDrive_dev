<?php session_start();

// VALIDAR ACCESO
if (@!$_COOKIE['auth']) {
    header("location:main-ctrl.php?acc=sesion-no-autorizada");
}

// ELIMINAR DATA DE ARCHIVOS TXT EDITADOS O CREADOS
$_SESSION['txt-nombre'] = $_SESSION['txt-contenido'] = NULL;

// ARCHIVOS Y CARPETAS DEL REPOSITORIO
// Directorio actual
$_SESSION['directorio'] = isset($_GET['directorio']) ? $_GET['directorio'] : 'Inicio';

// Gestion de enlaces
switch (@$_GET['acc']) {
    case 'inicio':
        // Ruta inicial
        $_SESSION['ruta'] = "repositorio/" . $_SESSION['directorio-usuario'];
        break;
    case 'directorio':
        // Entrar a un directorio
        // =====> Validar que el ultimo directorio no se repita
        @$ultimoDir = array_pop(explode("/", $_SESSION['ruta']));
        if ($ultimoDir != $_GET['directorio'] && $_GET['directorio'] != "Inicio") {
            $_SESSION['ruta'] .= '/' . $_GET['directorio'];
        }
        break;
    case 'directorio-prev':
        $tmp = explode("/", $_SESSION['ruta']);

        // Evitar salir del directorio del usuario al retroceder
        if (count($tmp) > 2) array_splice($tmp, -1, 1);

        // Mostrar nombre del directorio actual al retroceder
        if (count($tmp) > 2) $_SESSION['directorio'] = end($tmp);

        // Armar ruta
        $_SESSION['ruta'] = implode("/", $tmp);
        break;
}

// Escanear directorio actual
$directorioActual   = array_diff(scandir($_SESSION['ruta']), array('.', '..'));
$totalArchivos      = count($directorioActual);

// Se pasan los datos por referencia para que cada vez que se repita el cliclo
// el arreglo de rutas este actualizado.
// La funcion escanea todos los directorios y subdirectorios de forma recursiva
function escaneo_recursivo(&$rutas, &$pesoTotal)
{
    $i = 0;
    while ($i < count($rutas)) {
        $directorio = array_diff(scandir($rutas[$i]), array('.', '..'));
        foreach ($directorio as $archivo) {
            $fl = $rutas[$i] . '/' . $archivo;
            if (is_file($fl)) {
                $pesoTotal += filesize($fl);
            } else {
                if (is_dir($fl)) {
                    array_push($rutas, $fl);
                }
            }
        }
        $i++;
    }
}

// Almacenamiento total utilizado (Recursivo)
function almacenamiento_utilizado()
{
    $rutaUsuario = "repositorio/" . $_SESSION['directorio-usuario'];
    $rutas = [$rutaUsuario];
    $pesoTotal = 0;



    escaneo_recursivo($rutas, $pesoTotal);
    return $pesoTotal;
}

// Dar formato legible al tamaño de un archivo o el almacenamiento indicado
function formatear_almacenamiento($archivo, $pesoTotal = NULL)
{
    clearstatcache();
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    $formattedSize = 0;

    if (is_file($archivo)) {
        $pesoTotal = filesize($archivo);
    }

    for ($i = 0; $pesoTotal >= 1024; $i++) {
        $pesoTotal /= 1024;
        $formattedSize = round($pesoTotal, 2);
    }

    return $formattedSize . ' ' . $units[$i];
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
    <title>myDrive | Repositorio</title>
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
                <a href="cuenta-usuario.php" class="btn btn-outline-secondary icon-btn" title="Mi cuenta"><i class="bi bi-file-earmark-person"></i></a>
                <a href="main-ctrl.php?acc=cerrar-sesion" class="btn btn-outline-danger icon-btn" title="Cerrar sesion"><i class="bi bi-power"></i></a>
            </div>
        </div>
        <hr>

        <!-- FILTRO DE BUSQUEDA -->
        <div class="alert alert-secondary d-flex flex-wrap justify-content-between align-items-center p-2 mb-2" role="alert">
            <div class="col-12 col-md-5">
                <h5 class="text-muted m-0"><?php echo strtoupper($_SESSION['directorio']) ?></h5>
            </div>
            <div class="col-12 col-md-6 col-lg-4 mt-2 mt-md-0">
                <div class="input-group">
                    <input type="text" class="form-control" name="" id="buscar-archivo-inp" placeholder="Buscar archivo">
                    <button id="limpiar-filtro-btn" class="btn btn-secondary" title="Limpiar filtro de busqueda"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        </div>

        <!-- AVISO DIRECTORIO DUPLICADO -->
        <!-- ====================================================================== -->
        <!-- PHP -->
        <!-- ====================================================================== -->
        <!-- Directorio duplicado -->
        <?php if (@$duplicado) { ?>
            <div id="aviso-usuario" class="alert alert-warning" role="alert">
                El directorio ya existe.
            </div>

            <script type="text/javascript">
                setTimeout(() => {
                    document.getElementById("aviso-usuario").style.display = "none";
                }, 2000)
            </script>
        <?php }
        $duplicado = NULL; ?>

        <!-- Directorio lleno -->
        <?php if (@$dirLleno) { ?>
            <div id="aviso-usuario" class="alert alert-danger" role="alert">
                El directorio no esta vacio.
            </div>

            <script type="text/javascript">
                setTimeout(() => {
                    document.getElementById("aviso-usuario").style.display = "none";
                }, 2000)
            </script>
        <?php }
        $dirLleno = NULL; ?>

        <!-- Directorio creado -->
        <?php if (@$dirCreado) { ?>
            <div id="aviso-usuario" class="alert alert-success" role="alert">
                Directorio creado correctamente.
            </div>

            <script type="text/javascript">
                setTimeout(() => {
                    document.getElementById("aviso-usuario").style.display = "none";
                }, 2000)
            </script>
        <?php }
        $dirCreado = NULL; ?>

        <!-- Archivo cargado o eliminado -->
        <?php if (@$archivoCargado || @$archivoError) { ?>
            <div id="aviso-usuario" class="alert alert-success" role="alert">
                <?php echo $mensaje ?>
            </div>

            <script type="text/javascript">
                setTimeout(() => {
                    document.getElementById("aviso-usuario").style.display = "none";
                }, 2000)
            </script>
        <?php }
        $archivoCargado = $archivoError = NULL; ?>
        <!-- ====================================================================== -->
        <!-- ====================================================================== -->

        <!-- MENU LATERAL DESPLEGABLE PARA MOVILES  -->
        <div id="menu-lateral-moviles" class="mb-2" style="display:none">
            <?php include('menu-moviles.php')
            ?>
        </div>

        <!-- CONTENEDOR PRINCIPAL -->
        <div id="contenedor-principal" class="d-flex d-grid gap-2 mb-3">
            <!-- MENU LATERAL -->
            <aside id="menu-lateral-desktop" class="col-2 bg-light" style="display:none">
                <div class="d-flex flex-column justify-content-between p-2">
                    <!-- MENU DE CARPETAS / LISTA DE DIRECTORIO -->
                    <div>
                        <!-- BOTONES MENU CARPETAS -->
                        <div id="menu-carpetas-div" class="d-flex flex-wrap mb-3">
                            <div>
                                <a href="crear-nota.php" class="btn btn-outline-secondary px-2" title="Crear nota"><i class="bi bi-file-plus"></i></a>
                                <button type="button" class="btn btn-outline-secondary px-2" title="Crear directorio" data-bs-toggle="modal" data-bs-target="#crear-directorio-modal"><i class="bi bi-folder-plus"></i></button>
                                <button type="button" class="btn btn-outline-secondary px-2" title="Eliminar directorio" data-bs-toggle="modal" data-bs-target="#eliminar-directorio-modal"><i class="bi bi-folder-x"></i></a>
                            </div>
                            <div class="text-lg-end  mt-1 mt-xl-0">
                                <a href="main-app.php?acc=directorio-prev" class="btn btn-outline-secondary px-2" title="Volver"><i class="bi bi-arrow-left-circle"></i></a>
                                <a href="main-app.php?acc=inicio" class="btn btn-outline-secondary px-2" title="Ir al inicio"><i class="bi bi-house-door"></i></a>
                            </div>
                        </div>

                        <!-- DIRECTORIOS -->
                        <div id="directorios-div-lnk" class="mb-3">
                            <!-- ARBOL DE CARPETAS -->
                            <!-- ====================================================================== -->
                            <!-- PHP -->
                            <!-- ====================================================================== -->
                            <?php
                            $dir = 0;
                            foreach ($directorioActual as $directorio) {
                                $fl = $_SESSION['ruta'] . '/' . $directorio;
                                if (is_dir($fl)) {
                                    // Mostrar solo si NO ES un archivo
                            ?>
                                    <p class="m-0">
                                        <a class="carpeta-lnk" href="main-app.php?acc=directorio&directorio=<?php echo $directorio ?>"><i class="bi bi-folder" style="font-size:1.5em"></i> <?php echo $directorio ?></a>
                                    </p>
                            <?php $dir++;
                                }
                            }
                            if ($dir == 0) $sinDirectorios = "Sin directorios";
                            ?>

                            <p>
                                <?php if (@$sinDirectorios) echo $sinDirectorios; ?>
                            </p>
                            <!-- ====================================================================== -->
                            <!-- ====================================================================== -->
                        </div>
                    </div>

                    <!-- TOTAL USADO -->
                    <div class="alert alert-info m-0 p-1">
                        <small>
                            <b>Almacenamiento usado</b><br>
                            <span><?php echo formatear_almacenamiento(NULL, almacenamiento_utilizado()); ?></span>
                        </small>
                    </div>
                </div>
            </aside>

            <!-- TABLA DE ARCHIVOS -->
            <main class="col-12 col-lg-10">
                <div id="archivos-tbl-div">
                    <div class="table-responsive">
                        <table id="archivos-tbl" class="table table-hover" style="vertical-align:middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:5%">#</th>
                                    <th style="width:70%">Archivo</th>
                                    <th style="width:10%">Tamaño</th>
                                    <th class="text-center" style="width:15%">Acc</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- ARCHIVOS DE LA CARPETA SELECCIONADA -->
                                <!-- ====================================================================== -->
                                <!-- PHP -->
                                <!-- ====================================================================== -->
                                <?php
                                $cont = 0;
                                foreach ($directorioActual as $archivo) {
                                    $fl = $_SESSION['ruta'] . '/' . $archivo;
                                    // Mostrar solo SI ES un archivo
                                    if (is_file($fl)) {
                                ?>
                                        <tr>
                                            <td><?php echo $cont + 1 ?></td>
                                            <td><?php echo $archivo ?></td>
                                            <td><?php echo formatear_almacenamiento($fl) ?></td>
                                            <td class="d-flex flex-wrap justify-content-center align-items-center d-grid gap-2">
                                                <?php
                                                // Determinar si el archivo es TXT
                                                if (str_contains($archivo, '.txt')) { ?>
                                                    <a href="crear-nota.php?acc=editar-nota&nombre=<?php echo $archivo ?>&ruta=<?php echo base64_encode($fl) ?>" title="Editar archivo"><i class="bi bi-pencil-square" style="font-size:1.5em"></i></a>
                                                <?php } ?>
                                                <a href="<?php echo $fl ?>" title="Descargar" download><i class="bi bi-download" style="font-size:1.5em"></i></a>
                                                <a href="confirmar-eliminar.php?eliminar-archivo=true&archivo=<?php echo $archivo ?>" title="Eliminar"><i class="bi bi-eraser" style="font-size:1.5em; color:red"></i></a>
                                            </td>
                                        </tr>
                                <?php $cont++;
                                    }
                                }
                                if ($cont == 0) $sinArchivos = "Sin archivos para mostrar";
                                ?>
                                <!-- ====================================================================== -->
                                <!-- ====================================================================== -->
                            </tbody>
                        </table>
                        <?php if (@$sinArchivos) echo $sinArchivos; ?>
                        <!-- AVISO USUARIO -->
                        <div id="aviso-usuario" class="alert alert-primary mt-3" role="alert" style="display:none">
                            <i class='bi bi-emoji-frown'></i> Sin coincidencias.
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <!-- SUBIR ARCHIVO -->
        <div class="text-end mb-2">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#cargar-archivo-modal"><i class="bi bi-cloud-arrow-up"></i> Cargar archivo</button>
        </div>

        <!-- ====================================================================== -->
        <!-- PHP -->
        <!-- ====================================================================== -->
        <?php include('ventanas-modal.php') ?>
        <!-- ====================================================================== -->
        <!-- ====================================================================== -->

    </div>
</body>

</html>