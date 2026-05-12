const { SerialPort } = require('serialport');
const WebSocket = require('ws');
const { exec } = require('child_process');
const fs = require('fs');
const path = require('path');

const SCANNER_PORT = 'COM4';
const ARDUINO_PORT = 'COM5';
const BAUD_RATE = 9600;
const PRINTER_NAME = 'POS58';

const scanner = new SerialPort({ path: SCANNER_PORT, baudRate: BAUD_RATE });
const arduino = new SerialPort({ path: ARDUINO_PORT, baudRate: BAUD_RATE });

const wss = new WebSocket.Server({ port: 8081 });
let clients = [];


function printBarcode(text, fullName) {
  console.log('Preparing barcode print for:', text, fullName);

  const tempFile = path.join(__dirname, 'print.raw');

  const barcodeData = Buffer.from(String(text).trim(), 'ascii');
  const nameData = Buffer.from(String(fullName).trim(), 'ascii');

  if (barcodeData.length > 250) {
    console.error('Barcode too long:', barcodeData.length);
    return;
  }

  const data = Buffer.concat([
    Buffer.from([0x1B, 0x40]),

    Buffer.from([0x1B, 0x61, 0x01]),

    Buffer.from([0x1D, 0x48, 0x00]),

    Buffer.from([0x1D, 0x77, 0x02]),
    Buffer.from([0x1D, 0x68, 0x50]),

    Buffer.from([0x1D, 0x6B, 0x49]),
    Buffer.from([barcodeData.length]),
    barcodeData,

    Buffer.from([0x0A]),

    Buffer.from([0x1B, 0x45, 0x01]),
    nameData,
    Buffer.from([0x1B, 0x45, 0x00]),

    Buffer.from([0x0A, 0x0A, 0x0A]),

    Buffer.from([0x1D, 0x56, 0x42, 0x00])
  ]);

  fs.writeFileSync(tempFile, data);

  const cmd = `copy /B "${tempFile}" "\\\\127.0.0.1\\${PRINTER_NAME}"`;

  exec(cmd, (error) => {
    if (error) {
      console.error('Print Error:', error);
    } else {
      console.log('Printed barcode only:', text);
    }

    setTimeout(() => {
      if (fs.existsSync(tempFile)) fs.unlinkSync(tempFile);
    }, 1000);
  });
}

wss.on('connection', ws => {
  console.log('New WebSocket client connected');
  clients.push(ws);
  
  ws.on('message', msg => {
    console.log('Received WS message:', msg.toString());
    try {
      const data = JSON.parse(msg);
      if (data.command === 'PRINT_BARCODE') {
        if (data.codeText && data.fullName) {
          printBarcode(data.codeText, data.fullName);
        } else {
          console.log('Missing barcode or full name. Skipping print.');
        }
      } else {
        if (arduino.isOpen) {
          console.log('Sending command to Arduino:', data.command);
          arduino.write(data.command + '\n');
        } else {
          console.log('Arduino port is not open');
        }
      }
    } catch (e) {
      console.error('Socket Error:', e.message);
    }
  });

  ws.on('close', () => {
    console.log('Client disconnected');
    clients = clients.filter(c => c !== ws);
  });
});

scanner.on('data', d => {
  const data = d.toString().trim();
  console.log('Scanner Data:', data);
  broadcast(data);
});

arduino.on('data', d => {
  const data = d.toString().trim();
  console.log('Arduino Data:', data);
  broadcast(data);
});

function broadcast(msg) {
  console.log('Broadcasting to', clients.length, 'clients:', msg);
  clients.forEach(ws => {
    if (ws.readyState === WebSocket.OPEN) ws.send(msg);
  });
}

scanner.on('open', () => console.log('Scanner connection established'));
arduino.on('open', () => console.log('Arduino connection established'));

console.log('--- Bridge Server Started ---');
console.log(`Scanner: ${SCANNER_PORT}`);
console.log(`Arduino: ${ARDUINO_PORT}`);
console.log(`Printer Shared Name: ${PRINTER_NAME}`);
console.log(`WebSocket: ws://localhost:8081`);