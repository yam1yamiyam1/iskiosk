
<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
        <title>IsKiosk: Document Management System</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('assets/ico/PUPLogo.ico')); ?>" />

    <!-- CSS files -->
    <link href="<?php echo e(asset('dist/css/tabler.min.css')); ?>" rel="stylesheet"/>
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?php if(Auth()->user()->roles[0]->role == 0): ?>
        <link href="<?php echo e(asset('assets/css/staff.css')); ?>" rel="stylesheet"/>
    <?php endif; ?>
    
    <link href="<?php echo e(asset('assets/css/style.css')); ?>" rel="stylesheet"/>
    <!-- 
    <link href="<?php echo e(URL::asset('assets/css/style.css')); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo e(URL::asset('assets/css/media.css')); ?>" rel="stylesheet" type="text/css">
    <link href="<?php echo e(URL::asset('assets/css/learners.css')); ?>" rel="stylesheet" type="text/css"> -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        @import url('https://rsms.me/inter/inter.css');
        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }
        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>

    <?php echo $__env->yieldPushContent('page-styles'); ?>
</head>
    <body>

        <div class="page">

            <?php echo $__env->make('layouts.body.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php if(Auth::check()): ?>
                <?php echo $__env->make('layouts.body.navbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endif; ?>
            <div class="container-xl my-3">
                <?php echo $__env->make('layouts.flash', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <div class="page-wrapper">
                <div>
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>
        </div>
        <script src="<?php echo e(asset('dist/js/tabler.min.js')); ?>" defer></script>
        
        <?php echo $__env->yieldPushContent('page-scripts'); ?>
    </body>
</html>
<?php /**PATH C:\laragon\www\iskiosk\resources\views/layouts/tabler.blade.php ENDPATH**/ ?>