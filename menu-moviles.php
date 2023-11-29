<nav class="navbar bg-light">
    <div class="container-fluid">
        <div>
            <a href="main-app.php?acc=directorio-prev" class="btn btn-outline-secondary px-2" title="Volver"><i class="bi bi-arrow-left-circle"></i></a>
            <a href="main-app.php?acc=inicio" class="btn btn-outline-secondary px-2" title="Ir al inicio"><i class="bi bi-house-door"></i></a>
        </div>
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Directorios</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <!-- MENU LATERAL -->
            <aside>
                <div class="d-flex flex-column justify-content-between p-2">
                    <div>
                        <!-- BOTONES MENU CARPETAS -->
                        <div id="menu-carpetas-div" class="d-flex flex-wrap mb-3">
                            <div>
                                <a href="crear-nota.php" class="btn btn-outline-secondary px-2" title="Crear nota"><i class="bi bi-file-plus"></i></a>
                                <button type="button" class="btn btn-outline-secondary px-2" title="Crear directorio" data-bs-toggle="modal" data-bs-target="#crear-directorio-modal"><i class="bi bi-folder-plus"></i></button>
                                <button type="button" class="btn btn-outline-secondary px-2" title="Eliminar directorio" data-bs-toggle="modal" data-bs-target="#eliminar-directorio-modal"><i class="bi bi-folder-x"></i></a>
                            </div>
                        </div>

                        <!-- DIRECTORIOS -->
                        <div id="directorios-div-lnk" class="mb-2">
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
        </div>
    </div>
</nav>