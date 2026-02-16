let tooltip_top__ai = 0;
let tooltip_left__ai = 0;
let text_send__ai = "";
let target_mouse_down__ai = "";
let max_length__ai = undefined;
let body_height__ai = document.body.clientHeight;
let controller__ai = null;
let reference_elm__ai = null;

function removeAI() {

    $(".tooltip-ai").remove();
    $(".bg-box-ia").remove();
    $(".container-open-ai").remove();
    $(".list-ai").remove();
    target_mouse_down__ai = "";

    document.body.style.height = body_height__ai + "px";
}


function writeAnswerAI(data) {

    document.querySelector(".input-ai").classList.add("border-change-ai");

    const content_body = document.querySelector(".content-body-ai");
    content_body.style.display = "block";

    const content_footer = document.querySelector(".content-footer-ai");

    const field_response_ai = document.querySelector(".field-response-ai");
    field_response_ai.style.display = "block";
    field_response_ai.value = "";

    var text = data.replace('\n', '').replace('\n', '');
    var text_returned = text.split(" ");
    var currentWordIndex = 0;

    var interval = setInterval(function () {

        if (currentWordIndex < text_returned.length) {

            field_response_ai.value += text_returned[currentWordIndex] + ' ';
            currentWordIndex++;

            var event = new Event('input', { bubbles: true });
            field_response_ai.dispatchEvent(event);

            field_response_ai.onchange = field_response_ai.oninput = function () {
                $(this).height(0).height(this.scrollHeight);
                this.scrollTop = this.scrollHeight;
            };

        } else {

            clearInterval(interval);

            if (field_response_ai !== null && (document.querySelector(".writing-response-ai") !== null)) {
                document.querySelector(".writing-response-ai").style.display = "none";
                document.querySelector(".writing-response-ai").classList.remove("active");
                document.querySelector(".input-ai").removeAttribute("readonly");
                document.querySelector(".input-ai").value = text_send__ai;
                document.querySelector(".icon-close-ai").style.display = "block";
                document.querySelector(".bg-box-ia") ? document.querySelector(".bg-box-ia").style.display = "none" : "";

                field_response_ai.classList.remove("border-ai");
                field_response_ai.classList.add("border-none-ai");
                content_footer.style.display = "block";
                content_footer.style.top = field_response_ai.clientHeight + 36 + "px";

                document.querySelector(".text-replace-ai").onclick = function () {

                    if (max_length__ai != undefined && max_length__ai >= 1 && field_response_ai.value.length > max_length__ai) {

                        reference_elm__ai.value = field_response_ai.value.slice(0, max_length__ai);
                    } else {
                        reference_elm__ai.value = field_response_ai.value;
                    }

                    removeAI();
                }

                document.querySelector(".open-options-ai").onclick = function () {
                    const list_ai = document.querySelector(".list-ai");
                    list_ai.style.display = "block";
                    list_ai.style.zIndex = 99999;
                    list_ai.style.top = field_response_ai.clientHeight + $(".field-response-ai").offset().top - 33 + "px";

                    content_footer.style.display = "none";

                    field_response_ai.classList.remove("border-none-ai");
                    field_response_ai.classList.add("border-ai");

                    checkBottomToScrollAI(document.querySelector(".input-ai").value)
                }
            }

        }
    }, 150);
}


function showErrorOpenAI() {
    document.querySelector('.writing-response-ai').style.display = "none";
    document.querySelector('.writing-response-ai').classList.remove("active");

    const input_ai = document.querySelector(".input-ai");
    input_ai.classList.add("alert-error-ai");
    input_ai.value = GLOBAL_LANG.ai_request_error;
    input_ai.classList.contains("border-change-ai") == true ? input_ai.classList.remove("border-change-ai") : "";

    document.querySelector(".icon-close-ai").style.display = "block";
    document.querySelector(".content-body-ai").style.display = "none";
    document.addEventListener("keydown", (event) => { event.key === "Escape" ? removeAI() : "" });
}


async function fetchOpenAI(option, text) {

    return new Promise(async (resolve, reject) => {
        try {
            const myHeaders = new Headers();
            myHeaders.append("Content-Type", "application/json");

            const requestOptions = {
                method: 'POST',
                headers: myHeaders,
                body: JSON.stringify({ "text": `${option}: {${text}}` }),
                redirect: 'follow'
            };

            controller__ai = new AbortController();
            const signal = controller__ai.signal;
            requestOptions.signal = signal;

            const key = localStorage.getItem("userToken");

            const response = await fetch("https://services.talkall.com.br:4090/openai?key=" + key, requestOptions);

            const result = await response.json();

            controller__ai = null;

            if (result.erro) {
                showErrorOpenAI();
                console.log('error: ', result.reason)
                reject(new Error('Erro na resposta: ', result.reason));

                return;
            }

            resolve(result);

        } catch (error) {
            if (error.message === "The user aborted a request.") {
                console.log("A requisição foi cancelada.");
            } else {
                console.log(error);
                showErrorOpenAI();
                reject(error);
            }
        }
    });
}


async function chooseOpitionAI() {

    const input_ai = document.querySelector(".input-ai");
    text_send__ai = input_ai.value;
    input_ai.value = "";
    input_ai.classList.remove("border-change-ai");

    const box_writing = document.querySelector(".writing-response-ai");
    box_writing.style.display = "block";
    box_writing.classList.add("active");

    document.querySelector(".field-response-ai").style.display = "none";
    document.querySelector(".icon-close-ai").style.display = "none";
    document.querySelector(".list-ai").style.display = "none";
    document.querySelector(".bg-box-ia") ? document.querySelector(".bg-box-ia").style.display = "none" : "";

    input_ai.setAttribute("readonly", true);

    let chosen_option = this.innerText;

    if (this.dataset.item === "change_tone") {
        chosen_option = GLOBAL_LANG.ai_text_send_change_tone;
    }

    const result = await fetchOpenAI(chosen_option, text_send__ai);

    writeAnswerAI(result.new);
}


function changeBodyAI(data) {

    let height_to_increment = 0;

    while (document.documentElement.scrollHeight > document.body.offsetHeight) {
        document.body.style.height = document.body.clientHeight + height_to_increment++ + "px";
    }

    document.documentElement.scrollTo({ top: document.documentElement.scrollHeight, behavior: 'smooth' });

    $(".list-ai").remove();

    createOptionsAI(data);
}


function checkBottomToScrollAI(data) {

    if (document.querySelector(".list-ai").getBoundingClientRect().bottom >= window.innerHeight - 30) {

        document.querySelector(".input-ai").scrollIntoView({ behavior: 'smooth', block: 'center' });

        if (window.innerHeight + Math.round(window.scrollY) >= document.body.offsetHeight) {
            changeBodyAI(data);
        }
    }
}


function createOptionsAI(data) {

    $(".tooltip-ai").remove();
    $(".input-ai").remove();
    $(".container-open-ai").remove();

    reference_elm__ai = target_mouse_down__ai.target;
    text_send__ai = data;
    let top = target_mouse_down__ai.target.clientHeight + $(target_mouse_down__ai.target).offset().top;
    let width = target_mouse_down__ai.target.getBoundingClientRect().width;

    const container = document.createElement("div");
    container.className = "container-open-ai";
    container.style.top = top + 5 + "px";
    container.style.width = width + "px";
    container.style.left = $(target_mouse_down__ai.target).offset().left + "px";

    const content_header = document.createElement("div");
    content_header.className = "content-header-ai";

    const input_ai = document.createElement("input");
    input_ai.className = "input-ai";
    input_ai.value = text_send__ai;

    const img_star = document.createElement("img");
    img_star.src = document.location.origin + "/assets/icons/panel/ai_stars_ai.svg";
    img_star.className = "icon-star";

    const icon_close = document.createElement("img");
    icon_close.src = document.location.origin + "/assets/icons/panel/closeGray.svg";
    icon_close.className = "icon-close-ai";

    const box_writing = document.createElement("div");
    box_writing.className = "writing-response-ai";

    const writing_response = document.createElement("span");
    writing_response.className = "text-writing-response-ai";
    writing_response.innerText = GLOBAL_LANG.ai_writing_response;

    const img_loading = document.createElement("img");
    img_loading.src = document.location.origin + "/assets/icons/panel/ai_three_dots.svg";
    img_loading.className = "img-writing-response-ai";

    box_writing.appendChild(writing_response);
    box_writing.appendChild(img_loading);

    content_header.appendChild(input_ai);
    content_header.appendChild(img_star);
    content_header.appendChild(icon_close);
    content_header.appendChild(box_writing);

    const content_body = document.createElement("div");
    content_body.className = "content-body-ai";

    const field_response_ai = document.createElement("textarea");
    field_response_ai.className = "field-response-ai";
    field_response_ai.rows = "0";
    field_response_ai.readOnly = true;

    content_body.appendChild(field_response_ai);

    const content_footer = document.createElement("div");
    content_footer.className = "content-footer-ai";

    const title_options = document.createElement("span");
    title_options.className = "open-options-ai";
    title_options.innerText = GLOBAL_LANG.ai_select_title;

    const text_replace = document.createElement("span");
    text_replace.className = "text-replace-ai";
    text_replace.innerText = "Usar texto";

    const icon_replace = document.createElement("img");
    icon_replace.src = document.location.origin + "/assets/icons/panel/ai_data_transfer_icon.svg";
    icon_replace.className = "icon-replace-ai";

    content_footer.appendChild(title_options);
    content_footer.appendChild(text_replace);
    content_footer.appendChild(icon_replace);

    container.appendChild(content_header);
    container.appendChild(content_body);
    container.appendChild(content_footer);

    document.querySelector("body").appendChild(container);

    const list_ai = document.createElement("div");
    list_ai.className = "list-ai";
    list_ai.style.left = $(target_mouse_down__ai.target).offset().left + "px";
    list_ai.style.top = top + 6 + "px";

    const header = document.createElement("div");
    header.className = "header-ai";

    const title = document.createElement("span");
    title.innerText = GLOBAL_LANG.ai_select_title;
    header.appendChild(title);
    list_ai.appendChild(header);

    const select = document.createElement("div");
    select.className = "select-ai";

    const option_improve = document.createElement("div");
    option_improve.className = "option-ai";

    const box_improve = document.createElement("div");
    box_improve.className = "box-img-ai";

    const img_improve = document.createElement("img");
    img_improve.src = document.location.origin = "/assets/icons/panel/ai_improve_text.svg";

    const text_option_improve = document.createElement("span");
    text_option_improve.innerHTML = GLOBAL_LANG.ai_text_option_improve;

    box_improve.appendChild(img_improve);
    option_improve.appendChild(box_improve);
    option_improve.appendChild(text_option_improve);
    select.appendChild(option_improve);

    const option_correction = document.createElement("div");
    option_correction.className = "option-ai";

    const box_correction = document.createElement("div");
    box_correction.className = "box-img-ai";

    const img_correction = document.createElement("img");
    img_correction.src = document.location.origin = "/assets/icons/panel/ai_text_correction.svg";

    const text_option_correction = document.createElement("span");
    text_option_correction.innerHTML = GLOBAL_LANG.ai_text_option_correction;

    box_correction.appendChild(img_correction);
    option_correction.appendChild(box_correction);
    option_correction.appendChild(text_option_correction);
    select.appendChild(option_correction);

    const option_shorten = document.createElement("div");
    option_shorten.className = "option-ai";

    const box_shorten = document.createElement("div");
    box_shorten.className = "box-img-ai";

    const img_shorten = document.createElement("img");
    img_shorten.src = document.location.origin = "/assets/icons/panel/ai_shorten_text.svg";

    const text_option_shorten = document.createElement("span");
    text_option_shorten.innerHTML = GLOBAL_LANG.ai_text_option_shorten;

    box_shorten.appendChild(img_shorten);
    option_shorten.appendChild(box_shorten);
    option_shorten.appendChild(text_option_shorten);
    select.appendChild(option_shorten);

    const option_stretch = document.createElement("div");
    option_stretch.className = "option-ai";

    const box_stretch = document.createElement("div");
    box_stretch.className = "box-img-ai";

    const img_stretch = document.createElement("img");
    img_stretch.src = document.location.origin = "/assets/icons/panel/ai_stretch_text.svg";

    const text_option_stretch = document.createElement("span");
    text_option_stretch.innerHTML = GLOBAL_LANG.ai_text_option_stretch;

    box_stretch.appendChild(img_stretch);
    option_stretch.appendChild(box_stretch);
    option_stretch.appendChild(text_option_stretch);
    select.appendChild(option_stretch);

    const option_tone = document.createElement("div");
    option_tone.className = "option-ai";
    option_tone.dataset.item = "change_tone";

    const box_tone = document.createElement("div");
    box_tone.className = "box-img-ai";

    const img_tone = document.createElement("img");
    img_tone.src = document.location.origin = "/assets/icons/panel/ai_change_tone.svg";

    const text_option_tone = document.createElement("span");
    text_option_tone.innerHTML = GLOBAL_LANG.ai_text_option_tone;

    box_tone.appendChild(img_tone);
    option_tone.appendChild(box_tone);
    option_tone.appendChild(text_option_tone);
    select.appendChild(option_tone);

    const option_simplify = document.createElement("div");
    option_simplify.className = "option-ai";

    const box_simplify = document.createElement("div");
    box_simplify.className = "box-img-ai";

    const img_simplify = document.createElement("img");
    img_simplify.src = document.location.origin = "/assets/icons/panel/ai_simplify.svg";

    const text_option_simplify = document.createElement("span");
    text_option_simplify.innerHTML = GLOBAL_LANG.ai_text_option_simplify;

    box_simplify.appendChild(img_simplify);
    option_simplify.appendChild(box_simplify);
    option_simplify.appendChild(text_option_simplify);
    select.appendChild(option_simplify);

    const option_summary = document.createElement("div");
    option_summary.className = "option-ai";

    const box_summary = document.createElement("div");
    box_summary.className = "box-img-ai";

    const img_summary = document.createElement("img");
    img_summary.src = document.location.origin = "/assets/icons/panel/ai_summary.svg";

    const text_option_summary = document.createElement("span");
    text_option_summary.innerHTML = GLOBAL_LANG.ai_text_option_summary;

    box_summary.appendChild(img_summary);
    option_summary.appendChild(box_summary);
    option_summary.appendChild(text_option_summary);
    select.appendChild(option_summary);

    const option_explain = document.createElement("div");
    option_explain.className = "option-ai";

    const box_explain = document.createElement("div");
    box_explain.className = "box-img-ai";

    const img_explain = document.createElement("img");
    img_explain.src = document.location.origin = "/assets/icons/panel/ai_question.svg";

    const text_option_explain = document.createElement("span");
    text_option_explain.innerHTML = GLOBAL_LANG.ai_text_option_explain;

    box_explain.appendChild(img_explain);
    option_explain.appendChild(box_explain);
    option_explain.appendChild(text_option_explain);
    select.appendChild(option_explain);

    const option_translate = document.createElement("div");
    option_translate.className = "option-ai";

    const box_translate = document.createElement("div");
    box_translate.className = "box-img-ai";

    const img_translate = document.createElement("img");
    img_translate.src = document.location.origin = "/assets/icons/panel/ai_translate.svg";

    const text_option_translate = document.createElement("span");
    text_option_translate.innerHTML = GLOBAL_LANG.ai_text_option_translate;

    box_translate.appendChild(img_translate);
    option_translate.appendChild(box_translate);
    option_translate.appendChild(text_option_translate);
    select.appendChild(option_translate);

    list_ai.appendChild(select);
    container.appendChild(list_ai);
    container.after(list_ai);

    checkBottomToScrollAI(data);

    document.querySelector(".icon-close-ai").addEventListener("click", removeAI);
    document.querySelectorAll(".option-ai").forEach(elm => elm.addEventListener("click", chooseOpitionAI));
}


function getTooltipPositionAI(e) {

    body_height__ai = document.body.clientHeight;

    const selection = window.getSelection();
    selection.removeAllRanges();

    tooltip_top__ai = e.pageY - 57;
    tooltip_left__ai = e.x;

    document.body.getBoundingClientRect().width - tooltip_left__ai <= 200 ? tooltip_left__ai += - 200 : tooltip_left__ai;

    target_mouse_down__ai = e;
}


function createTooltipAI() {

    let data = "";
    let activeEl = document.activeElement;
    let activeElTagName = activeEl ? activeEl.tagName.toLowerCase() : null;

    if ((activeElTagName == "textarea") || (activeElTagName == "input" && /^(?:text|search|password|tel|url)$/i.test(activeEl.type)) && (typeof activeEl.selectionStart == "number")) {
        data = activeEl.value.slice(activeEl.selectionStart, activeEl.selectionEnd);
    }

    if (data.trim() !== "") {

        let wrinting_response = document.querySelector(".writing-response-ai");

        if (window.getSelection !== data && wrinting_response !== null) {

            if (wrinting_response.classList.contains("active") && controller__ai !== null) {
                controller__ai.abort();
            }
        }

        $(".tooltip-ai").remove();
        $(".container-open-ai").remove();
        $(".list-ai").remove();
        $(".bg-box-ia").remove();

        max_length__ai = activeEl.maxLength;

        const bg_box = document.createElement("div");
        bg_box.className = "bg-box-ia";

        const tooltip = document.createElement("div");
        tooltip.className = "tooltip-ai";
        tooltip.style.top = tooltip_top__ai + "px";
        tooltip.style.left = tooltip_left__ai + "px";

        const box = document.createElement("div");
        box.className = "box";

        const text = document.createElement('span');
        text.className = "span-ai";
        text.textContent = GLOBAL_LANG.ai_tooltip_content;

        const icon = document.createElement("img");
        icon.src = document.location.origin = "/assets/img/panel/star.png";

        box.appendChild(icon);
        box.appendChild(text);
        tooltip.appendChild(box);

        document.querySelector("body").appendChild(bg_box);
        document.querySelector("body").appendChild(tooltip);

        bg_box.addEventListener("click", removeAI);

        tooltip.addEventListener("click", () => createOptionsAI(data));
    }
}


function validateFieldAI() {

    let field_to_validate = document.activeElement;

    if (field_to_validate == undefined || target_mouse_down__ai == undefined) return false;

    if (field_to_validate !== target_mouse_down__ai.target) return false;

    if (field_to_validate.disabled) return false;

    if (field_to_validate.readOnly) return false;

    if (field_to_validate.id == "search") return false;

    if (field_to_validate.id == "input-cnpj") return false;

    if (field_to_validate.id == "input-search") return false;

    if (field_to_validate.id == "input-option-bot") return false;

    if (field_to_validate.classList.contains("input-ai")) return false;

    if (field_to_validate.classList.contains("datepicker")) return false;

    if (field_to_validate.className == "form-control time") return false;

    if (field_to_validate.id == "text_quick_answer_button") return false;

    if (field_to_validate.closest(".msf_multiselect") !== null) return false;

    if (field_to_validate.className == "form-control mx-2 time ") return false;

    if (field_to_validate.closest(".modal-content") !== null) { return false; }

    if (field_to_validate.closest(".bootstrap-tagsinput") !== null) return false;

    if (field_to_validate.classList.contains("field-response-ai")) return false;

    if (field_to_validate.classList.contains("no-ai")) return false;

    if (field_to_validate.id == "text-button" && field_to_validate.closest("#div_call_to_action")) return false;

    if (field_to_validate.id == "phone-button" && field_to_validate.closest("#div_call_to_action")) return false;

    return true;
}

document.querySelectorAll("textarea, input").forEach(c => {
    c.addEventListener("mousedown", function (e) {
        getTooltipPositionAI(e);
    });
});

document.onmouseup = document.onkeyup = function (e) {

    let active_elm = document.activeElement;

    if (e.type === "mouseup" && e.button === 2 && document.querySelector(".tooltip-ai") !== null) {
        $(".tooltip-ai").remove();
        $(".bg-box-ia").remove();
    }

    if (active_elm.tagName.toLowerCase() === "textarea" || active_elm.tagName.toLowerCase() === "input") {

        if (active_elm.value.slice(active_elm.selectionStart, active_elm.selectionEnd).trim() == '') {
            if (document.querySelector(".tooltip-ai") !== null) {

                $(".tooltip-ai").remove();
                $(".bg-box-ia").remove();
            }
        } else {

            if (!validateFieldAI()) return;

            createTooltipAI();
        }
    }
};

document.addEventListener("keydown", function (e) {
    if (e.key) {
        if (e.key.toLowerCase() === "tab") {
            if (document.querySelector(".tooltip-ai") !== null) {
                $(".tooltip-ai").remove();
                $(".bg-box-ia").remove();
                return;
            }
        }

        if (e.key.toLowerCase() === "escape") {
            if (document.querySelector(".container-open-ai") !== null &&
                !document.querySelector(".writing-response-ai").classList.contains("active")) {
                removeAI();
                return;
            }
        }

    }

});

