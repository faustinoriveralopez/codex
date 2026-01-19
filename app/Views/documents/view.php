<?php include 'app/Views/layout/header.php'; ?>
<?php include 'app/Views/layout/sidebar.php'; ?>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary">Folio: <?php echo e($doc['internal_folio']); ?></h5>
                <span class="badge bg-secondary"><?php echo e($doc['status']); ?></span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Dependencia Remitente</small>
                        <strong><?php echo e($doc['sender_dependency']); ?></strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Remitente</small>
                        <strong><?php echo e($doc['sender_name']); ?></strong>
                    </div>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Asunto</small>
                    <p class="fs-5 fw-bold"><?php echo e($doc['subject']); ?></p>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Descripción / Resumen</small>
                    <p><?php echo nl2br(e($doc['description'])); ?></p>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <small class="text-muted d-block">Área Actual</small>
                        <strong><?php echo e($doc['current_area_name'] ?? 'Sin asignar'); ?></strong>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block">Fecha Recepción</small>
                        <strong><?php echo date('d/m/Y H:i', strtotime($doc['created_at'])); ?></strong>
                    </div>
                </div>

                <hr>
                <h6 class="text-muted">Archivos Adjuntos</h6>
                <?php if(empty($attachments)): ?>
                    <div class="alert alert-light text-center text-muted">Sin archivos adjuntos.</div>
                <?php else: ?>
                    <ul class="list-group">
                        <?php foreach($attachments as $att): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><i class="bi bi-file-pdf text-danger"></i> <?php echo e($att['filename']); ?></span>
                                <a href="<?php echo BASE_URL . $att['filepath']; ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="bi bi-download"></i> Ver / Descargar</a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="card-footer bg-white text-end">
                <a href="<?php echo BASE_URL; ?>documents/reception" class="btn btn-outline-secondary">Volver</a>
                <a href="<?php echo BASE_URL; ?>documents/turnar?id=<?php echo e($doc['id']); ?>" class="btn btn-primary">Turnar / Asignar</a>
            </div>
        </div>

        <!-- Activity Form -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-chat-left-text"></i> Agregar Seguimiento / Actividad</h6>
            </div>
            <div class="card-body">
                <form action="<?php echo BASE_URL; ?>documents/addActivity" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="document_id" value="<?php echo e($doc['id']); ?>">
                    <div class="mb-3">
                        <textarea name="comment" class="form-control" rows="2" placeholder="Describa la actividad realizada..." required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted small">Adjuntar Evidencia (Opcional)</label>
                        <input type="file" name="evidence_file" class="form-control form-control-sm">
                    </div>
                    <button type="submit" class="btn btn-sm btn-success">Registrar Actividad</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0"><i class="bi bi-clock-history"></i> Historial de Movimientos</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush" style="max-height: 600px; overflow-y: auto;">
                    <?php foreach($history as $h): ?>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <small class="fw-bold"><?php echo e($h['action']); ?></small>
                                <small class="text-muted" style="font-size: 0.75rem;"><?php echo date('d/m H:i', strtotime($h['created_at'])); ?></small>
                            </div>
                            <small class="d-block text-primary"><?php echo e($h['user_name']); ?></small>
                            <?php if($h['comment']): ?>
                                <div class="mt-1 p-2 bg-light rounded">
                                    <small class="d-block fst-italic text-muted">"<?php echo e($h['comment']); ?>"</small>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'app/Views/layout/footer.php'; ?>
