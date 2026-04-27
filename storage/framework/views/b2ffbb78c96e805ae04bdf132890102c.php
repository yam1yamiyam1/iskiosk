<?php $__env->startSection('content'); ?>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        Overview
                    </div>
                    <h2 class="page-title">
                        Profile
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <div class="container-xl">
                <div class="row row-deck row-cards">
                    <div class="col-12">
                        <div class="row row-cards">
                            <div class="card">
                                <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                                    <div class="profile-pic-wrapper">
                                        <img class="rounded-circle mt-5 mb-2 profilepic" width="150px" height="150px"
                                            src="<?php echo e(Auth::user()->image ? asset('storage/users/' . Auth::user()->image) : asset('storage/users/p1.jpg')); ?>">
                                    </div>
                                    <?php use Illuminate\Support\Str; ?>

                                    <h2><?php echo e(Auth()->user()->fname); ?> <?php echo e(Str::upper(Auth()->user()->mi)); ?>. <?php echo e(Auth()->user()->lname); ?></h2>
                                    <h3>Account</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-xl">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="row row-cards">
                        <form method="POST" action="<?php echo e(route('profile.update')); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                            <div class="form-group row">
                                <div class="col-5">
                                    <label for="fname">First Name</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['fname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Enter a First Name" value="<?php echo e($user->fname); ?>" id="fname<?php echo e($user->id); ?>" autofocus name="fname" required>
                                </div>
                                <div class="col-2">
                                    <label for="mi">MI</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['mi'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="M.I." value="<?php echo e($user->mi); ?>" id="mi<?php echo e($user->id); ?>" name="mi" required>
                                </div>
                                <div class="col-5">
                                    <label for="lname">Last Name</label>
                                    <input type="text" class="form-control <?php $__errorArgs = ['lname'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Enter a Last Name" value="<?php echo e($user->lname); ?>" id="lname<?php echo e($user->id); ?>" name="lname" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input id="email<?php echo e($user->id); ?>" type="text" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" value="<?php echo e($user->email); ?>" placeholder="Enter an email" required autocomplete="email" >
                            </div>
                            <div class="form-group mt-5">
                                <label for="image">Profile Image</label>
                                <input id="image<?php echo e($user->id); ?>" type="file" accept="image/*" class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="image">
                            </div>
                            <div class="row mt-5">
                                <div class="col-md-6">
                                    <label for="password" class="control-label">Change Password (Optional)</label>
                                    <input type="password" class="form-control" id="password<?php echo e($user->id); ?>" name="password" placeholder="Enter a Password" autocomplete="new-password" minlength="8">
                                </div>
                                <div class="col-md-6">
                                    <label for="confirmpassword" class="control-label">Confirm Password</label>
                                    <input type="password" class="form-control" id="confirmpassword<?php echo e($user->id); ?>" name="password_confirmation" placeholder="Re-enter Password" minlength="8">
                                </div>
                            </div>
                            <div class="mt-5 text-center">
                                <button class="btn btn-primary profile-button" type="submit">Save Profile</button>
                            </div>
                        </Form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.tabler', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\kiosk\resources\views/profile/edit.blade.php ENDPATH**/ ?>