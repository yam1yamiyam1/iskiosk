<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>User Barcodes Directory</title>

    <style>
        body {
            font-family: sans-serif;
            margin: 20px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        
        td {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            padding: 10px;
        }

        .item {
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 15px;
        }

        .barcode {
            width: 180px;
            height: auto;
        }

        .name {
            margin-top: 8px;
            font-size: 13px;
            font-weight: bold;
        }

        .id-label {
            font-size: 11px;
        }
    </style>
</head>
<body>

    <h2>Identification Barcodes</h2>

    <?php
        $barcodeGenerator = new Milon\Barcode\DNS1D();
        $count = 0;
    ?>

    <table>
        <tr>
            <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($user->barcode): ?>
                    <td>
                        <div class="item">
                            <img class="barcode"
                                src="data:image/png;base64,<?php echo e($barcodeGenerator->getBarcodePNG($user->barcode, 'C128', 2, 50)); ?>">
                            <div class="name">
                                <?php echo e(strtoupper($user->fname)); ?> <?php echo e(strtoupper($user->lname)); ?>

                            </div>
                        </div>
                    </td>

                    <?php $count++; ?>

                    <?php if($count % 3 == 0): ?>
                        </tr><tr>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tr>
    </table>

</body>
</html><?php /**PATH C:\laragon\www\iskiosk\resources\views/pdf/user-barcodes.blade.php ENDPATH**/ ?>