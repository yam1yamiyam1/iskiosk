
<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
        <title>IsKiosk: Document Management System</title>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('assets/ico/PUPLogo.ico')); ?>" />
    <?php
        $route = Route::currentRouteName();
    ?>

    <?php if($route === 'kiosk.submit'): ?>
        <link href="<?php echo e(asset('assets/css/submit.css')); ?>" rel="stylesheet"/>
        
    <?php elseif($route === 'kiosk.track.form'): ?>
        <link href="<?php echo e(asset('assets/css/track.css')); ?>" rel="stylesheet"/>
    <?php else: ?>
        <link href="<?php echo e(asset('assets/css/kioskhome.css')); ?>" rel="stylesheet"/>
    <?php endif; ?>


</head>
    <body>

        <header class="header">
            <img src="<?php echo e(asset('assets/img/header.png')); ?>" alt="Header Image" class="header-img">

            <div class="datetime">
                <span class="date" id="date"></span>
                <span class="time" id="time"></span>
            </div>
            </header>

                    <?php echo $__env->yieldContent('content'); ?>
  
        <div class="bottom-section">
        
        <img src="<?php echo e(asset('assets/img/footer.png')); ?>" alt="Footer Tagline" class="footer-img" />
        
        </div>
<script>
    function updateDateTime() {
      const now = new Date();
      const dateOptions = { year: 'numeric', month: 'long', day: 'numeric' };
      document.getElementById('date').textContent = now.toLocaleDateString('en-US', dateOptions);
      document.getElementById('time').textContent = now.toLocaleTimeString('en-US', {
        hour: 'numeric', minute: '2-digit', hour12: true
      });
    }
    updateDateTime();
    setInterval(updateDateTime, 1000);
</script>
</body>
</html>
<?php /**PATH C:\laragon\www\iskiosk\resources\views/layouts/kiosk.blade.php ENDPATH**/ ?>