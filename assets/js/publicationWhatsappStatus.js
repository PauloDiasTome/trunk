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


// function anticipate() {

//     let minutes = 30;
//     let date_current = new Date();
//     let timestamp = date_current.setMinutes(date_current.getMinutes() + minutes);

//     let tamp = timestamp;
//     let date = new Date(tamp);

//     let hour = date.getHours();
//     let min = date.getMinutes();

//     if (min < 10) min = "0" + min;
//     if (hour < 10) hour = "0" + hour;

//     let date_anticipate = hour + ":" + min;

//     const time_start = document.getElementById("time_start");
//     time_start.value = date_anticipate;
// }


function callAlert(verify) {

    switch (verify) {
        case "limit":
            swal({
                title: GLOBAL_LANG.whatsapp_status_alert_dropzone_title,
                text: GLOBAL_LANG.whatsapp_status_alert_dropzone_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.whatsapp_status_alert_dropzone_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "maxSize":
            swal({
                title: GLOBAL_LANG.whatsapp_status_alert_dropzone_two_title,
                text: GLOBAL_LANG.whatsapp_status_alert_dropzone_two_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.whatsapp_status_alert_dropzone_two_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "png":
            swal({
                title: GLOBAL_LANG.whatsapp_status_alert_dropzone_three_title,
                text: GLOBAL_LANG.whatsapp_status_alert_dropzone_three_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.whatsapp_status_alert_dropzone_three_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "pdf":
            swal({
                title: GLOBAL_LANG.whatsapp_status_alert_dropzone_four_title,
                text: GLOBAL_LANG.whatsapp_status_alert_dropzone_four_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.whatsapp_status_alert_dropzone_four_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "extensions":
            swal({
                title: GLOBAL_LANG.whatsapp_status_alert_files_title,
                text: GLOBAL_LANG.whatsapp_status_alert_files_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.whatsapp_status_alert_files_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        default:
            break;
    }
}


function addName() {

    let f = 0, a = 0, t = 0, s = 0;

    const files = document.querySelectorAll(".file-hidden");
    const texAreas = document.querySelectorAll(".tex-area");
    const media_size = document.querySelectorAll(".size-hidden");

    for (let file of files) {
        file.name = "file" + f;
        f++;
    }

    for (let text of texAreas) {
        text.name = "text" + a;
        a++;
    }

    for (let size of media_size) {
        size.name = "media_size" + s;
        s++;
    }
}


function fillThumbnail(data) {

    console.log("fillThumbnail: ", data);

    let id = data.ta_id;
    $("#load_" + id).remove();

    $("#thumb_" + id).find(".file-hidden").attr("value", data.url);
    $("#thumb_" + id).find(".thumbnail-hidden").attr("value", data.thumbnail);
    $("#thumb_" + id).find(".size_hidden").attr("value", data.size);
    $("#thumb_" + id).css({ "background-image": "url(data:image/jpeg;base64," + data.thumbnail + ")" });

    $("#close_" + id).addClass("close-status");
    $("#close_" + id).attr("src", document.location.origin + "/assets/img/statusClose.png");

    $("#i_st_" + id).on("click", function () {
        window.open($("#" + this.id.replace("i_st_", "") + " input").val());
    });

    setTimeout(function () { addName(); }, 500);
}


function createStatus(data) {

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

    let bfather = document.createElement("div");
    bfather.className = "col-4 bfather";
    bfather.id = Math.floor(Math.random() * 100000);

    let boxStatus = document.createElement("div");
    boxStatus.className = "box-status";
    boxStatus.draggable = "true";
    boxStatus.id = data.retrieve === false ? "thumb_" + data.ta_id : "thumb_" + Math.floor(Math.random() * 100000);
    boxStatus.style.backgroundImage = data.retrieve === false ? "" : "url(data:video/mp4;base64," + data.thumbnail + ")";

    let video = document.createElement("video");
    if (data.media_type == 5) {
        video.className = "video";
        video.id = data.retrieve === false ? "video_" + data.ta_id : "video_" + Math.floor(Math.random() * 100000);
        video.src = data.retrieve === false ? "" : data.url;

    }

    let url_hidden = document.createElement("input");
    url_hidden.type = "hidden";
    url_hidden.className = "file-hidden";
    url_hidden.name = "file0";
    url_hidden.value = data.retrieve === false ? "" : data.url;

    let size_hidden = document.createElement("input");
    size_hidden.type = "hidden";
    size_hidden.className = "size-hidden";
    size_hidden.name = "media_size0";
    size_hidden.value = data.retrieve === false ? data.media_size : data.media_size;

    let thumbnail_hidden = document.createElement("input");
    thumbnail_hidden.type = "hidden";
    thumbnail_hidden.className = "thumbnail-hidden";
    thumbnail_hidden.value = data.retrieve === false ? "" : "data:image/jpeg;base64," + data.thumbnail;

    let load = document.createElement("img");
    load.src = data.retrieve === false ? document.location.origin + "/assets/img/loads/loading_2.gif" : "";
    load.id = data.retrieve === false ? "load_" + data.ta_id : "";
    load.style.width = "7em";

    let imgStatus = document.createElement("div");
    imgStatus.className = "img-status";

    let img = document.createElement("img");
    img.className = data.retrieve === false ? "" : "i_st";

    let close = document.createElement("img");
    close.className = data.retrieve === false ? "" : "close-status";
    close.src = data.retrieve === false ? "" : document.location.origin + "/assets/img/statusClose.png";
    close.id = data.retrieve === false ? "close_" + data.ta_id : "close_" + Math.floor(Math.random() * 100000);

    let textAstatus = document.createElement("div");
    textAstatus.className = "textarea-status";

    let textArea = document.createElement("textarea");
    textArea.placeholder = GLOBAL_LANG.whatsapp_status_add_edit_status_placeholder;
    textArea.className = "tex-area";
    textArea.name = "tex_ar";
    textArea.id = "tex_ar";
    textArea.dataset.index = Math.floor(Math.random() * 10000);
    textArea.maxLength = "700";
    textArea.value = data.retrieve === false ? "" : data.text;
    textArea.addEventListener("mousedown", (e) => getTooltipPositionAI(e));
    textArea.addEventListener("input", (e) => countCaracter(e));
    textArea.onmouseup = textArea.onkeyup = () => createTooltipAI();

    imgStatus.appendChild(close);

    if (data.media_type == 5 && data.retrieve === true) {

        imgStatus.appendChild(video);

    } else if (data.media_type == 3) {

        imgStatus.appendChild(img);

    }

    let caracter = document.createElement('div');
    caracter.setAttribute('class', 'count-caracter');

    let text_caracter = document.createElement('span');
    text_caracter.textContent = GLOBAL_LANG.whatsapp_status_text_caracter;

    let quantity_caracter = document.createElement('span');
    quantity_caracter.setAttribute('class', 'quantity-caracter');
    quantity_caracter.textContent = 700;

    caracter.appendChild(text_caracter);
    caracter.appendChild(quantity_caracter);

    boxStatus.appendChild(load);
    boxStatus.appendChild(url_hidden);
    boxStatus.appendChild(thumbnail_hidden);
    boxStatus.appendChild(imgStatus);
    boxStatus.appendChild(size_hidden);

    textAstatus.appendChild(textArea);
    boxStatus.appendChild(textAstatus);
    boxStatus.appendChild(caracter);
    bfather.appendChild(boxStatus);

    const dropStatus = document.getElementById("dropStatus");
    dropStatus.appendChild(bfather);

    bfather.animate([
        { opacity: '0' },
        { opacity: '1' }
    ], {
        duration: 1000,
    });

    setTimeout(function () { addName(); }, 500);
}


function editStatus(data) {

    let bfather = document.createElement("div");
    bfather.className = "col-4 bfather";
    bfather.id = Math.floor(Math.random() * 100000);

    let boxStatus = document.createElement("div");
    boxStatus.className = "box-status";
    boxStatus.id = "thumb_" + Math.floor(Math.random() * 100000);
    boxStatus.style.backgroundImage = `url(${data.url})`;
    boxStatus.style.backgroundSize = "cover";
    boxStatus.style.backgroundPosition = "center";
    boxStatus.style.backgroundRepeat = "no-repeat";

    let url_hidden = document.createElement("input");
    url_hidden.type = "hidden";
    url_hidden.className = "file-hidden";
    url_hidden.name = "file0";
    url_hidden.value = data.url;

    let imgStatus = document.createElement("div");
    imgStatus.className = "img-status";

    let img = document.createElement("img");
    let video = document.createElement("video");

    if (data.media_type == 3) {
        img.className = "i_st";
        img.id = "i_st_" + Math.floor(Math.random() * 100000);
        img.src = data.url;
    } else if (data.media_type == 5) {
        video.controls = true;
        video.className = "video";
        video.id = "video_" + Math.floor(Math.random() * 100000);
        video.src = data.url;
    }

    let textAstatus = document.createElement("div");
    textAstatus.className = "textarea-status";

    let textArea = document.createElement("textarea");
    textArea.placeholder = GLOBAL_LANG.whatsapp_status_add_edit_status_placeholder;
    textArea.className = "tex-area";
    textArea.name = "description_data";
    textArea.id = "description_data";
    textArea.dataset.index = Math.floor(Math.random() * 10000);
    textArea.maxLength = "700";
    textArea.value = data.text;
    textArea.addEventListener("mousedown", (e) => getTooltipPositionAI(e));
    textArea.addEventListener("input", (e) => countCaracter(e, data.media_type));
    textArea.onmouseup = textArea.onkeyup = () => createTooltipAI();

    if (data.media_type == 5) {
        imgStatus.appendChild(video);
    }

    let caracter = document.createElement('div');
    caracter.setAttribute('class', 'count-caracter');

    let text_caracter = document.createElement('span');
    text_caracter.textContent = GLOBAL_LANG.whatsapp_status_text_caracter;

    let quantity_caracter = document.createElement('span');
    quantity_caracter.setAttribute('class', 'quantity-caracter');
    quantity_caracter.textContent = 700;

    caracter.appendChild(text_caracter);
    caracter.appendChild(quantity_caracter);

    boxStatus.appendChild(url_hidden);
    boxStatus.appendChild(imgStatus);
    textAstatus.appendChild(textArea);
    boxStatus.appendChild(textAstatus);
    boxStatus.appendChild(caracter);
    bfather.appendChild(boxStatus);

    const dropStatus = document.getElementById("dropStatus");
    dropStatus.appendChild(bfather);

    bfather.animate([
        { opacity: '0' },
        { opacity: '1' }
    ], {
        duration: 500,
    });
}


function handleFiles(files) {

    let formData = new FormData();
    let total_files = parseInt($(".bfather").length);
    let myFiles = [];
    let media_size = '';
    let alert = false;
    let maxFileSize = 10 * 1024 * 1024;

    for (let i = 0; i < files.length; i++) {

        if (myFiles.length < 11) {
            if (files[i].name.includes("jfif")) {
                callAlert('extensions');
                return;
            }

            myFiles.push(files[i]);
        }

    }

    for (let i = 0; i < myFiles.length; i++)(function (t) {
        setTimeout(function () {

            if (i >= 10 || (total_files + 1) + i > 10) {
                if (!alert) callAlert("limit"); alert = true;

            } else if (myFiles[i].size > maxFileSize) {
                if (!alert) callAlert("maxSize"); alert = true;


            } else if (myFiles[i].type == "image/png") {
                if (!alert) callAlert("png"); alert = true;

            } else if (myFiles[i].type == "application/pdf") {
                if (!alert) callAlert("pdf"); alert = true;

            } else {

                switch (files[i].type) {

                    case "image/jpeg":
                    case "image/jpg":
                        media_type = 3;
                        media_size = files[i].size;
                        break;

                    case "video/mp4":
                        media_type = 5;
                        media_size = files[i].size;
                        break;
                }

                let id = Math.floor(Math.random() * 100000);

                let data = {
                    "ta_id": id,
                    "retrieve": false,
                    "media_type": media_type,
                    "media_size": media_size
                }

                createStatus(data);

                formData.append("filetoupload", myFiles[i]);
                formData.append("ta_id", id);

                var settings = {
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


        }, t * 550);

    }(i));

}


function dragOverHandler(ev) {
    ev.preventDefault();
}


function dropHandler(ev) {
    ev.preventDefault();

    if (ev.dataTransfer.items) {

        var files = [];

        for (var i = 0; i < ev.dataTransfer.items.length; i++) {

            ev.dataTransfer.items[i].kind

            if (ev.dataTransfer.items[i].type == 'image/jpeg' ||
                ev.dataTransfer.items[i].type == 'image/jpg' ||
                ev.dataTransfer.items[i].type == 'image/png' ||
                ev.dataTransfer.items[i].type == 'video/mp4' ||
                ev.dataTransfer.items[i].type == 'application/pdf') {

                var file = ev.dataTransfer.items[i].getAsFile();
                files.push(file)
            }
        }

    } else {
        for (var i = 0; i < ev.dataTransfer.files.length; i++) {
            console.log('... file[' + i + '].name = ' + ev.dataTransfer.files[i].name);
            console.log('... file[' + i + '].name = ' + ev.dataTransfer.files[i]);
        }
    }


    handleFiles(files);

    $(".box-status").css({ "width": "auto", "margin-left": "0px" });
    $(".bfather").css({ "border": "0px #1212ffdb dashed" });
    $(".bfather").css({ "background": "#fff", "opacity": "", "z-index": "" });
}


function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.status.search) {
            document.getElementById("search").value = Filters.status.search;
        }

        if (Filters.status.input_search) {
            document.getElementById("input-search").value = Filters.status.input_search;
        }

        if (Filters.status.channel.length !== 0) {
            modalFilter();
            document.getElementById("check-select2").click();

            document.querySelectorAll("#mult-select2 .cust_").forEach((element, index) => {

                if (Filters.status.channel.includes(element.value))
                    document.querySelectorAll("#mult-select2 .cust_")[index].click();
            })
        }

        if (Filters.status.status) {
            modalFilter();
            document.getElementById("check-status").click();
            document.getElementById("select-status").value = Filters.status.status;
        }

        if (Filters.status.dt_start) {
            modalFilter();

            document.getElementById("check-date").click();

            document.getElementById("dt-end").disabled = false;
            document.getElementById("dt-end").type = "date";
            document.getElementById("dt-start").type = "date";

            document.getElementById("dt-start").value = Filters.status.dt_start;
            document.getElementById("dt-end").value = Filters.status.dt_end;
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
            url: "status/find",
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
                targets: 5,
                render: function (data, type, full, meta) {
                    let ret = `<span class="badge badge-sm badge-info">${GLOBAL_LANG.whatsapp_status_datatable_column_status_sending}</span>`
                    switch (full.status) {
                        case '1':
                        case '6':
                            ret = `<span class="badge badge-sm badge-info">${GLOBAL_LANG.whatsapp_status_datatable_column_status_sending}</span>`
                            break;
                        case '2':
                            ret = `<span class="badge badge-sm badge-success">${GLOBAL_LANG.whatsapp_status_datatable_column_status_send}</span>`
                            break;
                        case '3':
                            ret = `<span class="badge badge-sm badge-secondary">${GLOBAL_LANG.whatsapp_status_datatable_column_status_processing}</span>`
                            break;
                        case '5':
                            ret = `<span class="badge badge-sm badge-danger">${GLOBAL_LANG.whatsapp_status_datatable_column_status_called_off}</span>`
                            break;
                        case '7':
                            ret = `<span class="badge badge-sm badge-secondary">${GLOBAL_LANG.whatsapp_status_datatable_column_status_processing}</span>`
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
                                                <span>${GLOBAL_LANG.whatsapp_status_datatable_column_action_view}</span>
                                            </a>
                                            <a id="` + full.token + `" class="dropdown-item ${full.status == 4 ? 'table-action-deleted disabled' : (full.status == 5 ? 'table-action-deleted disabled' : (full.status == 2 ? 'table-action-deleted disabled' : 'table-action-delete'))}" style="cursor: pointer">
                                                <div style="width: 24px; display: inline-block"> 
                                                    <i class="fas fa-times" style="font-size: 12pt; margin-left: 3px"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.whatsapp_status_datatable_column_action_cancel}</span>
                                            </a>
                                            <a id="${full.token}" class="dropdown-item table-action-resend"  
                                                style="cursor: pointer; display:${(full.status != 3) ? containsSupport(USER_EMAIL) == true ? 'block' : 'none' : 'none'}">
                                                <div style="width: 24px; display: inline-block"> 
                                                    <i class="fas fa-redo-alt" style="font-size: 12pt; margin-left: 3px"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.whatsapp_status_alert_resend}</span>
                                            </a>
                                            <a id="${full.token}" class="dropdown-item ${(full.status == 1 || full.status == 2 || full.status == 5 || full.status == 6 || checkTimeToEdit(full.schedule)) ? 'table-action-disabled disabled' : 'table-action-edit'}"  
                                                style="cursor: pointer; display:none" title="${(full.status == 1 || full.status == 2 || full.status == 5 || full.status == 6) ? alertEditDatatable(full.status) : checkTimeToEdit(full.schedule) ? GLOBAL_LANG.whatsapp_status_datatables_edit_less_than_one_hour : GLOBAL_LANG.whatsapp_status_datatable_column_action_edit}">
                                                <div style="width: 24px; display: inline-block"> 
                                                    <i class="far fa-edit" style="font-size: 12pt; margin-left: 3px"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.whatsapp_status_datatable_column_action_edit}</span>
                                            </a>
                                        </div>
                                    </div>`
                }
            },
            {
                orderable: false, targets: [0, 1, 5]
            }
        ],
        order: [[1, 'desc']],
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

            const status = {
                search: $("#search").val(),
                input_search: $("#input-search").val(),
                channel: $("#multiselect2").val(),
                status: $("#select-status").val(),
                dt_start: $("#dt-start").val(),
                dt_end: $("#dt-end").val(),
            }

            let filter = localStorage.getItem("filters");

            filter = filter ? JSON.parse(filter) : {};
            filter.status = status;

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

function containsSupport(email) {
    const emailLowerCase = email.toLowerCase();
    return emailLowerCase.startsWith('suporte') && emailLowerCase.endsWith('@talkall.com.br');
}

function submit() {
    $("[name='csrf_token_talkall']").val(Cookies.get("csrf_cookie_talkall"));
    $("form").unbind('submit').submit();
}

async function ruleSchedule() {
    const persona = document.getElementById("select_segmented_group").value > 0 ? document.getElementById("select_segmented_group").value : "";

    const edit = document.getElementById("edit")?.value ?? "";
    const formData = new FormData();
    formData.append("channels", $("#multiselect").val());
    formData.append("date_start", $("#date_start").val());
    formData.append("time_start", $("#time_start").val());
    formData.append("persona", persona);
    formData.append("count_doc", document.querySelectorAll("#dropBrodcast .col-6.broadcast").length);
    formData.append("is_wa_status", 1);
    formData.append("is_wa_broadcast", 2);

    formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

    const response = await fetch(document.location.origin + "/publication/whatsapp/status/ruleSchedule", {
        method: 'POST',
        body: formData
    });

    const result = await response.json();

    if (edit !== "expire") {
        if (Array.isArray(result.conflicts) && result.conflicts.length > 0) {
            showModalOverlap(result.conflicts);
            return;
        }
    }

    let rules = [];
    let channelNames = [];
    const data = result.data;

    for (let i = 0; i < data.length; i++) {
        const element = data[i];
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

function updateCharacterCount() {

    const inputElement = $("#input-data");
    const countElement = document.getElementById("count_caracter");

    let caractrs = inputElement.val() || "";

    const countEnters = (caractrs.match(/\n/g) || []).length;

    let totalLength = caractrs.length;

    const maxLength = 700 - countEnters;
    if (totalLength > maxLength) {
        caractrs = caractrs.slice(0, maxLength);
        inputElement.val(caractrs);
    }

    totalLength = caractrs.length + countEnters;

    inputElement.prop("maxLength", maxLength);

    countElement.textContent = 700 - totalLength;
    countElement.style.color = "red";
    countElement.style.fontSize = ".875rem";
}


function alertErrors(data) {
    if (data.errors?.code == "TA-023") {
        swal({
            title: GLOBAL_LANG.whatsapp_status_error_title,
            text: `${GLOBAL_LANG.whatsapp_status_error_ta023} (${data.errors.code})`,
            type: "error",
            buttonsStyling: !1,
            confirmButtonClass: "btn btn-danger"
        });
    }

    if (data.errors?.code == "TA-024") {
        swal({
            title: GLOBAL_LANG.whatsapp_status_error_title,
            text: `${GLOBAL_LANG.whatsapp_status_error_ta024} (${data.errors.code})`,
            type: "error",
            buttonsStyling: !1,
            confirmButtonClass: "btn btn-danger"
        });
    }
}


$(document).ready(function () {


    const multiselectAdd = document.getElementById("multiselect");
    const view = document.getElementById("view");
    const edit = document.getElementById("edit");

    if (multiselectAdd != null && !edit) {

        //*** ADD ***//
        let before, id_before, later, id_later;

        select = new MSFmultiSelect(
            document.querySelector('#multiselect'), {
            theme: 'theme1',
            selectAll: true,
            searchBox: true,
            width: "100%",
            height: 47,
            placeholder: GLOBAL_LANG.whatsapp_status_select,
            onChange: function (checked, value, instance) {
                if (select == "") select = value;

                if (checked == false) {
                    $(".ignore.add").children().prop('checked', false);
                };
            },
        });


        $('.time').mask(maskBehavior, spOptions);


        // const status_image = document.getElementById("status_image");
        showMediaInput();
        // const status_text = document.getElementById("status_text");

        // if (status_image != null)
        //     status_image.addEventListener("click", showMediaInput)

        // if (status_text != null)
        //     status_text.addEventListener("click", showTextInput)

        const choose_media = document.querySelectorAll(".choose_media");
        // const choose_text = document.querySelectorAll(".choose_text");

        choose_media.forEach(chosen => {
            chosen.addEventListener("click", () => changeTypeWarning("media"))
        });

        // choose_text.forEach(chosen => {
        //     chosen.addEventListener("click", () => changeTypeWarning("text"))
        // });


        let fileSelect = document.getElementById("fileSelect"),
            fileElem = document.getElementById("fileElem");

        fileSelect.addEventListener("click", function (e) {
            if (fileElem) fileElem.click();
            e.preventDefault();
        }, false);


        $("body").on("click change keyup keydown paste cut", function () {
            updateCharacterCount();
        });


        $("body").on("mouseover", ".box-status", function () {
            $("#" + this.id).find(".close-status").css({ "display": "block" });
        });


        $("body").on("mouseout", ".box-status", function () {
            $(".close-status").css({ "display": "none" });
        });


        $("body").on("click", ".close-status", function () {

            $("#" + this.id).parent().parent().parent().remove();

            if ($(".box-status").length < 1) {
                $(".drop-inner-img").show();
                $(".drop-inner-title").show();
                $(".drop-inner-text").show();
            }
        });


        $("body").on("dragstart", ".box-status", function () {

            let id = this.id;
            id_before_elm = id;

            id_before = $("#" + id).parent().attr("id");
            before = document.getElementById(id);

            $(".close-status").hide();
        });


        $("body").on("dragover", ".box-status", function () {

            let id = this.id;
            iden = $("#" + id).parent().attr("id");

            $("#" + iden).css({ "border": "1px blue dashed" });
            $("#" + iden).css({ "background": "rgb(174 174 174)", "opacity": " 0.2", "z-index": "99" });
        });


        $("body").on("dragleave", ".box-status", function () {

            let id = this.id;
            iden = $("#" + id).parent().attr("id");

            $("#" + iden).css({ "border": "0px blue dashed" });
            $("#" + id).css({ "width": "auto", "margin-left": "0px" });
            $("#" + iden).css({ "background": "#fff", "opacity": "", "z-index": "" });
        });


        $("body").on("dragend", ".box-status", function (e) {

            var elms = document.querySelectorAll(".bfather");

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

            setTimeout(function () { addName(); }, 500);

            $(".box-status").css({ "width": "auto", "margin-left": "0px" });
            $(".bfather").css({ "border": "0px #1212ffdb dashed" });
            $(".bfather").css({ "background": "#fff", "opacity": "", "z-index": "" });
        });


        $('body').on('change keyup keydown paste cut', '#tex_ar', function () {
            $(this).height(0).height(this.scrollHeight);
        }).find('#tex_ar').change();


        if ($(".retrieve").hasClass("retrieve")) {

            $(".retrieve").each(function (idx, elm) {
                setTimeout(function () {
                    let el = {
                        "url": elm.children[0].value,
                        "text": elm.children[1].value,
                        "thumbnail": elm.children[2].value,
                        "media_type": elm.children[3].value,
                        "media_size": elm.children[4].value,
                        "retrieve": true,
                    }

                    createStatus(el);

                }, 500 + (idx * 350));
            });

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
            editStatus(element);
        }

        $("form").submit(event => event.preventDefault());
        document.querySelector(".btn-success").addEventListener("click", validateFields);

    } else if (view != null) {

        //** VIEW **//

        $('#tex_ar').each(function () {
            this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
        }).on('input', function () {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

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

            let exist_class, thumb;

            exist_class = this.attributes.class;
            preview = this;

            if (exist_class != undefined) {

                thumb = this.attributes.class.nodeValue;

                if (thumb.includes("thumb")) {

                    $(".preview-thumb").remove();
                }
            }

        });

        $("#datatable-basic").on("click", ".table-action-view", function () {
            window.location.href = "status/view/" + this.id;
        });

        $("#datatable-basic").on("click", ".table-action-edit", function () {
            window.location.href = "status/edit/" + this.id;
        });

        $("#datatable-basic").on("click", ".table-action-resend", function () {
            swal({
                title: GLOBAL_LANG.whatsapp_status_alert_resend_title,
                text: GLOBAL_LANG.whatsapp_status_alert_resend_text,
                type: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                reverseButtons: true,
                confirmButtonClass: "btn btn-success",
                confirmButtonText: GLOBAL_LANG.whatsapp_status_alert_resend_confirmButtonText,
                cancelButtonClass: "btn btn-danger",
                cancelButtonText: GLOBAL_LANG.whatsapp_status_alert_resend_cancelButtonText,
            }).then(t => {
                if (t.value == true) {
                    $.post(document.location.origin + "/publication/whatsapp/status/resend/" + this.id, function (data) {
                        if (data.success?.status == true) {
                            t.value && swal({
                                title: GLOBAL_LANG.whatsapp_status_alert_resend_two_title,
                                text: GLOBAL_LANG.whatsapp_status_alert_resend_two_text,
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
                title: GLOBAL_LANG.whatsapp_status_alert_group_delete_title,
                text: GLOBAL_LANG.whatsapp_status_alert_group_delete_text,
                type: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                reverseButtons: true,
                confirmButtonClass: "btn btn-success",
                cancelButtonText: GLOBAL_LANG.whatsapp_status_alert_group_delete_cancelButtonText,
                cancelButtonClass: "btn btn-danger",
                confirmButtonText: GLOBAL_LANG.whatsapp_status_alert_group_delete_confirmButtonText,
            }).then(t => {
                if (t.value == true) {
                    $.ajax({
                        type: "POST",
                        url: document.location.origin + "/publication/whatsapp/status/cancelgroup",
                        data: tokens,
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (data) {

                            if (data.errors?.code == "TA-027") {
                                swal({
                                    title: GLOBAL_LANG.whatsapp_status_validation_cancel_title,
                                    text: `${GLOBAL_LANG.whatsapp_status_validation_cancel_description} (${data.errors.code})`,
                                    type: "error",
                                    buttonsStyling: !1,
                                    confirmButtonClass: "btn btn-danger"
                                });
                            }

                            if (data.success?.status === true) {
                                t.value && swal({
                                    title: GLOBAL_LANG.whatsapp_status_alert_delete_two_title,
                                    text: GLOBAL_LANG.whatsapp_status_alert_delete_two_text,
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
                                title: GLOBAL_LANG.whatsapp_status_validation_cancel_title,
                                text: GLOBAL_LANG.whatsapp_status_validation_cancel_description,
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
                title: GLOBAL_LANG.whatsapp_status_alert_delete_title,
                text: GLOBAL_LANG.whatsapp_status_alert_delete_text,
                type: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                reverseButtons: true,
                confirmButtonClass: "btn btn-success",
                confirmButtonText: GLOBAL_LANG.whatsapp_status_alert_delete_confirmButtonText,
                cancelButtonClass: "btn btn-danger",
                cancelButtonText: GLOBAL_LANG.whatsapp_status_alert_delete_cancelButtonText,
            }).then(t => {
                if (t.value == true) {
                    $.post("status/cancel/" + this.id, function (data) {

                        if (data.errors?.code == "TA-027") {
                            swal({
                                title: GLOBAL_LANG.whatsapp_status_validation_cancel_title,
                                text: `${GLOBAL_LANG.whatsapp_status_validation_cancel_description} (${data.errors.code})`,
                                type: "error",
                                buttonsStyling: !1,
                                confirmButtonClass: "btn btn-danger"
                            });
                        }

                        if (data.success?.status === true) {
                            t.value && swal({
                                title: GLOBAL_LANG.whatsapp_status_alert_delete_two_title,
                                text: GLOBAL_LANG.whatsapp_status_alert_delete_two_text,
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

    $('#campaign_estimate_suspend').on('click', () => campaignSuspend('modal-campaign-estimate'));
    $('#campaign_overlap_suspend').on('click', () => campaignSuspend('modal-campaign-overlap'));
    $('#campaign_estimate_review').on('click', () => console.log('Cliquei no review'));
    $('#campaign_estimate_change').on('click', () => $('#modal-campaign-estimate').modal('hide'));
    $('#campaign_overlap_change').on('click', () => $('#modal-campaign-overlap').modal('hide'));
    $('#campaign_estimate_send_partially').on('click', () => campaignEstimateSendPartial());
    $('#campaign_estimate_send_after').on('click', () => sendCampaignAfterWorkTime("modal-campaign-estimate"));
    $('#campaign_overlap_send_after').on('click', () => sendCampaignAfterWorkTime("modal-campaign-overlap"));

});



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
        &type=publicationWhatsappStatus`, function (response) {
        Swal.fire({
            title: GLOBAL_LANG.whatsapp_status_alert_export_title,
            text: GLOBAL_LANG.whatsapp_status_alert_export_text,
            type: 'success',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: GLOBAL_LANG.whatsapp_status_alert_export_confirmButtonText
        });
    });
}


function changeTypeWarning(type) {

    swal({
        title: GLOBAL_LANG.whatsapp_status_alert_change_type_title,
        text: GLOBAL_LANG.whatsapp_status_alert_change_type_text,
        type: "warning",
        showCancelButton: !0,
        buttonsStyling: !1,
        reverseButtons: true,
        confirmButtonClass: "btn btn-success",
        confirmButtonText: GLOBAL_LANG.whatsapp_status_alert_change_type_yes,
        cancelButtonClass: "btn btn-danger",
        cancelButtonText: GLOBAL_LANG.whatsapp_status_alert_change_type_no,
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

    const media_remove = document.querySelectorAll(".col-4.bfather");
    media_remove.forEach(element => {
        element.remove();
    });

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
    const dropStatus = document.getElementById("dropStatus");
    const type_status = document.getElementById("type_status");

    const change_type_icon_img = document.getElementById("change_type_icon_img");
    const change_type_icon_text = document.getElementById("change_type_icon_text");

    // status_text.style.display = "none";
    status_image.style.display = "none";

    label_chose_type.style.display = "none";
    data_status.style.display = "none";
    input_files.style.display = "block";
    dropStatus.style.display = "block";
    type_status.value = "image";

    change_type_icon_img.style.display = "none";
    // change_type_icon_text.style.display = "block";

};

function showTextInput() {

    clearMediaInput()

    const data_status = document.getElementById("status_data");
    const type_status = document.getElementById("type_status");

    const change_type_icon_img = document.getElementById("change_type_icon_img");
    const change_type_icon_text = document.getElementById("change_type_icon_text");

    // status_text.style.display = "none";
    status_image.style.display = "none";

    label_chose_type.style.display = "none";
    input_files.style.display = "none";
    dropStatus.style.display = "none";
    data_status.style.display = "block";
    type_status.value = "text";

    change_type_icon_text.style.display = "none";
    change_type_icon_img.style.display = "block";

}

function addResendToTimeline(data) {

    let timeline = $(".timeline");

    let timeline_block = document.createElement("div");
    timeline_block.className = "timeline-block mb-3";

    let timeline_step = document.createElement("span");
    timeline_step.className = "timeline-step";

    let timeline_icon = document.createElement("i");
    timeline_icon.className = "fas fa-redo";

    let timeline_content = document.createElement("div");
    timeline_content.className = "timeline-content";

    let timeline_text = document.createElement("h6");
    timeline_text.className = "text-dark text-sm font-weight-bold mb-0";
    timeline_text.innerText = GLOBAL_LANG.whatsapp_status_resent_broadcast_timeline_view;

    let timeline_creation = document.createElement("p");
    timeline_creation.className = "font-weight-bold text-xs mt-1 mb-0";
    timeline_creation.innerText = data.creation;

    timeline_step.append(timeline_icon);
    timeline_block.append(timeline_step);
    timeline_content.append(timeline_text);
    timeline_content.append(timeline_creation);
    timeline_block.append(timeline_content);
    timeline.prepend(timeline_block);
}

function addMinutes(date, minutes) {
    date.setMinutes(date.getMinutes() + minutes);
    return date;
}

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
            message = GLOBAL_LANG.whatsapp_status_datatables_edit_status_2
            break;
        case '5':
            message = GLOBAL_LANG.whatsapp_status_datatables_edit_status_5
            break;
        case '1':
        case '6':
            message = GLOBAL_LANG.whatsapp_status_datatables_edit_status_6
            break;
        default:
            message = GLOBAL_LANG.whatsapp_status_datatables_edit_default
            break;
    }

    return message;
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
            window.location.href = document.location.origin + '/publication/whatsapp/status';
        } else {
            $(`#${modalId}`).modal('show');
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

async function validateField() {
    let form_validation = true;

    const isEmpty = (val) => !val || val.trim().length === 0;
    const status_image = $("#status_image").is(":visible");
    // const status_text = $("#status_text").is(":visible");

    const url = window.location.href;
    if (url.includes("/edit/")) {
        const token = url.split("/").pop();
        const result = await fetch(`${document.location.origin}/publication/whatsapp/status/checktime/edit/${token}`);
        const data = await result.json();

        if (data.errors) {
            alertErrors(data);
            return false;
        }
    }

    // Título
    if (isEmpty($("#input_title").val())) {
        $("#alert_input_title").show();
        form_validation = false;
    } else {
        $("#alert_input_title").hide();
    }

    // Data
    if (!checkDate($("#date_start").val()?.trim())) {
        $("#alert_date_start").show();
        form_validation = false;
    } else {
        $("#alert_date_start").hide();
    }

    // Hora
    if (isEmpty($("#time_start").val())) {
        $("#alert_time_start").show();
        form_validation = false;
    } else {
        $("#alert_time_start").hide();
    }

    // Segmentações
    const multiselectVal = $("#multiselect").val();
    if (!multiselectVal || multiselectVal.length === 0) {
        $("#alert_multi_selects").show();
        form_validation = false;
    } else {
        $("#alert_multi_selects").hide();
    }

    // Mídia e texto
    const inputData = $("#input-data").val();
    const inputDataVisible = $("#status_data").is(":visible");

    if (status_image) {
        $("#alert_input_img_text").show();
        form_validation = false;
    } else if (
        $(".col-4.bfather").length === 0 &&
        !status_image &&
        // !status_text &&
        !inputDataVisible
    ) {
        $("#alert_input_img_text").hide();
        $("#alert_upload_media").show();
        form_validation = false;
    } else if (inputData !== undefined) {
        $("#alert_upload_media").hide();
        $("#alert_textarea_message").hide();

        if (isEmpty(inputData) && inputDataVisible) {
            $("#alert_textarea_message").show();
            form_validation = false;
        }
    } else {
        $("#alert_input_img_text, #alert_upload_media, #alert_textarea_message").hide();
    }

    return form_validation;
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

function countCaracter(e) {
    let quantity_digits = e.target.value.length;
    let max_digits = 700;

    if (quantity_digits <= max_digits) {
        let characters_remaining = max_digits - quantity_digits;
        let parentElement = e.target.parentElement.parentElement;

        let total_digits = parentElement.querySelector('.quantity-caracter');

        total_digits.textContent = characters_remaining;

    }
}