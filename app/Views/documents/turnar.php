<?php include 'app/Views/layout/header.php'; ?>
<?php include 'app/Views/layout/sidebar.php'; ?>

<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 text-primary"><i class="bi bi-arrow-right-circle"></i> Turnar Oficio: <?php echo e($doc['internal_folio']); ?></h5>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-light border">
                    <p class="mb-1"><strong>Asunto:</strong> <?php echo e($doc['subject']); ?></p>
                    <p class="mb-0"><strong>Remitente:</strong> <?php echo e($doc['sender_dependency']); ?></p>
                </div>

                <form action="<?php echo BASE_URL; ?>documents/processTurnar" method="POST">
                    <input type="hidden" name="document_id" value="<?php echo e($doc['id']); ?>">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Área Responsable</label>
                        <select name="area_id" class="form-select" required>
                            <option value="">Seleccione Área...</option>
                            <?php foreach($areas as $area): ?>
                                <option value="<?php echo e($area['id']); ?>"><?php echo e($area['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">El oficio aparecerá en la bandeja del área seleccionada.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Instrucción / Comentario</label>
                        <textarea name="comment" class="form-control" rows="3" placeholder="Ej. Para su atención y seguimiento..."></textarea>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">Confirmar Turnado</button>
                        <a href="<?php echo BASE_URL; ?>documents/reception" class="btn btn-link text-muted">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'app/Views/layout/footer.php'; ?>
