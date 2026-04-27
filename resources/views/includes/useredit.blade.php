<div class="modal fade" id="editUserModal{{ $user['id'] }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $user['id'] }}" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('users.update', $user['id']) }}" enctype="multipart/form-data" class="modal-content">
            @csrf
            @method('PUT')

            @php
                use \Milon\Barcode\DNS1D;
            @endphp
            
            <div class="modal-header">
                <h5 class="modal-title" id="editUserModalLabel{{ $user['id'] }}">Update User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">First Name</label>
                    <input name="fname" type="text" class="form-control" value="{{ $user['fname'] }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Middle Initial</label>
                    <input name="mi" type="text" class="form-control" value="{{ $user['mi'] }}" maxlength="2">
                </div>
                <div class="mb-3">
                    <label class="form-label">Last Name</label>
                    <input name="lname" type="text" class="form-control" value="{{ $user['lname'] }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-control" required onchange="toggleShopDropdown({{ $user['id'] }}); toggleBarcode({{ $user['id'] }});">
                        <option value="1" {{ $user->hasRole(1) ? 'selected' : '' }}>Admin</option>
                        <option value="0" {{ $user->hasRole(0) ? 'selected' : '' }}>Staff</option>
                    </select>
                </div>

                <div id="barcodeField{{ $user['id'] }}" class="mb-3 text-center">
                    <label class="form-label d-block">Barcode</label>

                    <div id="barcodeImage{{ $user['id'] }}">
                        @if($user->barcode)
                            @php
                                $barcodeGenerator = new Milon\Barcode\DNS1D();
                            @endphp
                            <img id="barcodeImg{{ $user['id'] }}" src="data:image/png;base64,{{ $barcodeGenerator->getBarcodePNG($user->barcode, 'C128') }}" alt="barcode">
                        @else
                            <div class="text-muted">No Barcode</div>
                        @endif
                    </div>

                    <button type="button" class="btn btn-sm mt-2" onclick="regenerateBarcode({{ $user['id'] }})">
                        Regenerate Barcode
                    </button>

                    <button type="button" class="btn btn-sm btn-success mt-2"
                            onclick="downloadBarcode({{ $user->id }}, '{{ $user->fname }}', '{{ $user->lname }}')">
                        Download Barcode
                    </button>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" class="form-control" value="{{ $user['email'] }}" required>
                </div>

                <div id="passwordFields{{ $user['id'] }}">
                    <div class="mb-3 position-relative">
                        <label class="form-label">Password</label>
                        <div class="input-group">
                            <input id="password{{ $user['id'] }}" name="password" type="password" class="form-control" placeholder="Leave blank to keep current password">
                            <button class="btn btn-outline-secondary" type="button"
                                onclick="generatePassword('password{{ $user['id'] }}', 'confirm{{ $user['id'] }}')">Generate</button>
                            <button class="btn btn-outline-secondary" type="button"
                                onclick="togglePassword('password{{ $user['id'] }}')">
                                <i class="bi bi-eye" id="eyePassword{{ $user['id'] }}"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mb-3 position-relative">
                        <label class="form-label">Confirm Password</label>
                        <div class="input-group">
                            <input id="confirm{{ $user['id'] }}" name="password_confirmation"
                                type="password" class="form-control">
                            <button class="btn btn-outline-secondary" type="button"
                                onclick="togglePassword('confirm{{ $user['id'] }}')">
                                <i class="bi bi-eye" id="eyeConfirm{{ $user['id'] }}"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control" required>
                        <option value="1" {{ $user['status'] == 1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ $user['status'] == 0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Profile Image</label>
                    <input name="image" type="file" class="form-control" accept="image/*">
                    @if ($user['image'])
                        <div class="mt-2">
                            <img src="{{ asset('storage/users/' . $user['image']) }}" width="60" height="60" style="object-fit: cover; border-radius: 4px;">
                        </div>
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update User</button>
            </div>
        </form>
    </div>

    <div class="modal fade" id="messageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="messageModalBody"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleShopDropdown(userId) {
    const roleSelect = document.querySelector(`#editUserModal${userId} select[name="role"]`);
    const shopDropdown = document.querySelector(`#shopDropdown${userId}`);

    if (roleSelect.value == 0) {
        shopDropdown.style.display = 'block';
    } else {
        shopDropdown.style.display = 'none';
    }
}

function toggleBarcode(userId) {
    const roleSelect = document.querySelector(`#editUserModal${userId} select[name="role"]`);
    const barcodeField = document.getElementById(`barcodeField${userId}`);

    if (roleSelect.value == "0") {
        barcodeField.style.display = "block";
    } else {
        barcodeField.style.display = "none";
    }
}

document.addEventListener('DOMContentLoaded', function() {
    @foreach ($users as $user)
        toggleShopDropdown({{ $user['id'] }});
        toggleBarcode({{ $user['id'] }});
    @endforeach
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

document.addEventListener("DOMContentLoaded", function () {

    @foreach ($users as $user)
    (function () {
        const roleSelect = document.querySelector(`#editUserModal{{ $user['id'] }} select[name="role"]`);
        const passwordFields = document.getElementById('passwordFields{{ $user['id'] }}');
        const passwordInput = document.getElementById('password{{ $user['id'] }}');
        const confirmInput = document.getElementById('confirm{{ $user['id'] }}');

        function togglePasswordFields() {
            if (roleSelect.value == "1") {
                passwordFields.style.display = "block";
                passwordInput.disabled = false;
                confirmInput.disabled = false;
            } else {
                passwordFields.style.display = "none";
                passwordInput.disabled = true;
                confirmInput.disabled = true;
                passwordInput.value = "";
                confirmInput.value = "";
            }
        }

        function handlePasswordInput() {
            if (passwordInput.value.length > 0) {
                confirmInput.required = true;
                passwordInput.required = true;
            } else {
                confirmInput.required = false;
                passwordInput.required = false;
            }
        }

        roleSelect.addEventListener("change", function() {
            togglePasswordFields();
            toggleBarcode({{ $user['id'] }});
        });

        passwordInput.addEventListener("input", handlePasswordInput);

        togglePasswordFields();
    })();
    @endforeach

});

function regenerateBarcode(userId) {
    const confirmModalHtml = `
        <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Action</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to regenerate the barcode? <br>
                        <strong>Note:</strong> The current barcode will no longer be usable after regeneration.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="confirmAction">Yes, Regenerate</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', confirmModalHtml);
    const confirmModalEl = document.getElementById('confirmModal');
    const confirmModal = new bootstrap.Modal(confirmModalEl);
    confirmModal.show();

    document.getElementById('confirmAction').addEventListener('click', function () {
        confirmModal.hide();
        confirmModalEl.remove();

        fetch(`/users/${userId}/regenerate-barcode`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {

            if (data.success) {
                document.getElementById(`barcodeImage${userId}`).innerHTML =
                    `<img id="barcodeImg${userId}" src="data:image/png;base64,${data.barcode}" alt="barcode">`;

                showMessageModal('Success', 'Barcode regenerated successfully!');
            } else {
                showMessageModal('Error', 'Failed to regenerate barcode.');
            }
        })
        .catch(error => {
            console.error(error);
            showMessageModal('Error', 'An error occurred while regenerating the barcode.');
        });
    });
}

function downloadBarcode(userId, firstName, lastName) {
    const img = document.getElementById(`barcodeImg${userId}`);
    if (!img || !img.src) {
        showMessageModal('Error', 'No barcode available to download.');
        return;
    }

    const safeFirstName = firstName.replace(/[^a-z0-9]/gi, '_');
    const safeLastName = lastName.replace(/[^a-z0-9]/gi, '_');

    const link = document.createElement('a');
    link.href = img.src;
    link.download = `barcode_${safeLastName}_${safeFirstName}.png`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    showMessageModal('Success', 'Barcode download started!');
}
</script>