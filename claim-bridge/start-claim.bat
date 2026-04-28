@echo off
echo ====================================================
echo   IsKiosk Claiming PC - Hardware Bridge Start
echo ====================================================
echo.

:: Check if node_modules exists, if not, run npm install
IF NOT EXIST node_modules (
    echo [Setup] Installing required dependencies...
    npm install
)

echo [Start] Launching Bridge...
node claim-bridge.js

pause
