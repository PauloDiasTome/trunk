"use strict";

let controller__ai = null;
const Components = new ComponentsDom();

function removeOptionsAI() {
    document.querySelector(".list-ai").style.display = "none";
    document.querySelector(".ai-bg-box").style.display = "none";
    document.querySelector(".ai-bg-box").removeEventListener("click", removeOptionsAI);

    if (document.querySelector(".list-ai .header-ai") !== null) document.querySelector(".list-ai .header-ai").remove();
    if (document.querySelector(".list-ai .select-ai") !== null) document.querySelector(".list-ai .select-ai").remove();
};


function removeFieldAI() {
    if (document.querySelector(".field-ai .head img") !== null) document.querySelector(".field-ai .head img").remove();
    if (document.querySelector(".field-ai .items .item") !== null) document.querySelector(".field-ai .items .item").remove();
    if (document.querySelector(".ticket .items .item") !== null) document.querySelector(".ticket").style.display = "block";

    document.querySelector(".chat .field-ai").style.display = "none";
    document.querySelector(".chat .field-ai .items").style.display = "none";

    if (controller__ai !== null) {
        controller__ai.abort();
    }
}


function removeQuickReplyAI() {
    let items = document.querySelectorAll(".quick .items .item");

    if (items.length > 0) {
        Array.from(items).forEach(function (item) {
            item.remove();
        });
    }

    document.querySelector(".quick").style.display = "none";
}


function showIconAI() {
    removeOptionsAI();

    if (window.getComputedStyle(document.getElementById("stop-record")).display !== 'none') return;

    if (document.querySelector(".input-text").innerText.trim() !== "") {
        document.getElementById("record-audio").style.display = "none";
        document.getElementById("icon-IA").style.display = "block";
    } else {
        if (messenger.Chat.is_type == 9) {
            document.getElementById("record-audio").style.display = "none";
            document.getElementById("icon-IA").style.display = "none";
        } else {
            document.getElementById("record-audio").style.display = "block";
            document.getElementById("icon-IA").style.display = "none";
        }
    }
}


function createOptionsAI() {
    document.getElementById("box-clip").style.display = "none";
    document.getElementById("emojipicker").style.display = "none";
    removeQuickReplyAI();

    const data = {
        options: [
            GLOBAL_LANG.messenger_ai_text_option_improve,
            GLOBAL_LANG.messenger_ai_text_option_correction,
            GLOBAL_LANG.messenger_ai_text_option_shorten,
            GLOBAL_LANG.messenger_ai_text_option_stretch,
            GLOBAL_LANG.messenger_ai_text_option_tone,
            GLOBAL_LANG.messenger_ai_text_option_simplify,
            GLOBAL_LANG.messenger_ai_text_option_summary,
            GLOBAL_LANG.messenger_ai_text_option_explain,
            GLOBAL_LANG.messenger_ai_text_option_translate,
        ],
        icons: [
            "/assets/icons/messenger/ai_improve_text.svg",
            "/assets/icons/messenger/ai_text_correction.svg",
            "/assets/icons/messenger/ai_shorten_text.svg",
            "/assets/icons/messenger/ai_stretch_text.svg",
            "/assets/icons/messenger/ai_change_tone.svg",
            "/assets/icons/messenger/ai_simplify.svg",
            "/assets/icons/messenger/ai_summary.svg",
            "/assets/icons/messenger/ai_question.svg",
            "/assets/icons/messenger/ai_translate.svg"
        ],
        dark_icons: [
            "/assets/icons/messenger/dark/ai_improve_text.svg",
            "/assets/icons/messenger/dark/ai_text_correction.svg",
            "/assets/icons/messenger/dark/ai_shorten_text.svg",
            "/assets/icons/messenger/dark/ai_stretch_text.svg",
            "/assets/icons/messenger/dark/ai_change_tone.svg",
            "/assets/icons/messenger/dark/ai_simplify.svg",
            "/assets/icons/messenger/dark/ai_summary.svg",
            "/assets/icons/messenger/dark/ai_question.svg",
            "/assets/icons/messenger/dark/ai_translate.svg"
        ]
    };

    const list_ai = document.querySelector(".list-ai");

    const header = Components.div({ class: "header-ai" });
    header.appendChild(Components.span({ text: GLOBAL_LANG.messenger_ai_select_title }));

    const select = Components.div({ class: "select-ai" });

    list_ai.appendChild(header);

    for (let i = 0; i < data.options.length; i++) {

        const box_option = Components.div({ class: "option-ai" });
        box_option.dataset.item = [i + 1];

        const box_img = Components.div();

        if (document.body.classList.contains("darkMessenger"))
            box_img.appendChild(Components.img({ src: document.location.origin + data.dark_icons[i] }));
        else
            box_img.appendChild(Components.img({ src: document.location.origin + data.icons[i] }));

        box_option.appendChild(box_img);
        box_option.appendChild(Components.span({ text: data.options[i] }));

        select.appendChild(box_option);
    }

    list_ai.appendChild(select);
    list_ai.style.display = "block";
    list_ai.style.bottom = document.getElementById("bottomEntryRectangle").clientHeight + "px";
    list_ai.style.left = document.getElementById("bottomEntryRectangle").clientWidth - 300 + "px";

    document.querySelector(".ai-bg-box").style.display = "block";
    document.querySelector(".ai-bg-box").addEventListener("click", removeOptionsAI);
    document.querySelectorAll(".option-ai").forEach(elm => elm.addEventListener("click", chooseOpitionAI));
}


async function chooseOpitionAI() {
    removeOptionsAI();
    removeFieldAI();
    removeQuickReplyAI();

    if (document.querySelector(".ticket .items .item") !== null) document.querySelector(".ticket").style.display = "none";

    document.querySelector(".right .chat .field-ai").style.display = "block";
    document.querySelector(".field-ai .head .close-ai").addEventListener("click", removeFieldAI);

    const text_head = document.querySelector(".chat .field-ai .head b");

    if (document.body.classList.contains("darkMessenger"))
        text_head.after(Components.img({ style: "width:24px;", src: document.location.origin + "/assets/icons/messenger/dark/ai_three_dots.svg" }));
    else
        text_head.after(Components.img({ style: "width:24px;", src: document.location.origin + "/assets/icons/messenger/ai_three_dots.svg" }));

    text_head.innerText = GLOBAL_LANG.messenger_ai_writing_response;

    let chosen_option = this.innerText;
    if (this.dataset.item === "5") {
        chosen_option = GLOBAL_LANG.messenger_ai_text_send_change_tone;
    }

    const result = await fetchOpenAI(chosen_option, document.querySelector(".input-text").innerText);
    writeAnswerAI(result.new);
}


function fetchOpenAI(option, text) {
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

            let key = localStorage.getItem("userToken");

            const response = await fetch("https://services.talkall.com.br:4090/openai?key=" + key, requestOptions);
            const result = await response.json();

            controller__ai = null;

            if (result.erro) {
                reject(new Error('Erro na resposta: ' + result.reason));

                if (document.querySelector(".field-ai img") != null) {
                    document.querySelector(".field-ai img").remove();
                }

                const text_head = document.querySelector(".chat .field-ai .head b");
                text_head.classList.add("alert-error-ai");
                text_head.innerText = GLOBAL_LANG.messenger_ai_request_error;

                return;
            }

            resolve(result);
        } catch (error) {

            if (error.message === 'The user aborted a request.') {
                console.log('A requisição foi cancelada.');
            } else {
                console.log('catch');
                reject(error);
                console.log(error);

                if (document.querySelector(".field-ai img") != null) {
                    document.querySelector(".field-ai img").remove();
                }

                const text_head = document.querySelector(".chat .field-ai .head b");
                text_head.classList.add("alert-error-ai");
                text_head.innerText = GLOBAL_LANG.messenger_ai_request_error;
            }
        }
    });
}


function writeAnswerAI(data) {

    if (data !== undefined) {
        const head = document.querySelector(".field-ai .head");
        const text_head = document.querySelector(".chat .field-ai .head b");
        const items = document.querySelector(".field-ai .items");

        const item = Components.div({ class: "item" });
        items.appendChild(item);

        var text = data.replace('\n', '').replace('\n', '');
        var text_returned = text.split(' ');
        var currentWordIndex = 0;

        const interval = setInterval(function () {
            if (currentWordIndex < text_returned.length) {
                head.style.borderRadius = "unset";
                items.style.display = "block";
                item.innerText += text_returned[currentWordIndex] + ' ';

                currentWordIndex++;
                items.scrollTop = items.scrollHeight;

            } else {
                clearInterval(interval);
                if (document.querySelector(".field-ai .head img") != null) document.querySelector(".field-ai .head img").remove();
                text_head.innerText = GLOBAL_LANG.messenger_ai_title;

                if (document.querySelector(".field-ai .items .item") !== null) document.querySelector(".field-ai .items .item").addEventListener("click", pasteTextAI);
            }
        }, 150);
    }
}


function pasteTextAI() {

    const max_length = messenger.Chat.is_type == 9 ? 1000 : 4096;
    const response = document.querySelector(".field-ai .items .item").innerText;

    if (response.length > max_length) {
        document.querySelector(".input-text").innerText = response.slice(0, max_length);
    } else {
        document.querySelector(".input-text").innerText = response;
    }

    removeFieldAI();
}

document.querySelector(".input-text").addEventListener("keyup", showIconAI);
document.querySelector(".input-text").addEventListener("focus", showIconAI);
document.getElementById("icon-IA").addEventListener("click", createOptionsAI);