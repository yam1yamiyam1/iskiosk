
<?php $__env->startSection('content'); ?>
<style>
.no-flex {
    display: block !important;
    align-items: initial !important;
}

.filter-buttons {
    margin-bottom: 1rem;
}

.filter-buttons .btn {
    margin-right: .5rem;
    background-color: #720100;
    border-color: #720100;
    color: #fff;
    transition: all 0.2s ease-in-out;
}

.filter-buttons .btn:hover {
    background-color: #8b1a1a;
    border-color: #8b1a1a;
}

.filter-buttons .btn.active {
    background-color: #b21c1c !important;
    border-color: #b21c1c !important;
    color: #fff !important;
    box-shadow: 0 0 10px rgba(178, 28, 28, 0.4);
}
</style>

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Activity Logs</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="filter-buttons">
            <?php
                $query = request()->except('module');
            ?>

            <a href="<?php echo e(route('activity-logs.index', array_merge($query, ['module' => null]))); ?>"
            class="btn btn-primary d-sm-inline-block <?php echo e(request('module') == null ? 'active' : ''); ?>">
                <i class="fa-solid fa-list"></i> <small>All</small>
            </a>

            <a href="<?php echo e(route('activity-logs.index', array_merge($query, ['module' => 'authentications']))); ?>"
            class="btn btn-primary d-sm-inline-block <?php echo e(request('module') == 'authentications' ? 'active' : ''); ?>">
                <i class="fa-solid fa-right-to-bracket"></i> <small>Authentications</small>
            </a>

            <a href="<?php echo e(route('activity-logs.index', array_merge($query, ['module' => 'accounts']))); ?>"
            class="btn btn-primary d-sm-inline-block <?php echo e(request('module') == 'accounts' ? 'active' : ''); ?>">
                <i class="fa-solid fa-user"></i> <small>Accounts</small>
            </a>

            <a href="<?php echo e(route('activity-logs.index', array_merge($query, ['module' => 'users']))); ?>"
            class="btn btn-primary d-sm-inline-block <?php echo e(request('module') == 'users' ? 'active' : ''); ?>">
                <i class="fa-solid fa-users-gear"></i> <small>Users</small>
            </a>

            <a href="<?php echo e(route('activity-logs.index', array_merge($query, ['module' => 'documents']))); ?>"
            class="btn btn-primary d-sm-inline-block <?php echo e(request('module') == 'documents' ? 'active' : ''); ?>">
                <i class="fa-solid fa-file"></i> <small>Documents</small>
            </a>

            <a href="<?php echo e(route('activity-logs.index', array_merge($query, ['module' => 'departments']))); ?>"
            class="btn btn-primary d-sm-inline-block <?php echo e(request('module') == 'departments' ? 'active' : ''); ?>">
                <i class="fa-solid fa-building"></i> <small>Departments</small>
            </a>

            <a href="<?php echo e(route('activity-logs.index', array_merge($query, ['module' => 'document_types']))); ?>"
            class="btn btn-primary d-sm-inline-block <?php echo e(request('module') == 'document_types' ? 'active' : ''); ?>">
                <i class="fa-solid fa-file-lines"></i> <small>Document Types</small>
            </a>

            <a href="<?php echo e(route('activity-logs.index', array_merge($query, ['module' => 'students']))); ?>"
            class="btn btn-primary d-sm-inline-block <?php echo e(request('module') == 'students' ? 'active' : ''); ?>">
                <i class="fa-solid fa-user-graduate"></i> <small>Students</small>
            </a>
        </div>

        <form method="GET" action="<?php echo e(route('activity-logs.index')); ?>" class="mb-4">
            <input type="hidden" name="module" value="<?php echo e(request('module')); ?>">
            <div class="row g-2">
                <div class="col-md-4">
                    <input type="date" id="startDate" name="startDate" class="form-control"
                        value="<?php echo e($startDate); ?>" onchange="updateToDateMin(); this.form.submit()" />
                </div>
                <div class="col-md-4">
                    <input type="date" id="endDate" name="endDate" class="form-control"
                        value="<?php echo e($endDate); ?>" onchange="updateFromDateMax(); this.form.submit()" />
                </div>
            </div>
        </form>

        <div class="row row-deck row-cards">
            <div class="col-12 table-responsive no-flex">
                <table id="activity-table" class="table table-bordered table-striped align-middle datatable" style="width:100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>Module</th>
                            <th>Description</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $logs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($loop->iteration); ?></td>
                                <td><?php echo e($log->user_full_name ?? 'Unknown'); ?></td>
                                <td><?php echo e($log->action); ?></td>
                                <td><?php echo e($log->module_name); ?></td>
                                <td><?php echo e($log->description); ?></td>
                                <td><?php echo e($log->created_at_formatted); ?></td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
<?php $__env->stopPush(); ?>

<?php $__env->startPush('page-scripts'); ?>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
$('#activity-table').DataTable({
    pageLength: 10,
    lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
    language: {
        search: "_INPUT_",
        searchPlaceholder: "Search activity logs...",
        infoEmpty: "No activity logs found.",
    },
    infoCallback: function(settings, start, end, max, total, pre) {
        if (settings._iDisplayLength == -1) {
            return `Showing all ${total} logs`;
        }
        return `Showing ${start} to ${end} of ${total} logs`;
    },
    autoWidth: false
});
function updateToDateMin() {
    const fromDate = document.getElementById('startDate').value;
    document.getElementById('endDate').min = fromDate;
}
function updateFromDateMax() {
    const toDate = document.getElementById('endDate').value;
    document.getElementById('startDate').max = toDate;
}
document.addEventListener('DOMContentLoaded', function () {
    updateToDateMin();
    updateFromDateMax();
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.tabler', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\kiosk\resources\views/admin/activity_logs.blade.php ENDPATH**/ ?>