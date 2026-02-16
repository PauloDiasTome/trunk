let newPin = [];
const Filters = JSON.parse(localStorage.getItem("filters")) || null;


function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.integration?.search) {
            document.getElementById("search").value = Filters.integration.search;
            searchOnResultFrontEnd();
        }

        Filters.btn_back = null;
        localStorage.setItem("filters", JSON.stringify(Filters));
    }
}

checkFilter();


document.querySelectorAll(".btn.btn-primary, #btn-return").forEach(function (button) {
    button.addEventListener("click", function () {
        if (Filters) {
            Filters.btn_back = true;
            localStorage.setItem("filters", JSON.stringify(Filters));
        }
    });
});


if (document.getElementById("whatsapp-cloud")) {
    //? Integração do WhatsApp Cloud //

    const modal_title = document.getElementById("modal-pin-label");
    const modal_text = document.querySelector("#modal-pin .modal-body span");
    const alert_field = document.querySelector("#modal-pin .alert-field-validation");


    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, "script", "facebook-jssdk"));

    window.fbAsyncInit = function () {
        FB.init({
            appId: "549279743372390",
            autoLogAppEvents: true,
            xfbml: true,
            version: "v18.0"
        });
    };

    function clearInputs(modal) {
        alert_field.style.display = "none";

        document.querySelectorAll(".modal-body input").forEach(function (input, index) {
            if (modal.action == "next" || modal.action == "return") {
                input.classList.toggle("pin-input");
                input.classList.toggle("pin-confirm");
            }

            input.value = "";
            if (index === 0) {
                input.focus();
            }
        });
    }

    function setModalContent(modal) {
        clearInputs(modal);

        switch (modal.action) {
            case "init":
            case "return":
                newPin = [];
                modal_title.innerHTML = GLOBAL_LANG.setting_integration_cloud_modal_pin_insert_title;
                modal_text.innerHTML = GLOBAL_LANG.setting_integration_cloud_modal_pin_insert_text;

                document.getElementById("btn-pin-next").style.display = "block";
                document.getElementById("btn-pin-return").style.display = "none";
                document.getElementById("btn-integration-conclude").style.display = "none";

                break;
            case "next":
                modal_title.innerHTML = GLOBAL_LANG.setting_integration_cloud_modal_pin_confirm_title;
                modal_text.innerHTML = GLOBAL_LANG.setting_integration_cloud_modal_pin_confirm_text;

                document.getElementById("btn-pin-next").style.display = "none";
                document.getElementById("btn-pin-return").style.display = "block";
                document.getElementById("btn-integration-conclude").style.display = "block";

                break;

            default:
                break;
        }

    }

    function showAlert(modal) {
        let lang = GLOBAL_LANG.setting_integration_cloud_pin_alert_min_characters;
        let marginTop = "108px";

        if (modal.action == "conclude") {
            marginTop = "88px";

            if (!modal.empty) {
                lang = GLOBAL_LANG.setting_integration_cloud_pin_alert_incorrect_code;
                clearInputs({ action: modal.action });
            }
        }

        alert_field.innerHTML = lang;
        alert_field.style.marginTop = marginTop;
        alert_field.style.display = "block";
    }

    function validateFields(modal) {
        const inputs = document.querySelectorAll(".pin-confirm").length > 0 ? document.querySelectorAll(".pin-confirm") : document.querySelectorAll(".pin-input");
        const allFilled = Array.from(inputs).every(input => input.value.length === input.maxLength);

        if (allFilled) {

            alert_field.style.display = "none";

            if (modal.action == "next") {
                newPin.push(Array.from(inputs).map(input => input.value).join(''));
                setModalContent(modal);
            }

            if (modal.action == "conclude") {
                newPin[1] = Array.from(inputs).map(input => input.value).join('');

                if (newPin[0] !== newPin[1]) {
                    showAlert({ action: modal.action, empty: false });

                } else {
                    document.getElementById("pin_code").value = newPin[0];
                    submit();
                    $("#modal-pin").modal("hide");
                }
            }

        } else
            showAlert({ action: modal.action, empty: true });
    }

    function autoTabFields() {
        document.querySelectorAll("#modal-pin input").forEach(function (input) {
            input.addEventListener("input", function () {
                this.value = this.value.replace(/[^\d]/g, "").trim();

                if (this.value.length == this.maxLength) {
                    const nextInput = this.nextElementSibling;
                    if (nextInput && nextInput.tagName === "INPUT") {
                        nextInput.focus();
                    }
                }
            });
        });
    }

    function openModalPin() {
        setModalContent({ action: "init" });
        $("#modal-pin").modal("show");
        autoTabFields();
    }

    function launchWhatsAppSignup() {
        FB.login(function (response) {
            if (response.authResponse && response.authResponse.code) {
                document.getElementById("code").value = response.authResponse.code;

                openModalPin();

            } else {
                console.log("Usuário cancelou ou não finalizou o processo de login");
                location.reload(true);
            }
        }, {
            config_id: "754066543306952",
            response_type: "code",
            override_default_response_type: true,
            extras: {
                // featureType: "only_waba_sharing",
                sessionInfoVersion: 2,
                setup: {
                }
            }
        });
    }

    const sessionInfoListener = (event) => {

        if (event.origin !== "https://www.facebook.com" && event.origin !== "https://web.facebook.com") {
            return;
        } else {
            console.log("origin url não encontrada")
        }


        try {
            const data = JSON.parse(event.data);

            if (data.type === "WA_EMBEDDED_SIGNUP") {
                const { phone_number_id, waba_id, current_step } = data.data;

                switch (data.event) {
                    case "FINISH":
                        document.getElementById("phone_number_id").value = phone_number_id;
                        document.getElementById("waba_id").value = waba_id;
                        break;
                    case "FINISH_ONLY_WABA":
                        document.getElementById("phone_number_id").value = "";
                        document.getElementById("waba_id").value = waba_id;
                        break;
                    default:
                        console.log(current_step);
                        break;
                }
            }
        } catch {
            console.log("Non JSON Response");
        }
    };

    window.addEventListener("message", sessionInfoListener);

    function submit() {
        $("form").unbind("submit").submit();
    }

    function alertErros() {

        const error = document.getElementById("error").value.trim() ? JSON.parse(document.getElementById("error").value.trim()) : {};

        if (error.errors?.code == "TA-003") {
            swal({
                title: GLOBAL_LANG.setting_integration_cloud_error_title,
                text: `${GLOBAL_LANG.setting_integration_cloud_error_ta003} (${error.errors.code})`,
                type: "error",
                buttonsStyling: !1,
                confirmButtonClass: "btn btn-danger"
            });
        }

        if (error.errors?.code == "TA-004") {
            swal({
                title: GLOBAL_LANG.setting_integration_cloud_error_title,
                text: `${GLOBAL_LANG.setting_integration_cloud_error_ta004} (${error.errors.code})`,
                type: "error",
                buttonsStyling: !1,
                confirmButtonClass: "btn btn-danger"
            });
        }

        if (error.errors?.code == "TA-005") {
            swal({
                title: GLOBAL_LANG.setting_integration_cloud_error_title,
                text: `${GLOBAL_LANG.setting_integration_cloud_error_ta005} (${error.errors.code})`,
                type: "error",
                buttonsStyling: !1,
                confirmButtonClass: "btn btn-danger"
            });
        }
    }

    alertErros();
}

if (document.getElementById("whatsapp-business")) {
    const button = document.getElementById("startSdkBtn");
    const buttonText = document.querySelector(".btn-inner--text");
    let authCode = null;
    let pendingWabaData = null;

    console.log("[BUSINESS] Iniciando carregamento do Facebook SDK...");

    (function (d, s, id) {
        if (d.getElementById(id)) return;
        const js = d.createElement(s); js.id = id;
        js.src = "https://connect.facebook.net/en_US/sdk.js";
        const fjs = d.getElementsByTagName(s)[0];
        fjs.parentNode.insertBefore(js, fjs);
    })(document, "script", "facebook-jssdk");

    window.fbAsyncInit = function () {
        console.log("[BUSINESS] fbAsyncInit executado");

        FB.init({
            appId: "549279743372390",
            autoLogAppEvents: true,
            xfbml: false,
            version: "v22.0"
        });

        console.log("[BUSINESS] FB.init concluído");

        button.disabled = false;
        buttonText.innerText = "Facebook Login";
        button.addEventListener("click", launchWhatsAppSignup);
    };

    function launchWhatsAppSignup() {
        console.log("[BUSINESS] Iniciando FB.login...");
        FB.login(function (response) {
            console.log("[BUSINESS] Resposta do FB.login:", response);

            if (response?.authResponse?.code) {
                authCode = response.authResponse.code;
                console.log("[BUSINESS] Código recebido via FB.login:", authCode);

                if (pendingWabaData) {
                    const { waba_id, business_id } = pendingWabaData;
                    if (business_id) {
                        console.log("[BUSINESS] Dados pendentes detectados, salvando:", pendingWabaData);
                        saveBusiness(waba_id, business_id);
                    } else {
                        console.warn("[BUSINESS] business_id está indefinido!");
                    }
                    pendingWabaData = null;
                }
            } else {
                console.warn("[BUSINESS] Nenhum código retornado pelo login ou usuário cancelou.");
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
        if (!["https://www.facebook.com", "https://web.facebook.com"].includes(event.origin)) return;
        if (typeof event.data !== "string" || !event.data.startsWith("{")) return;

        try {
            const data = JSON.parse(event.data);
            console.log("[BUSINESS] Dados parseados do postMessage:", data);

            if (data.type === "WA_EMBEDDED_SIGNUP") {
                const { business_id, waba_id } = data.data;

                console.log("[BUSINESS] Evento finalizado:", data.event);
                console.log("[BUSINESS] waba_id:", waba_id);
                console.log("[BUSINESS] business_id:", business_id);

                if (!authCode) {
                    console.log("[BUSINESS] Código ainda não disponível, aguardando login...");
                    pendingWabaData = { waba_id, business_id };
                    return;
                }

                if (!business_id) {
                    console.warn("[BUSINESS] business_id está indefinido!");
                    return;
                }

                saveBusiness(waba_id, business_id);
            }
        } catch (e) {
            console.error("[BUSINESS] Erro ao interpretar postMessage:", e);
        }
    });

    function getCsrfToken() {
        return {
            name: document.querySelector('meta[name="csrf-name"]').content,
            value: document.querySelector('meta[name="csrf-token"]').content
        };
    }

    function saveBusiness(waba_id, business_id) {
        const formData = new FormData();
        formData.append("waba_id", waba_id);
        formData.append("business_id", business_id);
        formData.append("auth_code", authCode);
        formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

        fetch(document.location.origin + '/integration/save_business', {
            method: 'POST',
            body: formData
        }).then((response) => response.json())
            .then((data) => {
                switch (data.errors?.code) {
                    case "TA-003":
                        swal({
                            title: GLOBAL_LANG.setting_integration_alert_business_title,
                            text: `${GLOBAL_LANG.setting_integration_alert_business_text} (${data.errors.code})`,
                            type: "error",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-primary"
                        });
                        break;
                    case "TA-004":
                        swal({
                            title: GLOBAL_LANG.setting_integration_alert_business_title,
                            text: `${GLOBAL_LANG.setting_integration_alert_business_text_duplicate} (${data.errors.code})`,
                            type: "error",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-primary"
                        });
                        break;
                    case "TA-005":
                        swal({
                            title: GLOBAL_LANG.setting_integration_alert_business_title,
                            text: `${GLOBAL_LANG.setting_integration_alert_business_text_webhook_failed} (${data.errors.code})`,
                            type: "error",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-primary"
                        });
                        break;
                    default:
                        break;
                }

                if (data.success?.status === true) {
                    swal({
                        title: GLOBAL_LANG.setting_integration_alert_business_success_title,
                        text: GLOBAL_LANG.setting_integration_alert_business_success_text,
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    }).then(() => {
                        window.location.href = "https://app.talkall.com.br/integration";
                    });
                }
            }).catch((error) => {
                console.error('Error:', error);
            });
    }
}

function clearChannel(id_channel) {

    swal({
        title: GLOBAL_LANG.setting_integration_alert_clear_title,
        text: GLOBAL_LANG.setting_integration_alert_clear_text,
        type: "warning",
        showCancelButton: !0,
        buttonsStyling: !1,
        reverseButtons: true,
        confirmButtonClass: "btn btn-success",
        confirmButtonText: GLOBAL_LANG.setting_integration_alert_clear_confirmButtonText,
        cancelButtonClass: "btn btn-danger",
        cancelButtonText: GLOBAL_LANG.setting_integration_alert_clear_cancelButtonText,
    }).then(async t => {
        if (t.value == true) {

            const formData = new FormData();
            formData.append("id_channel", id_channel);
            formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

            const response = await fetch(`${document.location.origin}/integration/clear/channel`, {
                method: "POST",
                body: formData
            });

            const data = await response.json();

            if (data.success?.status == true) {
                t.value && swal({
                    title: GLOBAL_LANG.setting_integration_response_alert_clear_title,
                    text: GLOBAL_LANG.setting_integration_response_alert_clear_text,
                    type: "success",
                    buttonsStyling: !1,
                    confirmButtonClass: "btn btn-success"
                }).then(() => {
                    document.location.reload(true);
                });
            }

            if (data.errors?.code == "PAD-002") {
                swal({
                    title: GLOBAL_LANG.setting_integration_response_error_clear_title,
                    text: `${GLOBAL_LANG.setting_integration_response_error_clear_pad002} (${data.errors.code})`,
                    type: "error",
                    buttonsStyling: !1,
                    confirmButtonClass: "btn btn-danger"
                });
            }
        }
    });
}


$(document).ready(function () {

    $("#search").on("keydown", function () {
        searchOnResultFrontEnd();

        const integration = {
            search: $("#search").val(),
        }

        let filter = localStorage.getItem("filters");

        filter = filter ? JSON.parse(filter) : {};
        filter.integration = integration;

        localStorage.setItem("filters", JSON.stringify(filter));
    });

    $("#btn-pin-next").on("click", function () {
        validateFields({ action: "next" });
    });

    $("#btn-pin-return").on("click", function () {
        setModalContent({ action: "return" });
    });

    $("#btn-integration-conclude").on("click", function () {
        validateFields({ action: "conclude" });
    });

    var integrationDuplicate = document.querySelector("#integrationValidation") != null ? document.querySelector("#integrationValidation") : "";
    var cont = 0;
    if (integrationDuplicate === "true" && cont == 0) {

        swal({
            title: GLOBAL_LANG.setting_integration_alert_sucess_title,
            text: GLOBAL_LANG.setting_integration_alert_sucess_text,
            type: "success",
            buttonsStyling: !1,
            confirmButtonClass: "btn btn-success"
        });

        setTimeout(function () {

            var ulr_not_parameters = window.location.href.split('?')[0];

            window.location.href = ulr_not_parameters;
        }, 2000);

    }

    if (integrationDuplicate === "false" && cont == 0) {

        swal({
            title: GLOBAL_LANG.setting_integration_alert_error_title,
            text: GLOBAL_LANG.setting_integration_alert_error_text,
            type: "error",
            buttonsStyling: !1,
            confirmButtonClass: "btn btn-danger"
        });

        setTimeout(function () {

            var ulr_not_parameters = window.location.href.split('?')[0];

            window.location.href = ulr_not_parameters;


        }, 2000);
    }


    $("#copy-clipboard").on("click", function () {
        copyTextToClipboard(document.getElementsByTagName("code")[0].textContent);
        swal({
            title: GLOBAL_LANG.setting_integration_alert_copy_title,
            text: GLOBAL_LANG.setting_integration_alert_copy_text,
            type: "success",
            buttonsStyling: !1,
            confirmButtonClass: "btn btn-success"
        });
    });


    $(".disconnect").on("click", function () {

        const id = $(this).data("itg");

        swal({
            title: "Atenção",
            text: "Você tem certeza que deseja remover está integração ?",
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: "Confirmar",
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: "Cancelar",
        }).then(t => {
            if (t.value == true) {
                $.get(document.location.origin + "/integration/page/delete/" + id, function (data) {
                    console.log(data)
                    t.value && swal({
                        title: "Sucesso",
                        text: "Integração foi removida com sucesso!",
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });
                    document.location.reload(true);
                });
            }
        });
    });


    $(".img-settings").on("click", function (e) {

        const id = $(this).attr("data-id");
        const popView = document.getElementById(id);

        if (popView.style.display === "none") {

            popView.style.display = "block";
            popView.classList.add("pop-view");

            this.style.backgroundColor = "#e4e4e4";

        } else {

            popView.style.display = "none";
            popView.classList.remove("pop-view");
            this.style.backgroundColor = "#fff";
        }

        const pop_settings = document.querySelectorAll(".pop-settings");
        const img_settings = document.querySelectorAll(".img-settings");

        for (setting of pop_settings) {

            if (setting.attributes.id.nodeValue != id) {

                for (elm of img_settings) {

                    if (elm.attributes[1].nodeValue != id) {
                        elm.style.backgroundColor = "#fff";
                    }
                }
                setting.style.display = "none";
            }
        }
        e.stopPropagation();
    });

    $("body").on("click", function () {

        $(".pop-settings").css({ "display": "none" });
        $(".pop-settings").removeClass("pop-view");
        $(".img-settings").css({ "background-color": "#fff" });
    });

    if (document.getElementById("oauth_response")) {

        const oauth_response = document.getElementById("oauth_response").value;
        if (oauth_response == 'success') {
            swal({
                title: GLOBAL_LANG.setting_integration_alert_sucess_title,
                text: GLOBAL_LANG.setting_integration_alert_success_text,
                type: "success",
                buttonsStyling: !1,
                confirmButtonClass: "btn btn-success"
            }).then(() => {
                window.location.href = document.location.origin + "/integration";
            });
        }

        if (oauth_response == 'TA-041' || oauth_response == 'PAD-002') {
            swal({
                title: GLOBAL_LANG.setting_integration_response_error_clear_title,
                text: `${GLOBAL_LANG.setting_integration_response_error_clear_pad002} (TA-041)`,
                type: "error",
                buttonsStyling: !1,
                confirmButtonClass: "btn btn-danger"
            }).then(() => {
                window.location.href = document.location.origin + "/integration";
            });
        }

        if (oauth_response == 'TA-042') {
            swal({
                title: GLOBAL_LANG.setting_integration_response_error_telegram_token_invalid_title,
                text: `${GLOBAL_LANG.setting_integration_response_error_telegram_token_invalid_text} (TA-042)`,
                type: "error",
                buttonsStyling: !1,
                confirmButtonClass: "btn btn-danger"
            }).then(() => {
                window.location.href = document.location.origin + "/integration";
            });;
        }

        if (oauth_response == 'TA-043') {
            swal({
                title: GLOBAL_LANG.setting_integration_response_error_telegram_token_invalid_title,
                text: `${GLOBAL_LANG.setting_integration_response_error_telegram_ta043} (TA-043)`,
                type: "error",
                buttonsStyling: !1,
                confirmButtonClass: "btn btn-danger"
            }).then(() => {
                window.location.href = document.location.origin + "/integration";
            });;
        }

        if (oauth_response == 'TA-044') {
            swal({
                title: GLOBAL_LANG.setting_integration_response_error_telegram_token_invalid_title,
                text: `${GLOBAL_LANG.setting_integration_response_error_telegram_ta044} (TA-044)`,
                type: "error",
                buttonsStyling: !1,
                confirmButtonClass: "btn btn-danger"
            }).then(() => {
                window.location.href = document.location.origin + "/integration";
            });;
        }
    }

});


function DeleteIntegration(url) {
    Swal.fire({
        title: GLOBAL_LANG.setting_integration_alert_delete_title,
        text: GLOBAL_LANG.setting_integration_alert_delete_text,
        type: "warning",
        showCancelButton: !0,
        buttonsStyling: !1,
        reverseButtons: true,
        confirmButtonClass: "btn btn-success",
        confirmButtonText: GLOBAL_LANG.setting_integration_alert_delete_confirmButtonText,
        cancelButtonClass: "btn btn-danger",
        cancelButtonText: GLOBAL_LANG.setting_integration_alert_delete_cancelButtonText,
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: url,
                type: 'DELETE',
                success: function (data) {
                    if (data.errors?.code == "TA-009") {
                        swalWaitList(data.errors.code);
                        return;
                    };

                    if (data.errors?.code == "TA-010") {
                        swalAttendance(data.errors.code);
                        return;
                    };

                    if (data.errors?.code == "TA-011") {
                        swalCampaign(data.errors.code);
                        return;
                    };

                    if (data.errors?.code == "PAD-002") {
                        swalGeneralError(data.errors.code);
                        return;
                    };

                    swal({
                        title: GLOBAL_LANG.setting_integration_alert_delete_two_title,
                        text: GLOBAL_LANG.setting_integration_alert_delete_two_text,
                        type: "success",
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    }).then(function () {
                        location.reload();
                    }
                    );
                }
            });
        }
    });
}


function copyTextToClipboard(text) {
    if (!navigator.clipboard) {
        fallbackCopyTextToClipboard(text);
        return;
    }
    navigator.clipboard.writeText(text).then(function () {
        console.log('Async: Copying to clipboard was successful!');
    }, function (err) {
        console.error('Async: Could not copy text: ', err);
    });
}


function searchOnResultFrontEnd() {
    let cards = $('.col-xl-6.col-md-6');
    let searchText = $('#search').val();

    for (let i = 0; i < cards.length; i++) {
        if (cards[i].innerText.toLowerCase().includes(searchText.toLowerCase())) {
            cards[i].style.display = "block";
        } else {
            cards[i].style.display = "none";
        }
    }
}


//setup before functions
var $input = $('#zendesk-domain');

//on keyup, start the countdown
$input.on('keyup', function () {
    var domain = $(this).val();

    $("#zendesk-link").addClass("disabled");

    if (domain) {
        $("#zendesk-link").removeClass("disabled");
    }

    var oldUrl = $("#zendesk-link").attr("href");
    var urlParts = oldUrl.replace('http://', '').replace('https://', '').split(/[/?#]/);
    var newUrl = oldUrl.replace(urlParts[0], domain + ".zendesk.com");
    var url = new URL(newUrl);
    url.searchParams.set("state", domain);
    $("#zendesk-link").attr("href", url.href);

});


function swalWaitList(e) {
    swal({
        title: GLOBAL_LANG.setting_integration_alert_delete_wait_list_title,
        text: `${GLOBAL_LANG.setting_integration_alert_delete_wait_list_text} (${e})`,
        type: "error",
        buttonsStyling: !1,
        confirmButtonClass: "btn btn-success"
    }).then(function () {
        location.reload();
    }
    );
}


function swalAttendance(e) {
    swal({
        title: GLOBAL_LANG.setting_integration_alert_delete_attendance_title,
        text: `${GLOBAL_LANG.setting_integration_alert_delete_attendance_text} (${e})`,
        type: "error",
        buttonsStyling: !1,
        confirmButtonClass: "btn btn-success"
    }).then(function () {
        location.reload();
    }
    );
}


function swalCampaign(e) {
    swal({
        title: GLOBAL_LANG.setting_integration_alert_delete_campaign_title,
        text: `${GLOBAL_LANG.setting_integration_alert_delete_campaign_text} (${e})`,
        type: "error",
        buttonsStyling: !1,
        confirmButtonClass: "btn btn-success"
    }).then(function () {
        location.reload();
    }
    );
}


function swalGeneralError(e) {
    swal({
        title: GLOBAL_LANG.setting_integration_openai_alert_error_title,
        text: `${GLOBAL_LANG.setting_integration_openai_alert_requisition_error} (${e})`,
        type: "error",
        buttonsStyling: !1,
        confirmButtonClass: "btn btn-success"
    }).then(function () {
        location.reload();
    }
    );
}

document.querySelectorAll(".clear").forEach(item => {
    item.addEventListener("click", () => clearChannel(item.id))
});
