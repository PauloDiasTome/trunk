var id_chat;
var load = true;
var tokenMessage;
var tailOut = true;
var last_date = "";
var meuId;

function FormatShortDate(timestamp) {
    let date = new Date(timestamp * 1000);
    let today = new Date();

    let currentDate = today.getFullYear() + '/' + today.getMonth() + '/' + today.getDate();
    let dt = date.getFullYear() + '/' + date.getMonth() + '/' + date.getDate();

    if (currentDate == dt) {
        return GLOBAL_LANG.report_waiting_service_dt_diff_today;
    } else {
        let diff = this.dateDiffInDays(date, today);
        if (diff < 3) {
            switch (diff) {
                case 1:
                    return GLOBAL_LANG.report_waiting_service_dt_diff_yesterday;
                case 2:
                    return GLOBAL_LANG.report_waiting_service_dt_diff_before_yesterday;
            }
        } else {
            if (diff < 7) {
                let semana = [GLOBAL_LANG.report_waiting_service_dt_diff_sun, GLOBAL_LANG.report_waiting_service_dt_diff_mon, GLOBAL_LANG.report_waiting_service_dt_diff_tue, GLOBAL_LANG.report_waiting_service_dt_diff_wed, GLOBAL_LANG.report_waiting_service_dt_diff_thu, GLOBAL_LANG.report_waiting_service_dt_diff_fri, GLOBAL_LANG.report_waiting_service_dt_diff_sat];
                return semana[date.getDay()];
            } else {

                let day, month;

                if (date.getDate() < 10) day = "0" + date.getDate(); else day = date.getDate();
                if (date.getMonth() < 10) month = "0" + (parseInt(date.getMonth()) + 1); else month = date.getMonth();

                return day + "/" + month + "/" + date.getFullYear();
            }
        }
    }
}

function FormatShortTime(timestamp) {
    let date = new Date(timestamp * 1000);
    let hours = date.getHours();
    let minutes = "0" + date.getMinutes();
    return hours + ':' + minutes.substr(-2);
}

const _MS_PER_DAY = 1000 * 60 * 60 * 24;

function dateDiffInDays(a, b) {
    const utc1 = Date.UTC(a.getFullYear(), a.getMonth(), a.getDate());
    const utc2 = Date.UTC(b.getFullYear(), b.getMonth(), b.getDate());
    return Math.floor((utc2 - utc1) / _MS_PER_DAY);
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

function message(json) {

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
        case 18:
            if (json.key_from_me == 1) {
                data = GLOBAL_LANG.report_waiting_service_function_message_if;
            } else {
                data = GLOBAL_LANG.report_waiting_service_function_message_else;
            }
            break;
        case 19:
            data = json.data;
            break;
        case 26:
            data = json.data;
            break;
        case 27:
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

        $(".chat").append(item);

        last_date = textData;
    }

    var item = document.createElement("div");
    item.className = "item";
    item.dataset.index = json.creation;
    item.id = json.token;

    var message = document.createElement("div");
    var bottom = document.createElement("div");
    bottom.className = "bottom";

    if (json.participant != null && json.participant != "0" && json.media_type < 3) {

        var participant = document.createElement("span");
        participant.style = "font-size:12px; color: " + color[0];
        // participant.innerHTML = json.participant + "<br>";

        message.appendChild(participant);
    }

    var time = document.createElement("span");
    time.className = "time";
    time.textContent = FormatShortTime(json.creation);

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
            message.className = "audioMessage";

            var audio = document.createElement("audio");
            audio.controls = true;
            audio.style.width = "100%";
            audio.src = json.media_url;
            audio.type = "audio/mp3";

            message.appendChild(audio);
            break;
        case 3:
            message.className = "imageMessage";
            message.dataset.url = json.media_url;

            var container = document.createElement("div");
            container.className = "image-container";

            var image = document.createElement("img");
            image.src = json.media_url;

            var caption = document.createElement("span");
            caption.textContent = json.media_caption == 0 ? "" : json.media_caption;

            container.appendChild(image);
            message.appendChild(container);
            message.appendChild(caption);
            break;
        case 4:
            message.className = "documentMessage";
            message.dataset.url = json.media_url;

            var body = document.createElement("div");
            body.className = "body";

            var title = document.createElement("span");
            title.textContent = json.title;

            body.appendChild(title);
            message.appendChild(body);
            break;
        case 5:
            message.className = "videoMessage";
            message.dataset.url = json.media_url;

            var thumbnail = document.createElement("div");
            thumbnail.className = "thumbnail";

            var img = document.createElement("img");
            img.alt = 'Video';
            img.src = '';

            var body = document.createElement("div");
            body.className = "body";

            var title = document.createElement("span");
            title.textContent = json.media_caption;

            thumbnail.appendChild(img);
            body.appendChild(title);

            message.appendChild(thumbnail);
            message.appendChild(body);
            break;
        case 6:
            message.className = "gifMessage";

            var container = document.createElement("div");
            container.className = "video-container";

            var vid = document.createElement("video");
            vid.src = json.media_url;
            vid.type = "video/mp4";

            var body = document.createElement("div");
            body.className = "body";

            var caption = document.createElement("span");
            caption.textContent = json.media_caption == 0 ? "" : json.media_caption;

            body.appendChild(caption);

            container.appendChild(vid);
            message.appendChild(container);
            if (json.media_caption != 0) message.appendChild(body);
            break;
        case 7:
            message.className = "locationMessage";

            var thumbnail = document.createElement("div");
            thumbnail.className = "thumbnail";

            var a = document.createElement("a");
            a.href = "http://maps.google.com/maps?q=" + json.latitude + "," + json.longitude + "&hl=pt-BR";
            a.target = "_blank";

            var img = document.createElement("img");
            img.src = 'data:image/jpeg;base64,' + json.thumb_image;
            img.style.width = "100%";

            var body = document.createElement("div");
            body.className = "body";

            var title = document.createElement("span");
            title.textContent = json.title;

            a.appendChild(img);
            thumbnail.appendChild(a);
            body.appendChild(title);

            message.appendChild(thumbnail);
            message.appendChild(body);
            break;
        case 9:
            const data = JSON.parse(json.data);

            message.classList.add("ContactMessage");

            var body = document.createElement("div");
            body.className = "body";

            var img = document.createElement("img");
            img.src = location.origin + "/assets/img/avatar.svg";
            img.style.width = "64px!important";

            var caption = document.createElement("span");
            caption.textContent = data.firstName && data.firstName !== data.cellPhone ? truncateString(data.firstName, 20) : "Contato";
            var buttom = document.createElement("div");
            buttom.className = "buttom";

            var input = document.createElement("input");
            input.type = "button";
            input.value = data.cellPhone ? data.cellPhone : '';
            input.style.width = "100%";
            input.style.backgroundColor = "transparent";
            input.style.padding = "10px";

            buttom.appendChild(input);
            body.appendChild(img);
            body.appendChild(caption);

            message.appendChild(body);
            message.appendChild(buttom);
            break;
        case 10:
            message.className = "zipMessage";
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
        case 18:
            message.className = "revoke";
            switch (parseInt(json.key_from_me)) {
                case 1:
                    message.innerHTML = "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 17 19'><path fill='currentColor' d='M12.629 12.463a5.17 5.17 0 0 0-7.208-7.209l7.208 7.209zm-1.23 1.229L4.191 6.484a5.17 5.17 0 0 0 7.208 7.208zM8.41 2.564a6.91 6.91 0 1 1 0 13.82 6.91 6.91 0 0 1 0-13.82z'></path></svg><span>Essa mensagem foi apagada!</span>";
                    break;
                case 2:
                    message.innerHTML = "<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 17 19'><path fill='currentColor' d='M12.629 12.463a5.17 5.17 0 0 0-7.208-7.209l7.208 7.209zm-1.23 1.229L4.191 6.484a5.17 5.17 0 0 0 7.208 7.208zM8.41 2.564a6.91 6.91 0 1 1 0 13.82 6.91 6.91 0 0 1 0-13.82z'></path></svg><span>Você apagou essa mensagem!</span>";
                    break;
            }
            break;
        case 19:
        case 20:
        case 21:
        case 22:
        case 23:
        case 24:
            message.className = "information";
            break;
        case 26:
            message.className = "StickerMessage";
            message.style.boxShadow = ' 0 1.2px 3px rgb(0 0 0 / 30%)';

            var sticker = document.createElement("img");
            sticker.src = json.media_url;
            sticker.style.background = "transparent";
            sticker.style.width = "100%";

            message.appendChild(sticker);
            break;
        case 27:
            message.className = "templateMessage";

            var body = document.createElement("div");
            body.className = "body";

            var span = document.createElement("span");
            span.innerHTML = json.data;

            body.append(span);
            message.appendChild(body);
            break;

    }

    switch (parseInt(json.key_from_me)) {
        case 1:
            switch (parseInt(json.media_type)) {
                case 19:
                case 20:
                case 21:
                case 22:
                case 23:
                    break;
                default:
                    message.className += " messageLeft";

                    if (tailOut) {
                        let tailOutMessage = document.createElement("span");
                        message.append(tailOutMessage);

                        tailOutMessage.className = "tailOutMessageLeft";
                        tailOut = false;
                    }
                    break;
            }
            message.style.cssFloat = 'left';
            message.style.background = '#dcf8c6';

            break;
        case 2:
            message.className += " messageRight";
            message.style.cssFloat = 'right';

            if (!tailOut) {
                let tailOutMessage = document.createElement("span");
                tailOutMessage.className = "tailOutMessageRight";

                message.append(tailOutMessage);
                tailOut = true;
            }

            switch (parseInt(json.status)) {
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
        case 27:
            bottom.prepend(time);
            message.appendChild(bottom);
            item.appendChild(message);
            break;
        case 18:
            item.appendChild(message);
            break;
        case 19:
            message.style.background = "transparent";
            var span = document.createElement("span");
            span.innerHTML = json.data;

            message.appendChild(span);
            item.appendChild(message);
            break;
        case 20:
            message.style.background = "transparent";
            var span = document.createElement("span");
            span.innerHTML = GLOBAL_LANG.report_waiting_service_span_innerhtml_service_transfer;
            message.appendChild(span);
            item.appendChild(message);
            break;
        case 21:
            message.style.background = "transparent";
            var span = document.createElement("span");
            span.innerHTML = GLOBAL_LANG.report_waiting_service_span_innerhtml_service_satart;

            message.appendChild(span);
            item.appendChild(message);
            break;
        case 22:
            message.style.background = "transparent";
            var span = document.createElement("span");
            span.innerHTML = GLOBAL_LANG.report_waiting_service_span_innerhtml_service_on_hold;

            message.appendChild(span);
            item.appendChild(message);
            break;
        case 23:
            message.style.background = "transparent";
            var span = document.createElement("span");
            span.innerHTML = GLOBAL_LANG.report_waiting_service_span_innerhtml_service_close;

            message.appendChild(span);
            item.appendChild(message);
            break;
        case 24:
            message.style.background = "transparent";
            var span = document.createElement("span");
            span.innerHTML = GLOBAL_LANG.report_waiting_service_span_innerhtml_protocol + json.data;

            message.appendChild(span);
            item.appendChild(message);
            break;
    }
    $(".chat").append(item);
    // $(".messages").scrollTop($(".messages").prop("scrollHeight") + 999);

}


function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "waiting_service/find",
            type: "POST",
            data: {
                text: $("#search").val(),
                sector: $('#verify-select2').val() == "2" ? "" : $('#multiselect').val(),
                dt_start: $('#dt-start').val(),
                dt_end: $('#dt-end').val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'creation'
            },
            {
                mData: 'contact'
            },
            {
                mData: 'name'
            },
            {
                mData: 'user'
            },
            {
                mData: 'sector'
            },
        ],
        columnDefs: [
            {
                targets: 5,
                render: function (data, type, full, meta) {

                    let time = "";
                    let hours = Math.floor(full.minutes / 60);
                    let minutes = full.minutes % 60;

                    if (hours > 72) {

                        time = hours / 24;
                        time = time.toString().split(".")[0] + " dias";
                    } else {
                        if (hours < 10) hours = "0" + hours;
                        if (minutes < 10) minutes = "0" + minutes;

                        time = hours.toString() + "h:" + minutes.toString();
                    }

                    return time;
                }
            },
            {
                targets: 6,
                render: function (data, type, full, meta) {
                    return "<i class='fas fa-clock' title='" + GLOBAL_LANG.report_waiting_columndefs_target6_title_service_waiting + "' style='background-color: red;color: #fff;padding:4.5px 4px 4.5px 4.9px; border-radius: 30px;'></i>";
                }
            },
            {
                targets: 7,
                render: function (data, type, full, meta) {

                    return `<div class="dropleft">
                                    <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                        <i class="fa fa-ellipsis-v"></i>
                                        <div class="ripple-container"></div>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="multiDropdownMenu">
                                        <a href='#' id="${full.id_chat_list}" data-name="${full.name.trim() == '' || full.name == null ? full.contact.split("-")[0] : full.name}" data-number="${full.contact}" class="dropdown-item table-btn-comment" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block">     
                                                <i class="far fa-comments"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.report_call_service_column_action_view}</span>
                                        </a>
                                    </div>
                                </div>`
                }
            },
            {
                orderable: true,
                targets: [0, 1, 2, 3, 4, 5, 6]
            }
        ],

        pagingType: "numbers",
        pageLength: 10,
        destroy: true,
        fixedHeader: true,
        responsive: true,
        lengthChange: true,
        searching: false,
        paginate: true,
        info: true,
        language: {
            url: `${document.location.origin}/assets/lang/${localStorage.getItem("language")}.json`
        },

        drawCallback: function (settings) {
            if (settings.json.recordsTotal == 0) {
                $("#modalExport").attr('disabled', true);
            } else {
                $("#modalExport").attr('disabled', false);
            }
        }

    });
}


$(document).ready(function () {

    $('#search').on('keyup', (e) => {
        if (e.which == 13) {
            document.getElementById("input-search").value = e.target.value;
            find();
        }
    });
    find();


    $("#datatable-basic").on("click", ".table-btn-comment", function () {

        document.getElementById("modal-number-contact").innerHTML = this.attributes[3].nodeValue;
        document.getElementById("modal-name-contact").innerHTML = this.attributes[2].nodeValue;
        document.getElementById("chat-settings").addEventListener("click", chatSettings);

        load = true;
        id_chat = this.id;
        meuId = this.id;

        statusModal(id_chat);

        $.post("interaction/getMessages", {
            id: id_chat,
            creation: 0,
            csrf_talkall: Cookies.get("csrf_cookie_talkall")
        }, (data) => {

            $(".chat").find(".item").remove();
            for (var i = data.length - 1; i >= 0; i--) message(data[i]);

            $("#modal-chat").modal();
            $(".modal-settings-chat").remove()
            $(".chat").scrollTop($(".chat").prop("scrollHeight"));
        });
    });


    $(".chat").on("scroll", function () {

        if (this.scrollTop == 0) {
            if (load) {
                let loading = document.createElement("div");
                loading.id = "load_container_svg";
                loading.innerHTML = `<i><img src="/assets/img/loads/loading_2.gif" class="load-img"></i>`;

                $("#load_container_svg").remove();
                $(".chat").prepend(loading);
            }

            let creation = $(".chat").find(".item").first()[0].attributes[1].nodeValue;
            tokenMessage = $(".chat").find(".item")[1].attributes[2].nodeValue;

            $.post("interaction/getMessages", {
                id: id_chat,
                creation: creation,
                csrf_talkall: Cookies.get("csrf_cookie_talkall")
            }, function (data) {

                if (data.length <= 1) {
                    load = false;
                } else {
                    for (var i = data.length - 1; i >= 0; i--) message(data[i]);
                }

                $(".chat").find('.item').sort(function (a, b) {
                    return $(a).attr('data-index') - $(b).attr('data-index');
                }).appendTo(".chat");

                if (load) $("#" + tokenMessage)[0].scrollIntoView();
                $("#load_container_svg").remove();

            });
        }
    });

    // Exportar conversas no click no ícone da datatable
    $("#datatable-basic").on("click", ".exportTalkClass", function () {
        meuId = "";
        meuId = this.id;
        $("#modal-export-talk").modal();
    });


    $("#modal-content-chat").on("click", () => $(".modal-settings-chat").remove());
    $("#modalFilter").one("click", () => modalFilter());
    $('#sendEmailExport').on('click', () => modalExport());
    $(".sendEmailExportTalk").on("click", () => modalExportTalk());

});


function chatSettings(e) {

    let modal = document.createElement("div");
    modal.className = "modal-settings-chat";

    let cluttered_list = document.createElement("ul");
    cluttered_list.className = "modal-settings-ul";

    let wallpaper = document.createElement("li");
    wallpaper.innerHTML = GLOBAL_LANG.report_waiting_service_fuction_chatsetting_background;

    let export_chat = document.createElement("li");
    export_chat.innerHTML = GLOBAL_LANG.report_waiting_service_fuction_chatsetting_export;
    export_chat.id = "exportTalkChat";

    // cluttered_list.appendChild(wallpaper);
    cluttered_list.appendChild(export_chat);
    modal.appendChild(cluttered_list);

    $(".modal-settings-chat").remove();
    e.target.parentNode.appendChild(modal);

    document.getElementById("exportTalkChat").addEventListener("click", function () {
        $("#modal-export-talk").modal();
    });
    e.stopPropagation();
}


function statusModal() {

    const status_modal = document.getElementById("modal-status");

    status_modal.className = "diamond bg-danger";
    status_modal.innerHTML = GLOBAL_LANG.report_waiting_service_fuction_statusmodal_waiting_service;
    status_modal.style.top = "-18px";
    status_modal.style.fontSize = "12px";
}


function modalFilter() {

    if (!$(".msf_multiselect_container").hasClass("msf_multiselect_container")) {
        const msf_multiselect_container = document.querySelectorAll(".msf_multiselect_container");

        for (elm of msf_multiselect_container) elm.remove();

        var select = new MSFmultiSelect(
            document.querySelector('#multiselect'), {
            theme: 'theme1',
            selectAll: true,
            searchBox: true,
            width: "100%",
            height: 47,
            placeholder: GLOBAL_LANG.report_waiting_service_filter_sector_placehoder,
            onChange: function (checked, value, instance) {
                if (select == "") select = value;
            },
        });
    }

    const search = document.getElementById("search");

    const check_search = document.getElementById("check-search");
    const input_search = document.getElementById("input-search");

    const check_select2 = document.getElementById("check-select2");
    const mult_select2 = document.getElementById("mult-select2");
    const verify_select2 = document.getElementById("verify-select2");

    const dt_start = document.getElementById("dt-start");
    const dt_end = document.getElementById("dt-end");

    const check_date = document.getElementById("check-date");
    const btn_search = document.getElementById("btn-search");

    check_search.checked = true;
    input_search.style.display = "block";

    check_search.addEventListener("click", () => {
        if (check_search.checked) {
            input_search.style.display = "block";
        }
        else {
            input_search.value = "";
            input_search.style.display = "none";
        }

    });

    check_select2.addEventListener("click", () => {
        if (check_select2.checked) {
            mult_select2.style.display = "block";
            verify_select2.value = "1";
        }
        else {
            mult_select2.style.display = "none";
            verify_select2.value = "2";
        }
    });

    check_date.addEventListener("click", () => {
        if (check_date.checked) {

            dt_start.value = "";
            dt_start.type = "text";
            dt_start.style.display = "block";
            dt_start.placeholder = GLOBAL_LANG.report_waiting_service_date_initial;

            dt_end.type = "text";
            dt_end.value = "";
            dt_end.disabled = true;
            dt_end.style.display = "block";

        } else {
            dt_start.style.display = "none";
            dt_end.style.display = "none";
        }

    });

    check_date.addEventListener("change", () => {
        if (check_date.checked == false) {

            dt_start.value = "";
            dt_start.type = "text";
            dt_start.placeholder = GLOBAL_LANG.contact_filter_period_placeholder_date_start;

            dt_end.type = "text";
            dt_end.value = "";

            btn_search.disabled = false;
        }

    });

    dt_start.addEventListener("focus", () => {

        dt_start.type = "date";
        btn_search.disabled = true;
    });

    dt_start.addEventListener("change", () => {

        if (dt_start.value != "") dt_end.disabled = false; else dt_end.disabled = true;

        dt_end.type = "date";
        btn_search.disabled = false;

        let date = new Date();
        let day = String(date.getDate()).padStart(2, '0');
        let month = String(date.getMonth() + 1).padStart(2, '0');
        let year = date.getFullYear();
        current = year + '-' + month + '-' + day;

        dt_end.value = current;
    });

    dt_end.addEventListener("change", () => {
        if (dt_end.value != "") btn_search.disabled = false; else btn_search.disabled = true;
    });

    btn_search.addEventListener("click", () => {
        search.value = input_search.value;
        find();
    });
}


function modalExport() {
    let column = $("#datatable-basic").DataTable().order()[0][0];
    let order = $("#datatable-basic").DataTable().order()[0][1];

    switch (column) {
        case 0:
            column = "creation";
            break;

        case 1:
            column = "key_remote_id";
            break;

        case 2:
            column = "full_name";
            break;

        case 3:
            column = "last_name";
            break;

        case 4:
            column = "sector";
            break;
    }

    $.get(`/export/xlsx?
        search=${$('#search').val() == "" ? $('#input-search').val() : $('#search').val()}
        &sector=${$('#verify-select2').val() == "2" ? "" : $('#multiselect').val()}
        &column=${column}
        &order=${order}
        &dt_start=${$('#dt-start').val()}
        &dt_end=${$('#dt-end').val()}
        &type=reportWaitingService`, function (response) {

        Swal.fire({
            title: GLOBAL_LANG.report_waiting_service_alert_export_title,
            text: GLOBAL_LANG.report_waiting_service_alert_export_text,
            type: 'success',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: GLOBAL_LANG.report_waiting_service_alert_export_confirmButtonText
        });
    });
}


function modalExportTalk(e) {

    $.get(`/export/xlsx?
    &id_chat_list=${meuId}
    &type=reportMessageHistory`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.report_waiting_service_alert_export_title,
                text: GLOBAL_LANG.report_waiting_service_alert_export_text,
                type: 'success',
                reverseButtons: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.report_waiting_service_alert_export_confirmButtonText
            });
            $('.swal2-container').css('z-index', 10000);
        }
    });
}

function truncateString(str, num) {
    if (str.length > num) {
        return str.slice(0, num) + "...";
    }
    else {
        return str;
    }
}