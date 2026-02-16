document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('form');
    const nameInput = document.getElementById('name');
    const companyInput = document.getElementById('company');
    const phoneInput = document.getElementById('phone');

    const nameError = document.getElementById('name-error');
    const companyError = document.getElementById('company-error');
    const phoneError = document.getElementById('phone-error');

    function displayError(element, message) {
        element.textContent = message;
        element.style.display = message ? 'block' : 'none';
    }

    function validateName() {
        const name = nameInput.value.trim();
        if (!name) {
            displayError(nameError, 'O nome completo é obrigatório.');
            return false;
        }
        if (name.length < 5) {
            displayError(nameError, 'Informe seu nome completo.');
            return false;
        }
        displayError(nameError, '');
        return true;
    }

    function validateCompany() {
        const company = companyInput.value.trim();
        if (!company) {
            displayError(companyError, 'O nome da empresa é obrigatório.');
            return false;
        }
        if (company.length < 2) {
            displayError(companyError, 'Informe um nome válido para a empresa.');
            return false;
        }
        displayError(companyError, '');
        return true;
    }

    function validatePhone() {
        const phone = phoneInput.value.trim();
        const phoneRegex = /^\(\d{2}\)\s9?\d{4}-?\d{4}$/;
        if (!phone) {
            displayError(phoneError, 'O telefone é obrigatório.');
            return false;
        }
        if (!phoneRegex.test(phone)) {
            displayError(phoneError, 'Informe um telefone válido no formato (XX) XXXXX-XXXX ou (XX) XXXX-XXXX.');
            return false;
        }
        displayError(phoneError, '');
        return true;
    }

    phoneInput.addEventListener('input', (e) => {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 11) value = value.slice(0, 11);
        if (value.length >= 2) value = `(${value.substring(0, 2)}) ${value.substring(2)}`;
        if (value.length >= 10) value = value.replace(/(\(\d{2}\))\s(\d{5})(\d{4})/, '$1 $2-$3');
        e.target.value = value;
    });

    nameInput.addEventListener('input', validateName);
    companyInput.addEventListener('input', validateCompany);
    phoneInput.addEventListener('input', validatePhone);

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const validName = validateName();
        const validCompany = validateCompany();
        const validPhone = validatePhone();

        if (!(validName && validCompany && validPhone)) return;

        const urlParts = window.location.pathname.split('/');
        const token = urlParts[urlParts.length - 1];

        swal({
            title: "Confirmar cadastro?",
            text: "Usaremos essas informações para iniciar seu teste gratuito.",
            type: "warning",
            showCancelButton: true,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            cancelButtonClass: "btn btn-danger",
            confirmButtonText: "Sim, continuar",
            cancelButtonText: "Cancelar"
        }).then(async (result) => {
            if (result.value) {
                try {
                    const csrfTokenName = "csrf_token_talkall";
                    const csrfTokenValue = Cookies.get("csrf_cookie_talkall");

                    const dataToEncode = {
                        name: nameInput.value.trim(),
                        company: companyInput.value.trim(),
                        phone: phoneInput.value.trim(),
                        token: token,
                        [csrfTokenName]: csrfTokenValue
                    };

                    const urlEncodedBody = new URLSearchParams(dataToEncode).toString();

                    // 🔵 Exibe a tela de carregamento
                    document.getElementById('loading-screen').style.display = 'flex';

                    const response = await fetch('/signup/company', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded;charset=UTF-8'
                        },
                        body: urlEncodedBody
                    });

                    const res = await response.json();

                    // ❌ Esconde a tela de carregamento
                    document.getElementById('loading-screen').style.display = 'none';

                    if (res.status === 'success' && !res.errors) {
                        swal({
                            title: "Cadastro concluído!",
                            text: "Você receberá o código de validação no WhatsApp informado.",
                            type: "success",
                            confirmButtonClass: "btn btn-success"
                        }).then(() => {
                            form.reset();
                            window.location.href = `/signup/code?token=${token}`;
                        });
                    } else {
                        swal({
                            title: "Erro!",
                            text: res.message || "Ocorreu um erro ao finalizar o cadastro.",
                            type: "error",
                            confirmButtonClass: "btn btn-danger"
                        });
                    }

                    $("[name='csrf_token_talkall']").val(Cookies.get("csrf_cookie_talkall"));
                } catch (error) {
                    document.getElementById('loading-screen').style.display = 'none';

                    swal({
                        title: "Erro!",
                        text: "Falha ao conectar com o servidor.",
                        type: "error",
                        confirmButtonClass: "btn btn-danger"
                    });
                }
            }
        });
    });
});
