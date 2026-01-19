<?php include 'app/Views/layout/header.php'; ?>
<?php include 'app/Views/layout/sidebar.php'; ?>

<div class="row">
    <div class="col-md-12">
        <h4 class="mb-4">Panel de Administración</h4>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-people-fill fs-1 text-primary"></i>
                        <h5 class="mt-3">Usuarios</h5>
                        <p class="text-muted">Gestión de usuarios y accesos.</p>
                        <a href="<?php echo BASE_URL; ?>admin/users" class="btn btn-outline-primary">Administrar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-building fs-1 text-success"></i>
                        <h5 class="mt-3">Áreas</h5>
                        <p class="text-muted">Catálogo de áreas y departamentos.</p>
                        <a href="<?php echo BASE_URL; ?>admin/areas" class="btn btn-outline-success">Administrar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <i class="bi bi-archive-fill fs-1 text-secondary"></i>
                        <h5 class="mt-3">Tipos de Cierre</h5>
                        <p class="text-muted">Catálogo de clasificaciones para cierre.</p>
                        <a href="<?php echo BASE_URL; ?>admin/closing_types" class="btn btn-outline-secondary">Administrar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/Views/layout/footer.php'; ?>
