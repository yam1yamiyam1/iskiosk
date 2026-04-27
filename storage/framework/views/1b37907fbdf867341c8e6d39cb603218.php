<?php $__env->startSection('content'); ?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Document Types
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-primary d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#addDocumentTypeModal" style="background-color: #720100;">
                        <?php if (isset($component)) { $__componentOriginal6315a526d124ee5b3ba861082d11f72e = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6315a526d124ee5b3ba861082d11f72e = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon.plus','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon.plus'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6315a526d124ee5b3ba861082d11f72e)): ?>
<?php $attributes = $__attributesOriginal6315a526d124ee5b3ba861082d11f72e; ?>
<?php unset($__attributesOriginal6315a526d124ee5b3ba861082d11f72e); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6315a526d124ee5b3ba861082d11f72e)): ?>
<?php $component = $__componentOriginal6315a526d124ee5b3ba861082d11f72e; ?>
<?php unset($__componentOriginal6315a526d124ee5b3ba861082d11f72e); ?>
<?php endif; ?>
                        Add New Document Type
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-deck row-cards">
            <div class="col-12">
                <div class="input-group mb-4">
                    <input type="text" class="form-control" id="advanced-search-input" placeholder="Search types..." />
                    <button class="btn btn-primary" style="background-color: #720100;" id="advanced-search-button" type="button">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </div>

            <div class="col-12 table-responsive">
                <?php if(count($types) === 0): ?>
                    <div class="alert alert-warning" role="alert">
                        No document types available.
                    </div>
                <?php else: ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th style="width: 80px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($index + 1); ?></td>
                                    <td><?php echo e($type->name); ?></td>
                                    <td>
                                        <a href="#" class="text-primary me-2" data-bs-toggle="modal" data-bs-target="#editDocumentTypeModal<?php echo e($type->id); ?>" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#deleteDocumentTypeModal<?php echo e($type->id); ?>" title="Delete">
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

<?php echo $__env->make('includes.documenttype-add', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__currentLoopData = $types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php echo $__env->make('includes.documenttype-edit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('includes.documenttype-delete', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('page-libraries'); ?>
<script src="<?php echo e(asset('dist/libs/apexcharts/dist/apexcharts.min.js')); ?>" defer></script>
<?php $__env->stopPush(); ?>

<?php if (! $__env->hasRenderedOnce('c71fa401-a61c-4de6-962a-d5e9522e17eb')): $__env->markAsRenderedOnce('c71fa401-a61c-4de6-962a-d5e9522e17eb');
$__env->startPush('page-scripts'); ?>
<script>
    const advancedSearchInput = document.getElementById('advanced-search-input');
    const table = document.querySelector('.table tbody');

    const search = (value) => {
        const phrase = value.trim().toLowerCase();
        const rows = table.querySelectorAll('tr');

        rows.forEach(row => {
            const name = row.children[1]?.textContent.toLowerCase() || '';
            const match = name.includes(phrase);
            row.style.display = match ? '' : 'none';
        });
    };

    advancedSearchInput.addEventListener('input', (e) => {
        search(e.target.value);
    });
</script>
<?php $__env->stopPush(); endif; ?>

<?php echo $__env->make('layouts.tabler', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\iskiosk\resources\views/admin/type.blade.php ENDPATH**/ ?>