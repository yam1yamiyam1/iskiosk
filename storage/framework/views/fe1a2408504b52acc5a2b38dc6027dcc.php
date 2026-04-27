<div class="modal fade" id="editDepartmentModal<?php echo e($department->id); ?>" tabindex="-1" aria-labelledby="editDepartmentModalLabel<?php echo e($department->id); ?>" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="<?php echo e(route('departments.update', $department->id)); ?>" class="modal-content">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>
            <div class="modal-header">
                <h5 class="modal-title" id="editDepartmentModalLabel<?php echo e($department->id); ?>">Edit Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input name="name" type="text" class="form-control" value="<?php echo e($department->name); ?>" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>
<?php /**PATH C:\laragon\www\kiosk\resources\views/includes/department-edit.blade.php ENDPATH**/ ?>