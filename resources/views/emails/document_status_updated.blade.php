<p>Dear {{ $document->given_name }},</p>

@if ($document->status === 'Submitted')
<p>We have successfully received your document. It has been queued<br>
for processing and you will be notified once there are updates.</p>

<p>Tracking Code: {{ $document->tracking_code }}</p>

<p>Thank you. Please retain your tracking code for future reference.</p>

<p>IsKiosk Document Management System</p>

@elseif ($document->status === 'Collected and Processing')
<p>Your document is currently being reviewed and processed.<br>
We will send you another update once it is ready for pickup.</p>

<p>Tracking Code: {{ $document->tracking_code }}</p>

<p>Thank you. Please retain your tracking code for future reference.</p>

<p>IsKiosk Document Management System</p>

@elseif ($document->status === 'Ready for claiming')
<p>Your document is now ready for claiming. Please visit our office<br>
to collect it at your earliest convenience.</p>

<p>Please bring a valid ID upon claiming.</p>

@if($document->remarks)
<p>Remarks: {{ $document->remarks }}</p>
@endif

<p>Tracking Code: {{ $document->tracking_code }}</p>

<p>Thank you. Please retain your tracking code for future reference.</p>

<p>IsKiosk Document Management System</p>

@elseif ($document->status === 'Claimed')
<p>Your document has been successfully claimed.<br>
We hope we were able to assist you.</p>

<p>Tracking Code: {{ $document->tracking_code }}</p>

<p>Thank you for your cooperation.</p>

<p>IsKiosk Document Management System</p>

@else
<p>The status of your document has been updated.</p>

@if($document->remarks)
<p>Remarks: {{ $document->remarks }}</p>
@endif

<p>Tracking Code: {{ $document->tracking_code }}</p>

<p>Thank you. Please retain your tracking code for future reference.</p>

<p>IsKiosk Document Management System</p>
@endif