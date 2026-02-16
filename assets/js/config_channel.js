"use strict";

const callAlerts = (alert) => {

    switch (alert) {
        case "pdf":
            break;
        case "maxSize":
            swal({
                title: GLOBAL_LANG.config_edit_channel_alert_dropzone_maxSize_title,
                text: GLOBAL_LANG.config_edit_channel_alert_dropzone_maxSize_text,
                type: "warning",
                confirmButtonColor: '#2dce89',
                confirmButtonText: GLOBAL_LANG.config_edit_channel_alert_dropzone_maxSize_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "jpg":
            break;
        case "formart":
            swal({
                title: GLOBAL_LANG.config_edit_channel_alert_dropzone_format_title,
                text: GLOBAL_LANG.config_edit_channel_alert_dropzone_format_text,
                type: "warning",
                confirmButtonColor: '#2dce89',
                confirmButtonText: GLOBAL_LANG.config_edit_channel_alert_dropzone_format_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "loading_img":
            swal({
                title: GLOBAL_LANG.config_edit_channel_alert_loading_img_title,
                text: GLOBAL_LANG.config_edit_channel_alert_loading_img_text,
                type: "warning",
                confirmButtonColor: '#2dce89',
                confirmButtonText: GLOBAL_LANG.config_edit_channel_alert_loading_img_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        default:
            break;
    }
}

const doTruncarStr = (str, size) => {
    if (str == undefined ||
        str == "undefined" ||
        str == "" ||
        size == undefined ||
        size == "undefined" ||
        size == "") {
        return str;
    }

    let shortText = str;

    if (str.length >= size + 3) {
        shortText = str.substring(0, size).concat("...");
    }

    return shortText;
}

const checkModal = (id) => {
    return document.getElementById(id).classList.contains('show');
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

const load = (field) => {

    if (field === "picture") {
        document.querySelector(".transition-effect img").src = `${document.location.origin}/assets/img/loads/loading_2.gif`;
        document.querySelector(".transition-effect img").classList.add("load");
    }

    if (field === "optin") {
        document.querySelector("#preview_opt_in img").src = `${document.location.origin}/assets/img/loads/loading_2.gif`;
        document.querySelector("#modal-opt-in .file-content .left").style.height = "auto";
        document.querySelector("#preview_opt_in img").classList.add("load");
    }
}

const thumbnail = (result, field) => {

    if (field == "picture") {
        document.querySelector(".transition-effect img").src = "data:image/jpeg;base64," + result.thumbnail;
        document.getElementById("input-picture").value = result.url;
        return;
    }

    if (field == "optin") {
        document.querySelector("#preview_opt_in img").src = "data:image/jpeg;base64," + result.thumbnail;
        document.getElementById("optInFile").value = result.url;
        document.querySelector("#preview_opt_in img").classList.remove("load");
        document.querySelector("#modal-opt-in .file-content .left").style.height = "280px";
        return;
    }
}

const clearInputFile = () => {
    document.getElementById("opt-in__file").value = "";
}

const handleFiles = async (e) => {

    if (e.files[0].type != "image/jpeg") {
        callAlerts("formart");
        clearInputFile();
        return
    }

    if (e.files[0].size > (5 * 1024 * 1024)) {
        callAlerts("maxSize");
        clearInputFile();
        return
    }

    load(e.dataset.file);
    thumbnail(await upload(e), e.dataset.file);
    clearInputFile();
}

const submit = () => {

    document.getElementById("alert_input_name").style.display = "none";
    document.getElementById("alert_textarea_description").style.display = "none";
    document.getElementById("alert_input_site").style.display = "none";
    document.getElementById("alert_input_email").style.display = "none";

    const input_name = document.getElementById("input-name").value;
    const textarea_descrip = document.getElementById("textarea-description").value;
    const input_email = document.getElementById("input-email").value;
    const input_site = document.getElementById("input-site").value;

    let formValid_name = true, formValid_description = true, formValid_email = true, formValid_site = true;

    // name
    if (!emptyText(input_name)) {
        formValid_name = false;
        document.getElementById("alert_input_name").style.display = "block";
        document.getElementById("alert_input_name").innerHTML = GLOBAL_LANG.config_edit_channel_validation_name;
        document.getElementById("input-name").focus();
        return;
    }

    if (!min_length(input_name, 3)) {
        formValid_name = false;
        document.getElementById("alert_input_name").style.display = "block";
        document.getElementById("alert_input_name").innerHTML = GLOBAL_LANG.config_edit_channel_validation_name_min_length.replace("{param}", 3);
        document.getElementById("input-name").focus();
        return;
    }

    if (!max_length(input_name, 30)) {
        formValid_name = false;
        document.getElementById("alert_input_name").style.display = "block";
        document.getElementById("alert_input_name").innerHTML = GLOBAL_LANG.config_edit_channel_validation_name_max_length.replace("{param}", 30);
        document.getElementById("input-name").focus();
        return;
    }

    // description
    if (!max_length(textarea_descrip, 500)) {
        formValid_description = false;
        document.getElementById("alert_textarea_description").style.display = "block";
        document.getElementById("alert_textarea_description").innerHTML = GLOBAL_LANG.config_edit_channel_validation_description_max_length.replace("{param}", 500);
        document.getElementById("textarea-description").focus();
        return;
    }

    // email
    if (!min_length(input_email, 3)) {
        formValid_email = false;
        document.getElementById("alert_input_email").style.display = "block";
        document.getElementById("alert_input_email").innerHTML = GLOBAL_LANG.config_edit_channel_validation_email_min_length.replace("{param}", 3);
        document.getElementById("input-email").focus();
        return;
    }

    if (!max_length(input_email, 150)) {
        formValid_email = false;
        document.getElementById("alert_input_email").style.display = "block";
        document.getElementById("alert_input_email").innerHTML = GLOBAL_LANG.config_edit_channel_validation_email_max_length.replace("{param}", 150);
        document.getElementById("input-email").focus();
        return;
    }

    // site
    if (!min_length(input_site, 3)) {
        formValid_site = false;
        document.getElementById("alert_input_site").style.display = "block";
        document.getElementById("alert_input_site").innerHTML = GLOBAL_LANG.config_edit_channel_validation_site_min_length.replace("{param}", 3);
        document.getElementById("input-site").focus();
        return;
    }

    if (!max_length(input_site, 200)) {
        formValid_site = false;
        document.getElementById("alert_input_site").style.display = "block";
        document.getElementById("alert_input_site").innerHTML = GLOBAL_LANG.config_edit_channel_validation_site_max_length.replace("{param}", 200);
        document.getElementById("input-site").focus();
        return;
    }

    if (!validModalWelcome()) return;
    if (!validModalPrivacyPilicy()) return;
    if (!validModalOptIn()) return;
    if (!validModalOptOut()) return;
    if (!validModalCloseMessage()) return;
    if (!validModalAutomaticMessage()) return;

    if (formValid_name && formValid_description && formValid_email && formValid_site) {
        $("form").unbind('submit').submit();
    }
}

const addOptionList = () => {

    const opt_list = document.querySelectorAll(".opt-list");
    const itens = document.querySelectorAll("#modal-list .modal-body .item");

    for (const elm of itens) elm.remove();

    for (const elm of opt_list) {

        if (elm.value == "") continue;

        const label = document.createElement("label");
        label.className = "item";

        const span = document.createElement("span");
        span.innerHTML = elm.value;

        const input = document.createElement("input");
        input.type = "radio";

        label.appendChild(span);
        label.appendChild(input);

        label.addEventListener("click", checkedList);

        document.querySelector("#modal-list .modal-body").appendChild(label);
    }
}

const recoverUrlProfile = () => {
    const url = document.querySelector(".box-picture-profile-config img").src;

    if (url != document.location.origin + "/assets/icons/panel/profile_default.svg")
        document.getElementById("input-picture").value = url;
}

const privacyPolicyTermCheck = () => {
    setTimeout(() => {
        if (parseInt(getComputedStyle(document.querySelector('.toggle__agree'), ':before').left.split("px")[0]) === 1)
            document.getElementsByName("input-term-agree").value = false;
        else
            document.getElementsByName("input-term-agree").value = true;

        if (parseInt(getComputedStyle(document.querySelector('.toggle__dont__agree'), ':before').left.split("px")[0]) === 1)
            document.getElementsByName("input-term-dont-agree").value = false;
        else
            document.getElementsByName("input-term-dont-agree").value = true;

    }, 300);
}

const recoverModalprivacyPolicy = () => {

    if (document.getElementById("term_agree").value != "") {
        document.querySelector(".toggle__agree").click();
        privacyPolicyTermCheck();
    }

    if (document.getElementById("term_dont_agree").value != "") {
        document.querySelector(".toggle__dont__agree").click();
        privacyPolicyTermCheck();
    }

    setTimeout(() => addReaction(), 500);
}

const addReaction = () => {

    const term_agree = document.getElementById("term_agree").value;
    const term_dont_agree = document.getElementById("term_dont_agree").value;

    function* buttons() {

        const check_term_agree = document.getElementsByName("input-term-agree").value;
        const check_term_dont_agree = document.getElementsByName("input-term-dont-agree").value;

        const agree = check_term_agree != false ? `<small class='reactions'>${term_agree}</small>` : "";
        const dont_agree = check_term_dont_agree != false ? `<small class='reactions'>${term_dont_agree}</small>` : "";

        yield agree;
        yield dont_agree;
    }

    const btnMsg = [...document.querySelectorAll(".privacy-policy .messageText .bottom .info .btn-msg span")];
    const btnMsgReaction = [...document.querySelectorAll(".privacy-policy .messageText .bottom .info .btn-msg small")];
    const btns = buttons();

    if (btnMsgReaction.length > 0) {
        btnMsgReaction.map((elm, idx) => elm.remove());
    }

    btnMsg.map((elm, idx) => {

        const val = elm.innerHTML;
        elm.innerHTML = val + btns.next().value;
    });

    for (const elm of [...document.querySelectorAll(".privacy-policy .messageText .bottom .info .btn-msg small")]) {
        if (elm.innerHTML == "") elm.remove();
    }
}

const validModalWelcome = () => {

    document.getElementById("alert_welcome-message__textarea-desc").style.display = "none";

    const welcomeMessage__textarea_desc = document.getElementById("welcome-message__textarea-desc").value;

    let valid_welcomeMessage = true;

    if (!emptyText(welcomeMessage__textarea_desc)) {
        valid_welcomeMessage = false;
        document.getElementById("alert_welcome-message__textarea-desc").style.display = "block";
        document.getElementById("alert_welcome-message__textarea-desc").innerHTML = GLOBAL_LANG.config_edit_channel_welcome_message_modal_validation_description;
        document.getElementById("welcome-message__textarea-desc").focus();

        if (!checkModal("modal-welcome-message")) $("#modal-welcome-message").modal('show');
        return false;
    }

    if (valid_welcomeMessage) {

        document.querySelector(".welcome-message .messageText .tex span").innerHTML = welcomeMessage__textarea_desc;

        $("#modal-welcome-message").modal('hide');
        return true;
    }
}

const validModalPrivacyPilicy = () => {

    const privacy_policy__info = document.getElementById("privacy-policy__info").value;
    const privacy_policy__link = document.getElementById("privacy-policy__link").value;

    const term_agree = document.getElementById("term_agree").value;
    const term_dont_agree = document.getElementById("term_dont_agree").value;

    if (document.getElementsByName("input-term-agree").value) {
        if (!emptyText(term_agree)) {
            document.getElementById("alert_emoji_term_dont").style.display = "block";
            document.getElementById("alert_emoji_term_dont").innerHTML = GLOBAL_LANG.config_edit_channel_privacy_policy_modal_validation_emoji;

            if (!checkModal("modal-privacy-policy")) $("#modal-privacy-policy").modal('show');
            return false;
        }
    }

    if (document.getElementsByName("input-term-dont-agree").value) {
        if (!emptyText(term_dont_agree)) {
            document.getElementById("alert_emoji_term_dont").style.display = "block";
            document.getElementById("alert_emoji_term_dont").innerHTML = GLOBAL_LANG.config_edit_channel_privacy_policy_modal_validation_emoji;

            if (!checkModal("modal-privacy-policy")) $("#modal-privacy-policy").modal('show');
            return false;
        }
    }

    function* data() {
        yield privacy_policy__info;
        yield privacy_policy__link;
    }

    const res = data();
    const messageText = [...document.querySelectorAll(".privacy-policy .messageText .body .tex span")];
    const messageLink = document.querySelector(".privacy-policy .messageText .body .link a");

    messageText.map((elm, idx) => elm.innerHTML = doTruncarStr(res.next().value, 100));
    messageLink.href = res.next().value;

    addReaction();

    $("#modal-privacy-policy").modal('hide');
    return true;

}

const validModalOptIn = () => {

    document.getElementById("alert_opt-in__invitation").style.display = "none";

    const optin__invitation = document.getElementById("opt-in__invitation").value;
    const optin__textarea_desc = document.getElementById("opt-in__textarea-desc").value;
    const url = document.getElementById("preview_opt_in").children[0].src;

    let formValid_invitation = true;

    if (!emptyText(optin__invitation)) {
        formValid_invitation = false;
        document.getElementById("alert_opt-in__invitation").style.display = "block";
        document.getElementById("alert_opt-in__invitation").innerHTML = GLOBAL_LANG.config_edit_channel_opt_in_modal_validation_customer_invitation;
        document.getElementById("opt-in__invitation").focus();

        if (!checkModal("modal-opt-in")) $("#modal-opt-in").modal('show');
        return false;
    }

    if (formValid_invitation) {

        document.querySelector(".opt-in .messageText .invitation span").innerHTML = optin__invitation;
        document.querySelector(".opt-in .messageText .options span").innerHTML = optin__textarea_desc;
        document.querySelector(".opt-in .messageImage img").src = url;

        const urlDefault = `${document.location.origin}/assets/img/panel/background_no_picture.jpg`;

        if (url === urlDefault) {
            document.querySelector(".opt-in .left .messageImage").style.display = "none";
        } else {
            document.querySelector(".opt-in .left .messageImage").style.display = "block";
        }

        $("#modal-opt-in").modal('hide');
        return true;
    }
}

const validModalOptOut = () => {

    document.getElementById("alert_opt-out__leave_opt_out").style.display = "none";
    document.getElementById("alert_opt-out__keyword").style.display = "none";

    const question_reason = document.getElementById("opt-out__question_reason").value;
    const optout_leave = document.getElementById("opt-out__leave_opt_out").value;
    const optout_keyword = document.getElementById("opt-out__keyword").value;

    let formValid_optout = true, formValid_optout_keyword = true;

    if (!emptyText(optout_leave)) {
        formValid_optout = false;
        document.getElementById("alert_opt-out__leave_opt_out").style.display = "block";
        document.getElementById("alert_opt-out__leave_opt_out").innerHTML = GLOBAL_LANG.config_edit_channel_opt_out_modal_validation_optout_leave;
        document.getElementById("opt-out__leave_opt_out").focus();

        if (!checkModal("modal-opt-out")) $("#modal-opt-out").modal('show');
        return false;
    }

    if (!emptyText(optout_keyword)) {
        formValid_optout_keyword = false;
        document.getElementById("alert_opt-out__keyword").style.display = "block";
        document.getElementById("alert_opt-out__keyword").innerHTML = GLOBAL_LANG.config_edit_channel_opt_out_modal_validation_optout_keyword;
        document.getElementById("opt-out__keyword").focus();

        if (!checkModal("modal-opt-out")) $("#modal-opt-out").modal('show');
        return false;
    }

    if (formValid_optout && formValid_optout_keyword) {

        document.querySelector(".opt-out .messageText .tex span").innerHTML = question_reason;
        addOptionList();

        $("#modal-opt-out").modal('hide');
        return true;
    }
}

const validModalCloseMessage = () => {

    document.getElementById("alert_closed-message__call_closed").style.display = "none";

    const message__call_closed = document.getElementById("closed-message__call_closed").value;
    const message__contact_return = document.getElementById("closed-message__contact_return").value;

    let formValid_call_closed = true;

    if (!emptyText(message__call_closed)) {
        formValid_call_closed = false;
        document.getElementById("alert_closed-message__call_closed").style.display = "block";
        document.getElementById("alert_closed-message__call_closed").innerHTML = GLOBAL_LANG.config_edit_channel_closed_message_modal_validation_call_closed;
        document.getElementById("closed-message__call_closed").focus();

        if (!checkModal("modal-closed-message")) $("#modal-closed-message").modal('show');
        return false;
    }

    if (formValid_call_closed) {

        document.querySelector(".closed-message .messageText .call-closed span").innerHTML = message__call_closed;
        document.querySelector(".closed-message .messageText .contact-return span").innerHTML = message__contact_return;

        $("#modal-closed-message").modal('hide');
        return true;
    }
}

const validModalAutomaticMessage = () => {

    document.getElementById("alert_automatic-messages__info_attendance").style.display = "none";

    const messages__info_attendance = document.getElementById("automatic-messages__info_attendance").value;
    const messages__about_attendance = document.getElementById("automatic-messages__about_attendance").value;
    const messages__phone_attendance = document.getElementById("automatic-messages__phone_attendance").value;

    let formValid_info_attendance = true;

    if (!emptyText(messages__info_attendance)) {
        formValid_info_attendance = false;
        document.getElementById("alert_automatic-messages__info_attendance").style.display = "block";
        document.getElementById("alert_automatic-messages__info_attendance").innerHTML = GLOBAL_LANG.config_edit_channel_automatic_messages_modal_validation_info_attendance;
        document.getElementById("automatic-messages__info_attendance").focus();

        if (!checkModal("modal-automatic-messages")) $("#modal-automatic-messages").modal('show');
        return false;
    }

    if (formValid_info_attendance) {

        document.querySelector(".automatic-messages .messageText .info-attendance span").innerHTML = messages__info_attendance;
        document.querySelector(".automatic-messages .messageText .about-attendance span").innerHTML = messages__about_attendance;
        document.querySelector(".automatic-messages .messageText .phone-attendance a").href = `https://wa.me/${messages__phone_attendance}`;

        $("#modal-automatic-messages").modal('hide');
        return true;
    }
}

function checkedList() {
    for (const elm of document.querySelectorAll("#modal-list .item")) {
        elm.children[1].checked = false;
    }

    this.children[1].checked = true;
}

document.querySelector(".btn-success").addEventListener("click", submit, false);

document.getElementsByClassName("transition-effect")[0].addEventListener("mouseover", function () {
    document.getElementById("addProfile").style.display = "block";
    document.getElementsByClassName("picture-profile-title")[0].style.display = "block";
    document.querySelector(".transition-effect img").style.opacity = "0.8";
    document.querySelector(".transition-effect img").style.filter = "brightness(50%)";
});

document.getElementsByClassName("transition-effect")[0].addEventListener("mouseenter", function () {
    document.getElementById("addProfile").style.display = "block";
    document.getElementsByClassName("picture-profile-title")[0].style.display = "block";
    document.querySelector(".transition-effect img").style.opacity = "0.8";
    document.querySelector(".transition-effect img").style.filter = "brightness(50%)";
});

document.getElementsByClassName("transition-effect")[0].addEventListener("mouseout", function () {
    document.getElementById("addProfile").style.display = "none";
    document.getElementsByClassName("picture-profile-title")[0].style.display = "none";
    document.querySelector(".transition-effect img").style.opacity = "1";
    document.querySelector(".transition-effect img").style.filter = "brightness(100%)";
});

document.querySelector(".box-picture-profile-config .transition-effect").addEventListener("click", function () {
    document.getElementById("inputFile").click();
});

document.getElementById("input-phone").addEventListener("onkeyup", function () {
    mascara(this, mtel);
});

document.querySelector("form").addEventListener("submit", function (event) {
    event.preventDefault();
}, true);

$(".textarea-emoji").emojioneArea({
    standalone: true,
    search: false,
    autocomplete: false,
    tonesStyle: 'checkbox',
    tones: false,
    filters: {
        recent: false,
        food_drink: false,
        objects: false,
        symbols: false,
        flags: false,
        smileys_people: {
            icon: "tes",
            title: "Emoji",
            emoji: "heart thumbsup clap heart_eyes wink blush star_struck handshake white_check_mark eyes pray slight_smile joy cry disappointed_relieved sob thumbsdown thinking rage x",
        },
        activity: false,
        travel_places: false,
        animals_nature: false
    }
});

document.querySelector(".optTermAgree").addEventListener("click", function () {
    const input_term_agree = document.getElementsByName("input-term-agree").value;

    if (!input_term_agree) {
        document.querySelectorAll(".emojionearea-picker")[0].classList.add("hidden");
    }
});

document.querySelector(".optTermDontAgree").addEventListener("click", function () {
    const input_dont_term_agree = document.getElementsByName("input-term-dont-agree").value;

    if (!input_dont_term_agree) {
        document.querySelectorAll(".emojionearea-picker")[1].classList.add("hidden");
    }
});

document.querySelectorAll(".reactions-term .toggle").forEach(elm => elm.addEventListener("click", function () {
    privacyPolicyTermCheck();
}));

document.getElementById("toggle-privacy-policy").addEventListener("change", function () {
    if ($("#toggle-privacy-policy").is(":checked")) {
        document.querySelector(".privacy-policy-description").style.display = "block";
        document.querySelector(".privacy-policy [data-target]").style.display = "block";
        document.querySelector(".privacy-policy .left").style.display = "flex";
    } else {
        document.querySelector(".privacy-policy [data-target]").style.display = "none";
        document.querySelector(".privacy-policy-description").style.display = "none";
        document.querySelector(".privacy-policy .left").style.display = "none";
    }
});

document.querySelector("#modal-privacy-policy .modal-content").addEventListener("click", function (e) {
    if (e.target.classList[0] != "emojionearea-button") {
        [...document.querySelectorAll(".emojionearea-picker")].map((elm, idx) => elm.classList.add("hidden"));
    }
});

document.querySelector("#modal-opt-in .file-content .btn-add").addEventListener("click", function () {
    document.getElementById("opt-in__file").click();
});

document.querySelector("#modal-opt-in .file-content .btn-remove").addEventListener("click", function () {
    if (document.querySelector("#preview_opt_in img").classList.contains('load')) {
        callAlerts("loading_img");
    } else {
        document.getElementById("preview_opt_in").children[0].src = `${document.location.origin}/assets/img/panel/background_no_picture.jpg`;
        document.getElementById("optInFile").value = "";
    }
});

document.querySelector("#modal-opt-in .file-content .btn-remove").addEventListener("click", function () {
    if (document.querySelector("#preview_opt_in img").classList.contains('load')) {
        callAlerts("loading_img");
    } else {
        document.getElementById("preview_opt_in").children[0].src = `${document.location.origin}/assets/img/panel/background_no_picture.jpg`;
    }
});

function checkPrivacypolicy() {

    const privacy_policy__info = document.getElementById("privacy-policy__info").value;
    const privacy_policy__link = document.getElementById("privacy-policy__link").value;

    if (privacy_policy__info != "" || privacy_policy__link != "") {
        if (!$("#toggle-privacy-policy").is(":checked")) {
            document.getElementById("toggle-privacy-policy").click();
        }
    } else {
        document.querySelector(".privacy-policy [data-target]").style.display = "none";
        document.querySelector(".privacy-policy-description").style.display = "none";
        document.querySelector(".privacy-policy .left").style.display = "none";
    }
}

document.getElementById("btn-welcome-message").addEventListener("click", validModalWelcome);
document.getElementById("btn-privacy-policy").addEventListener("click", validModalPrivacyPilicy);
document.getElementById("btn-opt-in").addEventListener("click", validModalOptIn);
document.getElementById("btn-opt-out").addEventListener("click", validModalOptOut);
document.getElementById("btn-closed-message").addEventListener("click", validModalCloseMessage);
document.getElementById("btn-automatic-messages").addEventListener("click", validModalAutomaticMessage);
document.querySelectorAll("#modal-list .item").forEach(elm => elm.addEventListener("click", checkedList));

addOptionList();
recoverUrlProfile();
recoverModalprivacyPolicy();
checkPrivacypolicy();