let pScroll = "";
let id_chat_list = 0;


function FormatShortDate(timestamp) {

    var date = new Date(timestamp * 1000);
    var today = new Date();

    var currentDate = today.getFullYear() + '/' + today.getMonth() + '/' + today.getDate();
    var dt = date.getFullYear() + '/' + date.getMonth() + '/' + date.getDate();

    if (currentDate == dt) {
        return GLOBAL_LANG.report_princess_function_fsd_today;
    } else {
        var diff = dateDiffInDays(date, today);
        if (diff < 3) {
            switch (diff) {
                case 1:
                    return GLOBAL_LANG.report_princess_function_fsd_yesterday;
                case 2:
                    return GLOBAL_LANG.report_princess_function_fsd_day_before_yesterday;
            }
        } else {
            if (diff < 7) {
                var semana = [GLOBAL_LANG.report_princess_function_fsd_sunday, GLOBAL_LANG.report_princess_function_fsd_monday, GLOBAL_LANG.report_princess_function_fsd_tuesday, GLOBAL_LANG.report_princess_function_fsd_wednesday, report_princess_function_fsd_thursday, GLOBAL_LANG.report_princess_function_fsd_friday, GLOBAL_LANG.report_princess_function_fsd_saturday];
                return semana[date.getDay()];
            } else {
                return date.getDate() + '/' + date.getMonth() + '/' + date.getFullYear();
            }
        }
    }
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


function message(json) {

    let textData = FormatShortDate(json.creation);

    const list_messages = document.getElementById("list-messages");

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
                data = GLOBAL_LANG.report_princess_function_message_if;
            } else {
                data = GLOBAL_LANG.report_princess_function_message_else;
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

    if (!textData) {

        $("." + textData.replace("/", "_").replace("/", "_")).remove();

        let item = document.createElement("div");
        item.className = "item " + textData.replace("/", "_").replace("/", "_");
        item.dataset.index = json.creation;

        let message = document.createElement("div");
        message.className = "information";

        let bottom = document.createElement("div");
        bottom.className = "bottom";
        bottom.innerHTML = "<span>" + FormatShortDate(json.creation) + "</span>";

        message.appendChild(bottom);
        item.appendChild(message);

        list_messages.append(item);
    }

    let item = document.createElement("div");
    item.className = "item elm";
    item.dataset.index = json.creation;
    item.id = "is_" + json.creation;

    let message = document.createElement("div");
    let bottom = document.createElement("div");
    bottom.className = "bottom";

    if (json.participant != null && json.participant != "0" && json.media_type < 3) {

        let participant = document.createElement("span");
        participant.style = "font-size:12px; color: " + color[0];
        participant.innerHTML = json.participant + "<br>";

        message.appendChild(participant);
    }

    let time = document.createElement("span");
    time.className = "time";
    time.textContent = FormatShortTime(json.creation);

    switch (parseInt(json.media_type)) {
        case 1:
        case 27:
            message.className = "textMessage";
            message.style.boxShadow = ' 0 4px 4px rgb(0 0 0 / 40%)';

            var body = document.createElement("div");
            body.className = "body message-text";

            var span = document.createElement("span");
            span.innerHTML = json.data != "0" ? json.data : "";

            body.append(span);
            message.appendChild(body);

            break;
        case 2:
            message.className = "AudioMessage";
            message.style.boxShadow = ' 0 4px 4px rgb(0 0 0 / 40%)';

            var audio = document.createElement("audio");
            audio.controls = true;
            audio.style.width = "300px";
            audio.style.marginTop = "10px";
            audio.style.marginRight = "-12px";
            audio.src = json.media_url;
            audio.type = "audio/mp3";

            message.appendChild(audio);

            break;
        case 3:
            message.className = "ImageMessage";
            message.style.boxShadow = ' 0 4px 4px rgb(0 0 0 / 40%)';

            var image = document.createElement("img");
            image.src = json.media_url;

            var caption = document.createElement("span");
            caption.textContent = json.media_caption != "0" ? json.media_caption : "";

            message.appendChild(image);
            message.appendChild(caption);

            break;
        case 4:
            message.className = "DocumentMessage";
            message.style.boxShadow = ' 0 4px 4px rgb(0 0 0 / 40%)';
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
        case 5:
            message.className = "VideoMessage";
            message.style.boxShadow = ' 0 4px 4px rgb(0 0 0 / 40%)';
            message.dataset.url = json.media_url;

            var thumbnail = document.createElement("div");
            thumbnail.className = "thumbnail";

            var img = document.createElement("img");
            img.src = 'data:image/jpeg;base64,' + json.thumb_image;

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
            message.className = "GifMessage";
            message.style.boxShadow = ' 0 4px 4px rgb(0 0 0 / 40%)';

            var vid = document.createElement("video");
            vid.src = json.media_url;
            vid.type = "video/mp4";

            var body = document.createElement("div");
            body.className = "body";

            var caption = document.createElement("span");
            caption.textContent = json.media_caption;

            body.appendChild(caption);
            body.appendChild(caption);

            message.appendChild(vid);
            message.appendChild(body);

            break;
        case 7:
            message.className = "LocationMessage";
            message.style.boxShadow = ' 0 4px 4px rgb(0 0 0 / 40%)';

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
        case 8:
            message.className = "textMessage";
            break;
        case 9:
            message.className = "ContactMessage";
            message.style.boxShadow = ' 0 4px 4px rgb(0 0 0 / 40%)';

            var body = document.createElement("div");
            body.className = "body";

            var img = document.createElement("img");
            img.src = "../../assets/img/avatar.svg";
            img.style.width = "55px";
            img.style.height = "55px";

            var caption = document.createElement("span");
            caption.textContent = json.media_caption;

            var buttom = document.createElement("div");
            buttom.className = "buttom";

            var number_contact = json.data;

            if (number_contact.split("waid")[1] !== undefined) {
                var separateNumber = number_contact.split("waid")[1];
                var numberDD = separateNumber.split(":")[0];
                var number_whatsapp = numberDD.split("=55")[1];

            } else {
                var numberDD_residencial = number_contact.split("+55")[1];
                var number_residencial = numberDD_residencial.split("END:VCARD")[0];
            }

            var input = document.createElement("input");
            input.type = "button";
            input.value = number_whatsapp == undefined ? "+55 " + number_residencial : "+55 " + number_whatsapp;
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
            message.style.boxShadow = ' 0 4px 4px rgb(0 0 0 / 40%)';
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
            message.className = "information";
            break;
        case 26:
            message.className = "StickerMessage";

            var sticker = document.createElement("img");
            sticker.src = json.media_url;
            sticker.style.background = "transparent";
            sticker.style.width = "100%";

            message.appendChild(sticker);

            break;
    }

    switch (parseInt(json.key_from_me)) {
        case 1:
        case 27:
            switch (parseInt(json.media_type)) {
                case 19:
                case 20:
                case 21:
                case 22:
                case 23:
                    break;
                default:
                    message.className += " messageLeft";
                    break;
            }
            message.style.cssFloat = 'left';
            message.style.background = '#dcf8c6';
            message.style.minWidth = '110px';
            message.style.color = 'black';
            message.style.border = 'solid 0px';

            break;
        case 2:
            message.className += " messageRight";
            message.style.cssFloat = 'right';
            message.style.minWidth = "110px";
            message.style.border = "solid 1px #e4e4e4";

            switch (parseInt(json.msgStatus)) {
                case 0:
                    bottom.innerHTML = msgWait();
                    break;
                case 1:
                case 27:
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
            bottom.appendChild(time);
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
            span.innerHTML = GLOBAL_LANG.report_princess_function_parseint_case_20;
            message.appendChild(span);
            item.appendChild(message);
            break;
        case 21:
            message.style.background = "transparent";
            var span = document.createElement("span");
            span.innerHTML = GLOBAL_LANG.report_princess_function_parseint_case_21;

            message.appendChild(span);
            item.appendChild(message);
            break;
        case 22:
            message.style.background = "transparent";
            var span = document.createElement("span");
            span.innerHTML = GLOBAL_LANG.report_princess_function_parseint_case_22;

            message.appendChild(span);
            item.appendChild(message);
            break;
        case 23:
            message.style.background = "transparent";
            var span = document.createElement("span");
            span.innerHTML = GLOBAL_LANG.report_princess_function_parseint_case_23;

            message.appendChild(span);
            item.appendChild(message);
            break;
    }

    list_messages.append(item);

}


function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "princess_fields/find",
            type: "POST",
            data: {
                text: $("#search").val(),
                dt_start: $('#dt-start').val(),
                dt_end: $('#dt-end').val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'first_timestamp_client'
            },
            {
                mData: 'full_name'
            },
            {
                mData: 'key_remote_id'
            },
            {
                mData: 'minutes'
            },
        ],
        columnDefs: [
            {
                targets: 3,
                render: function (data, type, full, meta) {

                    let time = "";
                    let hours = Math.floor(full.minutes / 60);
                    let minutes = full.minutes % 60;

                    if (hours > 72) {

                        time = hours / 24;
                        time = time.toString().split(".")[0] + GLOBAL_LANG.report_princess_dt_columndefs_target3_day_format;
                    } else {
                        if (hours < 10) hours = "0" + hours;
                        if (minutes < 10) minutes = "0" + minutes;

                        time = hours.toString() + "h:" + minutes.toString();
                    }

                    return time;
                }
            },
            {
                targets: 4,
                render: function (data, type, full, meta) {
                    return "<a href='#' id='" + full.id_chat_list + "' data-creation='" + full.start + "' class='table-action table-action-comment' data-toggle='tooltip' data-original-title='" + GLOBAL_LANG.report_princess_dt_columndefs_target4_title_edit + "'>" + "<i class='fas fa-comments'></i></a>"
                }
            },
            {
                orderable: true,
                targets: [0, 1, 2, 3]
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

    $("#search").on("keydown", function (event) {
        if (event.which == 13) {
            find();
        }
    });
    find();


    $("#datatable-basic").on("click", ".table-action-comment", function () {

        id_chat_list = this.id;

        $.post("princess_fields/history", {
            id: id_chat_list,
            creation: 0,
            csrf_talkall: Cookies.get("csrf_cookie_talkall")
        }, function (data) {

            $(".messages").html("");
            if (data.length > 0) for (let i = 0; i < data.length; i++) message(data[i]);

            $("#modal-chat").modal();
            $("#load_container_svg").remove();

            $("#list-messages").find('.item').sort(function (a, b) {
                return $(a).attr('data-index') - $(b).attr('data-index')
            }).appendTo("#list-messages");

            setTimeout(() => { $(".messages").scrollTop($(".messages").prop("scrollHeight") + 999); }, 20);
        });
    });


    $(".messages").on("scroll", function () {

        if (this.scrollTop == 0) {

            let creation = document.getElementsByClassName('item')[0].attributes[1].nodeValue;

            let loading = document.createElement("div");
            loading.innerHTML = `<i><img src="/assets/img/loads/loading_1.gif" class="load-img"></i>`;
            loading.id = "load_container_svg";

            $("#load_container_svg").remove();

            let messages = document.getElementById("list-messages");
            messages.prepend(loading);

            $.post("princess_fields/history", {
                id: id_chat_list,
                creation: creation,
                csrf_talkall: Cookies.get("csrf_cookie_talkall")
            }, function (data) {

                if (data.length > 0) {
                    for (let i = 0; i < data.length; i++) {
                        message(data[i]);
                    }
                }

                $("#modal-chat").modal();
                $("#load_container_svg").remove();

                $("#list-messages").find('.item').sort(function (a, b) {
                    return $(a).attr('data-index') - $(b).attr('data-index');
                }).appendTo("#list-messages");

                const elment = document.getElementById("is_" + data[0].creation);
                elment.scrollIntoView();

            });

        }
    });


    $("#modalFilter").on("click", () => modalFilter());
    $('#sendEmailExport').on('click', () => modalExport());

});


function modalFilter() {

    const search = document.getElementById("search");

    const check_search = document.getElementById("check-search");
    const input_search = document.getElementById("input-search");

    const dt_end = document.getElementById("dt-end");
    const dt_start = document.getElementById("dt-start");

    const check_date = document.getElementById("check-date");
    const btn_search = document.getElementById("btn-search");

    check_search.checked = true;
    input_search.style.display = "block";

    check_search.addEventListener("click", () => {
        if (check_search.checked) {
            input_search.style.display = "block";
            input_search.value = "";
        }
        else {
            input_search.style.display = "none";
        }

    });

    check_date.addEventListener("click", () => {
        if (check_date.checked) {

            dt_start.value = "";
            dt_start.type = "text";
            dt_start.style.display = "block";
            dt_start.placeholder = "Data inicío";

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

        const contact = document.getElementById("input-search");
        search.value = contact.value;

        find();
        search.value = "";
    });

}


function modalExport() {

    $.get(`/export/xlsx?
        search=${$('#search').val() == "" ? $('#input-search').val() : $('#search').val()}
        &dt_start=${$('#dt-start').val()}
        &dt_end=${$('#dt-end').val()}
        &type=reportChatbotPrincessField`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.report_princess_function_export_alert_title,
                text: GLOBAL_LANG.report_princess_function_export_alert_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.report_princess_function_export_alert_confirmButtonText
            });
        }
    });
}