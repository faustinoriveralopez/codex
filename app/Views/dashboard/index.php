<?php include 'app/Views/layout/header.php'; ?>
<?php include 'app/Views/layout/sidebar.php'; ?>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Bienvenido, <?php echo e($user['user_name']); ?></h5>
                <p class="card-text text-muted">
                    Rol: <?php echo ucfirst(e($user['role'])); ?> | Área: <?php echo e($user['area_name']); ?>
                </p>
                <hr>
                <p>Seleccione una opción del menú lateral para comenzar.</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Quick Stats -->
    <div class="col-md-3 mb-3">
        <div class="card card-stat shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Total Pendientes</h6>
                <h3 class="fw-bold"><?php echo $stats['pending']; ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card card-stat shadow-sm" style="border-left-color: #ffc107;">
            <div class="card-body">
                <h6 class="text-muted">Próximos a Vencer</h6>
                <h3 class="fw-bold text-warning"><?php echo $stats['yellow']; ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card card-stat shadow-sm" style="border-left-color: #dc3545;">
            <div class="card-body">
                <h6 class="text-muted">Vencidos</h6>
                <h3 class="fw-bold text-danger"><?php echo $stats['red']; ?></h3>
            </div>
        </div>
    </div>
</div>

<!-- Alerts Section -->
<?php if(!empty($criticalDocs)): ?>
<div class="row mt-4">
    <div class="col-12">
        <div class="card shadow-sm border-danger">
            <div class="card-header bg-danger text-white">
                <h6 class="mb-0"><i class="bi bi-exclamation-triangle-fill"></i> Alertas: Atención Inmediata Requerida</h6>
            </div>
            <div class="list-group list-group-flush">
                <?php foreach($criticalDocs as $doc): ?>
                    <a href="<?php echo BASE_URL; ?>documents/view?id=<?php echo $doc['id']; ?>" class="list-group-item list-group-item-action">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1 text-danger fw-bold"><?php echo e($doc['internal_folio']); ?>: <?php echo e($doc['subject']); ?></h6>
                            <small class="text-muted">Vence: <?php echo date('d/m/Y', strtotime($doc['deadline_date'])); ?></small>
                        </div>
                        <small class="mb-1 text-muted">
                            <span class="badge <?php echo ($doc['alert_level'] == 'ROJO') ? 'bg-danger' : 'bg-warning text-dark'; ?>">
                                <?php echo $doc['alert_level']; ?>
                            </span>
                            En: <?php echo e($doc['current_area_name'] ?? 'Mesa de Control'); ?>
                        </small>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'app/Views/layout/footer.php'; ?>
