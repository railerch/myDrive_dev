<!-- CARGAR ARCHIVO -->
<div class="modal fade" id="cargar-archivo-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document" style="max-width:400px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId"><i class="bi bi-cloud-arrow-up"></i> Subir archivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="main-ctrl.php?acc=subir-archivo" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="file" class="form-control" name="archivo" accept=".jpg, .png, .pdf, .xls, .xlsx, .doc, .docx, .ppt, .pptx" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Subir</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- AGREGAR CARPETA -->
<div class="modal fade" id="crear-directorio-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document" style="max-width:400px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId"><i class="bi bi-folder-plus"></i> Crear carpeta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="main-ctrl.php?acc=crear-directorio" method="post">
                <div class="modal-body">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre del directorio" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Cancelar</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check"></i> Crear</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ELIMINAR CUENTA DE USUARIO -->
<div class="modal fade" id="eliminar-cuenta-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document" style="max-width:400px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId"><i class="bi bi-trash"></i> Eliminar cuenta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="main-ctrl.php?acc=eliminar-cuenta" method="post">
                <div class="modal-body">
                    <p>Está a punto de eliminar su cuenta, esto borrara todos sus archivos y datos de usuario, desea continuar?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-danger">Si</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ELIMINAR CARPETA -->
<div class="modal fade" id="eliminar-directorio-modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document" style="max-width:400px">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId"><i class="bi bi-folder-x"></i> Eliminar carpeta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="confirmar-eliminar.php?eliminar-directorio=true" method="post">
                <div class="modal-body">
                    <div class="mb-3">
                        <select class="form-select form-select-lg" name="nombre">
                            <option selected>Seleccionar</option>
                            <!-- ARBOL DE CARPETAS -->
                            <!-- ====================================================================== -->
                            <!-- PHP -->
                            <!-- ====================================================================== -->
                            <?php
                            foreach ($directorioActual as $archivo) {
                                $fl = $_SESSION['ruta'] . '/' . $archivo;
                                // Mostrar solo si NO es un archivo
                                if (is_dir($fl) && $archivo != '.' && $archivo != '..') { ?>
                                    <option value="<?php echo $archivo ?>"><?php echo $archivo ?></option>
                            <?php }
                            } ?>
                            <!-- ====================================================================== -->
                            <!-- ====================================================================== -->
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x-circle"></i> Cancelar</button>
                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Eliminar</button>
                </div>
            </form>
        </div>
    </div>
</div>