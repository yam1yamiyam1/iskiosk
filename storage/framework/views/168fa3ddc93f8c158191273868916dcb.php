

<?php $__env->startSection('content'); ?>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        Users
                    </h2>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary d-sm-inline-block" data-bs-toggle="modal" data-bs-target="#addUserModal" style="background-color: #720100;">
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
                            Add New User
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
                        <input type="text" class="form-control" id="advanced-search-input" placeholder="Search..." />
                        <button data-mdb-button-init data-mdb-ripple-init class="btn btn-primary" style="background-color: #720100;" id="advanced-search-button" type="button">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </div>

                <div class="col-12 table-responsive">
                    <?php if(count($users) === 0): ?>
                        <div class="alert alert-warning" role="alert">
                            No users available.
                        </div>
                    <?php else: ?>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th style="text-align: center;">Role</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($index + 1); ?></td>
                                        <td>
                                            <?php if($user->image): ?>
                                                <img src="<?php echo e(asset('storage/users/' . $user->image)); ?>" alt="Image" width="60" height="60" style="object-fit: cover; border-radius: 4px;">
                                            <?php else: ?>
                                                <span class="text-muted">No Image</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($user->fname); ?> <?php echo e($user->lname); ?></td>
                                        <td><?php echo e($user->email); ?></td>
                                        <td class="text-center align-middle">
                                            <?php $__currentLoopData = $user->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $isAdmin = $role->role == 1;
                                                    $roleName = $isAdmin ? 'Admin' : 'Staff';
                                                    $badgeColor = $isAdmin ? 'linear-gradient(135deg, #004aad, #007bff)' : 'linear-gradient(135deg, #495057, #6c757d)';
                                                    $icon = $isAdmin ? 'fa-user-shield' : 'fa-user';
                                                ?>

                                                <div class="d-inline-flex justify-content-center align-items-center gap-2 text-white fw-semibold shadow-sm role-badge"
                                                    style="
                                                        width: 110px;
                                                        height: 34px;
                                                        font-size: 0.9rem;
                                                        border-radius: 50px;
                                                        background: <?php echo e($badgeColor); ?>;
                                                        transition: all 0.25s ease-in-out;
                                                    ">
                                                    <i class="fa-solid <?php echo e($icon); ?>" style="font-size: 0.85rem;"></i>
                                                    <?php echo e($roleName); ?>

                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </td>
                                        <style>
                                            .role-badge:hover {
                                                transform: translateY(-2px) scale(1.03);
                                                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
                                            }
                                        </style>
                                        <td><?php echo e($user->isActive() ? 'Active' : 'Disabled'); ?></td>
                                        <td>
                                            <a href="#" class="text-primary me-2"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editUserModal<?php echo e($user->id); ?>"
                                            title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a href="javascript:void(0)"
                                            class="text-success me-2"
                                            title="Download Barcode"
                                            onclick="downloadBarcode(<?php echo e($user->id); ?>, '<?php echo e($user->fname); ?>', '<?php echo e($user->lname); ?>')">
                                                <i class="fas fa-barcode"></i>
                                            </a>

                                            <a href="#" class="text-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteUserModal<?php echo e($user->id); ?>"
                                            title="Delete">
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

    <?php echo $__env->make('includes.useradd', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php echo $__env->make('includes.useredit', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <?php echo $__env->make('includes.userdelete', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('page-libraries'); ?>
    <script src="<?php echo e(asset('dist/libs/apexcharts/dist/apexcharts.min.js')); ?>" defer></script>
    <script src="<?php echo e(asset('dist/libs/jsvectormap/dist/js/jsvectormap.min.js')); ?>" defer></script>
    <script src="<?php echo e(asset('dist/libs/jsvectormap/dist/maps/world.js')); ?>" defer></script>
    <script src="<?php echo e(asset('dist/libs/jsvectormap/dist/maps/world-merc.js')); ?>" defer></script>
<?php $__env->stopPush(); ?>

<?php if (! $__env->hasRenderedOnce('2e56d0be-49bf-4509-9a33-9483e48e7f63')): $__env->markAsRenderedOnce('2e56d0be-49bf-4509-9a33-9483e48e7f63');
$__env->startPush('page-scripts'); ?>
<script>
    const advancedSearchInput = document.getElementById('advanced-search-input');
    const table = document.querySelector('.table tbody');

    const search = (value) => {
        const [phrasePart, columnsPart] = value.split(' in:').map(str => str.trim().toLowerCase());
        const phrase = phrasePart;
        const columns = columnsPart ? columnsPart.split(',').map(str => str.trim()) : [];

        const rows = table.querySelectorAll('tr');

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            let match = false;

            cells.forEach((cell, index) => {
                const columnName = ['name', 'email', 'role', 'status'][index];

                if (columns.length === 0 || columns.includes(columnName.toLowerCase())) {
                    if (cell.textContent.toLowerCase().includes(phrase)) {
                        match = true;
                    }
                }
            });

            row.style.display = match ? '' : 'none';
        });
    };

    advancedSearchInput.addEventListener('input', (e) => {
        search(e.target.value);
    });
</script>
<?php $__env->stopPush(); endif; ?>

<?php echo $__env->make('layouts.tabler', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\kiosk\resources\views\admin\user.blade.php ENDPATH**/ ?>