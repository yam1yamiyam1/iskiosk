

<?php $__env->startSection('content'); ?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Students</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <button type="button" class="btn btn-primary d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#addStudentModal"  style="background-color: #720100;">
                    <i class="fas fa-user-plus"></i> Add New Student
                </button>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            <div class="col-12 table-responsive">
                <?php if($students->isEmpty()): ?>
                    <div class="alert alert-warning">No students available.</div>
                <?php else: ?>
                    <table id="students-table" class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>ID Number</th>
                                <th>Full Name</th>
                                <th>Year Level</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th style="width: 120px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($index + 1); ?></td>
                                <td><?php echo e($student->id_number); ?></td>
                                <td><?php echo e($student->surname); ?>, <?php echo e($student->given_name); ?> <?php echo e($student->middle_name); ?></td>
                                <td><?php echo e($student->year_level); ?></td>
                                <td><?php echo e($student->email ?? '-'); ?></td>
                                <td><?php echo e($student->contact_number ?? '-'); ?></td>
                                <td>
                                    <a href="#" class="text-primary me-2" data-bs-toggle="modal" data-bs-target="#editStudentModal<?php echo e($student->id); ?>">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#deleteStudentModal<?php echo e($student->id); ?>">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php echo $__env->make('includes.student-add', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php echo $__env->make('includes.student-edit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('includes.student-delete', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('page-scripts'); ?>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#students-table').DataTable({
            pageLength: 10,
            lengthMenu: [[5,10,25,50,-1],[5,10,25,50,'All']],
            columnDefs: [{ orderable: false, targets: 6 }],
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search students..."
            },
            autoWidth: false
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.tabler', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\kiosk\resources\views\admin\student.blade.php ENDPATH**/ ?>