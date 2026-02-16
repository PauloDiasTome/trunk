"use strict";

var socket;

const host = "wss://app.talkall.com.br:5000";
const appVersion = "2.2027.17";

const removeElements = () => {
    document.querySelector(".main-content .img-default") ? document.querySelector(".main-content .img-default").remove() : "";
    document.querySelector(".main-content video") ? document.querySelector(".main-content video").remove() : "";
    document.querySelector(".loading-load").style.display = "block";
}

const tvStart = (json) => {
    if (!document.querySelector(".img-default")) {

        removeElements();

        const img = document.createElement("img");
        img.classList = "img-default";
        img.src = JSON.parse(json.tv_settings).url;
        img.style.display = "block";

        document.querySelector(".loading-load").style.display = "none";
        document.querySelector(".main-content").appendChild(img);
    }
}


const startBroadcast = (json) => {
    if (document.querySelector(".main-content video")?.dataset.token !== json.token || !document.querySelector(".main-content video")) {

        removeElements();

        const url = json.media_url;
        const main_content = document.querySelector(".main-content");

        const video = document.createElement("video");
        video.src = url;
        video.style.display = "block";
        video.autoplay = true;
        video.loop = true;
        video.controls = false;
        video.dataset.token = json.token;
        video.muted = true;

        document.querySelector(".loading-load").style.display = "none";
        main_content.appendChild(video);
    }
}


const updateTv = (json) => {
    if (!document.querySelector(".main-content video") && document.querySelector(".main-content .img-default")) {
        document.querySelector(".main-content .img-default").src = JSON.parse(json.tv_settings).url;
    }
}


const modalAlert = (data) => {
    const modal = document.getElementById("modal-alert");
    document.querySelector("#modal-alert span").innerHTML = data;
    modal.style.display = "block";
}


const checkConnection = (json) => {
    if (json.status == 1000) {
        if (json.reason == "connection replace") {
            localStorage.removeItem("tv_token");
            modalAlert(GLOBAL_LANG.tv_index_modal_alert_connection_replace);

            setTimeout(() => {
                window.location.href = window.location.origin + "/tv";
            }, 5000);
        }
    }

    if(json.status == 501) {
        if(json.reason == "not authorized") {
            localStorage.removeItem("tv_token");

            document.querySelector(".loading-load").style.display = "none";
            modalAlert(GLOBAL_LANG.tv_index_modal_alert_connection_not_authorized);

            setTimeout(() => {
                window.location.href = window.location.origin + "/tv";
            }, 5000);
        }
    }
}


const TAWebsocket = () => {

    try {
        socket = new WebSocket(host);

        socket.onopen = function () {

            const data = {
                Cmd: "login",
                account: localStorage.getItem("tv_token"),
                pw: localStorage.getItem("tv_token"),
                version: appVersion
            };

            socket.send(JSON.stringify(data));
        };

        socket.onmessage = function (msg) {

            const json = JSON.parse(msg.data);

            if (json.status == 200) {

                switch (json.Cmd) {
                    case "TV":
                        tvStart(json);
                        break;
                    case "broadcast":
                        startBroadcast(json);
                        break;
                    case "UpdateTv":
                        updateTv(json)
                        break;
                    case "disconnect":
                        localStorage.removeItem("tv_token");
                        modalAlert(GLOBAL_LANG.tv_index_modal_alert_connection_close)

                        setTimeout(() => {
                            window.location.href = window.location.origin + "/tv";
                        }, 5000);
                        break;
                    default:
                        break;
                }
            }

            checkConnection(json);
        };

        socket.onclose = function () {
            console.log("error onclose")
            setTimeout(() => TAWebsocket(), 6000);
        };

    } catch (exception) {
        console.log("error exception");
        setTimeout(() => TAWebsocket(), 5000);
    }
}


TAWebsocket();
