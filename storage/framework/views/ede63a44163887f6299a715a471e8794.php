<div class="modal fade" id="editStudentModal<?php echo e($student->id); ?>" tabindex="-1" aria-labelledby="editStudentModalLabel<?php echo e($student->id); ?>" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="<?php echo e(route('students.update', $student->id)); ?>" class="modal-content">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div class="modal-header">
                <h5 class="modal-title" id="editStudentModalLabel<?php echo e($student->id); ?>">Update Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">ID Number</label>
                    <input id="id_number_modal_<?php echo e($student->id); ?>" name="id_number" type="text" class="form-control" required
                        placeholder="XXXX - XXXXX - AA - X"
                        pattern="^[0-9]{4}\s?-\s?[0-9]{5}\s?-\s?[A-Za-z]{2}\s?-\s?[0-9]$"
                        value="<?php echo e($student->id_number); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Surname</label>
                    <input name="surname" type="text" class="form-control" required
                        pattern="[A-Za-z\s.-]{2,50}"
                        value="<?php echo e($student->surname); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Given Name</label>
                    <input name="given_name" type="text" class="form-control" required
                        pattern="[A-Za-z\s.-]{2,50}"
                        value="<?php echo e($student->given_name); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Middle Name</label>
                    <input name="middle_name" type="text" class="form-control" maxlength="50"
                        pattern="[A-Za-z\s.-]{0,50}"
                        value="<?php echo e($student->middle_name); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Year Level</label>
                    <input id="year_level_modal_<?php echo e($student->id); ?>" name="year_level" type="text" class="form-control" required
                        placeholder="X - X"
                        pattern="^[0-9]\s?-\s?[0-9]$"
                        value="<?php echo e($student->year_level); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" class="form-control" value="<?php echo e($student->email); ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Contact Number</label>
                    <input name="contact_number" type="text" class="form-control"
                        pattern="^09\d{9}$"
                        maxlength="11"
                        value="<?php echo e($student->contact_number); ?>"
                        placeholder="09XXXXXXXXX">
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update Student</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('#editStudentModal<?php echo e($student->id); ?> form');

    const idInput = document.getElementById('id_number_modal_<?php echo e($student->id); ?>');
    const contactInput = form.querySelector('input[name="contact_number"]');
    const yearInput = document.getElementById('year_level_modal_<?php echo e($student->id); ?>');

    idInput.addEventListener('input', (e) => {
        let v = e.target.value.toUpperCase().replace(/[^0-9A-Z]/g, '');
        if (v.length > 4 && v.length <= 9) v = v.slice(0,4) + ' - ' + v.slice(4);
        else if (v.length > 9 && v.length <= 11) v = v.slice(0,4) + ' - ' + v.slice(4,9) + ' - ' + v.slice(9);
        else if (v.length > 11) v = v.slice(0,4) + ' - ' + v.slice(4,9) + ' - ' + v.slice(9,11) + ' - ' + v.slice(11,12);
        e.target.value = v;
    });

    yearInput.addEventListener('input', (e) => {
        let v = e.target.value.replace(/[^0-9]/g, '');
        if (v.length === 0) e.target.value = '';
        else if (v.length === 1) e.target.value = v;
        else e.target.value = v[0] + ' - ' + v[1];
    });

    contactInput?.addEventListener('input', (e) => {
        let v = e.target.value.replace(/[^0-9]/g, '');
        if (v.length === 1 && v !== '0') v = '0';
        if (v.length >= 2 && v.slice(0,2) !== '09') v = '09';
        e.target.value = v.slice(0, 11);
    });

    function isValidID(id) { return /^[0-9]{4}\s?-\s?[0-9]{5}\s?-\s?[A-Z]{2}\s?-\s?[0-9]$/.test(id.trim()); }
    function isValidPhone(num) { return /^09\d{9}$/.test(num.trim()); }

    function showPopup(msg) {
        const overlay = document.createElement('div');
        overlay.className = 'popup-overlay';
        overlay.innerHTML = `<div class="popup-box">
            <h3 style="color:#d00000;">Invalid Input</h3>
            <p>${msg}</p>
            <button id="popupClose">OK</button>
        </div>`;
        document.body.appendChild(overlay);
        document.getElementById('popupClose').onclick = () => overlay.remove();
    }

    form.addEventListener('submit', (e) => {
        const idVal = idInput.value.trim();
        const phoneVal = contactInput.value.trim();
        const yearVal = yearInput.value.trim();

        if (!isValidID(idVal)) { e.preventDefault(); showPopup('Invalid ID format.<br><b>XXXX - XXXXX - AA - X</b>'); return; }
        if (phoneVal && !isValidPhone(phoneVal)) { e.preventDefault(); showPopup('Invalid contact number.<br><b>09XXXXXXXXX</b>'); return; }
        if (!/^[0-9]\s?-\s?[0-9]$/.test(yearVal)) { e.preventDefault(); showPopup('Invalid Year Level format.<br><b>Format: X - X</b>'); return; }
        if (!form.checkValidity()) { e.preventDefault(); form.reportValidity(); return; }
    });
});
</script><?php /**PATH C:\laragon\www\kiosk\resources\views\includes\student-edit.blade.php ENDPATH**/ ?>