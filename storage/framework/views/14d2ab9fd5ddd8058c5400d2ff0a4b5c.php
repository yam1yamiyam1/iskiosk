<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($subject); ?></title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f7f7f7; padding: 20px; }
        .container { background-color: #fff; padding: 30px; border-radius: 8px; max-width: 600px; margin: auto; }
        h1 { color: #333; }
        p { color: #555; }
        .button { background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; margin-top: 20px; display: inline-block; }
        .footer { margin-top: 30px; font-size: 12px; color: #888; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Password Reset Request</h1>
        <p><?php echo e($greeting); ?></p>
        <p><?php echo e($line1); ?></p>
        <a href="<?php echo e($actionUrl); ?>" class="button"><?php echo e($actionText); ?></a>
        <p><?php echo e($line2); ?></p>
        <p><?php echo e($salutation); ?></p>
    </div>
    <div class="footer">
        <p>&copy; <?php echo e(date('Y')); ?> IsKiosk: Document Management System. All rights reserved.</p>
    </div>
</body>
</html>
<?php /**PATH C:\laragon\www\kiosk\resources\views\emails\reset-password.blade.php ENDPATH**/ ?>