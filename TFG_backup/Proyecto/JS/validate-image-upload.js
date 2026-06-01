(function () {
    var ALLOWED = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    var MSG = 'Tipo de archivo incorrecto. Solo se permiten imágenes (JPG, PNG, GIF, WEBP).';

    function isValidImageFile(file) {
        if (!file || !file.name) {
            return true;
        }
        if (ALLOWED.indexOf(file.type) !== -1) {
            return true;
        }
        var ext = file.name.split('.').pop().toLowerCase();
        return ['jpg', 'jpeg', 'png', 'gif', 'webp'].indexOf(ext) !== -1;
    }

    function validateInput(input) {
        if (!input.files || !input.files.length) {
            return true;
        }
        for (var i = 0; i < input.files.length; i++) {
            if (!isValidImageFile(input.files[i])) {
                alert(MSG);
                input.value = '';
                return false;
            }
        }
        return true;
    }

    function validateForm(form) {
        var inputs = form.querySelectorAll('input[type="file"][accept*="image"]');
        for (var i = 0; i < inputs.length; i++) {
            if (!validateInput(inputs[i])) {
                return false;
            }
        }
        return true;
    }

    document.addEventListener('change', function (e) {
        if (e.target && e.target.matches('input[type="file"][accept*="image"]')) {
            validateInput(e.target);
        }
    });

    document.addEventListener('submit', function (e) {
        var form = e.target;
        if (!form || form.tagName !== 'FORM') {
            return;
        }
        if (!form.querySelector('input[type="file"][accept*="image"]')) {
            return;
        }
        if (!validateForm(form)) {
            e.preventDefault();
        }
    });
})();
