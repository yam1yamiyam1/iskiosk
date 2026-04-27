
<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar">
            <div class="container-xl">
                <ul class="navbar-nav">
                    <li class="nav-item <?php echo e(request()->is('dashboard*') ? 'active' : null); ?>">
                        <a class="nav-link" href="<?php echo e(route('dashboard')); ?>" >
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="fa-solid fa-chart-simple" style="<?php echo e(request()->is('dashboard*') ? 'color: #ff0d0b;' : 'color: #720100;'); ?>"></i>
                            </span>
                            <span class="nav-link-title">
                                <?php echo e(__('Dashboard')); ?>

                            </span>
                        </a>
                    </li>

                    <li class="nav-item <?php echo e(request()->is('documents*') ? 'active' : ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('documents.index')); ?>">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="fa-solid fa-file-lines"
                                style="color: <?php echo e(request()->is('documents*') ? '#ff0d0b' : '#720100'); ?>;">
                                </i>
                            </span>
                            <span class="nav-link-title">
                                <?php echo e(__('Documents')); ?>

                            </span>
                        </a>
                    </li>

                    <li class="nav-item <?php echo e(request()->is('students*') ? 'active' : ''); ?>">
                        <a class="nav-link" href="<?php echo e(route('students.index')); ?>">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="fa-solid fa-user-graduate"
                                style="color: <?php echo e(request()->is('students*') ? '#ff0d0b' : '#720100'); ?>;">
                                </i>
                            </span>
                            <span class="nav-link-title">
                                <?php echo e(__('Students')); ?>

                            </span>
                        </a>
                    </li>

                    <?php if(Auth::user()->roles[0]->role != 0): ?>

                        <li class="nav-item <?php echo e(request()->is('types*') ? 'active' : ''); ?>">
                            <a class="nav-link" href="<?php echo e(route('types.index')); ?>">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="fa-solid fa-layer-group"
                                    style="color: <?php echo e(request()->is('types*') ? '#ff0d0b' : '#720100'); ?>;">
                                    </i>
                                </span>
                                <span class="nav-link-title">
                                    <?php echo e(__('Types / Categories')); ?>

                                </span>
                            </a>
                        </li>

                        <li class="nav-item <?php echo e(request()->is('departments*') ? 'active' : ''); ?>">
                            <a class="nav-link" href="<?php echo e(route('departments.index')); ?>">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="fa-solid fa-building"
                                    style="color: <?php echo e(request()->is('departments*') ? '#ff0d0b' : '#720100'); ?>;">
                                    </i>
                                </span>
                                <span class="nav-link-title">
                                    <?php echo e(__('Departments')); ?>

                                </span>
                            </a>
                        </li>

                        <li class="nav-item dropdown <?php echo e(request()->is('users*') ? 'active' : null); ?>">
                            <a class="nav-link" href="<?php echo e(route('users.index')); ?>">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="fa-solid fa-users-gear"
                                    style="<?php echo e(request()->is('users*') ? 'color: #ff0d0b;' : 'color: #720100;'); ?>">
                                    </i>
                                </span>
                                <span class="nav-link-title">
                                    <?php echo e(__('Users')); ?>

                                </span>
                            </a>
                        </li>

                        <li class="nav-item <?php echo e(request()->is('activity-logs*') ? 'active' : null); ?>">
                            <a class="nav-link" href="<?php echo e(route('activity-logs.index')); ?>">
                                <span class="nav-link-icon d-md-none d-lg-inline-block">
                                    <i class="fa-solid fa-clock-rotate-left"
                                    style="<?php echo e(request()->is('activity-logs*') ? 'color: #ff0d0b;' : 'color: #720100;'); ?>">
                                    </i>
                                </span>
                                <span class="nav-link-title">
                                    <?php echo e(__('Activity Logs')); ?>

                                </span>
                            </a>
                        </li>

                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </div>
</header>
<?php /**PATH C:\laragon\www\kiosk\resources\views\layouts\body\navbar.blade.php ENDPATH**/ ?>