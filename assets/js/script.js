// Konfirmasi hapus
function confirmDelete(message = 'Apakah Anda yakin ingin menghapus item ini?') {
    return confirm(message);
}

// Preview gambar
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

// Auto-hide alerts
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});

// Active nav link
document.addEventListener('DOMContentLoaded', function() {
    var currentPage = window.location.href;
    var navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(function(link) {
        if (link.href === currentPage) {
            link.classList.add('active');
        }
    });
}); 