<?php include 'app/Views/layout/header.php'; ?>
<?php include 'app/Views/layout/sidebar.php'; ?>

<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card shadow-sm border-success">
            <div class="card-header bg-success text-white py-3">
                <h5 class="mb-0"><i class="bi bi-send-plus"></i> Registrar Oficio de Salida</h5>
            </div>
            <div class="card-body p-4">
                <?php if(isset($related_incoming)): ?>
                    <div class="alert alert-info d-flex align-items-center">
                        <i class="bi bi-reply-fill fs-4 me-3"></i>
                        <div>
                            <strong>Respondiendo al Oficio: <?php echo e($related_incoming['internal_folio']); ?></strong><br>
                            <small><?php echo e($related_incoming['subject']); ?></small>
                        </div>
                    </div>
                <?php endif; ?>

                <form action="<?php echo BASE_URL; ?>outgoing/store" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="related_incoming_id" value="<?php echo isset($related_incoming) ? $related_incoming['id'] : ''; ?>">

                    <h6 class="text-secondary border-bottom pb-2 mb-3">1. Datos del Destinatario</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nombre Destinatario *</label>
                            <input type="text" name="recipient_name" class="form-control" required placeholder="Ej. Lic. María López">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Dependencia / Área *</label>
                            <input type="text" name="recipient_dependency" class="form-control" placeholder="Ej. Secretaría de Administración">
                        </div>
                    </div>

                    <h6 class="text-secondary border-bottom pb-2 mb-3 mt-4">2. Contenido</h6>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Asunto *</label>
                        <input type="text" name="subject" class="form-control" required placeholder="Asunto del oficio de salida">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Archivo PDF (Firmado/Final)</label>
                        <input type="file" name="pdf_file" class="form-control" accept="application/pdf">
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end border-top pt-3">
                        <a href="<?php echo BASE_URL; ?>outgoing/index" class="btn btn-outline-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-success px-4"><i class="bi bi-save"></i> Registrar y Generar Folio</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'app/Views/layout/footer.php'; ?>
