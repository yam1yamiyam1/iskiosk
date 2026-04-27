<p>Dear <?php echo e($document->given_name); ?>,</p>

<?php if($document->status === 'Submitted'): ?>
<p>We have successfully received your document. It has been queued<br>
for processing and you will be notified once there are updates.</p>

<p>Tracking Code: <?php echo e($document->tracking_code); ?></p>

<p>Thank you. Please retain your tracking code for future reference.</p>

<p>IsKiosk Document Management System</p>

<?php elseif($document->status === 'Collected and Processing'): ?>
<p>Your document is currently being reviewed and processed.<br>
We will send you another update once it is ready for pickup.</p>

<p>Tracking Code: <?php echo e($document->tracking_code); ?></p>

<p>Thank you. Please retain your tracking code for future reference.</p>

<p>IsKiosk Document Management System</p>

<?php elseif($document->status === 'Ready for claiming'): ?>
<p>Your document is now ready for claiming. Please visit our office<br>
to collect it at your earliest convenience.</p>

<p>Please bring a valid ID upon claiming.</p>

<?php if($document->remarks): ?>
<p>Remarks: <?php echo e($document->remarks); ?></p>
<?php endif; ?>

<p>Tracking Code: <?php echo e($document->tracking_code); ?></p>

<p>Thank you. Please retain your tracking code for future reference.</p>

<p>IsKiosk Document Management System</p>

<?php elseif($document->status === 'Claimed'): ?>
<p>Your document has been successfully claimed.<br>
We hope we were able to assist you.</p>

<p>Tracking Code: <?php echo e($document->tracking_code); ?></p>

<p>Thank you for your cooperation.</p>

<p>IsKiosk Document Management System</p>

<?php else: ?>
<p>The status of your document has been updated.</p>

<?php if($document->remarks): ?>
<p>Remarks: <?php echo e($document->remarks); ?></p>
<?php endif; ?>

<p>Tracking Code: <?php echo e($document->tracking_code); ?></p>

<p>Thank you. Please retain your tracking code for future reference.</p>

<p>IsKiosk Document Management System</p>
<?php endif; ?><?php /**PATH C:\laragon\www\iskiosk\resources\views/emails/document_status_updated.blade.php ENDPATH**/ ?>