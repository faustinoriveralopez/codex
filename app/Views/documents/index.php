<?php include 'app/Views/layout/header.php'; ?>
<?php include 'app/Views/layout/sidebar.php'; ?>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 text-primary"><i class="bi bi-folder2-open"></i> Mi Bandeja de Entrada</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Folio</th>
                        <th>Asunto</th>
                        <th>Fecha Recepción</th>
                        <th>Estado</th>
                        <th class="text-end pe-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($documents)): ?>
                        <tr><td colspan="5" class="text-center py-5 text-muted">No tienes oficios pendientes.</td></tr>
                    <?php else: ?>
                        <?php foreach($documents as $doc): ?>
                            <tr>
                                <td class="ps-3"><span class="badge bg-light text-dark border"><?php echo e($doc['internal_folio']); ?></span></td>
                                <td>
                                    <div class="fw-bold"><?php echo e($doc['subject']); ?></div>
                                    <small class="text-muted"><?php echo e($doc['sender_dependency']); ?></small>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($doc['created_at'])); ?></td>
                                <td>
                                    <span class="badge bg-primary"><?php echo e($doc['status']); ?></span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="<?php echo BASE_URL; ?>documents/view?id=<?php echo e($doc['id']); ?>" class="btn btn-sm btn-outline-secondary">Ver</a>
                                    <a href="<?php echo BASE_URL; ?>documents/view?id=<?php echo e($doc['id']); ?>" class="btn btn-sm btn-success">Atender</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'app/Views/layout/footer.php'; ?>
