<div class="modal fade" id="deleteStudentModal<?php echo e($student->id); ?>" tabindex="-1" aria-labelledby="deleteStudentModalLabel<?php echo e($student->id); ?>" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="<?php echo e(route('students.destroy', $student->id)); ?>" class="modal-content">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>

            <div class="modal-header">
                <h5 class="modal-title" id="deleteStudentModalLabel<?php echo e($student->id); ?>">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                Are you sure you want to delete <strong><?php echo e($student->surname); ?>, <?php echo e($student->given_name); ?></strong>?
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger">Yes, Delete</button>
            </div>
        </form>
    </div>
</div><?php /**PATH C:\laragon\www\kiosk\resources\views/includes/student-delete.blade.php ENDPATH**/ ?>