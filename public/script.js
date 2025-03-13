
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role');
    const patientFields = document.getElementById('patientFields');
    const doctorFields = document.getElementById('doctorFields');
    const staffFields = document.getElementById('staffFields');

    function hideAllFields() {
        patientFields.style.display = 'none';
        doctorFields.style.display = 'none';
        staffFields.style.display = 'none';
    }
    function showFieldsForRole(role) {
        hideAllFields();
        if (role === 'patient') {
            patientFields.style.display = 'block';
        } else if (role === 'doctor') {
            doctorFields.style.display = 'block';
        } else if (role === 'staff') {
            staffFields.style.display = 'block';
        }
    }

    if (roleSelect) {
        showFieldsForRole(roleSelect.value);
        roleSelect.addEventListener('change', function () {
            showFieldsForRole(this.value);
        });
    } else {
        showFieldsForRole('<?= $defaultRole ?>');
    }
});
