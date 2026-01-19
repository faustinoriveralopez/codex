<?php include 'app/Views/layout/header.php'; ?>
<?php include 'app/Views/layout/sidebar.php'; ?>

<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 text-primary"><i class="bi bi-file-earmark-plus"></i> Registrar Nuevo Oficio</h5>
            </div>
            <div class="card-body p-4">
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>

                <form action="<?php echo BASE_URL; ?>documents/store" method="POST" enctype="multipart/form-data">

                    <h6 class="text-secondary border-bottom pb-2 mb-3">1. Datos de Origen</h6>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Dependencia Remitente *</label>
                            <input type="text" name="sender_dependency" class="form-control" required placeholder="Ej. Secretaría de Finanzas">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nombre del Remitente</label>
                            <input type="text" name="sender_name" class="form-control" placeholder="Ej. Lic. Juan Pérez">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Folio Externo</label>
                            <input type="text" name="external_folio" class="form-control" placeholder="Folio del oficio original">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tipo Documento</label>
                            <select name="doc_type" class="form-select">
                                <option value="OFICIO">Oficio</option>
                                <option value="MEMORANDUM">Memorándum</option>
                                <option value="CIRCULAR">Circular</option>
                                <option value="INVITACION">Invitación</option>
                                <option value="OTRO">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Prioridad</label>
                            <select name="priority" class="form-select">
                                <option value="NORMAL">Normal</option>
                                <option value="ALTA">Alta</option>
                                <option value="URGENTE" class="text-danger fw-bold">Urgente</option>
                            </select>
                        </div>
                    </div>

                    <h6 class="text-secondary border-bottom pb-2 mb-3 mt-4">2. Contenido</h6>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Asunto *</label>
                        <input type="text" name="subject" class="form-control" required placeholder="Resumen breve del asunto">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descripción / Resumen Detallado</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Adjuntar Digitalización (PDF)</label>
                        <input type="file" name="pdf_file" class="form-control" accept="application/pdf">
                        <div class="form-text">Se recomienda subir el documento escaneado para el expediente digital.</div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end border-top pt-3">
                        <a href="<?php echo BASE_URL; ?>dashboard" class="btn btn-outline-secondary me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-save"></i> Registrar y Generar Folio</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'app/Views/layout/footer.php'; ?>
