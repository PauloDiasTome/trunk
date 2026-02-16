$(function () {
    let authCode = null;
    let pendingWabaData = null;


    function openStep(step) {
        $('.modal').modal('hide');
        setTimeout(() => {
            $('#modal-tutorial-step-' + step).modal('show');
        }, 400);
    }

    function loadOnboarding(auto = false) {

        fetch(document.location.origin + '/tutorial/getstep')
            .then(res => res.json())
            .then(data => {
                if (!data || data.status == 1) {
                    if (auto) openStep(1);
                    return;
                }

                if (data.status == 2) {
                    if (auto && data.current_step >= 3) return;
                    openStep(data.current_step);
                    return;
                }

                if (data.status == 3) {
                    if (!auto) openStep(4);
                }
            })
            .catch(() => {
                if (auto) openStep(1);
            });
    }


    function updateOnboarding(step, status = 2) {
        const formData = new FormData();
        formData.append('current_step', step);
        formData.append('status', status);
        formData.append('csrf_token_talkall', Cookies.get('csrf_cookie_talkall'));

        fetch(document.location.origin + '/tutorial/savestep', {
            method: 'POST',
            body: formData
        });
    }

    if (window.COMPANY_FLAGS.communication != 1 && window.COMPANY_FLAGS.is_trial == 1 && window.COMPANY_FLAGS.is_in_trial_period == 1) {
        loadOnboarding(true);
    }

    $('.btn-open-tutorial').on('click', function () {
        loadOnboarding(false);
    });


    $('#tutorial-step-1-next').on('click', function () {
        updateOnboarding(2, 2);
        openStep(2);
    });

    (function (d, s, id) {
        if (d.getElementById(id)) return;
        const js = d.createElement(s);
        js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        const fjs = d.getElementsByTagName(s)[0];
        fjs.parentNode.insertBefore(js, fjs);
    })(document, "script", "facebook-jssdk");

    window.fbAsyncInit = function () {
        FB.init({
            appId: "549279743372390",
            autoLogAppEvents: true,
            xfbml: false,
            version: "v22.0"
        });

        $('#connect-whatsapp').on('click', launchWhatsAppSignup);
    };

    function launchWhatsAppSignup() {
        FB.login(function (response) {
            if (response?.authResponse?.code) {
                authCode = response.authResponse.code;

                if (pendingWabaData) {
                    saveBusiness(
                        pendingWabaData.waba_id,
                        pendingWabaData.business_id
                    );
                    pendingWabaData = null;
                }
            }
        }, {
            config_id: "754066543306952",
            response_type: "code",
            override_default_response_type: true,
            extras: {
                setup: {},
                featureType: "whatsapp_business_app_onboarding",
                sessionInfoVersion: "3"
            }
        });
    }

    window.addEventListener("message", function (event) {
        if (!["https://www.facebook.com", "https://web.facebook.com"].includes(event.origin)) {
            return;
        }

        try {
            const data = JSON.parse(event.data);

            if (data.type === "WA_EMBEDDED_SIGNUP") {
                const { business_id, waba_id } = data.data;

                if (!authCode) {
                    pendingWabaData = { waba_id, business_id };
                } else {
                    saveBusiness(waba_id, business_id);
                }
            }
        } catch (e) {
            console.error("Erro postMessage:", e);
        }
    });

    function saveBusiness(waba_id, business_id) {
        const formData = new FormData();
        formData.append("waba_id", waba_id);
        formData.append("business_id", business_id);
        formData.append("auth_code", authCode);
        console.log('Saving business with auth code:', authCode);
        formData.append("csrf_token_talkall", Cookies.get('csrf_cookie_talkall'));
        debugger
        fetch(document.location.origin + '/integration/save_business', {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                if (data.success?.status === true) {
                    debugger
                    if (data.display_phone_number) {
                        $('#integrated-phone').text(data.display_phone_number);
                    }

                    updateOnboarding(3, 2);
                    openStep(3);

                } else {
                    alert('Erro ao salvar integração.');
                }
            })
            .catch(() => {
                alert('Erro de comunicação com o servidor.');
            });
    }

    $('#open-messenger').on('click', function () {
        updateOnboarding(4, 3);
        window.open(document.location.origin + '/messenger', '_blank');
        openStep(4);
    });

});
