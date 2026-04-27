<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="<?php echo e(route('users.store')); ?>" enctype="multipart/form-data" class="modal-content">
            <?php echo csrf_field(); ?>
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">First Name</label>
                    <input name="fname" type="text" class="form-control" required
                        pattern="[A-Za-z\s.-]{3,}"
                        title="At least 3 characters. Only letters, spaces, hyphens, and dots."
                        oninput="this.value = this.value.replace(/[^A-Za-z\s.-]/g, '')">
                </div>

                <div class="mb-3">
                    <label class="form-label">Middle Initial</label>
                    <input name="mi" type="text" class="form-control" required maxlength="2"
                        pattern="[A-Za-z\s.-]{2,}"
                        title="At least 2 characters. Only letters, spaces, hyphens, and dots."
                        oninput="this.value = this.value.replace(/[^A-Za-z\s.-]/g, '')">
                </div>

                <div class="mb-3">
                    <label class="form-label">Last Name</label>
                    <input name="lname" type="text" class="form-control" required
                        pattern="[A-Za-z\s.-]{3,}"
                        title="At least 3 characters. Only letters, spaces, hyphens, and dots."
                        oninput="this.value = this.value.replace(/[^A-Za-z\s.-]/g, '')">
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="1" selected>Active</option>
                        <option value="0">Disabled</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-control" required>
                        <option value="0" selected>Staff</option>
                        <option value="1">Admin</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" class="form-control" required>
                </div>
                <div>
                    <div class="mb-3 position-relative">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input id="password" name="password" type="password" class="form-control">
                            <button class="btn btn-outline-secondary" type="button"
                                onclick="generatePassword('password', 'password_confirmation')">Generate</button>
                            <button class="btn btn-outline-secondary" type="button"
                                onclick="togglePassword('password')">
                                <i class="bi bi-eye" id="toggleIcon-password"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3 position-relative">
                        <label class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input id="password_confirmation" name="password_confirmation"
                                type="password" class="form-control">
                            <button class="btn btn-outline-secondary" type="button"
                                onclick="togglePassword('password_confirmation')">
                                <i class="bi bi-eye" id="toggleIcon-password_confirmation"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">User Image</label>
                    <input name="image" type="file" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save User</button>
            </div>
        </form>
    </div>
</div>
<script>

document.addEventListener("DOMContentLoaded", function () {
        const roleSelect = document.querySelector('select[name="role"]');
        const shopDiv = document.getElementById('shopSelectDiv');

        function toggleShopDropdown() {
            if (roleSelect.value == "0") {
                shopDiv.style.display = "block";
            } else {
                shopDiv.style.display = "none";
            }
        }

        roleSelect.addEventListener('change', toggleShopDropdown);
        toggleShopDropdown();
    });
    
function togglePassword(id) {
    const input = document.getElementById(id);
    const icon = document.getElementById('eye' + id.charAt(0).toUpperCase() + id.slice(1));
    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
    } else {
        input.type = "password";
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
    }
}

function generatePassword(passwordId, confirmPasswordId = null) {
    const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()";
    let password = "";
    for (let i = 0; i < 12; i++) {
        password += chars.charAt(Math.floor(Math.random() * chars.length));
    }

    const passInput = document.getElementById(passwordId);
    const confirmInput = document.getElementById(confirmPasswordId);
    const passIcon = document.getElementById('eye' + passwordId.charAt(0).toUpperCase() + passwordId.slice(1));
    const confirmIcon = document.getElementById('eye' + confirmPasswordId.charAt(0).toUpperCase() + confirmPasswordId.slice(1));

    passInput.value = password;
    passInput.type = 'text';
    if (passIcon) {
        passIcon.classList.remove("bi-eye-slash");
        passIcon.classList.add("bi-eye");
    }

    if (confirmInput) {
        confirmInput.value = password;
        confirmInput.type = 'text';
        if (confirmIcon) {
            confirmIcon.classList.remove("bi-eye-slash");
            confirmIcon.classList.add("bi-eye");
        }
    }
}
</script>
<?php /**PATH C:\laragon\www\kiosk\resources\views\includes\useradd.blade.php ENDPATH**/ ?>