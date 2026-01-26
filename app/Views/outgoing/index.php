<?php include 'app/Views/layout/header.php'; ?>
<?php include 'app/Views/layout/sidebar.php'; ?>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 text-success"><i class="bi bi-send"></i> Oficios de Salida</h5>
        <a href="<?php echo BASE_URL; ?>outgoing/create" class="btn btn-success btn-sm"><i class="bi bi-plus-lg"></i> Registrar Salida</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Folio Salida</th>
                        <th>Destinatario</th>
                        <th>Asunto</th>
                        <th>Referencia (Entrada)</th>
                        <th>Archivo</th>
                        <th class="text-end pe-3">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($docs)): ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">No hay oficios de salida registrados.</td></tr>
                    <?php else: ?>
                        <?php foreach($docs as $doc): ?>
                            <tr>
                                <td class="ps-3"><span class="badge bg-light text-dark border"><?php echo e($doc['folio']); ?></span></td>
                                <td>
                                    <div class="fw-bold"><?php echo e($doc['recipient_name']); ?></div>
                                    <small class="text-muted"><?php echo e($doc['recipient_dependency']); ?></small>
                                </td>
                                <td><?php echo e($doc['subject']); ?></td>
                                <td>
                                    <?php if($doc['incoming_folio']): ?>
                                        <a href="<?php echo BASE_URL; ?>documents/view?id=<?php echo $doc['related_incoming_id']; ?>" class="badge bg-info text-dark text-decoration-none" target="_blank">
                                            <i class="bi bi-link-45deg"></i> <?php echo e($doc['incoming_folio']); ?>

                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($doc['filepath']): ?>
                                        <a href="<?php echo BASE_URL . $doc['filepath']; ?>" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="bi bi-file-pdf"></i></a>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-3"><?php echo date('d/m/Y', strtotime($doc['created_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'app/Views/layout/footer.php'; ?>
