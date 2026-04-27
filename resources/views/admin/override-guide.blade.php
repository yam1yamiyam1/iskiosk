<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Override Guide - {{ $document->tracking_code }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 24px; color: #222; }
        .header { margin-bottom: 16px; }
        .box { border: 1px solid #bbb; padding: 16px; margin-bottom: 16px; }
        .label { font-weight: bold; }
        .muted { color: #666; font-size: 13px; }
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Manual Override Guide</h2>
        <p class="muted">For document types that do not require kiosk waiting/drop-off.</p>
    </div>

    <div class="box">
        <p><span class="label">Tracking Code:</span> {{ $document->tracking_code }}</p>
        <p><span class="label">Student ID:</span> {{ $document->id_number }}</p>
        <p><span class="label">Student Name:</span> {{ $document->surname }}, {{ $document->given_name }} {{ $document->middle_name }}</p>
        <p><span class="label">Document Type:</span> {{ optional($document->document_typeb)->name }}</p>
        <p><span class="label">Program:</span> {{ optional($document->programb)->name }}</p>
        <p><span class="label">Status:</span> {{ $document->status }}</p>
        <p><span class="label">Remarks:</span> {{ $document->remarks ?: '-' }}</p>
    </div>

    <div class="box">
        <p class="label">Staff Handling Notes</p>
        <ol>
            <li>Document was manually overridden from Submitted.</li>
            <li>No kiosk waiting/drop-off is required for this request.</li>
            <li>This request is treated as completed and marked Claimed immediately.</li>
            <li>Keep this printout attached to the physical record for audit trail.</li>
        </ol>
    </div>

    <button class="no-print" onclick="window.print()">Print Guide</button>
</body>
</html>
