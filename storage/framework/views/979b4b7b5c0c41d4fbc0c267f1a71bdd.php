<h3>Document Status Update</h3>

<p>Dear <?php echo e($document->surname); ?>,</p>

<?php
    $statusMessage = match($document->status) {
        'Submitted' => 'We have received your document and it is now submitted for processing.',
        'Collected and Processing' => 'Your document is currently being processed.',
        'Ready for claiming' => 'Your document is now ready for claiming. Please visit the office to collect it at your earliest convenience.',
        'Claimed' => 'Your document has been successfully claimed. Thank you for your cooperation.',
        default => 'The status of your document has been updated.',
    };
?>

<p><?php echo e($statusMessage); ?></p>

<?php if($document->remarks): ?>
<p><strong>Remarks:</strong> <?php echo e($document->remarks); ?></p>
<?php endif; ?>

<p><strong>Tracking Code:</strong> <?php echo e($document->tracking_code); ?></p>

<p>Thank you. Please retain your tracking code for future reference.</p><?php /**PATH C:\laragon\www\iskiosk\resources\views/emails/document_status_updated.blade.php ENDPATH**/ ?>