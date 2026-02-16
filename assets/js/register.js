document.addEventListener('DOMContentLoaded', () => {
    const nextStepBtn = document.getElementById('next-step-btn');
    const termsCheckbox = document.getElementById('terms');

    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const passwordError = document.getElementById('password-error');

    const emailError = document.getElementById('email-error');
    const confirmPasswordError = document.getElementById('confirm-password-error');

    function displayError(element, message) {
        element.textContent = message;
        element.style.display = message ? 'block' : 'none';
    }

    function validateEmail() {
        const email = emailInput.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (!email) {
            displayError(emailError, 'O email é obrigatório.');
            return false;
        }

        if (!emailRegex.test(email)) {
            displayError(emailError, 'Por favor, insira um email válido.');
            return false;
        }

        displayError(emailError, '');
        return true;
    }

    function validateTerms() {
        if (!termsCheckbox.checked) {
            swal({
                title: "Atenção!",
                text: "Você deve aceitar os Termos de Serviço para continuar.",
                type: "warning",
                confirmButtonClass: "btn btn-warning"
            });
            return false;
        }
        return true;
    }

    function validatePassword() {
        const password = passwordInput.value;

        if (!password) {
            displayError(passwordError, 'A senha é obrigatória.');
            return false;
        }

        if (password.length < 8) {
            displayError(passwordError, 'A senha deve ter pelo menos 8 caracteres.');
            return false;
        }

        displayError(passwordError, ''); // Limpa o erro se estiver ok
        return true;
    }

    function checkPasswordStrength(password) {
        let strength = 0;
        const length = password.length;
        const hasLower = /[a-z]/.test(password);
        const hasUpper = /[A-Z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasSpecial = /[!@#$%^&*()_+\[\]{};':"\\|,.<>/?]/.test(password);

        if (length >= 8) strength++;
        if (hasLower && hasUpper) strength++;
        if (hasNumber || hasSpecial) strength++;
        if (length >= 12 && hasLower && hasUpper && (hasNumber || hasSpecial)) strength++;

        let score = 0;
        if (strength >= 3) score = 2;
        else if (strength >= 2) score = 1;
        else if (length > 0) score = 0;

        return score;
    }

    function updatePasswordStrength() {
        const password = passwordInput.value;
        const strengthMeter = document.querySelector('.password-strength-meter'); // Container pai
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');

        if (password.length === 0) {
            strengthMeter.style.display = 'none';
            strengthBar.style.width = '0%';
            return;
        }

        strengthMeter.style.display = 'block';

        const strengthScore = checkPasswordStrength(password);
        let width = 0;
        let color = '#dc3545';
        let text = 'Fraca';

        if (strengthScore === 0) {
            width = 33;
            color = '#dc3545';
            text = 'Fraca';
        } else if (strengthScore === 1) {
            width = 66;
            color = '#ffc107';
            text = 'Média';
        } else if (strengthScore === 2) {
            width = 100;
            color = '#28a745';
            text = 'Forte';
        }

        strengthBar.style.width = `${width}%`;
        strengthBar.style.backgroundColor = color;
        strengthText.textContent = text;
        strengthText.style.color = color;
    }

    function validatePassword() {
        const password = passwordInput.value;
        return password.length >= 8;
    }

    function validateConfirmPassword() {
        const password = passwordInput.value;
        const confirmPassword = confirmPasswordInput.value;

        if (confirmPassword !== password) {
            displayError(confirmPasswordError, 'As senhas não coincidem.');
            return false;
        }

        displayError(confirmPasswordError, '');
        return true;
    }

    // Mostrar/ocultar senha
    document.querySelectorAll('.toggle-password').forEach(icon => {
        icon.addEventListener('click', (e) => {
            const targetId = e.target.getAttribute('data-target');
            const targetInput = document.getElementById(targetId);
            const isPassword = targetInput.type === 'password';

            targetInput.type = isPassword ? 'text' : 'password';
            e.target.classList.toggle('fa-eye', isPassword);
            e.target.classList.toggle('fa-eye-slash', !isPassword);
        });
    });

    // Eventos de validação
    passwordInput.addEventListener('input', () => {
        updatePasswordStrength();
        validatePassword();
    });

    confirmPasswordInput.addEventListener('input', validateConfirmPassword);
    emailInput.addEventListener('input', validateEmail);

    nextStepBtn.addEventListener('click', (e) => {
        e.preventDefault();

        if (validateEmail() && validatePassword() && validateConfirmPassword() && validateTerms()) {
            swal({
                title: "Confirmar registro?",
                text: "Um e-mail de verificação será enviado para completar o cadastro.",
                type: "warning",
                showCancelButton: true,
                reverseButtons: true,
                confirmButtonClass: "btn btn-success",
                cancelButtonClass: "btn btn-danger",
                confirmButtonText: "Sim, enviar",
                cancelButtonText: "Cancelar"
            }).then(result => {
                if (!result.value) return;

                $.post(`${document.location.origin}/signup/post`, {
                    email: emailInput.value.trim(),
                    password: passwordInput.value
                }, function (data) {
                    console.log(data, "data");

                    if (data.errors?.code === "DBE-101") {
                        swal({
                            title: "Erro!",
                            text: "Tente novamente mais tarde! (DBE-101)",
                            type: "error",
                            confirmButtonClass: "btn btn-danger"
                        });
                        return;
                    }

                    if (data.success?.status === true) {
                        window.location.href =
                            `${document.location.origin}/signup/success?email=${encodeURIComponent(
                                emailInput.value.trim()
                            )}`;
                    }
                }).fail(() => {
                    swal({
                        title: "Erro!",
                        text: "O email já está cadastrado no TalkAll. Tente um email diferente.",
                        type: "error",
                        confirmButtonClass: "btn btn-danger"
                    });
                });
            });
        }
    });
});
