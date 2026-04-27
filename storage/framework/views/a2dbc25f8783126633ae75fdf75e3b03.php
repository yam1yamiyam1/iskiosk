

<?php $__env->startSection('content'); ?>

<nav class="nav-top">
<img src="<?php echo e(asset('assets/img/side_header2.png')); ?>" alt="side_header" class="side">
</nav>
<main class="trackdoc-container">
  <div class="trackdoc-card">
    <h1 class="trackdoc-title">TRACK DOCUMENT</h1>
    <p class="trackdoc-subtitle">ENTER YOUR TRACKING NUMBER OR ID TO VIEW DOCUMENT PROGRESS</p>

    <label class="trackdoc-label" for="trackingNumber">Document Tracking Number</label>
    <div class="trackdoc-input-row">
      <input id="trackingNumber" type="text" class="trackdoc-input" placeholder="COR - XXXXXXXXXXX" autocomplete="off">
      <button class="btn trackdoc-btn track-btn" type="button" id="btnTrack">Track Status</button>
    </div>

    <button class="btn trackdoc-btn scan-btn" type="button" id="btnScan">Scan Barcode</button>
  </div>
</main>

<!-- Center popup for status/errors -->
<p id="trackMsg" class="verify-msg" aria-live="polite"></p>

<!-- Icons/Fonts -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500;700&display=swap" rel="stylesheet">

<div class="bottom-section">
  <button type="button" class="btn back-btn"><i class="fa-solid fa-arrow-left"></i> Back</button>
  <img src="img/footer.png" alt="Footer Tagline" class="footer-img">
  <button type="submit" class="btn continue-btn"> <i class="fa-solid fa-arrow-right"></i></button>
</div>

<!-- Modal for confirmation -->
<div id="confirmModal" style="display:none;position:fixed;z-index:9999;top:0;left:0;width:100vw;height:100vh;background:rgba(40,40,40,0.65);align-items:center;justify-content:center;">
  <div style="background:#fff;border-radius:16px;max-width:420px;width:90vw;padding:36px 32px 28px 32px;box-shadow:0 8px 32px rgba(0,0,0,0.18);display:flex;flex-direction:column;align-items:center;">
    <p id="confirmModalMsg" style="font-size:1.18rem;color:#222;text-align:center;margin-bottom:32px;margin-top:0;"></p>
    <div style="display:flex;gap:28px;">
      <button id="modalNoBtn" style="min-width:100px;padding:12px 0;font-size:1.1rem;font-family:'Roboto',sans-serif;font-weight:500;background:#7a0a0a;color:#fff;border:none;border-radius:10px;box-shadow:0 2px 8px #0001;cursor:pointer;transition:background 0.2s;">No</button>
      <button id="modalYesBtn" style="min-width:100px;padding:12px 0;font-size:1.1rem;font-family:'Roboto',sans-serif;font-weight:500;background:#fff;color:#7a0a0a;border:2px solid #7a0a0a;border-radius:10px;box-shadow:0 2px 8px #0001;cursor:pointer;transition:background 0.2s;">Yes</button>
    </div>
  </div>
</div>

<script>
  function updateDateTime() {
    const now = new Date();
    const dateOptions = { year: 'numeric', month: 'long', day: 'numeric' };
    document.getElementById('date').textContent = now.toLocaleDateString('en-US', dateOptions);
    document.getElementById('time').textContent = now.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
  }
  updateDateTime(); setInterval(updateDateTime, 1000);

  // Modal logic
  function showConfirmModal(message, onYes, onNo) {
    const modal = document.getElementById('confirmModal');
    const msg = document.getElementById('confirmModalMsg');
    const yesBtn = document.getElementById('modalYesBtn');
    const noBtn = document.getElementById('modalNoBtn');
    msg.textContent = message;
    modal.style.display = 'flex';
    function cleanup() {
      modal.style.display = 'none';
      yesBtn.onclick = null;
      noBtn.onclick = null;
    }
    yesBtn.onclick = () => { cleanup(); if(onYes) onYes(); };
    noBtn.onclick = () => { cleanup(); if(onNo) onNo(); };
  }
</script>
<script>
  // --- GLOBAL QR/BARCODE SCANNER LISTENER ---
// This will capture QR/barcode scans anywhere on the page and auto-fill the Document Tracking Number input
let qrValue = '';
let qrTimeout = null;
document.addEventListener('keydown', function (e) {
    if (e.ctrlKey || e.altKey || e.metaKey) return;
    if (e.key === 'Enter') {
        if (qrValue.length > 0) {
            const qrInput = document.getElementById('trackingNumber');
            if (qrInput) {
                qrInput.value = qrValue;
                qrInput.dispatchEvent(new Event('input', { bubbles: true }));
                goTrack(); // auto-track after scan
            }
            qrValue = '';
        }
        e.preventDefault();
        return;
    }
    if (e.key.length === 1) {
        qrValue += e.key;
        clearTimeout(qrTimeout);
        qrTimeout = setTimeout(() => qrValue = '', 100);
    }
});
document.addEventListener('DOMContentLoaded', () => {


    document.querySelector('.back-btn')?.addEventListener('click', () => {
        history.back();
    });

    let barcode = '';
    let barcodeTimeout = null;
    document.addEventListener('keydown', function (e) {

        if (e.ctrlKey || e.altKey || e.metaKey) return;


        if (e.key === 'Enter') {
            if (barcode.length > 0) {
                const trackingInput = document.getElementById('trackingNumber');
                if (trackingInput) {
                    trackingInput.value = barcode;
                    goTrack();
                }
                barcode = '';
            }
            e.preventDefault();
            return;
        }


        if (e.key.length === 1) {
            barcode += e.key;

            clearTimeout(barcodeTimeout);
            barcodeTimeout = setTimeout(() => barcode = '', 100);
        }
    });

    const input = document.getElementById("trackingNumber");
    const trackBtn = document.getElementById("btnTrack");
    const btnScan = document.getElementById("btnScan");
    const msgEl = document.getElementById("trackMsg"); // optional popup

    function showMsg(text, type = 'info', ms = 2200) {
        if (!msgEl) { alert(text); return; }
        msgEl.textContent = text || '';
        msgEl.className = `verify-msg ${type} show`;
        clearTimeout(msgEl._hideTimer);
        msgEl._hideTimer = setTimeout(() => {
            msgEl.classList.remove('show');
            msgEl.textContent = '';
        }, ms);
    }


    const looksLikeCode = raw => raw.toUpperCase().startsWith('COR-');

    function goTrack() {
        const v = (input?.value || '').trim();
        if (!v) {
            showMsg("Please enter your tracking number or ID number.", "warn");
            return;
        }

        const isCode = looksLikeCode(v);


        sessionStorage.setItem('lastTrackingType', isCode ? 'code' : 'id');
        sessionStorage.setItem('lastTrackingValue', v);


        const paramValue = isCode
            ? v.toUpperCase()
            : v.replace(/\W+/g, '').toUpperCase();

        const url = isCode
            ? `../statusDocument/statusDocument.html?code=${encodeURIComponent(paramValue)}`
            : `../statusDocument/statusDocument.html?id=${encodeURIComponent(paramValue)}`;

        window.location.assign(url);
    }


    trackBtn?.addEventListener('click', goTrack);
    input?.addEventListener('keydown', e => {
        if (e.key === 'Enter') { e.preventDefault(); goTrack(); }
    });

    // Scan button click → redirect to scan page
    btnScan?.addEventListener('click', () => {
        const lastCode = sessionStorage.getItem('lastTrackingValue');
        if (lastCode) {
            window.location.href = `../scanDocument/scanDocument.html?code=${encodeURIComponent(lastCode)}`;
        } else {
            window.location.href = `../scanDocument/scanDocument.html`;
        }
    });
});

</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kiosk', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\kiosk\resources\views\kiosk\track.blade.php ENDPATH**/ ?>