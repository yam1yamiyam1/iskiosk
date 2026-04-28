const WebSocket = require('ws');

const wss = new WebSocket.Server({ port: 8081 });

console.log('✅ Dummy WebSocket server running at ws://localhost:8081');

wss.on('connection', (ws) => {
  console.log('Client connected');

  
  setTimeout(() => {
    const dummyIDs = "1231 - 23123 - AA - 1";

    console.log("📤 Auto-scan ID:", dummyIDs);
    ws.send(dummyIDs);

}, 2000);

  ws.on('message', (message) => {
    console.log('Received from client:', message.toString());

    try {
      const data = JSON.parse(message);

      if (data.command === "CHECK_DRAWER") {
        console.log('Simulating drawer check...');

        setTimeout(() => {
          const responses = ["OPEN_DRAWER1", "OPEN_DRAWER2", "ALL_FULL"];
          const random = responses[Math.floor(Math.random() * responses.length)];

          console.log('Sending:', random);
          ws.send(random);
        }, 2000);
      }

    } catch (err) {
      console.log('Invalid JSON message');
    }
  });

  ws.on('close', () => {
    console.log('Client disconnected');
  });
});