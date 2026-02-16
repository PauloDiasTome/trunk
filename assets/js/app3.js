var base64data;
var secounds = 0;
var minutes = 0;
var stop = false;

const workerOptions = {
    OggOpusEncoderWasmPath: 'https://app.talkall.com.br/assets/OggOpusEncoder.wasm',
    WebMOpusEncoderWasmPath: 'https://app.talkall.com.br/assets/WebMOpusEncoder.wasm'
};

function countSecounds() {
    if (stop == true) {
        stop = false;
        secounds = 0;
        minutes = 0;
        return;
    }
    var sSecound = "";
    var sMinute = "";
    if (secounds == 59) {
        minutes = minutes + 1;
        secounds = 0;
    } else {
        secounds = secounds + 1;
    }
    sSecound = secounds;
    sMinute = minutes;
    if (secounds <= 9) {
        sSecound = "0" + secounds.toString();
    }
    $("#recording-time").html(sMinute + ":" + sSecound);
    setTimeout('countSecounds()', 1000);
    ta.chat.recording();
}


function startRecording() {

    window.MediaRecorder = OpusMediaRecorder;

    navigator.mediaDevices.getUserMedia({ audio: true }).then(stream => {

        $("#recording-time").html("0:00");
        $(".input .text").width("calc(100% - 86px)");

        let options = { mimeType: 'audio/ogg' };
        recorder = new MediaRecorder(stream, options, workerOptions);
        recorder.start();

        $("#record-audio").hide();
        $("#stop-record").show(500);
        $("#ok-record").show(500);
        $("#recording-time").show(500);

        setTimeout('countSecounds()', 500);

        recorder.addEventListener('dataavailable', (e) => {

            var reader = new FileReader();
            reader.readAsDataURL(e.data);
            reader.onloadend = function () {

                base64data = reader.result;

                var data = new FormData();
                data.append("base64", base64data);
                data.append("media_type", "audio");
                data.append("ta_id", messenger.Chat.key_remote_id);

                $.ajax({
                    type: "POST",
                    url: "https://files.talkall.com.br:3000/upload/base64",
                    data: data,
                    success: function (json) {
                        switch (json.mimetype) {
                            case "audio/ogg":
                                ta.message.sendAudio(json.ta_id, json.mimetype, json.size, json.duration, json.url);
                                break;
                        }
                    },
                    cache: false,
                    contentType: false,
                    processData: false,
                    xhr: function () {
                        var xmlHttp = $.ajaxSettings.xhr();
                        xmlHttp.upload.onprogress = function (event) {
                            // console.log(event);
                        };
                        return xmlHttp;
                    }
                });

            }
        });
    }).catch(error => {
        alert(GLOBAL_LANG.messenger_alert_microphone_disconnected);
    });

}


function stopRecording() {
    stop = true;

    recorder.stop();
    recorder.stream.getTracks().forEach(i => i.stop());

    $("#recording-time").hide();
    $("#record-audio").show();
    $("#file-upload").show();
    $("#stop-record").hide();
    $("#ok-record").hide();

    ta.chat.stoped();

    $(".input .text")[0].style.width = "calc(100% - 86px)";
}


function cancelRecording() {
    if ($(".icon-ia").is(":visible")) return;

    stop = true;
    if (typeof recorder !== 'undefined') {
        if (recorder != undefined || recorder != null) {
            recorder.stream.getTracks().forEach(i => i.stop());
        }
    }

    $("#recording-time").hide();
    $("#stop-record").hide();
    $("#ok-record").hide();

    if (messenger.Chat.is_type != 9)
        $("#record-audio").show();

    $("#file-upload").show();

    $(".input .text").width("calc(100% - 86px)");
    // recorder.clear();
    // ta.chat.wait();
}


function onFileUpload() {

    var data = new FormData();
    data.append("filetoupload", event.target.files[0]);
    data.append("ta_id", messenger.Chat.key_remote_id);

    let creation = Math.floor(Date.now() / 1000);
    let key_id = Util.makeId();

    $.ajax({
        type: "POST",
        url: "https://files.talkall.com.br:3000",
        data: data,
        success: function (json) {
            switch (json.mimetype) {
                case "audio/ogg":
                    ta.message.sendAudio(json.ta_id, json.mimetype, json.size, json.duration, json.url);
                    break;
                case "image/jpeg":
                case "image/jpg":
                case "image/png":
                    ta.message.sendImage(json.ta_id, "", json.url, json.mimetype, json.size, json.thumbnail);
                    break;
                case "application/pdf":
                    ta.message.sendDocument(json.ta_id, key_id, json.media_name, json.size, json.pages_count, json.url, json.mimetype, json.thumbnail);
                    messenger.Message.DocumentMessage({
                        'Cmd': 'Message',
                        'type': 'DocumentMessage',
                        'token': key_id,
                        'creation': creation,
                        'key_from_me': 2,
                        'key_remote_id': messenger.Chat.key_remote_id,
                        'media_caption': "",
                        'media_name': json.media_name,
                        'media_title': json.media_name,
                        'page_count': null,
                        'media_mime_type': "application/pdf",
                        'media_type': 4,
                        'media_url': json.url,
                        'msgStatus': 0,
                        'thumb_image': ""
                    }, true);
                    break;
                case "video/mp4":
                    ta.message.sendVideo(json.ta_id, key_id, json.media_name, json.duration, json.size, json.mimetype, json.url, json.thumbnail);
                    messenger.Message.VideoMessage({
                        'Cmd': 'Message',
                        'type': 'VideoMessage',
                        'token': key_id,
                        'creation': creation,
                        'key_from_me': 2,
                        'key_remote_id': messenger.Chat.key_remote_id,
                        'media_caption': event.target.files[0].name,
                        'media_duration': 0,
                        'media_mime_type': event.target.files[0].type,
                        'media_type': 5,
                        'media_url': json.url,
                        'msgStatus': 0
                    }, true);
                    break;
                default:
                    ta.message.sendDocument(json.ta_id, key_id, json.media_name, json.size, json.pages_count, json.url, json.mimetype, json.thumbnail);
                    messenger.Message.DocumentMessage({
                        'Cmd': 'Message',
                        'type': 'DocumentMessage',
                        'token': key_id,
                        'creation': creation,
                        'key_from_me': 2,
                        'key_remote_id': messenger.Chat.key_remote_id,
                        'media_caption': json.media_name,
                        'media_name': json.media_name,
                        'media_title': json.media_name,
                        'page_count': null,
                        'media_mime_type': json.mimetype,
                        'media_type': 4,
                        'media_url': json.url,
                        'msgStatus': 0,
                        'thumb_image': "load"
                    }, true);
                    break;
            }
        },
        cache: false,
        contentType: false,
        processData: false
    });
}


function readMessages() {

    let count = "";
    let noRead = $(".itemSelected").find(".body").find(".no-read-message").find("label").text();
    let notification = document.title;

    notification = notification.split(")")[0];
    notification = notification.split("(")[1];

    if (typeof notification == 'undefined' || notification == '') notification = 0;

    count = parseInt(notification) - parseInt(noRead);

    document.title = `${count == 0 ? "" : "(" + count + ")"} TalkAll | Messenger`;

    $(".itemSelected").find(".body").find(".no-read-message").hide();
    $(".itemSelected").find(".body").find(".no-read-message").find("label").html(0);

    const chat_active = document.getElementById("chat-active");
    const chat_internal = document.getElementById("chat-internal");
    const chat_wait = document.getElementById("chat-wait");

    if (chat_active.style !== "none") $("#chat-active").find(".no-read").find("label").text(count);
    if (chat_internal.style !== "none") $("#chat-internal").find(".no-read").find("label").text(count);
    if (chat_wait.style !== "none") $("#chat-internal").find(".no-read").find("label").text(count);

    cancelRecording();
}

document.querySelector(".input-text").addEventListener("click", readMessages);

function loadPerfil(attendant_remote_id) {
    if (attendant_remote_id !== null || attendant_remote_id.trim() !== '' || attendant_remote_id.length <= 0) {
        ta.conn.sendRequest({ Cmd: 'loadAttendanceProfile', user_key_remote_id: attendant_remote_id });
    } else {
        return;
    }
}

function loadAttendanceProfile(json) {
    document.getElementById('profile_name').textContent = json.items.name;
    document.getElementById('profile_sector').textContent = json.items.sector;
    document.getElementById('profile_attendance').textContent = json.items.job;
    document.getElementById('profile_email').textContent = json.items.email
}

$(document).on("keyup", ".input-text", function () {
    const inputText = $(this);

    if (inputText.html() === "<br>") {
        inputText.html("");
    }
});

function updateKeyRemoteId(key_remote_id) {

    for (let chat of messenger.Chats) {
        if (chat.key_remote_id === key_remote_id) {
            return;
        }
    }

    if (key_remote_id.startsWith("55")) {

        const _contact = key_remote_id.split('-')[0];
        const _channel = key_remote_id.split('-')[1];

        const key_with_nine = _contact.replace(/^(55\d{2})(\d{4})(\d{4})$/, "$19$2$3");

        let search_key_remote_id = key_with_nine + '-' + _channel;

        for (let chat of messenger.Chats) {
            if (chat.key_remote_id === search_key_remote_id) {
                chat.key_remote_id = key_remote_id;

                if (messenger.Chat.key_remote_id == key_remote_id || messenger.Chat.key_remote_id == search_key_remote_id) {
                    messenger.Chat.key_remote_id = key_remote_id;
                }

                if (ta.key_remote_id == key_remote_id || ta.key_remote_id == search_key_remote_id) {
                    ta.key_remote_id = key_remote_id;
                }

                return;
            }
        }

        const key_without_nine = _contact.replace(/^(55\d{2})(\d)(\d{4})(\d{4})$/, "$1$3$4");
        search_key_remote_id = key_without_nine + '-' + _channel;

        for (let chat of messenger.Chats) {
            if (chat.key_remote_id === search_key_remote_id) {
                chat.key_remote_id = key_remote_id;

                if (messenger.Chat.key_remote_id == key_remote_id || messenger.Chat.key_remote_id == search_key_remote_id) {
                    messenger.Chat.key_remote_id = key_remote_id;
                }

                if (ta.key_remote_id == key_remote_id || ta.key_remote_id == search_key_remote_id) {
                    ta.key_remote_id = key_remote_id;
                }

                return;
            }
        }

    }
}

function createElementTemplateHeader(text) {
    const container_header = document.createElement("div");
    container_header.className = "templateHeader";

    const header_text = document.createElement("span");
    header_text.textContent = text;

    container_header.append(header_text);
    messenger.Message.body.prepend(container_header);
}