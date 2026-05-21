document.addEventListener('DOMContentLoaded', function() {
    
    const clockCanvas = document.getElementById('clockCanvas');
    if (clockCanvas) {
        const ctx = clockCanvas.getContext('2d');
        clockCanvas.width = 200;
        clockCanvas.height = 200;
        
        function drawClock() {
            const now = new Date();
            const h = now.getHours();
            const m = now.getMinutes();
            const s = now.getSeconds();
            const cx = clockCanvas.width / 2;
            const cy = clockCanvas.height / 2;
            
            const timeString = `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
            
            ctx.clearRect(0, 0, clockCanvas.width, clockCanvas.height);
            
            const gradient = ctx.createLinearGradient(0, 0, clockCanvas.width, clockCanvas.height);
            gradient.addColorStop(0, '#78A2D2');
            gradient.addColorStop(1, '#5a8bc2');
            ctx.fillStyle = gradient;
            ctx.fillRect(0, 0, clockCanvas.width, clockCanvas.height);
            
            ctx.strokeStyle = '#FEFFAF';
            ctx.lineWidth = 3;
            ctx.strokeRect(8, 8, clockCanvas.width - 16, clockCanvas.height - 16);
            
            ctx.font = 'bold 14px "Segoe UI", Arial';
            ctx.fillStyle = '#FEFFAF';
            ctx.textAlign = 'center';
            ctx.fillText('KVITTER', cx, 45);
            
           
            ctx.font = 'bold 32px "Courier New", monospace';
            ctx.fillStyle = '#FEFFAF';
            ctx.fillText(timeString, cx, cy + 15);
            
            
            ctx.beginPath();
            ctx.moveTo(40, cy + 55);
            ctx.lineTo(clockCanvas.width - 40, cy + 55);
            ctx.strokeStyle = '#FEFFAF';
            ctx.lineWidth = 1;
            ctx.stroke();
            
           
            ctx.font = '10px "Segoe UI", Arial';
            ctx.fillStyle = '#FEFFAF';
            ctx.fillText('digital klocka', cx, cy + 80);
        }
        
        drawClock();
        setInterval(drawClock, 1000);
    }
    
    
    const kvitterTextarea = document.querySelector('textarea[name="content"]');
    const charCount = document.getElementById('charCount');
    
    if (kvitterTextarea && charCount) {
        function updateCharCount() {
            const count = kvitterTextarea.value.length;
            charCount.textContent = count + '/280';
            
            if (count > 250) {
                charCount.style.color = '#dc3545';
                charCount.style.fontWeight = 'bold';
            } else if (count > 200) {
                charCount.style.color = '#ffc107';
                charCount.style.fontWeight = 'normal';
            } else {
                charCount.style.color = '#6c757d';
                charCount.style.fontWeight = 'normal';
            }
        }
        
        kvitterTextarea.addEventListener('input', updateCharCount);
        updateCharCount();
    }
    
    const passwordField = document.getElementById('password');
    const strengthBar = document.getElementById('strengthBar');
    const strengthText = document.getElementById('strengthText');
    
    if (passwordField && strengthBar) {
        function updatePasswordStrength() {
            const password = passwordField.value;
            let strength = 0;
            let message = '';
            let color = '';
            
            if (password.length >= 8) strength += 33;
            if (/[A-Z]/.test(password)) strength += 33;
            if (/[0-9]/.test(password)) strength += 34;
            
            if (strength < 33) {
                message = 'För svagt - minst 8 tecken, stor bokstav och siffra';
                color = '#dc3545';
            } else if (strength < 66) {
                message = 'Okej - kan bli starkare';
                color = '#ffc107';
            } else {
                message = 'Starkt! Bra lösenord';
                color = '#28a745';
            }
            
            strengthBar.style.width = strength + '%';
            strengthBar.style.backgroundColor = color;
            
            if (strengthText) {
                strengthText.textContent = message;
                strengthText.style.color = color;
            }
        }
        
        passwordField.addEventListener('input', updatePasswordStrength);
        updatePasswordStrength();
    }
    
    
    const confirmField = document.getElementById('confirm');
    const matchMsg = document.getElementById('matchMsg');
    
    if (passwordField && confirmField && matchMsg) {
        function checkPasswordMatch() {
            if (confirmField.value === '') {
                matchMsg.innerHTML = '';
            } else if (passwordField.value === confirmField.value) {
                matchMsg.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> Lösenorden matchar</span>';
            } else {
                matchMsg.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle"></i> Lösenorden matchar inte</span>';
            }
        }
        
        passwordField.addEventListener('input', checkPasswordMatch);
        confirmField.addEventListener('input', checkPasswordMatch);
    }
    
    
    const gdprCheckbox = document.getElementById('gdpr');
    const submitBtn = document.getElementById('submitBtn');
    
    if (gdprCheckbox && submitBtn) {
        function toggleSubmitButton() {
            submitBtn.disabled = !gdprCheckbox.checked;
        }
        
        gdprCheckbox.addEventListener('change', toggleSubmitButton);
        toggleSubmitButton();
    }
    
    
    const deleteLinks = document.querySelectorAll('a[onclick*="confirm"], a[href*="delete"]');
    deleteLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const message = this.getAttribute('data-confirm') || 'Är du säker på att du vill ta bort detta?';
            if (!confirm(message)) {
                e.preventDefault();
            }
        });
    });
    
   
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => {
                if (alert.parentNode) alert.remove();
            }, 500);
        }, 5000);
    });
    
    
    
    const firstInput = document.querySelector('form input:not([type="hidden"]), form textarea, form select');
    if (firstInput && !firstInput.value) {
        firstInput.focus();
    }
    
    console.log('Kvitter JS laddad! ');
});

const likeButtons = document.querySelectorAll('a[href*="like="], a[href*="unlike="]');
likeButtons.forEach(btn => {
    btn.addEventListener('click', function(e) {
        const icon = this.querySelector('i');
        if (icon) {
            icon.classList.add('heart-animation');
            setTimeout(() => {
                icon.classList.remove('heart-animation');
            }, 300);
        }
    });
});


