const Filters = JSON.parse(localStorage.getItem("filters"));

function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.bot.search) {
            document.getElementById("search").value = Filters.bot.search;
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
            url: "../bot/trainer/find",
            type: "POST",
            data: {
                text: $("#search").val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'option'
            },
            {
                mData: 'description'
            },
        ],
        columnDefs: [
            {
                targets: 2,
                render: function (data, type, full, meta) {

                    return `<div class="dropleft">
                                    <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                        <i class="fa fa-ellipsis-v"></i>
                                    <div class="ripple-container"></div>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="multiDropdownMenu">
                                        <a id="` + full.id_chatbot + `" href='#' class="dropdown-item table-btn-access" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block">
                                               <i class="far fa-hand-pointer"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.setting_bot_trainer_dt_columndefs_target2_title_access}</span>
                                        </a>
                                        <a id="` + full.id_chatbot + `" href='#' class="dropdown-item table-btn-edit" style="cursor: pointer">                                           
                                            <div style="width: 24px; display: inline-block">
                                               <i class="far fa-edit"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.setting_bot_trainer_dt_columndefs_target2_title_edit}</span>
                                        </a>
                                        <a id="` + full.id_chatbot + `" href='#' class="dropdown-item table-btn-delete" style="cursor: pointer">                                  
                                            <div style="width: 24px; display: inline-block">
                                               <i class="far fa-trash-alt"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.setting_bot_trainer_dt_columndefs_target2_title_delete}</span>
                                        </a>
                                    </div>
                                </div>`
                }
            },
            {
                targets: 1,
                render: function (data, type, row) {
                    return data.substr(0, 170);
                }
            }
        ],
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

            const bot = {
                search: $("#search").val(),
            }

            let filter = localStorage.getItem("filters");

            filter = filter ? JSON.parse(filter) : {};
            filter.bot = bot;

            localStorage.setItem("filters", JSON.stringify(filter));
        }

    });
}

$(document).ready(function () {

    $("#search").on("keyup", function (event) {
        if (event.which == 13) {
            find();
        }
    });

    if ($("#search").val() !== undefined) {
        find();
    }

    $("#datatable-basic").on("click", ".table-btn-access", function () {
        let nivel_access = localStorage.getItem('nivel');
        if (nivel_access !== null) {
            localStorage.setItem('nivel', this.id);
        }
        if (nivel_access === null) {
            let nivel_def = 1;
            if (nivel_def != parseInt(this.id)) {
                nivel_def = this.id;
            }
            localStorage.setItem('nivel', nivel_def);
        }
        window.location.href = "../bot/trainer/" + this.id;
    });

    $("#datatable-basic").on("click", ".table-btn-edit", function () {
        let nivel_access = localStorage.getItem('nivel');
        if (nivel_access !== null) {
            localStorage.setItem('nivel', 0);
        }
        window.location.href = "../bot/trainer/edit/" + this.id;
    });

    $("#datatable-basic").on("click", ".table-btn-delete", function () {
        swal({
            title: GLOBAL_LANG.setting_bot_trainer_alert_delete_title,
            text: GLOBAL_LANG.setting_bot_trainer_alert_detete_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.setting_bot_trainer_alert_delete_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.setting_bot_trainer_alert_delete_cancelButtonText,
        }).then(t => {
            if (t.value == true) {
                $.post("../bot/trainer/delete/" + this.id, function (data) {
                    t.value && swal({
                        title: GLOBAL_LANG.setting_bot_trainer_alert_delete_two_title,
                        text: GLOBAL_LANG.setting_bot_trainer_alert_delete_two_text,
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });
                    $("#datatable-basic").DataTable().ajax.reload(null, false);
                });
            }
        });
    });

    $('#sendEmailExport').on('click', () => modalExport());
    $("form").submit(event => event.preventDefault());
    if (document.querySelector(".btn-success")) {
        document.querySelector(".btn-success").addEventListener("click", submit);
    }

    const current_path = window.location.pathname;

    if (current_path.includes("/add") || current_path.includes("/edit")) {

        if (current_path.includes("/edit")) {
            const data = JSON.parse(document.getElementById('edit').value);

            if (data.media_type) {
                switch (data.media_type) {
                    case "1":
                        showTextInput(data);
                        break;
                    case "2":
                    case "3":
                    case "4":
                    case "5":
                        showMediaInput(data);
                        break;
                    case "9":
                        showContactInput(data);
                        break;
                }
            }
        }

        const text_option = document.getElementById("text_option");
        const media_option = document.getElementById("media_option");
        const contact_option = document.getElementById("contact_option");

        text_option.addEventListener("click", () => showTextInput());
        media_option.addEventListener("click", () => showMediaInput());
        contact_option.addEventListener("click", () => showContactInput());

        const choose_text = document.querySelectorAll(".choose-text");
        const choose_media = document.querySelectorAll(".choose-media");
        const choose_contact = document.querySelectorAll(".choose-contact");

        choose_text.forEach(chosen => {
            chosen.addEventListener("click", () => changeTypeWarning("text"));
        });

        choose_media.forEach(chosen => {
            chosen.addEventListener("click", () => changeTypeWarning("media"));
        });

        choose_contact.forEach(chosen => {
            chosen.addEventListener("click", () => changeTypeWarning("contact"));
        });

        let fileSelect = document.getElementById("fileSelect"),
            fileElem = document.getElementById("fileElem");

        fileSelect.addEventListener("click", function (e) {
            if (fileElem) fileElem.click();
            e.preventDefault();
        }, false);

        const toggle = document.getElementById("sector_toggle");
        const sector_select = document.getElementById("sector_select");
        const selected_sector = document.querySelector("select[name='selected_sector']");

        toggle.addEventListener("change", function () {
            if (this.checked) {
                sector_select.style.display = "block";
                selected_sector.removeAttribute("disabled");
            } else {
                sector_select.style.display = "none";
                selected_sector.setAttribute("disabled", "disabled");
            }
        });

        if (!toggle.checked) {
            selected_sector.setAttribute("disabled", "disabled");
        }
    }

});

function submit() {

    const input_option = formValidation({ field: document.getElementById("input_option_bot"), text: GLOBAL_LANG.setting_bot_trainer_option, required: true, min: 1, max: 2 });
    const input_content = formValidation({ field: document.getElementById("input_content"), text: GLOBAL_LANG.setting_bot_trainer_text, required: true, min: 3, max: 100 });
    const is_menu = document.getElementById("id_submenu") === null;

    const selected_option = document.getElementById("chatbot_type").value;

    let form_valid = true;

    switch (selected_option) {
        case "text":
            form_valid = formValidation({ field: document.getElementById("input_text"), text: GLOBAL_LANG.setting_bot_trainer_column_description, required: document.getElementById("id_submenu") === null, min: 3, max: 3000 });
            break;
        case "media":
            const has_media = document.getElementById("preview_container").innerHTML !== '';
            if (is_menu && !has_media) {
                document.getElementById("alert_upload_media").classList.remove("hidden");
                form_valid = false;
            } else {
                document.getElementById("alert_upload_media").classList.add("hidden");
            }
            break;
        case "contact":
            const name_field = document.getElementById("input_name_bot");
            const phone_field = document.getElementById("input_phone_bot");
            const text_field = document.getElementById("input_text_contact");

            const is_required = is_menu || name_field.value !== '' || phone_field.value !== '';

            const contact_name_valid = formValidation({
                field: name_field,
                text: GLOBAL_LANG.setting_bot_trainer_contact_name,
                required: is_required,
                min: 3,
                max: 100
            });

            const contact_number_valid = formValidation({
                field: phone_field,
                text: GLOBAL_LANG.setting_bot_trainer_contact_phone_number,
                required: is_required,
                min: 6,
                max: 15
            });

            formValidation({
                field: text_field,
                text: GLOBAL_LANG.setting_bot_trainer_column_description,
                required: false,
                min: 3,
                max: 3000
            });

            form_valid = contact_name_valid && contact_number_valid;
            break;
    }

    const chatbot_option_alert = document.getElementById("alert_input_chatbot_option");
    if (is_menu && !selected_option) {
        chatbot_option_alert.classList.remove("hidden");
        form_valid = false;
    } else {
        chatbot_option_alert.classList.add("hidden");
    }

    if (input_option && input_content && form_valid) {
        document.querySelector(".btn-success").disabled = true;
        $("form").unbind('submit').submit();
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
            column = "option";
            break;

        case 1:
            column = "description";
            break;
    }

    $.get(`/export/xlsx?
        search=${$('#search').val()}
        &column=${column}
        &order=${order}
        &type=bot`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.setting_bot_trainer_alert_export_title,
                text: GLOBAL_LANG.setting_bot_trainer_alert_export_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.setting_bot_trainer_alert_export_confirmButtonText,
            });
        }
    });
}

function validNumber(e) {
    if (e.which != 8 && e.which != 0 && e.which < 48 || e.which > 57) {
        e.preventDefault();
    }
}

if (document.querySelector("#input_option") != null) {
    document.querySelector("#input_option").addEventListener("keypress", validNumber);
}

function bytesToSize(bytes) {

    let sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    if (bytes == 0) return '0 Byte';
    let i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
    return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
}

function callAlert(verify) {
    switch (verify) {
        case "extensions":
            swal({
                title: GLOBAL_LANG.setting_bot_trainer_alert_warning_title,
                text: GLOBAL_LANG.setting_bot_trainer_alert_accepted_files,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.setting_bot_trainer_alert_confirm_button,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "limit":
            swal({
                title: GLOBAL_LANG.setting_bot_trainer_alert_warning_title,
                text: GLOBAL_LANG.setting_bot_trainer_alert_limit_files,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.setting_bot_trainer_alert_confirm_button,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        case "maxsize":
            swal({
                title: GLOBAL_LANG.setting_bot_trainer_alert_warning_title,
                text: GLOBAL_LANG.setting_bot_trainer_alert_maxsize_files,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.setting_bot_trainer_alert_confirm_button,
                cancelButtonClass: "btn btn-secondary"
            });
            break;
        default:
            break;
    }
}

function changeTypeWarning(type) {
    swal({
        title: GLOBAL_LANG.setting_bot_trainer_alert_change_title,
        text: GLOBAL_LANG.setting_bot_trainer_alert_change_text,
        type: "warning",
        showCancelButton: !0,
        buttonsStyling: !1,
        reverseButtons: true,
        confirmButtonClass: "btn btn-success",
        confirmButtonText: GLOBAL_LANG.setting_bot_trainer_alert_delete_confirmButtonText,
        cancelButtonClass: "btn btn-danger",
        cancelButtonText: GLOBAL_LANG.setting_bot_trainer_alert_delete_cancelButtonText,
    }).then(t => {
        if (t.value == true) {
            switch (type) {
                case "text":
                    showTextInput();
                    break;
                case "media":
                    showMediaInput();
                    break;
                case "contact":
                    showContactInput();
                    break;
            }
        }
    });
}

function clearTextInput() {

    const text = document.getElementById("text_data");
    text.innerHTML = "";
    text.classList.add("hidden");
}

function clearMediaInput() {

    document.getElementById('fileElem').value = "";
    document.getElementById("drop_chatbot").innerHTML = "";
    document.getElementById("input_files").classList.add("hidden");
    document.getElementById("drop_chatbot").classList.add("hidden");
}

function clearContactInput() {

    document.getElementById("contact_chatbot").innerHTML = "";
}

function countCharacter(textarea, counter, max_chars) {

    textarea.addEventListener("input", function () {
        let text_body = textarea.value;
        let count_enters = (text_body.match(/\n/g) || []).length;
        let total_chars = text_body.length + count_enters;

        if (total_chars >= max_chars) {
            textarea.value = text_body.slice(0, max_chars - count_enters);
            total_chars = max_chars;
        }

        counter.textContent = Math.max(0, max_chars - total_chars);
    });

    textarea.addEventListener("keydown", function (event) {
        if (event.key === "Enter" && counter.textContent < 2) {
            event.preventDefault();
        }
    });
}

function createMediaOption(data, edit = false) {

    let media_type = null;

    if (edit) {
        media_type = data.media_type;
    } else {
        if (typeof data.mimetype === "string") {
            if (data.mimetype.startsWith("audio/")) {
                media_type = "2";
            } else if (data.mimetype.startsWith("image/")) {
                media_type = "3";
            } else if (data.mimetype.startsWith("application/")) {
                media_type = "4";
            } else if (data.mimetype.startsWith("video/")) {
                media_type = "5";
            }
        }
    }

    const preview_container = document.getElementById("preview_container");
    preview_container.innerHTML = "";

    const media_container = document.createElement("div");
    media_container.classList.add("col-6", "media-container");

    const media_box = document.createElement("div");
    media_box.classList.add("media-box");

    const remove_media = document.createElement("div");
    remove_media.classList.add("remove-media");

    const svgNS = "http://www.w3.org/2000/svg";
    const svg = document.createElementNS(svgNS, "svg");
    svg.setAttribute("width", "15");
    svg.setAttribute("height", "15");
    svg.setAttribute("viewBox", "0 0 22 22");
    svg.setAttribute("fill", "none");
    svg.setAttribute("xmlns", svgNS);

    const path = document.createElementNS(svgNS, "path");
    path.setAttribute("d", "M21.7316 0.268754C21.5597 0.0969053 21.3266 0.000366211 21.0835 0.000366211C20.8404 0.000366211 20.6073 0.0969053 20.4354 0.268754L11.0002 9.704L1.56492 0.268754C1.39302 0.0969053 1.1599 0.000366211 0.916837 0.000366211C0.67377 0.000366211 0.440654 0.0969053 0.268754 0.268754C0.0969053 0.440654 0.000366211 0.67377 0.000366211 0.916837C0.000366211 1.1599 0.0969053 1.39302 0.268754 1.56492L9.704 11.0002L0.268754 20.4354C0.0969053 20.6073 0.000366211 20.8404 0.000366211 21.0835C0.000366211 21.3266 0.0969053 21.5597 0.268754 21.7316C0.440654 21.9034 0.67377 22 0.916837 22C1.1599 22 1.39302 21.9034 1.56492 21.7316L11.0002 12.2963L20.4354 21.7316C20.6073 21.9034 20.8404 22 21.0835 22C21.3266 22 21.5597 21.9034 21.7316 21.7316C21.9034 21.5597 22 21.3266 22 21.0835C22 20.8404 21.9034 20.6073 21.7316 20.4354L12.2963 11.0002L21.7316 1.56492C21.9034 1.39302 22 1.1599 22 0.916837C22 0.67377 21.9034 0.440654 21.7316 0.268754Z");
    path.setAttribute("fill", "#ffffff");

    svg.appendChild(path);
    remove_media.appendChild(svg);

    const media_bytes_container = document.createElement("div");
    media_bytes_container.classList.add("tail");

    const media_bytes = document.createElement("span");
    media_bytes.classList.add("bytes");
    if (edit) {
        fetch(data.media_url, { method: 'HEAD' })
            .then(response => {
                const size = response.headers.get('content-length');
                media_bytes.innerHTML = size ? bytesToSize(parseInt(size)) : "???";
            })
            .catch(error => {
                console.error("Erro ao obter tamanho do arquivo: ", error);
                media_bytes.innerHTML = "???";
            });
    } else {
        media_bytes.innerHTML = bytesToSize(parseInt(data.size));
    }
    media_bytes_container.appendChild(media_bytes);

    const textarea = document.createElement("textarea");
    textarea.classList.add("textarea", "text-area");
    textarea.id = "media_description";
    textarea.name = "media_description";
    textarea.placeholder = GLOBAL_LANG.setting_bot_trainer_media_description;
    textarea.innerHTML = edit ? data.media_caption : "";

    const label = document.createElement("label");
    label.classList.add("form-control-label", "label-character");
    label.innerHTML = GLOBAL_LANG.setting_bot_trainer_media_chars_remaining;

    const count_character = document.createElement("span");
    count_character.classList.add("count-character");
    count_character.id = "count_character";

    const max_chars = media_type == 4 ? "700" : "1024";
    textarea.maxLength = max_chars;
    count_character.innerHTML = max_chars;

    countCharacter(textarea, count_character, max_chars);

    const input_hidden = document.createElement("input");
    input_hidden.type = "hidden";
    input_hidden.name = "file_hidden";
    input_hidden.value = edit ? data.media_url : data.url;

    const media_type_hidden = document.createElement("input");
    media_type_hidden.type = "hidden";
    media_type_hidden.name = "media_type";
    media_type_hidden.value = media_type;

    label.appendChild(count_character);
    media_container.appendChild(input_hidden);
    media_container.appendChild(media_type_hidden);
    media_container.appendChild(media_box);

    remove_media.addEventListener('click', function () {
        preview_container.innerHTML = '';
        document.getElementById("fileElem").value = "";
        document.querySelector('.drop-inner-img').style.display = 'block';
        document.querySelector('.drop-inner-title').style.display = 'block';
        document.querySelector('.drop-inner-text').style.display = 'block';
    });

    switch (media_type) {
        case "2":
            const audio = document.createElement('audio');
            audio.src = edit ? data.media_url : data.url;
            audio.controls = true;
            audio.style.width = "318px";
            media_box.appendChild(audio);
            media_box.appendChild(remove_media);
            media_box.appendChild(media_bytes_container);

            preview_container.appendChild(media_container);
            return;
        case "3":
            const img = document.createElement('img');
            img.src = edit ? data.media_url : data.url;
            img.style.width = '318px';
            img.style.height = '200px';
            media_box.appendChild(img);
            break;
        case "4":
            const pdf_link = document.createElement('a');
            const pdf_url = edit ? data.media_url : data.url;
            pdf_link.href = pdf_url;
            pdf_link.setAttribute("target", "_blank");

            const pdf_js_lib = window['pdfjsLib'] || window['pdfjs-dist/build/pdf'];

            if (!pdf_js_lib) {
                console.error("Biblioteca PDF.js não carregada.");
                return;
            }

            pdf_js_lib.GlobalWorkerOptions.workerSrc = "https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js";

            const loading_task = pdf_js_lib.getDocument(pdf_url);
            loading_task.promise.then(function (pdf) {
                pdf.getPage(1).then(function (page) {

                    const desired_width = 316;
                    const viewport = page.getViewport({ scale: 1, });
                    const scale = desired_width / viewport.width;
                    const scaled_viewport = page.getViewport({ scale: scale, });

                    const canvas = document.createElement("canvas");
                    canvas.id = "pdf-canva";

                    const context = canvas.getContext('2d');
                    canvas.width = scaled_viewport.width;
                    canvas.height = scaled_viewport.height;
                    canvas.style = "border: solid 1px #dee2e6; border-radius: 5px;";

                    const render_context = {
                        canvasContext: context,
                        viewport: scaled_viewport
                    };

                    const render_task = page.render(render_context);
                    render_task.promise.then(function () {
                        pdf_link.appendChild(canvas);

                        media_box.appendChild(pdf_link);
                        media_box.appendChild(remove_media);
                        media_box.appendChild(media_bytes_container);
                        media_container.appendChild(textarea);
                        media_container.appendChild(label);

                        preview_container.appendChild(media_container);
                    });
                });
            }, function (reason) {
                console.error("Erro ao carregar o PDF: ", reason);
            });
            return;
        case "5":
            const video = document.createElement('video');
            video.src = edit ? data.media_url : data.url;
            video.controls = true;
            video.style.width = '318px';
            video.style.height = '200px';
            media_box.appendChild(video);
            break;
    }

    media_box.appendChild(remove_media);
    media_box.appendChild(media_bytes_container);
    media_container.appendChild(textarea);
    media_container.appendChild(label);

    preview_container.appendChild(media_container);
}

function dragOverHandler(e) {
    e.preventDefault();
}

function dropHandler(e) {
    e.preventDefault();

    let files;

    if (e.dataTransfer.items) {
        if (e.dataTransfer.items.length > 1) {
            callAlert("limit");
            return;
        }
        files = e.dataTransfer.items[0].getAsFile();

    } else {
        if (e.dataTransfer.files.length > 1) {
            callAlert("limit");
            return;
        }
        files = e.dataTransfer.files[0];
    }

    handleFiles([files]);
}

function handleFiles(files) {
    document.getElementById("file-name").value = files[0].name;

    if (files.length > 1) {
        callAlert("limit");
        return;
    }

    const file = files[0];
    const allowed_types = ['image/jpeg', 'image/jpg', 'video/mp4', 'application/pdf']

    if (file) {
        const file_type = file.type;
        const file_size = file.size;
        const max_size = 5 * 1024 * 1024;

        if (!allowed_types.includes(file_type)) {
            callAlert("extensions");
            return;
        }

        if (file_size > max_size) {
            callAlert("maxsize");
            return;
        }

        let formData = new FormData();
        formData.append("filetoupload", file);

        const preview_container = document.getElementById("preview_container");
        preview_container.innerHTML = "";

        const loader = document.createElement("img");
        loader.src = document.location.origin + "/assets/img/loads/loading_2.gif";
        loader.className = "load";
        loader.style.width = "80px";
        loader.style.display = "block";
        loader.style.margin = "10px auto";

        preview_container.appendChild(loader);

        fetch("https://files.talkall.com.br:3000", {
            method: "POST",
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                preview_container.innerHTML = "";
                createMediaOption(data);
            })
            .catch(error => {
                console.error("Erro:", error);
                preview_container.innerHTML = "";
            });

        document.querySelector('.drop-inner-img').style.display = 'none';
        document.querySelector('.drop-inner-title').style.display = 'none';
        document.querySelector('.drop-inner-text').style.display = 'none';
    }
}

function showTextInput(data = null) {
    clearMediaInput();
    clearContactInput();

    document.getElementById("chatbot_type").value = "text";

    const media_type_hidden = document.createElement("input");
    media_type_hidden.name = "media_type";
    media_type_hidden.type = "hidden";
    media_type_hidden.value = 1;

    const text_data = document.getElementById("text_data");
    text_data.innerHTML = "";

    const container = document.createElement("div");
    container.classList.add("form-group");

    const label_textarea = document.createElement("label");
    label_textarea.classList.add("form-control-label");
    label_textarea.setAttribute("for", "input_text");
    label_textarea.textContent = GLOBAL_LANG.setting_bot_trainer_text_description;

    const max_chars = "3000";

    const count_character = document.createElement("span");
    count_character.id = "count_character";
    count_character.style.color = "red";
    count_character.textContent = max_chars;

    label_textarea.appendChild(count_character);

    const textarea = document.createElement("textarea");
    textarea.classList.add("form-control");
    textarea.id = "input_text";
    textarea.name = "input_text";
    textarea.rows = 10;
    textarea.setAttribute("resize", "none");
    textarea.maxLength = max_chars;

    if (data && data.text) {
        textarea.value = data.text;
    }

    const alert_input_text = document.createElement("div");
    alert_input_text.classList.add("alert-field-validation", "hidden");
    alert_input_text.id = "alert__input_text";

    countCharacter(textarea, count_character, max_chars);

    container.appendChild(media_type_hidden);
    container.appendChild(label_textarea);
    container.appendChild(textarea);
    container.appendChild(alert_input_text);

    text_data.prepend(container);
    text_data.classList.remove("hidden");

    text_option.style.display = "none";
    media_option.style.display = "none";
    contact_option.style.display = "none";

    label_choose_type.style.display = "none";

    menu_type.style.display = "flex";
    choose_text.style.display = "none";
    choose_media.style.display = "block";
    choose_contact.style.display = "block";
    choose_media.style.marginBottom = "-25px"
    choose_contact.style.marginBottom = "-25px"

}

function showMediaInput(data = null) {
    clearTextInput();
    clearContactInput();

    document.getElementById("chatbot_type").value = "media";

    const drop_chatbot = document.getElementById("drop_chatbot");
    drop_chatbot.innerHTML = "";

    const drop_media = document.createElement("div");
    drop_media.classList.add("drop-media");
    drop_media.setAttribute("multiple", "");
    drop_media.setAttribute("ondrop", "dropHandler(event);");
    drop_media.setAttribute("ondragover", "dragOverHandler(event);");

    const preview_container = document.createElement("div");
    preview_container.id = "preview_container";
    preview_container.classList.add("preview-container");

    const drop_inner_img = document.createElement("div");
    drop_inner_img.classList.add("drop-inner-img");

    const drop_icon = document.createElement("img");
    drop_icon.classList.add("drop-icon");
    drop_icon.src = document.location.origin + "/assets/img/panel/image.png";

    const drop_inner_title = document.createElement("div");
    drop_inner_title.classList.add("drop-inner-title");

    const title_bold = document.createElement("b");
    title_bold.textContent = GLOBAL_LANG.setting_bot_trainer_add_title_drop;

    const drop_inner_text = document.createElement("div");
    drop_inner_text.classList.add("drop-inner-text");

    const subtitle_span = document.createElement("span");
    subtitle_span.textContent = GLOBAL_LANG.setting_bot_trainer_add_subtitle_drop;

    const alert_upload_media = document.createElement("div");
    alert_upload_media.classList.add("alert-field-validation", "hidden");
    alert_upload_media.id = "alert_upload_media";
    alert_upload_media.textContent = GLOBAL_LANG.setting_bot_trainer_required_field;

    drop_inner_img.appendChild(drop_icon);
    drop_inner_title.appendChild(title_bold);
    drop_inner_text.appendChild(subtitle_span);

    drop_media.appendChild(preview_container);
    drop_media.appendChild(drop_inner_img);
    drop_media.appendChild(drop_inner_title);
    drop_media.appendChild(drop_inner_text);

    drop_chatbot.appendChild(drop_media);
    drop_chatbot.appendChild(alert_upload_media);
    drop_chatbot.classList.remove("hidden");

    const input_files = document.getElementById("input_files");
    input_files.classList.remove("hidden");

    text_option.style.display = "none";
    media_option.style.display = "none";
    contact_option.style.display = "none";

    label_choose_type.style.display = "none";

    menu_type.style.display = "flex";
    choose_text.style.display = "block";
    choose_media.style.display = "none";
    choose_contact.style.display = "block";
    choose_text.style.marginBottom = "5px"
    choose_contact.style.marginBottom = "5px"

    if (data) {
        document.querySelector('.drop-inner-img').style.display = 'none';
        document.querySelector('.drop-inner-title').style.display = 'none';
        document.querySelector('.drop-inner-text').style.display = 'none';
        createMediaOption(data, true);
    }
}

function showContactInput(data = null) {
    clearTextInput();
    clearMediaInput();

    document.getElementById("chatbot_type").value = "contact";

    const media_type_hidden = document.createElement("input");
    media_type_hidden.name = "media_type";
    media_type_hidden.type = "hidden";
    media_type_hidden.value = 9;

    const contact_chatbot = document.getElementById("contact_chatbot");
    contact_chatbot.innerHTML = "";

    const contact_name = document.createElement("div");
    contact_name.classList.add("col-lg-8");
    contact_name.id = "contact_name";

    const form_group_name = document.createElement("div");
    form_group_name.classList.add("form-group");

    const label_name = document.createElement("label");
    label_name.classList.add("form-control-label");
    label_name.setAttribute("for", "input_name");
    label_name.textContent = GLOBAL_LANG.setting_bot_trainer_contact_name;

    const input_name_bot = document.createElement("input");
    input_name_bot.type = "text";
    input_name_bot.classList.add("form-control");
    input_name_bot.name = "input_name";
    input_name_bot.id = "input_name_bot";
    input_name_bot.minLength = "3";
    input_name_bot.maxLength = "100";
    input_name_bot.placeholder = GLOBAL_LANG.setting_bot_trainer_contact_name_placeholder;
    input_name_bot.value = data && data.vcard ? JSON.parse(data.vcard).display_name : "";

    const alert_input_name_bot = document.createElement("div");
    alert_input_name_bot.classList.add("alert-field-validation", "hidden");
    alert_input_name_bot.id = "alert__input_name_bot";
    alert_input_name_bot.style.display = "none";

    form_group_name.appendChild(label_name);
    form_group_name.appendChild(input_name_bot);
    form_group_name.appendChild(alert_input_name_bot);
    contact_name.appendChild(form_group_name);

    const contact_number = document.createElement("div");
    contact_number.classList.add("col-lg-4");
    contact_number.id = "contact_number";

    const form_group_number = document.createElement("div");
    form_group_number.classList.add("form-group");

    const label_phone = document.createElement("label");
    label_phone.classList.add("form-control-label");
    label_phone.setAttribute("for", "input_phone");
    label_phone.textContent = GLOBAL_LANG.setting_bot_trainer_contact_phone_number;

    const input_phone_bot = document.createElement("input");
    input_phone_bot.type = "tel";
    input_phone_bot.setAttribute("pattern", "\\d*");
    input_phone_bot.classList.add("form-control");
    input_phone_bot.name = "input_phone";
    input_phone_bot.id = "input_phone_bot";
    input_phone_bot.minLength = "6";
    input_phone_bot.maxLength = "15";
    input_phone_bot.placeholder = GLOBAL_LANG.setting_bot_trainer_contact_phone_number_placeholder;
    input_phone_bot.value = data && data.vcard ? JSON.parse(data.vcard).number : "";

    input_phone_bot.addEventListener("keypress", function (event) {
        const charCode = event.which ? event.which : event.keyCode;
        if (charCode < 48 || charCode > 57) {
            event.preventDefault();
        }
    });

    input_phone_bot.addEventListener("paste", function (event) {
        const clipboardData = event.clipboardData || window.Clipboard;
        const pastedData = clipboardData.getData("Text");
        if (isNaN(pastedData)) {
            event.preventDefault();
        }
    });

    const alert_input_phone_bot = document.createElement("div");
    alert_input_phone_bot.classList.add("alert-field-validation", "hidden");
    alert_input_phone_bot.id = "alert__input_phone_bot";

    form_group_number.appendChild(label_phone);
    form_group_number.appendChild(input_phone_bot);
    form_group_number.appendChild(alert_input_phone_bot);
    contact_number.appendChild(form_group_number);

    const form_group_text = document.createElement("div");
    form_group_text.classList.add("col-lg-12");
    form_group_text.classList.add("form-group");

    const label_textarea = document.createElement("label");
    label_textarea.classList.add("form-control-label");
    label_textarea.setAttribute("for", "input_text_contact");
    label_textarea.textContent = GLOBAL_LANG.setting_bot_trainer_text_description;

    const max_chars = "3000";

    const count_character = document.createElement("span");
    count_character.id = "count_character";
    count_character.style.color = "red";
    count_character.textContent = max_chars;

    label_textarea.appendChild(count_character);

    const textarea = document.createElement("textarea");
    textarea.classList.add("form-control");
    textarea.id = "input_text_contact";
    textarea.name = "input_text_contact";
    textarea.rows = 10;
    textarea.setAttribute("resize", "none");
    textarea.maxLength = max_chars;

    if (data && data.text) {
        textarea.value = data.text;
    }

    const alert_input_text = document.createElement("div");
    alert_input_text.classList.add("alert-field-validation", "hidden");
    alert_input_text.id = "alert__input_text";

    countCharacter(textarea, count_character, max_chars);

    form_group_text.appendChild(label_textarea);
    form_group_text.appendChild(textarea);
    form_group_text.appendChild(alert_input_text);
    form_group_text.classList.add("form-group", "center-form-group"); // Adicionando a classe para centralizar

    contact_chatbot.appendChild(media_type_hidden);
    contact_chatbot.appendChild(contact_name);
    contact_chatbot.appendChild(contact_number);
    contact_chatbot.appendChild(form_group_text);

    text_option.style.display = "none";
    media_option.style.display = "none";
    contact_option.style.display = "none";
    label_choose_type.style.display = "none";

    menu_type.style.display = "flex";
    choose_text.style.display = "block";
    choose_media.style.display = "block";
    choose_contact.style.display = "none";
    choose_text.style.marginBottom = "-25px";
    choose_media.style.marginBottom = "-25px";
}