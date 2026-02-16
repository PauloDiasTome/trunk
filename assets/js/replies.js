let preventDefault = false;
const Filters = JSON.parse(localStorage.getItem("filters")) || null;


function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.replies.search) {
            document.getElementById("search").value = Filters.replies.search;
        }

        Filters.btn_back = null;
        localStorage.setItem("filters", JSON.stringify(Filters));
    }
}

checkFilter();


function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "replies/find",
            type: "POST",
            data: {
                "text": $("#search").val(),
                "csrf_talkall": Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'title'
            },
            {
                mData: 'tag'
            },
        ],
        columnDefs: [
            {
                orderable: false,
                targets: 2,
                render: function (data, type, full, meta) {

                    return `<div class="dropleft">
                                    <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                        <i class="fa fa-ellipsis-v"></i>
                                    <div class="ripple-container"></div>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="multiDropdownMenu">
                                        <a id="` + full.id_quick_replies + `" href='#' class="dropdown-item table-btn-edit" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block">     
                                                <i class="far fa-edit"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.replies_js_columndefs_target2_title_edit}</span>
                                        </a>
                                        <a id="` + full.id_quick_replies + `" href='#' class="dropdown-item table-btn-delete" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block"> 
                                                <i class="far fa-trash-alt"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.replies_js_columndefs_target2_title_delete}</span>
                                        </a>
                                    </div>
                                </div>`
                }
            },
            {
                orderable: true,
                targets: [0, 1]
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

            const replies = {
                search: $("#search").val(),
            }

            let filter = localStorage.getItem("filters");

            filter = filter ? JSON.parse(filter) : {};
            filter.replies = replies;

            localStorage.setItem("filters", JSON.stringify(filter));
        }
    });
}


function submit() {

    //Substitui % por 1&&& para passar pela validação de carácter do codeigniter global_xss_filtering//
    let text = $("#text-area-content").val();
    let result = text.replace(/%/gi, "1&&&");

    let validation = validateFields();

    if (validation) {
        document.querySelector(".btn-success").disabled = true;
        $("#input-content").val(result);
        $("form").unbind('submit').submit();
    }
}

$(document).ready(function () {

    $("#search").on("keyup", function (event) {
        if (event.which == 13) {
            find()
        }
    });
    find();


    $("#datatable-basic").on("click", ".table-btn-edit", function () {
        window.location.href = "replies/edit/" + this.id;
    });


    $("#datatable-basic").on("click", ".table-btn-delete", function () {
        swal({
            title: GLOBAL_LANG.replies_alert_delete_title,
            text: GLOBAL_LANG.replies_alert_delete_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.replies_alert_delete_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.replies_alert_delete_cancelButtonText,
        }).then(t => {
            if (t.value == true) {
                $.post("replies/delete/" + this.id, function (data) {
                    t.value && swal({
                        title: GLOBAL_LANG.replies_alert_delete_two_title,
                        text: GLOBAL_LANG.replies_alert_delete_two_text,
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });
                    $("#datatable-basic").DataTable().ajax.reload(null, false);
                });
            }
        });
    });

    $("#text-area-content").on("click keyup keydown copy paste", function () {
        $("label[for=input-content]").find("span").text(4096 - $(this).val().length);
    }).trigger("keyup");

    $("#input-preview").on("click", function () {
        $("input[type='file']").trigger("click");
    });

    $("#private").on("click", function () {
        $('#public').prop('checked', false);
    });

    $("#public").on("click", function () {
        $('#private').prop('checked', false);
    });

    $('#sendEmailExport').on('click', () => modalExport());

    $("form").submit(event => event.preventDefault());

    $(".btn-success").on('click', () => submit());

    const option_media = document.getElementById("option_media");
    const option_text = document.getElementById("option_text");

    if (option_media != null)
        option_media.addEventListener("click", showMediaInput)

    if (option_text != null)
        option_text.addEventListener("click", showTextInput)

    const choose_media = document.querySelectorAll(".choose_media");
    const choose_text = document.querySelectorAll(".choose_text");

    choose_media.forEach(chosen => {
        chosen.addEventListener("click", () => changeTypeWarning("media"))
    });
    choose_text.forEach(chosen => {
        chosen.addEventListener("click", () => changeTypeWarning("text"))
    });

    let fileSelect = document.getElementById("fileSelect"),
        fileElem = document.getElementById("fileElem");

    if (document.getElementById("fileSelect")) {
        fileSelect.addEventListener("click", function (e) {
            if (fileElem) fileElem.click();
            e.preventDefault();
        }, false);
    }


    $("body").on("mouseover", ".box-broadcast", function () {
        $("#" + this.id).find(".tail").show();
        $("#" + this.id).find(".close-broadcast").css({ "display": "block" });
    });


    $("body").on("mouseout", ".box-broadcast", function () {
        $(".tail").css({ "display": "none" });
        $(".close-broadcast").css({ "display": "none" });
    });


    $("body").on("click", ".close-broadcast", function () {
        $(this).closest(".broadcast").remove();

        if ($(".box-broadcast").length < 1) {
            $(".drop-inner-img").show();
            $(".drop-inner-title").show();
            $(".drop-inner-text").show();
        }
        $('#fileElem').val("");
        $('#file').val("");
        cleanMediaInput();
    });


    $('body').on('change keyup keydown paste cut', '#tex_ar', function () {

        $(".tail").css({ "display": "none" });
        $(this).height(0).height(this.scrollHeight);

        let type = $(this).attr("data-type");
        if (type != "type-image") $(this).attr('maxlength', '55');

    }).find('#tex_ar').change();


    $("#fileElem").on("click", checkFileInput);

    var type_view = $("#type_view").val()

    if (type_view === "edit") {
        const type_edit = $("#edit").val();

        if (type_edit === "media") {
            showMediaInput();
        } else {
            showTextInput();
        }
    }


    if (type_view == "edit") {
        var type_edit = $("#edit").val()

        if (type_edit == "media") {

            let file = {
                "url": $("#media_url_edit").val(),
                "text": '',
                "size": $("#media_size_edit").val(),
                "thumbnail": '',
                "media_type": $("#media_type_edit").val(),
                "retrieve": true,
            }

            if (file.media_type == 2) {
                createAudio(file);
            } else {
                createBroadcast(file);
            };

        }
    }

});


function checkFileInput() {
    const hasMediaVisible = document.querySelectorAll(".broadcast, .broadcast-audio").length > 0;

    if (hasMediaVisible) {
        callAlert("limit");
        return false;
    } else {
        $("#fileElem").val("");
        return true;
    }
}

function changeTypeWarning(type) {

    swal({
        title: GLOBAL_LANG.replies_alert_change_type_title,
        text: GLOBAL_LANG.replies_alert_change_type_text,
        type: "warning",
        showCancelButton: !0,
        buttonsStyling: !1,
        reverseButtons: true,
        confirmButtonClass: "btn btn-success",
        confirmButtonText: GLOBAL_LANG.replies_alert_change_type_confirmButtonText,
        cancelButtonClass: "btn btn-danger",
        cancelButtonText: GLOBAL_LANG.replies_alert_change_type_cancelButtonText,
    }).then(t => {
        if (t.value == true) {

            switch (type) {
                case "media":
                    showMediaInput();
                    break;
                case "text":
                    showTextInput();
                    break;
            }
        }
    });

}


function cleanTextInput() {
    $("#text-area-content").val("");
    $("#alert_input_media").hide();
    $("#alert_input_text").hide();
}


function cleanMediaInput() {
    $(".broadcast").remove();
    $(".broadcast-audio").remove();

    $(".drop-inner-img").show();
    $(".drop-inner-title").show();
    $(".drop-inner-text").show();

    $("#alert_input_media").hide();
    $("#alert_input_text").hide();

    $("#fileElem").val("");
    $("#file").val("");

    $(".media-name-hidden").val("");
    $(".file-hidden").val("");
    $(".byte-hidden").val("");
    $(".duration-hidden").val("");
}

function showMediaInput() {

    cleanTextInput()

    const data_status = document.getElementById("status_data");
    const input_files = document.getElementById("input_files");
    const dropBrodcast = document.getElementById("dropBrodcast");
    const label_chose_type = document.getElementById("label_chose_type");
    const change_type_icon_img = document.getElementById("change_type_icon_img");
    const change_type_icon_text = document.getElementById("change_type_icon_text");
    const type_reply = document.getElementById("type_reply");
    const alert_input_type_reply = document.getElementById("alert_input_type_reply");

    option_text.style.display = "none";
    option_media.style.display = "none";

    input_files.style.display = "block";
    dropBrodcast.style.display = "block";

    data_status.style.display = "none";

    label_chose_type.style.display = "none";

    change_type_icon_img.style.display = "none";
    change_type_icon_text.style.display = "block";

    type_reply.value = "media";

    alert_input_type_reply.style.display = "none";
}


function showTextInput() {

    cleanMediaInput()

    const data_status = document.getElementById("status_data");
    const label_chose_type = document.getElementById("label_chose_type");
    const change_type_icon_img = document.getElementById("change_type_icon_img");
    const change_type_icon_text = document.getElementById("change_type_icon_text");
    const type_reply = document.getElementById("type_reply");
    const alert_input_type_reply = document.getElementById("alert_input_type_reply");

    option_text.style.display = "none";
    option_media.style.display = "none";

    input_files.style.display = "none";
    dropBrodcast.style.display = "none";

    data_status.style.display = "block";

    label_chose_type.style.display = "none";

    change_type_icon_img.style.display = "block";
    change_type_icon_text.style.display = "none";

    type_reply.value = "text";

    alert_input_type_reply.style.display = "none";
}


function bytesToSize(bytes) {

    let sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes == 0) return '0 Byte';
    let i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}


function decodeFile(key_id, media_url, type) {

    var settings = {
        "url": media_url,
        "method": "GET",
        "timeout": 0,
    };

    // $.ajax(settings).done(function (response) {
    // console.log(response)

    // const blob = b64toBlob(response, type);
    // const blobUrl = URL.createObjectURL(settings.url);

    $("#audio_" + key_id).attr("src", settings.url);
    $("#source_" + key_id).attr("src", settings.url);

    // });

}


function fillThumbnail(data) {

    let id = data.ta_id;
    $("#load_" + id).remove();

    $("#" + id).find(".file-hidden").attr("value", data.url);
    $("#" + id).find(".thumbnail-hidden").attr("value", data.thumbnail);
    $("#" + id).find(".byte-hidden").attr("value", data.size);
    $("#" + id).find(".media-name-hidden").attr("value", data.media_name);
    $("#" + id).find(".duration-hidden").attr("value", data.duration);
    $("#" + id).find(".box-inner").css({ "justify-content": "inherit" });

    $("#close_" + id).addClass("close-broadcast");
    $("#close_" + id).attr("src", document.location.origin + "/assets/img/statusClose.png");

    if (data.mimetype == "video/mp4") {

        $("#video_" + id).show();
        $("#video_" + id).addClass("video");
        $("#video_" + id).attr("src", data.url);
    } else if (data.mimetype == "application/pdf") {

        $("#i_st_" + id).addClass("i_st");
        $("#i_st_" + id).attr("src", document.location.origin + "/assets/img/panel/pdf_example.png");
    } else {
        $("#i_st_" + id).addClass("i_st");
        $("#i_st_" + id).attr("src", "data:image/jpeg;base64," + data.thumbnail);
    }

    $("#tail_" + id).addClass("tail");
    $("#tail_" + id).find(".tail-byte").text(bytesToSize(data.size));

    if ($(".b-audio").hasClass("b-audio")) {
        decodeFile(data.ta_id, data.url, data.mimetype);
    }
}


function createAudio(data) {

    document.querySelectorAll(".drop-inner-img, .drop-inner-title, .drop-inner-text").forEach(el => el.style.display = "none");

    let broadcast = document.createElement("div");
    broadcast.className = "col-6 broadcast-audio";
    broadcast.id = data.retrieve ? Math.random() * 100000 : data.ta_id;
    broadcast.classList.add("b-audio");

    let boxBroadcast = document.createElement("div");
    boxBroadcast.className = "box-broadcast-audio";
    boxBroadcast.style.position = "relative";

    const hiddenFields = [
        { cls: "file-hidden", name: "file", value: data.url || "" },
        { cls: "byte-hidden", name: "byte", value: data.retrieve ? data.size : "" },
        { cls: "media-name-hidden", name: "media_name", value: data.retrieve ? data.media_name : "" },
        { cls: "duration-hidden", name: "duration", value: data.retrieve ? data.duration : "" }
    ];

    hiddenFields.forEach(f => {
        let input = document.createElement("input");
        input.type = "hidden";
        input.className = f.cls;
        input.name = f.name;
        input.value = f.value;
        boxBroadcast.appendChild(input);
    });

    let close = document.createElement("span");
    close.innerHTML = "✕";
    close.className = "audio-close";
    close.onclick = () => {
        broadcast.remove();
        $("#fileElem").val("");
        $("#file").val("");

        const fileElem = document.getElementById("fileElem");
        if (fileElem) fileElem.value = "";

        document.querySelectorAll(".drop-inner-img").forEach(el => el.style.display = "block");
        document.querySelectorAll(".drop-inner-title").forEach(el => el.style.display = "block");
        document.querySelectorAll(".drop-inner-text").forEach(el => el.style.display = "block");
    };

    let mainContent = document.createElement("div");
    mainContent.className = "audio-preview";

    let header = document.createElement("div");
    header.className = "audio-header";

    let icon = document.createElement("i");
    icon.className = "fas fa-music";

    let title = document.createElement("span");
    title.innerText = GLOBAL_LANG.replies_add_audio;

    header.appendChild(icon);
    header.appendChild(title);

    let audio = document.createElement("audio");
    audio.controls = true;
    audio.preload = "metadata";

    if (data.retrieve) {
        audio.src = data.url;
    } else if (data.file) {
        audio.src = URL.createObjectURL(data.file);
    }

    audio.load();

    let footer = document.createElement("div");
    footer.className = "audio-footer";

    let size = document.createElement("span");
    size.innerText = data.retrieve ? bytesToSize(parseInt(data.size)) : "";

    footer.appendChild(size);

    mainContent.appendChild(header);
    mainContent.appendChild(audio);
    mainContent.appendChild(footer);

    boxBroadcast.appendChild(close);
    boxBroadcast.appendChild(mainContent);
    broadcast.appendChild(boxBroadcast);

    document.getElementById("dropBrodcast").appendChild(broadcast);

    broadcast.animate(
        [{ opacity: 0 }, { opacity: 1 }],
        { duration: 300 }
    );
}


function createBroadcast(data) {

    const dropImage = document.querySelectorAll(".drop-inner-img");
    const dropTitle = document.querySelectorAll(".drop-inner-title");
    const dropText = document.querySelectorAll(".drop-inner-text");

    dropImage.forEach(function (elm) {
        elm.style.display = "none";
    });

    dropTitle.forEach(function (elm) {
        elm.style.display = "none";
    });

    dropText.forEach(function (elm) {
        elm.style.display = "none";
    });

    let broadcast = document.createElement("div");
    broadcast.className = "col-6 broadcast";
    broadcast.id = Math.floor(Math.random() * 100000);

    let boxBroadcast = document.createElement("div");
    boxBroadcast.className = "box-broadcast";
    boxBroadcast.draggable = "false";
    boxBroadcast.id = data.retrieve === false ? data.ta_id : Math.floor(Math.random() * 100000);

    let url_hidden = document.createElement("input");
    url_hidden.type = "hidden";
    url_hidden.className = "file-hidden";
    url_hidden.name = "file";
    url_hidden.value = data.retrieve === false ? "" : data.url;

    let byte_hidden = document.createElement("input");
    byte_hidden.type = "hidden";
    byte_hidden.className = "byte-hidden";
    byte_hidden.name = "byte";
    byte_hidden.value = data.retrieve === false ? "" : data.size;

    let media_name_hidden = document.createElement("input");
    media_name_hidden.type = "hidden";
    media_name_hidden.className = "media-name-hidden";
    media_name_hidden.name = "media_name";
    media_name_hidden.value = data.retrieve === false ? "" : (data.media_name || "");

    let duration_hidden = document.createElement("input");
    duration_hidden.type = "hidden";
    duration_hidden.className = "duration-hidden";
    duration_hidden.name = "duration";
    duration_hidden.value = data.retrieve === false ? "" : (data.duration || "");

    let boxInner = document.createElement("div");
    boxInner.className = "box-inner";
    boxInner.style = data.retrieve === false ? "" : "justify-content: center";

    let load = document.createElement("img");
    load.src = data.retrieve === false ? document.location.origin + "/assets/img/loads/loading_2.gif" : "";
    load.id = data.retrieve === false ? "load_" + data.ta_id : "";
    load.className = "load";
    load.style.marginTop = "80%";
    load.style.width = "102px";

    let imgBroadcast = document.createElement("div");
    imgBroadcast.className = "img-broadcast";

    let img = document.createElement("img");
    let video = document.createElement("video");

    if (data.media_type == 3 || data.media_type == 4) {

        img.className = data.retrieve === false ? "" : "i_st";
        img.id = data.retrieve === false ? "i_st_" + data.ta_id : "i_st_" + Math.floor(Math.random() * 100000);
        if (data.media_type == 3) {
            img.src = data.retrieve === false ? "" : data.url;
        } else if (data.media_type == 4) {
            img.src = data.retrieve === false ? "" : document.location.origin + "/assets/img/panel/pdf_example.png";
        }

    } else if (data.media_type == 5) {

        video.controls = true;
        video.className = "video";
        video.id = data.retrieve === false ? "video_" + data.ta_id : "video_" + Math.floor(Math.random() * 100000);
        video.src = data.retrieve === false ? "" : data.url;

    }

    let close = document.createElement("img");
    close.className = data.retrieve === false ? "" : "close-broadcast";
    close.src = data.retrieve === false ? "" : document.location.origin + "/assets/img/statusClose.png";
    close.id = data.retrieve === false ? "close_" + data.ta_id : "close_" + Math.floor(Math.random() * 100000);

    let tail = document.createElement("div");
    tail.id = data.retrieve === false ? "tail_" + data.ta_id : "tail_" + Math.floor(Math.random() * 100000);
    tail.className = data.retrieve === false ? "" : "tail";

    let byte = document.createElement("span");
    byte.className = "tail-byte";
    byte.innerHTML = data.retrieve === false ? "" : bytesToSize(parseInt(data.size));

    tail.appendChild(byte);

    imgBroadcast.appendChild(load);
    imgBroadcast.appendChild(close);
    imgBroadcast.appendChild(tail);

    if (data.media_type == 3 || data.media_type == 4) {
        imgBroadcast.appendChild(img);
    } else if (data.media_type == 5) {
        imgBroadcast.appendChild(video);
        if (data.retrieve == false) {
            video.style.display = "none";
        }
    }

    boxBroadcast.appendChild(url_hidden);
    boxBroadcast.appendChild(media_name_hidden);
    boxBroadcast.appendChild(byte_hidden);
    boxBroadcast.appendChild(media_name_hidden);
    boxBroadcast.appendChild(duration_hidden);

    boxInner.appendChild(imgBroadcast);
    boxBroadcast.appendChild(boxInner);
    broadcast.appendChild(boxBroadcast);

    const dropBrodcast = document.getElementById("dropBrodcast");
    dropBrodcast.appendChild(broadcast);

    if (data.retrieve === true) {
        $(".load").remove();
    }

    broadcast.animate([
        { opacity: '0' },
        { opacity: '1' }
    ], {
        duration: 500,
    });

    // remover text-area para pdf   
    $('.tex-area').each(function () {
        if ($(this).attr('data-type') === 'type-pdf') {
            $(this).hide();
        }
    });
}


function callAlert(verify) {

    switch (verify) {
        case "limit":
            swal({
                title: GLOBAL_LANG.whatsapp_broadcast_alert_dropzone_title,
                text: GLOBAL_LANG.whatsapp_broadcast_alert_dropzone_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_alert_dropzone_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "maxSize":
            swal({
                title: GLOBAL_LANG.whatsapp_broadcast_alert_dropzone_two_title,
                text: GLOBAL_LANG.whatsapp_broadcast_alert_dropzone_two_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_alert_dropzone_two_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "png":
            swal({
                title: GLOBAL_LANG.whatsapp_broadcast_alert_dropzone_three_title,
                text: GLOBAL_LANG.whatsapp_broadcast_alert_dropzone_three_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_alert_dropzone_three_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "extensions":
            swal({
                title: GLOBAL_LANG.whatsapp_broadcast_alert_dropzone_three_title,
                text: GLOBAL_LANG.whatsapp_broadcast_alert_dropzone_three_extensions,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_alert_dropzone_three_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });

            break;
        default:
            break;
    }
    $('#fileElem').val("");
}

function handleFiles(files) {

    if (preventDefault) return;

    let formData = new FormData();
    let total_files = document.querySelectorAll(".broadcast, .broadcast-audio").length;
    let myFiles = [];
    let out = false;
    let maxFileSize = 10 * 1024 * 1024;

    for (let i = 0; i < files.length; i++) {
        if (myFiles.length >= 3) break;

        const type = files[i].type;

        if (
            type === "image/jpeg" ||
            type === "image/jpg" ||
            type === "image/png" ||
            type === "audio/ogg" ||
            type === "video/mp4" ||
            type === "application/pdf"
        ) {
            myFiles.push(files[i]);
        } else {
            callAlert("extensions");
        }
    }

    myFiles.forEach((file, i) => {

        if (total_files + i >= 1) {
            if (!out) {
                callAlert("limit");
                out = true;
            }
            return;
        }

        if (file.size > maxFileSize) {
            if (!out) {
                callAlert("maxSize");
                out = true;
            }
            return;
        }

        let media_type = null;

        switch (file.type) {
            case "image/jpeg":
            case "image/jpg":
                media_type = 3;
                break;
            case "application/pdf":
                media_type = 4;
                break;
            case "video/mp4":
                media_type = 5;
                break;
        }

        let id = Math.floor(Math.random() * 100000);

        let data = {
            ta_id: id,
            retrieve: false,
            media_type: media_type,
            file: file,
            media_name: file.name
        };

        if (file.type === "audio/ogg") {
            createAudio(data);
        } else {
            createBroadcast(data);
        }

        let uploadData = new FormData();
        uploadData.append("filetoupload", file);
        uploadData.append("ta_id", id);

        $.ajax({
            url: "https://files.talkall.com.br:3000",
            method: "POST",
            processData: false,
            contentType: false,
            dataType: "json",
            data: uploadData
        }).done(function (response) {
            response.media_name = file.name;
            fillThumbnail(response);
        });

    });

    preventDefault = false;
}

function dragOverHandler(ev) {
    ev.preventDefault();
}


function dropHandler(ev) {
    ev.preventDefault();

    let files = [];

    if (ev.dataTransfer.items) {
        for (let i = 0; i < ev.dataTransfer.items.length; i++) {
            if (ev.dataTransfer.items[i].kind === 'file') {
                files.push(ev.dataTransfer.items[i].getAsFile());
            }
        }
    } else {
        files = ev.dataTransfer.files;
    }

    if (files.length > 0) {
        handleFiles(files);
    } else {
        console.error("Nenhum arquivo detectado no drop.");
    }
}


document.querySelectorAll(".btn.btn-primary, .btn.btn-danger").forEach(function (button) {
    button.addEventListener("click", function () {
        if (Filters) {
            Filters.btn_back = true;
            localStorage.setItem("filters", JSON.stringify(Filters));
        }
    });
});


function modalExport() {
    let column = $("#datatable-basic").DataTable().order()[0][0];
    let order = $("#datatable-basic").DataTable().order()[0][1];

    switch (column) {
        case 0:
            column = "title";
            break;

        default:
            column = "tag";
            break;
    }

    $.get(`/export/xlsx?
        search=${$('#search').val()}
        &column=${column}
        &order=${order}
        &type=replies`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.replies_alert_export_title,
                text: GLOBAL_LANG.replies_alert_export_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.replies_alert_export_confirmButtonText
            });
        }
    });
}


function validateFields() {
    const text_title = document.getElementById("input-title").value;
    const text_tag = document.getElementById("input-tag").value;
    const text_area_content = document.getElementById("text-area-content").value.trim();

    let fileElem = document.getElementById("fileElem").value;

    let fileHidden = document.querySelector(".file-hidden") ? document.querySelector(".file-hidden").value : "";

    const type_reply = document.getElementById("type_reply").value;
    const type_view = document.getElementById("type_view").value;

    let form_validation_text_title = true;
    let form_validation_text_tag = true;
    let form_validation_text_area_content = true;
    let form_validation_type_reply = true;

    if (!text_title) {
        form_validation_text_title = false;
        document.getElementById("alert_input_title").style.display = "block";
        document.getElementById("alert_input_title").innerHTML = GLOBAL_LANG.replies_title_validation;
    } else {
        document.getElementById("alert_input_title").style.display = "none";
    }

    if (!text_tag) {
        form_validation_text_tag = false;
        document.getElementById("alert_input_tag").style.display = "block";
        document.getElementById("alert_input_tag").innerHTML = GLOBAL_LANG.replies_tag_validation;
    } else {
        document.getElementById("alert_input_tag").style.display = "none";
    }

    switch (type_reply) {
        case "media":
            if (!fileElem && !fileHidden) {
                form_validation_text_area_content = false;
                document.getElementById("alert_input_media").style.display = "block";
                document.getElementById("alert_input_media").innerHTML = GLOBAL_LANG.replies_media_validation;
            } else {
                document.getElementById("alert_input_media").style.display = "none";
            }
            break;

        case "text":
            if (!text_area_content) {
                form_validation_text_area_content = false;
                document.getElementById("alert_input_text").style.display = "block";
                document.getElementById("alert_input_text").innerHTML = GLOBAL_LANG.replies_text_validation;
            } else {
                document.getElementById("alert_input_text").style.display = "none";
            }
            break;

        case "":
            form_validation_type_reply = false;
            document.getElementById("alert_input_type_reply").style.display = "block";
            document.getElementById("alert_input_type_reply").innerHTML = GLOBAL_LANG.replies_type_reply_validation;
            break;
    }

    return (form_validation_text_title && form_validation_text_tag && form_validation_text_area_content && form_validation_type_reply);
}