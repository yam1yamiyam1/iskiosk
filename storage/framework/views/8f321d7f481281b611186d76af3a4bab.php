

<?php $__env->startSection('content'); ?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Hardware Testing Panel</h2>
                <div class="text-muted">Control printer, scanner, and drawers</div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">

        <!-- STATUS -->
        <div class="alert alert-info" id="connection-status">
            Connecting to hardware...
        </div>

        <div class="row row-deck row-cards">

            <!-- PRINTER -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header"><strong>Printer</strong></div>
                    <div class="card-body">
                        <input type="text" id="barcode-input" class="form-control mb-2" placeholder="Enter barcode text">
                        <button class="btn btn-primary w-100" onclick="printBarcode()">
                            Print Barcode
                        </button>
                    </div>
                </div>
            </div>

            <!-- DRAWERS -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header"><strong>Drawers</strong></div>
                    <div class="card-body d-grid gap-2">

                        <button class="btn btn-success" onclick="sendCommand('OPEN_DRAWER1')">Open Drawer 1</button>
                        <button class="btn btn-warning" onclick="sendCommand('CLOSE_DRAWER1')">Close Drawer 1</button>

                        <button class="btn btn-success" onclick="sendCommand('OPEN_DRAWER2')">Open Drawer 2</button>
                        <button class="btn btn-warning" onclick="sendCommand('CLOSE_DRAWER2')">Close Drawer 2</button>

                        <hr>

                        <button class="btn btn-primary" onclick="sendCommand('OPEN_ALL')">Open All</button>
                        <button class="btn btn-secondary" onclick="sendCommand('CLOSE_ALL')">Close All</button>

                        <button class="btn btn-dark" onclick="sendCommand('CHECK_DRAWER')">Check Status</button>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header"><strong>Scanner / Logs</strong></div>
                    <div class="card-body">
                        <textarea id="logs" class="form-control" rows="10" readonly></textarea>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('page-scripts'); ?>
<script>
    let ws;

    function connectWS() {
        ws = new WebSocket('ws://localhost:8081');

        ws.onopen = () => {
            document.getElementById('connection-status').innerText = 'Connected to hardware';
            document.getElementById('connection-status').classList.remove('alert-info');
            document.getElementById('connection-status').classList.add('alert-success');
        };

        ws.onmessage = (event) => {
            appendLog(event.data);
        };

        ws.onclose = () => {
            document.getElementById('connection-status').innerText = 'Disconnected. Reconnecting...';
            document.getElementById('connection-status').classList.add('alert-danger');
            setTimeout(connectWS, 2000);
        };
    }

    function sendCommand(command) {
        if (!ws || ws.readyState !== WebSocket.OPEN) {
            alert('WebSocket not connected');
            return;
        }

        ws.send(JSON.stringify({ command }));
        appendLog('Sent: ' + command);
    }

    function printBarcode() {
        const codeText = document.getElementById('barcode-input').value;

        if (!codeText) {
            alert('Enter barcode text');
            return;
        }

        ws.send(JSON.stringify({
            command: 'PRINT_BARCODE',
            codeText: codeText,
            fullName: codeText
        }));

        appendLog('Printing: ' + codeText);
    }

    function appendLog(message) {
        const logs = document.getElementById('logs');
        logs.value += message + "\n";
        logs.scrollTop = logs.scrollHeight;
    }

    connectWS();
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.tabler', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\kiosk\resources\views/admin/testing.blade.php ENDPATH**/ ?>