<div class="modal fade" id="deleteUserModal<?php echo e($user['id']); ?>" tabindex="-1" aria-labelledby="deleteUserModalLabel<?php echo e($user['id']); ?>" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="<?php echo e(route('users.destroy', $user['id'])); ?>" class="modal-content">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel<?php echo e($user['id']); ?>">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete <strong><?php echo e($user['fname']); ?> <?php echo e($user['lname']); ?></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Yes, Delete</button>
            </div>
        </form>
    </div>
</div>
<?php /**PATH C:\laragon\www\kiosk\resources\views\includes\userdelete.blade.php ENDPATH**/ ?>