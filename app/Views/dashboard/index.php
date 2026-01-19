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
    <!-- Quick Stats Placeholders -->
    <div class="col-md-3 mb-3">
        <div class="card card-stat shadow-sm">
            <div class="card-body">
                <h6 class="text-muted">Mis Pendientes</h6>
                <h3 class="fw-bold">0</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card card-stat shadow-sm" style="border-left-color: #ffc107;">
            <div class="card-body">
                <h6 class="text-muted">Próximos a Vencer</h6>
                <h3 class="fw-bold">0</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card card-stat shadow-sm" style="border-left-color: #198754;">
            <div class="card-body">
                <h6 class="text-muted">Atendidos Mes</h6>
                <h3 class="fw-bold">0</h3>
            </div>
        </div>
    </div>
</div>

<?php include 'app/Views/layout/footer.php'; ?>
