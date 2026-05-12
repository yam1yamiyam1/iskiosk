<?php $__env->startSection('content'); ?>

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Documents</h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <form method="GET" action="<?php echo e(route('documents.index')); ?>">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Start Date</label>
                        <input type="date" name="start_date" class="form-control"
                            value="<?php echo e(request('start_date')); ?>">
                    </div>

                    <div class="col-md-3">
                        <label>End Date</label>
                        <input type="date" name="end_date" class="form-control"
                            value="<?php echo e(request('end_date')); ?>">
                    </div>

                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">Filter</button>

                        <a href="<?php echo e(route('documents.index')); ?>" class="btn btn-secondary">Reset</a>

                        <a href="<?php echo e(route('documents.export', [
                            'start_date' => request('start_date'),
                            'end_date' => request('end_date')
                        ])); ?>" 
                        class="btn btn-success">
                            <i class="fas fa-download"></i> Download Report
                        </a>
                    </div>
                </div>
            </form>

            <div class="col-12 mb-3 d-flex gap-2 flex-wrap">
                <button class="btn <?php echo e($documents->where('status', 'Submitted')->isEmpty() || $passes ? 'btn-secondary' : 'btn-primary'); ?>"
                        data-bs-toggle="modal"
                        data-bs-target="#processAllModal"
                        <?php echo e($documents->where('status', 'Submitted')->isEmpty() || $passes ? 'disabled' : ''); ?>>
                    <i class="fas fa-play px-1"></i> Manual
                </button>

                <button class="btn <?php echo e($documents->where('status', 'Collected and Processing')->isEmpty() ? 'btn-secondary' : 'btn-warning'); ?>"
                        data-bs-toggle="modal"
                        data-bs-target="#markAllReadyModal"
                        <?php echo e($documents->where('status', 'Collected and Processing')->isEmpty() ? 'disabled' : ''); ?>>
                    <i class="fas fa-box px-1"></i> Mark All as Ready
                </button>

                <button class="btn <?php echo e($documents->where('status', 'Ready for claiming')->isEmpty() ? 'btn-secondary' : 'btn-success'); ?>"
                        data-bs-toggle="modal"
                        data-bs-target="#markAllClaimedModal"
                        <?php echo e($documents->where('status', 'Ready for claiming')->isEmpty() ? 'disabled' : ''); ?>>
                    <i class="fas fa-check px-1"></i> Mark All as Claimed
                </button>
            </div>

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
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>Status Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><b><?php echo e($document->tracking_code); ?></b></td>
                                    <td><?php echo e($document->document_type); ?></td>
                                    <td><?php echo e($document->id_number); ?></td>
                                    <td>
                                        <?php echo e($document->surname); ?>,
                                        <?php echo e($document->given_name); ?>

                                        <?php echo e($document->middle_name); ?>

                                    </td>
                                    <td><?php echo e($document->program); ?></td>
                                    <td>
                                        <span class="badge bg-light">
                                            <?php if($document->status === 'Submitted' && $passes): ?>
                                                Waiting to be retrieved by <?php echo e($passes->staff->name); ?>

                                            <?php else: ?>
                                                <?php echo e(ucfirst($document->status)); ?>

                                            <?php endif; ?>
                                        </span>
                                    </td>
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
                                    <td>
                                        <?php if($document->status === 'Collected and Processing'): ?>
                                            <a href="#" class="link-warning me-2 text-decoration-none"
                                               data-bs-toggle="modal"
                                               data-bs-target="#markReadyModal<?php echo e($document->id); ?>"
                                               title="Mark as Ready">
                                                <i class="fas fa-box px-1"></i> Mark as Ready
                                            </a>

                                        <?php elseif($document->status === 'Ready for claiming'): ?>
                                            <a href="#" class="link-success me-2 text-decoration-none"
                                               data-bs-toggle="modal"
                                               data-bs-target="#markClaimedModal<?php echo e($document->id); ?>"
                                               title="Mark as Claimed">
                                                <i class="fas fa-check px-1"></i> Mark as Claimed
                                            </a>
                                            <?php if(\Illuminate\Support\Str::contains((string) $document->remarks, '[OVERRIDE]')): ?>
                                                <a href="<?php echo e(route('documents.overrideGuide', $document->id)); ?>"
                                                   target="_blank"
                                                   class="link-primary text-decoration-none"
                                                   title="Print Override Guide">
                                                    <i class="fas fa-print px-1"></i> Print Guide
                                                </a>
                                            <?php endif; ?>

                                        <?php elseif($document->status === 'Submitted'): ?>
                                            <a href="#" class="link-danger me-2 text-decoration-none"
                                               data-bs-toggle="modal"
                                               data-bs-target="#overrideModal<?php echo e($document->id); ?>"
                                               title="Override Submitted">
                                                <i class="fas fa-forward px-1"></i> Override
                                            </a>

                                        <?php elseif($document->status === 'Claimed' && \Illuminate\Support\Str::contains((string) $document->remarks, '[OVERRIDE]')): ?>
                                            <a href="<?php echo e(route('documents.overrideGuide', $document->id)); ?>"
                                               target="_blank"
                                               class="link-primary text-decoration-none"
                                               title="Print Override Guide">
                                                <i class="fas fa-print px-1"></i> Print Guide
                                            </a>

                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
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

<div class="modal fade" id="processAllModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manual Document Collection</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="<?php echo e(route('documents.processAll')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="modal-body">
                    <p>
                        Use this option only during power outages or system failure.
                        This will mark all documents in the kiosk as collected.
                    </p>

                    <div class="mb-3">
                        <label class="form-label">Select User (Collector)</label>
                        <select name="user_id" class="form-select" required>
                            <option value="" selected disabled>Select User</option>
                            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($user->id); ?>">
                                    <?php echo e($user->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>

                    <div class="alert alert-warning">
                        ⚠️ This action will update all pending documents as collected.
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        Confirm Manual Process
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="markAllReadyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark All Collected Documents as Ready</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="<?php echo e(route('documents.markAllReady')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="modal-body">
                    <div class="mb-3 text-center">
                        <span class="badge bg-secondary text-white">Collected and Processing</span>
                        <i class="fas fa-arrow-right mx-2 text-muted"></i>
                        <span class="badge bg-warning text-white">Ready for Claiming</span>
                    </div>

                    <p>
                        Are you sure you want to mark 
                        <strong>all collected documents</strong> as Ready for Claiming?
                    </p>

                    <div class="mb-3">
                        <label class="form-label">Remark (Optional)</label>
                        
                        <div class="mb-2 quick-remarks-container">
                            <?php $__currentLoopData = $quickRemarks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $qr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-primary text-white cursor-pointer quick-remark-chip <?php echo e($index >= 3 ? 'd-none extra-remark' : ''); ?>" 
                                      style="cursor: pointer; color: white !important; margin-right: 5px; margin-bottom: 5px;" 
                                      data-remark="<?php echo e($qr->remark); ?>"><?php echo e(\Illuminate\Support\Str::limit($qr->remark, 30)); ?></span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php if(count($quickRemarks) > 3): ?>
                                <span class="badge bg-secondary text-white cursor-pointer show-more-remarks" style="cursor: pointer; margin-right: 5px; margin-bottom: 5px;">Show more...</span>
                            <?php endif; ?>
                            <span class="badge bg-dark text-white cursor-pointer custom-remark-chip" style="cursor: pointer; margin-bottom: 5px;">Other...</span>
                        </div>
                        <textarea name="remark" class="form-control remark-textarea" rows="3" maxlength="500"
                                placeholder="Enter remark for all documents (optional).."></textarea>
                    </div>

                    <div class="alert alert-warning">
                        ⚠️ This remark will be applied to all affected documents.
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-warning">
                        Mark as Ready
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="markAllClaimedModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mark All Ready Documents as Claimed</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?php echo e(route('documents.markAllClaimed')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    Are you sure you want to mark <strong>all ready for claiming documents</strong> as Claimed?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Mark as Claimed</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="scanResultModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-header">
                <h5 class="modal-title" id="scanModalTitle">Scan Result</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p id="scanModalMessage"></p>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">
                    OK
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="scanConfirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Scan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3 text-center">
                    <span id="c_status_badge"></span>
                    <i class="fas fa-arrow-right mx-2 text-muted"></i>
                    <span id="c_update_badge"></span>
                </div>
                
                <div class="card mb-3 bg-light">
                    <div class="card-body p-2" style="font-size: 0.9em;">
                        <strong>Tracker ID:</strong> <span id="c_tracking"></span><br>
                        <strong>Student:</strong> <span id="c_fullname"></span><br>
                        <strong>Document:</strong> <span id="c_doc"></span>
                    </div>
                </div>

                <div class="text-center mt-3 text-muted" id="scanKeyboardShortcuts" style="font-size: 0.85em;">
                    <kbd>Enter</kbd> to confirm &nbsp;&nbsp; <kbd>Esc</kbd> to cancel
                </div>
            </div>

            <div class="modal-footer" id="scanModalFooter">
                <!-- Dynamically populated buttons -->
            </div>
        </div>
    </div>
</div>

<?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="markReadyModal<?php echo e($document->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mark Document as Ready</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="<?php echo e(route('documents.markReady', $document->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>

                    <div class="modal-body">
                        <div class="mb-3 text-center">
                            <span class="badge bg-secondary text-white">Collected and Processing</span>
                            <i class="fas fa-arrow-right mx-2 text-muted"></i>
                            <span class="badge bg-warning text-white">Ready for Claiming</span>
                        </div>
                        
                        <div class="card mb-3 bg-light">
                            <div class="card-body p-2" style="font-size: 0.9em;">
                                <strong>Tracker ID:</strong> <?php echo e($document->tracking_code); ?><br>
                                <strong>Student:</strong> <?php echo e($document->surname); ?>, <?php echo e($document->given_name); ?><br>
                                <strong>Document:</strong> <?php echo e($document->document_type); ?>

                            </div>
                        </div>

                        <p>
                            Mark this document as <strong>Ready for Claiming</strong>?
                        </p>

                        <div class="mb-3">
                            <label class="form-label">Remark (Optional)</label>
                            
                            <div class="mb-2 quick-remarks-container">
                                <?php $__currentLoopData = $quickRemarks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $qr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="badge bg-primary text-white cursor-pointer quick-remark-chip <?php echo e($index >= 3 ? 'd-none extra-remark' : ''); ?>" 
                                          style="cursor: pointer; color: white !important; margin-right: 5px; margin-bottom: 5px;" 
                                          data-remark="<?php echo e($qr->remark); ?>"><?php echo e(\Illuminate\Support\Str::limit($qr->remark, 30)); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php if(count($quickRemarks) > 3): ?>
                                    <span class="badge bg-secondary text-white cursor-pointer show-more-remarks" style="cursor: pointer; margin-right: 5px; margin-bottom: 5px;">Show more...</span>
                                <?php endif; ?>
                                <span class="badge bg-dark text-white cursor-pointer custom-remark-chip" style="cursor: pointer; margin-bottom: 5px;">Other...</span>
                            </div>
                            <textarea name="remark" class="form-control remark-textarea" rows="3" maxlength="500"
                                    placeholder="Enter any note or remark (optional)..."></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-warning">
                            Confirm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="markClaimedModal<?php echo e($document->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Mark Document as Claimed</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="<?php echo e(route('documents.markClaimed', $document->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>

                    <div class="modal-body">
                        <div class="mb-3 text-center">
                            <span class="badge bg-warning text-white">Ready for Claiming</span>
                            <i class="fas fa-arrow-right mx-2 text-muted"></i>
                            <span class="badge bg-success text-white">Claimed</span>
                        </div>
                        
                        <div class="card mb-3 bg-light">
                            <div class="card-body p-2" style="font-size: 0.9em;">
                                <strong>Tracker ID:</strong> <?php echo e($document->tracking_code); ?><br>
                                <strong>Student:</strong> <?php echo e($document->surname); ?>, <?php echo e($document->given_name); ?><br>
                                <strong>Document:</strong> <?php echo e($document->document_type); ?>

                            </div>
                        </div>

                        Are you sure you want to mark this document as <strong>Claimed</strong>?
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Confirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="overrideModal<?php echo e($document->id); ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Override Submitted Document</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="<?php echo e(route('documents.override', $document->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>

                    <div class="modal-body">
                        <div class="mb-3 text-center">
                            <span class="badge bg-secondary text-white">Submitted</span>
                            <i class="fas fa-arrow-right mx-2 text-danger"></i>
                            <span class="badge bg-danger text-white">Claimed (Override)</span>
                        </div>
                        
                        <div class="card mb-3 bg-light">
                            <div class="card-body p-2" style="font-size: 0.9em;">
                                <strong>Tracker ID:</strong> <?php echo e($document->tracking_code); ?><br>
                                <strong>Student:</strong> <?php echo e($document->surname); ?>, <?php echo e($document->given_name); ?><br>
                                <strong>Document:</strong> <?php echo e($document->document_type); ?>

                            </div>
                        </div>

                        <p>
                            This will bypass kiosk waiting and mark this document as
                            <strong>Claimed</strong> immediately.
                        </p>

                        <div class="mb-3">
                            <label class="form-label">Override Note (Optional)</label>
                            <textarea name="remark" class="form-control" rows="3" maxlength="500"
                                      placeholder="Reason for manual override..."></textarea>
                        </div>

                        <div class="alert alert-warning">
                            ⚠️ Use only for document types that do not require kiosk drop-off.
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Confirm Override</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    
    <div id="testModePanel" style="position: fixed; bottom: 20px; right: 20px; background: #1e293b; border-radius: 10px; padding: 12px 16px; z-index: 1100; min-width: 230px; box-shadow: 0 4px 16px rgba(0,0,0,0.35);">
        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
            <span style="font-size: 10px; font-weight: 700; letter-spacing: 0.1em; color: #f59e0b; text-transform: uppercase;">⚙ Dev / Test Mode</span>
            <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 12px; color: #94a3b8; margin: 0;">
                <input type="checkbox" id="testModeToggle" style="accent-color: #f59e0b;"> On
            </label>
        </div>
        <div id="testModeContainer" style="display: none;">
            <input type="text" id="testBarcode" placeholder="Enter Tracking Code"
                style="width: 100%; box-sizing: border-box; padding: 7px 10px; font-size: 13px; border-radius: 6px !important; border: 1px solid #475569 !important; background: #0f172a; color: #f1f5f9; margin-bottom: 8px; min-height: unset; box-shadow: none !important;">
            <button id="testSubmit"
                style="width: 100%; padding: 8px; background: #8B1A1A; color: white; border: none; border-radius: 6px !important; font-size: 13px; cursor: pointer; font-weight: 600; font-family: 'Roboto', sans-serif; height: auto;">
                Simulate Scan
            </button>
        </div>
    </div>
    

<?php $__env->stopSection(); ?>

<?php if (! $__env->hasRenderedOnce('920a5d02-3d84-4833-b9e7-2ef7830c3bcf')): $__env->markAsRenderedOnce('920a5d02-3d84-4833-b9e7-2ef7830c3bcf');
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
            { orderable: false, targets: [9] },
            { searchable: false, targets: 9 }
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

    const ws = new WebSocket('ws://localhost:8081');

    ws.addEventListener('message', async e => {
        let msg = e.data.trim();

        console.log("📩 Scan received:", msg);

        await handleTrackingScan(msg);
    });

    let scannedDocument = null;
    let scanAction = 'process';

    async function handleTrackingScan(code) {
        try {
            const res = await fetch('<?php echo e(route('documents.scanTrack')); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({ tracking_code: code })
            });

            const data = await res.json();

            if (data.status === 'not_found') {
                showScanModal('Not Found', '❌ Tracking code not found.', 'danger');
                return;
            }

            if (data.status === 'found') {
                const currentStatus = data.document.current_status;

                if (currentStatus === 'Claimed') {
                    showScanModal(
                        'Already Claimed',
                        '✅ This document has already been claimed.',
                        'success'
                    );
                    return;
                }
                
                scannedDocument = data.document;
                scanAction = 'process';

                document.getElementById('c_tracking').innerText = code;
                document.getElementById('c_fullname').innerText = data.document.fullname;
                document.getElementById('c_doc').innerText = data.document.document_type;
                
                const statusBadgeMap = {
                    'Submitted': '<span class="badge bg-secondary text-white">Submitted</span>',
                    'Collected and Processing': '<span class="badge bg-secondary text-white">Collected and Processing</span>',
                    'Ready for claiming': '<span class="badge bg-warning text-white">Ready for Claiming</span>',
                    'Claimed': '<span class="badge bg-success text-white">Claimed</span>'
                };
                
                document.getElementById('c_status_badge').innerHTML = statusBadgeMap[currentStatus] || `<span class="badge bg-light text-dark">${currentStatus}</span>`;

                let nextStatusBadge = '';
                let footerHtml = '';

                if (currentStatus === 'Submitted') {
                    document.getElementById('scanKeyboardShortcuts').style.display = 'none';
                    nextStatusBadge = '<span class="badge bg-secondary text-white">Collected and Processing</span>';
                    footerHtml = `
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-danger ms-auto" id="overrideScanBtn">Override to Claimed</button>
                        <button class="btn btn-primary" id="confirmScanBtn">Collect Document</button>
                    `;
                } else if (currentStatus === 'Collected and Processing') {
                    document.getElementById('scanKeyboardShortcuts').style.display = 'block';
                    nextStatusBadge = '<span class="badge bg-warning text-white">Ready for Claiming</span>';
                    footerHtml = `
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" id="confirmScanBtn">Confirm Update</button>
                    `;
                } else if (currentStatus === 'Ready for claiming') {
                    document.getElementById('scanKeyboardShortcuts').style.display = 'block';
                    nextStatusBadge = '<span class="badge bg-success text-white">Claimed</span>';
                    footerHtml = `
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-primary" id="confirmScanBtn">Confirm Update</button>
                    `;
                } else {
                    document.getElementById('scanKeyboardShortcuts').style.display = 'block';
                    nextStatusBadge = '<span class="badge bg-light text-dark">No action</span>';
                    footerHtml = `<button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>`;
                }

                document.getElementById('c_update_badge').innerHTML = nextStatusBadge;
                document.getElementById('scanModalFooter').innerHTML = footerHtml;

                const scanModalEl = document.getElementById('scanConfirmModal');
                const scanModal = new bootstrap.Modal(scanModalEl);
                scanModal.show();
                
                const confirmBtn = document.getElementById('confirmScanBtn');
                if (confirmBtn) {
                    confirmBtn.addEventListener('click', () => {
                        scanAction = 'process';
                        processScanConfirm();
                    });
                }
                
                const overrideBtn = document.getElementById('overrideScanBtn');
                if (overrideBtn) {
                    overrideBtn.addEventListener('click', () => {
                        scanAction = 'override';
                        processScanConfirm();
                    });
                }
            }

        } catch (err) {
            console.error(err);
            showScanModal('Error', 'Something went wrong.', 'danger');
        }
    }

    document.addEventListener('keydown', function(e) {
        const scanModalEl = document.getElementById('scanConfirmModal');
        if (scanModalEl.classList.contains('show')) {
            if (scannedDocument && scannedDocument.current_status === 'Submitted') {
                return; // Disable custom enter/esc handling when there are multiple options
            }
            if (e.key === 'Enter') {
                e.preventDefault();
                const confirmBtn = document.getElementById('confirmScanBtn');
                if (confirmBtn) confirmBtn.click();
            } else if (e.key === 'Escape') {
                e.preventDefault();
                const cancelBtn = scanModalEl.querySelector('[data-bs-dismiss="modal"]');
                if (cancelBtn) cancelBtn.click();
            }
        }
    });

    async function processScanConfirm() {
        if (!scannedDocument) return;

        const confirmBtns = document.querySelectorAll('#scanModalFooter .btn');
        confirmBtns.forEach(btn => btn.disabled = true);

        const res = await fetch('<?php echo e(route('documents.confirmScan')); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({
                id: scannedDocument.id,
                action: scanAction
            })
        });

        const data = await res.json();

        if (data.status === 'updated') {
            bootstrap.Modal.getInstance(document.getElementById('scanConfirmModal')).hide();
            
            showScanModal(
                'Success',
                `✅ Status updated to:<br><b>${data.new_status}</b>`,
                'success'
            );

            setTimeout(() => location.reload(), 1000);
        } else {
             confirmBtns.forEach(btn => btn.disabled = false);
             showScanModal('Error', 'Error processing scan', 'danger');
        }
    }

    function showScanModal(title, message, type = 'primary') {
        const modalEl = document.getElementById('scanResultModal');

        document.getElementById('scanModalTitle').innerText = title;
        document.getElementById('scanModalMessage').innerHTML = message;

        const header = modalEl.querySelector('.modal-header');
        header.className = `modal-header bg-${type} text-white`;

        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }

    document.querySelectorAll('.quick-remark-chip').forEach(chip => {
        chip.addEventListener('click', function() {
            const remarkText = this.getAttribute('data-remark');
            const container = this.closest('.quick-remarks-container');
            const textarea = this.closest('.modal-body').querySelector('.remark-textarea');
            
            if(textarea) {
                textarea.value = remarkText;
            }
            
            if(container) {
                container.querySelectorAll('.badge').forEach(b => {
                    b.classList.remove('bg-success');
                    if (b.classList.contains('quick-remark-chip') || b.classList.contains('custom-remark-chip')) {
                        b.classList.add(b.classList.contains('custom-remark-chip') ? 'bg-dark' : 'bg-primary');
                    }
                });
                this.classList.remove('bg-primary');
                this.classList.add('bg-success');
            }
        });
    });

    document.querySelectorAll('.show-more-remarks').forEach(btn => {
        btn.addEventListener('click', function() {
            const container = this.closest('.quick-remarks-container');
            container.querySelectorAll('.extra-remark').forEach(chip => {
                chip.classList.remove('d-none');
            });
            this.classList.add('d-none');
        });
    });

    document.querySelectorAll('.custom-remark-chip').forEach(chip => {
        chip.addEventListener('click', function() {
            const container = this.closest('.quick-remarks-container');
            const textarea = this.closest('.modal-body').querySelector('.remark-textarea');
            
            if(textarea) {
                textarea.focus();
            }
            
            if(container) {
                container.querySelectorAll('.badge').forEach(b => {
                    b.classList.remove('bg-success');
                    if (b.classList.contains('quick-remark-chip') || b.classList.contains('custom-remark-chip')) {
                        b.classList.add(b.classList.contains('custom-remark-chip') ? 'bg-dark' : 'bg-primary');
                    }
                });
                this.classList.remove('bg-dark');
                this.classList.add('bg-success');
            }
        });
    });

    // Test Mode logic
    const testModeToggle = document.getElementById('testModeToggle');
    if (testModeToggle) {
        testModeToggle.addEventListener('change', (e) => {
            document.getElementById('testModeContainer').style.display = e.target.checked ? 'block' : 'none';
        });
        document.getElementById('testSubmit').addEventListener('click', () => {
            const val = document.getElementById('testBarcode').value.trim();
            if (val) { 
                handleTrackingScan(val); 
                document.getElementById('testBarcode').value = ''; 
            }
        });
        document.getElementById('testBarcode').addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                document.getElementById('testSubmit').click();
            }
        });
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.tabler', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\iskiosk\resources\views/admin/document.blade.php ENDPATH**/ ?>