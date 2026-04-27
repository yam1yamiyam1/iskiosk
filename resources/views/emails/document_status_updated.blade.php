<h3>Document Status Update</h3>

<p>Dear {{ $document->surname }},</p>

@php
    $statusMessage = match($document->status) {
        'Submitted' => 'We have received your document and it is now submitted for processing.',
        'Collected and Processing' => 'Your document is currently being processed.',
        'Ready for claiming' => 'Your document is now ready for claiming. Please visit the office to collect it at your earliest convenience.',
        'Claimed' => 'Your document has been successfully claimed. Thank you for your cooperation.',
        default => 'The status of your document has been updated.',
    };
@endphp

<p>{{ $statusMessage }}</p>

@if($document->remarks)
<p><strong>Remarks:</strong> {{ $document->remarks }}</p>
@endif

<p><strong>Tracking Code:</strong> {{ $document->tracking_code }}</p>

<p>Thank you. Please retain your tracking code for future reference.</p>