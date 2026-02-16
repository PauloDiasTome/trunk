let preventDefault = false;
const Components = new ComponentsDom();
const Filters = JSON.parse(localStorage.getItem("filters")) || null;

const maskBehavior = (val) => {
    val = val.split(":");
    return (parseInt(val[0]) > 19) ? "HZ:M0" : "H0:M0";
}

const spOptions = {
    onKeyPress: function (val, e, field, options) {
        field.mask(maskBehavior.apply({}, arguments), options);
    },
    translation: {
        'H': { pattern: /[0-2]/, optional: false },
        'Z': { pattern: /[0-3]/, optional: false },
        'M': { pattern: /[0-5]/, optional: false }
    }
};

const Campaign = {
    cancel_list: [],

    push(token) {
        this.cancel_list.push({ token: token });
    },
    remove(token) {
        let index = Campaign.cancel_list.findIndex(item => item.token === token);
        if (index !== -1)
            this.cancel_list.splice(index, 1);
    },
    clear() {
        this.cancel_list = [];
    }
}

const Table = {
    row: {
        click(e) {
            const row = document.getElementById(e.target.id);

            if (row.checked) {
                Campaign.push(row.id);
                Table.row.selected(row);
            } else {
                Campaign.remove(row.id);
                Table.row.deselected(row);
            }
        },
        selected(row) {
            row.checked = true;
            row.classList.add("selected");
            row.parentNode.parentNode.parentNode.style.background = "#cfd2dbba";
        },
        deselected(row) {
            row.checked = false;
            row.classList.remove("selected");
            row.parentNode.parentNode.parentNode.style.background = "#ffffff";
        }
    }
}

function bytesToSize(bytes) {

    let sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes == 0) return '0 Byte';
    let i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}

function addName() {

    let f = 0, t = 0, a = 0, b = 0, c = 0;

    const files = document.querySelectorAll(".file-hidden");
    const texAreas = document.querySelectorAll(".tex-area");
    const bytes = document.querySelectorAll(".byte-hidden");
    const media_names = document.querySelectorAll(".media_name-hidden");

    for (let file of files) {
        file.name = "file" + f;
        f++;
    }

    for (let texArea of texAreas) {
        texArea.name = "text" + a;
        a++;
    }

    for (let byte of bytes) {
        byte.name = "byte" + b;
        b++;
    }

    for (let media_name of media_names) {
        media_name.name = "media_name" + c;
        c++;
    }
}

function fillThumbnail(data) {

    let id = data.ta_id;
    $("#load_" + id).remove();

    $("#" + id).find(".file-hidden").attr("value", data.url);
    $("#" + id).find(".thumbnail-hidden").attr("value", data.thumbnail);
    $("#" + id).find(".byte-hidden").attr("value", data.size);
    $("#" + id).find(".media_name-hidden").attr("value", data.media_name);
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

    $("#i_st_" + id).on("click", function () {
        window.open($("#" + this.id.replace("i_st_", "") + " input").val());
    });

    $("#tail_" + id).addClass("tail");
    $("#tail_" + id).find(".tail-byte").text(bytesToSize(data.size));

    setTimeout(function () { addName(); }, 500);
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
    boxBroadcast.draggable = "true";
    boxBroadcast.id = data.retrieve === false ? data.ta_id : Math.floor(Math.random() * 100000);

    let url_hidden = document.createElement("input");
    url_hidden.type = "hidden";
    url_hidden.className = "file-hidden";
    url_hidden.name = "file";
    url_hidden.value = data.retrieve === false ? "" : data.url;

    let thumbnail_hidden = document.createElement("input");
    thumbnail_hidden.type = "hidden";
    thumbnail_hidden.className = "thumbnail-hidden";
    thumbnail_hidden.value = data.retrieve === false ? "" : "data:image/jpeg;base64," + data.thumbnail;

    let byte_hidden = document.createElement("input");
    byte_hidden.type = "hidden";
    byte_hidden.className = "byte-hidden";
    byte_hidden.name = "byte";
    byte_hidden.value = data.retrieve === false ? "" : data.size;

    let media_name_hidden = document.createElement("input");
    media_name_hidden.type = "hidden";
    media_name_hidden.className = "media_name-hidden";
    media_name_hidden.name = "media_name";
    media_name_hidden.value = data.retrieve === false ? "" : data.media_name;

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

            img.src = data.retrieve === false ? "" : "data:image/jpeg;base64," + data.thumbnail;
        } else {
            img.src = data.retrieve === false ? "" : data.thumbnail;

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

    let textAbroadcast = document.createElement("div");
    textAbroadcast.className = "textarea-status";

    let textArea = document.createElement("textarea");
    textArea.placeholder = GLOBAL_LANG.whatsapp_broadcast_community_description_placeholder;
    textArea.className = "tex-area";
    textArea.name = "tex_ar";
    textArea.id = "tex_ar";
    textArea.dataset.type = data.media_type == 4 ? "type-pdf" : "type-image";
    textArea.dataset.index = Math.floor(Math.random() * 10000);
    textArea.maxLength = data.media_type == 4 ? "700" : "1024";

    textArea.value = data.retrieve === false ? "" : data.text;
    textArea.addEventListener("mousedown", (e) => getTooltipPositionAI(e));
    textArea.addEventListener("input", (e) => countCaracter(e, data.media_type));
    textArea.onmouseup = textArea.onkeyup = () => createTooltipAI();

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

    let caracter = document.createElement('div');
    caracter.setAttribute('class', 'count-caracter');

    let text_caracter = document.createElement('span');
    text_caracter.textContent = GLOBAL_LANG.whatsapp_broadcast_community_text_caracter;

    let quantity_caracter = document.createElement('span');
    quantity_caracter.setAttribute('class', 'quantity-caracter');
    quantity_caracter.textContent = data.media_type == 4 ? "700" : "1024";

    caracter.appendChild(text_caracter);
    caracter.appendChild(quantity_caracter);

    boxBroadcast.appendChild(url_hidden);
    boxBroadcast.appendChild(byte_hidden);
    boxBroadcast.appendChild(thumbnail_hidden);
    boxBroadcast.appendChild(media_name_hidden);

    boxInner.appendChild(imgBroadcast);
    boxBroadcast.appendChild(boxInner);
    textAbroadcast.appendChild(textArea);
    boxBroadcast.appendChild(textAbroadcast);
    boxBroadcast.appendChild(caracter);
    broadcast.appendChild(boxBroadcast);

    const dropBrodcast = document.getElementById("dropBrodcast");
    dropBrodcast.appendChild(broadcast);

    if (data.retrieve === true) {
        setTimeout(function () { addName(); }, 500);
        $(".load").remove();
    }

    broadcast.animate([
        { opacity: '0' },
        { opacity: '1' }
    ], {
        duration: 500,
    });
}


function editBroadcast(data) {

    let broadcast = document.createElement("div");
    broadcast.className = "col-6 broadcast";
    broadcast.id = Math.floor(Math.random() * 100000);

    let boxBroadcast = document.createElement("div");
    boxBroadcast.className = "box-broadcast";
    boxBroadcast.id = Math.floor(Math.random() * 100000);

    let url_hidden = document.createElement("input");
    url_hidden.type = "hidden";
    url_hidden.className = "file-hidden";
    url_hidden.name = "file0";
    url_hidden.value = data.url;

    let boxInner = document.createElement("div");
    boxInner.className = "box-inner";
    boxInner.style = "justify-content: inherit";

    let imgBroadcast = document.createElement("div");
    imgBroadcast.className = "img-broadcast";

    let img = document.createElement("img");
    let video = document.createElement("video");
    let pdf_link = document.createElement("a");

    if (data.media_type == 3) {
        img.className = "i_st";
        img.id = "i_st_" + Math.floor(Math.random() * 100000);
        img.src = data.url;
    } else if (data.media_type == 4) {

        const pdfjsLib = window['pdfjs-dist/build/pdf'];
        pdfjsLib.GlobalWorkerOptions.workerSrc = document.location.origin + "/assets/lib/build_pdf/pdf_build_pdf.worker.js";

        const loadingTask = pdfjsLib.getDocument(data.url);
        loadingTask.promise.then(function (pdf) {

            const pageNumber = 1;
            pdf.getPage(pageNumber).then(function (page) {

                let desiredWidth = 320;
                let viewport = page.getViewport({ scale: 1, });
                let scale = desiredWidth / viewport.width;
                let scaledViewport = page.getViewport({ scale: scale, });

                let canvas = document.createElement("canvas");
                canvas.id = "pdf-canva";

                let context = canvas.getContext('2d');
                canvas.height = 265;
                canvas.width = 320;

                const renderContext = {
                    canvasContext: context,
                    viewport: scaledViewport
                };

                const renderTask = page.render(renderContext);
                renderTask.promise.then(function () {

                    pdf_link.href = data.url;
                    pdf_link.setAttribute("target", "_blank");
                    pdf_link.appendChild(canvas);

                });
            });
        }, function (reason) {
            console.error(reason);
        });

    } else if (data.media_type == 5) {
        video.controls = true;
        video.className = "video";
        video.id = "video_" + Math.floor(Math.random() * 100000);
        video.src = data.url;
    }

    let textAbroadcast = document.createElement("div");
    textAbroadcast.className = "textarea-status";

    let textArea = document.createElement("textarea");
    textArea.placeholder = GLOBAL_LANG.whatsapp_broadcast_community_description_placeholder;
    textArea.className = "tex-area";
    textArea.name = "description_data";
    textArea.id = "description_data";
    textArea.dataset.type = "type-image";
    textArea.dataset.index = Math.floor(Math.random() * 10000);
    textArea.maxLength = "1024";
    textArea.value = data.text;
    textArea.addEventListener("mousedown", (e) => getTooltipPositionAI(e));
    textArea.addEventListener("input", (e) => countCaracter(e, data.media_type));
    textArea.onmouseup = textArea.onkeyup = () => createTooltipAI();

    if (data.media_type == 3) {
        imgBroadcast.appendChild(img);
    } else if (data.media_type == 4) {
        imgBroadcast.appendChild(pdf_link);
    } else if (data.media_type == 5) {
        imgBroadcast.appendChild(video);
    }

    let caracter = document.createElement('div');
    caracter.setAttribute('class', 'count-caracter');

    let text_caracter = document.createElement('span');
    text_caracter.textContent = GLOBAL_LANG.whatsapp_broadcast_text_caracter;

    let quantity_caracter = document.createElement('span');
    quantity_caracter.setAttribute('class', 'quantity-caracter');
    quantity_caracter.textContent = data.media_type == 4 ? "700" : "1024";

    caracter.appendChild(text_caracter);
    caracter.appendChild(quantity_caracter);


    boxBroadcast.appendChild(url_hidden);
    boxInner.appendChild(imgBroadcast);
    boxBroadcast.appendChild(boxInner);
    textAbroadcast.appendChild(textArea);
    boxBroadcast.appendChild(textAbroadcast);
    boxBroadcast.appendChild(caracter);
    broadcast.appendChild(boxBroadcast);

    const dropBrodcast = document.getElementById("dropBrodcast");
    dropBrodcast.appendChild(broadcast);

    broadcast.animate([
        { opacity: '0' },
        { opacity: '1' }
    ], {
        duration: 500,
    });
}


function callAlert(verify) {

    switch (verify) {
        case "limit":
            swal({
                title: GLOBAL_LANG.whatsapp_broadcast_community_alert_dropzone_title,
                text: GLOBAL_LANG.whatsapp_broadcast_community_alert_dropzone_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_community_alert_dropzone_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "maxSize":
            swal({
                title: GLOBAL_LANG.whatsapp_broadcast_community_alert_dropzone_two_title,
                text: GLOBAL_LANG.whatsapp_broadcast_community_alert_dropzone_two_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_community_alert_dropzone_two_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "png":
            swal({
                title: GLOBAL_LANG.whatsapp_broadcast_community_alert_dropzone_three_title,
                text: GLOBAL_LANG.whatsapp_broadcast_community_alert_dropzone_three_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_community_alert_dropzone_three_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "extensions":
            swal({
                title: GLOBAL_LANG.whatsapp_broadcast_community_alert_dropzone_three_title,
                text: GLOBAL_LANG.whatsapp_broadcast_community_alert_dropzone_three_extensions,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_community_alert_dropzone_three_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });

            break;
        default:
            break;
    }
    $('#fileElem').val("");
}

function handleFiles(files) {

    if (!preventDefault) {

        let formData = new FormData();
        let total_files = parseInt($(".broadcast").length);
        let myFiles = [];
        let out = false;
        let media_type;
        let maxFileSize = 10 * 1024 * 1024;

        for (let i = 0; i < files.length; i++) {

            if (myFiles.length < 3) {
                if (files[i].name.includes("jfif")) {
                    callAlert('extensions');
                    return;
                }
                
                if (files[i].type === "image/jpeg" ||
                    files[i].type === "image/jpg" ||
                    files[i].type === "audio/ogg" ||
                    files[i].type === "image/png" ||
                    files[i].type === "video/mp4" ||
                    files[i].type === "application/pdf") {
                    myFiles.push(files[i]);
                } else {
                    callAlert('extensions');
                }
            }
        }

        for (let i = 0; i < myFiles.length; i++)(function (t) {
            setTimeout(function () {

                if (i >= 2 || (total_files + 1) + i > 2) {

                    if (!out) {
                        callAlert("limit");
                        out = true;
                    }
                } else if (myFiles[i].size > maxFileSize) {

                    if (!out) {
                        callAlert("maxSize");
                        out = true;
                    }
                } else if (myFiles[i].type == "image/png") {

                    if (!out) {
                        callAlert("png");
                        out = true;
                    }
                } else {

                    switch (files[i].type) {

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
                        "ta_id": id,
                        "retrieve": false,
                        "media_type": media_type
                    }

                    if (myFiles[i].type != "audio/ogg") {
                        createBroadcast(data);
                    } else {
                    }

                    formData.append("filetoupload", myFiles[i]);
                    formData.append("ta_id", id);

                    let settings = {
                        "url": "https://files.talkall.com.br:3000",
                        "method": "POST",
                        "timeout": 0,
                        "crossDomain": true,
                        "processData": false,
                        "mimeType": "multipart/form-data",
                        "contentType": false,
                        "data": formData
                    };


                    $.ajax(settings).done(function (response) {
                        fillThumbnail(JSON.parse(response));
                    });

                }

            }, t * 500);

        }(i))

        preventDefault = false;
    }
}

function dragOverHandler(ev) {
    ev.preventDefault();
}

function dropHandler(ev) {
    ev.preventDefault();

    if (ev.dataTransfer.items) {

        var files = [];

        for (var i = 0; i < ev.dataTransfer.items.length; i++) {

            if (ev.dataTransfer.items[i].type == 'image/jpeg' ||
                ev.dataTransfer.items[i].type == 'image/jpg' ||
                ev.dataTransfer.items[i].type == 'image/png' ||
                ev.dataTransfer.items[i].type == 'audio/ogg' ||
                ev.dataTransfer.items[i].type == 'video/mp4' ||
                ev.dataTransfer.items[i].type == 'application/pdf') {
                var file = ev.dataTransfer.items[i].getAsFile();
                files.push(file);
            }
        }

    } else {
        for (var i = 0; i < ev.dataTransfer.files.length; i++) {
            console.log('... file[' + i + '].name = ' + ev.dataTransfer.files[i].name);
            console.log('... file[' + i + '].name = ' + ev.dataTransfer.files[i]);
        }
    }

    handleFiles(files);

    $(".box-broadcast").css({ "width": "auto", "margin-left": "0px" });
    $(".broadcast").css({ "border": "0px #1212ffdb dashed" });
    $(".broadcast").css({ "background": "#fff", "opacity": "", "z-index": "" });
}


function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.community.search) {
            document.getElementById("search").value = Filters.community.search;
        }

        if (Filters.community.input_search) {
            document.getElementById("input-search").value = Filters.community.input_search;
        }

        if (Filters.community.community.length !== 0) {
            modalFilter();
            document.getElementById("check-select-community").click();

            document.querySelectorAll("#mult-select-community .cust_").forEach((element, index) => {

                if (Filters.community.community.includes(element.value))
                    document.querySelectorAll("#mult-select-community .cust_")[index].click();
            })
        }

        if (Filters.community.channel.length !== 0) {
            modalFilter();
            document.getElementById("check-select2").click();

            document.querySelectorAll("#mult-select2 .cust_").forEach((element, index) => {

                if (Filters.community.channel.includes(element.value))
                    document.querySelectorAll("#mult-select2 .cust_")[index].click();
            })
        }

        if (Filters.community.status) {
            modalFilter();
            document.getElementById("check-status").click();
            document.getElementById("select-status").value = Filters.community.status;
        }

        if (Filters.community.dt_start) {
            modalFilter();

            document.getElementById("check-date").click();

            document.getElementById("dt-end").disabled = false;
            document.getElementById("dt-end").type = "date";
            document.getElementById("dt-start").type = "date";

            document.getElementById("dt-start").value = Filters.community.dt_start;
            document.getElementById("dt-end").value = Filters.community.dt_end;
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
            url: "community/find",
            type: "POST",
            data: {
                text: $("#search").val(),
                community: $('#verify-select-community').val() == "2" ? "" : $('#multiselect-community').val(),
                channel: $('#verify-select2').val() == "2" ? "" : $('#multiselect2').val(),
                status: $('#select-status').val(),
                dt_start: $('#dt-start').val(),
                dt_end: $('#dt-end').val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall"),
            },
        },
        columns: [
            {
                mData: ''
            },
            {
                mData: 'thumb_image'
            },
            {
                mData: 'schedule'
            },
            {
                mData: 'name'
            },
            {
                mData: 'title'
            },
            {
                mData: 'status'
            },
        ],
        columnDefs: [
            {
                targets: 0,
                render: function (data, type, full, meta) {
                    return `<div class='${full.status == 2 || full.status == 4 || full.status == 5 ? "checkbox-table-disabled" : "checkbox-table"}'><input type='checkbox' id='${full.token}' name='verify_check_box[]' class='check-box' ${full.status == 2 || full.status == 4 || full.status == 5 ? "disabled" : ""}></div>`;
                }
            },
            {
                targets: 1,
                className: "thumb",
                render: function (data, type, full, meta) {

                    switch (full.media_type) {
                        case "1":
                            return "<div class='box-inner-datatable'><img src='" + document.location.origin + "/assets/img/loads/loading_2.gif' data-media-url='" + full.media_url + "' data-media-type='" + full.media_type + "' data-text='" + full.data + "' style='padding:8px'></div>";
                        case "3":
                            return "<div class='box-inner-datatable'><img src='" + document.location.origin + "/assets/img/loads/loading_2.gif' data-media-url='" + full.media_url + "' data-media-type='" + full.media_type + "' data-text='" + full.data + "' style='padding:8px'></div>";
                        case "4":
                            return "<div class='box-inner-datatable'><img src='" + document.location.origin + "/assets/img/loads/loading_2.gif' data-media-url='" + full.media_url + "' data-media-type='" + full.media_type + "' data-text='" + full.data + "' style='padding:8px'></div>";
                        case "5":
                            return "<div class='box-inner-datatable'><img src='" + document.location.origin + "/assets/img/loads/loading_2.gif' data-media-url='" + full.media_url + "' data-media-type='" + full.media_type + "' data-text='" + full.data + "' style='padding:8px'></div>";
                        default:
                            break;
                    }

                    return "<div class='box-inner-datatable'><img src='" + document.location.origin + "/assets/img/referral.png'/></div>";
                }
            },
            {
                targets: 3,
                render: function (data, type, full, meta) {
                    return `<div>
                                <span style="font-weight: bolder;">
                                    ${full.community_name.trim() ? full.community_name : ""}
                                </span>
                                <br>
                                <span>
                                    ${full.name.trim() ? full.name : full.channel}
                                </span>
                            </div>`;
                }
            },
            {
                targets: 5,
                render: function (data, type, full, meta) {
                    let ret = `<span class="badge badge-sm badge-info">${GLOBAL_LANG.whatsapp_broadcast_community_datatable_column_status_sending}</span>`
                    switch (full.status) {
                        case '2':
                            ret = `<span class="badge badge-sm badge-success">${GLOBAL_LANG.whatsapp_broadcast_community_datatable_column_status_send}</span>`
                            break;
                        case '3':
                            ret = `<span class="badge badge-sm badge-secondary">${GLOBAL_LANG.whatsapp_broadcast_community_datatable_column_status_waiting}</span>`
                            break;
                        case '5':
                            ret = `<span class="badge badge-sm badge-danger">${GLOBAL_LANG.whatsapp_broadcast_community_datatable_column_status_canceled}</span>`
                            break;
                        case '1':
                        case '6':
                            ret = `<span class="badge badge-sm badge-info">${GLOBAL_LANG.whatsapp_broadcast_community_datatable_column_status_sending}</span>`

                            break;
                        default:
                            ret = ret
                            break;
                    }
                    return `<b>${ret}</b>`;
                }
            },
            {
                targets: 6,
                render: function (data, type, full, meta) {
                    return `<div class="dropleft">
                                        <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                            <i class="fa fa-ellipsis-v"></i>
                                        <div class="ripple-container"></div>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="multiDropdownMenu">
                                            <a id="` + full.token + `" class="dropdown-item table-action-view" style="cursor: pointer">
                                                <div style="width: 24px; display: inline-block"> 
                                                    <i class="fa fa-eye" style="font-size: 11pt"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.whatsapp_broadcast_community_datatable_column_action_view}</span>
                                            </a>
                                            <a id="` + full.token + `" class="dropdown-item ${full.status == 4 ? 'table-action-deleted disabled' : (full.status == 5 ? 'table-action-deleted disabled' : (full.status == 2 ? 'table-action-deleted disabled' : 'table-action-delete'))}"  style="cursor: pointer">
                                                <div style="width: 24px; display: inline-block"> 
                                                    <i class="fas fa-times" style="font-size: 12pt; margin-left: 3px"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.whatsapp_broadcast_community_datatable_column_action_cancel}</span>
                                            </a>
                                            <a id="${full.token}" class="dropdown-item table-action-resend"  
                                                style="cursor: pointer; display:${(full.status != 3 && full.media_type != 33) ? (containsSupport(USER_EMAIL) ? 'block' : 'none') : 'none'}">
                                                <div style="width: 24px; display: inline-block"> 
                                                    <i class="fas fa-redo-alt" style="font-size: 12pt; margin-left: 3px"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.whatsapp_broadcast_community_alert_resend}</span>
                                            </a>
                                            <a id="${full.token}" class="dropdown-item ${(full.status == 1 || full.status == 2 || full.status == 5 || full.status == 6 || checkTimeToEdit(full.schedule)) ? 'table-action-disabled disabled' : 'table-action-edit'}"  
                                                style="cursor: pointer; display:none" title="${(full.status == 1 || full.status == 2 || full.status == 5 || full.status == 6) ? alertEditDatatable(full.status) : checkTimeToEdit(full.schedule) ? GLOBAL_LANG.whatsapp_broadcast_community_datatables_edit_less_than_one_hour : GLOBAL_LANG.whatsapp_broadcast_community_datatables_edit_column_action}">
                                                <div style="width: 24px; display: inline-block"> 
                                                    <i class="far fa-edit" style="font-size: 12pt; margin-left: 3px"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.whatsapp_broadcast_community_datatables_edit_column_action}</span>
                                            </a>
                                        </div>
                                    </div>`
                }
            },
            {
                orderable: false, targets: [0, 1, 5]
            }
        ],
        order: [[2, 'desc']],
        pagingType: "numbers",
        pageLength: 10,
        destroy: true,
        fixedHeader: true,
        responsive: true,
        lengthChange: true,
        searching: true,
        paginate: true,
        info: true,
        language: {
            url: `${document.location.origin}/assets/lang/${localStorage.getItem("language")}.json`
        },
        drawCallback: function (settings) {
            for (const row of [...document.querySelectorAll(".check-box")]) {
                for (const campaign of Campaign.cancel_list) {
                    if (campaign.token == row.id) {
                        Table.row.selected(row);
                    }
                }
            }

            if (settings.json.recordsTotal == 0) {
                $("#modalExport").attr('disabled', true);
            } else {
                $("#modalExport").attr('disabled', false);
            }

            const community = {
                search: $("#search").val(),
                input_search: $("#input-search").val(),
                community: $("#multiselect-community").val(),
                channel: $("#multiselect2").val(),
                status: $("#select-status").val(),
                dt_start: $("#dt-start").val(),
                dt_end: $("#dt-end").val(),
            }

            let filter = localStorage.getItem("filters");

            filter = filter ? JSON.parse(filter) : {};
            filter.community = community;

            localStorage.setItem("filters", JSON.stringify(filter));

            document.querySelectorAll(".box-inner-datatable").forEach(async (elm) => {

                const media_type = elm.children[0].getAttribute("data-media-type");

                switch (media_type) {
                    case "1":

                        elm.children[0].src = document.location.origin = "/assets/img/panel/big-texto.png";
                        elm.children[0].style.padding = "0px";
                        break;

                    case "3":

                        const convertToBlob = (blob) => {
                            return new Promise((resolve, reject) => {

                                try {
                                    const reader = new FileReader();
                                    reader.readAsDataURL(blob);
                                    reader.onloadend = () => resolve(reader.result);

                                } catch (error) {
                                    reject(document.location.origin + "/assets/img/referral.png");
                                }
                            });
                        }

                        var response = await fetch(elm.children[0].getAttribute("data-media-url"));
                        var blob = await response.blob();

                        elm.children[0].src = await convertToBlob(blob);
                        elm.children[0].style.padding = "0px";
                        break;

                    case "4":

                        elm.children[0].src = document.location.origin = "/assets/img/panel/pdf_icon.png";
                        elm.children[0].style.padding = "0px";

                        var response = await fetch(elm.children[0].getAttribute("data-media-url"));
                        var data = await response.blob();
                        var metadata = { type: 'application/pdf' };
                        var file = new File([data], "img.pdf", metadata);

                        elm.children[0].setAttribute("data-media-url", URL.createObjectURL(file));
                        break;

                    case "5":

                        var response = await fetch(elm.children[0].getAttribute("data-media-url"));
                        var data = await response.blob();
                        var metadata = { type: 'video/mp4' };
                        var file = new File([data], "img.jpg", metadata);

                        var video = document.createElement("video");
                        video.src = URL.createObjectURL(file);
                        video.style.padding = "0px";
                        video.setAttribute("data-media-url", elm.children[0].getAttribute("data-media-url"));
                        video.setAttribute("data-media-type", elm.children[0].getAttribute("data-media-type"));

                        elm.children[0].remove();
                        elm.appendChild(video);
                        break;

                    default:
                        break;
                }
            });
        }
    });
}


function getCurrentTime() {
    const now = new Date();
    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    return `${hours}:${minutes}`;
}


function checkDate(date_start) {

    if (document.getElementById("date_start").value == "") return false;
    if (document.getElementById("time_start").value == "") return false;

    let date = date_start;
    date = date.split("/")[2] + "-" + date.split("/")[1] + "-" + date.split("/")[0];

    let date_current = new Date();

    let year = date_current.getFullYear();
    let month = date_current.getMonth() + 1;
    let day = date_current.getDate();

    if (month < 10) month = "0" + month;
    if (day < 10) day = "0" + day;

    date_current = year + "-" + month + "-" + day;

    if (date === date_current) {
        const current_time = getCurrentTime();
        const time_start = document.getElementById('time_start');

        if (time_start.value < current_time) {
            time_start.value = current_time;
        }
    }

    const date__start = new Date(date);
    const date__current = new Date(date_current);

    if (date__start >= date__current) {
        return true;
    } else {
        return false;
    }
}

function checkTimeValidEdit() {
    return new Promise(async (resolve, reject) => {
        try {
            const url = window.location.href;
            let formValidation = true;

            if (url.includes("/edit/")) {
                const token = url.split('/').pop();
                const response = await fetch(`${document.location.origin}/publication/whatsapp/broadcast/community/checktime/edit/${token}`);
                const data = await response.json();

                if (data.errors) {
                    alertErrors(data);
                    formValidation = false;
                }
            }

            resolve(formValidation);
        } catch (error) {
            console.error('Erro ao buscar dados:', error);
            reject(error);
        }
    });
}

function validOptPoll(inputs_array) {
    let filled_count = 0;

    for (let input of inputs_array) {
        if (input.value !== '') {
            filled_count++;
        }

        if (filled_count >= 2) {
            return true;
        }
    }

    return false;
}

async function submit() {

    if (!await checkTimeValidEdit()) return;

    form_validation = true;

    const input_title = formValidation({ field: document.getElementById("input_title"), text: GLOBAL_LANG.whatsapp_broadcast_community_valid_title, required: true, min: 3, max: 100 });
    const select_channel = formValidation({ field: document.getElementById("multiselect"), text: GLOBAL_LANG.whatsapp_broadcast_community_valid_channel, required: true });
    const select_community = formValidation({ field: document.getElementById("multiselect2"), text: GLOBAL_LANG.whatsapp_broadcast_community_valid_community, required: true });

    if (!checkDate(document.getElementById("date_start").value)) {
        $("#alert_date_start").show();
        form_validation = false;
    } else
        $("#alert_date_start").hide();


    switch (document.getElementById("type_broadcast").value) {
        case "text":
            form_validation = formValidation({ field: document.getElementById("input-data"), text: GLOBAL_LANG.whatsapp_broadcast_community_validation_message, required: true, min: 1, max: 1024 });
            break;
        case "media":
            if (document.querySelectorAll(".box-broadcast").length === 0) {
                $("#alert_upload_media").show();
                form_validation = false;
            } else
                $("#alert_upload_media").hide();
            break
        case "poll":
            let inputs = document.querySelectorAll(".list .opt");
            let inputs_array = Array.from(inputs);

            if (!validOptPoll(inputs_array)) {
                $("#alert_message_poll").show();
                form_validation = false;
            } else
                $("#alert_message_poll").hide();


            document.querySelectorAll(".list .opt").forEach(elm => {
                if (elm.nextElementSibling.style.display == "block") {
                    form_validation = false;
                    return;
                }
            });
            break;
        default:
            break;
    }

    if ($("#type_text").is(":hidden") && $("#type_media").is(":hidden") && $("#type_message_poll").is(":hidden")) {
        form_validation = false;
        $("#alert_input_img_text").show();
    } else {
        $("#alert_input_img_text").hide();
    }

    if (input_title && select_channel && select_community && form_validation) {
        document.querySelector(".btn-success").disabled = true;
        $("form").unbind('submit').submit();
    }
}

function checkTimeToEdit(schedule) {

    const [date_part, time_part] = schedule.split(' ');
    const [day, month, year] = date_part.split('/');
    const [hour, minute, second] = time_part.split(':');

    const schedule_date = new Date(year, month - 1, day, hour, minute, second);
    const date = new Date();
    const interval_ms = schedule_date - date;

    if (interval_ms > (1000 * 60 * 60))
        return false;

    return true;
}


function alertEditDatatable(status) {

    switch (status) {
        case '2':
            message = GLOBAL_LANG.whatsapp_broadcast_community_datatables_edit_status_2
            break;
        case '5':
            message = GLOBAL_LANG.whatsapp_broadcast_community_datatables_edit_status_5
            break;
        case '1':
        case '6':
            message = GLOBAL_LANG.whatsapp_broadcast_community_datatables_edit_status_6
            break;
        default:
            message = GLOBAL_LANG.whatsapp_broadcast_community_datatables_edit_default
            break;
    }

    return message;
}



function updateCharacterCount() {

    const inputElement = $("#input-data");
    const countElement = document.getElementById("count_caracter");

    let caractrs = inputElement.val() || "";

    const countEnters = (caractrs.match(/\n/g) || []).length;

    let totalLength = caractrs.length;

    const maxLength = 1024 - countEnters;
    if (totalLength > maxLength) {
        caractrs = caractrs.slice(0, maxLength);
        inputElement.val(caractrs);
    }

    totalLength = caractrs.length + countEnters;

    inputElement.prop("maxLength", maxLength);

    countElement.textContent = 1024 - totalLength;
    countElement.style.color = "red";
    countElement.style.fontSize = ".875rem";
}


function alertErrors(data) {
    if (data.errors?.code == "TA-023") {
        swal({
            title: GLOBAL_LANG.whatsapp_broadcast_community_error_title,
            text: `${GLOBAL_LANG.whatsapp_broadcast_community_error_ta023} (${data.errors.code})`,
            type: "error",
            buttonsStyling: !1,
            confirmButtonClass: "btn btn-danger"
        });
    }

    if (data.errors?.code == "TA-024") {
        swal({
            title: GLOBAL_LANG.whatsapp_broadcast_community_error_title,
            text: `${GLOBAL_LANG.whatsapp_broadcast_community_error_ta024} (${data.errors.code})`,
            type: "error",
            buttonsStyling: !1,
            confirmButtonClass: "btn btn-danger"
        });
    }
}


$(document).ready(function () {

    $('#tex_area').each(function () {

        if ($("#thumb_image").length !== 0) {
            if (this.scrollHeight <= 175) {
                this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
            } else {
                this.setAttribute('style', 'height: 175px;');
            }
        } else {
            if (this.scrollHeight <= 450) {
                this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
            } else if ($('#videoPreview').attr('width') >= 190) {
                this.setAttribute('style', 'height: 120px;');
            } else {
                this.setAttribute('style', 'height: 450px;');
            }
        }

    }).on('input', function () {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    const multiselectAdd = document.getElementById("multiselect");
    const view = document.getElementById("view");
    const edit = document.getElementById("edit");

    if (multiselectAdd != null && !edit) {

        //*** Add ***//

        let before, id_before, later, id_later;

        new MSFmultiSelect(
            document.querySelector('#multiselect'), {
            theme: 'theme1',
            selectAll: true,
            searchBox: true,
            width: "100%",
            height: 47,
            placeholder: GLOBAL_LANG.whatsapp_broadcast_community_select,
        });

        new MSFmultiSelect(
            document.querySelector('#multiselect2'), {
            theme: 'theme1',
            selectAll: true,
            searchBox: true,
            width: "100%",
            height: 47,
            placeholder: GLOBAL_LANG.whatsapp_broadcast_community_select,
        });

        $('.time').mask(maskBehavior, spOptions);

        $("body").on("click change keyup keydown paste cut", function () {
            updateCharacterCount();
        });

        selectTypeBroadcast();

        let fileSelect = document.getElementById("fileSelect"),
            fileElem = document.getElementById("fileElem");

        fileSelect.addEventListener("click", function (e) {
            if (fileElem) fileElem.click();
            e.preventDefault();
        }, false);


        $("body").on("mouseover", ".box-broadcast", function () {
            $("#" + this.id).find(".tail").show();
            $("#" + this.id).find(".close-broadcast").css({ "display": "block" });
        });


        $("body").on("mouseout", ".box-broadcast", function () {
            $(".tail").css({ "display": "none" });
            $(".close-broadcast").css({ "display": "none" });
        });


        $("body").on("click", ".close-broadcast", function () {
            $("#" + this.id).parent().parent().parent().parent().remove();

            if ($(".box-broadcast").length < 1) {
                $(".drop-inner-img").show();
                $(".drop-inner-title").show();
                $(".drop-inner-text").show();
            }
            $('#fileElem').val("");
        });


        $("body").on("dragstart", ".box-broadcast", function () {

            let id = this.id;
            id_before_elm = id;

            id_before = $("#" + id).parent().attr("id");
            before = document.getElementById(id);

            preventDefault = true;

            $(".close-broadcast").hide();
        });


        $("body").on("dragover", ".box-broadcast", function () {

            let id = this.id;
            iden = $("#" + id).parent().attr("id");

            $("#" + iden).css({ "border": "1px blue dashed" });
            $("#" + iden).css({ "background": "rgb(174 174 174)", "opacity": " 0.2", "z-index": "99" });
        });


        $("body").on("dragleave", ".box-broadcast", function () {

            let id = this.id;
            iden = $("#" + id).parent().attr("id");

            $("#" + iden).css({ "border": "0px blue dashed" });
            $("#" + id).css({ "width": "auto", "margin-left": "0px" });
            $("#" + iden).css({ "background": "#fff", "opacity": "", "z-index": "" });
        });


        $("body").on("dragend", ".box-broadcast", function (e) {

            var elms = document.querySelectorAll(".broadcast");

            for (var area of elms) {

                if ((e.clientX > area.getBoundingClientRect().x &&
                    e.clientX < area.getBoundingClientRect().x + area.clientWidth) &&
                    (e.clientY > area.getBoundingClientRect().y &&
                        e.clientY < area.getBoundingClientRect().y + area.clientHeight)) {

                    if (area.childElementCount == 1) {

                        id_later = $("#" + area.firstElementChild.id).parent().attr("id");
                        later = area.firstElementChild;

                        $("#" + area.firstElementChild.id).remove();
                        $("#" + id_before_elm).remove();

                        let move_later = document.getElementById(id_later);
                        let move_before = document.getElementById(id_before);

                        move_before.appendChild(later);
                        move_later.appendChild(before);
                    }
                }
            }

            preventDefault = false;

            setTimeout(function () { addName(); }, 500);

            $(".box-broadcast").css({ "width": "auto", "margin-left": "0px" });
            $(".broadcast").css({ "border": "0px #1212ffdb dashed" });
            $(".broadcast").css({ "background": "#fff", "opacity": "", "z-index": "" });
        });


        $("#toggle-segmented-community").on("click", function () {

            if ($("#toggle-segmented-community").is(":checked")) {
                $("#segmented_community").show();

                if ($(".new").length > 1) {
                    $(".ignore.add").click();
                }

                if ($(".new").length > 1) {
                    $(".ignore.add").click();
                }

            } else {
                $("#segmented_community").hide();
                $("#select_segmented_community").val(0)
            };
        });

        $("body").on("click", "#msf_multiselect1 label li input", function () {

            const check__options = document.querySelectorAll("label li input");

            let community = "";
            let iCountCommunity = 0;

            for (option of check__options) {
                if (option.checked) {
                    if (option.value != "on") {
                        if (iCountCommunity == 0) {
                            community = option.value;
                            iCountCommunity++;
                        } else {
                            community += "," + option.value;
                        }
                    }
                }
            }

            const opt = document.querySelectorAll("#segmented_community option");
            for (const elm of opt) if (elm.value != 0) elm.remove();

            if (community != "") {

                fetch(document.location.origin + "/publication/whatsapp/broadcast/community/list/" + community).then(res => res.json().then(res => {

                    const msf_multiselect_container = document.querySelectorAll("#segmented_community .msf_multiselect_container");

                    for (elm of msf_multiselect_container) elm.remove();

                    $("#multiselect2").empty();

                    if (res.length > 0) {
                        for (const elm of res) {
                            $("#multiselect2").append(`<option value="${elm.id_community}">${elm.name}</option>`);
                        }
                    }

                    var select2 = new MSFmultiSelect(
                        document.querySelector('#multiselect2'), {
                        theme: 'theme2',
                        selectAll: true,
                        searchBox: true,
                        width: 'auto',
                        height: 47,
                        onChange: function (checked, value, instance) {
                            if (select2 == "") select2 = value;

                            if (checked == false) {
                                $(".ignore.add").children().prop('checked', false);
                            };
                        },
                    });

                    if (res.length == 0) {

                        const opt = document.querySelectorAll("#segmented_community ul label");

                        for (let i = 0; i < opt.length; i++) {
                            if (i > 1) {
                                opt[i].remove()
                            }
                        }
                    }
                }));
            } else {
                const opt = document.querySelectorAll("#segmented_community ul label");

                for (let i = 0; i < opt.length; i++) {
                    if (i > 1) {
                        opt[i].remove()
                    }
                }
            }
        });



        $('body').on('change keyup keydown paste cut', '#tex_ar', function () {
            $(".tail").css({ "display": "none" });
            $(this).height(0).height(this.scrollHeight);

            let type = $(this).attr("data-type");
            if (type != "type-image") $(this).attr('maxlength', '700');

        }).find('#tex_ar').change();

        $("#button_text").on("click", function () {
            changeTypeWarning("text");
        });

        $("#button_media").on("click", function () {
            changeTypeWarning("media");
        });

        // $("#button_poll").on("click", function () {
        //     changeTypeWarning("poll");
        // });


        $("form").submit(event => event.preventDefault());
        document.querySelector(".btn-success").addEventListener("click", submit);

    } else if (edit) {

        //** EDIT **//

        $('.time').mask(maskBehavior, spOptions);

        if (document.getElementById("count_caracter")) {
            $("body").on("click change keyup keydown paste cut", function () {
                updateCharacterCount();
            });
        }

        if (document.getElementById("media_campaign")) {
            let element = {
                "url": document.getElementById("media_campaign").getAttribute("media_url"),
                "text": document.getElementById("media_campaign").value,
                "media_type": document.getElementById("media_campaign").getAttribute("media_type")
            }
            editBroadcast(element);
        }

        if (document.getElementById("type_message_poll")) {
            const element = JSON.parse(document.querySelector("#json_poll").value);
            const options_length = Object.keys(element.option);
            const element_length = options_length.length == 8 ? options_length.length : options_length.length + 1;

            createOption(element_length, true, element);
        }

        $("#toggle_validity").on("click", function () {
            if ($("#toggle_validity").is(":checked")) {
                $("#date-validity").show(50);
                $("#date_start_validity").prop("disabled", false);
                $("#time_start_validity").prop("disabled", false);

            } else {
                $("#date-validity").hide(50);
                $("#date_start_validity").prop("disabled", true);
                $("#time_start_validity").prop("disabled", true);
            };
        });

        $("form").submit(event => event.preventDefault());
        document.querySelector(".btn-success").addEventListener("click", submit);

    } else if (view != null) {

        //** VIEW **//

        var preview_body = document.getElementById("wa-body");
        preview_body.scrollTop = preview_body.scrollHeight;

        const box_inner_pdf = document.getElementsByClassName("box-inner-pdf")[0];
        const media_type = box_inner_pdf?.getAttribute("data-media-type");
        const media_url = box_inner_pdf?.getAttribute("data-media-url");

        if (media_type === "4") {

            const pdfjsLib = window['pdfjs-dist/build/pdf'];
            pdfjsLib.GlobalWorkerOptions.workerSrc = document.location.origin + "/assets/lib/build_pdf/pdf_build_pdf.worker.js";

            const loadingTask = pdfjsLib.getDocument(media_url);
            loadingTask.promise.then(function (pdf) {

                const pageNumber = 1;
                pdf.getPage(pageNumber).then(function (page) {

                    let desiredWidth = 240;
                    let viewport = page.getViewport({ scale: 1, });
                    let scale = desiredWidth / viewport.width;
                    let scaledViewport = page.getViewport({ scale: scale, });

                    let canvas = document.createElement("canvas");
                    canvas.id = "pdf-canva";

                    let context = canvas.getContext('2d');
                    canvas.height = 180;
                    canvas.width = 274;

                    const renderContext = {
                        canvasContext: context,
                        viewport: scaledViewport
                    };

                    const renderTask = page.render(renderContext);
                    renderTask.promise.then(function () {
                        document.querySelector("#thumb_image").remove();

                        let pdf_link = document.createElement("a");
                        pdf_link.href = media_url;
                        pdf_link.setAttribute("target", "_blank");
                        pdf_link.appendChild(canvas);

                        box_inner_pdf.appendChild(pdf_link);
                    });
                });
            }, function (reason) {
                console.error(reason);
            });
        }

    } else {

        //** FIND **//

        $('#search').on('keyup', (e) => {
            if (e.which == 13) {
                document.getElementById("input-search").value = e.target.value;
                find();
            }
        });
        find();

        $("#datatable-basic").on("click", ".checkbox-table", function (e) {
            this.firstElementChild.click();
        });

        $("#datatable-basic").on("click", ".check-box", function (e) {
            Table.row.click(e);
            e.stopPropagation();

            let cancel_list = Campaign.cancel_list.length;

            if (cancel_list > 0) {
                $("#btn-cancel").show();
            } else {
                $("#btn-cancel").hide();
            }

            $("#count_status").text(cancel_list);
        });

        $('#datatable-basic tbody').on('mouseover', 'td', async function () {
            const is_class = this.attributes.class;

            if (is_class != undefined) {

                const thumb = this.attributes.class.nodeValue;

                if (thumb.includes("thumb")) {

                    const media_type = this.children[0].children[0].getAttribute("data-media-type");

                    const box = document.createElement("div");
                    box.className = "preview-thumb";

                    $(".preview-thumb").remove();

                    switch (media_type) {
                        case "1":

                            var text = document.createElement("div");
                            text.className = "textMessage";

                            var textarea = document.createElement("textarea");
                            textarea.className = "form-control";
                            textarea.innerHTML = this.children[0].children[0].getAttribute("data-text");
                            textarea.rows = "6";

                            text.appendChild(textarea);
                            box.appendChild(text);
                            this.prepend(box);
                            break;

                        case "3":

                            var img = document.createElement("img");
                            img.src = this.firstChild.firstChild.attributes.src.nodeValue;

                            box.appendChild(img);
                            this.prepend(box);
                            break;

                        case "4":

                            var iframe = document.createElement("iframe");
                            iframe.src = this.firstChild.firstChild.getAttribute("data-media-url");
                            iframe.id = "iframeDocument";
                            iframe.setAttribute("scrolling", "no");

                            box.appendChild(iframe);
                            this.prepend(box);

                            break;

                        case "5":

                            var video = document.createElement("video");
                            video.src = this.firstChild.firstChild.attributes.src.nodeValue;

                            box.appendChild(video);
                            this.prepend(box);
                            break;

                        default:
                            break;
                    }

                    box.animate([
                        { opacity: '0' },
                        { opacity: '1' }
                    ], {
                        duration: 500,
                    });
                }
            }
        });

        $('#datatable-basic tbody').on('mouseout', 'td', function (e) {

            let exist_class, thumb, src, preview;

            exist_class = this.attributes.class;
            preview = this;

            if (exist_class != undefined) {

                thumb = this.attributes.class.nodeValue;

                if (thumb.trim() == "thumb") {

                    $(".preview-thumb").remove();
                }
            }

        });

        $("#datatable-basic").on("click", ".table-action-view", function () {
            window.location.href = "community/view/" + this.id;
        });

        $("#datatable-basic").on("click", ".table-action-edit", function () {
            window.location.href = "community/edit/" + this.id;
        });

        $("#datatable-basic").on("click", ".table-action-resend", function () {
            swal({
                title: GLOBAL_LANG.whatsapp_broadcast_community_alert_resend_title,
                text: GLOBAL_LANG.whatsapp_broadcast_community_alert_resend_text,
                type: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                reverseButtons: true,
                confirmButtonClass: "btn btn-success",
                confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_community_alert_resend_confirmButtonText,
                cancelButtonClass: "btn btn-danger",
                cancelButtonText: GLOBAL_LANG.whatsapp_broadcast_community_alert_resend_cancelButtonText,
            }).then(t => {
                if (t.value == true) {
                    $.post(document.location.origin + "/publication/whatsapp/broadcast/community/resend/" + this.id, function (data) {
                        if (data.success?.status == true) {
                            t.value && swal({
                                title: GLOBAL_LANG.whatsapp_broadcast_community_alert_resend_two_title,
                                text: GLOBAL_LANG.whatsapp_broadcast_community_alert_resend_two_text,
                                type: "success",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-success"
                            });
                            $("#datatable-basic").DataTable().ajax.reload(null, false);
                        }
                    });
                }
            });
        });

        $("#btn-cancel").on("click", function () {
            let tokens = new FormData();

            Campaign.cancel_list.forEach(element => {
                tokens.append("tokens[]", element.token);
            });

            swal({
                title: GLOBAL_LANG.whatsapp_broadcast_community_alert_group_delete_title,
                text: GLOBAL_LANG.whatsapp_broadcast_community_alert_group_delete_text,
                type: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                reverseButtons: true,
                confirmButtonClass: "btn btn-success",
                confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_community_alert_group_delete_confirmButtonText,
                cancelButtonClass: "btn btn-danger",
                cancelButtonText: GLOBAL_LANG.whatsapp_broadcast_community_alert_group_delete_cancelButtonText,
            }).then(t => {
                if (t.value == true) {

                    $.ajax({
                        type: "POST",
                        url: document.location.origin + "/publication/whatsapp/broadcast/community/cancelgroup",
                        data: tokens,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {

                            if (data.errors?.code == "TA-027") {
                                t.value && swal({
                                    title: GLOBAL_LANG.whatsapp_broadcast_community_validation_cancel_title,
                                    text: GLOBAL_LANG.whatsapp_broadcast_community_validation_cancel_description,
                                    type: "error",
                                    buttonsStyling: !1,
                                    confirmButtonClass: "btn btn-danger"
                                });
                            }

                            if (data.success?.status === true) {
                                t.value && swal({
                                    title: GLOBAL_LANG.whatsapp_broadcast_community_alert_group_delete_two_title,
                                    text: GLOBAL_LANG.whatsapp_broadcast_community_alert_group_delete_two_text,
                                    type: "success",
                                    buttonsStyling: !1,
                                    confirmButtonClass: "btn btn-success"
                                });
                                $("#datatable-basic").DataTable().ajax.reload(null, false);
                            }

                            Campaign.clear();
                            document.getElementById("btn-cancel").style.display = "none";
                        },
                        error: function () {
                            t.value && swal({
                                title: GLOBAL_LANG.whatsapp_broadcast_community_validation_cancel_title,
                                text: GLOBAL_LANG.whatsapp_broadcast_community_validation_cancel_description,
                                type: "error",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-danger"
                            });
                        }

                    });

                }
            });

        });

        $("#datatable-basic").on("click", ".table-action-delete", function () {

            swal({
                title: GLOBAL_LANG.whatsapp_broadcast_community_alert_delete_title,
                text: GLOBAL_LANG.whatsapp_broadcast_community_alert_delete_text,
                type: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                reverseButtons: true,
                confirmButtonClass: "btn btn-success",
                confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_community_alert_delete_confirmButtonText,
                cancelButtonClass: "btn btn-danger",
                cancelButtonText: GLOBAL_LANG.whatsapp_broadcast_community_alert_delete_cancelButtonText,
            }).then(t => {
                if (t.value == true) {
                    $.post("community/cancel/" + this.id, function (data) {

                        if (data.errors?.code == "TA-027") {
                            t.value && swal({
                                title: GLOBAL_LANG.whatsapp_broadcast_community_validation_cancel_title,
                                text: GLOBAL_LANG.whatsapp_broadcast_community_validation_cancel_description,
                                type: "error",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-danger"
                            });
                        }

                        if (data.success?.status === true) {
                            t.value && swal({
                                title: GLOBAL_LANG.whatsapp_broadcast_community_alert_delete_two_title,
                                text: GLOBAL_LANG.whatsapp_broadcast_community_alert_delete_two_text,
                                type: "success",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-success"
                            });
                            $("#datatable-basic").DataTable().ajax.reload(null, false);
                        }

                        Campaign.clear();
                        document.getElementById("btn-cancel").style.display = "none";
                    });
                }
            });
        });

        $('#sendEmailExport').on('click', () => modalExport());
        $("#modalFilter").one("click", () => modalFilter());

    }
});

function containsSupport(email) {
    const emailLowerCase = email.toLowerCase();
    return emailLowerCase.startsWith('suporte') && emailLowerCase.endsWith('@talkall.com.br');
}

const Drag = {
    Sortable: null,
    create(slider) {
        if (this.Sortable != null)
            this.Sortable.destroy();

        this.Sortable = new Sortable(slider, {
            handle: '.handle',
            animation: 150,
            onEnd: function (event) {
                updateFileInput();
                updatePreviewPoll(event);
            }
        });
    }
}

function addOptionInput(data) {
    const inputs = document.querySelectorAll(`input[name^="${data}"]`);
    const next_number = inputs.length;

    return data + next_number;
}

function updatePreviewPoll(e) {
    const option_preview = document.getElementById("option_preview_" + e.item.id);
    const id_brotter = e.item.nextElementSibling == null ? 0 : e.item.nextElementSibling.id;

    document.getElementById("option_preview_" + e.item.id).remove();

    if (id_brotter === 0) {
        document.querySelector(".preview-poll .option").appendChild(option_preview);
    } else {
        $("#option_preview_" + id_brotter).before(option_preview);
    }
}

function updateFileInput() {
    const inputs = document.querySelectorAll(".list .opt");

    const inputs_array = Array.from(inputs);
    const filtered_inputs = inputs_array.filter(input => input.value !== '');

    for (let i = 0; i < filtered_inputs.length; i++) {
        filtered_inputs[i].name = "option" + i;
    }

    for (let i = 0; i < inputs.length; i++) {
        if (inputs[i].value == "")
            inputs[i].name = "";
    }
}

function showMenuType(type) {
    document.getElementById("menu_type").style.display = "flex";

    document.getElementById("button_text").style.display = "block";
    document.getElementById("button_media").style.display = "block";
    // document.getElementById("button_poll").style.display = "block";

    document.getElementById(type).style.display = "none";
}

function selectTypeBroadcast() {

    const hideTypeSelect = () => {
        document.getElementById("select_type").style.display = "none";
    }

    const text = () => {
        hideTypeSelect();
        showMenuType("button_text");

        document.getElementById("type_broadcast").value = "text";
        document.getElementById("type_text").style.display = "flex";
    };

    const midia = () => {
        hideTypeSelect();
        showMenuType("button_media");

        document.getElementById("type_broadcast").value = "media";
        document.getElementById("type_media").style.display = "flex";
    };

    // const poll = () => {
    //     hideTypeSelect();
    //     createOption(2, true);
    //     showMenuType("button_poll");

    //     document.getElementById("type_broadcast").value = "poll";
    //     document.getElementById("type_message_poll").style.display = "flex";
    // };

    document.getElementById("status_text").addEventListener("click", text);
    document.getElementById("status_image").addEventListener("click", midia);
    // document.getElementById("status_poll").addEventListener("click", poll);
}

function createOption(amount, header, element = "") {

    let uuid = Math.floor(Math.random() * 100000);

    const optionNullBrother = (item) => {
        if (item.parentElement.parentElement.nextElementSibling === null) {
            createOption(1, false);
        }
    }

    const allOptionsFilled = (item) => {
        let empty = [];

        item.parentElement.parentElement.parentElement.childNodes.forEach(elm => {
            if (elm.childNodes[0].firstChild.value.trim() === "") {
                empty.push(true);
            }
        });

        if (empty.length == 0) {
            createOption(1, false);
            empty = [];
        }
    }

    const removeEmptyOption = (item) => {
        if (item.value.length === 0) {
            item.parentElement.parentElement.parentElement.childNodes.forEach(elm => {
                if (elm.childNodes[0].firstChild.value.length == 0) {
                    if (item.parentElement.parentElement.id !== elm.id) {
                        if (item.parentElement.parentElement.parentElement.childNodes.length > 2) {
                            document.getElementById("option_preview_" + elm.id).remove();
                            elm.remove();
                        }
                    }
                }
            });
        }
    }

    const warningEqualOptions = (item) => {
        const text = item.value;

        item.parentElement.parentElement.querySelector('.alert-opt').style.display = "none";
        item.style.borderBottom = "solid #007AFF 3.5px";

        item.parentElement.parentElement.parentElement.childNodes.forEach(elm => {
            if (item.id !== elm.childNodes[0].firstChild.id) {
                if (elm.childNodes[0].firstChild.value.trim() === text.trim()) {
                    item.parentElement.parentElement.querySelector('.alert-opt').style.display = "block";
                    item.style.borderBottom = "solid #ff0000 3.5px";
                }
            }
        });

        if (text == "") {
            item.style.borderBottom = "solid #007AFF 3.5px";
            item.parentElement.parentElement.querySelector('.alert-opt').style.display = "none";
            document.getElementById("text_preview" + item.id.split("input_")[1]).innerHTML = GLOBAL_LANG.whatsapp_broadcast_community_message_poll_add;
        }
    }

    const countCaracter = (item) => {
        if (item.value.length > 20) {

            let caractrs = item.value;
            let id = "count_caracter_" + item.id.split("_")[1];
            let amount = document.getElementById(id) == null ? document.getElementById("count_caracter_question") : document.getElementById(id);
            let qtde = document.getElementById(id) == null ? 255 : 100;

            let countEnters = (caractrs.match(/\n/g) || []).length;
            let totalPHP = `${caractrs.length + countEnters}`;

            if (totalPHP >= qtde) {
                item.maxLength = `${qtde - countEnters}`;

                caractrs = caractrs.slice(0, `${qtde - countEnters}`);
                item.value = caractrs;

            } else {
                item.maxLength = qtde.toString();
            }

            totalPHP = `${caractrs.length + countEnters}`;

            amount.textContent = qtde - `${totalPHP}`;
            amount.style.color = "red";
            amount.style.fontSize = ".875 rem";
            amount.style.display = "inline";
        } else {
            let id = "count_caracter_" + item.id.split("_")[1];
            let amount = document.getElementById(id) == null ? document.getElementById("count_caracter_question") : document.getElementById(id);
            amount.style.display = "none";
        }
    }

    const removeFocus = (item) => {
        if (item.value.trim().length == 0) item.value = "";

        if (item.value == "") {
            item.parentElement.parentElement.lastElementChild.children[0].style.display = "none";
            item.style.borderBottom = "solid #525f7f 3.4px";
            item.nextElementSibling.style.display = "none";
        }

        removeEmptyOption(item);
        warningEqualOptions(item);
        updateFileInput();

        item.style.borderBottom = "solid #525f7f 3.4px";

        if (item.nextElementSibling.style.display == "block") {
            item.style.borderBottom = "solid #ff0000 3.5px";
        }
    }

    const removeFocusQuestion = (item) => {
        if (item.value.trim().length == 0) item.value = "";

        if (item.value == "") {
            item.style.borderBottom = "solid #525f7f 3.4px";
            item.nextElementSibling.style.display = "none";
        }
    }

    const addTextQuestionPreview = (item) => {
        if (item.value == "")
            document.getElementById("question_preview").innerHTML = GLOBAL_LANG.whatsapp_broadcast_community_message_poll_question;
        else
            document.getElementById("question_preview").innerHTML = item.value;
    }

    const addTextPreview = (item) => {
        if (item.value == "") return;

        document.getElementById("text_preview" + item.id.split("input_")[1]).innerHTML = item.value;
    }

    const setmultipleAnswers = (item) => {
        if (item.checked) {
            document.getElementById("preview-multiple-answers-svg").src = "/assets/icons/kanban/concluded_dark_gray2.svg";
            document.getElementById("preview-multiple-answers").innerHTML = GLOBAL_LANG.whatsapp_broadcast_community_message_pollone_option_or_more;
        } else {
            document.getElementById("preview-multiple-answers-svg").src = "/assets/icons/kanban/concluded_dark_gray1.svg";
            document.getElementById("preview-multiple-answers").innerHTML = GLOBAL_LANG.whatsapp_broadcast_community_message_poll_one_option;
        }
    }

    const headerPoll = () => {
        const list = document.querySelector("#type_message_poll .list");

        const header = Components.div({ class: "_header" });
        const question = Components.div({ class: "question" });

        const question_text = Components.span({ text: GLOBAL_LANG.whatsapp_broadcast_community_poll_question });
        const question_input = Components.input({ name: "question", value: element?.question ?? "", customAttribute: ["placeholder", GLOBAL_LANG.whatsapp_broadcast_community_poll_question_placeholder, "maxlength", "255"] });
        const caracter = Components.span({ class: "count-caracter", text: "0", id: "count_caracter_question" });
        const title_text = Components.span({ text: GLOBAL_LANG.whatsapp_broadcast_community_poll_option });

        const footer = Components.div({ class: "_footer" });
        const title = Components.div({ class: "title" });
        const options = Components.div({ class: "options" });
        const response = Components.div({ class: "response" });

        const text = Components.span({ text: GLOBAL_LANG.whatsapp_broadcast_community_poll_multiple_answers });
        const check = Components.input({ type: "checkbox", name: "multiple_answers", customAttribute: (!element || element.multiple_answers) ? ["checked", "true"] : "" });
        check.addEventListener("click", function () { setmultipleAnswers(this) });

        question_input.addEventListener("keydown", function () { countCaracter(this) });
        question_input.addEventListener("paste", function () { countCaracter(this) });
        question_input.addEventListener("cut", function () { countCaracter(this) });
        question_input.addEventListener("keyup", function () { countCaracter(this) });
        question_input.addEventListener("keyup", function () { removeFocusQuestion(this) });
        question_input.addEventListener("keyup", function () { addTextQuestionPreview(this) });

        question.appendChild(question_text);
        question.appendChild(question_input);
        question.appendChild(caracter);
        header.appendChild(question);

        title.appendChild(title_text);
        footer.appendChild(title);
        footer.appendChild(options);
        footer.appendChild(response);

        response.appendChild(text);
        response.appendChild(check);

        list.appendChild(header);
        list.appendChild(footer);
    }

    const optionPoll = (option = "") => {
        if (document.querySelectorAll(".options .item").length >= 8) return;

        const item = Components.div({ class: "item", id: uuid });

        const left = Components.div({ class: "_left" });
        const right = Components.div({ class: "_right" });

        const input = Components.input({ class: "opt", name: addOptionInput("option"), value: option || "", id: "input_" + uuid, customAttribute: ["placeholder", GLOBAL_LANG.whatsapp_broadcast_community_poll_option_add, "maxlength", "100"] });
        const alert = Components.span({ class: "alert-opt", text: GLOBAL_LANG.whatsapp_broadcast_community_poll_option_validion });
        const caracter = Components.span({ class: "count-caracter", text: "0", id: "count_caracter_" + uuid });
        const icon = Components.i({ class: "fas fa-bars handle" });

        input.addEventListener("keyup", function () {
            optionNullBrother(this);
            allOptionsFilled(this);
            removeEmptyOption(this);
            warningEqualOptions(this);
            countCaracter(this);
            addTextPreview(this);
        });

        input.addEventListener("keydown", function () { countCaracter(this) });
        input.addEventListener("paste", function () { countCaracter(this) });
        input.addEventListener("cut", function () { countCaracter(this) });
        input.addEventListener("blur", function () { removeFocus(this) });

        left.appendChild(input);
        left.appendChild(alert);
        right.appendChild(caracter);
        right.appendChild(icon);

        item.appendChild(left);
        item.appendChild(right);
        document.querySelector(".options").appendChild(item);
    }

    const headerPreview = () => {

        const preview_poll = Components.div({ class: "preview-poll" });

        const question = Components.div({ class: "question" });
        const question_span = Components.span({ id: "question_preview", text: element?.question ?? GLOBAL_LANG.whatsapp_broadcast_community_message_poll_question });

        const multiple_answers = Components.div({ class: "multiple-answers" });
        const multiple_answers_svg = Components.img({ id: "preview-multiple-answers-svg", src: (!element || element.multiple_answers) ? "/assets/icons/kanban/concluded_dark_gray2.svg" : "/assets/icons/kanban/concluded_dark_gray1.svg" });
        const multiple_answers_text = Components.span({ id: "preview-multiple-answers", text: (!element || element.multiple_answers) ? GLOBAL_LANG.whatsapp_broadcast_community_message_pollone_option_or_more : GLOBAL_LANG.whatsapp_broadcast_community_message_poll_one_option });
        const option = Components.div({ class: "option" });

        const show_voting = Components.div({ class: "show-voting" });
        const show_voting_span = Components.span({ text: GLOBAL_LANG.whatsapp_broadcast_community_show_votes });

        multiple_answers.appendChild(multiple_answers_svg);
        multiple_answers.appendChild(multiple_answers_text);
        question.appendChild(question_span);
        show_voting.appendChild(show_voting_span);

        preview_poll.appendChild(question);
        preview_poll.appendChild(multiple_answers);
        preview_poll.appendChild(option);
        preview_poll.appendChild(show_voting);

        document.querySelector(".real-time-preview").appendChild(preview_poll);
    }

    const optionPreview = (option = "") => {
        if (document.querySelectorAll(".option .item").length >= 8) return;

        const item = Components.div({ id: "option_preview_" + uuid, class: "item" });
        const top = Components.div({ class: "_top" });

        const box = Components.div({ class: "box-checkbox" });
        const checkbox = Components.div({ type: "checkbox", class: "checkbox-res" });

        const text = Components.div({ class: "text" });
        const text_span = Components.span({ id: "text_preview" + uuid, text: option || GLOBAL_LANG.whatsapp_broadcast_community_message_poll_add });
        const count_voting = Components.span({ class: "count-voting", text: "0" });

        const bottom = Components.div({ class: "_bottom" });
        const voting = Components.div({ class: "voting" });

        box.appendChild(checkbox);
        text.appendChild(text_span);

        top.appendChild(box);
        top.appendChild(text);
        top.appendChild(count_voting);

        bottom.appendChild(voting);

        item.appendChild(top);
        item.appendChild(bottom);

        document.querySelector(".real-time-preview .option").appendChild(item);
    }

    if (header) {
        headerPoll();
        headerPreview();
    }

    for (let i = 0; i < amount; i++) {
        uuid = Math.floor(Math.random() * 100000);
        optionPoll(element ? element.option[i] : "");
        optionPreview(element ? element.option[i] : "");
    }

    Drag.create(document.querySelector(".options"));
}

function modalFilter() {

    if (!$(".msf_multiselect_container").hasClass("msf_multiselect_container")) {
        const msf_multiselect_container = document.querySelectorAll(".msf_multiselect_container");

        for (elm of msf_multiselect_container) elm.remove();

        var select_community = new MSFmultiSelect(
            document.querySelector('#multiselect-community'), {
            theme: 'theme2',
            selectAll: true,
            searchBox: true,
            width: 'auto',
            width: "100%",
            height: 47
        });

        var select2 = new MSFmultiSelect(
            document.querySelector('#multiselect2'), {
            theme: 'theme2',
            selectAll: true,
            searchBox: true,
            width: 'auto',
            width: "100%",
            height: 47
        });
    }

    const resetMultiselect = (element) => {
        if (element === undefined) return;

        const arr = [];
        const options_select = element.select;

        for (let i = 0; i < options_select.length; i++) {
            arr.push(options_select[i].value);
        }

        element.removeValue(arr);
    }


    const search = document.getElementById("search");

    const check_search = document.getElementById("check-search");
    const input_search = document.getElementById("input-search");

    const check_select_community = document.getElementById("check-select-community");
    const mult_select_community = document.getElementById("mult-select-community");
    const verify_select_community = document.getElementById("verify-select-community");

    const check_select2 = document.getElementById("check-select2");
    const mult_select2 = document.getElementById("mult-select2");
    const verify_select2 = document.getElementById("verify-select2");

    const check_status = document.getElementById("check-status");
    const select_status = document.getElementById("select-status");

    const dt_end = document.getElementById("dt-end");
    const dt_start = document.getElementById("dt-start");

    const check_date = document.getElementById("check-date");
    const btn_search = document.getElementById("btn-search");

    const logger = document.querySelectorAll(".logger");

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

    check_select_community.addEventListener("click", () => {
        if (check_select_community.checked) {
            resetMultiselect(select_community);
            mult_select_community.style.display = "block";
            verify_select_community.value = "1";
        }
        else {
            mult_select_community.style.display = "none";
            verify_select_community.value = "2";
        }
    });

    check_select2.addEventListener("click", () => {
        if (check_select2.checked) {
            resetMultiselect(select2);
            mult_select2.style.display = "block";
            verify_select2.value = "1";
        }
        else {
            mult_select2.style.display = "none";
            verify_select2.value = "2";
        }
    });

    check_status.addEventListener("click", () => {
        if (check_status.checked) {
            select_status.style.display = "block";
        } else {
            select_status.value = "";
            select_status.style.display = "none";
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
        search.value = input_search.value;
        find();
    });

    for (elm of logger) {
        elm.style.paddingLeft = "15px";
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
        case 2:
            column = "schedule";
            break;
        case 3:
            column = "community";
            break;
        case 4:
            column = "title";
            break;
        default:
            break;
    }

    $.get(`/export/xlsx?
        search=${$('#search').val() == "" ? $('#input-search').val() : $('#search').val()}
        &community=${$('#verify-select-community').val() == "2" ? "" : $('#multiselect-community').val()}
        &channel=${$('#verify-select2').val() == "2" ? "" : $('#multiselect2').val()}
        &status=${$('#select-status').val()}
        &column=${column}
        &order=${order}
        &dt_start=${$('#dt-start').val()}
        &dt_end=${$('#dt-end').val()}
        &type=publicationWhatsappBroadcastCommunity`, function (response) {
        Swal.fire({
            title: GLOBAL_LANG.whatsapp_broadcast_community_alert_export_title,
            text: GLOBAL_LANG.whatsapp_broadcast_community_alert_export_text,
            type: 'success',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_community_alert_export_confirmButtonText
        });
    });
}

function changeTypeWarning(type) {

    swal({
        title: GLOBAL_LANG.whatsapp_broadcast_community_alert_change_type_title,
        text: GLOBAL_LANG.whatsapp_broadcast_community_alert_change_type_text,
        type: "warning",
        showCancelButton: !0,
        buttonsStyling: !1,
        reverseButtons: true,
        confirmButtonClass: "btn btn-success",
        confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_community_alert_change_type_yes,
        cancelButtonClass: "btn btn-danger",
        cancelButtonText: GLOBAL_LANG.whatsapp_broadcast_community_alert_change_type_no,
    }).then(t => {
        if (t.value == true) {
            switch (type) {
                case "media":
                    showMenuType("button_media");

                    $(".col-6.broadcast").remove();
                    $(".drop-inner-img").show();
                    $(".drop-inner-title").show();
                    $(".drop-inner-text").show();
                    $('#fileElem').val("");

                    document.getElementById("type_text").style.display = "none";
                    document.getElementById("type_media").style.display = "flex";
                    document.getElementById("type_message_poll").style.display = "none";
                    document.getElementById("type_broadcast").value = "media";
                    document.querySelector(".preview-poll")?.remove();
                    break;

                case "text":
                    showMenuType("button_text");

                    document.getElementById("input-data").value = "";
                    document.getElementById("type_text").style.display = "flex";
                    document.getElementById("type_media").style.display = "none";
                    document.getElementById("type_message_poll").style.display = "none";
                    document.getElementById("type_broadcast").value = "text";
                    document.querySelector(".preview-poll")?.remove();
                    break;

                // case "poll":
                //     const list = document.querySelector('#type_message_poll .list');

                //     if (list)
                //         while (list.firstChild) list.removeChild(list.firstChild);

                //     document.getElementById("type_text").style.display = "none";
                //     document.getElementById("type_media").style.display = "none";
                //     document.getElementById("type_message_poll").style.display = "flex";
                //     document.getElementById("type_broadcast").value = "poll";
                //     document.querySelector(".preview-poll")?.remove();

                //     showMenuType("button_poll");
                //     createOption(2, true);
                //     break;
                // default:
                //     break;
            }
        }
    });
}

function countCaracter(e, media_type) {
    let quantity_digits = e.target.value.length;
    let max_digits = media_type == 4 ? 700 : 1024;

    if (quantity_digits <= max_digits) {
        let characters_remaining = max_digits - quantity_digits;
        let parentElement = e.target.parentElement.parentElement;

        let total_digits = parentElement.querySelector('.quantity-caracter');

        total_digits.textContent = characters_remaining;

    } else {

    }

}