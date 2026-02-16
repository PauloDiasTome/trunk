var bConected = false;
var widget_token = "CDxenrvWpNZPa53aUqB29S7BG1P2WrID";
var widget_client = "";
var widget_chat = "2DMJbIdFCQFLLcjY3APCKCW97oOBJCTi";
var typingTimer;
var doneTypingInterval = 2000;
var Update = true;
var last_date = "";

function postNotification(title, icon, body) {
    var notification = new Notification(title, {
        icon: icon,
        body: body
    });
    notification.onclick = function () {
        window.open("#");
    }
}

function available(id) {
}

function getTypeThumbMsg(type) {
    switch (parseInt(type)) {
        case 1:
            return "";
        case 2:
            return "<svg style='width:16px;height:16px;float:left;margin-right:5px;margin-top: -2px;' xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 12 20\"><path fill=\"currentColor\" fill-opacity=\".4\" d=\"M6 11.745a2 2 0 0 0 2-2V4.941a2 2 0 0 0-4 0v4.803a2 2 0 0 0 2 2.001zm3.495-2.001c0 1.927-1.568 3.495-3.495 3.495s-3.495-1.568-3.495-3.495H1.11c0 2.458 1.828 4.477 4.192 4.819v2.495h1.395v-2.495c2.364-.342 4.193-2.362 4.193-4.82H9.495v.001z\"/></svg>";
        case 3:
            return "<span style='width:16px;height:16px;float:left;margin-right:5px;margin-top: -2px;'><svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 16 20\"><path fill=\"currentColor\" fill-opacity=\".4\" d=\"M13.822 4.668H7.14l-1.068-1.09a1.068 1.068 0 0 0-.663-.278H3.531c-.214 0-.51.128-.656.285L1.276 5.296c-.146.157-.266.46-.266.675v1.06l-.001.003v6.983c0 .646.524 1.17 1.17 1.17h11.643a1.17 1.17 0 0 0 1.17-1.17v-8.18a1.17 1.17 0 0 0-1.17-1.169zm-5.982 8.63a3.395 3.395 0 1 1 0-6.79 3.395 3.395 0 0 1 0 6.79zm0-5.787a2.392 2.392 0 1 0 0 4.784 2.392 2.392 0 0 0 0-4.784z\"/></svg></span>";
        case 4:
        case 10:
        case 27:
            return "<svg style='width:16px;height:16px;float:left;margin-right:5px;margin-top: -2px;' xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 13 20\"><path fill=\"currentColor\" fill-opacity=\".4\" d=\"M10.2 3H2.5C1.7 3 1 3.7 1 4.5v10.1c0 .7.7 1.4 1.5 1.4h7.7c.8 0 1.5-.7 1.5-1.5v-10C11.6 3.7 11 3 10.2 3zm-2.6 9.7H3.5v-1.3h4.1v1.3zM9.3 10H3.5V8.7h5.8V10zm0-2.7H3.5V6h5.8v1.3z\"/></svg>";
        case 5:
            return "<svg style='width:16px;height:16px;float:left;margin-right:5px;margin-top: -2px;' xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 16 14\"><path fill=\"currentColor\" d=\"M14.987 2.668l-3.48 3.091v-2.27c0-.657-.532-1.189-1.189-1.189H1.689C1.032 2.3.5 2.832.5 3.489v7.138c0 .657.532 1.189 1.189 1.189h8.629c.657 0 1.189-.532 1.189-1.189V8.328l3.48 3.09v-8.75z\"/></svg>";
        case 6:
            return "<svg style='width:16px;height:16px;float:left;margin-right:5px;margin-top: -2px;' xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 20 20\"><path id=\"Combined-Shape\" fill=\"currentColor\" d=\"M4.878 3.9h10.285c1.334 0 1.818.139 2.306.4s.871.644 1.131 1.131c.261.488.4.972.4 2.306v4.351c0 1.334-.139 1.818-.4 2.306a2.717 2.717 0 0 1-1.131 1.131c-.488.261-.972.4-2.306.4H4.878c-1.334 0-1.818-.139-2.306-.4s-.871-.644-1.131-1.131-.4-.972-.4-2.306V7.737c0-1.334.139-1.818.4-2.306s.643-.87 1.131-1.131.972-.4 2.306-.4zm6.193 5.936c-.001-.783.002-1.567-.003-2.35a.597.597 0 0 0-.458-.577.59.59 0 0 0-.683.328.907.907 0 0 0-.062.352c-.004 1.492-.003 2.984-.002 4.476 0 .06.002.121.008.181a.592.592 0 0 0 .468.508c.397.076.728-.196.731-.611.004-.768.001-1.537.001-2.307zm-3.733.687c0 .274-.005.521.002.768.003.093-.031.144-.106.19a2.168 2.168 0 0 1-.905.292c-.819.097-1.572-.333-1.872-1.081a2.213 2.213 0 0 1-.125-1.14 1.76 1.76 0 0 1 1.984-1.513c.359.05.674.194.968.396a.616.616 0 0 0 .513.112.569.569 0 0 0 .448-.464c.055-.273-.055-.484-.278-.637-.791-.545-1.677-.659-2.583-.464-2.006.432-2.816 2.512-2.08 4.196.481 1.101 1.379 1.613 2.546 1.693.793.054 1.523-.148 2.2-.56.265-.161.438-.385.447-.698.014-.522.014-1.045.001-1.568-.007-.297-.235-.549-.51-.557a37.36 37.36 0 0 0-1.64-.001c-.21.004-.394.181-.446.385a.494.494 0 0 0 .217.559.714.714 0 0 0 .313.088c.296.011.592.004.906.004zm6.477-2.519h.171c.811 0 1.623.002 2.434-.001.383-.001.632-.286.577-.654-.041-.274-.281-.455-.611-.455h-3.074c-.474 0-.711.237-.711.713v4.479c0 .243.096.436.306.56.41.241.887-.046.896-.545.009-.504.002-1.008.002-1.511v-.177h.169c.7 0 1.4.001 2.1-.001a.543.543 0 0 0 .535-.388c.071-.235-.001-.488-.213-.611a.87.87 0 0 0-.407-.105c-.667-.01-1.335-.005-2.003-.005h-.172V8.004z\"/></svg>";
        case 7:
            return "<svg style='width:16px;height:16px;float:left;margin-right:5px;margin-top: -2px;' xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 13 20\"><path fill=\"currentColor\" fill-opacity=\".4\" d=\"M6.487 3.305A4.659 4.659 0 0 0 1.8 7.992c0 3.482 4.687 8.704 4.687 8.704s4.687-5.222 4.687-8.704a4.659 4.659 0 0 0-4.687-4.687zm0 6.36c-.937 0-1.674-.737-1.674-1.674s.736-1.674 1.674-1.674 1.674.737 1.674 1.674c0 .938-.737 1.674-1.674 1.674z\"/></svg>";
        case 9:
            return "<svg style='width:16px;height:16px;float:left;margin-right:5px;margin-top: -2px;'xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 14 20\"><path fill=\"currentColor\" fill-opacity=\".4\" d=\"M6.844 9.975a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm5.759 3.087c-.884-.845-3.136-1.587-5.721-1.587-2.584 0-4.739.742-5.622 1.587-.203.195-.26.464-.26.746v1.679h12v-1.679c0-.282-.193-.552-.397-.746z\"></path></svg>";
        case 26:
            return "<svg style='width:16px;height:16px;float:left;margin-right:5px;margin-top: -2px;' xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 16 16\"><path id=\"Combined-Shape_1_\" fill=\"currentColor\" fill-opacity=\".4\" d=\"M9.179 14.637c.061-.14.106-.29.135-.45.031-.171.044-.338.049-.543a9.05 9.05 0 0 0 .003-.233l.001-.067v-.072l.002-.216c.01-.364.032-1.205.08-1.473.052-.287.136-.538.255-.771a2.535 2.535 0 0 1 1.125-1.111 2.8 2.8 0 0 1 .786-.255c.27-.048 1.098-.07 1.487-.08l.152.001h.047l.325-.004a3.63 3.63 0 0 0 .554-.048 2.06 2.06 0 0 0 .494-.151 4.766 4.766 0 0 1-1.359 2.429 143.91 143.91 0 0 1-2.057 1.924 4.782 4.782 0 0 1-2.079 1.12zm-1.821.16l-.474.012a9.023 9.023 0 0 1-1.879-.11 4.747 4.747 0 0 1-1.314-.428 4.376 4.376 0 0 1-1.123-.807 4.354 4.354 0 0 1-.816-1.11 4.584 4.584 0 0 1-.434-1.303 8.783 8.783 0 0 1-.12-1.356 29.156 29.156 0 0 1-.009-.617c-.002-.206-.002-.37-.002-.736v-.674l.001-.549.001-.182c.001-.223.004-.426.009-.62a8.69 8.69 0 0 1 .121-1.358c.087-.476.229-.903.434-1.301a4.399 4.399 0 0 1 1.936-1.916 4.7 4.7 0 0 1 1.315-.429 8.926 8.926 0 0 1 1.379-.12c.72-.009.989-.011 1.359-.011h.528c.896.003 1.143.005 1.366.011.55.015.959.046 1.371.12.482.085.913.226 1.314.428a4.396 4.396 0 0 1 1.937 1.915c.206.4.348.827.434 1.302.075.412.107.819.121 1.356.006.198.009.402.01.619v.024c0 .069-.001.132-.003.194a2.61 2.61 0 0 1-.033.391.902.902 0 0 1-.494.677 1.05 1.05 0 0 1-.29.094 2.734 2.734 0 0 1-.395.033l-.311.004h-.039l-.163-.001c-.453.012-1.325.036-1.656.096a3.81 3.81 0 0 0-1.064.348 3.541 3.541 0 0 0-.911.655c-.267.263-.49.566-.661.899-.166.324-.281.67-.351 1.054-.06.33-.085 1.216-.096 1.636l-.002.23v.069l-.001.067c0 .074-.001.143-.003.213-.004.158-.014.28-.033.388a.902.902 0 0 1-.494.676 1.054 1.054 0 0 1-.289.093 1.335 1.335 0 0 1-.176.024z\"/></svg>";

    }
}

function doTruncarStr(str, size) {
    if (str == undefined || str == 'undefined' || str == '' || size == undefined || size == 'undefined' || size == '') {
        return str;
    }
    var shortText = str;
    if (str.length >= size + 3) {
        shortText = str.substring(0, size).concat('...');
    }
    return shortText;
}

function FormatShortTime(timestamp) {
    var date = new Date(timestamp * 1000);
    var hours = date.getHours();
    var minutes = "0" + date.getMinutes();
    return hours + ':' + minutes.substr(-2);
}

const _MS_PER_DAY = 1000 * 60 * 60 * 24;

// a and b are javascript Date objects
function dateDiffInDays(a, b) {
    // Discard the time and time-zone information.
    const utc1 = Date.UTC(a.getFullYear(), a.getMonth(), a.getDate());
    const utc2 = Date.UTC(b.getFullYear(), b.getMonth(), b.getDate());
    return Math.floor((utc2 - utc1) / _MS_PER_DAY);
}

function FormatShortDate(timestamp) {

    var date = new Date(timestamp * 1000);

    var today = new Date();

    var currentDate = today.getFullYear() + '/' + today.getMonth() + '/' + today.getDate();
    var dt = date.getFullYear() + '/' + date.getMonth() + '/' + date.getDate();

    if (currentDate == dt) {
        return "Hoje";
    } else {
        var diff = dateDiffInDays(date, today);
        if (diff < 3) {
            switch (diff) {
                case 1:
                    return "Ontem";
                case 2:
                    return "Anteontem";
            }
        } else {
            if (diff < 7) {
                var semana = ["Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sábado"];
                return semana[date.getDay()];
            } else {
                return date.getDate() + '/' + date.getMonth() + '/' + date.getFullYear();
            }
        }
    }
}

$(document).ready(function () {
    $(".talkall-widget .caption").click(function () {
        if ($("#talkall-widget").attr('class') == 'talkall-widget') {
            $(".messages").show();
            $("#talkall-widget").attr('class', 'talkall-show');
        } else {
            $(".messages").hide();
            $("#talkall-widget").attr('class', 'talkall-widget');
        }
    });
    $(".talkall-show .caption .img").click(function () {
        alert('fechar');
    });
    $("#btn-open").click(function () {
        if (bConected) {
            alert('ok');
        }
    });
    $(".input-text").live("keypress", function (e) {

        if (bConected) {

            if ($(".input-text").val().length > 0) {

                if (e.keyCode == 13) {

                    e.preventDefault();

                    var json = { Cmd: "TextMessage", "to": widget_chat, "data": $(".input-text").val() };
                    socket.send(JSON.stringify(json));

                    var json = { Cmd: "Read", "key_remote_id": widget_chat };
                    socket.send(JSON.stringify(json));

                    $(".input-text").val("");
                }
            } else {
                if (e.keyCode == 13) {
                    e.preventDefault();
                }
            }
        }
    });

    TAWebsocket();
});

function doneTyping() {
    if (bConected) {
        var json = { Cmd: "Presence", "key_remote_id": widget_chat, "type": "available" };
        socket.send(JSON.stringify(json));
    }
}

function message(json, event) {

    var data = "";

    switch (parseInt(json.media_type)) {
        case 1:
            data = json.data;
            break;
        case 2:
            data = json.media_duration;
            break;
        case 3:
            data = json.media_caption;
            break;
        case 4:
            data = json.media_title;
            break;
        case 19:
            data = json.data;
            break;
        case 26:
            data = json.data;
            break;
    }

    var textData = FormatShortDate(json.creation);

    if (last_date != textData) {

        $("." + textData.replace("/", "_").replace("/", "_")).remove();

        var item = document.createElement("div");
        item.className = "item " + textData.replace("/", "_").replace("/", "_");
        item.dataset.index = json.creation;
        item.id = json.creation;

        var message = document.createElement("div");
        message.className = "information";

        var bottom = document.createElement("div");
        bottom.className = "bottom";
        bottom.innerHTML = "<span>" + FormatShortDate(json.creation) + "</span>";

        message.appendChild(bottom);
        item.appendChild(message);

        switch (event) {
            case 'Add':
                $(".messages").append(item);
                break;
            case 'Reply':
                $(".messages").append(item);
                break;
        }

        last_date = textData;
    }

    var item = document.createElement("div");
    item.className = "item";
    item.dataset.index = json.creation;
    item.id = json.token;

    var message = document.createElement("div");
    var bottom = document.createElement("div");
    bottom.className = "bottom";

    //var time = document.createElement("span");
    //time.className = "time";
    //time.textContent = FormatShortTime(json.creation);

    switch (parseInt(json.media_type)) {
        case 1:
            message.className = "textMessage";

            var body = document.createElement("div");
            body.className = "body";

            var span = document.createElement("span");
            span.innerHTML = json.data;

            body.append(span);
            message.appendChild(body);

            break;
        case 2:
            message.className = "AudioMessage";

            var audio = document.createElement("audio");
            audio.controls = true;
            audio.style.width = "100%";
            audio.src = json.media_url;
            audio.type = "audio/mp3";

            message.appendChild(audio);

            break;
        case 3:
            message.className = "ImageMessage";
            message.dataset.url = json.media_url;

            var image = document.createElement("img");
            image.src = json.media_url;

            var caption = document.createElement("span");
            caption.textContent = json.media_caption;

            message.appendChild(image);
            message.appendChild(caption);

            break;
        case 4:
            message.className = "DocumentMessage";
            message.dataset.url = json.media_url;

            var thumbnail = document.createElement("div");
            thumbnail.className = "thumbnail";

            var img = document.createElement("img");
            img.src = 'data:image/jpeg;base64,' + json.thumb_image;

            var body = document.createElement("div");
            body.className = "body";

            var title = document.createElement("span");
            title.textContent = json.title;

            thumbnail.appendChild(img);
            body.appendChild(title);

            message.appendChild(thumbnail);
            message.appendChild(body);

            break;
        case 8:
            message.className = "textMessage";
            break;
        case 9:
            message.className = "ContactMessage";

            var body = document.createElement("div");
            body.className = "body";

            var img = document.createElement("img");
            img.src = "assets/img/avatar.svg";
            img.style.width = "32px";

            var caption = document.createElement("span");
            caption.textContent = json.media_caption;

            var buttom = document.createElement("div");
            buttom.className = "buttom";

            var input = document.createElement("input");
            input.type = "button";
            input.value = "Enviar mensagem";
            input.style.width = "100%";
            input.style.padding = "10px";

            buttom.appendChild(input);

            body.appendChild(img);
            body.appendChild(caption);

            message.appendChild(body);
            message.appendChild(buttom);

            break;
        case 10:
            message.className = "ZipMessage";
            message.dataset.url = json.media_url;

            var a = document.createElement("a");
            a.download = json.file_name;
            a.href = json.media_url;

            var img = document.createElement("img");
            img.src = "assets/img/download.svg";

            var body = document.createElement("div");
            body.className = "body";

            var title = document.createElement("span");
            title.textContent = json.title;

            body.appendChild(a);
            a.appendChild(title);
            a.appendChild(img);

            message.appendChild(body);
            break;
        case 19:
        case 20:
        case 21:
        case 22:
        case 23:
            message.className = "information";
            break;
    }

    switch (parseInt(json.key_from_me)) {
        case 1:
            message.style.cssFloat = 'left';
            message.style.background = '#f1f0f0';
            message.style.color = 'black';
            break;
        case 2:
            message.style.cssFloat = 'right';
            switch (parseInt(json.msgStatus)) {
                case 0:
                    bottom.innerHTML = msgWait();
                    break;
                case 1:
                    bottom.innerHTML = msgSend();
                    break;
                case 2:
                    bottom.innerHTML = msgReceived();
                    break;
                case 3:
                case 4:
                    bottom.innerHTML = msgRead();
                    break;
            }
            break;
    }

    switch (parseInt(json.media_type)) {
        case 1:
        case 2:
        case 3:
        case 4:
        case 5:
        case 6:
        case 7:
        case 8:
        case 9:
        case 10:
        case 26:
            //bottom.appendChild(time);
            message.appendChild(bottom);
            item.appendChild(message);
    }

    switch (event) {
        case 'Add':
            $(".messages").append(item);
            break;
        case 'Reply':
            $(".messages").append(item);
            break;
    }

    $(".messages").scrollTop(document.getElementsByClassName("messages")[1].scrollHeight);

    available(json.key_remote_id);
}

function msgWait() {
    return "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 15' width='16' height='15'><path fill='#0' d='M9.75 7.713H8.244V5.359a.5.5 0 0 0-.5-.5H7.65a.5.5 0 0 0-.5.5v2.947a.5.5 0 0 0 .5.5h.094l.003-.001.003.002h2a.5.5 0 0 0 .5-.5v-.094a.5.5 0 0 0-.5-.5zm0-5.263h-3.5c-1.82 0-3.3 1.48-3.3 3.3v3.5c0 1.82 1.48 3.3 3.3 3.3h3.5c1.82 0 3.3-1.48 3.3-3.3v-3.5c0-1.82-1.48-3.3-3.3-3.3zm2 6.8a2 2 0 0 1-2 2h-3.5a2 2 0 0 1-2-2v-3.5a2 2 0 0 1 2-2h3.5a2 2 0 0 1 2 2v3.5z'></path></svg>";
}

function msgSend() {
    return "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 15' width='16' height='15'><path fill='#0' d='M10.91 3.316l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.879a.32.32 0 0 1-.484.033L1.891 7.769a.366.366 0 0 0-.515.006l-.423.433a.364.364 0 0 0 .006.514l3.258 3.185c.143.14.361.125.484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z'></path></svg>";
}

function msgReceived() {
    return "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 15' width='16' height='15'><path fill='#0' d='M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.879a.32.32 0 0 1-.484.033l-.358-.325a.319.319 0 0 0-.484.032l-.378.483a.418.418 0 0 0 .036.541l1.32 1.266c.143.14.361.125.484-.033l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.879a.32.32 0 0 1-.484.033L1.891 7.769a.366.366 0 0 0-.515.006l-.423.433a.364.364 0 0 0 .006.514l3.258 3.185c.143.14.361.125.484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z'></path></svg>";
}

function msgRead() {
    return "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 15' width='16' height='15'><path fill='#4FC3F7' d='M15.01 3.316l-.478-.372a.365.365 0 0 0-.51.063L8.666 9.879a.32.32 0 0 1-.484.033l-.358-.325a.319.319 0 0 0-.484.032l-.378.483a.418.418 0 0 0 .036.541l1.32 1.266c.143.14.361.125.484-.033l6.272-8.048a.366.366 0 0 0-.064-.512zm-4.1 0l-.478-.372a.365.365 0 0 0-.51.063L4.566 9.879a.32.32 0 0 1-.484.033L1.891 7.769a.366.366 0 0 0-.515.006l-.423.433a.364.364 0 0 0 .006.514l3.258 3.185c.143.14.361.125.484-.033l6.272-8.048a.365.365 0 0 0-.063-.51z'></path></svg>";
}

function processAck(json) {
    switch (parseInt(json.ack)) {
        case 1:
            $("#" + json.token + " svg").html(msgSend());
            break;
        case 2:
            $("#" + json.token + " svg").html(msgReceived());
            break;
        case 3:
        case 4:
            $("#" + json.token + " svg").html(msgRead());
            break;
    }
}

function TAWebsocket() {

    widget_client = localStorage.getItem("widget-client");

    try {
        socket = new WebSocket($("#webserver").val() + ":28192");

        socket.onopen = function () {

            if (localStorage.getItem("widget-client") == null) {

                var json = { Cmd: "login", "account": widget_token, "pw": widget_token };
                socket.send(JSON.stringify(json));

            } else {
                bConected = true;
                var json = { Cmd: "login", "account": widget_token, "pw": widget_client };
                socket.send(JSON.stringify(json));
                $(".messages").html("");
            }
        }

        socket.onmessage = function (msg) {
            console.log(msg.data);
            var json = JSON.parse(msg.data);
            switch (json.Cmd) {
                case 'login':
                    if (json.status == 200) {
                        if (widget_client == null) {
                            var json = { Cmd: "init" };
                            socket.send(JSON.stringify(json));
                        } else {
                            if (bConected == true) {
                                $("#form-contact").hide();
                                var json = { Cmd: "action", "key_remote_id": widget_client, "presence": { "type": "available" } };
                                socket.send(JSON.stringify(json));
                            }
                        }
                    }
                    break;
                case 'init':
                    widget_client = json.key_remote_id;
                    localStorage.setItem("widget-client", widget_client);

                    var json = { Cmd: "login", "account": widget_token, "pw": widget_client };
                    socket.send(JSON.stringify(json));

                    bConected = true;
                    break;
                case 'Message':
                    if (json.items.length > 0) {
                        creation = json.items[json.items.length - 1].creation;
                        for (var i = json.items.length - 1; i >= 0; i--) {
                            message(
                                json.items[i],
                                json.event,
                            );
                        }
                    }

                    $('.messages').find('.item').sort(function (a, b) {
                        return $(a).attr('data-index') - $(b).attr('data-index');
                    }).appendTo('.messages');

                    $(".messages").scrollTop(document.getElementsByClassName("messages")[1].scrollHeight);

                    break;
                case 'Ack':
                    processAck(json);
                    break;
                case 'Msg':
                    message(
                        json,
                        json.event,
                    );
                    break;
            }
        }
        socket.onclose = function () {
            bConected = false;
            console.log("Conexão fechada");
        }
    } catch (exception) {
        bConected = false;
        console.log(exception);
    }
}
