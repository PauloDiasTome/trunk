// "use strict";

const Components = new ComponentsDom();
const Filters = JSON.parse(localStorage.getItem("filters")) || null;


function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.tv.search) {
            document.getElementById("search").value = Filters.tv.search;
        }

        if (Filters.tv.input_search) {
            document.getElementById("input-search").value = Filters.tv.input_search;
        }

        if (Filters.tv.channel.length !== 0) {
            modalFilter();
            document.getElementById("check-select2").click();

            document.querySelectorAll("#mult-select2 .cust_").forEach((element, index) => {

                if (Filters.tv.channel.includes(element.value))
                    document.querySelectorAll("#mult-select2 .cust_")[index].click();
            })
        }

        if (Filters.tv.status) {
            modalFilter();
            document.getElementById("check-status").click();
            document.getElementById("select-status").value = Filters.tv.status;
        }

        if (Filters.tv.dt_start) {
            modalFilter();

            document.getElementById("check-date").click();

            document.getElementById("dt-end").disabled = false;
            document.getElementById("dt-end").type = "date";
            document.getElementById("dt-start").type = "date";

            document.getElementById("dt-start").value = Filters.tv.dt_start;
            document.getElementById("dt-end").value = Filters.tv.dt_end;
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
                mData: 'media_url'
            },
            {
                mData: 'title'
            },
            {
                mData: 'schedule'
            },
            {
                mData: 'expire'
            },
        ],
        columnDefs: [
            {
                targets: 0,
                orderable: false,
                className: "thumb",
                render: function (data, type, full, meta) {
                    return "<div class='box-inner-datatable'><img src='" + document.location.origin + "/assets/img/loads/loading_2.gif' data-media-url='" + full.media_url + "' style='padding:8px'></div>";
                }
            },
            {
                targets: 1,
                render: function (data, type, full, meta) {
                    return '<b>' + full.title + '</b><br>' + full.name;
                }
            },
            {
                targets: 4,
                render: function (data, type, full, meta) {
                    let ret = `<span class="badge badge-sm badge-secondary">${GLOBAL_LANG.whatsapp_broadcast_datatable_column_status_processing}</span>`
                    switch (full.status) {
                        case '2':
                            ret = `<span class="badge badge-sm badge-success">${GLOBAL_LANG.tv_broadcast_filter_status_completed}</span>`
                            break;
                        case '3':
                            ret = `<span class="badge badge-sm badge-secondary">${GLOBAL_LANG.tv_broadcast_filter_status_scheduled}</span>`
                            break;
                        case '5':
                            ret = `<span class="badge badge-sm badge-danger">${GLOBAL_LANG.tv_broadcast_filter_status_called_off}</span>`
                            break;
                        case '6':
                            switch (full.is_paused) {
                                case "1":
                                    ret = `<span class="badge badge-sm badge-warning">${GLOBAL_LANG.tv_broadcast_filter_status_paused}</span>`
                                    break;
                                case "2":
                                    ret = `<span class="badge badge-sm badge-info">${GLOBAL_LANG.tv_broadcast_filter_status_on_display}</span>`
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
                                                <span>${GLOBAL_LANG.tv_broadcast_datatable_column_action_view}</span>
                                            </a>`

                    switch (full.status) {

                        case "6":
                            switch (full.is_paused) {
                                case "1":
                                    res += `<a id="${full.token}" class="dropdown-item table-action-resume action-resume" style="cursor: pointer" data-toggle='tooltip' data-placement='right' title='${GLOBAL_LANG.whatsapp_broadcast_datatable_column_action_resume_distribution}'> 
                                                <div style="width: 24px; display: inline-block"> 
                                                    <i class="far fa-play-circle" style="font-size: 13pt"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.tv_broadcast_datatable_column_action_resume_distribution}</span>
                                            </a>`
                                    break;
                                case "2":
                                    res += `<a id="${full.token}" class="dropdown-item table-action-pause action-pause" style="cursor: pointer" data-toggle='tooltip' data-placement='right' title='${GLOBAL_LANG.whatsapp_broadcast_datatable_column_action_pause_distribution}'> 
                                                <div style="width: 24px; display: inline-block"> 
                                                    <i class='far fa-pause-circle' style="font-size: 13pt"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.tv_broadcast_datatable_column_action_pause_distribution}</span>
                                            </a>`
                                    break;
                            }
                            break;
                    }

                    res += `<a id="${full.token}" class="dropdown-item ${full.status == 4 ? 'table-action-deleted' : (full.status == 5 || full.status == 2 || full.status == 4 ? 'table-action-deleted' : 'table-action-delete')} action" style="cursor: pointer">
                                <div style="width: 24px; display: inline-block"> 
                                    <i class="fas fa-times" style="font-size: 12pt; margin-left: 3px"></i>
                                </div>
                                <span>${GLOBAL_LANG.tv_broadcast_datatable_column_action_cancel}</span>
                            </a>
                        </div>
                     </div>
                  </div>`

                    return res;
                }
            },
            {
                orderable: false, targets: [0, 3, 4]
            }
        ],
        order: [[0]],
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
            if (settings.json.recordsTotal == 0) {
                $("#modalExport").attr('disabled', true);
            } else {
                $("#modalExport").attr('disabled', false);
            }

            const tv = {
                search: $("#search").val(),
                input_search: $("#input-search").val(),
                channel: $("#multiselect2").val(),
                status: $("#select-status").val(),
                dt_start: $("#dt-start").val(),
                dt_end: $("#dt-end").val(),
            }

            let filter = localStorage.getItem("filters");

            filter = filter ? JSON.parse(filter) : {};
            filter.tv = tv;

            localStorage.setItem("filters", JSON.stringify(filter));

            document.querySelectorAll(".box-inner-datatable").forEach(async (elm) => {

                var response = await fetch(elm.children[0].getAttribute("data-media-url"));
                var data = await response.blob();
                var metadata = { type: 'video/mp4' };
                var file = new File([data], "img.jpg", metadata);

                var video = document.createElement("video");
                video.src = URL.createObjectURL(file);
                video.style.padding = "0px";

                elm.children[0].remove();
                elm.appendChild(video);

            });
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

    $("#modalFilter").one("click", () => modalFilter());
    $('#sendEmailExport').on('click', () => modalExport());

    $("#datatable-basic").on("click", ".table-action-delete", function () {
        cancelBroadcast(this.id);
    });

    $("#datatable-basic").on("click", ".table-action-pause", function () {
        pauseBroadcast(this.id);
    });

    $("#datatable-basic").on("click", ".table-action-resume", function () {
        resumeBroadcast(this.id);
    });

    $("#datatable-basic").on("click", ".table-action-view", function () {
        window.location.href = "broadcast/view/" + this.id;
    });

    $("#datatable-basic tbody").on("mouseover", "td", async function () {
        const is_class = this.attributes.class;

        if (is_class != undefined) {

            const thumb = this.attributes.class.nodeValue;

            if (thumb.includes("thumb")) {

                const box = document.createElement("div");
                box.className = "preview-thumb";

                $(".preview-thumb").remove();

                var video = document.createElement("video");
                video.src = this.firstChild.firstChild.attributes.src.nodeValue;

                box.appendChild(video);
                this.prepend(box);

                box.animate([
                    { opacity: '0' },
                    { opacity: '1' }
                ], {
                    duration: 500,
                });
            }
        }

    });

    $("#datatable-basic tbody").on("mouseout", "td", function () {
        const is_class = this.attributes.class;

        if (is_class != undefined) {
            const thumb = this.attributes.class.nodeValue;

            if (thumb.includes("thumb")) {
                $(".preview-thumb").remove();
            }
        }
    });

    const multiselect = document.querySelector('#multiselect');
    if (multiselect) {
        new MSFmultiSelect(
            multiselect, {
            theme: 'theme2',
            selectAll: true,
            searchBox: true,
            width: "100%",
            height: 47,
        });
    }

    $("body").on("dragstart", ".drag_", function (e) {
        id_post = this.id;
        id_elm_dragged = this.children[0].id;
        elm_dragged = document.getElementById(id_elm_dragged);

        preventDefault = true;
    });

    $("body").on("dragover", ".drag_", function (e) {
        const post = document.getElementById(this.id);
        post.style.border = "1px blue dashed";
        post.style.background = "rgb(174 174 174)";
        post.style.zIndex = "99";
        post.style.opacity = "0.2";
    });

    $("body").on("dragleave", ".drag_", function (e) {
        const post = document.getElementById(this.id);
        post.style.border = "1px #fff dashed";
        post.style.background = "#fff";
        post.style.zIndex = "0";
        post.style.opacity = "1";
    });

    $("body").on("dragend", ".drag_", function (e) {
        const elms = document.querySelectorAll(".post");

        for (area of elms) {

            if ((e.clientX > area.getBoundingClientRect().x &&
                e.clientX < area.getBoundingClientRect().x + area.clientWidth) &&
                (e.clientY > area.getBoundingClientRect().y &&
                    e.clientY < area.getBoundingClientRect().y + area.clientHeight)) {

                if (area.childElementCount == 1) {

                    if (area.id != id_post) {

                        const id_post_current = area.id;
                        const elm_current = area.children[0];

                        document.getElementById(area.children[0].id).remove();
                        document.getElementById(id_elm_dragged).remove();

                        const post_dragged = document.getElementById(id_post);
                        const post_current = document.getElementById(id_post_current);

                        post_dragged.appendChild(elm_current);
                        post_current.appendChild(elm_dragged);
                    }
                }
            }
        }
        $(".post").css({ "background": "#fff", "opacity": "", "z-index": "" });
        $(".post").css({ "border": "solid 1px #fff" });
    });

    broadcast.helpers.setDateTime("datetime_start", 5);
    broadcast.helpers.setDateTime("datetime_end", 60);

    broadcast.files.openBox("file-select", "file-elm");
    broadcast.files.openBox("file-select-modal", "file-elm-modal");

    $("form").submit(event => event.preventDefault());
    document.querySelector(".btn-success")?.addEventListener("click", submit);
});

const broadcast = {

    files: {
        openBox(file_select_id, file_elm_id) {
            const file_select = document.getElementById(file_select_id);
            const file_elm = document.getElementById(file_elm_id);

            file_select?.addEventListener("click", (e) => {
                if (file_elm) {
                    file_elm.click();
                    $("#modal-upload").modal("show");
                }

                broadcast.files.showDrop();
                document.querySelector("body").classList.remove("play");
                e.preventDefault();
            }, false);
            broadcast.files.showDrop();
            document.querySelector("body").classList.remove("play");
        },
        get() {
            const data = [];

            document.querySelectorAll(".box-post").forEach(elm => {
                const url_input = elm.children[0].querySelector(".file");
                const second_input = elm.children[1].querySelector(".input-time");

                const url = url_input.value;
                const second = parseInt(second_input.value);

                const modified_url = url.replace("https://files.talkall.com.br:3000/v/", "/files/f/");
                const type = url.endsWith(".mp4") ? "video" : "image";

                const item = {
                    url: modified_url,
                    type: type,
                    second: second
                };

                data.push(item);
            });

            return data;
        },
        upload(file) {
            const form_data = new FormData();
            const ta_id = Math.floor(Math.random() * 100000);

            file.ta_id = ta_id;
            broadcast.files.create(file);

            form_data.append("filetoupload", file);
            form_data.append("ta_id", ta_id);

            const settings = {
                "url": "https://files.talkall.com.br:3000",
                "method": "POST",
                "timeout": 0,
                "crossDomain": true,
                "processData": false,
                "mimeType": "multipart/form-data",
                "contentType": false,
                "data": form_data
            };

            $.ajax(settings).done(function (response) {
                broadcast.files.addthumb(JSON.parse(response));
            });
        },
        updateSecond() {
            const seconds = document.getElementById('dropdown-value');
            seconds.value = this.value;

            const input_time = document.getElementById("seconds_" + this.id.split("range_")[1]);
            input_time.value = this.value;
        },
        updateInfo() {
            const total_files = document.querySelectorAll(".post").length;
            let total_minutes = 0;
            let total_seconds = 0;

            document.querySelectorAll(".bottom_ .input-time").forEach(elm => {
                let time = "";

                if (elm.dataset.video != "true") {
                    if (elm.value > 10)
                        time = "0:0" + elm.value;
                    else
                        time = "0:" + elm.value;

                } else {
                    if (elm.value.includes(":"))
                        time = elm.value;
                    else
                        time = "0:" + elm.value;
                }

                let parts = time.split(":");
                let minutes = parseInt(parts[0]);
                let seconds = parseInt(parts[1]);

                total_minutes += minutes;
                total_seconds += seconds;
            });

            total_minutes += Math.floor(total_seconds / 60);
            total_seconds = total_seconds % 60;

            document.getElementById("totalImage").innerHTML = total_files;
            document.getElementById("totalDuration").innerHTML = total_minutes + ":" + (total_seconds < 10 ? '0' : '') + total_seconds;
        },
        setSecondsForAll() {
            document.querySelectorAll(".input-time").forEach(elm => {
                if (elm.disabled !== true) {
                    elm.value = document.getElementById("dropdown-value").value;
                }
            });
            broadcast.helpers.handleBodyClick(this);
            document.querySelector(".dropdown-box")?.remove();
            broadcast.files.updateInfo();

        },
        dropdown(e) {
            e.stopPropagation();

            document.querySelectorAll(".drag_").forEach(elm => {
                elm.className = "post";
                elm.firstChild.draggable = false;
            });

            document.querySelector(".dropdown-box")?.remove();
            const dropdown = Components.div({ class: "dropdown-box", id: "dropdown-box" });

            const header = Components.div({ class: "_header" });
            const title = Components.span({ text: GLOBAL_LANG.tv_broadcast_dropdown_duration });
            const seconds = Components.input({ type: "number", class: "value", id: "dropdown-value", value: document.getElementById("seconds_" + e.target.id.split("clock_")[1]).value });

            const body = Components.div({ class: "_body" });
            const input = Components.input({ type: "range", id: "range_" + e.target.id.split("clock_")[1], value: document.getElementById("seconds_" + e.target.id.split("clock_")[1]).value, customAttribute: ["min", "1", "max", "60", "step", "1"] });

            const footer = Components.div({ class: "_footer" });
            const desc = Components.span({ text: GLOBAL_LANG.tv_broadcast_dropdown_apply_to_everyore });
            const checkbox = Components.input({ type: "checkbox" });

            input.addEventListener("input", broadcast.files.updateSecond);
            input.addEventListener('input', broadcast.files.updateInfo);
            input.addEventListener('change', broadcast.files.updateSecond);
            seconds.addEventListener("input", broadcast.helpers.limitCaracter);
            checkbox.addEventListener("click", broadcast.files.setSecondsForAll);

            header.appendChild(title);
            header.appendChild(seconds);
            body.appendChild(input);
            footer.appendChild(desc);
            footer.appendChild(checkbox);

            dropdown.appendChild(header);
            dropdown.appendChild(body);
            dropdown.appendChild(footer);
            dropdown.addEventListener("click", (e) => e.stopPropagation());

            this.insertAdjacentElement('afterend', dropdown);
        },
        async create(data) {
            document.querySelector(".drop-group-desc").style.display = "none";

            const uuid = Math.floor(Math.random() * 100000);
            const post = Components.div({ class: "post drag_", id: data.ta_id });
            const box = Components.div({ class: "box-post", id: uuid, customAttribute: ["draggable", true] });

            const top = Components.div({ class: "top_" });
            const image = Components.img({ class: "load", src: `${document.location.origin}/assets/img/loads/loading_2.gif` });
            const close = Components.img({ class: "close", id: "remov_" + uuid, src: `${document.location.origin}/assets/img/statusClose.png` });
            const file = Components.input({ class: "file", type: "hidden" });
            const dimension = Components.span({ class: "dimension", text: await broadcast.helpers.getDimensionsFromFile(data) });

            const bottom = Components.div({ class: "bottom_" });
            const input = Components.input({ class: "input-time", id: "seconds_" + uuid, value: await broadcast.helpers.getVideoDuration(data), type: data.type == "video/mp4" ? "text" : "number", customAttribute: ["pattern", "[0-9]*2"], "maxlength": 2 });
            const time = Components.div({ class: "time" });
            const icon = Components.i({ class: "far fa-clock", id: "clock_" + uuid, customAttribute: ["data-toggle", "dropdown", "aria-haspopup", "true", "aria-expanded", "false"] });
            const megabytes = Components.span({ text: "", class: "size" });

            if (data.type === "video/mp4") {
                input.disabled = true;
                input.dataset.video = "true";
                time.classList.add("is-video");
            } else
                icon.addEventListener("click", broadcast.files.dropdown);

            input.addEventListener("change", broadcast.files.updateInfo);

            top.appendChild(image);
            top.appendChild(close);
            top.appendChild(file);
            top.appendChild(dimension);

            time.appendChild(icon);
            time.appendChild(input);

            bottom.appendChild(time);
            bottom.appendChild(megabytes);

            box.appendChild(top);
            box.appendChild(bottom);
            post.appendChild(box);

            input.addEventListener("input", broadcast.helpers.limitCaracter);
            document.getElementById("dropBrodcast").appendChild(post);
        },
        addthumb(data) {
            const box_post = document.getElementById(data.ta_id);
            const img = box_post.querySelector('.load');
            const file = box_post.querySelector('.file');
            const size = box_post.querySelector(".size");

            if (img && file) {
                img.src = "";
                img.src = "data:image/jpeg;base64," + data.thumbnail;
                img.className = "img";
                file.value = data.url;
                size.innerHTML = broadcast.helpers.bytesToSize(data.size);
            }

            const post = document.querySelectorAll(".post");
            const close = document.querySelectorAll(".close");

            post.forEach((elm) => elm.addEventListener("mouseover", broadcast.files.mouseover));
            post.forEach((elm) => elm.addEventListener("mouseout", broadcast.files.mouseout));
            close.forEach((img) => img.addEventListener("click", broadcast.files.remove));

            broadcast.files.updateInfo();
        },
        mouseover() {
            const post = document.getElementById(this.id);
            post.firstChild.firstChild.children[1].style.display = "block";
            post.firstChild.firstChild.children[3].style.display = "block";
        },
        mouseout() {
            const post = document.getElementById(this.id);
            post.firstChild.firstChild.children[1].style.display = "none";
            post.firstChild.firstChild.children[3].style.display = "none";
        },
        remove() {
            const id = this.id.split("remov_")[1];
            document.getElementById(id).parentNode?.remove()

            const post = document.querySelectorAll(".post");

            if (post.length === 0)
                document.querySelector(".drop-group-desc").style.display = "block";

            broadcast.files.updateInfo();
        },
        showDrop() {
            const upload_element = document.querySelector(".space-upload");
            const load_element = document.querySelector(".space-load");

            if (upload_element && load_element) {
                upload_element.style.display = "block";
                load_element.style.display = "none";
            }
        },
        hideDrop() {
            document.querySelector("body").classList.remove("play");
            const upload_element = document.querySelector(".space-upload");
            const load_element = document.querySelector(".space-load");

            if (upload_element && load_element) {
                upload_element.style.display = "none";
                load_element.style.display = "flex";
            }
        },
        totalSize() {
            let total_bytes = 0;
            document.querySelectorAll(".size").forEach(elm => {
                let sizeText = elm.innerHTML;
                let sizeInKB = parseFloat(sizeText.replace(" KB", ""));

                totalBytes += sizeInKB;
            });

            return total_bytes;
        }
    },

    preview: {
        setIntervalProcess: null,

        videoMeker() {
            const data = broadcast.files.get();

            if (data.length === 0) return;

            broadcast.files.hideDrop();
            broadcast.preview.setIntervalProcess = setInterval(() => broadcast.helpers.processLoad(), 1000);

            const settings = {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ data })
            };

            fetch('https://files.talkall.com.br:3000/video/maker', settings)
                .then(response => response.json())
                .then(data => broadcast.preview.create(data))
                .catch(error => console.error('Erro ao enviar a solicitação:', error));
        },
        create(data) {
            $("#modal-upload").modal("hide");
            clearInterval(broadcast.preview.setIntervalProcess);
            document.getElementById("preview-broadcast")?.remove();
            document.getElementById("media-urlt")?.remove();
            document.getElementById("progress-bar").style.width = "0%";
            document.getElementById("row-preview").style.display = "flex";

            const slider = document.getElementById("container-slider");
            const form = document.querySelector("form");

            const video = Components.video({ class: "arq", id: "preview-broadcast", src: data.url });
            const input = Components.input({ type: "hidden", value: data.url, id: "media-url", name: "media_url" });

            video.controls = false;
            video.loop = true;
            video.play();

            slider.appendChild(video);
            form.appendChild(input);

            broadcast.preview.btnClose.create();
            broadcast.preview.btnDownload.create();

            $("#modal-preview").modal("show");
            document.querySelector("body").classList.add("play");
            document.getElementById("alert__input_file").style.display = "none";
        },
        edit() {
            broadcast.files.showDrop();
            document.querySelector("body").classList.remove("play");
        },
        play() {
            const slider = document.getElementById("container-slider");
            const preview = document.getElementById("preview-broadcast");
            const url = preview.src;
            preview.remove();

            const video = Components.video({ class: "arq", id: "preview-broadcast", src: url });
            video.controls = false;
            video.loop = true;
            video.play();

            slider.appendChild(video);

            broadcast.preview.btnClose.create();
            broadcast.preview.btnDownload.create();
            document.querySelector("body").classList.add("play");
        },

        btnClose: {
            create() {
                const close = Components.div({ class: "btns-actions btn-close", text: GLOBAL_LANG.tv_broadcast_btn_close });
                document.querySelector("body").appendChild(close);

                close.addEventListener("click", broadcast.preview.btnClose.click);
            },
            remove() {
                document.querySelector(".btn-close")?.remove();
            },
            click() {
                broadcast.preview.btnClose.remove();
                broadcast.preview.btnDownload.remove();

                $("#modal-preview").modal("hide");
            }
        },

        btnDownload: {
            create() {
                const download = Components.div({ class: "btns-actions btn-download", text: GLOBAL_LANG.tv_broadcast_btn_download });
                document.querySelector("body").appendChild(download);

                download.addEventListener("click", broadcast.preview.btnDownload.click);
            },
            remove() {
                document.querySelector(".btn-download")?.remove();
            },
            click() {
                const url = document.getElementById("preview-broadcast").src;
                const file_name = "video_maker";

                const link = document.createElement('a');
                link.href = url;
                link.download = file_name;
                link.click();
            }
        }
    },

    validation: {
        isAcceptedFileType(file) {
            const allowed_types = new Set(['image/jpeg', 'image/jpg', 'video/mp4']);

            if (allowed_types.has(file.type)) {
                
                if (file.name.includes("jfif")) {
                    return false;
                }

                return true;
            }
            else
                return false;
        },
        hasReachedLimit() {
            const total_files = $(".post").length;

            if (total_files >= 10)
                return false;
            else
                return true;
        },
        hasReachedMaxSize(file) {
            const max_file_size = 1 * 1024 * 1024;

            if (file.size > max_file_size)
                return false;
            else
                return true;
        },
        hasAcceptedDimension(width, height) {
            if ((width == 1280 && height == 720) || (width == 1920 && height == 1080) || (width == 1080 && height == 1920) || (width == 1080 && height == 1080)) {
                return true;
            } else {
                return false;
            }
        },
        hasAcceptedDucationLimit(file, duration) {
            if (file.type !== "video/mp4") return true;

            if (duration.includes(":")) {
                if (duration.split(":")[0] > 5) {
                    return false;
                } else {
                    return true;
                }
            }

            return true
        }
    },

    alert: {
        active: false,

        fileNotAllowed() {
            this.modalAlert(GLOBAL_LANG.tv_broadcast_alert_dropzone_not_allowed);
        },
        reachedLimit() {
            this.modalAlert(GLOBAL_LANG.tv_broadcast_alert_dropzone_reached_limit);
        },
        reachedMaxSize() {
            this.modalAlert(GLOBAL_LANG.tv_broadcast_alert_dropzone_reached_size);
        },
        acceptedDimension() {
            this.modalAlert(GLOBAL_LANG.tv_broadcast_alert_dropzone_dimensions);
        },
        acceptedDucationLimit() {
            this.modalAlert(GLOBAL_LANG.tv_broadcast_alert_dropzone_duration);
        },


        modalAlert(message) {
            if (broadcast.alert.active === true) return;

            const box = Components.div({ class: "alert-bg-box" });
            const alert = Components.div({ class: "modal-alert" });

            const title_box = Components.div({ class: "title-box" });
            const title_lang = Components.span({ text: "Atenção" });

            const description_box = Components.div({ class: "description-box" });
            const description_lang = Components.span({ text: message });

            const bottom_box = Components.div({ class: "buttom-box" });
            const btn_ok = Components.button({ class: "btn-yes", text: "Ok" });
            btn_ok.addEventListener("click", broadcast.alert.btnOk);

            title_box.appendChild(title_lang);
            description_box.appendChild(description_lang);
            bottom_box.appendChild(btn_ok);

            alert.appendChild(title_box);
            alert.appendChild(description_box);
            alert.appendChild(bottom_box);

            document.querySelector("body").appendChild(alert);
            document.querySelector("body").appendChild(box);

            broadcast.alert.active = true;
        },
        remove() {
            document.querySelector(".alert-bg-box")?.remove();
            document.querySelector(".modal-alert")?.remove();
        },
        btnOk() {
            broadcast.alert.remove();
        }
    },

    helpers: {
        validDateTime: true,

        convertDuration(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = seconds % 60;

            if (minutes === 0)
                return `${seconds}s`;
            else
                return `${minutes}.${remainingSeconds}m`;
        },
        getVideoDuration(data) {
            if (data.type !== "video/mp4") return 5;

            const video = document.createElement('video');
            video.src = URL.createObjectURL(data);
            video.controls = true;

            return new Promise((resolve, reject) => {
                video.onloadedmetadata = function () {
                    URL.revokeObjectURL(this.src);

                    var hours = Math.floor(Math.round(this.duration.toFixed(2)) / 60);
                    var minutes = Math.round(this.duration.toFixed(2)) % 60;

                    if (hours == 0)
                        resolve((minutes < 10 ? '0' : '') + minutes);
                    else
                        resolve(hours + ":" + (minutes < 10 ? '0' : '') + minutes);
                };

                video.onerror = function () {
                    reject(new Error('Unable to load the video'));
                };
            });
        },
        handleBodyClick(event) {
            const dropdownBox = document.querySelector(".dropdown-box");

            if (dropdownBox && !dropdownBox.contains(event.target)) {
                dropdownBox.remove();

                document.querySelectorAll(".post").forEach(elm => {
                    elm.className = "post drag_"
                    elm.firstChild.draggable = true;
                });
            }
        },
        bytesToSize(bytes) {
            const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
            if (bytes == 0) return '0 Byte';
            let i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
            return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
        },
        getDimensionsFromFile(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();

                reader.onload = function (event) {
                    if (file.type === "video/mp4") {
                        const video = document.createElement('video');

                        video.onloadedmetadata = function () {
                            const width = this.videoWidth.toLocaleString().replace(/,/g, '.');
                            const height = this.videoHeight.toLocaleString().replace(/,/g, '.');
                            const dimensionsString = `${width} x ${height}`;
                            resolve(dimensionsString);
                        };

                        video.onerror = function () {
                            reject("Erro ao carregar vídeo.");
                        };

                        video.src = event.target.result;
                    } else {
                        const img = new Image();

                        img.onload = function () {
                            const width = this.width.toLocaleString().replace(/,/g, '.');
                            const height = this.height.toLocaleString().replace(/,/g, '.');
                            const dimensionsString = `${width} x ${height}`;
                            resolve(dimensionsString);
                        };

                        img.onerror = function () {
                            reject("Erro ao carregar imagem.");
                        };

                        img.src = event.target.result;
                    }
                };

                reader.onerror = function () {
                    reject("Erro ao ler arquivo.");
                };

                reader.readAsDataURL(file);
            });
        },
        async validateDate() {
            const form_data = new FormData();
            form_data.append("datetime_start", document.getElementById("datetime_start")?.value);
            form_data.append("datetime_end", document.getElementById("datetime_end")?.value);
            form_data.append("id_channel", $('#multiselect').val());
            form_data.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

            const response = await fetch(`${document.location.origin}/publication/tv/broadcast/check/schedule`, {
                method: "POST",
                body: form_data
            });

            $("[name='csrf_token_talkall']").val(Cookies.get("csrf_cookie_talkall"));

            return await response.json();
        },
        checkDateYear() {
            const input_value = this.value;
            const date_object = new Date(input_value);

            if (isNaN(date_object.getTime())) {
                const year = input_value.split("-")[0];
                this.value = input_value.replace(year, year.substring(0, 4));
            }
        },
        limitCaracter() {
            let value = this.value;
            value = value.replace(/\D/g, '');
            value = value.substring(0, 2);

            if (parseInt(value) < 1 || parseInt(value) > 60) {
                if (parseInt(value) < 1) {
                    value = "1";
                } else if (parseInt(value) > 60) {
                    value = "60";
                }
            }

            if (value == "") value = 1;

            this.value = value;
        },
        clearInputFile() {
            const file_elm = document.getElementById("file-elm");
            const file_elm_modal = document.getElementById("file-elm-modal");

            file_elm.value = "";
            file_elm_modal.value = "";
        },
        processLoad() {
            const duration = document.getElementById("totalDuration").innerHTML;

            function convert_to_total_minutes(time_str) {
                var parts = time_str.split(":");
                var minutes = parseInt(parts[0]);
                var seconds = parseInt(parts[1]);
                return minutes + seconds / 60;
            }

            let new_value_progress = "";
            const total_duration = convert_to_total_minutes(duration);
            const time_one = convert_to_total_minutes("1:00");

            if (total_duration > time_one)
                new_value_progress = 0.2;
            else
                new_value_progress = 5;


            let progress_bar = document.getElementById("progress-bar");
            let current_value_progress = parseFloat(progress_bar.style.width) + new_value_progress;
            progress_bar.style.width = current_value_progress + "%";
        },
        setDateTime(date_time_id, time) {
            const input_date_time = document.getElementById(date_time_id);

            if (input_date_time) {
                const now = new Date();
                now.setMinutes(now.getMinutes() + time);

                const pad_zero = number => number < 10 ? '0' + number : number;
                const formatted_date_time = `${now.getFullYear()}-${pad_zero(now.getMonth() + 1)}-${pad_zero(now.getDate())}T${pad_zero(now.getHours())}:${pad_zero(now.getMinutes())}`;

                input_date_time.value = formatted_date_time;
            }
        }
    }
}

document.querySelector("body")?.addEventListener("click", broadcast.helpers.handleBodyClick, true);
document.querySelector(".btns.finish")?.addEventListener("click", broadcast.preview.videoMeker, true);

document.querySelector("#box-preview")?.addEventListener("click", broadcast.preview.play, true);
document.querySelector("#box-edit")?.addEventListener("click", broadcast.preview.edit, true);

document.getElementById("datetime_start")?.addEventListener("change", broadcast.helpers.checkDateYear);
document.getElementById("datetime_end")?.addEventListener("change", broadcast.helpers.checkDateYear);

function startFileProcessing(files) {
    for (let i = 0; i < files.length; i++) (function (t) {
        setTimeout(function () {

            const validation = async (width, height) => {

                const duration = await broadcast.helpers.getVideoDuration(files[i]);

                if (!broadcast.validation.isAcceptedFileType(files[i])) {
                    broadcast.alert.fileNotAllowed();

                } else if (!broadcast.validation.hasReachedLimit()) {
                    broadcast.alert.reachedLimit();

                } else if (!broadcast.validation.hasReachedMaxSize(files[i])) {
                    broadcast.alert.reachedMaxSize();

                } else if (!broadcast.validation.hasAcceptedDimension(width, height)) {
                    broadcast.alert.acceptedDimension();

                } else if (!broadcast.validation.hasAcceptedDucationLimit(files[i], duration)) {
                    broadcast.alert.acceptedDucationLimit();

                } else {
                    broadcast.files.upload(files[i]);
                }
            }

            if (files[i].type === "video/mp4") {

                const video = document.createElement('video');
                video.src = URL.createObjectURL(files[i]);
                video.onloadedmetadata = function () {
                    validation(this.videoWidth, this.videoHeight);
                };

            } else {

                const img = new Image();
                img.src = URL.createObjectURL(files[i]);
                img.onload = function () {
                    validation(this.width, this.height);
                };
            }

        }, t * 500);

    }(i))
}

function handleFiles(files) {
    broadcast.alert.active = false;
    startFileProcessing(files);
}

function dropHandler(e) {
    e.preventDefault();
    broadcast.alert.active = false;

    if (e.dataTransfer.items[0])
        if (e.dataTransfer.items[0].type == "text/plain") return;

    let files = [];

    if (e.dataTransfer.items && e.dataTransfer.items.length) {
        for (let i = 0; i < e.dataTransfer.items.length; i++) {
            if (e.dataTransfer.items[i].kind === 'file') {
                let file = e.dataTransfer.items[i].getAsFile();
                files.push(file);
            }
        }
    } else {
        if (e.dataTransfer.files && e.dataTransfer.files.length) {
            for (let i = 0; i < e.dataTransfer.files.length; i++) {
                let file = e.dataTransfer.files[i];
                files.push(file);
            }
        }
    }
    startFileProcessing(files);
}


function dragOverHandler(e) {
    e.dataTransfer.items.clear();
    e.preventDefault();
}

async function submit() {
    let validate_date = true;

    const input_title = formValidation({ field: document.getElementById("input_title"), text: GLOBAL_LANG.tv_broadcast_column_title, required: true, min: 3, max: 100 });
    const input_display = formValidation({ field: document.getElementById("multiselect"), text: GLOBAL_LANG.tv_broadcast_select_channel, required: true });
    const datetime_start = formValidation({ field: document.getElementById("datetime_start"), text: GLOBAL_LANG.tv_broadcast_date_scheduling, is_date_time_less_than_current: true });
    const datetime_end = formValidation({ field: document.getElementById("datetime_end"), field2: document.getElementById("datetime_start"), text: GLOBAL_LANG.tv_broadcast_date_scheduling, comparing_two_date_time: true });
    const input_file = document.getElementById("preview-broadcast") ? true : false;

    if (datetime_start) {
        validate_date = await broadcast.helpers.validateDate();

        if (!validate_date) {
            document.getElementById("alert__datetime_start").innerHTML = GLOBAL_LANG.tv_broadcast_alert_date;
            document.getElementById("alert__datetime_start").style.display = "block";
        } else {
            document.getElementById("alert__datetime_start").innerHTML = "";
            document.getElementById("alert__datetime_start").style.display = "none";
        }
    }

    if (!input_file)
        document.getElementById("alert__input_file").style.display = "block";
    else
        document.getElementById("alert__input_file").style.display = "none";

    if (input_title && input_display && datetime_start && input_file && datetime_end && validate_date) {
        document.querySelector(".btn-success").disabled = true;
        $("form").unbind('submit').submit();
    }
}

function cancelBroadcast(token) {
    swal({
        title: GLOBAL_LANG.tv_broadcast_alert_delete_title,
        text: GLOBAL_LANG.tv_broadcast_alert_delete_text,
        type: "warning",
        showCancelButton: !0,
        buttonsStyling: !1,
        reverseButtons: true,
        confirmButtonClass: "btn btn-success",
        confirmButtonText: GLOBAL_LANG.tv_broadcast_alert_delete_confirmButtonText,
        cancelButtonClass: "btn btn-danger",
        cancelButtonText: GLOBAL_LANG.tv_broadcast_alert_delete_cancelButtonText,
    }).then(t => {
        if (t.value == true) {
            $.post("broadcast/cancel/" + token, function (data) {
                if (data.success) {
                    t.value && swal({
                        title: GLOBAL_LANG.tv_broadcast_alert_delete_two_title,
                        text: GLOBAL_LANG.tv_broadcast_alert_delete_two_text,
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });
                } else {
                    t.value && swal({
                        title: GLOBAL_LANG.tv_broadcast_error_broadcast_title,
                        text: GLOBAL_LANG.tv_broadcast_error_broadcast_message + ' ' + '(' + data.error.code + ')',
                        type: "error",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });
                }
                $("#datatable-basic").DataTable().ajax.reload(null, false);
            });
        }
    });
}

function pauseBroadcast(token) {
    swal({
        title: GLOBAL_LANG.tv_broadcast_alert_broadcast_title,
        text: GLOBAL_LANG.tv_broadcast_alert_broadcast_pause_text,
        type: "warning",
        showCancelButton: !0,
        buttonsStyling: !1,
        reverseButtons: true,
        confirmButtonClass: "btn btn-success",
        confirmButtonText: GLOBAL_LANG.tv_broadcast_alert_broadcast_confirmButtonText,
        cancelButtonClass: "btn btn-danger",
        cancelButtonText: GLOBAL_LANG.tv_broadcast_alert_broadcast_cancelButtonText,
    }).then(t => {
        if (t.value == true) {
            $.post("broadcast/pause/" + token, function (data) {
                if (data.success) {
                    t.value && swal({
                        title: GLOBAL_LANG.tv_broadcast_alert_broadcast_pause_two_title,
                        text: GLOBAL_LANG.tv_broadcast_alert_broadcast_pause_two_text,
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });
                } else {
                    t.value && swal({
                        title: GLOBAL_LANG.tv_broadcast_error_broadcast_title,
                        text: GLOBAL_LANG.tv_broadcast_error_broadcast_message + ' ' + '(' + data.error.code + ')',
                        type: "error",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });
                }
                $("#datatable-basic").DataTable().ajax.reload(null, false);
            });
        }
    });
}

function resumeBroadcast(token) {
    swal({
        title: GLOBAL_LANG.tv_broadcast_alert_broadcast_title,
        text: GLOBAL_LANG.tv_broadcast_alert_broadcast_resume_text,
        type: "warning",
        showCancelButton: !0,
        buttonsStyling: !1,
        reverseButtons: true,
        confirmButtonClass: "btn btn-success",
        confirmButtonText: GLOBAL_LANG.tv_broadcast_alert_broadcast_confirmButtonText,
        cancelButtonClass: "btn btn-danger",
        cancelButtonText: GLOBAL_LANG.tv_broadcast_alert_broadcast_cancelButtonText,
    }).then(t => {
        if (t.value == true) {
            $.post("broadcast/resume/" + token, function (data) {
                if (data.success) {
                    t.value && swal({
                        title: GLOBAL_LANG.tv_broadcast_alert_broadcast_resume_two_title,
                        text: GLOBAL_LANG.tv_broadcast_alert_broadcast_resume_two_text,
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });
                } else {
                    t.value && swal({
                        title: GLOBAL_LANG.tv_broadcast_error_broadcast_title,
                        text: GLOBAL_LANG.tv_broadcast_error_broadcast_message + ' ' + '(' + data.error.code + ')',
                        type: "error",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });
                }
                $("#datatable-basic").DataTable().ajax.reload(null, false);
            });
        }
    });
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
            dt_start.placeholder = GLOBAL_LANG.tv_broadcast_filter_period_placeholder_date_start;

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
        case 1:
            column = "title";
            break;
        case 2:
            column = "schedule";
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
        &type=publicationBroadcastTv`, function (response) {
        Swal.fire({
            title: GLOBAL_LANG.tv_broadcast_alert_export_title,
            text: GLOBAL_LANG.tv_broadcast_alert_export_text,
            type: 'success',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: GLOBAL_LANG.tv_broadcast_alert_export_confirmButtonText
        });
    });
}
