<?php include 'app/Views/layout/header.php'; ?>
<?php include 'app/Views/layout/sidebar.php'; ?>

<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Catálogo: Tipos de Cierre</h5>
                <a href="<?php echo BASE_URL; ?>admin/dashboard" class="btn btn-sm btn-outline-secondary">Volver</a>
            </div>
            <div class="card-body">
                <form action="<?php echo BASE_URL; ?>admin/closing_types/store" method="POST" class="row g-3 mb-4 border-bottom pb-4">
                    <div class="col-md-9">
                        <label class="visually-hidden">Nombre</label>
                        <input type="text" name="name" class="form-control" placeholder="Nuevo tipo de cierre (Ej. Cancelado)" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Agregar</button>
                    </div>
                </form>

                <ul class="list-group">
                    <?php foreach($types as $type): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?php echo e($type['name']); ?>

                            <span class="badge bg-light text-muted rounded-pill">ID: <?php echo $type['id']; ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include 'app/Views/layout/footer.php'; ?>
