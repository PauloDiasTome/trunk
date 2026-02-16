let preventDefault = false;
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
    pause_list: [],
    resume_list: [],
    only_list: [],

    push(token, action) {
        switch (action) {
            case "cancel":
                this.cancel_list.push({ token: token });
                break;
            case "pause":
                this.pause_list.push({ token: token });
                break;
            case "resume":
                this.resume_list.push({ token: token });
                break;
            case "only":
                this.only_list.push({ token: token });
                break;
            default:
                break;
        }
    },
    remove(token, action) {
        let index = null;
        switch (action) {
            case "cancel":
                index = Campaign.cancel_list.findIndex(item => item.token === token);
                if (index !== -1)
                    this.cancel_list.splice(index, 1);
            case "pause":
                index = Campaign.pause_list.findIndex(item => item.token === token);
                if (index !== -1)
                    this.pause_list.splice(index, 1);
                break;
            case "resume":
                index = Campaign.resume_list.findIndex(item => item.token === token);
                if (index !== -1)
                    this.resume_list.splice(index, 1);
                break;
            case "only":
                index = Campaign.only_list.findIndex(item => item.token === token);
                if (index !== -1)
                    this.only_list.splice(index, 1);
                break;
            default:
                break;
        }
    },
    clear() {
        this.cancel_list = [];
        this.pause_list = [];
        this.resume_list = [];
        this.only_list = [];
    }
}


const Table = {
    row: {
        click(e) {
            const row = document.getElementById(e.target.id);

            if (row.checked) {
                if (row.classList.contains("check-box-cancel"))
                    Campaign.push(row.id, "cancel");

                if (row.classList.contains("check-box-pause"))
                    Campaign.push(row.id, "pause");

                if (row.classList.contains("check-box-resume"))
                    Campaign.push(row.id, "resume");

                if (row.classList.contains("check-box-cancel-only"))
                    Campaign.push(row.id, "only");

                Table.row.selected(row);
            } else {
                if (row.classList.contains("check-box-cancel"))
                    Campaign.remove(row.id, "cancel");

                if (row.classList.contains("check-box-pause"))
                    Campaign.remove(row.id, "pause");

                if (row.classList.contains("check-box-resume"))
                    Campaign.remove(row.id, "resume");

                if (row.classList.contains("check-box-cancel-only"))
                    Campaign.remove(row.id, "only");

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


// function anticipate() {
//     
//     let minutes = 30;
//     let date_current = new Date();
//     let timestamp = date_current.setMinutes(date_current.getMinutes() + minutes);

//     let tamp = timestamp;
//     let date = new Date(tamp);

//     let hour = date.getHours();
//     let min = date.getMinutes();

//     if (min < 10) min = "0" + min;
//     if (hour < 10) hour = "0" + hour;

//     let early_date = hour + ":" + min;
//     let early_date_val = hour + ":" + min;

//     const time_start = document.getElementById("time_start");
//     time_start.value = early_date;

//     const time_start_validity = document.getElementById("time_start_validity");
//     time_start_validity.value = early_date_val;
// }


function bytesToSize(bytes) {

    let sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes == 0) return '0 Byte';
    let i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}


function b64toBlob(b64Data, contentType = '', sliceSize = 512) {



    const byteCharacters = atob(b64Data);
    const byteArrays = [];

    for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {

        const slice = byteCharacters.slice(offset, offset + sliceSize);
        const byteNumbers = new Array(slice.length);

        for (let i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
        }

        const byteArray = new Uint8Array(byteNumbers);
        byteArrays.push(byteArray);
    }

    const blob = new Blob(byteArrays, { type: contentType });
    return blob;
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
        $("#i_st_" + id).attr("src", "data:image/jpeg;base64," + data.thumbnail);
        //$("#i_st_" + id).attr("src", document.location.origin + "/assets/img/panel/pdf_example.png");
    } else {
        $("#i_st_" + id).addClass("i_st");
        $("#i_st_" + id).attr("src", "data:image/jpeg;base64," + data.thumbnail);
    }

    $("#i_st_" + id).on("click", function () {
        window.open($("#" + this.id.replace("i_st_", "") + " input").val());
    });

    $("#tail_" + id).addClass("tail");
    $("#tail_" + id).find(".tail-byte").text(bytesToSize(data.size));

    if ($(".b-audio").hasClass("b-audio")) {
        decodeFile(data.ta_id, data.url, data.mimetype);
    }
    setTimeout(function () { addName(); }, 500);
}


function createAudio(data) {

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

    let text_hidden = document.createElement("input");
    text_hidden.setAttribute("name", "text_hidden");
    text_hidden.className = "tex-area";
    text_hidden.type = "hidden";

    let boxInner = document.createElement("div");
    boxInner.className = "box-inner";
    boxInner.style.minHeight = "360px";
    boxInner.style.justifyContent = "inherit";
    boxInner.style.backgroundImage = 'url("../img/panel/backgroundPng.png")';
    boxInner.style.backgroundImage = "linear-gradient(#ff00008f, #0600ff59)";

    let load = document.createElement("img");
    load.src = data.retrieve === false ? document.location.origin + "/assets/img/loads/loading_2.gif" : "";
    load.id = data.retrieve === false ? "load_" + data.ta_id : "";
    load.className = "load";
    load.style.marginTop = "35%";
    load.style.width = "7em";
    load.style.display = "block";
    load.style.marginLeft = "auto";
    load.style.marginRight = "auto";

    let mainContent = document.createElement("div");
    mainContent.className = "main-content b-audio";
    mainContent.style.cursor = "all-scroll";

    let title = document.createElement("span");
    title.innerHTML = "Campanha em áudio";
    title.className = "title";

    let icon = document.createElement("i");
    icon.className = "fas fa-music icon-audio";

    let ta_id = Math.floor(Math.random() * 100000);

    let audio = document.createElement("audio");
    audio.controls = true;
    audio.className = "audio";
    audio.id = data.retrieve === false ? "audio_" + data.ta_id : "audio_" + ta_id;
    audio.src = data.retrieve === false ? "" : decodeFile(ta_id, data.url, "audio/ogg");

    let source = document.createElement("source");
    source.id = data.retrieve === false ? "source_" + data.ta_id : "source_" + ta_id;
    source.src = data.retrieve === false ? data.blob : decodeFile(ta_id, data.url, "audio/ogg");

    let close = document.createElement("img");
    close.className = data.retrieve === false ? "" : "close-broadcast";
    close.src = data.retrieve === false ? "" : document.location.origin + "/assets/img/statusClose.png";
    close.id = data.retrieve === false ? "close_" + data.ta_id : "close_" + Math.floor(Math.random() * 100000);
    close.style.display = "none";
    close.style.position = "absolute";
    close.style.cursor = "pointer";
    close.style.right = "2%";
    close.style.top = "3%";

    let tail = document.createElement("div");
    tail.id = data.retrieve === false ? "tail_" + data.ta_id : "tail_" + Math.floor(Math.random() * 100000);
    tail.className = data.retrieve === false ? "" : "tail";

    let byte = document.createElement("span");
    byte.className = "tail-byte";
    byte.innerHTML = data.retrieve === false ? "" : bytesToSize(parseInt(data.size));
    tail.style.bottom = "-0.1%";
    tail.style.right = "0%";
    tail.style.top = "319px";

    audio.append(source);
    tail.appendChild(byte);
    boxBroadcast.appendChild(url_hidden);
    boxBroadcast.appendChild(byte_hidden);
    boxBroadcast.appendChild(text_hidden);
    boxBroadcast.appendChild(name_hidden);

    mainContent.appendChild(load);
    mainContent.appendChild(close);
    mainContent.appendChild(tail);
    mainContent.appendChild(icon);
    mainContent.appendChild(title);
    mainContent.appendChild(audio);

    boxInner.appendChild(mainContent);
    boxBroadcast.appendChild(boxInner);
    broadcast.appendChild(boxBroadcast);

    const dropBrodcast = document.getElementById("dropBrodcast");
    dropBrodcast.appendChild(broadcast);

    broadcast.animate([
        { opacity: '0' },
        { opacity: '1' }
    ], {
        duration: 500,
    });

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
    textArea.placeholder = GLOBAL_LANG.whatsapp_broadcast_edit_broadcast_placeholder;
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
    text_caracter.textContent = GLOBAL_LANG.whatsapp_broadcast_text_caracter;

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

    const edit = document.getElementById("edit")?.value ?? "";

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
    textArea.placeholder = GLOBAL_LANG.whatsapp_broadcast_edit_broadcast_placeholder;
    textArea.className = "tex-area";
    textArea.name = "description_data";
    textArea.id = "description_data";
    textArea.disabled = edit === "expire" ? true : false;
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
    boxBroadcast.appendChild(caracter);
    boxBroadcast.appendChild(textAbroadcast);
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
                        createAudio(data);
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

function getGroups(id_channel) {

    $("#select_segmented_group").find("option").remove();
    $("#select_segmented_group").append(`<option value="0">${GLOBAL_LANG.whatsapp_broadcast_segments_select_group_placeholder}</option>`);

    fetch(document.location.origin + "/publication/whatsapp/broadcast/waba/listGroups/" + id_channel)
        .then(response => response.json()
            .then(function (response) {

            }));

}


function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.broadcast.search) {
            document.getElementById("search").value = Filters.broadcast.search;
        }

        if (Filters.broadcast.input_search) {
            document.getElementById("input-search").value = Filters.broadcast.input_search;
        }

        if (Filters.broadcast.channel.length !== 0) {
            modalFilter();
            document.getElementById("check-select2").click();

            document.querySelectorAll("#mult-select2 .cust_").forEach((element, index) => {

                if (Filters.broadcast.channel.includes(element.value))
                    document.querySelectorAll("#mult-select2 .cust_")[index].click();
            })
        }

        if (Filters.broadcast.status) {
            modalFilter();
            document.getElementById("check-status").click();
            document.getElementById("select-status").value = Filters.broadcast.status;
        }

        if (Filters.broadcast.dt_start) {
            modalFilter();

            document.getElementById("check-date").click();

            document.getElementById("dt-end").disabled = false;
            document.getElementById("dt-end").type = "date";
            document.getElementById("dt-start").type = "date";

            document.getElementById("dt-start").value = Filters.broadcast.dt_start;
            document.getElementById("dt-end").value = Filters.broadcast.dt_end;
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
            url: "broadcast/find",
            type: "POST",
            data: {
                text: $("#search").val(),
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
                mData: 'media_url'
            },
            {
                mData: 'title'
            },
            {
                mData: 'name'
            },
            {
                mData: 'status'
            },
        ],
        columnDefs: [
            {
                targets: 0,
                render: function (data, type, full, meta) {
                    return `<div class='${full.status == 2 || full.status == 4 || full.status == 5 ? "checkbox-table-disabled" : "checkbox-table"}'><input type='checkbox' ${full.status == 2 || full.status == 4 || full.status == 5 ? "disabled" : ""} id='${full.token}' name='verify_check_box[]' class='check-box ${full.status == 1 || full.status == 3 ? "check-box-cancel check-box-cancel-only" : (full.status == 6 && full.is_paused == 1 ? 'check-box-cancel check-box-resume' : (full.status == 6 && full.is_paused == 2 ? 'check-box-cancel check-box-pause' : 'check-box-cancel'))}'></div>`;
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
                targets: 2,
                render: function (data, type, full, meta) {
                    return '<b>' + full.title + '</b><br>' + full.schedule;
                }
            },
            {
                targets: 4,
                render: function (data, type, full, meta) {
                    let ret = `<span class="badge badge-sm badge-secondary">${GLOBAL_LANG.whatsapp_broadcast_datatable_column_status_processing}</span>`
                    switch (full.status) {
                        case '2':
                            ret = `<span class="badge badge-sm badge-success">${GLOBAL_LANG.whatsapp_broadcast_datatable_column_status_send}</span>`
                            break;
                        case '1':
                        case '3':
                        case '7':
                            ret = `<span class="badge badge-sm badge-secondary">${GLOBAL_LANG.whatsapp_broadcast_datatable_column_status_processing}</span>`
                            break;
                        case '5':
                            ret = `<span class="badge badge-sm badge-danger">${GLOBAL_LANG.whatsapp_broadcast_datatable_column_status_called_off}</span>`
                            break;
                        case '6':
                            switch (full.is_paused) {
                                case "1":
                                    ret = `<span class="badge badge-sm badge-warning">${GLOBAL_LANG.whatsapp_broadcast_datatable_column_status_paused}</span>`
                                    break;
                                case "2":
                                    ret = `<span class="badge badge-sm badge-info">${GLOBAL_LANG.whatsapp_broadcast_datatable_column_status_sending}</span>`
                                    break;
                            }
                            break;

                        default:
                            ret = ret
                            break;
                    }
                    return `<b>${ret}</b>`;
                }
            },
            {
                targets: 5,
                render: function (data, type, full, meta) {

                    let res = `<div class="dropleft">
                                        <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                            <i class="fa fa-ellipsis-v"></i>
                                            <div class="ripple-container"></div>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="multiDropdownMenu">
                                            <a id="${full.token}" class="dropdown-item table-action-view action" style="cursor: pointer">
                                                <div style="width: 24px; display: inline-block"> 
                                                    <i class="fa fa-eye" style="font-size: 11pt"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.whatsapp_broadcast_datatable_column_action_view}</span>
                                            </a>`

                    switch (full.status) {

                        case "6":
                            switch (full.is_paused) {
                                case "1":
                                    res += `<a token="${full.token}" class="dropdown-item table-action-resume action-resume" style="cursor: pointer" data-toggle='tooltip' data-placement='right' title='${GLOBAL_LANG.whatsapp_broadcast_datatable_column_action_resume_distribution}'> 
                                                <div style="width: 24px; display: inline-block"> 
                                                    <i class="far fa-play-circle" style="font-size: 13pt"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.whatsapp_broadcast_datatable_column_action_resume}</span>
                                            </a>`
                                    break;
                                case "2":
                                    res += `<a token="${full.token}" class="dropdown-item table-action-pause action-pause" style="cursor: pointer" data-toggle='tooltip' data-placement='right' title='${GLOBAL_LANG.whatsapp_broadcast_datatable_column_action_pause_distribution}'> 
                                                <div style="width: 24px; display: inline-block"> 
                                                    <i class='far fa-pause-circle' style="font-size: 13pt"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.whatsapp_broadcast_datatable_column_action_pause}</span>
                                            </a>`
                                    break;
                            }
                            break;
                    }

                    res += `<a id="${full.token}" class="dropdown-item ${full.status == 4 ? 'table-action-deleted' : (full.status == 5 || full.status == 2 || full.status == 4 ? 'table-action-deleted' : 'table-action-delete')} action" style="cursor: pointer">
                                <div style="width: 24px; display: inline-block"> 
                                    <i class="fas fa-times" style="font-size: 12pt; margin-left: 3px"></i>
                                </div>
                                <span>${GLOBAL_LANG.whatsapp_broadcast_datatable_column_action_cancel}</span>
                            </a>
                            <a id="${full.token}" class="dropdown-item table-action-resend"  
                            style="cursor: pointer; display:${(full.status != 3) ? containsSupport(USER_EMAIL) == true ? 'block' : 'none' : 'none'}">
                                <div style="width: 24px; display: inline-block"> 
                                    <i class="fas fa-redo-alt" style="font-size: 12pt; margin-left: 3px"></i>
                                </div>
                                <span>${GLOBAL_LANG.whatsapp_broadcast_alert_resend}</span>
                           </a>
                            <a id="${full.token}" class="dropdown-item ${(full.status == 4 || full.status == 5) ? 'table-action-disabled disabled' : 'table-action-edit'}"  
                                style="cursor: pointer; display: none;" title="${(full.status == 4 || full.status == 5) ? alertEditDatatable(full.status) : GLOBAL_LANG.whatsapp_broadcast_datatables_edit_column_action}">
                                <div style="width: 24px; display: inline-block"> 
                                    <i class="far fa-edit" style="font-size: 12pt; margin-left: 3px"></i>
                                </div>
                                <span>${GLOBAL_LANG.whatsapp_broadcast_datatables_edit_column_action}</span>
                            </a>
                        </div>
                     </div>
                  </div>`

                    return res;
                }
            },
            {
                orderable: false, targets: [0, 1, 3, 4]
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

            const broadcast = {
                search: $("#search").val(),
                input_search: $("#input-search").val(),
                channel: $("#multiselect2").val(),
                status: $("#select-status").val(),
                dt_start: $("#dt-start").val(),
                dt_end: $("#dt-end").val(),
            }

            let filter = localStorage.getItem("filters");

            filter = filter ? JSON.parse(filter) : {};
            filter.broadcast = broadcast;

            localStorage.setItem("filters", JSON.stringify(filter));

            document.querySelectorAll(".box-inner-datatable").forEach(async (elm) => {

                const media_type = elm.children[0].getAttribute("data-media-type");

                switch (media_type) {
                    case "1":

                        elm.children[0].src = document.location.origin = "/assets/img/panel/Lorem_Ipsum.png";
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

            input_campaign_canceled = document.querySelectorAll('.checkbox-table-disabled input');
            input_campaign_canceled.forEach(function (input) {
                input.disabled = true;
            });
        }
    });
}

function containsSupport(email) {
    const emailLowerCase = email.toLowerCase();
    return emailLowerCase.startsWith('suporte') && emailLowerCase.endsWith('@talkall.com.br');
}

async function validateFields() {

    const validate_field = await validateField();

    if (validate_field) {
        document.querySelector(".btn-success").disabled = true;
        ruleSchedule();

    } else {
        $("#alert-type-campaign").show();
    };
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
            title: GLOBAL_LANG.whatsapp_broadcast_error_title,
            text: `${GLOBAL_LANG.whatsapp_broadcast_error_ta023} (${data.errors.code})`,
            type: "error",
            buttonsStyling: !1,
            confirmButtonClass: "btn btn-danger"
        });
    }

    if (data.errors?.code == "TA-024") {
        swal({
            title: GLOBAL_LANG.whatsapp_broadcast_error_title,
            text: `${GLOBAL_LANG.whatsapp_broadcast_error_ta024} (${data.errors.code})`,
            type: "error",
            buttonsStyling: !1,
            confirmButtonClass: "btn btn-danger"
        });
    }
}


$(document).ready(function () {

    document.getElementById('CLOSE_AVISO').addEventListener('click', function () {

        window.location.href = document.location.origin + "/account/logoff";

    });

    $('#datatable-basic').on('length.dt', function (e, settings, len) {
        $("#btn-cancel, #btn-pause, #btn-resume").hide();
        showCheckboxAll();
    });

    $('#tex_area').each(function () {

        if ($("#thumb_image").length !== 0) {
            if (this.scrollHeight <= 175) {
                this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
            } else {
                this.setAttribute('style', 'height: 175px;');
            }
        } else {
            if (this.scrollHeight <= 175) {
                this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;');
            } else if ($('#videoPreview').attr('width') >= 190) {
                this.setAttribute('style', 'height: 175px;');
            } else {
                this.setAttribute('style', 'height: 175px;');
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

        //*** ADD ***//

        let before, id_before, later, id_later

        select = new MSFmultiSelect(
            document.querySelector('#multiselect'), {
            theme: 'theme1',
            selectAll: true,
            searchBox: true,
            width: "100%",
            height: 47,
            placeholder: GLOBAL_LANG.whatsapp_broadcast_select,
            onChange: function (checked, value, instance) {
                if (select == "") select = value;

                if (checked == false) {
                    $(".ignore.add").children().prop('checked', false);
                };
            },
        });


        $('.time').mask(maskBehavior, spOptions);

        $("body").on("click change keyup keydown paste cut", function () {
            updateCharacterCount();
        });


        const status_image = document.getElementById("status_image");
        const status_text = document.getElementById("status_text");

        if (status_image != null)
            status_image.addEventListener("click", showMediaInput)

        if (status_text != null)
            status_text.addEventListener("click", showTextInput)

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

        $("#toggle-segmented").on("click", function () {

            if ($("#toggle-segmented").is(":checked")) {
                $("#segmented").show();

                if ($(".new").length > 1) {
                    $(".ignore.add").click();
                }
            } else {
                $("#segmented").hide();
                $("#select_segmented").val(0);
            };
        });

        $("#toggle-segmented-group").on("click", function () {

            if ($("#toggle-segmented-group").is(":checked")) {
                $("#segmented_group").show();

                if ($(".new").length > 1) {
                    $(".ignore.add").click();
                }

                if ($(".new").length > 1) {
                    $(".ignore.add").click();
                }

            } else {
                $("#segmented_group").hide();
                $("#select_segmented_group").val(0)
            };
        });


        $("body").on("click", "label li input", function () {

            if ($("#segmented_group").is(":visible")) {

                const check__options = document.querySelectorAll("label li input");
                let idx = 0;

                for (option of check__options) {
                    if (option.checked) {
                        if (option.value != "on") {
                            idx++;
                        }
                    }
                }


                if (idx > 1) {
                    setTimeout(() => {
                        this.parentNode.parentNode.click();
                    }, 10)

                    swal({
                        title: "Atenção",
                        text: "Não é permitido selecionar mais um canal para campanhas segmentadas",
                        type: "warning",
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_alert_dropzone_confirmButtonText,
                        cancelButtonClass: "btn btn-secondary"
                    });

                }
            }

        });


        $("body").on("change", "label li input", function () {

            const options = document.querySelectorAll("label li.new");
            const check__options = document.querySelectorAll("label li input");
            const form = new URLSearchParams();

            for (let i = 0; i < check__options.length; i++) {
                if (check__options[i].checked) {
                    if (check__options[i].value != "on") {
                        form.append(i, check__options[i].value);
                    }
                }
            }

            if (options.length === 0) {

                $("label li")[1].attributes.class.nodeValue = "ignore add";
                $("label li input")[1].checked = false;

                $("#select_segmented_group").find("option").remove();
                $("#select_segmented_group").append(`<option value="0">${GLOBAL_LANG.whatsapp_broadcast_segments_select_group_placeholder}</>`);

            } else {

                fetch(document.location.origin + "/publication/whatsapp/broadcast/listGroups", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: form.toString()
                })
                    .then(response => response.json()
                        .then(function (response) {

                            $("#select_segmented_group").find("option").remove();
                            $("#select_segmented_group").append(`<option value="0">${GLOBAL_LANG.whatsapp_broadcast_segments_select_group_placeholder}</>`);

                            for (let i = 0; i < response.length; i++) {

                                const option = document.createElement("option");
                                option.value = response[i]['id_group_contact'];
                                option.innerHTML = response[i]['name']

                                const select_segmented_group = document.getElementById("select_segmented_group");
                                select_segmented_group.appendChild(option);

                            }
                        }));
            }

        });


        $('body').on('change keyup keydown paste cut', '#tex_ar', function () {

            $(".tail").css({ "display": "none" });
            $(this).height(0).height(this.scrollHeight);

            let type = $(this).attr("data-type");
            if (type != "type-image") $(this).attr('maxlength', '700');

        }).find('#tex_ar').change();


        if ($(".retrieve").hasClass("retrieve")) {

            $(".retrieve").each(function (idx, elm) {
                setTimeout(function () {
                    let el = {
                        "url": elm.children[0].value,
                        "text": elm.children[1].value,
                        "size": elm.children[2].value,
                        "thumbnail": elm.children[3].value,
                        "media_type": elm.children[4].value,
                        "retrieve": true,
                    }

                    if (el.thumbnail == "" && el.media_type == 2) {
                        createAudio(el);
                    } else {
                        createBroadcast(el);
                    };

                }, 500 + (idx * 350));
            });

        }

        if ($("#segmented_group").is(":visible")) {
            $(".custom-toggle-slider-segmented").click();
        }

        if ($("#date-validity").is(":visible")) {
            $(".custom-toggle-slider-validity").click();
        }

        $("form").submit(event => event.preventDefault());
        document.querySelector(".btn-success").addEventListener("click", validateFields);


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
        document.querySelector(".btn-success").addEventListener("click", validateFields);

    } else if (view != null) {

        //** VIEW **//

        const box_inner_pdf = document.getElementsByClassName("box-inner-pdf")[0];
        const media_type = box_inner_pdf.getAttribute("data-media-type");
        const media_url = box_inner_pdf.getAttribute("data-media-url");

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
                clearAllSelections();
                find();
            }
        });
        find();

        $('#datatable-basic tbody').on('mouseover', 'td', async function () {
            const is_class = this.attributes.class;

            if (is_class != undefined) {

                const thumb = this.attributes.class.nodeValue;

                if (thumb.trim() === "thumb") {

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

        $('#datatable-basic tbody').on('mouseout', 'td', function () {
            const is_class = this.attributes.class;

            if (is_class != undefined) {
                const thumb = this.attributes.class.nodeValue;

                if (thumb.trim() == "thumb") {
                    $(".preview-thumb").remove();
                }
            }
        });

        $("#datatable-basic").on("click", ".table-action-view", function () {
            window.location.href = "broadcast/view/" + this.id;
        });

        $("#datatable-basic").on("click", ".table-action-edit", function () {
            window.location.href = "broadcast/edit/" + this.id;
        });

        $("#datatable-basic").on("click", ".table-action-resend", function () {
            swal({
                title: GLOBAL_LANG.whatsapp_broadcast_alert_resend_title,
                text: GLOBAL_LANG.whatsapp_broadcast_alert_resend_text,
                type: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                reverseButtons: true,
                confirmButtonClass: "btn btn-success",
                confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_alert_resend_confirmButtonText,
                cancelButtonClass: "btn btn-danger",
                cancelButtonText: GLOBAL_LANG.whatsapp_broadcast_alert_resend_cancelButtonText,
            }).then(t => {
                if (t.value == true) {
                    $.post(document.location.origin + "/publication/whatsapp/broadcast/resend/" + this.id, function (data) {
                        if (data.success?.status == true) {
                            t.value && swal({
                                title: GLOBAL_LANG.whatsapp_broadcast_alert_resend_two_title,
                                text: GLOBAL_LANG.whatsapp_broadcast_alert_resend_two_text,
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

        $("#datatable-basic").on("click", ".table-action-delete", function () {
            swal({
                title: GLOBAL_LANG.whatsapp_broadcast_alert_delete_title,
                text: GLOBAL_LANG.whatsapp_broadcast_alert_delete_text,
                type: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                reverseButtons: true,
                confirmButtonClass: "btn btn-success",
                confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_alert_delete_confirmButtonText,
                cancelButtonClass: "btn btn-danger",
                cancelButtonText: GLOBAL_LANG.whatsapp_broadcast_alert_delete_cancelButtonText,
            }).then(t => {
                if (t.value == true) {
                    $.post("broadcast/cancel/" + this.id, function (data) {

                        if (data.errors?.code == "TA-027") {
                            t.value && swal({
                                title: GLOBAL_LANG.whatsapp_broadcast_validation_cancel_title,
                                text: GLOBAL_LANG.whatsapp_broadcast_validation_cancel_description,
                                type: "error",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-danger"
                            });
                        }

                        if (data.success?.status === true) {
                            t.value && swal({
                                title: GLOBAL_LANG.whatsapp_broadcast_alert_delete_two_title,
                                text: GLOBAL_LANG.whatsapp_broadcast_alert_delete_two_text,
                                type: "success",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-success"
                            });

                            $("#datatable-basic").DataTable().ajax.reload(null, false);
                            $("#btn-cancel, #btn-pause, #btn-resume").hide();
                        }

                        Campaign.clear();
                        showCheckboxAll();
                        document.querySelector("#dropdownMenuInput").checked = false;
                        document.getElementById("btn-cancel").style.display = "none";
                    });
                }
            });
        });

        $("#datatable-basic").on("click", ".table-action-pause", function () {

            swal({
                title: GLOBAL_LANG.whatsapp_broadcast_alert_broadcast_title,
                text: GLOBAL_LANG.whatsapp_broadcast_alert_broadcast_pause_text,
                type: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                reverseButtons: true,
                confirmButtonClass: "btn btn-success",
                confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_alert_broadcast_confirmButtonText,
                cancelButtonClass: "btn btn-danger",
                cancelButtonText: GLOBAL_LANG.whatsapp_broadcast_alert_broadcast_cancelButtonText,
            }).then(t => {
                if (t.value == true) {

                    let tokens = [];
                    tokens.push($(this).attr('token'));

                    $.ajax({
                        type: "POST",
                        url: document.location.origin + "/publication/whatsapp/broadcast/pause",
                        data: {
                            tokens: tokens
                        },
                        success: function (data) {

                            if (data.errors?.code == "TA-027") {
                                t.value && swal({
                                    title: GLOBAL_LANG.whatsapp_broadcast_error_broadcast_title,
                                    text: GLOBAL_LANG.whatsapp_broadcast_error_broadcast_message,
                                    type: "error",
                                    buttonsStyling: !1,
                                    confirmButtonClass: "btn btn-danger"
                                });
                            }

                            if (data.success?.status === true) {
                                t.value && swal({
                                    title: GLOBAL_LANG.whatsapp_broadcast_alert_broadcast_two_title,
                                    text: GLOBAL_LANG.whatsapp_broadcast_alert_broadcast_pause_two_text,
                                    type: "success",
                                    buttonsStyling: !1,
                                    confirmButtonClass: "btn btn-success"
                                });
                                $("#datatable-basic").DataTable().ajax.reload(null, false);
                                $("#btn-cancel, #btn-pause, #btn-resume").hide();
                                showCheckboxAll();
                            }

                            Campaign.clear();
                            $("#btn-pause").hide();
                            $("[name='csrf_token_talkall']").val(Cookies.get("csrf_cookie_talkall"));
                        },
                        error: function () {
                            t.value && swal({
                                title: GLOBAL_LANG.whatsapp_broadcast_error_broadcast_title,
                                text: GLOBAL_LANG.whatsapp_broadcast_error_broadcast_message,
                                type: "error",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-danger"
                            });
                        }
                    });
                }
            });
        });

        $("#datatable-basic").on("click", ".table-action-resume", function () {

            swal({
                title: GLOBAL_LANG.whatsapp_broadcast_alert_broadcast_title,
                text: GLOBAL_LANG.whatsapp_broadcast_alert_broadcast_resume_text,
                type: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                reverseButtons: true,
                confirmButtonClass: "btn btn-success",
                confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_alert_broadcast_confirmButtonText,
                cancelButtonClass: "btn btn-danger",
                cancelButtonText: GLOBAL_LANG.whatsapp_broadcast_alert_broadcast_cancelButtonText,
            }).then(t => {
                if (t.value == true) {

                    let tokens = [];
                    tokens.push($(this).attr('token'));

                    $.ajax({
                        type: "POST",
                        url: document.location.origin + "/publication/whatsapp/broadcast/resume",
                        data: {
                            tokens: tokens
                        },
                        success: function (data) {

                            if (data.errors?.code == "TA-027") {
                                t.value && swal({
                                    title: GLOBAL_LANG.whatsapp_broadcast_error_broadcast_title,
                                    text: GLOBAL_LANG.whatsapp_broadcast_error_broadcast_message,
                                    type: "error",
                                    buttonsStyling: !1,
                                    confirmButtonClass: "btn btn-danger"
                                });
                            }

                            if (data.success?.status === true) {
                                t.value && swal({
                                    title: GLOBAL_LANG.whatsapp_broadcast_alert_broadcast_two_title,
                                    text: GLOBAL_LANG.whatsapp_broadcast_alert_broadcast_resume_two_text,
                                    type: "success",
                                    buttonsStyling: !1,
                                    confirmButtonClass: "btn btn-success"
                                });
                                $("#datatable-basic").DataTable().ajax.reload(null, false);
                                $("#btn-cancel, #btn-pause, #btn-resume").hide();
                                showCheckboxAll();
                            }

                            Campaign.clear();
                            $("#btn-resume").hide();
                            $("[name='csrf_token_talkall']").val(Cookies.get("csrf_cookie_talkall"));
                        },
                        error: function () {
                            t.value && swal({
                                title: GLOBAL_LANG.whatsapp_broadcast_error_broadcast_title,
                                text: GLOBAL_LANG.whatsapp_broadcast_error_broadcast_message,
                                type: "error",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-danger"
                            });
                        }

                    })

                }
            });

        })

        $("#datatable-basic").on("click", ".checkbox-table", function (e) {
            this.firstElementChild.click();
        });

        $("#datatable-basic").on("click", ".check-box", function (e) {
            Table.row.click(e);
            e.stopPropagation();

            countBroadcastButton();
            showCheckboxAll();
        });

        $("#btn-cancel").on("click", function () {
            let tokens = new FormData();

            Campaign.cancel_list.forEach(element => {
                tokens.append("tokens[]", element.token);
            });

            swal({
                title: GLOBAL_LANG.whatsapp_broadcast_alert_group_delete_title,
                text: GLOBAL_LANG.whatsapp_broadcast_alert_group_delete_text,
                type: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                reverseButtons: true,
                confirmButtonClass: "btn btn-success",
                confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_alert_group_delete_confirmButtonText,
                cancelButtonClass: "btn btn-danger",
                cancelButtonText: GLOBAL_LANG.whatsapp_broadcast_alert_group_delete_cancelButtonText,
            }).then(t => {
                if (t.value == true) {
                    $.ajax({
                        type: "POST",
                        url: document.location.origin + "/publication/whatsapp/broadcast/cancelgroup",
                        data: tokens,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {

                            if (data.errors?.code == "TA-027") {
                                t.value && swal({
                                    title: GLOBAL_LANG.whatsapp_broadcast_validation_cancel_title,
                                    text: GLOBAL_LANG.whatsapp_broadcast_validation_cancel_description,
                                    type: "error",
                                    buttonsStyling: !1,
                                    confirmButtonClass: "btn btn-danger"
                                });
                            }

                            if (data.success?.status === true) {
                                t.value && swal({
                                    title: GLOBAL_LANG.whatsapp_broadcast_alert_group_delete_two_title,
                                    text: GLOBAL_LANG.whatsapp_broadcast_alert_group_delete_two_text,
                                    type: "success",
                                    buttonsStyling: !1,
                                    confirmButtonClass: "btn btn-success"
                                });

                                $("#datatable-basic").DataTable().ajax.reload(null, false);
                            }

                            Campaign.clear();
                            $("#btn-cancel, #btn-pause, #btn-resume").hide();
                            showCheckboxAll();
                        },
                        error: function () {
                            t.value && swal({
                                title: GLOBAL_LANG.whatsapp_broadcast_validation_cancel_title,
                                text: GLOBAL_LANG.whatsapp_broadcast_validation_cancel_description,
                                type: "error",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-danger"
                            });
                        }
                    });
                }
            });
        });

        $("#btn-pause").on("click", function () {
            let tokens = [];

            Campaign.pause_list.forEach(element => {
                tokens.push(element.token);
            });

            swal({
                title: GLOBAL_LANG.whatsapp_broadcast_alert_broadcast_title,
                text: GLOBAL_LANG.whatsapp_broadcast_alert_broadcast_pause_all_text,
                type: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                reverseButtons: true,
                confirmButtonClass: "btn btn-success",
                confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_alert_broadcast_confirmButtonText,
                cancelButtonClass: "btn btn-danger",
                cancelButtonText: GLOBAL_LANG.whatsapp_broadcast_alert_broadcast_cancelButtonText,
            }).then(t => {
                if (t.value == true) {
                    $.ajax({
                        type: "POST",
                        url: document.location.origin + "/publication/whatsapp/broadcast/pause",
                        data: {
                            tokens: tokens
                        },
                        success: function (data) {

                            if (data.success?.status === true) {
                                t.value && swal({
                                    title: GLOBAL_LANG.whatsapp_broadcast_alert_broadcast_two_title,
                                    text: GLOBAL_LANG.whatsapp_broadcast_alert_broadcast_pause_two_all_text,
                                    type: "success",
                                    buttonsStyling: !1,
                                    confirmButtonClass: "btn btn-success"
                                });

                                $("#datatable-basic").DataTable().ajax.reload(null, false);
                            } else {
                                t.value && swal({
                                    title: GLOBAL_LANG.whatsapp_broadcast_error_broadcast_title,
                                    text: GLOBAL_LANG.whatsapp_broadcast_error_broadcast_message,
                                    type: "error",
                                    buttonsStyling: !1,
                                    confirmButtonClass: "btn btn-danger"
                                });
                            }

                            Campaign.clear();
                            showCheckboxAll();
                            $("#btn-cancel, #btn-pause, #btn-resume").hide();
                            $("[name='csrf_token_talkall']").val(Cookies.get("csrf_cookie_talkall"));
                        },
                        error: function (data) {
                            t.value && swal({
                                title: GLOBAL_LANG.whatsapp_broadcast_error_broadcast_title,
                                text: GLOBAL_LANG.whatsapp_broadcast_error_broadcast_message,
                                type: "error",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-danger"
                            });
                        }

                    })

                }
            });

        });

        $("#btn-resume").on("click", function () {
            let tokens = [];

            Campaign.resume_list.forEach(element => {
                tokens.push(element.token);
            });

            swal({
                title: GLOBAL_LANG.whatsapp_broadcast_alert_broadcast_title,
                text: GLOBAL_LANG.whatsapp_broadcast_alert_broadcast_resume_all_text,
                type: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                reverseButtons: true,
                confirmButtonClass: "btn btn-success",
                confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_alert_broadcast_confirmButtonText,
                cancelButtonClass: "btn btn-danger",
                cancelButtonText: GLOBAL_LANG.whatsapp_broadcast_alert_broadcast_cancelButtonText,
            }).then(t => {
                if (t.value == true) {
                    $.ajax({
                        type: "POST",
                        url: document.location.origin + "/publication/whatsapp/broadcast/resume",
                        data: {
                            tokens: tokens
                        },
                        success: function (data) {

                            if (data.success?.status === true) {
                                t.value && swal({
                                    title: GLOBAL_LANG.whatsapp_broadcast_alert_broadcast_two_title,
                                    text: GLOBAL_LANG.whatsapp_broadcast_alert_broadcast_resume_two_all_text,
                                    type: "success",
                                    buttonsStyling: !1,
                                    confirmButtonClass: "btn btn-success"
                                });

                                $("#datatable-basic").DataTable().ajax.reload(null, false);
                            } else {
                                t.value && swal({
                                    title: GLOBAL_LANG.whatsapp_broadcast_error_broadcast_title,
                                    text: GLOBAL_LANG.whatsapp_broadcast_error_broadcast_message,
                                    type: "error",
                                    buttonsStyling: !1,
                                    confirmButtonClass: "btn btn-danger"
                                });
                            }

                            Campaign.clear();
                            showCheckboxAll();
                            $("#btn-cancel, #btn-pause, #btn-resume").hide();
                            $("[name='csrf_token_talkall']").val(Cookies.get("csrf_cookie_talkall"));
                        },
                        error: function (data) {
                            t.value && swal({
                                title: GLOBAL_LANG.whatsapp_broadcast_error_broadcast_title,
                                text: GLOBAL_LANG.whatsapp_broadcast_error_broadcast_message,
                                type: "error",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-danger"
                            });
                        }

                    })

                }
            });

        });

        $("#toggle-validity-view").on("click", function () {
            if ($("#toggle-validity-view").is(":checked")) {
                $("#date-validity").show(50);
                $("#date_start_validity").prop("disabled", false);
                $("#time_start_validity").prop("disabled", false);

            } else {
                $("#date-validity").hide(50);
                $("#date_start_validity").prop("disabled", true);
                $("#time_start_validity").prop("disabled", true);
            };
        });

        $('#sendEmailExport').on('click', () => modalExport());
        $("#modalFilter").one("click", () => modalFilter());

    }

    $('#campaign_estimate_suspend').on('click', () => campaignSuspend('modal-campaign-estimate'));
    $('#campaign_overlap_suspend').on('click', () => campaignSuspend('modal-campaign-overlap'));
    $('#campaign_estimate_review').on('click', () => console.log('Cliquei no review'));
    $('#campaign_estimate_change').on('click', () => $('#modal-campaign-estimate').modal('hide'));
    $('#campaign_overlap_change').on('click', () => $('#modal-campaign-overlap').modal('hide'));
    $('#campaign_estimate_send_partially').on('click', () => campaignEstimateSendPartial());
    $('#campaign_estimate_send_after').on('click', () => sendCampaignAfterWorkTime("modal-campaign-estimate"));
    $('#campaign_overlap_send_after').on('click', () => sendCampaignAfterWorkTime("modal-campaign-overlap"));

    document.querySelectorAll(".dropdown .dropdown-item")?.forEach(elm => elm.addEventListener("click", dropdownCick));
});

function countBroadcastButton() {
    let cancel_list = Campaign.cancel_list.length;
    let pause_list = Campaign.pause_list.length;
    let resume_list = Campaign.resume_list.length;
    let only_list = Campaign.only_list.length;

    if (cancel_list > 0) {
        $("#btn-cancel").show();
    } else {
        $("#btn-cancel").hide();
    }

    if (pause_list > 0) {
        $("#btn-pause").show();
    } else {
        $("#btn-pause").hide();
    }

    if (resume_list > 0) {
        $("#btn-resume").show();
    } else {
        $("#btn-resume").hide();
    }

    if (only_list > 0) {
        $("#btn-pause").hide();
        $("#btn-resume").hide();
    }

    $("#count_cancel").text(cancel_list);
    $("#count_pause").text(pause_list);
    $("#count_resume").text(resume_list);
}

function showCheckboxAll() {

    if (Campaign.cancel_list.length > 0) {
        document.getElementById("dropdownMenuInput").style.display = "inline-block";
        document.getElementById("dropdownMenuIcon").style.display = "inline-block";
    } else {
        document.getElementById("dropdownMenuInput").style.display = "none";
        document.getElementById("dropdownMenuIcon").style.display = "none";
        document.querySelector("#dropdownMenuInput").checked = false;
    }

    if (Campaign.cancel_list.length > 0) {
        document.getElementById("infoContact").style.display = "flex";
    } else {
        document.getElementById("infoContact").style.display = "none";
        document.querySelector("#dropdownMenuInput").checked = false;
    }

    const number = Campaign.cancel_list.length;
    document.getElementById("infoContact").firstElementChild.innerHTML = GLOBAL_LANG.whatsapp_broadcast_info_all.replace("{{number}}", number.toLocaleString("pt-BR"));
}

function dropdownCick(event) {
    event.preventDefault();
    const clickedItem = event.target.id;

    if (clickedItem === 'all') {
        countBroadcastsToDelete();
        document.querySelector("#dropdownMenuInput").checked = true;

    } else if (clickedItem === 'empty') {
        clearAllSelections();
        countBroadcastButton();
        document.querySelector("#dropdownMenuInput").checked = false;
    }
}

function clearAllSelections() {
    Campaign.clear();

    const selectedRows = document.querySelectorAll(".check-box");
    for (const row of selectedRows) {
        Table.row.deselected(row);
    }

    showCheckboxAll();
}

async function countBroadcastsToDelete() {

    const formData = new FormData();
    formData.append("text", $("#search").val());
    formData.append("channel", $('#verify-select2').val() == "2" ? "" : $('#multiselect2').val());
    formData.append("status", $('#select-status').val());
    formData.append("dt_start", $('#dt-start').val());
    formData.append("dt_end", $('#dt-end').val());
    formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

    const broadcast = await fetch(document.location.origin + "/publication/whatsapp/broadcast/countBroadcastsToDelete", {
        method: "POST",
        body: formData
    });

    const data = await broadcast.json();

    if (data.tokens && data.status && data.tokens.length === data.status.length) {
        Campaign.clear();

        for (let i = 0; i < data.tokens.length; i++) {
            const token = data.tokens[i];
            const status = data.status[i];

            let action = getActionByStatus(status);

            if (action) {
                Campaign.push(token, action);
            }
        }
    }

    document.getElementById("infoContact").firstElementChild.innerHTML = GLOBAL_LANG.whatsapp_broadcast_info_all.replace("{{number}}", data.countBroadcast.toLocaleString("pt-BR"));

    for (const row of [...document.querySelectorAll(".check-box")]) {
        for (const campaign of Campaign.cancel_list) {
            if (campaign.token == row.id) {
                Table.row.selected(row);
            }
        }
    }

    countBroadcastButton();
    console.log('Campaign lists after processing:', {
        cancel: Campaign.cancel_list,
        pause: Campaign.pause_list,
        resume: Campaign.resume_list,
        only: Campaign.only_list
    })
}

function getActionByStatus(status) {
    switch (status) {
        case '1':
        case '3':
        case '6':
            return 'cancel';
        // case 'X':
        //     return 'pause';
        // case 'Y':
        //     return 'resume';
        // case 'Z':
        //     return 'only';
        default:
            return null; // Status não mapeado
    }
}

function addMinutes(date, minutes) {
    date.setMinutes(date.getMinutes() + minutes);
    return date;
}


function modalFilter() {

    if (!$(".msf_multiselect_container").hasClass("msf_multiselect_container")) {
        const msf_multiselect_container = document.querySelectorAll(".msf_multiselect_container");

        for (elm of msf_multiselect_container) elm.remove();

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
    }

    const search = document.getElementById("search");

    const check_search = document.getElementById("check-search");
    const input_search = document.getElementById("input-search");

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
        document.querySelector("#dropdownMenuInput").checked = false;
        $("#btn-cancel, #btn-pause, #btn-resume").hide();
        Campaign.clear();
        clearAllSelections();
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
    console.log(order);

    switch (column) {
        case 2:
            column = "schedule";
            break;
        case 3:
            column = "channel";
            break;
        case 4:
            column = "title";
            break;
        default:
            break;
    }

    $.get(`/export/xlsx?
        search=${$('#search').val() == "" ? $('#input-search').val() : $('#search').val()}
    &channel=${$('#verify-select2').val() == "2" ? "" : $('#multiselect2').val()}
    &status=${$('#select-status').val()}
    &column=${column}
    &order=${order}
    &dt_start=${$('#dt-start').val()}
    &dt_end=${$('#dt-end').val()}
    &type=publicationWhatsappBroadcast`, function (response) {
        Swal.fire({
            title: GLOBAL_LANG.whatsapp_broadcast_alert_export_title,
            text: GLOBAL_LANG.whatsapp_broadcast_alert_export_text,
            type: 'success',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_alert_export_confirmButtonText
        });
    });
}


function changeTypeWarning(type) {

    swal({
        title: GLOBAL_LANG.whatsapp_broadcast_alert_change_type_title,
        text: GLOBAL_LANG.whatsapp_broadcast_alert_change_type_text,
        type: "warning",
        showCancelButton: !0,
        buttonsStyling: !1,
        reverseButtons: true,
        confirmButtonClass: "btn btn-success",
        confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_alert_change_type_yes,
        cancelButtonClass: "btn btn-danger",
        cancelButtonText: GLOBAL_LANG.whatsapp_broadcast_alert_change_type_no,
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


function clearTextInput() {

    $("#input-data").val("");
    $("#alert_textarea_message").hide();

}

function clearMediaInput() {

    $(".broadcast").remove();
    $("#alert_upload_media").hide();
    $(".drop-inner-img").show();
    $(".drop-inner-title").show();
    $(".drop-inner-text").show();
    $("#fileElem").val("");

}


function showMediaInput() {

    clearTextInput()

    const label_chose_type = document.getElementById("label_chose_type");
    const data_status = document.getElementById("status_data");
    const input_files = document.getElementById("input_files");
    const dropBrodcast = document.getElementById("dropBrodcast");
    const type_broadcast = document.getElementById("type_broadcast");

    const change_type_icon_img = document.getElementById("change_type_icon_img");
    const change_type_icon_text = document.getElementById("change_type_icon_text");

    status_text.style.display = "none";
    status_image.style.display = "none";

    label_chose_type.style.display = "none";
    data_status.style.display = "none";
    input_files.style.display = "block";
    dropBrodcast.style.display = "block";
    type_broadcast.value = "image";

    change_type_icon_img.style.display = "none";
    change_type_icon_text.style.display = "block";

};

function showTextInput() {

    clearMediaInput()

    const data_status = document.getElementById("status_data");
    const type_broadcast = document.getElementById("type_broadcast");

    const change_type_icon_img = document.getElementById("change_type_icon_img");
    const change_type_icon_text = document.getElementById("change_type_icon_text");

    status_text.style.display = "none";
    status_image.style.display = "none";

    label_chose_type.style.display = "none";
    input_files.style.display = "none";
    dropBrodcast.style.display = "none";
    data_status.style.display = "block";
    type_broadcast.value = "text";

    change_type_icon_text.style.display = "none";
    change_type_icon_img.style.display = "block";

}


$("#preview-campaign").on('click', function () {

    if (validateField()) {

        $("#alert_upload_media").hide();
        $("#modal-preview-campaign").modal('show');
        document.getElementById("btn-send-preview").disabled = false;

        previewSelectedChannelShow();
        $("#alert_number_fone").hide();
        $("#alert_select_channel").hide();
        $("#inputFone").mask("(99)99999-9999");

        return true;
    }
});


function getCurrentTime() {
    const now = new Date();
    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    return `${hours}:${minutes}`;
}


function checkDate(date_start) {

    let date = date_start;
    date = date.split("/")[2] + "-" + date.split("/")[1] + "-" + date.split("/")[0];

    let date_current = new Date();

    let year = date_current.getFullYear();
    let month = date_current.getMonth() + 1;
    let day = date_current.getDate();

    if (month < 10) month = "0" + month;
    if (day < 10) day = "0" + day;

    date_current = year + "-" + month + "-" + day;

    const date__start = new Date(date);
    const date__current = new Date(date_current);

    if (date === date_current) {
        const current_time = getCurrentTime();
        const time_start = document.getElementById('time_start');

        if (time_start.value < current_time) {
            time_start.value = current_time;
        }
    }

    if (date__start >= date__current) {

        return true;
    } else {
        return false;
    }
}


function checkTimeToEdit(schedule) {

    const [date_part, time_part] = schedule.split(' - ');
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
            message = GLOBAL_LANG.whatsapp_broadcast_datatables_edit_status_2
            break;
        case '5':
            message = GLOBAL_LANG.whatsapp_broadcast_datatables_edit_status_5
            break;
        case '1':
        case '6':
            message = GLOBAL_LANG.whatsapp_broadcast_datatables_edit_status_6
            break;
        default:
            message = GLOBAL_LANG.whatsapp_broadcast_datatables_edit_default
            break;
    }

    return message;
}


async function validateField() {
    return new Promise((resolve) => {
        const status_image = $("#status_image").is(":visible");
        const status_text = $("#status_text").is(":visible");
        const edit = $("#edit").val()?.trim() ?? "";

        let form_validation = true;

        const isEmpty = (val) => !val || val.trim().length === 0;

        // Título
        if (isEmpty($("#input_title").val())) {
            $("#alert_input_title").show();
            form_validation = false;
        } else {
            $("#alert_input_title").hide();
        }

        // Validação de data de início (exceto para edit = 'expire')
        if (edit !== "expire") {
            if (!checkDate($("#date_start").val()?.trim())) {
                $("#alert_date_start").show();
                $("#alert_time_start_validity").hide();
                form_validation = false;
            } else {
                $("#alert_date_start").hide();
            }
        }

        // Hora de início
        if (isEmpty($("#time_start").val())) {
            $("#alert_time_start").show();
            form_validation = false;
        } else {
            $("#alert_time_start").hide();
        }

        // Segmentações
        if (!$("#multiselect").val() || $("#multiselect").val().length === 0) {
            $("#alert_multi_selects").show();
            form_validation = false;
        } else {
            $("#alert_multi_selects").hide();
        }

        // Validade programada
        if ($("#toggle_validity").is(":checked")) {
            let date_start = $("#date_start").val()?.trim();
            let date_start_validity = $("#date_start_validity").val()?.trim();
            let time_start_validity = $("#time_start_validity").val()?.trim();

            if (!isEmpty(date_start_validity)) {
                const formatDate = (d) => d.split("/").reverse().join("-");
                const dStart = formatDate(date_start);
                const dValid = formatDate(date_start_validity);

                if (dValid < dStart) {
                    $("#alert_date_start_validity").show();
                    $("#alert_time_start_validity").hide();
                    form_validation = false;
                } else {
                    $("#alert_date_start_validity").hide();
                    if (dValid === dStart && time_start_validity <= $("#time_start").val()?.trim()) {
                        $("#alert_time_start_validity").show();
                        form_validation = false;
                    } else {
                        $("#alert_time_start_validity").hide();
                    }
                }
            } else {
                $("#alert_date_start_validity").show();
                form_validation = false;
            }

            if (isEmpty(time_start_validity)) {
                $("#alert_time_start_validity").show();
                form_validation = false;
            }
        } else {
            $("#alert_time_start_validity").hide();
            $("#alert_date_start_validity").hide();
        }

        // Verificação de status imagem/texto/upload
        const inputData = $("#input-data").val();
        const inputDataVisible = $("#status_data").is(":visible");

        if (status_image && status_text) {
            $("#alert_input_img_text").show();
            form_validation = false;
        } else if (
            !$(".drop-broadcast .broadcast").hasClass("broadcast") &&
            !status_image &&
            !status_text &&
            !inputDataVisible
        ) {
            $("#alert_input_img_text").hide();
            $("#alert_upload_media").show();
            form_validation = false;
        } else if (inputData !== undefined) {
            $("#alert_upload_media").hide();
            if (isEmpty(inputData) && inputDataVisible) {
                $("#alert_textarea_message").show();
                form_validation = false;
            } else {
                $("#alert_textarea_message").hide();
            }
        } else {
            $("#alert_input_img_text, #alert_upload_media, #alert_textarea_message").hide();
        }

        resolve(form_validation);
    });
}

function previewSelectedChannelShow() {

    let selectedChannels = $("#multiselect").val();
    let selectedPreviewChannels = [...$('#select-channel-preview option')];

    selectedPreviewChannels.forEach(element => {
        $(element).hide()
    });

    selectedChannels.forEach(element => {
        selectedPreviewChannels.filter((elm) => {
            $('#select-channel-preview').val(0);
            $('#inputFone').val('');

            if (elm.value == 0) {
                $(elm).css({ "display": "block" });
            }
            if (elm.value == element) {
                $(elm).css({ "display": "block" });
            }
        });
    });
}

$('#btn-send-preview').on('click', function () {

    let foneNumber = $('#inputFone').val();

    if (foneNumber.length != 0 && $('#select-channel-preview').val() != 0) {
        document.getElementById("btn-send-preview").disabled = true;
        checkPreview();
    } else {
        $('#alert_number_fone').show();

    }
    if (foneNumber.length != 0 && $('#select-channel-preview').val() == 0) {
        $('#alert_select_channel').show();
    }
});


async function checkPreview() {

    const validate = await validateOptinChannel();

    if (validate == false) return;

    let media = listMediaUrlFile();
    let textData = getTextInputData();
    let mediaName = getNameMedia();
    let dataCampaign = { validate, media, textData, mediaName };

    $.ajax({
        type: "POST",
        url: document.location.origin + "/publication/whatsapp/broadcast/save/preview",
        data: {
            key_remote_id: dataCampaign.validate.key_remote_id,
            id_channel: dataCampaign.validate.channel,
            urlFile: dataCampaign.media.mediaUrlFile,
            textContent: dataCampaign.textData.textMedia,
            textInputData: dataCampaign.textData.textInputMedia,
            cmd: dataCampaign.media.mediaExtention,
            mediaName: dataCampaign.mediaName.mediaNameFile
        },
        success: function (data) {
            $('#modal-preview-campaign-success').modal('show');
            $('#modal-preview-campaign').modal('hide');
            $("[name='csrf_token_talkall']").val(Cookies.get("csrf_cookie_talkall"));
        }
    })
}


async function validateOptinChannel() {

    let result = await fetchValidateContact();

    $("[name='csrf_token_talkall']").val(Cookies.get("csrf_cookie_talkall"));

    if (result != false) {
        return result;
    } else {
        notifyPhoneNumberWithoutOptin();
        return false;
    }
}


async function fetchValidateContact() {

    let inputFone = document.getElementById('inputFone').value.replace(/\D/g, '');
    let destinationPhone = '55' + inputFone;
    let idChannel = document.getElementById('select-channel-preview').value;

    const formData = new FormData();
    formData.append("key_remote_id", destinationPhone);
    formData.append("id_channel", idChannel);
    formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

    const response = await fetch(document.location.origin + "/publication/whatsapp/broadcast/validateContact", {
        method: 'POST',
        body: formData
    });

    const result = await response.json();

    return result;
}


function notifyPhoneNumberWithoutOptin() {

    let getElementIdChannel = document.getElementById('select-channel-preview');
    let idChannel = getElementIdChannel.options[getElementIdChannel.selectedIndex].text
    let ddd = document.getElementById('inputFone').value.substring(0, 4);
    let numberphone = document.getElementById('inputFone').value.substring(4);

    swal({
        title: GLOBAL_LANG.whatsapp_broadcast_campaign_preview_atention,
        text: GLOBAL_LANG.whatsapp_broadcast_campaign_preview_the_number + ddd + ' ' + numberphone + GLOBAL_LANG.whatsapp_broadcast_campaign_preview_no_optin + idChannel,
        type: "warning",
        confirmButtonColor: '#3085d6',
        confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_campaign_preview_ok,
        cancelButtonClass: "btn btn-secondary"
    });
}


function listMediaUrlFile() {

    let mediaUrl = [...$("input[name*='file']")];
    let mediaUrlFile = [];
    let mediaExtention = []

    mediaUrl.forEach(element => {
        let media_url = element.value.split('.').pop();
        mediaExtention.push(getUrlFileExtension(media_url));
        mediaUrlFile.push(element.value);
    });

    let infoMedia = {
        mediaExtention,
        mediaUrlFile,
    }

    return infoMedia;
}


function getUrlFileExtension(media) {

    let mediaType = {
        mp4: 'VideoMessage',
        jpg: 'ImageMessage',
        jpeg: 'ImageMessage',
        jfif: 'ImageMessage',
        pdf: 'DocumentMessage',
        ogg: 'AudioMessage',
    }
    return mediaType[media];
}


function getNameMedia() {

    let mediaName = [...$("input[name*='media_name']")];
    let mediaNameFile = [];

    mediaName.forEach(element => {
        let media_url = element.value.split('.').shift();
        mediaNameFile.push(media_url);
    });

    let infoMediaName = {
        mediaNameFile,
    }

    return infoMediaName;
}


function getTextInputData() {

    let textContent = [...$("textarea[name*='text']")];
    let textInputData = [...$("textarea[name*='input-data']")];
    let textMedia = [], textInputMedia = [];

    textContent.forEach(element => {
        textMedia.push(element.value);
    });

    textInputData.forEach(element => {
        textInputMedia.push(element.value);
    });

    let contentText = {
        textMedia,
        textInputMedia,
    }

    return contentText;
}

function showModalOverlap(data) {

    document.querySelector(".btn-success").disabled = false;
    const number_list = document.querySelector("#campaign_overlap_channel_info .list-numbers");
    number_list.innerHTML = '';

    const info = data.slice(0, 3);
    const channels = info.map(channel => `${channel.channel_name} (${channel.conflict_info[0].channel_id})`).join(', ');
    const text = data.length > 3 ? channels + GLOBAL_LANG.campaign_overlap_channel_info_others : channels;

    number_list.textContent = text;

    if (data.length == 1) {
        document.querySelector("#campaign_overlap_channel_info .text").innerHTML = GLOBAL_LANG.campaign_overlap_channel_info;
    } else document.querySelector("#campaign_overlap_channel_info .text").innerHTML = GLOBAL_LANG.campaign_overlap_channels_info;

    $('#modal-campaign-overlap').modal();
}

function submit() {
    $("[name='csrf_token_talkall']").val(Cookies.get("csrf_cookie_talkall"));
    $("form").unbind('submit').submit();
}

async function processChunks(chunkedArray, persona) {
    return new Promise(async (resolve, reject) => {
        try {
            let data = [];

            for (const element of chunkedArray) {
                const formData = new FormData();

                formData.append("channels", element);
                formData.append("date_start", $("#date_start").val());
                formData.append("time_start", $("#time_start").val());
                formData.append("persona", persona);
                formData.append("count_doc", document.querySelectorAll("#dropBrodcast .col-6.broadcast").length);
                formData.append("is_wa_status", 2);
                formData.append("is_wa_broadcast", 1);
                formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

                const response = await fetch(document.location.origin + "/publication/whatsapp/broadcast/ruleSchedule", {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                data.push(result);
            }

            resolve(data);
        } catch (error) {
            reject(error);
        }
    });
}

async function ruleSchedule() {
    const edit = document.getElementById("edit")?.value ?? "";
    const persona = document.getElementById("select_segmented_group").value > 0 ? document.getElementById("select_segmented_group").value : "";

    const originalArray = $("#multiselect").val();
    const chunkSize = 20;
    const chunkedArray = [];

    for (let i = 0; i < originalArray.length; i += chunkSize) {
        chunkedArray.push(originalArray.slice(i, i + chunkSize));
    }

    const data = await processChunks(chunkedArray, persona);

    const newArrayConflicts = data.flatMap(item =>
        item.data.map(d => ({ data: d, conflicts: item.conflicts }))
    );

    const newArray = data.flatMap(item => item.data);

    if (edit !== "expire" && newArrayConflicts.length > 0) {
        const conflicts = newArrayConflicts[0].conflicts;
        if (Array.isArray(conflicts) && conflicts.length > 0) {
            showModalOverlap(conflicts);
            return;
        }
    }

    let rules = [];
    let channelNames = [];

    for (let i = 0; i < newArray.length; i++) {
        const element = newArray[i];
        if (element.pass.ruleOne == false) {
            rules.push(element.pass);
            let channelName = `${element.pass.name} (${element.pass.channel})`;
            channelNames.push(` ${channelName}`);
        }
    }

    if (rules.length != 0) {
        let list_number = document.getElementById('list-numbers');
        if (channelNames.length > 3) {
            list_number.textContent = channelNames[0] + ' ,' + channelNames[1] + ' ,' + channelNames[2] + ' ,' + GLOBAL_LANG.campaign_estimate_channel_info1;
        } else {
            channelNames.toString();
            list_number.textContent = channelNames + '.';
        }

        $('#modal-campaign-estimate').modal();
        document.querySelector(".btn-success").disabled = false;

    } else {
        if (edit !== "expire")
            checkDate($("#date_start").val());
        $("[name='csrf_token_talkall']").val(Cookies.get("csrf_cookie_talkall"));
        $("form").unbind('submit').submit();
    }

    return;
}


function campaignSuspend(modalId) {

    $(`#${modalId}`).modal('hide');
    swal.fire({
        title: GLOBAL_LANG.campaign_estimate_suspend_notify_title,
        text: GLOBAL_LANG.campaign_estimate_suspend_notify_text,
        type: "warning",
        showCancelButton: !0,
        buttonsStyling: !1,
        reverseButtons: true,
        cancelButtonClass: "btn btn-danger",
        cancelButtonText: GLOBAL_LANG.campaign_estimate_suspend_notify_cancel,
        confirmButtonClass: "btn btn-success",
        confirmButtonText: GLOBAL_LANG.campaign_estimate_suspend_notify_ok,
    }).then(result => {
        if (result.value == true) {
            window.location.href = document.location.origin + '/publication/whatsapp/broadcast';
        } else {
            $(`#${modalId}`).modal('show');
        }
    });
}


function campaignEstimateSendPartial() {

    $('#modal-campaign-estimate').modal('hide');

    swal.fire({
        title: GLOBAL_LANG.campaign_estimate_partial_notify_title,
        text: GLOBAL_LANG.campaign_estimate_partial_notify_text,
        type: "warning",
        showCancelButton: !0,
        buttonsStyling: !1,
        reverseButtons: true,
        cancelButtonClass: "btn btn-danger",
        cancelButtonText: GLOBAL_LANG.campaign_estimate_partial_notify_cancel,
        confirmButtonClass: "btn btn-success",
        confirmButtonText: GLOBAL_LANG.campaign_estimate_partial_notify_ok,
    }).then(result => {
        if (result.value == true) {
            Swal.fire(
                GLOBAL_LANG.campaign_estimate_partial_notify_add_title,
                GLOBAL_LANG.campaign_estimate_partial_notify_sucess,
                'success'
            ).then(() => {
                let partial = document.createElement('input');
                partial.setAttribute('type', 'hidden');
                partial.setAttribute('name', 'isPartial');
                partial.setAttribute('id', 'isPartial');
                partial.setAttribute('value', 1);
                document.querySelector('form').appendChild(partial);

                checkDate($("#date_start").val());
                submit();
            });
        } else {
            $('#modal-campaign-estimate').modal();
        }
    });
}

function sendCampaignAfterWorkTime(modalId) {

    $(`#${modalId}`).modal('hide');

    swal.fire({
        title: GLOBAL_LANG.campaign_estimate_partial_notify_title,
        text: GLOBAL_LANG.campaign_estimate_after_notify_text,
        type: "warning",
        showCancelButton: !0,
        buttonsStyling: !1,
        reverseButtons: true,
        cancelButtonClass: "btn btn-danger",
        cancelButtonText: GLOBAL_LANG.campaign_estimate_partial_notify_cancel,
        confirmButtonClass: "btn btn-success",
        confirmButtonText: GLOBAL_LANG.campaign_estimate_partial_notify_ok,
    }).then(result => {
        if (result.value == true) {
            Swal.fire(
                GLOBAL_LANG.campaign_estimate_partial_notify_add_title,
                GLOBAL_LANG.campaign_estimate_partial_notify_sucess,
                'success'
            ).then(() => {
                let partial = document.createElement('input');
                partial.setAttribute('type', 'hidden');
                partial.setAttribute('name', 'isAfter');
                partial.setAttribute('id', 'isAfter');
                partial.setAttribute('value', 1);
                document.querySelector('form').appendChild(partial);

                checkDate($("#date_start").val());
                submit();
            });
        } else {
            $(`#${modalId}`).modal();
        }
    });
}


function configRow(e, elm) {

    if ($(elm).is(":checked")) {
        $(elm).addClass("selected")
        $(elm).parent().parent().parent().css("background", "#cfd2dbba")
    } else {
        $(elm).removeClass("selected")
        $(elm).parent().parent().parent().css("background", "#fff")
    }

    let count_cancel = $(".selected.check-box-cancel").length;
    $("#count_cancel").text(count_cancel);

    let count_cancel_only = $(".selected.check-box-cancel-only").length;

    let count_pause = $(".selected.check-box-pause").length;
    $("#count_pause").text(count_pause);

    let count_resume = $(".selected.check-box-resume").length;
    $("#count_resume").text(count_resume);

    if (count_cancel > 0) {
        $("#btn-cancel").show()
    } else {
        $("#btn-cancel").hide()
    }

    if (count_pause > 0 && count_resume == 0 && count_cancel_only == 0) {
        $("#btn-pause").show()
    } else {
        $("#btn-pause").hide()
    }

    if (count_resume > 0 && count_pause == 0 && count_cancel_only == 0) {
        $("#btn-resume").show()
    } else {
        $("#btn-resume").hide()
    }
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