@extends('layouts.kiosk')

@section('content')

<nav class="nav-top">
<img src="{{ asset('assets/img/side_header3.png') }}" alt="side_header" class="side">
</nav>
<div class="form-container" style="text-align: center; min-height: 50vh; display: flex; flex-direction: column; justify-content: center; align-items: center;">

  <!-- Step 1: Idle State -->
  <div id="step-1">
    <h2>Claim Document</h2>
    <p class="title-sub">SCAN YOUR DOCUMENT BARCODE TO CLAIM IT</p>
    <div style="margin-top: 30px;">
      <i class="fa-solid fa-barcode" style="font-size: 80px; color: #7f1d1d;"></i>
    </div>
  </div>

  <!-- Step 2: Confirm State -->
  <div id="step-2" style="display: none; width: 100%;">
    <h2>Document Details</h2>
    <p class="title-sub">PLEASE VERIFY THE INFORMATION BELOW</p>
    <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; text-align: left; max-width: 500px; margin: 20px auto;">
        <p><strong>Tracking Code:</strong> <span id="doc-tracking"></span></p>
        <p><strong>Name:</strong> <span id="doc-name"></span></p>
        <p><strong>Type:</strong> <span id="doc-type"></span></p>
    </div>
    <div class="bottom-section" style="justify-content: center; gap: 20px;">
      <button type="button" class="btn back-btn" id="cancelBtn" style="width: auto; padding: 10px 20px;"><i class="fa-solid fa-xmark"></i> Cancel</button>
      <button type="button" class="btn continue-btn" id="confirmBtn" style="width: auto; padding: 10px 20px;">Confirm Claim <i class="fa-solid fa-check"></i></button>
    </div>
  </div>

  <!-- Step 3: Success State -->
  <div id="step-3" style="display: none;">
    <h2 style="color: #16a34a;">Successfully Claimed</h2>
    <p class="title-sub">YOUR DOCUMENT HAS BEEN CLAIMED</p>
    <div style="margin-top: 30px;">
      <i class="fa-solid fa-circle-check" style="font-size: 80px; color: #16a34a;"></i>
    </div>
    <p style="margin-top: 20px; font-size: 1.2rem;">Tracking Code: <strong id="success-tracking"></strong></p>
    <div style="margin-top: 30px;">
      <button type="button" class="btn continue-btn" id="scanAnotherBtn" style="width: auto; padding: 10px 20px;">Scan Another Document</button>
    </div>
  </div>

  <!-- Test Mode Toggle -->
  <div style="position: fixed; bottom: 20px; right: 20px; background: rgba(0,0,0,0.1); padding: 10px; border-radius: 8px; text-align: right;">
    <label style="font-size: 12px; cursor: pointer;">
      <input type="checkbox" id="testModeToggle"> Enable Test Mode
    </label>
    <div id="testModeContainer" style="display: none; margin-top: 10px;">
      <input type="text" id="testBarcode" placeholder="Enter Tracking Code" style="padding: 8px; font-size: 14px; width: 200px;">
      <button id="testSubmit" style="padding: 8px 12px; background: #7f1d1d; color: white; border: none; cursor: pointer;">Simulate Scan</button>
    </div>
  </div>

  <div id="loadingModal" class="loading-modal">
    <div class="loading-box">
      <div class="spinner"></div>
      <p id="loadingText">Processing...</p>
    </div>
  </div>

  <div id="statusModal" class="status-modal">
    <div class="status-box">
      <div id="statusIcon" class="status-icon"></div>
      <h3 id="statusTitle"></h3>
      <p id="statusMessage"></p>
      <button id="statusBtn">OK</button>
    </div>
  </div>

</div>

<script>
const $ = (sel) => document.querySelector(sel);
let currentStep = 1;
let currentTrackingCode = null;
let idleTimer = null;
const IDLE_TIMEOUT = 30000; // 30 seconds

// Barcode scanner buffer
let barcodeBuffer = '';
let barcodeTimeout = null;

// UI Elements
const step1 = $('#step-1');
const step2 = $('#step-2');
const step3 = $('#step-3');
const loadingModal = $('#loadingModal');
const loadingText = $('#loadingText');
const statusModal = $('#statusModal');

function resetIdleTimer() {
    clearTimeout(idleTimer);
    if (currentStep !== 1) {
        idleTimer = setTimeout(() => {
            goToStep(1);
        }, IDLE_TIMEOUT);
    }
}

function showLoading(msg) {
    loadingText.textContent = msg;
    loadingModal.classList.add('active');
}

function hideLoading() {
    loadingModal.classList.remove('active');
}

function showError(title, msg) {
    $('#statusTitle').textContent = title;
    $('#statusTitle').style.color = "#dc2626";
    $('#statusIcon').textContent = "❌";
    $('#statusMessage').textContent = msg;
    statusModal.classList.add('active');
}

$('#statusBtn').addEventListener('click', () => {
    statusModal.classList.remove('active');
    if (currentStep !== 2 && currentStep !== 3) {
        goToStep(1);
    }
});

function goToStep(step) {
    currentStep = step;
    step1.style.display = 'none';
    step2.style.display = 'none';
    step3.style.display = 'none';

    if (step === 1) {
        step1.style.display = 'block';
        currentTrackingCode = null;
        clearTimeout(idleTimer);
    } else if (step === 2) {
        step2.style.display = 'block';
        resetIdleTimer();
    } else if (step === 3) {
        step3.style.display = 'block';
        resetIdleTimer();
    }
}

// Global events to reset timer
['mousemove', 'keydown', 'click', 'touchstart'].forEach(evt => {
    document.addEventListener(evt, () => {
        if (currentStep !== 1) resetIdleTimer();
    });
});

// Barcode reading logic
document.addEventListener('keydown', (e) => {
    if ($('#testModeToggle').checked && document.activeElement === $('#testBarcode')) {
        return; // Ignore if typing in test mode
    }

    if (currentStep !== 1) return; // Only accept scans on Step 1

    if (e.key === 'Enter') {
        if (barcodeBuffer.length > 0) {
            handleScan(barcodeBuffer.trim());
            barcodeBuffer = '';
        }
    } else if (e.key.length === 1) {
        barcodeBuffer += e.key;
        clearTimeout(barcodeTimeout);
        barcodeTimeout = setTimeout(() => {
            barcodeBuffer = '';
        }, 100); // 100ms debounce
    }
});

// Test Mode logic
$('#testModeToggle').addEventListener('change', (e) => {
    $('#testModeContainer').style.display = e.target.checked ? 'block' : 'none';
});

$('#testSubmit').addEventListener('click', () => {
    if (currentStep !== 1) {
        alert("Please go back to Step 1 to scan.");
        return;
    }
    const val = $('#testBarcode').value.trim();
    if (val) {
        handleScan(val);
        $('#testBarcode').value = '';
    }
});

async function handleScan(code) {
    showLoading("Verifying document...");
    try {
        const res = await fetch('{{ route('kiosk.claim.verify') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ tracking_code: code })
        });
        const data = await res.json();
        
        hideLoading();

        if (data.status === 'success') {
            currentTrackingCode = data.document.tracking_code;
            $('#doc-tracking').textContent = data.document.tracking_code;
            $('#doc-name').textContent = data.document.full_name;
            $('#doc-type').textContent = data.document.document_type;
            goToStep(2);
        } else {
            showError("Invalid Document", data.message || "Document not found or not ready for claiming.");
        }
    } catch (err) {
        console.error(err);
        hideLoading();
        showError("Error", "An unexpected error occurred while verifying.");
    }
}

$('#cancelBtn').addEventListener('click', () => {
    goToStep(1);
});

$('#confirmBtn').addEventListener('click', async () => {
    if (!currentTrackingCode) return;
    
    showLoading("Claiming document...");
    try {
        const res = await fetch('{{ route('kiosk.claim.confirm') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ tracking_code: currentTrackingCode })
        });
        const data = await res.json();
        
        hideLoading();

        if (data.status === 'success') {
            $('#success-tracking').textContent = data.tracking_code;
            goToStep(3);
        } else {
            showError("Claim Failed", data.message || "Could not claim document.");
        }
    } catch (err) {
        console.error(err);
        hideLoading();
        showError("Error", "An unexpected error occurred while claiming.");
    }
});

$('#scanAnotherBtn').addEventListener('click', () => {
    goToStep(1);
});

// Initial Setup
goToStep(1);
</script>

@endsection
