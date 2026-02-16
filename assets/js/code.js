document.addEventListener('DOMContentLoaded', () => {
    const button = document.getElementById('validate-button');
    const codeInput = document.getElementById('code');
    const codeError = document.getElementById('code-error');
    const resendWhatsapp = document.querySelector('.whatsapp');
    const resendSms = document.querySelector('.sms');
    const resendContainer = resendWhatsapp.parentElement;
    const token = TOKEN;
    const editPhoneBtn = document.getElementById('edit-phone');

    // --- TOAST ---
    function showToast(message, duration = 2500, color = "#4CAF50") {
        let toast = document.getElementById('toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'toast';
            toast.style.cssText = `
                position: fixed;
                top: 15%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: ${color};
                color: white;
                padding: 15px 25px;
                border-radius: 6px;
                font-family: sans-serif;
                font-size: 15px;
                z-index: 9999;
                opacity: 0;
                transition: opacity 0.3s ease;
            `;
            document.body.appendChild(toast);
        }
        toast.style.background = color;
        toast.textContent = message;
        toast.style.opacity = "1";
        setTimeout(() => (toast.style.opacity = "0"), duration);
    }

    function displayError(el, msg) {
        el.textContent = msg;
        el.style.display = msg ? 'block' : 'none';
    }

    function validateCode() {
        const code = codeInput.value.trim();
        if (!code || code.length !== 5 || isNaN(code)) {
            displayError(codeError, 'Informe um código de 5 dígitos válido.');
            return false;
        }
        displayError(codeError, '');
        return true;
    }

    // --- BOTÃO VALIDAR ---
    button.addEventListener('click', async (e) => {
        e.preventDefault();
        if (!validateCode()) return;

        try {
            const csrfName = "csrf_token_talkall";
            const csrfValue = Cookies.get("csrf_cookie_talkall");

            const res = await fetch(`/signup/validateCode/${token}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' },
                body: new URLSearchParams({ code: codeInput.value.trim(), [csrfName]: csrfValue })
            });
            const data = await res.json();

            if (data.success) {
                showToast('Código verificado! Redirecionando...');
                setTimeout(() => window.location.href = 'https://app.talkall.com.br/', 2000);
            } else {
                showToast(data.message || 'Código inválido.', 2500, '#e53935');
            }
        } catch {
            showToast('Erro ao validar código.', 2500, '#e53935');
        }
    });

    // --- REENVIO GLOBAL ---
    const stateKey = 'resend_global';
    let state = JSON.parse(localStorage.getItem(stateKey)) || { count: 0, lockUntil: 0 };
    const saveState = () => localStorage.setItem(stateKey, JSON.stringify(state));

    function showTimer(remaining) {
        const min = String(Math.floor(remaining / 60)).padStart(2, '0');
        const sec = String(remaining % 60).padStart(2, '0');

        resendContainer.innerHTML = `
        <span style="
            display: inline-block;
            padding: 6px 12px;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 6px;
            color: #212529;
            font-weight: 600;
            font-size: 14px;
        ">
            Reenviar em <span style="color:#0d6efd;">${min}:${sec}</span>
        </span>
    `;
    }

    function updateResendButtons() {
        const now = Date.now();

        // bloqueio total
        if (state.count >= 3) {
            resendContainer.innerHTML = `<b style="color:#9ca3af;">Reenvio bloqueado permanentemente</b>`;
            return;
        }

        if (now < state.lockUntil) {
            const remaining = Math.ceil((state.lockUntil - now) / 1000);
            showTimer(remaining);
            setTimeout(updateResendButtons, 1000);
        } else {
            resendContainer.innerHTML = `
                <b>Não recebeu o código?</b><br>
                <a href="#" class="whatsapp"><i class="fab fa-whatsapp"></i> Reenviar via WhatsApp</a>
                <a href="#" class="sms"><i class="fas fa-comment-dots"></i> Reenviar via SMS</a>
            `;
            attachEvents();
        }
    }

    async function handleResend(url, label) {
        const now = Date.now();
        if (now < state.lockUntil) return;
        if (state.count >= 3) {
            showToast('Limite de reenviamentos atingido.', 3000, '#e53935');
            return;
        }

        showTimer(300);

        try {
            const csrfName = "csrf_token_talkall";
            const csrfValue = Cookies.get("csrf_cookie_talkall");
            const res = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8' },
                body: new URLSearchParams({ [csrfName]: csrfValue, token })
            });
            const text = await res.text();
            const data = JSON.parse(text || '{}');

            if (res.ok && data.success !== false) {
                showToast(`Código reenviado via ${label}!`);
                state.count++;
                if (state.count === 1) state.lockUntil = now + 5 * 60 * 1000;
                else if (state.count === 2) state.lockUntil = now + 15 * 60 * 1000;
                else state.lockUntil = Infinity;
                saveState();
                updateResendButtons();
            } else {
                showToast(data.message || 'Erro ao reenviar.', 3000, '#e53935');
                updateResendButtons();
            }
        } catch {
            showToast('Erro de conexão.', 2500, '#e53935');
            updateResendButtons();
        }
    }

    function attachEvents() {
        const whatsapp = document.querySelector('.whatsapp');
        const sms = document.querySelector('.sms');

        whatsapp.addEventListener('click', (e) => {
            e.preventDefault();
            handleResend(`/signup/whatsapp/code/resend/${token}`, 'WhatsApp');
        });

        sms.addEventListener('click', (e) => {
            e.preventDefault();
            handleResend(`/signup/sms/code/resend/${token}`, 'SMS');
        });
    }

    updateResendButtons();

    editPhoneBtn.addEventListener('click', async () => {
        const { value: raw } = await swal({
            title: "Alterar telefone",
            text: "Informe o número do telefone:",
            input: "text",
            inputPlaceholder: "(99) 99999-9999",
            showCancelButton: true,
            confirmButtonText: "Salvar",
            cancelButtonText: "Cancelar",
            confirmButtonClass: "btn btn-success",
            cancelButtonClass: "btn btn-danger",
            reverseButtons: true,
            onOpen: () => {
                const input = document.querySelector('.swal2-input');
                if (!input) return;

                input.addEventListener('input', (e) => {
                    let v = e.target.value.replace(/\D/g, '');
                    if (v.length > 11) v = v.slice(0, 11);

                    // Máscara flexível
                    if (v.length > 10) {
                        e.target.value = v.replace(/^(\d{2})(\d{5})(\d{0,4}).*/, '($1) $2-$3');
                    } else if (v.length > 6) {
                        e.target.value = v.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
                    } else if (v.length > 2) {
                        e.target.value = v.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
                    } else {
                        e.target.value = v;
                    }
                });

                input.addEventListener('keypress', (e) => {
                    if (!/[0-9]/.test(e.key)) e.preventDefault();
                });
            },
            inputValidator: (value) => {
                if (!value) return "Informe o DDD + número.";
                const v = value.replace(/\D/g, '');
                if (value.startsWith('55')) return "Não inclua o código do país (55).";
                if (v.length < 10 || v.length > 11) return "Informe 10 ou 11 dígitos (DDD + número).";
            }
        });

        if (!raw) return;

        const newPhone = raw.replace(/\D/g, '');

        try {
            const csrfName = "csrf_token_talkall";
            const csrfValue = Cookies.get("csrf_cookie_talkall");

            const dataToSend = new URLSearchParams({
                phone: newPhone,
                [csrfName]: csrfValue
            });

            const res = await fetch(`/signup/updatePhone/${token}`, {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded;charset=UTF-8" },
                body: dataToSend
            });

            const data = await res.json();

            if (res.ok && data.success) {
                // 🔄 RESETAR REENVIO DE CÓDIGO
                localStorage.removeItem('resend_global');
                state = { count: 0, lockUntil: 0 };
                saveState();
                updateResendButtons();

                swal({
                    title: "Número atualizado!",
                    text: "O novo número foi salvo com sucesso.",
                    type: "success",
                    confirmButtonClass: "btn btn-success"
                }).then(() => location.reload());
            } else {
                swal({
                    title: "Erro!",
                    text: data.message || "Não foi possível atualizar o número.",
                    type: "error",
                    confirmButtonClass: "btn btn-danger"
                });
            }
        } catch {
            swal({
                title: "Erro!",
                text: "Falha na comunicação com o servidor.",
                type: "error",
                confirmButtonClass: "btn btn-danger"
            });
        }
    });
});
