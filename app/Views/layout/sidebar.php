<div class="sidebar">
    <div class="sidebar-header">
        <h5 class="mb-0">SICOR-OPEO</h5>
        <small class="text-muted" style="font-size: 0.75rem;">Oficina de Pensiones</small>
    </div>

    <a href="<?php echo BASE_URL; ?>dashboard" class="<?php echo (isset($title) && $title == 'Tablero Principal') ? 'active' : ''; ?>">
        <i class="bi bi-speedometer2"></i> Tablero
    </a>

    <!-- Menu Items based on Role -->
    <?php $role = $_SESSION['role'] ?? ''; ?>

    <?php if($role == 'mesa_control' || $role == 'admin' || $role == 'director' || $role == 'jefe_area'): ?>
        <a href="<?php echo BASE_URL; ?>documents/create">
            <i class="bi bi-plus-circle"></i> Registrar Oficio
        </a>
    <?php endif; ?>

    <?php if($role == 'mesa_control' || $role == 'admin'): ?>
        <a href="<?php echo BASE_URL; ?>documents/reception">
            <i class="bi bi-inbox"></i> Mesa de Control
        </a>
    <?php endif; ?>

    <a href="<?php echo BASE_URL; ?>documents/my_tray">
        <i class="bi bi-folder2-open"></i> Bandeja de Entrada
    </a>

    <?php if($role == 'director'): ?>
        <a href="<?php echo BASE_URL; ?>documents/all">
            <i class="bi bi-eye"></i> Monitor Global
        </a>
    <?php endif; ?>

    <a href="<?php echo BASE_URL; ?>logout" class="text-danger mt-4">
        <i class="bi bi-box-arrow-left"></i> Cerrar Sesión
    </a>
</div>

<div class="main-content">
    <div class="top-bar">
        <h4 class="m-0"><?php echo e($title ?? ''); ?></h4>
        <div>
            <i class="bi bi-person-circle me-2"></i>
            <span class="me-2 fw-bold"><?php echo e($_SESSION['user_name'] ?? 'Usuario'); ?></span>
            <span class="badge bg-primary"><?php echo e($_SESSION['area_name'] ?? 'Area'); ?></span>
        </div>
    </div>
