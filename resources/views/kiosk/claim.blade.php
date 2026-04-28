@extends('layouts.kiosk')

@section('content')


<div class="form-container" style="display: flex; flex-direction: column; justify-content: center; align-items: center; min-height: 60vh;">

    <!-- Step 1: Idle State -->
    <div id="step-1" style="text-align: center;">
        <h2>Claim Document</h2>
        <p class="title-sub">SCAN YOUR DOCUMENT BARCODE TO CLAIM IT</p>
        <div style="margin-top: 40px;">
            <i class="fa-solid fa-barcode" style="font-size: clamp(60px, 8vw, 90px); color: #8B1A1A; opacity: 0.8;"></i>
        </div>
        <p style="margin-top: 18px; color: #aaa; font-size: clamp(12px, 1.1vw, 15px); font-family: 'Roboto', sans-serif;">Waiting for scan...</p>
    </div>

    <!-- Step 2: Confirm State -->
    <div id="step-2" style="display: none; width: 100%; max-width: 560px; text-align: center;">
        <h2>Document Details</h2>
        <p class="title-sub">PLEASE VERIFY THE INFORMATION BELOW</p>

        <div style="background: #f8fafc; border: 1px solid #C47A7A; border-radius: 16px; padding: 24px 32px; text-align: left; margin: 20px auto;">
            <table style="width: 100%; border-collapse: collapse; font-family: 'Roboto', sans-serif;">
                <tr>
                    <td style="padding: 10px 0; color: #666; font-size: clamp(12px, 1.1vw, 14px); width: 140px; border-bottom: 1px solid #e2e8f0;">Tracking Code</td>
                    <td style="padding: 10px 0; font-weight: 700; color: #8B1A1A; font-size: clamp(12px, 1.1vw, 14px); border-bottom: 1px solid #e2e8f0;" id="doc-tracking"></td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: #666; font-size: clamp(12px, 1.1vw, 14px); border-bottom: 1px solid #e2e8f0;">Name</td>
                    <td style="padding: 10px 0; font-weight: 700; color: #1f2937; font-size: clamp(12px, 1.1vw, 14px); border-bottom: 1px solid #e2e8f0;" id="doc-name"></td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: #666; font-size: clamp(12px, 1.1vw, 14px);">Document Type</td>
                    <td style="padding: 10px 0; font-weight: 700; color: #1f2937; font-size: clamp(12px, 1.1vw, 14px);" id="doc-type"></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Step 3: Success State -->
    <div id="step-3" style="display: none; text-align: center;">
        <h2 style="color: #16a34a;">Successfully Claimed!</h2>
        <p class="title-sub" style="color: #16a34a;">YOUR DOCUMENT HAS BEEN CLAIMED</p>
        <div style="margin-top: 30px;">
            <i class="fa-solid fa-circle-check" style="font-size: clamp(60px, 8vw, 90px); color: #16a34a;"></i>
        </div>
        <p style="margin-top: 20px; font-size: clamp(13px, 1.3vw, 16px); font-family: 'Roboto', sans-serif; color: #374151;">
            Tracking Code: <strong id="success-tracking" style="color: #8B1A1A;"></strong>
        </p>
        <p style="color: #aaa; font-size: clamp(11px, 1vw, 13px); margin-top: 6px; font-family: 'Roboto', sans-serif;">
            You may scan another document to claim.
        </p>
    </div>

</div>

<!-- Bottom buttons: Step 2 — mirrors submit page layout (back | footer | continue) -->
<div id="bottom-step-2" class="bottom-section" style="display: none;">
    <button type="button" class="btn back-btn" id="cancelBtn">
        <i class="fa-solid fa-xmark"></i> Cancel
    </button>
    <button type="button" class="btn continue-btn" id="confirmBtn">
        Confirm Claim <i class="fa-solid fa-check"></i>
    </button>
</div>

<!-- Bottom button: Step 3 — button on the right -->
<div id="bottom-step-3" class="bottom-section" style="display: none;">
    <div style="flex: 1;"></div>
    <button type="button" class="btn continue-btn" id="scanAnotherBtn">
        <i class="fa-solid fa-barcode"></i> Scan Another Document
    </button>
</div>




{{-- ============================================================
     TEST MODE PANEL
     TO REMOVE FOR PRODUCTION: Delete this entire block.
     ============================================================ --}}
<div id="testModePanel" style="position: fixed; bottom: 20px; right: 20px; background: #1e293b; border-radius: 10px; padding: 12px 16px; z-index: 1100; min-width: 230px; box-shadow: 0 4px 16px rgba(0,0,0,0.35);">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
        <span style="font-size: 10px; font-weight: 700; letter-spacing: 0.1em; color: #f59e0b; text-transform: uppercase;">⚙ Dev / Test Mode</span>
        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; font-size: 12px; color: #94a3b8; margin: 0;">
            <input type="checkbox" id="testModeToggle" style="accent-color: #f59e0b;"> On
        </label>
    </div>
    <div id="testModeContainer" style="display: none;">
        <input type="text" id="testBarcode" placeholder="Enter Tracking Code"
            style="width: 100%; box-sizing: border-box; padding: 7px 10px; font-size: 13px; border-radius: 6px !important; border: 1px solid #475569 !important; background: #0f172a; color: #f1f5f9; margin-bottom: 8px; min-height: unset; box-shadow: none !important;">
        <button id="testSubmit"
            style="width: 100%; padding: 8px; background: #8B1A1A; color: white; border: none; border-radius: 6px !important; font-size: 13px; cursor: pointer; font-weight: 600; font-family: 'Roboto', sans-serif; height: auto;">
            Simulate Scan
        </button>
    </div>
</div>
{{-- ============================================================
     END TEST MODE PANEL
     ============================================================ --}}

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

<script>
const $ = (sel) => document.querySelector(sel);

let currentStep = 1;
let currentTrackingCode = null;
let idleTimer = null;
const IDLE_TIMEOUT = 30000;

// UI references
const step1        = $('#step-1');
const step2        = $('#step-2');
const step3        = $('#step-3');
const bottomStep2  = $('#bottom-step-2');
const bottomStep3  = $('#bottom-step-3');

const loadingModal = $('#loadingModal');
const loadingText  = $('#loadingText');
const statusModal  = $('#statusModal');

// ─── BARCODE PERIPHERAL ───────────────────────────────────────────────────────
// Uses the WebSocket serial bridge to capture barcode scans.
function initBarcodePeripheral() {
    const ws = new WebSocket('ws://localhost:8081');

    ws.addEventListener('message', async e => {
        if (currentStep !== 1) return; // Only process scans in Step 1

        let msg = e.data.trim();
        console.log("📩 Scan received via WebSocket:", msg);
        
        if (msg) {
            handleScan(msg);
        }
    });

    ws.addEventListener('open', () => {
        console.log("✅ WebSocket connected for scanner.");
    });

    ws.addEventListener('error', (err) => {
        console.error("❌ WebSocket error:", err);
    });
}

initBarcodePeripheral();
// ─── END BARCODE PERIPHERAL ───────────────────────────────────────────────────

function showLoading(msg) {
    loadingText.textContent = msg;
    loadingModal.classList.add('active');
}
function hideLoading() {
    loadingModal.classList.remove('active');
}
function showError(title, msg) {
    $('#statusTitle').textContent = title;
    $('#statusTitle').style.color = '#dc2626';
    $('#statusIcon').textContent  = '❌';
    $('#statusMessage').textContent = msg;
    statusModal.classList.add('active');
}

$('#statusBtn').addEventListener('click', () => {
    statusModal.classList.remove('active');
    if (currentStep !== 2 && currentStep !== 3) goToStep(1);
});

function resetIdleTimer() {
    clearTimeout(idleTimer);
    if (currentStep !== 1) {
        idleTimer = setTimeout(() => goToStep(1), IDLE_TIMEOUT);
    }
}

function goToStep(step) {
    currentStep = step;

    step1.style.display       = 'none';
    step2.style.display       = 'none';
    step3.style.display       = 'none';
    bottomStep2.style.display = 'none';
    bottomStep3.style.display = 'none';
    if (step === 1) {
        step1.style.display      = 'block';
        currentTrackingCode      = null;
        clearTimeout(idleTimer);
    } else if (step === 2) {
        step2.style.display       = 'block';
        bottomStep2.style.display = 'flex';
        resetIdleTimer();
    } else if (step === 3) {
        step3.style.display       = 'block';
        bottomStep3.style.display = 'flex';
        resetIdleTimer();
    }
}

['mousemove', 'keydown', 'click', 'touchstart'].forEach(evt => {
    document.addEventListener(evt, () => { if (currentStep !== 1) resetIdleTimer(); });
});

async function handleScan(code) {
    showLoading('Verifying document...');
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
            currentTrackingCode            = data.document.tracking_code;
            $('#doc-tracking').textContent  = data.document.tracking_code;
            $('#doc-name').textContent      = data.document.full_name;
            $('#doc-type').textContent      = data.document.document_type;
            goToStep(2);
        } else {
            showError('Invalid Document', data.message || 'Document not found or not ready for claiming.');
        }
    } catch (err) {
        console.error(err);
        hideLoading();
        showError('Error', 'An unexpected error occurred while verifying.');
    }
}

$('#cancelBtn').addEventListener('click', () => goToStep(1));

$('#confirmBtn').addEventListener('click', async () => {
    if (!currentTrackingCode) return;
    showLoading('Claiming document...');
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
            showError('Claim Failed', data.message || 'Could not claim document.');
        }
    } catch (err) {
        console.error(err);
        hideLoading();
        showError('Error', 'An unexpected error occurred while claiming.');
    }
});

$('#scanAnotherBtn').addEventListener('click', () => goToStep(1));

// ─── TEST MODE ────────────────────────────────────────────────────────────────
// Remove this entire block when deploying to the actual kiosk.
$('#testModeToggle').addEventListener('change', (e) => {
    $('#testModeContainer').style.display = e.target.checked ? 'block' : 'none';
});
$('#testSubmit').addEventListener('click', () => {
    if (currentStep !== 1) { alert('Please wait until Step 1 to scan.'); return; }
    const val = $('#testBarcode').value.trim();
    if (val) { handleScan(val); $('#testBarcode').value = ''; }
});
$('#testBarcode').addEventListener('keydown', (e) => {
    if (e.key === 'Enter') $('#testSubmit').click();
});
// ─── END TEST MODE ────────────────────────────────────────────────────────────

goToStep(1);
</script>

@endsection