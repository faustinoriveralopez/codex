<?php include 'app/Views/layout/header.php'; ?>
<?php include 'app/Views/layout/sidebar.php'; ?>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 text-primary"><i class="bi bi-shield-check"></i> Auditoría y Bitácora Global</h5>
    </div>
    <div class="card-body">
        <form method="GET" action="<?php echo BASE_URL; ?>reports/audit" class="row g-3 align-items-end mb-4 border-bottom pb-4">
            <div class="col-md-3">
                <label class="form-label">Fecha Inicio</label>
                <input type="date" name="start_date" class="form-control" value="<?php echo $filters['start_date']; ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Fecha Fin</label>
                <input type="date" name="end_date" class="form-control" value="<?php echo $filters['end_date']; ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Usuario</label>
                <select name="user_id" class="form-select">
                    <option value="">Todos</option>
                    <?php foreach($users as $u): ?>
                        <option value="<?php echo $u['id']; ?>" <?php echo ($filters['user_id'] == $u['id']) ? 'selected' : ''; ?>>
                            <?php echo e($u['name']); ?>

                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Acción</label>
                <input type="text" name="action" class="form-control" placeholder="Ej. CIERRE, TURNADO" value="<?php echo e($filters['action']); ?>">
            </div>
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search"></i></button>
            </div>
        </form>

        <div class="d-flex justify-content-end mb-2">
            <a href="<?php echo BASE_URL; ?>reports/exportAudit?<?php echo http_build_query($_GET); ?>" class="btn btn-sm btn-outline-success"><i class="bi bi-file-earmark-excel"></i> Exportar CSV</a>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Fecha/Hora</th>
                        <th>Folio</th>
                        <th>Acción</th>
                        <th>Usuario</th>
                        <th>Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($logs)): ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">No se encontraron registros.</td></tr>
                    <?php else: ?>
                        <?php foreach($logs as $log): ?>
                            <tr>
                                <td style="width: 150px;"><?php echo date('d/m/Y H:i', strtotime($log['created_at'])); ?></td>
                                <td><a href="<?php echo BASE_URL; ?>documents/view?id=<?php echo $log['document_id']; ?>" target="_blank"><?php echo e($log['internal_folio']); ?></a></td>
                                <td><span class="badge bg-secondary"><?php echo e($log['action']); ?></span></td>
                                <td><?php echo e($log['user_name']); ?></td>
                                <td class="text-truncate" style="max-width: 300px;" title="<?php echo e($log['comment']); ?>">
                                    <?php echo e($log['comment']); ?>
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
