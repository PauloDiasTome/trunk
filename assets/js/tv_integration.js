const Components = new ComponentsDom();
const Filters = JSON.parse(localStorage.getItem("filters")) || null;
let imgUrl = null;

const callAlerts = (alert) => {

    switch (alert) {
        case "pdf":
            break;
        case "maxSize":
            swal({
                title: GLOBAL_LANG.setting_integration_add_tv_alert_dropzone_maxSize_title,
                text: GLOBAL_LANG.setting_integration_add_tv_alert_dropzone_maxSize_text,
                type: "warning",
                confirmButtonColor: "#2dce89",
                confirmButtonText: GLOBAL_LANG.setting_integration_add_tv_alert_dropzone_maxSize_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "jpg":
            break;
        case "formart":
            swal({
                title: GLOBAL_LANG.setting_integration_add_tv_alert_dropzone_format_title,
                text: GLOBAL_LANG.setting_integration_add_tv_alert_dropzone_format_text,
                type: "warning",
                confirmButtonColor: "#2dce89",
                confirmButtonText: GLOBAL_LANG.setting_integration_add_tv_alert_dropzone_format_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "loading_img":
            swal({
                title: GLOBAL_LANG.setting_integration_add_tv_alert_loading_img_title,
                text: GLOBAL_LANG.setting_integration_add_tv_alert_loading_img_text,
                type: "warning",
                confirmButtonColor: "#2dce89",
                confirmButtonText: GLOBAL_LANG.setting_integration_add_tv_alert_loading_img_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        default:
            break;
    }
}


const upload = (e) => {
    return new Promise((resolve, reject) => {

        const formData = new FormData();
        const type = e.files[0].type;

        formData.append("filetoupload", e.files[0]);
        formData.append("media_id", Math.floor(Math.random() * 100000));
        formData.append("media_mime_type", type);

        const settings = {
            "url": "https://files.talkall.com.br:3000",
            "method": "POST",
            "timeout": 0,
            "crossDomain": true,
            "processData": false,
            "mimeType": "multipart/form-data",
            "contentType": false,
            "data": formData
        };

        $.ajax(settings).done(function (res) {
            resolve(JSON.parse(res));
        });
    });
}


const load = () => {
    document.querySelector(".box-img").classList.add("load-align");
    document.querySelector(".box-img img").src = `${document.location.origin}/assets/img/loads/loading_2.gif`;
    document.querySelector(".box-img img").classList.add("load");
}


const thumbnail = (result) => {

    imgUrl = result.url;
    document.querySelector(".box-img img").classList.remove("load");
    document.querySelector(".box-img").classList.remove("load-align");
    document.querySelector(".box-img img").src = "data:image/jpeg;base64," + result.thumbnail;
    document.getElementById("input-picture").value = imgUrl;
    return;
}


const handleFiles = async (e) => {

    if (e.files[0].type != "image/jpeg" && e.files[0].type != "image/png") {
        callAlerts("formart");
        return
    }

    if (e.files[0].size > (10 * 1024 * 1024)) {
        callAlerts("maxSize");
        return
    }

    load();
    thumbnail(await upload(e));
}


function changePageContent(data) {

    document.getElementById("connection-code").value = data;
    document.querySelectorAll(".step-progress-item")[0].classList.remove("current-item");
    document.querySelectorAll(".step-progress-item")[1].classList.add("current-item");
    document.querySelector(".registration").style.display = "none";
    document.querySelector(".conclude").classList.remove("hidden");

    const code_container = document.querySelector(".code-container");
    const box_img = document.querySelector(".img");
    const box_input = Components.div({ class: "box-input" });

    for (let i = 0; i < data.length; i++) {
        const input = Components.input();
        input.type = "text";
        input.maxLength = 1;
        input.disabled = true;
        input.value = data.charAt(i);
        box_input.appendChild(input);
    }

    document.getElementById("code-container").appendChild(box_input);

    box_img.appendChild(Components.img({ style: "width:100%;", src: document.location.origin + "/assets/img/tv_login.png" }));
    code_container.appendChild(box_input.cloneNode(true));
}


function conclude(page) {

    if (page === "add") {
        document.querySelectorAll(".step-progress-item")[1].classList.remove("current-item");
        document.querySelectorAll(".step-progress-item")[2].classList.add("current-item");
    }

    if (page === "edit") {
        document.querySelectorAll(".step-progress-item")[0].classList.remove("current-item");
        document.querySelectorAll(".step-progress-item")[1].classList.add("current-item");

        setTimeout(() => {
            document.querySelectorAll(".step-progress-item")[1].classList.remove("current-item");
            document.querySelectorAll(".step-progress-item")[2].classList.add("current-item");
        }, 400);
    }

    window.location.href = window.location.origin + "/integration";
}


async function save(formData) {
    return new Promise(async (resolve, reject) => {
        try {
            const response = await fetch(document.location.origin + "/integration/save/tv/", {
                method: "POST",
                body: formData
            });

            if (response.ok) {
                const data = await response.json();
                resolve(data);
            }

        } catch (error) {
            console.error("Erro ao enviar a solicitação:", error);
            reject(error);
        }
    });
}

function cancelTvIntegration() {
    return new Promise(async (resolve, reject) => {
        try {
            const formData = new FormData();
            formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));
            formData.append("connection_code", document.getElementById("connection-code").value);

            const response = await fetch(document.location.origin + "/integration/cancel/tv", {
                method: "POST",
                body: formData
            });

            if (response.ok) {
                window.location.href = window.location.origin + "/integration";
            }

        } catch (error) {
            console.log("error: ", error);
            reject(error);
        }
    });
}


async function submit(page) {

    const formData = new FormData();
    const position = [...document.querySelectorAll(".position")].filter(elm => elm.checked);
    const url = document.getElementById("input-picture").value == "" ? "https://files.talkall.com.br:3000/v/2024128/59081.png" : document.getElementById("input-picture").value;
    const tv_settings = { url: url, position: position[0].value };

    formData.append("tv_settings", JSON.stringify(tv_settings));
    formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));
    formData.append("id_channel", document.getElementById("id-channel") ? document.getElementById("id-channel").value : 0);
    formData.append("name", document.getElementById("input-name").value.trim());

    if (page === "add") {
        formData.append("connection_code", Math.floor(100000 + Math.random() * 900000));

        const res = await save(formData);
        changePageContent(res);
    }

    if (page === "edit") {

        await save(formData);
        conclude("edit");
    }
}


function validateFields(page) {
    const input_name = formValidation({ field: document.getElementById("input-name"), text: GLOBAL_LANG.setting_integration_tv_label_name, required: true, min: 3, max: 55 });

    if (input_name) {
        submit(page);
    }
}

document.getElementById("btn_save_tv")?.addEventListener("click", function () {
    validateFields("add");
})

document.getElementById("btn_conclude")?.addEventListener("click", function () {
    conclude("add");
})
document.getElementById("btn_cancel")?.addEventListener("click", function () {
    cancelTvIntegration()
})

document.getElementById("btn_edit_tv")?.addEventListener("click", function () {
    validateFields("edit");
})

document.getElementsByClassName("box-img")[0].addEventListener("mouseover", function () {
    document.getElementById("addProfile").style.display = "block";
    document.getElementsByClassName("picture-profile-title")[0].style.display = "block";
});

document.getElementsByClassName("box-img")[0].addEventListener("mouseenter", function () {
    document.getElementById("addProfile").style.display = "block";
    document.getElementsByClassName("picture-profile-title")[0].style.display = "block";
});

document.getElementsByClassName("box-img")[0].addEventListener("mouseout", function () {
    document.getElementById("addProfile").style.display = "none";
    document.getElementsByClassName("picture-profile-title")[0].style.display = "none";

});

document.querySelector(".box-picture-default .box-img").addEventListener("click", function (event) {
    if (!event.target.matches("#inputFile")) {
        document.getElementById("inputFile").click();
    }
});

document.querySelector(".btn.btn-primary").addEventListener("click", function () {
    if (Filters) {
        Filters.btn_back = true;
        localStorage.setItem("filters", JSON.stringify(Filters));
    }
});