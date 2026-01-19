<?php include 'app/Views/layout/header.php'; ?>
<?php include 'app/Views/layout/sidebar.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm border-success">
            <div class="card-header bg-success text-white py-3">
                <h5 class="mb-0"><i class="bi bi-check-circle-fill"></i> Cerrar Oficio: <?php echo e($doc['internal_folio']); ?></h5>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-light border mb-4">
                    <p class="mb-1"><strong>Asunto:</strong> <?php echo e($doc['subject']); ?></p>
                    <p class="mb-0"><strong>Remitente:</strong> <?php echo e($doc['sender_dependency']); ?></p>
                </div>

                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-danger">
                        <?php
                            if($_GET['error'] == 'missing_file') echo "Debe adjuntar el Acuse o Documento de Cierre obligatoriamente.";
                            if($_GET['error'] == 'invalid_file') echo "Tipo de archivo no permitido.";
                            if($_GET['error'] == 'upload_failed') echo "Error al subir el archivo.";
                        ?>
                    </div>
                <?php endif; ?>

                <form action="<?php echo BASE_URL; ?>documents/processClose" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="document_id" value="<?php echo e($doc['id']); ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Tipo de Cierre / Clasificación *</label>
                        <select name="closing_type_id" class="form-select" required>
                            <option value="">Seleccione...</option>
                            <?php foreach($closingTypes as $type): ?>
                                <option value="<?php echo e($type['id']); ?>"><?php echo e($type['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Documento de Cierre / Acuse (Obligatorio) *</label>
                        <input type="file" name="closing_file" class="form-control" required>
                        <div class="form-text text-danger">"Regla de oro: Si no hay evidencia, no se cierra."</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Comentarios Finales / Motivo</label>
                        <textarea name="comment" class="form-control" rows="3" placeholder="Ej. Se entregó respuesta mediante oficio numero..." required></textarea>
                    </div>

                    <div class="d-grid gap-2 mt-4 d-md-flex justify-content-md-end">
                        <a href="<?php echo BASE_URL; ?>documents/view?id=<?php echo e($doc['id']); ?>" class="btn btn-outline-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-success px-4"><i class="bi bi-archive"></i> Confirmar Cierre</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'app/Views/layout/footer.php'; ?>
