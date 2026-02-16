const toggleElements = () => {
    document.getElementById("input-token").classList.toggle("hidden");
    document.getElementById("btn-connect").classList.toggle("hidden");

    document.getElementById("btn-save").classList.toggle("hidden");
    document.querySelector(".btn-danger").classList.toggle("hidden");
    document.querySelector(".gpt-version").classList.toggle("hidden");
}


const hideAlert = () => {
    const div_alert = document.getElementById("alert__input-version");
    const alert_display = window.getComputedStyle(div_alert).getPropertyValue("display");

    if (alert_display == "block") {
        div_alert.innerHTML = "";
        div_alert.style.display = "none";
    }
}


const showError = (error) => {

    let text = GLOBAL_LANG.setting_integration_openai_general_error;

    const errorMappings = {
        "Incorrect API key provided": `${GLOBAL_LANG.setting_integration_openai_alert_incorrect_api_key} <br> ${GLOBAL_LANG.setting_integration_openai_alert_incorrect_api_key_info} <a href='https://platform.openai.com/account/api-keys' target='_blank'>${GLOBAL_LANG.setting_integration_openai_alert_learn_more}</a>`,
        "Invalid Authentication": `${GLOBAL_LANG.setting_integration_openai_alert_invalid_authentication} <a href='https://platform.openai.com/account/api-keys' target='_blank'>${GLOBAL_LANG.setting_integration_openai_alert_learn_more}</a>`,
        "You must be a member of an organization to use the API": `${GLOBAL_LANG.setting_integration_openai_alert_account_organization} <a href='https://platform.openai.com/account/team' target='_blank'>${GLOBAL_LANG.setting_integration_openai_alert_learn_more}</a>`,
        "Country, region, or territory not supported": `${GLOBAL_LANG.setting_integration_openai_alert_region_not_supported} <a href='https://platform.openai.com/docs/supported-countries' target='_blank'>${GLOBAL_LANG.setting_integration_openai_alert_learn_more}</a>`,
        "Rate limit reached for requests": `${GLOBAL_LANG.setting_integration_openai_alert_limit_reachaed} <a href='https://platform.openai.com/docs/guides/rate-limits' target='_blank'>${GLOBAL_LANG.setting_integration_openai_alert_learn_more}</a>`,
        "You exceeded your current quota, please check your plan and billing details": `${GLOBAL_LANG.setting_integration_openai_alert_exceeded_quota} <a href='https://platform.openai.com/account/limits' target='_blank'>${GLOBAL_LANG.setting_integration_openai_alert_learn_more}</a>`,
        "The server had an error while processing your request": `${GLOBAL_LANG.setting_integration_openai_alert_server_error} <a href='https://status.openai.com/' target='_blank'>${GLOBAL_LANG.setting_integration_openai_alert_learn_more}</a>`,
        "The engine is currently overloaded, please try again later": GLOBAL_LANG.setting_integration_openai_alert_server_high_traffic
    };

    for (const [key, value] of Object.entries(errorMappings)) {
        if (error.message.includes(key)) {
            text = value;
            break;
        }
    }

    swal({
        title: GLOBAL_LANG.setting_integration_openai_alert_title,
        html: text,
        type: "warning",
        buttonsStyling: false,
        confirmButtonClass: "btn btn-danger"
    });
}


const save = () => {
    const form = new FormData();
    form.append("token", document.getElementById("input-token").value.trim());
    form.append("version", document.getElementById("id-version").value.trim());
    form.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

    return new Promise(async (resolve, reject) => {
        try {

            const response = await fetch(`${document.location.origin}/integration/save/openai`, { method: 'POST', body: form });

            if (response.ok) {

                const data = await response.json();

                if (data.success) {

                    window.location.href = window.location.origin + "/integration";

                } else {

                    if (data.errors?.code === "PAD-002") {

                        swal({
                            title: GLOBAL_LANG.setting_integration_openai_alert_error_title,
                            text: `${GLOBAL_LANG.setting_integration_openai_alert_requisition_error} (${data.errors.code})`,
                            type: "error",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-danger"
                        });

                    } else {
                        console.log("Error", data.errors.code)
                    }
                }

                resolve(data);
            }

        } catch (error) {
            console.log('Erro na requisição:', error);
            reject(error);
        }
    })
}


const validateField = () => {
    const selected_version = document.getElementById("input-version");
    const input_version = formValidation({ field: selected_version, text: GLOBAL_LANG.setting_integration_openai_alert_version_empty, required: true });

    if (!input_version) return

    const version_list = document.querySelectorAll(".version-list li");
    const version_match = Array.from(version_list).some(item => item.innerText === selected_version.value.trim());

    if (version_match) {

        save();

    } else {
        document.getElementById("alert__input-version").innerHTML = GLOBAL_LANG.setting_integration_openai_alert_version_wrong;
        document.getElementById("alert__input-version").style.display = "block";
    }
}


const cancel = () => {
    document.getElementById("btn-connect").addEventListener("click", getGptVersion);
    document.getElementById("input-version").value = "";
    document.getElementById("id-version").value = "";
    document.getElementById("provide-info").innerHTML = GLOBAL_LANG.setting_integration_openai_insert_token;

    document.querySelectorAll(".version-list li").forEach(item => {
        item.remove();
    })

    toggleElements();
}


const selectVersion = (data) => {
    document.getElementById("input-version").value = data.innerText;
    document.getElementById("id-version").value = data.innerText;

    document.querySelectorAll(".version-list li").forEach(item => {
        item.style.display = "none";
    })
}


const filterVersion = (event) => {
    const option = event.target.value.toLowerCase();
    const list = document.querySelectorAll(".version-list li");

    list.forEach(item => {
        const version_id = item.textContent.toLowerCase();
        if (version_id.startsWith(option)) {
            item.style.display = "block";
        } else {
            item.style.display = "none";
        }
    });
}


const createVersionsList = (version) => {
    const list = document.querySelector(".version-list");
    document.getElementById("provide-info").innerHTML = GLOBAL_LANG.setting_integration_openai_select_version;
    const version_to_keep = ["gpt-4", "gpt-3.5-turbo", "gpt-3.5-turbo-instruct", "babbage-002", "davinci-002"];

    const filteredVersions = version.filter(model => {
        return model.id.startsWith("gpt-4") || version_to_keep.includes(model.id);
    });

    filteredVersions.forEach(model => {
        const li = document.createElement("li");
        li.style.cursor = "pointer";
        li.style.width = "88%";

        const span = document.createElement("span");
        span.textContent = model.id;

        li.appendChild(span);
        list.appendChild(li);
        li.addEventListener("click", () => selectVersion(li));
    });

    toggleElements();
}



const getGptVersion = () => {

    document.getElementById("btn-connect").removeEventListener("click", getGptVersion);
    const input_token = formValidation({ field: document.getElementById("input-token"), text: "token", required: true });

    if (!input_token) {
        document.getElementById("btn-connect").addEventListener("click", getGptVersion);
        return
    }

    const form = new FormData();
    form.append("token", document.getElementById("input-token").value.trim());
    form.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

    return new Promise(async (resolve, reject) => {
        try {

            const result = await fetch(`${document.location.origin}/integration/openai/version`, { method: 'POST', body: form });

            if (result.ok) {

                const res = await result.json();


                if (JSON.parse(res).error) {
                    showError(JSON.parse(res).error);
                    document.getElementById("btn-connect").addEventListener("click", getGptVersion);
                } else {
                    const version = JSON.parse(res);
                    const list = version.data;
                    createVersionsList(list)
                }

                resolve(res);
            }

        } catch (error) {
            document.getElementById("btn-connect").addEventListener("click", getGptVersion);
            console.log('Erro na requisição:', error);
            reject(error);
        }
    })
}

document.getElementById("btn-connect").addEventListener("click", getGptVersion);
document.querySelector(".btn.btn-danger").addEventListener("click", cancel);
document.getElementById("btn-save").addEventListener("click", validateField);
document.getElementById("input-version")?.addEventListener("input", filterVersion);
document.getElementById("input-version")?.addEventListener("focus", () => hideAlert());
document.getElementById("input-version")?.addEventListener("click", () => document.querySelector(".version-list").style.display = "block");

document.addEventListener("click", (event) => {
    const input = document.getElementById("input-version");
    const list = document.querySelector(".version-list");
    if (!input.contains(event.target) && !list.contains(event.target)) {
        list.style.display = "none";
    }
});
