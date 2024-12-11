<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createModalLabel">Crear nueva galería</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body">
        <form action="" method="POST">
          <div class="mb-3">
            <label for="gallery_name" class="form-label">Nombre de la galería:</label>
            <input type="text" name="name" id="gallery_name" class="form-control" placeholder="Escribe el nombre" required>
          </div>
          <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('seg'); ?>">
          <input type="hidden" name="action" value="1">
          <button type="submit" class="btn btn-primary">
            <i class="fas fa-plus"></i> Crear
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
