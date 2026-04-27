

<?php $__env->startSection('content'); ?>

<nav class="nav-top">
<img src="<?php echo e(asset('assets/img/side_header.png')); ?>" alt="side_header" class="side">
</nav>
<div class="container">
  <section class="welcome-section">
    <h1 class="welcome-title">WELCOME TO ISKIOSK DMS </h1>
  </section>


<div class="services">
  <button class="service-btn" onclick="location.href='<?php echo e(route('kiosk.submit')); ?>'">
    <img src="<?php echo e(asset('assets/img/icon_1.png')); ?>" alt="Submit Icon" class="icon">
    Submit Document
    </button>
</div>

<script>
  let kioskTimeout;
  const timeoutDuration = 1 * 60 * 1000;

  function resetKioskTimeout() {
    clearTimeout(kioskTimeout);
    kioskTimeout = setTimeout(() => {
      window.location.href = "<?php echo e(route('kiosk.carousel')); ?>";
    }, timeoutDuration);
  }

  ['mousemove', 'keydown', 'click', 'touchstart'].forEach(evt => {
    document.addEventListener(evt, resetKioskTimeout);
  });

  resetKioskTimeout();
</script>

<script>
const ws = new WebSocket('ws://localhost:8081');

function formatID(raw) {
  if (!raw) return raw;
  return raw;
}

ws.addEventListener('message', (e) => {
  let msg = e.data.trim();

  console.log("📩 Home scan:", msg);

  if (msg.startsWith('USR')) {
    console.log("🧾 Scanned ID on home:", msg);

    window.location.href = "<?php echo e(route('kiosk.submit')); ?>?scan=" + encodeURIComponent(msg);
  }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kiosk', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\kiosk\resources\views/kiosk/home.blade.php ENDPATH**/ ?>