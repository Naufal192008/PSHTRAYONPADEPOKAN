document.addEventListener('DOMContentLoaded', function() {
    const registrationForm = document.getElementById('registrationForm');
    
    registrationForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        clearErrors();
        
        if (validateForm()) {
            const submitBtn = registrationForm.querySelector('button[type="submit"]');
            const originalBtnText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
            
            try {
                const formData = new FormData(registrationForm);
                const data = Object.fromEntries(formData.entries());
                
                data.persetujuan = registrationForm.querySelector('#persetujuan').checked;
                
                if (data.tanggal_lahir) {
                    const dateObj = new Date(data.tanggal_lahir);
                    data.tanggal_lahir = dateObj.toISOString().split('T')[0];
                }
                
                const response = await fetch('pendaftaran.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (!response.ok || !result.success) {
                    throw new Error(result.message || 'Terjadi kesalahan server');
                }
                
                window.location.href = `pendaftaran-sukses.html?id=PSHT-${result.registration_id}`;
                
            } catch (error) {
                showFormError(error.message);
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalBtnText;
            }
        }
    });
    
    function validateForm() {
        let isValid = true;
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        const nama = document.getElementById('nama');
        if (!nama.value.trim()) {
            showError(nama, 'Nama lengkap harus diisi');
            isValid = false;
        } else if (nama.value.length > 100) {
            showError(nama, 'Nama terlalu panjang (maks 100 karakter)');
            isValid = false;
        } else {
            showSuccess(nama);
        }
        
        const email = document.getElementById('email');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email.value.trim()) {
            showError(email, 'Email harus diisi');
            isValid = false;
        } else if (!emailRegex.test(email.value)) {
            showError(email, 'Email tidak valid');
            isValid = false;
        } else if (email.value.length > 100) {
            showError(email, 'Email terlalu panjang (maks 100 karakter)');
            isValid = false;
        } else {
            showSuccess(email);
        }
        
        const telepon = document.getElementById('telepon');
        const phoneRegex = /^[0-9]{10,13}$/;
        if (!telepon.value.trim()) {
            showError(telepon, 'Nomor telepon harus diisi');
            isValid = false;
        } else if (!phoneRegex.test(telepon.value)) {
            showError(telepon, 'Nomor telepon tidak valid (10-13 digit)');
            isValid = false;
        } else {
            showSuccess(telepon);
        }
        
        const tanggal_lahir = document.getElementById('tanggal_lahir');
        if (!tanggal_lahir.value) {
            showError(tanggal_lahir, 'Tanggal lahir harus diisi');
            isValid = false;
        } else {
            const birthDate = new Date(tanggal_lahir.value);
            const age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
            if (age < 12) {
                showError(tanggal_lahir, 'Usia minimal 12 tahun');
                isValid = false;
            } else {
                showSuccess(tanggal_lahir);
            }
        }
        
        const persetujuan = document.getElementById('persetujuan');
        if (!persetujuan.checked) {
            showError(persetujuan, 'Anda harus menyetujui syarat dan ketentuan');
            isValid = false;
        } else {
            showSuccess(persetujuan);
        }
        
        return isValid;
    }
    
    function showError(input, message) {
        const formGroup = input.closest('.form-group') || input.parentElement;
        formGroup.classList.add('error');
        
        let errorMessage = formGroup.querySelector('.error-message');
        if (!errorMessage) {
            errorMessage = document.createElement('small');
            errorMessage.className = 'error-message text-danger';
            formGroup.appendChild(errorMessage);
        }
        
        errorMessage.textContent = message;
    }
    
    function showSuccess(input) {
        const formGroup = input.closest('.form-group') || input.parentElement;
        formGroup.classList.remove('error');
        
        const errorMessage = formGroup.querySelector('.error-message');
        if (errorMessage) {
            errorMessage.remove();
        }
    }
    
    function clearErrors() {
        document.querySelectorAll('.error-message').forEach(el => el.remove());
        document.querySelectorAll('.form-group').forEach(el => el.classList.remove('error'));
    }
    
    function showFormError(message) {
        let errorContainer = document.getElementById('form-error');
        if (!errorContainer) {
            errorContainer = document.createElement('div');
            errorContainer.id = 'form-error';
            errorContainer.className = 'alert alert-danger mb-3';
            registrationForm.prepend(errorContainer);
        }
        
        errorContainer.textContent = message;
        errorContainer.style.display = 'block';
    }
});