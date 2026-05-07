<?php $__env->startSection('content'); ?>

<div class="form-container">
  <h2>Submit Document</h2>
  <p class="title-sub">PLEASE FILL UP THE DETAILS BELOW</p>

  <div class="id-row">
    <div class="id-input">
      <label for="id_number">ID Number</label>
      <input type="text" id="id_number" placeholder="XXXX - XXXXX - AA - X"
        inputmode="text"
        pattern="^[0-9]{4}\s?-\s?[0-9]{5}\s?-\s?[A-Za-z]{2}\s?-\s?[0-9]$"
        title="Format: 0000 - 00000 - AA - 0">
    </div>
    <p id="verifyMsg" class="id-hint">Your information will automatically fill in if the ID has been used before.</p>
  </div>

  <hr class="section-rule">

  <form id="submitForm" action="<?php echo e(route('kiosk.store')); ?>" method="POST" novalidate>
    <?php echo csrf_field(); ?>
    <input type="hidden" id="id_number_hidden" name="id_number">

    <div class="col-4" style="margin-right: 0;">
  <label for="surname">Surname*</label>
  <input type="text" id="surname" name="surname"
    placeholder="Enter your Surname"
    pattern="^[A-Za-z\s\-']{3,}$"
    minlength="3"
    title="At least 3 characters. Letters, spaces, hyphens, and apostrophes only"
    required>
</div>

<div class="col-4">
  <label for="given_name">Given Name*</label>
  <input type="text" id="given_name" name="given_name"
    placeholder="Enter your Given Name"
    pattern="^[A-Za-z\s\-']{3,}$"
    minlength="3"
    title="At least 3 characters. Letters, spaces, hyphens, and apostrophes only"
    required>
</div>

<div class="col-4">
  <label for="middle_name">Middle Name (optional)</label>
  <input type="text" id="middle_name" name="middle_name"
    placeholder="Enter your Middle Name"
    pattern="^[A-Za-z\s\-']{3,}$"
    minlength="3"
    title="If provided, must be at least 3 characters">
</div>

    <div class="col-4">
      <label for="year_level">Year Level*</label>
      <input type="text" id="year_level" name="year_level" placeholder="e.g. 4 - 5" required>
    </div>

    <div class="col-4">
      <label for="program">Program*</label>
      <select id="program" name="program" style="width: 373px;" required>
        <option value="" disabled selected>Select your Program</option>
        <?php $__currentLoopData = $departments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $department): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($department->name); ?>"><?php echo e($department->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </div>

    <div class="col-4">
      <label for="document_type">Document Type*</label>
      <select id="document_type" name="document_type" style="width: 373px;" required>
        <option value="" disabled selected>Select Document Type</option>
        <?php $__currentLoopData = $documentTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($type->name); ?>"><?php echo e($type->name); ?></option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </div>
    
    <div class="col-6">
      <label for="email">Active Email Address*</label>
      <input type="email" id="email" name="email" placeholder="Enter your Email Address" required>
    </div>

    <div class="col-6">
      <label for="contact_number">Contact Number*</label>
      <input type="text" id="contact_number" name="contact_number" placeholder="Enter your Contact Number"
        inputmode="tel" pattern="^09\d{9}$" maxlength="11"
        oninput="validatePhone(this)" onkeypress="return event.charCode >= 48 && event.charCode <= 57"
        title="Enter a valid contact number" required>
    </div>



    <div class="bottom-section">
      <button type="button" class="btn back-btn" id="backBtn"><i class="fa-solid fa-arrow-left"></i> Back</button>
      <img src="<?php echo e(asset('assets/img/footer.png')); ?>" alt="Footer Tagline" class="footer-img"/>
      <button type="submit" class="btn continue-btn" id="submitBtn">Continue <i class="fa-solid fa-arrow-right"></i></button>
    </div>

    <p id="formMsg" class="form-msg" aria-live="polite"></p>
    <div id="loadingModal" class="loading-modal">
      <div class="loading-box">
        <div class="spinner"></div>
        <p id="loadingText">Processing...</p>
      </div>
    </div>

  </form>

  
    <div id="statusModal" class="status-modal">
      <div class="status-box">
        <div id="statusIcon" class="status-icon"></div>
        <h3 id="statusTitle"></h3>
        <p id="statusMessage"></p>
        <button id="statusBtn">OK</button>
      </div>
    </div>

    <!-- Verify Print Modal -->
    <div id="verifyPrintModal" class="status-modal">
      <div class="status-box">
        <h3 style="color: #2563eb;">Barcode Printing</h3>
        <p id="verifyPrintMessage">Your barcode is printing.<br>Did you receive the sticker?</p>
        <div style="display:flex; gap:10px; justify-content:center; margin-top: 15px;">
          <button id="verifyPrintYes" style="background:#16a34a;">Yes, I have it</button>
          <button id="verifyPrintNo" style="background:#dc2626;">No, Printer Error</button>
        </div>
      </div>
    </div>
</div>

<script>

const $ = (sel) => document.querySelector(sel);
const idVisible  = $('#id_number');
const idHidden   = $('#id_number_hidden');
const yearInput  = $('#year_level');
const verifyMsg  = $('#verifyMsg');
const form       = $('#submitForm');
const formMsg    = $('#formMsg');
const submitBtn  = $('#submitBtn');
let lastAutoScannedID = '';

const syncId = () => { idHidden.value = (idVisible.value || '').trim(); };
    idVisible.addEventListener('input', syncId);
    syncId();

    idVisible.addEventListener('input', (e) => {
      let v = e.target.value.toUpperCase().replace(/[^0-9A-Z]/g, '');
      if (v.length > 4 && v.length <= 9)       v = v.slice(0,4) + ' - ' + v.slice(4);
      else if (v.length > 9 && v.length <= 11) v = v.slice(0,4) + ' - ' + v.slice(4,9) + ' - ' + v.slice(9);
      else if (v.length > 11)                  v = v.slice(0,4) + ' - ' + v.slice(4,9) + ' - ' + v.slice(9,11) + ' - ' + v.slice(11,12);
      e.target.value = v;
      syncId();

      // Auto-run the same verification flow when ID is manually typed.
      const formattedID = (e.target.value || '').trim();
      if (isValidID(formattedID) && formattedID !== lastAutoScannedID) {
        lastAutoScannedID = formattedID;
        handleScan(formattedID);
      } else if (!isValidID(formattedID)) {
        lastAutoScannedID = '';
      }
    });

    yearInput?.addEventListener('input', (e) => {
      let v = e.target.value.replace(/[^0-9]/g, '');
      if (v.length > 1) v = v[0] + ' - ' + v.slice(1,2);
      e.target.value = v;
    });

function setMsg(el, msg, type='info') {
      if (!el) return;
      el.textContent = msg || '';
      el.className = (el.className.split(' ')[0] || '') + ' ' + (type || 'info');
    }
function showPopup(msg, success=false) { const overlay=document.createElement('div'); overlay.className='popup-overlay'; overlay.innerHTML=`<div class="popup-box"><h3 style="color:${success?'#00a000':'#d00000'};">${success?'Success':'Invalid Input'}</h3><p>${msg}</p><button id="popupClose" style="background:${success?'#00a000':'#d00000'};">OK</button></div>`; document.body.appendChild(overlay); document.getElementById('popupClose').addEventListener('click',()=>overlay.remove()); }
function isValidID(id) { return /^[0-9]{4}\s?-\s?[0-9]{5}\s?-\s?[A-Z]{2}\s?-\s?[0-9]$/.test((id||'').trim()); }
function isValidYear(val) { return /^[0-9]\s?-\s?[0-9]$/.test((val||'').trim()); }


function formatID(raw) {
  
  if (raw.startsWith('USR')) {
    return raw;
  }

  if(!raw || raw.length < 12) return raw;
  const part1 = raw.slice(0,4);
  const part2 = raw.slice(5,10);
  const part3 = raw.slice(11,13);
  const part4 = raw.slice(14,15);
  console.log(raw);
  
  return `${part1} - ${part2} - ${part3} - ${part4}`;
}

let waitingDrawer = false;
const ws = new WebSocket('ws://localhost:8081');

// --- HARDWARE MOCK MODE ---
// If the physical hardware (Arduino) is not running, we mock the WebSocket
// so kiosk testing can continue without COM devices attached.
let isMockMode = false;
const originalSend = ws.send.bind(ws);

ws.onerror = () => {
  isMockMode = true;
  console.warn("Hardware bridge unavailable. Switching to mock mode.");
};

ws.send = function (data) {
  if (isMockMode || ws.readyState !== WebSocket.OPEN) {
    try {
      const payload = JSON.parse(data);

      if (payload.command === "CHECK_DRAWER") {
        setTimeout(() => {
          ws.dispatchEvent(new MessageEvent('message', { data: "OPEN_DRAWER1" }));
        }, 500);
      } else if (payload.command === "OPEN_ALL") {
        setTimeout(() => {
          ws.dispatchEvent(new MessageEvent('message', { data: "ALL_DRAWERS_OPENED" }));
        }, 300);
      } else if (payload.command === "CLOSE_ALL") {
        setTimeout(() => {
          ws.dispatchEvent(new MessageEvent('message', { data: "ALL_DRAWERS_CLOSED" }));
        }, 300);
      }
    } catch (error) {
      console.warn("Mock send parse error:", error);
    }
    return;
  }

  return originalSend(data);
};

async function handlePrintVerification(trackingCode, barcodeBase64, fullName, drawerMsg) {
  let printRetries = 0;
  const verifyPrintModal = document.getElementById('verifyPrintModal');
  const btnYes = document.getElementById('verifyPrintYes');
  const btnNo = document.getElementById('verifyPrintNo');
  const msgEl = document.getElementById('verifyPrintMessage');
  
  msgEl.innerHTML = "Your barcode is printing.<br>Did you receive the sticker?";

  const sendPrintCommand = () => {
    ws.send(JSON.stringify({
        command: "PRINT_BARCODE",
        barcode: barcodeBase64,
        codeText: trackingCode,
        fullName: fullName
    }));
  };

  sendPrintCommand();
  verifyPrintModal.classList.add('active');

  return new Promise((resolve) => {
    btnYes.onclick = async () => {
      verifyPrintModal.classList.remove('active');
      
      try {
        await fetch('<?php echo e(route("kiosk.submit.finalize")); ?>', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
          },
          body: JSON.stringify({ tracking_code: trackingCode })
        });
      } catch (err) {
        console.error("Finalize error", err);
      }

      ws.send(JSON.stringify({ command: drawerMsg }));
      showStatusModal(
        drawerMsg === "OPEN_DRAWER1" ? "Drawer 1 Unlocked" : "Drawer 2 Unlocked",
        "Please insert your document into the drawer.<br><b>Please click OK before closing the drawer.</b><br><br><small>(Closes automatically in 30 seconds)</small>",
        "success"
      );

      let timeoutClosed = false;
      let drawerTimeout = setTimeout(() => {
        if(!timeoutClosed) {
           timeoutClosed = true;
           statusModal.classList.remove('active');
           ws.send(JSON.stringify({ command: "CLOSE_ALL" }));
           form.reset();
           idVisible.value = '';
           syncId();
        }
      }, 30000);

      statusBtn.onclick = () => {
        if(!timeoutClosed) {
          timeoutClosed = true;
          clearTimeout(drawerTimeout);
          statusModal.classList.remove('active');
          ws.send(JSON.stringify({ command: "CLOSE_ALL" }));
          form.reset();
          idVisible.value = '';
          syncId();
        }
      };
      resolve();
    };

    btnNo.onclick = async () => {
      if (printRetries === 0) {
        printRetries++;
        sendPrintCommand();
        msgEl.innerHTML = "Retrying print...<br>Did you receive the sticker?";
      } else {
        verifyPrintModal.classList.remove('active');
        
        try {
          await fetch('<?php echo e(route("kiosk.submit.printerError")); ?>', {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
            },
            body: JSON.stringify({ tracking_code: trackingCode })
          });
        } catch (err) {
          console.error("Flag error", err);
        }
        
        showStatusModal(
          "Printer Error",
          "We could not print your barcode. Please seek admin assistance.",
          "error"
        );
        statusBtn.onclick = () => {
          statusModal.classList.remove('active');
          form.reset();
          idVisible.value = '';
          syncId();
        };
        resolve();
      }
    };
  });
}

ws.addEventListener('message', async e => {
  let msg = e.data.trim();

  console.log("📩 WS message:", msg);
  console.log("⏳ waitingDrawer state:", waitingDrawer);

  if (waitingDrawer) {
    console.log("🟡 Waiting for drawer response...");

    if (msg === "OPEN_DRAWER1" || msg === "OPEN_DRAWER2") {
      console.log(`✅ MATCH: ${msg}`);
      waitingDrawer = false;

      hideLoading();

      if (window._pendingFormData) {
        try {
          showLoading("Submitting your request...");
          console.log("📡 Sending request...");

          const response = await fetch(form.action, {
            method: 'POST',
            headers: {
              'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
              'Accept': 'application/json'
            },
            body: window._pendingFormData
          });

          const data = await response.json();
          console.log("📦 Response received after drawer open:", data);

          if (data.status === 'success') {
            await handlePrintVerification(data.tracking_code, data.barcode_base64, data.full_name, msg);
          } else if (data.status === 'confirm_update') {
            hideLoading();
            let changesHtml = '<ul style="text-align:left;">';
            for (const field in data.changes) {
              changesHtml += `<li><b>${field}</b>: ${data.changes[field].old || '-'} → ${data.changes[field].new}</li>`;
            }
            changesHtml += '</ul>';

            showConfirmModal(
              `Your information does not match our records.<br><br>${changesHtml}<br>Do you want to update it?`,
              async () => {
                console.log("✅ User confirmed update");

                try {
                  const formData = new FormData(form);
                  formData.append('confirm_update', '1');

                  const resp = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                      'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                      'Accept': 'application/json'
                    },
                    body: formData
                  });

                  const respData = await resp.json();
                  if (respData.status === 'success') {
                    await handlePrintVerification(data.tracking_code, data.barcode_base64, data.full_name, msg);
                  } else {
                    showPopup('Update failed. Please try again.');
                  }
                } catch (err) {
                  console.error(err);
                  showPopup('Something went wrong.');
                }
              },
              () => {
                console.log("❌ User cancelled update");
              }
            );
          } else {
            showPopup('Something went wrong during submission.');
          }

        } catch (err) {
          console.error("🔥 Fetch error:", err);
          showPopup('Submission failed after drawer opened.');
        } finally {
          submitBtn.disabled = false;
          window._pendingFormData = null;
          hideLoading();
        }
      }

      return;
    }

    if (msg === "ALL_FULL") {
      console.log("⚠️ MATCH: ALL_FULL");
      waitingDrawer = false;

      hideLoading();
      showPopup("All drawers are full.<br>Please try again later.");
      submitBtn.disabled = false;
      window._pendingFormData = null;
      return;
    }

    console.warn("❓ Unknown message while waitingDrawer=true:", msg);
  }

  if (
    msg === "DRAWER1_FULL" ||
    msg === "DRAWER2_FULL" ||
    msg === "DRAWER1_NOT_FULL" ||
    msg === "DRAWER2_NOT_FULL" ||
    msg === "DRAWER1_OPENED" ||
    msg === "DRAWER1_CLOSED" ||
    msg === "DRAWER2_OPENED" ||
    msg === "DRAWER2_CLOSED" ||
    msg === "ALL_DRAWERS_CLOSED" ||
    msg === "ALL_DRAWERS_OPENED"
  ) {
    console.log("ℹ️ Ignored system message:", msg);
    return;
  }

  console.log("🔍 Treating as scanned ID:", msg);
  let scannedID = formatID(msg);

  console.log("🧾 Formatted ID:", scannedID);

  if (!msg.startsWith('USR')) {
    idVisible.value = scannedID;
  }
  syncId();

  console.log("📡 Calling handleScan()");
  handleScan(scannedID);
});

async function handleScan(scannedID) {
  if (!scannedID.startsWith('USR') && !isValidID(scannedID)) {
    setMsg(verifyMsg,'Scanned ID format invalid.','info');
    return;
  }
  setMsg(verifyMsg,'Checking record…','info');
  console.log('Checking record… info');
  console.log(scannedID);
  
  try {
    const res=await fetch('<?php echo e(route('kiosk.verifyID')); ?>',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'<?php echo e(csrf_token()); ?>'},body:JSON.stringify({id_number:scannedID})});
    const data=await res.json();
    if (data.type === 'user') {
      if (data.exists) {

        if (data.error) {

          showStatusModal(
            "Notice",
            data.error,
            "warning"
          );

          return;
        }
        
        setMsg(verifyMsg,'User verified.','success');

        ws.send(JSON.stringify({ command: "OPEN_ALL" }));

        showStatusModal(
          "All Drawers Unlocked",
          "Click OK before closing all drawers.",
          "success"
        );

        statusBtn.onclick = () => {
          statusModal.classList.remove('active');
          ws.send(JSON.stringify({ command: "CLOSE_ALL" }));
        };

      } else {
        setMsg(verifyMsg,'User not found.','warn');
      }

      return;
    }

    else if(data.exists){
      $('#surname').value=data.student.surname??'';
      $('#given_name').value=data.student.given_name??'';
      $('#middle_name').value=data.student.middle_name??'';
      $('#year_level').value=data.student.year_level??'';
      $('#program').value=data.student.program??'';
      $('#email').value=data.student.email??'';
      $('#contact_number').value=data.student.contact_number??'';
      setMsg(verifyMsg,'Record found. Fields auto-filled.','success');
    } else {
      setMsg(verifyMsg,'No existing record. You can proceed.','warn');
    }
  } catch(err){ console.error(err); setMsg(verifyMsg,'Unable to verify right now.','error'); }
}

form.addEventListener('submit', async e => {
  e.preventDefault();
  console.log("🚀 Submit triggered");

  const idVal = idVisible.value.trim();
  const yearVal = yearInput?.value.trim() || '';

  console.log("🧾 ID:", idVal);
  console.log("🎓 Year:", yearVal);

  if(!isValidID(idVal)){
    console.warn("❌ Invalid ID");
    showPopup('Please enter a valid ID format:<br><b>XXXX - XXXXX - AA - X</b>');
    return;
  }

  if(!isValidYear(yearVal)){
    console.warn("❌ Invalid Year");
    showPopup('Please enter a valid Year Level format:<br><b>X - X</b>');
    return;
  }

  if(!form.checkValidity()){
    console.warn("❌ Form validation failed");
    form.reportValidity();
    return;
  }

  submitBtn.disabled = true;


  showLoading("Checking available drawer...");
  console.log("🗄️ Waiting for drawer availability...");

  window._pendingFormData = new FormData(form);
  waitingDrawer = true;

  ws.send(JSON.stringify({command:"CHECK_DRAWER"}));
});

$('#backBtn')?.addEventListener('click',()=>history.back());
function validatePhone(input){let value=input.value;if(value.length===1&&value!=='0')input.value='0';if(value.length===2&&value!=='09')input.value='09';}

const loadingModal = document.getElementById('loadingModal');
const loadingText = document.getElementById('loadingText');

function showLoading(message = "Please wait...") {
  loadingText.textContent = message;
  loadingModal.classList.add('active');
}

function hideLoading() {
  loadingModal.classList.remove('active');
}

const statusModal = document.getElementById('statusModal');
const statusTitle = document.getElementById('statusTitle');
const statusMessage = document.getElementById('statusMessage');
const statusIcon = document.getElementById('statusIcon');
const statusBtn = document.getElementById('statusBtn');

function showStatusModal(title, message, type = "info") {
  statusTitle.textContent = title;
  statusMessage.innerHTML = message;

  if(type === "success"){
    statusIcon.textContent = "✔️";
    statusTitle.style.color = "#16a34a";
  } else if(type === "error"){
    statusIcon.textContent = "❌";
    statusTitle.style.color = "#dc2626";
  } else {
    statusIcon.textContent = "ℹ️";
    statusTitle.style.color = "#2563eb";
  }

  statusModal.classList.add('active');
}

statusBtn.addEventListener('click', () => {
  statusModal.classList.remove('active');
});

function showConfirmModal(message, onConfirm, onCancel){
  const overlay = document.createElement('div');
  overlay.className = 'popup-overlay';

  overlay.innerHTML = `
    <div class="popup-box">
      <h3 style="color:#d97706;">Confirm Update</h3>
      <p>${message}</p>
      <div style="display:flex; gap:10px; justify-content:center;">
        <button id="confirmYes" style="background:#16a34a;">Confirm</button>
        <button id="confirmNo" style="background:#dc2626;">Cancel</button>
      </div>
    </div>
  `;

  document.body.appendChild(overlay);

  document.getElementById('confirmYes').onclick = () => {
    overlay.remove();
    onConfirm && onConfirm();
  };

  document.getElementById('confirmNo').onclick = () => {
    overlay.remove();
    onCancel && onCancel();
  };
}

let kioskTimeout;
const timeoutDuration = 100 * 60 * 1000;

function resetKioskTimeout() {
  clearTimeout(kioskTimeout);
  kioskTimeout = setTimeout(() => {
    window.location.href = "<?php echo e(route('kiosk.home')); ?>";
  }, timeoutDuration);
}

['mousemove', 'keydown', 'click', 'touchstart'].forEach(evt => {
  document.addEventListener(evt, resetKioskTimeout);
});

resetKioskTimeout();
</script>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const urlParams = new URLSearchParams(window.location.search);
  const scannedFromHome = urlParams.get('scan');

  if (scannedFromHome) {
    handleScan(scannedFromHome);
  }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.kiosk', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\iskiosk\resources\views/kiosk/submit.blade.php ENDPATH**/ ?>