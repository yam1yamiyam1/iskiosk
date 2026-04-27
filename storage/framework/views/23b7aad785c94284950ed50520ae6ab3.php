<?php $__env->startSection('content'); ?>

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Records</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">

            <div class="col-12 table-responsive d-block">
                <?php if($documents->isEmpty()): ?>
                    <div class="alert alert-warning">
                        No documents available.
                    </div>
                <?php else: ?>
                    <table id="documents-table" class="table table-striped datatable" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tracker ID</th>
                                <th>Document Type</th>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Program</th>
                                <th>Remarks</th>
                                <th>Status Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><b><?php echo e($document->tracking_code); ?></b></td>
                                    <td><?php echo e($document->document_typeb->name); ?></td>
                                    <td><?php echo e($document->id_number); ?></td>
                                    <td>
                                        <?php echo e($document->surname); ?>,
                                        <?php echo e($document->given_name); ?>

                                        <?php echo e($document->middle_name); ?>

                                    </td>
                                    <td><?php echo e($document->programb->name); ?></td>
                                    <td><?php echo e($document->remarks); ?></td>
                                    <?php
                                        switch($document->status) {
                                            case 'Submitted':
                                                $date = $document->created_at;
                                                $title = 'Date Submitted';
                                                break;
                                            case 'Collected and Processing':
                                                $date = $document->updated_at;
                                                $title = 'Date Collected';
                                                break;
                                            case 'Ready for Claiming':
                                                $date = $document->updated_at;
                                                $title = 'Last Updated';
                                                break;
                                            case 'Claimed':
                                                $date = $document->updated_at;
                                                $title = 'Date Claimed';
                                                break;
                                            default:
                                                $date = $document->updated_at;
                                                $title = 'Last Updated';
                                                break;
                                        }
                                    ?>

                                    <td>
                                        <strong><?php echo e($title); ?>:</strong>
                                        <div style="font-size: 0.9em; margin-top: 2px;">
                                            <?php echo e($date ? $date->copy()->addHours(8)->format('M. j, Y g:i A') : 'N/A'); ?>

                                        </div>
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

<?php $__env->stopSection(); ?>

<?php if (! $__env->hasRenderedOnce('f9f0a846-03ed-4ac2-bcc6-3ce1fbb9cad0')): $__env->markAsRenderedOnce('f9f0a846-03ed-4ac2-bcc6-3ce1fbb9cad0');
$__env->startPush('page-scripts'); ?>
<script>
    const documentSearchInput = document.getElementById('document-search-input');
    const documentTableBody = document.querySelector('.table tbody');

    const searchDocuments = (value) => {
        const phrase = value.trim().toLowerCase();
        const rows = documentTableBody.querySelectorAll('tr');
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(phrase) ? '' : 'none';
        });
    };

    documentSearchInput.addEventListener('input', (e) => {
        searchDocuments(e.target.value);
    });
</script>
<?php $__env->stopPush(); endif; ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" />
<?php $__env->stopPush(); ?>

<?php $__env->startPush('page-scripts'); ?>
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
    const table = $('#documents-table').DataTable({
        pageLength: 10,
        lengthMenu: [[5, 10, 25, 50, 100, -1], [5, 10, 25, 50, 100, "All"]],
        columnDefs: [
            { orderable: false, targets: [7] },
            { searchable: false, targets: 7 }
        ],
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search documents...",
            infoEmpty: "No entries to show",
        },
        infoCallback: function(settings, start, end, max, total, pre) {
            if (settings._iDisplayLength == -1) {
                return `Showing all ${total} documents`;
            }
            return `Showing ${start} to ${end} of ${total} documents`;
        },
        autoWidth: false
    });

    $('#document-search-input').on('keyup', function () {
        table.search(this.value).draw();
    });

</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.tabler', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\iskiosk\resources\views/admin/records.blade.php ENDPATH**/ ?>