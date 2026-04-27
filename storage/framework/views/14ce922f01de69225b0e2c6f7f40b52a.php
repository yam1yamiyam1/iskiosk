<div class="modal fade" id="editDocumentTypeModal<?php echo e($type->id); ?>" tabindex="-1" aria-labelledby="editDocumentTypeModalLabel<?php echo e($type->id); ?>" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="<?php echo e(route('types.update', $type->id)); ?>" class="modal-content">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="modal-header">
                <h5 class="modal-title" id="editDocumentTypeModalLabel<?php echo e($type->id); ?>">Edit Document Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input name="name" type="text" class="form-control" value="<?php echo e($type->name); ?>" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div><?php /**PATH C:\laragon\www\kiosk\resources\views/includes/documenttype-edit.blade.php ENDPATH**/ ?>