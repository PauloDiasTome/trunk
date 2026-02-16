/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var socket;
var widgetId = "";
var clientId = "";
var host = "wss://messenger.talkall.com.br:28192";
// var host = "wss:/messenger.interno.gelt.com.br:3030";

const Filters = JSON.parse(localStorage.getItem("filters")) || null;

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

$(document).ready(function () {
    TAWebsocket();

    $('#input-button').on("keyup", function () {
        $("#button-msg").text($("#input-button").val());

        let limit = $("#input-button").val();

        if (limit.length == 25) {
            $("#alert-button").text("O nome deve conter no máximo 25 caracteres");
        } else {
            $("#alert-button").text("");
        }

    });

    // coloca título no preview do widget
    $('#input-title').on("keyup", function () {
        $("#title-msg").text($("#input-title").val());

        let limit = $("#input-title").val();

        if (limit.length == 23) {
            $("#alert-title").text("O título deve conter no máximo 25 caracteres");
        } else {
            $("#alert-title").text("");
        }

    });

    // coloca subtítulo no preview do widget
    $('#input-subtitle').on("keyup", function () {
        $("#subtitle-msg").text($("#input-subtitle").val());

        let limit = $("#input-subtitle").val();

        if (limit.length == 100) {
            $("#alert-subtitle").text("O subtitulo deve conter no máximo 100 caracteres");
        } else {
            $("#alert-subtitle").text("");
        }

    });

    var _color;
    var _title;
    var _button;
    var _subtitle;
    var _position;
    var _name;

    const etp_button = document.querySelector("#btn_etp_button");
    etp_button.addEventListener("click", () => {

        _button = document.getElementById('input-button').value;

        let progress = document.getElementById('button');
        progress.classList.add("active-line");

        progress = document.getElementById('title');
        progress.classList.add('active-ball');

        document.getElementById('etp_button').style.display = "none";
        document.getElementById('container-preview-button').style.display = "none";

        document.getElementById('etp_title').style.display = "block";
        document.getElementById('container-preview-inner').style.display = "block";

    });

    const etp_title = document.querySelector('#btn_etp_title');
    etp_title.addEventListener('click', () => {

        // scriptWidget();
        // pega valores dos inputs
        _title = document.getElementById('input-title').value;
        _subtitle = document.getElementById('input-subtitle').value;
        _name = document.querySelector("#nameWidget").value;

        if (_name == '') {
            _name = 'Widget';
        }

        let progress = document.getElementById('title');
        progress.classList.add("active-line");

        progress = document.getElementById('conclude');
        progress.classList.add('active-ball');

    });

    const btn_close_title = document.querySelector("#btn_close_title");
    btn_close_title.addEventListener('click', () => {

        let progress = document.getElementById('button');
        progress.classList.remove("active-line");

        progress = document.getElementById('title');
        progress.classList.remove('active-ball');

        document.getElementById('etp_button').style.display = "block";
        document.getElementById('container-preview-button').style.display = "block";

        document.getElementById('etp_title').style.display = "none";
        document.getElementById('container-preview-inner').style.display = "none";

        document.getElementById('container-preview-inner').style.display = "none";

    });

    $('body').on("click", ".palette", function (e) {

        let id = $(this).attr('id');
        let color = document.getElementById(id).style.backgroundColor;

        _color = color;
        $(".container-preview-button").css({ "background-color": color });

    });

    $('body').on("click", "#btn_etp_title", function () {

        document.getElementById("btn_etp_title").disabled = true;

        $(".position").each(function (idx, elm) {

            if (elm.checked) {
                _position = elm.value;
            }
        });

        if (_color == undefined) {
            _color = document.getElementById("container-preview-button").style.backgroundColor = "rgb(37 211 102)";
        }

        // verifica se é add ou update
        var is_channel = window.location.href.includes("add") ? 0 : window.location.href.split('/')[5];

        if (is_channel == 0) {

            $.ajax({
                type: "post",
                url: document.location.origin + "/integration/edit/widget",
                data: {
                    name: _name,
                    id_channel: is_channel,
                    color: _color,
                    button: _button,
                    title: _title,
                    subtitle: _subtitle,
                    position: _position,
                },
                dataType: 'html', success: function (response) {

                    let channel = JSON.parse(response);
                    $("#div_loading_req").modal({ backdrop: 'static', keyboard: false, show: true });
                    setTimeout(() => { $('#div_loading_req').modal('hide'); }, 1000);
                    window.location.href = origin + "/copy/integration/script/" + channel.id_channel;
                }
            });

        } else {

            $.ajax({
                type: "post",
                url: document.location.origin + "/integration/edit/widget",
                data: {
                    name: _name,
                    id_channel: is_channel,
                    color: _color,
                    button: _button,
                    title: _title,
                    subtitle: _subtitle,
                    position: _position,
                },
                dataType: 'html', success: function (response) {

                    $("#div_loading_req").modal({ backdrop: 'static', keyboard: false, show: true });
                    setTimeout(() => { $('#div_loading_req').modal('hide'); }, 1000);
                    window.location.href = origin + "/copy/integration/script/" + is_channel;
                }
            });
        }

    });

    let color = $("#input-color").attr('name');

    $("#container-preview-button").css({ "background-color": color });

});

function getInfo() {

    $.get("https://ipinfo.io", function (response) {

        var json = {
            "Cmd": "visitorInfo",
            "ip": response.ip,
            "hostname": response.hostname,
            "city": response.city,
            "region": response.region,
            "country": response.country,
            "org": response.org,
            "timezone": response.timezone,
            "page": window.location.href
        };

        socket.send(JSON.stringify(json));

    }, "jsonp");
}

function setPresenceOnline() {
    var json = { Cmd: "action", "key_remote_id": clientId, "presence": { "type": "available" } };
    socket.send(JSON.stringify(json));
}

function TAWebsocket() {
    try {
        socket = new WebSocket(host);
        socket.onopen = function () {

            widgetId = localStorage.getItem("widget-id");
            clientId = localStorage.getItem("client-id");

            if (clientId == null) {

                var json = {
                    Cmd: "login",
                    "account": widgetId,
                    "pw": widgetId
                };

                socket.send(JSON.stringify(json));

            } else {
                var json = { Cmd: "login", "account": widgetId, "pw": clientId };
                socket.send(JSON.stringify(json));
            }
        }
        socket.onmessage = function (msg) {
            var json = JSON.parse(msg.data);
            if (json.status == 200) {
                switch (json.Cmd) {
                    case 'login':
                        if (clientId == null) {
                            var json = { Cmd: "init" };
                            socket.send(JSON.stringify(json));
                        } else {
                            setPresenceOnline();
                            getInfo();
                        }
                        break;
                    case 'init':
                        clientId = json.key_remote_id;
                        localStorage.setItem("client-id", clientId);
                        setPresenceOnline();
                        getInfo();
                        break;
                    case 'broadcast':
                        welcome_message_title.innerHTML = json.title;
                        welcome_message_body.innerHTML = json.message;
                        widget_popup.style.opacity = 1;
                        break;
                }
            }
        }
        socket.onclose = function () {
            setTimeout(function () { TAWebsocket(); }, 15000);
        }
    } catch (exception) {
        setTimeout(function () { TAWebsocket(); }, 15000);
    }
}

document.querySelector(".btn.btn-primary").addEventListener("click", function () {
    if (Filters) {
        Filters.btn_back = true;
        localStorage.setItem("filters", JSON.stringify(Filters));
    }
});