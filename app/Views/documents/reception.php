<?php include 'app/Views/layout/header.php'; ?>
<?php include 'app/Views/layout/sidebar.php'; ?>

<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h5 class="mb-0 text-primary"><i class="bi bi-inbox"></i> Mesa de Control - Todos los Oficios</h5>
        <a href="<?php echo BASE_URL; ?>documents/create" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Registrar Nuevo</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Folio</th>
                        <th>Asunto / Descripción</th>
                        <th>Remitente</th>
                        <th>Prioridad</th>
                        <th>Estado</th>
                        <th>Ubicación</th>
                        <th class="text-end pe-3">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($documents)): ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted">No hay documentos registrados en el sistema.</td></tr>
                    <?php else: ?>
                        <?php foreach($documents as $doc): ?>
                            <tr>
                                <td class="ps-3"><span class="badge bg-light text-dark border"><?php echo e($doc['internal_folio']); ?></span></td>
                                <td>
                                    <div class="fw-bold text-truncate" style="max-width: 200px;"><?php echo e($doc['subject']); ?></div>
                                    <small class="text-muted text-truncate d-block" style="max-width: 200px;"><?php echo e($doc['description']); ?></small>
                                </td>
                                <td>
                                    <small class="d-block fw-bold"><?php echo e($doc['sender_dependency']); ?></small>
                                    <small class="text-muted"><?php echo e($doc['sender_name']); ?></small>
                                </td>
                                <td>
                                    <?php
                                        $badgeClass = 'bg-secondary';
                                        if($doc['priority'] == 'URGENTE') $badgeClass = 'bg-danger';
                                        if($doc['priority'] == 'ALTA') $badgeClass = 'bg-warning text-dark';
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?>"><?php echo e($doc['priority']); ?></span>
                                </td>
                                <td>
                                    <span class="badge <?php echo ($doc['status']=='RECIBIDO')?'bg-info text-dark':'bg-success'; ?>">
                                        <?php echo e($doc['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <small><?php echo e($doc['current_area_name'] ?? 'Sin asignar'); ?></small>
                                </td>
                                <td class="text-end pe-3">
                                    <div class="btn-group">
                                        <a href="<?php echo BASE_URL; ?>documents/view?id=<?php echo e($doc['id']); ?>" class="btn btn-sm btn-light border" title="Ver Detalles"><i class="bi bi-eye"></i></a>
                                        <a href="<?php echo BASE_URL; ?>documents/turnar?id=<?php echo e($doc['id']); ?>" class="btn btn-sm btn-outline-primary" title="Turnar / Asignar">
                                            <i class="bi bi-arrow-right-circle"></i> Turnar
                                        </a>
                                    </div>
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
