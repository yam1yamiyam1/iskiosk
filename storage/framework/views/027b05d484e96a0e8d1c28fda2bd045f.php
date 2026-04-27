<div class="modal fade" id="deleteDepartmentModal<?php echo e($department->id); ?>" tabindex="-1" aria-labelledby="deleteDepartmentModalLabel<?php echo e($department->id); ?>" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="<?php echo e(route('departments.destroy', $department->id)); ?>" class="modal-content">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <div class="modal-header">
                <h5 class="modal-title" id="deleteDepartmentModalLabel<?php echo e($department->id); ?>">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete <strong><?php echo e($department->name); ?></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Yes, Delete</button>
            </div>
        </form>
    </div>
</div>
<?php /**PATH C:\laragon\www\kiosk\resources\views/includes/department-delete.blade.php ENDPATH**/ ?>