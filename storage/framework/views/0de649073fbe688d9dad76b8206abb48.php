
<header class="navbar-expand-md">
    <div class="collapse navbar-collapse" id="navbar-menu">
        <div class="navbar">
            <div class="container-xl">
                <ul class="navbar-nav">
                    <li class="nav-item <?php echo e(request()->is('home*') ? 'active' : null); ?>">
                        <a class="nav-link" href="<?php echo e(route('home')); ?>" >
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="fa-solid fa-house" style="<?php echo e(request()->is('home*') ? 'color: #ff0d0b;' : 'color: #720100;'); ?>"></i>
                            </span>
                            <span class="nav-link-title">
                                <?php echo e(__('Home')); ?>

                            </span>
                        </a>
                    </li>

                    <li class="nav-item <?php echo e(request()->is('daily-report*') ? 'active' : null); ?>">
                        <a class="nav-link" href="<?php echo e(route('daily-report.index')); ?>" >
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="fa-solid fa-clipboard-list" style="<?php echo e(request()->is('daily-report*') ? 'color: #ff0d0b;' : 'color: #720100;'); ?>"></i>
                            </span>
                            <span class="nav-link-title">
                                <?php echo e(__('Daily Reports')); ?>

                            </span>
                        </a>
                    </li>
                    
                    <li class="nav-item <?php echo e(request()->is('products*') ? 'active' : null); ?>">
                        <a class="nav-link" href="<?php echo e(route('staff.products.index')); ?>" >
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="fa-solid fa-boxes-stacked" style="<?php echo e(request()->is('products*') ? 'color: #ff0d0b;' : 'color: #720100;'); ?>"></i>
                            </span>
                            <span class="nav-link-title">
                                <?php echo e(__('Products')); ?>

                            </span>
                        </a>
                    </li>
                  
                </ul>
            </div>
        </div>
    </div>
</header>
<?php /**PATH C:\laragon\www\kiosk\resources\views\layouts\body\staffnavbar.blade.php ENDPATH**/ ?>