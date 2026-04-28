const { SerialPort } = require('serialport');
const { ReadlineParser } = require('@serialport/parser-readline');
const { WebSocketServer } = require('ws');
const { exec } = require('child_process');

// --- CONFIGURATION ---
const COM_PORT = 'COM3'; // CHANGE THIS TO YOUR SCANNER's COM PORT
const BAUD_RATE = 9600;
const WS_PORT = 8081;
const TARGET_URL = 'http://192.168.40.156:8000/kiosk/claim';
const WINDOW_TITLE = 'IsKiosk'; // Matches the webpage <title>
// ---------------------

const wss = new WebSocketServer({ port: WS_PORT });

console.log(`[WebSocket] Server started on port ${WS_PORT}`);

// Window Management Helper
function executePowerShell(script) {
    const command = `powershell -NoProfile -ExecutionPolicy Bypass -Command "${script.replace(/\n/g, ' ')}"`;
    exec(command, (error) => {
        if (error) console.error(`PowerShell Error: ${error.message}`);
    });
}

function maximizeWindow() {
    console.log('[Window] Bringing claim window to front...');
    const ps = `
        $sig = '[DllImport("user32.dll")] public static extern bool ShowWindowAsync(IntPtr hWnd, int nCmdShow); [DllImport("user32.dll")] public static extern bool SetForegroundWindow(IntPtr hWnd);'
        Add-Type -MemberDefinition $sig -name NativeMethods -namespace Win32
        $procs = Get-Process chrome -ErrorAction SilentlyContinue | Where-Object { $_.MainWindowTitle -match "${WINDOW_TITLE}" }
        foreach ($p in $procs) {
            [Win32.NativeMethods]::ShowWindowAsync($p.MainWindowHandle, 3) | Out-Null
            [Win32.NativeMethods]::SetForegroundWindow($p.MainWindowHandle) | Out-Null
        }
    `;
    executePowerShell(ps);
}

function minimizeWindow() {
    console.log('[Window] Minimizing claim window...');
    const ps = `
        $sig = '[DllImport("user32.dll")] public static extern bool ShowWindowAsync(IntPtr hWnd, int nCmdShow);'
        Add-Type -MemberDefinition $sig -name NativeMethods -namespace Win32
        $procs = Get-Process chrome -ErrorAction SilentlyContinue | Where-Object { $_.MainWindowTitle -match "${WINDOW_TITLE}" }
        foreach ($p in $procs) {
            [Win32.NativeMethods]::ShowWindowAsync($p.MainWindowHandle, 2) | Out-Null
        }
    `;
    executePowerShell(ps);
}

// Initial Launch
console.log(`[Browser] Launching browser to ${TARGET_URL}...`);
exec(`start chrome --app=${TARGET_URL}`, (err) => {
    if (!err) {
        setTimeout(minimizeWindow, 3000); // Minimize automatically after 3 seconds of loading
    }
});

// Broadcast to all connected websocket clients
function broadcast(message) {
    wss.clients.forEach(client => {
        if (client.readyState === 1) { // OPEN
            client.send(message);
        }
    });
}

// Handle messages from the browser
wss.on('connection', (ws) => {
    console.log('[WebSocket] Client connected.');
    ws.on('message', (message) => {
        try {
            const data = JSON.parse(message);
            if (data.command === 'minimize') {
                minimizeWindow();
            }
        } catch (e) {
            console.error('[WebSocket] Invalid JSON received:', message.toString());
        }
    });
});

// Setup Serial Port
try {
    const port = new SerialPort({ path: COM_PORT, baudRate: BAUD_RATE });
    const parser = port.pipe(new ReadlineParser({ delimiter: '\r\n' }));

    port.on('open', () => {
        console.log(`[Serial] Connected to scanner on ${COM_PORT}`);
    });

    port.on('error', (err) => {
        console.error(`[Serial] Error: `, err.message);
    });

    parser.on('data', (data) => {
        const barcode = data.trim();
        console.log(`[Scanner] Scanned: ${barcode}`);
        
        maximizeWindow();
        
        // Wait briefly for window to pop up before sending the scan data to Chrome
        setTimeout(() => {
            broadcast(barcode);
        }, 300);
    });

} catch (err) {
    console.error(`[Serial] Failed to initialize COM port:`, err.message);
}
