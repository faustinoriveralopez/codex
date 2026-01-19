<?php include 'app/Views/layout/header.php'; ?>
<?php include 'app/Views/layout/sidebar.php'; ?>

<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Usuarios del Sistema</h5>
                <a href="<?php echo BASE_URL; ?>admin/dashboard" class="btn btn-sm btn-outline-secondary">Volver</a>
            </div>
            <div class="card-body">
                <div class="alert alert-info py-2"><small><i class="bi bi-info-circle"></i> La gestión completa de usuarios (crear/editar/borrar) se implementará en una fase posterior.</small></div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Cargo</th>
                                <th>Rol ID</th>
                                <th>Área ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($users as $user): ?>
                                <tr>
                                    <td><?php echo $user['id']; ?></td>
                                    <td class="fw-bold"><?php echo e($user['name']); ?></td>
                                    <td><?php echo e($user['email']); ?></td>
                                    <td><?php echo e($user['position']); ?></td>
                                    <td><?php echo $user['role_id']; ?></td>
                                    <td><?php echo $user['area_id']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'app/Views/layout/footer.php'; ?>
