const Filters = JSON.parse(localStorage.getItem("filters")) || null;

const Table = {
    hasFilters: false,

    row: {
        click(e) {
            const row = document.getElementById(e.target.id);
            const channelCell = row.parentNode.parentNode.parentNode.querySelector('td:nth-child(3)');
            const channel_name = channelCell.textContent.trim();

            const checkbox = e.target;
            const channel_id = checkbox.getAttribute("channel_id");


            if (row.checked) {
                Contact.push(row.id, channel_name, channel_id);
                Table.row.selected(row);

                Table.dropdown.show();
                Table.btn.show();

            } else {
                Contact.remove(row.id);
                Table.row.deselected(row);

                Table.dropdown.hide();
                Table.btn.hide();
            }

            Contact.unique_id = [];
            Table.dropdown.checkIfAllSelected();
            Table.row.information();
            Table.row.areIsSelected();
        },
        selected(row) {
            row.checked = true;
            row.parentNode.parentNode.parentNode.style.background = "#cfd2dbba";
            row.parentNode.parentNode.parentNode.children[4].querySelector('span i').style.background = "#cfd2dbba";
            row.parentNode.parentNode.parentNode.children[4].querySelector('span').style.background = "#cfd2dbba";
        },
        deselected(row) {
            row.checked = false;
            row.parentNode.parentNode.parentNode.style.background = "#fff";
            row.parentNode.parentNode.parentNode.children[4].querySelector('span i').style.background = "#fff";
            row.parentNode.parentNode.parentNode.children[4].querySelector('span').style.background = "#fff";
        },
        information() {
            if (Contact.list.length > 0)
                document.getElementById("infoContact").style.display = "flex";
            else
                document.getElementById("infoContact").style.display = "none";

            Table.row.count();
        },
        count() {
            const number = Contact.list.length;
            document.getElementById("infoContact").firstElementChild.innerHTML = GLOBAL_LANG.contact_info_all.replace("{{number}}", number.toLocaleString("pt-BR"));
        },
        areIsSelected() {
            if (Contact.count_all === Contact.list.length) {
                Table.dropdown.checkbox(true);
            }
        }
    },

    btn: {
        selectedAction: null,

        async action(action) {
            Contact.attendance = 0;
            Table.btn.selectedAction = action;

            Table.helpers.showLoadProcess();

            if (action !== "unblock" && action !== "persona")
                await Table.helpers.actionWithContact("attendance", 2000);

            if (Contact.attendance === 0) {
                const response = await Table.helpers.actionWithContact(action, 500);

                if (response === true) {
                    setTimeout(() => {
                        Table.helpers.removeModal();
                        $("#datatable-basic").DataTable().ajax.reload(null, false);
                        if (action === "persona") Contact.persona.alert.show("created");
                    }, 1000);
                }

            } else {
                setTimeout(() => {
                    Table.helpers.removeModal();
                    swal({
                        title: GLOBAL_LANG.contact_alert_attendance_title,
                        text: `${GLOBAL_LANG.contact_alert_attendance_text.replace("{{number}}", Contact.attendance.toLocaleString('pt-BR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }))}`,
                        type: "warning",
                        buttonsStyling: !1,
                        reverseButtons: true,
                        confirmButtonClass: "btn btn-success",
                        confirmButtonText: GLOBAL_LANG.contact_alert_attendance_confirmButtonText,
                    });
                }, 1000);
            }
        },
        delete() {
            const contacts = Contact.unique_id.length === 1 ? 1 : Contact.list.length;
            swal({
                title: GLOBAL_LANG.contact_alert_delete_title,
                text: `${GLOBAL_LANG.contact_alert_delete_text_contact.replace("{{number}}", contacts.toLocaleString('pt-BR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }))}`,
                type: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                reverseButtons: true,
                confirmButtonClass: "btn btn-success",
                confirmButtonText: GLOBAL_LANG.contact_alert_delete_confirmButtonText,
                cancelButtonClass: "btn btn-danger",
                cancelButtonText: GLOBAL_LANG.contact_alert_delete_cancelButtonText,
            }).then(t => {
                if (t.value === true)
                    Table.btn.action("delete");
            });
        },
        block() {
            const contacts = Contact.unique_id.length === 1 ? 1 : Contact.list.length;
            swal({
                title: GLOBAL_LANG.contact_alert_block_title,
                text: `${GLOBAL_LANG.contact_alert_block_text_contact.replace("{{number}}", contacts.toLocaleString('pt-BR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }))}`,
                type: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                reverseButtons: true,
                confirmButtonClass: "btn btn-success",
                confirmButtonText: GLOBAL_LANG.contact_alert_block_confirmButtonText,
                cancelButtonClass: "btn btn-danger",
                cancelButtonText: GLOBAL_LANG.contact_alert_block_cancelButtonText,
            }).then(t => {
                if (t.value === true)
                    Table.btn.action("block");
            });
        },
        unblock() {
            const contacts = Contact.unique_id.length === 1 ? 1 : Contact.list.length;
            swal({
                title: GLOBAL_LANG.contact_alert_unblock_title,
                text: `${GLOBAL_LANG.contact_alert_unblock_text_contact.replace("{{number}}", contacts.toLocaleString('pt-BR', { minimumFractionDigits: 0, maximumFractionDigits: 0 }))}`,
                type: "warning",
                showCancelButton: !0,
                buttonsStyling: !1,
                reverseButtons: true,
                confirmButtonClass: "btn btn-success",
                confirmButtonText: GLOBAL_LANG.contact_alert_unblock_confirmButtonText,
                cancelButtonClass: "btn btn-danger",
                cancelButtonText: GLOBAL_LANG.contact_alert_unblock_cancelButtonText,
            }).then(t => {
                if (t.value === true)
                    Table.btn.action("unblock");
            });
        },
        show() {
            document.querySelector(".header-contact .group-btn").style.display = "flex";
            Contact.persona.btn.toggle();
        },
        hide() {
            if (Contact.list.length === 0) {
                document.querySelector(".header-contact .group-btn").style.display = "none";
            }
            Contact.persona.btn.toggle();
        }
    },

    dropdown: {
        selected: null,
        countAll: null,
        click(event) {
            const isAll = this.id === "all";
            const isEmpty = this.id === "empty";

            if (isAll) {
                Table.dropdown.selected = "all";
                Table.dropdown.checkbox(true);
                Table.helpers.searchByModalFilter();
                document.querySelectorAll(".check-box").forEach(check => {
                    check.checked = true;
                    Table.row.selected(check);
                });

            } else if (isEmpty) {
                Table.helpers.removeModal();
            } else {
                this.checked = !this.checked;

                if (!this.checked) {
                    const selectAll = document.getElementById("all");
                    if (selectAll) selectAll.checked = false;
                }

                Table.row.selected(this);
            }

            const checkboxes = document.querySelectorAll(".check-box:checked");
            const idChannels = Array.from(checkboxes).map(cb => cb.getAttribute("channel_id"));
            const allSame = idChannels.every(id => id === idChannels[0]);

            const personaBtn = document.getElementById("btn-persona");
            if (allSame && checkboxes.length > 0) {
                personaBtn.disabled = false;
                personaBtn.title = "";
                personaBtn.addEventListener("click", Contact.persona.modal.prepare);
            } else {
                personaBtn.disabled = true;
                personaBtn.title = GLOBAL_LANG.contact_alert_persona_multiple_channels_text;
                personaBtn.removeEventListener("click", Contact.persona.modal.prepare);
            }
        },
        show() {
            document.getElementById("dropdownMenuInput").style.display = "inline-block";
            document.getElementById("dropdownMenuIcon").style.display = "inline-block";
        },
        hide() {
            if (Contact.list.length === 0) {
                document.getElementById("dropdownMenuInput").style.display = "none";
                document.getElementById("dropdownMenuIcon").style.display = "none";
            }
        },
        checkbox(param) {
            if (param)
                document.querySelector("#dropdownMenuInput").checked = true;
            else
                document.querySelector("#dropdownMenuInput").checked = false;
        },
        icon() {
            setTimeout(() => document.querySelector("#dropdownMenuInput").click(), 100);
        },
        count() {
            if (Table.dropdown.selected === "all") {
                this.countAll = Contact.list.length;
            }
            else {
                this.countEmpty = [];
            }
        },
        checkIfAllSelected() {
            if (parseInt(document.getElementById("countInfo")?.innerHTML) !== Contact.list.length) {
                Table.dropdown.checkbox(false);
                Table.dropdown.selected = null
            }
            this.dropLeft();
        },
        dropLeft() {
            if (Contact.list.length > 0) {
                document.querySelectorAll(".dropleft").forEach(elm => elm.style.display = "none");
            } else
                document.querySelectorAll(".dropleft").forEach(elm => elm.style.display = "block");
        }
    },

    helpers: {
        actionWithContact(action, number_of_requests) {
            return new Promise(async (resolve, reject) => {

                const contacts = Contact.unique_id.length !== 0 ? Contact.unique_id : Contact.list;
                const chunked_data = Table.helpers.splitArrayIntoSubarrays(contacts, number_of_requests);
                const progress = 100 / chunked_data.length;
                this.resetProgressModal();

                for (let i = 0; i < chunked_data.length; i++) {

                    const list = Table.helpers.removeIndexFromArray(chunked_data[i]);

                    const form = new FormData();
                    form.append("data", list);
                    form.append("action", action);

                    if (action === "persona") {
                        form.append("id_persona", Contact.persona.id);
                        form.append("name", document.getElementById("input-name").value);
                        form.append("image", document.getElementById("input-file-hidden").value);
                        form.append("id_channel", Contact.list[0].channel_id);
                    }

                    try {

                        const result = await fetch(`${document.location.origin}/contact/action`, { method: 'POST', body: form });
                        const res = await result.json();

                        this.alertsError(res);
                        this.inService(action, res, progress, list.length);

                    } catch (error) {
                        console.log('Erro na requisição:', error);
                        reject(false);
                    }
                }

                resolve(true);
            });
        },
        inService(action, res, value_progress, value_contact) {
            if (action === "attendance") {
                Contact.attendance += res != null ? res.success.attendance : 0;
            } else
                Table.helpers.setProgressModal(value_progress, value_contact);
        },
        setTitleModal() {
            switch (Table.btn.selectedAction) {
                case "delete":
                    return GLOBAL_LANG.contact_modal_action_title_delete;
                case "block":
                    return GLOBAL_LANG.contact_modal_action_title_block;
                case "unblock":
                    return GLOBAL_LANG.contact_modal_action_title_unblock;
                case "persona":
                    return GLOBAL_LANG.contact_persona_modal_action_title;
                default:
                    break;
            }
        },
        setDescriptionModal() {
            switch (Table.btn.selectedAction) {
                case "delete":
                    return GLOBAL_LANG.contact_modal_action_delete;
                case "block":
                    return GLOBAL_LANG.contact_modal_action_block;
                case "unblock":
                    return GLOBAL_LANG.contact_modal_action_unblock;
                case "persona":
                    return GLOBAL_LANG.contact_persona_modal_action_info;
                default:
                    break;
            }
        },
        setProgressModal(value_progress, value_contact) {
            const contacts = Contact.unique_id.length === 1 ? 1 : Contact.list.length;

            let progress_bar = document.getElementById("progress-bar");
            let current_value_progress = parseFloat(progress_bar.style.width);
            let new_value_progress = current_value_progress + value_progress;

            let finalized_contacts = document.getElementById("finalized-contacts");
            let current_value_contacts = parseInt(finalized_contacts.innerHTML.replace(/\./g, ""), 10) || 0;
            let new_value_contacts = current_value_contacts + value_contact;

            let format_total_contacts = contacts.toLocaleString('pt-BR', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
            let format_new_contacts = new_value_contacts.toLocaleString('pt-BR', { minimumFractionDigits: 0, maximumFractionDigits: 0 });

            document.getElementById("load-main").style.display = "none";
            document.getElementById("progress-main").style.display = "flex";
            document.getElementById("description-progress").innerHTML = `${this.setDescriptionModal()} <span id='finalized-contacts'>${format_new_contacts}</span> ${GLOBAL_LANG.contact_info_the} ${format_total_contacts}`;

            setTimeout(() => progress_bar.style.width = new_value_progress + "%", 100);
            setTimeout(() => progress_bar.innerHTML = parseInt(new_value_progress) + "%", 100);
            progress_bar.setAttribute("aria-valuenow", new_value_progress);
        },
        resetProgressModal() {
            document.getElementById("title-progress").innerHTML = Table.helpers.setTitleModal();
            document.getElementById("progress-bar").style.width = 0;
            document.getElementById("finalized-contacts").innerHTML = "";

            document.getElementById("description-progress").innerHTML = GLOBAL_LANG.contact_info_processing;
            document.getElementById("progress-main").style.display = "none";
            document.getElementById("load-main").style.display = "block";
        },
        removeModal() {
            Contact.unique_id = [];
            Contact.clear();
            Table.btn.hide();
            Table.row.information();

            Table.dropdown.hide();
            Table.dropdown.checkbox(false);
            Table.dropdown.dropLeft();
            Table.helpers.hideLoadProcess();
            document.querySelectorAll(".check-box").forEach(check => Table.row.deselected(check));
        },
        alertsError(res) {
            if (res == null) return;

            if (res.errors?.code == "TA-001") {
                t.value && swal({
                    title: GLOBAL_LANG.ticket_type_alert_delete_four_title,
                    text: `${GLOBAL_LANG.ticket_type_alert_delete_four_text} (${data.errors.code})`,
                    type: "error",
                    buttonsStyling: !1,
                    confirmButtonClass: "btn btn-danger"
                });
            }

            if (res.success?.status == true) {
                console.log(res);
            }

            if (res.success?.id_persona !== "") {
                Contact.persona.id = res.success?.id_persona;
            }
        },
        async searchSelected() {
            // LIMPA SELEÇÃO ANTIGA
            document.querySelectorAll(".check-box").forEach(cb => cb.checked = false);
            if (Table.row.selectedItems) Table.row.selectedItems = [];

            const text = $("#search").val() || '';
            const search_type = $('#search-type').val() || '';
            const situation = $('#select-situation').val() || '';
            const responsible = $('#select-responsible').val() || '';
            const checkbox_selected = document.getElementById("dropdownMenuInput")?.checked;
            const dt_start = $('#dt-start').val() || '';
            const dt_end = $('#dt-end').val() || '';

            const channels = $('#verify-select').val() == '2'
                ? []
                : ($('#multiselect').val() || []);

            const labels = $('#verify-select2').val() == '2'
                ? []
                : ($('#multiselect2').val() || []);

            const personas = $('#select-persona').val() || [];

            const formData = new FormData();
            formData.append('text', text);
            formData.append('search_type', search_type);
            formData.append('situation', situation);
            formData.append('responsible', responsible);
            formData.append('checkbox_selected', checkbox_selected ? 'true' : 'false');
            formData.append('dt_start', dt_start);
            formData.append('dt_end', dt_end);

            channels.forEach(id => formData.append('channel[]', id));
            labels.forEach(id => formData.append('label[]', id));
            personas.forEach(id => formData.append('persona[]', id));

            const result = await fetch(
                `${document.location.origin}/contact/search/list`,
                { method: 'POST', body: formData }
            );

            if (!result.ok) return;

            const response = await result.json();

            Contact.list = response;
            Contact.count_all = response.length;

            Table.row.information();
            Table.row.count();
        },
        searchByModalFilter() {
            Table.helpers.searchSelected();
        },
        splitArrayIntoSubarrays(list_array, size) {
            const subarrays = [];
            for (let i = 0; i < list_array.length; i += size) {
                const subarray = list_array.slice(i, i + size);
                subarrays.push(subarray);
            }

            return subarrays;
        },
        removeIndexFromArray(contacts) {
            if (!contacts || !contacts[0]) {
                return [];
            }

            return contacts.map(contact => parseInt(contact.id_contact));
        },
        showLoadProcess() {
            $("#modal-process").modal();
            document.querySelector("body").style.paddingRight = "0px";
        },
        hideLoadProcess() {
            $("#modal-process").modal("hide");
            this.removePaddingRight();
        },
        removePaddingRight() {
            setTimeout(() => document.querySelector("body").style.paddingRight = "0px", 500);
        }
    }
}

const Contact = {
    list: [],
    filteredList: [],
    filteredFlag: false,
    unique_id: [],
    attendance: null,
    count_all: 0,

    push(id_contact, channel_name, channel_id) {
        this.list.push({ id_contact: id_contact, channel_name: channel_name, channel_id: channel_id });
    },
    remove(id_contact) {
        const index = Contact.list.findIndex(item => item.id_contact === id_contact);
        if (index !== -1) {
            this.list.splice(index, 1);
        }
    },
    clear() {
        this.list = [];
    },

    persona: {
        ChannelFilter: [],
        id: "",

        add() {
            Contact.persona.id = "";
            const input_name = formValidation({ field: document.getElementById("input-name"), text: GLOBAL_LANG.contact_modal_persona_name, required: true, min: 3, max: 100, alpha_numeric_spaces: true });

            if (input_name) {
                $("#modal-add-persona").modal("hide");
                Table.btn.action("persona");
            }
        },

        btn: {

            toggle() {
                const personaBtn = document.getElementById("btn-add-persona");
                if (this.sameChannel()) {
                    personaBtn.addEventListener("click", Contact.persona.modal.prepare);
                    personaBtn.style.display = "block";
                    document.getElementById("btn-persona").disabled = false;
                    document.getElementById("btn-persona").removeAttribute("title");
                } else {
                    document.getElementById("btn-persona").disabled = true;
                    document.getElementById("btn-persona").title = GLOBAL_LANG.contact_alert_persona_multiple_channels_text;
                    personaBtn.removeEventListener("click", Contact.persona.modal.prepare);
                }
            },
            hide() {
                const personaBtn = document.getElementById("btn-add-persona");
                personaBtn.style.display = "none";
                personaBtn.removeEventListener("click", Contact.persona.modal.prepare);
            },
            sameChannel() {
                if (Contact.list.length === 0) return false;
                const firstChannel = Contact.list[0].channel_id;
                return Contact.list.every(contact => contact.channel_id === firstChannel);
            }
        },

        modal: {
            prepare() {
                const channel_name = Contact.list[0].channel_name;

                document.getElementById("input-channel").value = channel_name;

                document.getElementById("input-file").removeEventListener("change", Contact.persona.picture.verifyUpload);
                document.querySelector(".picture-persona").removeEventListener("click", Contact.persona.picture.click);
                document.getElementById("save-persona").removeEventListener("click", Contact.persona.add);

                document.getElementById("input-name").value = "";
                document.getElementById("input-file-hidden").value = "";
                document.getElementById("alert__input-name").innerText = "";
                document.querySelector(".picture-persona img").src = "assets/img/panel/image_placeholder.png";

                Contact.persona.modal.show();
            },
            show() {
                document.getElementById("input-file").addEventListener("change", Contact.persona.picture.verifyUpload);
                document.querySelector(".picture-persona").addEventListener("click", Contact.persona.picture.click);
                document.getElementById("save-persona").addEventListener("click", Contact.persona.add);

                $("#modal-add-persona").modal();
            }
        },

        alert: {
            show(alert) {
                switch (alert) {
                    case "max_size":
                        swal({
                            title: GLOBAL_LANG.contact_alert_title,
                            text: GLOBAL_LANG.contact_alert_dropzone_maxSize_text,
                            type: "warning",
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: GLOBAL_LANG.contact_alert_confirmButtonText,
                            cancelButtonClass: "btn btn-secondary",
                        });
                        break;
                    case "files":
                        swal({
                            title: GLOBAL_LANG.contact_alert_title,
                            text: GLOBAL_LANG.contact_alert_dropzone_archives_text,
                            type: "warning",
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: GLOBAL_LANG.contact_alert_confirmButtonText,
                            cancelButtonClass: "btn btn-secondary",
                        });
                        break;
                    case "contact_is_blocked":
                        swal({
                            title: GLOBAL_LANG.contact_alert_title,
                            text: GLOBAL_LANG.contact_alert_contact_blocked_text,
                            type: 'warning',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: GLOBAL_LANG.contact_alert_confirmButtonText,
                            cancelButtonClass: "btn btn-secondary"
                        });
                        break;
                    case "created":
                        swal({
                            title: GLOBAL_LANG.contact_alert_created_persona_title,
                            text: GLOBAL_LANG.contact_alert_created_persona_text,
                            type: 'success',
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: GLOBAL_LANG.contact_alert_confirmButtonText
                        });
                        break;
                }
            }
        },

        picture: {
            click() {
                document.getElementById("input-file").click();
            },
            upload(file) {
                const id_channel = document.querySelector("#mult-select").getAttribute("id");
                const formData = new FormData();

                formData.append("filetoupload", file);
                formData.append("ta_id", id_channel);
                formData.append("media_mime_type", file.type);

                const settings = {
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
                    document.querySelector(".transition-effect img").setAttribute("src", "data:image/jpeg;base64," + JSON.parse(response).thumbnail);
                    document.getElementById("input-file-hidden").value = JSON.parse(response).url;
                });
            },
            verifyUpload(element) {
                const max_file_size = 10 * 1024 * 1024;
                const accepted_file_type = ["image/png", "image/jpg", "image/jpeg"];
                const file = element.target.files[0];

                if (file?.size > max_file_size) {
                    Contact.persona.alert.show("max_size");
                } else if (!accepted_file_type.includes(file.type)) {
                    Contact.persona.alert.show("files");
                } else {
                    Contact.persona.picture.upload(file);
                }
            }
        }
    }
}

function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.contact.search) {
            document.getElementById("search").value = Filters.contact.search;
        }

        if (Filters.contact.input_search) {
            document.getElementById("input-search").value = Filters.contact.input_search;
        }

        if (Filters.contact.search_type) {
            document.getElementById("search-type").value = Filters.contact.search_type;
        }

        if (Filters.contact.channel.length !== 0) {
            modalFilter();
            document.getElementById("check-select").click();

            document.querySelectorAll("#mult-select .cust_").forEach((element, index) => {

                if (Filters.contact.channel.includes(element.value))
                    document.querySelectorAll("#mult-select .cust_")[index].click();
            })
        }

        if (Filters.contact.label.length !== 0) {
            modalFilter();
            document.getElementById("check-select2").click();

            document.querySelectorAll("#mult-select2 .cust_").forEach((element, index) => {

                if (Filters.contact.label.includes(element.value))
                    document.querySelectorAll("#mult-select2 .cust_")[index].click();
            })
        }

        if (Filters.contact.responsible) {
            modalFilter();
            document.getElementById("check-responsible").click();
            document.getElementById("select-responsible").value = Filters.contact.responsible;
        }

        if (Filters.contact.situation) {
            modalFilter();
            document.getElementById("check-situation").click();
            document.getElementById("select-situation").value = Filters.contact.situation;
        }

        if (Filters.contact.dt_start) {
            modalFilter();

            document.getElementById("check-date").click();

            document.getElementById("dt-end").disabled = false;
            document.getElementById("dt-end").type = "date";
            document.getElementById("dt-start").type = "date";

            document.getElementById("dt-start").value = Filters.contact.dt_start;
            document.getElementById("dt-end").value = Filters.contact.dt_end;
        }

        Filters.btn_back = null;
        localStorage.setItem("filters", JSON.stringify(Filters));
    }
}

checkFilter(Filters);

function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "contact/find",
            type: "POST",
            data: {
                text: $("#search").val(),
                search_type: $('#search-type').val(),
                channel: $('#verify-select').val() == '2' ? '' : ($('#multiselect').val()?.length == 0 ? '' : $('#multiselect').val()),
                label: $('#verify-select2').val() == '2' ? '' : ($('#multiselect2').val()?.length == 0 ? '' : $('#multiselect2').val()),
                situation: $('#select-situation').val(),
                responsible: $('#select-responsible').val(),
                persona: $('#check-persona').is(':checked')
                    ? ($('#select-persona').val() && $('#select-persona').val().length > 0
                        ? $('#select-persona').val()
                        : '')
                    : '',
                checkbox_selected: document.getElementById("dropdownMenuInput")?.checked,
                dt_start: $('#dt-start').val(),
                dt_end: $('#dt-end').val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall"),
            }
        },
        columns: [
            { mData: '' },
            { mData: 'profile' },
            { mData: 'channel' },
            { mData: 'creation_contact' },
            { mData: 'verify' }
        ],
        columnDefs: [
            {
                targets: 0,
                render: function (data, type, full, meta) {
                    return `<div class='checkbox-table'>
                                <input type='checkbox' class='check-box' channel_id=${full.id_channel} id='${full.id_contact}' name='verify_check_box[]' data-spam='${full.spam}' data-key_remote_id='${full.key_remote_id}'>
                            </div>`;
                }
            },
            {
                targets: 1,
                render: function (data, type, full, meta) {
                    let name = full.full_name == "" ? full.key_remote_id : full.full_name;

                    return `<div class='kt-user-card-v2'>
                                <div class='kt-user-card-v2__pic'>
                                <img src='https://files.talkall.com.br:3000/p/${full.key_remote_id}.jpeg' class='avatar rounded-circle mr-3 m-img-rounded kt-marginless' alt='photo'>
                                </div>
                                <div class='kt-user-card-v2__details'>
                                <b class='kt-user-card-v2__name' style='margin-bottom: 6px'>${name}</b>
                                <span data-toggle='modal' data-target='#modal-open-chat' class='modal-open-chat' style='cursor: pointer'>${full.key_remote_id}</span>
                                </div>
                           </div>`;
                }
            },
            {
                targets: 4,
                render: function (data, type, full, meta) {
                    if (full.verify == 1 && full.spam == 1) {
                        return "<span style='background: #fff;' data-toggle='tooltip' data-placement='right' title='" + GLOBAL_LANG.contact_datatable_column_situation_user_verify + "'><i class='fas fa-exclamation-triangle' style='color: orange; font-size: 13pt;'></i></span>";
                    }
                    if (full.spam == 2) {
                        return "<span style='background: #fff;' data-toggle='tooltip' data-placement='right' title='" + GLOBAL_LANG.contact_datatable_column_situation_user_spam + "'><i class='fas fa-sharp fa-solid fa-ban' style='color: red; font-size: 13pt;'></i></span>";
                    }
                    if (full.verify == 2 && full.exist == 2) {
                        return "<span style='background: #fff;' data-toggle='tooltip' data-placement='right' title='" + GLOBAL_LANG.contact_datatable_column_situation_user_verified + "'><i class='fas fa-times' style='color: red; font-size: 13pt;'></i></span>";
                    }
                    if (full.verify == 2 && full.exist == 1 && full.spam == 1) {
                        return "<span style='background: #fff;' data-toggle='tooltip' data-placement='right' title='" + GLOBAL_LANG.contact_datatable_column_situation_user_verified_whatsapp + "'><i class='fas fa-check' style='color: green; font-size: 13pt;'></i></span>";
                    }
                }
            },
            {
                targets: 5,
                render: function (data, type, full, meta) {
                    return `<div class="dropleft">
                                <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                    <i class="fa fa-ellipsis-v"></i>
                                    <div class="ripple-container"></div>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="multiDropdownMenu">
                                    <a id="${full.id_contact}" href='#' class="dropdown-item table-btn-edit" style="cursor: pointer">
                                        <div style="width: 24px; display: inline-block">
                                            <i class="far fa-edit"></i>
                                        </div>
                                        <span>${GLOBAL_LANG.contact_datatable_dropdown_menu_edit}</span>
                                    </a>
                                    <a class="dropdown-item table-btn-spam ${full.spam == 1 ? 'block-contact' : 'unblock-contact'}" id="btn-blocklist" data-id="${full.id_contact}" style="cursor: pointer">
                                        <div style="width: 24px; display: inline-block">
                                            <i class="${full.spam == 1 ? "fas fa-thumbs-up" : "fas fa-unlock"}"></i>
                                        </div>
                                        <span>${full.spam == 1 ? GLOBAL_LANG.contact_datatable_dropdown_menu_block_list : GLOBAL_LANG.contact_delete_block_list}</span>
                                    </a>
                                    <a href='#' class="dropdown-item table-btn-delete" id="btn-delete" data-id="${full.id_contact}" data-key_remote_id="${full.key_remote_id}" style="cursor: pointer">
                                        <div style="width: 24px; display: inline-block">
                                            <i class="far fa-trash-alt"></i>
                                        </div>
                                        <span>${GLOBAL_LANG.contact_datatable_dropdown_menu_delete}</span>
                                    </a>
                                </div>
                            </div>`;
                }
            },
            {
                orderable: false,
                targets: [0, 5]
            }
        ],
        order: [[1, 'asc']],
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
            for (const check of [...document.querySelectorAll(".check-box")]) {
                for (const item of Contact.list) {
                    if (item.id_contact == check.id) {
                        Table.row.selected(check);
                    }
                }
            }

            if (settings.json.recordsTotal == 0) {
                $("#modalExport").attr('disabled', true);
            } else {
                $("#modalExport").attr('disabled', false);
            }

            const contact = {
                search: $("#search").val(),
                input_search: $("#input-search").val(),
                search_type: $("#search-type").val(),
                channel: $("#multiselect").val(),
                label: $("#multiselect2").val(),
                responsible: $("#select-responsible").val(),
                situation: $("#select-situation").val(),
                dt_start: $("#dt-start").val(),
                dt_end: $("#dt-end").val(),
                persona: $('#check-persona').is(':checked') ? $('#select-persona').val() : ''
            };

            let filter = localStorage.getItem("filters");
            filter = filter ? JSON.parse(filter) : {};
            filter.contact = contact;
            localStorage.setItem("filters", JSON.stringify(filter));

            if (Table.hasFilters == true) {
                Table.helpers.removeModal();
                Table.hasFilters = false;
                return;
            }

            Table.dropdown.dropLeft();
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

    $("body").on("mouseover", ".transition-effect", () => {
        $("#add-profile").css({ "display": "block" });
        $(".picture-persona-title").css({ "display": "block" });
        $(".transition-effect img").css({ "opacity": "0.8" });
        $(".transition-effect img").css({ "filter": "brightness(50%)" });
    });

    $("body").on("mouseenter", ".transition-effect", () => {
        $("#add-profile").css({ "display": "block" });
        $(".picture-persona-title").css({ "display": "block" });
        $(".transition-effect img").css({ "opacity": "0.8" });
        $(".transition-effect img").css({ "opacity": "0.8" });
        $(".transition-effect img").css({ "filter": "brightness(40%)" });
    });

    $("body").on("mouseout", ".transition-effect", () => {
        $("#add-profile").css({ "display": "none" });
        $(".picture-persona-title").css({ "display": "none" });
        $(".transition-effect img").css({ "opacity": "1" });
        $(".transition-effect img").css({ "opacity": "1" });
        $(".transition-effect img").css({ "filter": "brightness(100%)" });
    });

    $("#datatable-basic").on("click", ".table-btn-edit", function () {
        window.location.href = "contact/edit/" + this.id;
    });

    $("#datatable-basic").on("click", "#btn-delete", function () {
        Contact.unique_id = [];
        Contact.unique_id.push({ id_contact: this.dataset.id });
        Table.btn.delete();
    });

    $("#datatable-basic").on("click", "#btn-blocklist", function () {
        Contact.unique_id = [];
        Contact.unique_id.push({ id_contact: this.dataset.id });

        if (this.classList.contains("block-contact"))
            Table.btn.block();
        else
            Table.btn.unblock();
    });

    $("#btnUnblock").on("click", function () {
        swal({
            title: GLOBAL_LANG.contact_alert_unblock_title,
            text: `${GLOBAL_LANG.contact_alert_unblock_text_contact.replace("{{number}}", 1)}`,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.contact_alert_unblock_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.contact_alert_unblock_cancelButtonText,
        }).then(async t => {
            if (t.value === true) {

                const form = new FormData();
                form.append("data", [this.dataset.id]);
                form.append("action", "unblock");

                const result = await fetch(`${document.location.origin}/contact/action`, { method: 'POST', body: form });

                if (result.ok)
                    document.querySelector("#btnUnblock").style.display = "none";
            }
        });
    });

    $("body").on("click", ".check-box", function (e) {
        Table.row.click(e);
        e.stopPropagation();
    });

    $("body").on("click", ".checkbox-table", function () {
        this.firstElementChild.click();
    });

    $("#modalFilter").one("click", () => modalFilter());
    $('#sendEmailExport').on('click', () => modalExport());

    createLabels();
    modalOpenChat();

    document.querySelectorAll(".dropdown .dropdown-item")?.forEach(elm => elm.addEventListener("click", Table.dropdown.click));
    document.getElementById("dropdownMenuIcon")?.addEventListener("click", Table.dropdown.icon);

    document.getElementById("btn-delete")?.addEventListener("click", Table.btn.delete);
    document.getElementById("btn-block")?.addEventListener("click", Table.btn.block);
    document.getElementById("btn-unblock")?.addEventListener("click", Table.btn.unblock);
});

function getPersonas(selectedChannels) {

    const formData = new FormData();
    formData.append("channels", JSON.stringify(selectedChannels));
    formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

    fetch('/contact/get/personas', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (!response.ok)
                throw new Error(`HTTP error! status: ${response.status}`);

            return response.json();
        })
        .then(data => {
            updatePersonaSelect(data);
        })
        .catch(error => {
            console.error('Erro:', error);
        });
}

function updatePersonaSelect(personas) {
    const existingContainer = document.querySelector('#select-persona').parentNode.querySelector('.msf_multiselect_container');
    if (existingContainer) {
        existingContainer.remove();
    }

    const selectPersona = document.querySelector('#select-persona');
    selectPersona.innerHTML = '';

    personas.forEach(persona => {
        const option = document.createElement('option');
        option.value = persona.id_group_contact;
        option.text = persona.name;
        selectPersona.appendChild(option);
    });

    new MSFmultiSelect(selectPersona, {
        theme: 'theme2',
        selectAll: true,
        searchBox: true,
        width: "100%",
        height: 47
    });
}

function verifyInputOrder(input_order) {
    let input_value = input_order.value.trim();
    input_value = input_value.replace(/[^\d]/g, "");

    const number_digits = input_value.toString().length;
    input_order.value = number_digits > 6 ? input_value.slice(0, 6) : input_value;
}

function createLabels() {
    let label_name_val = $("#label-name").val();
    let label_color_val = $("#label-color").val();

    let label_name = (label_name_val && label_name_val !== "") ? label_name_val.split(",") : [];
    let label_color = (label_color_val && label_color_val !== "") ? label_color_val.split(",") : [];

    $("#label-contact").empty();
    $("#modal-label .custom-control-input").prop('checked', false);

    for (let i = 0; i < label_name.length; i++) {
        if (label_name[i] !== "") {
            $("#label-contact").append(`<span style="background:${label_color[i]};color: #fff;font-weight: 600;text-transform: uppercase;margin: 3px 2px;padding: 2px 8px; border-radius: 20px; font-size: 11px; display: inline-block;">${label_name[i]}</span>`);

            $("#modal-label li").each(function () {
                if ($(this).find(".label-text-style").text().trim() === label_name[i].toUpperCase().trim()) {
                    $(this).find(".custom-control-input").prop('checked', true);
                }
            });
        }
    }
}

function modalOpenChat() {
    let key_remote_id_chat;
    let text = '?text=' + GLOBAL_LANG.contact_js_let_text_hello;

    $('body').on("click", ".modal-open-chat", (e) => {
        $('#label-info-contact').html(GLOBAL_LANG.contact_js_label_info_contact_one + ' ' + e.target.innerHTML + ' ' + GLOBAL_LANG.contact_js_label_info_contact_two);
        key_remote_id_chat = e.target.innerHTML;
    });

    $('body').on("click", "#open-chat", () => {
        window.open(document.location.origin + "/chat/" + key_remote_id_chat + text);
    });
}

function modalFilter() {

    $("body").on("click", ".ignore", function (e) {
        const id = e.target.parentElement.parentElement.parentElement.parentElement.id;
        if (id != "mult-select") return;

        let executou = false;

        if (!executou) {
            handleAnyChange('selectAll');
            executou = true;
        }
    });

    if (!$(".msf_multiselect_container").hasClass("msf_multiselect_container")) {
        const msf_multiselect_container = document.querySelectorAll(".msf_multiselect_container");

        for (elm of msf_multiselect_container) elm.remove();

        var select = new MSFmultiSelect(document.querySelector('#multiselect'), {
            theme: 'theme2',
            selectAll: true,
            searchBox: true,
            width: "100%",
            height: 47,
            onChange: function (value, text, element) {
                handleAnyChange('onChange');
            }
        });

        function handleAnyChange(eventType) {
            $(".closeBtn").each(function () {
                $(this).on("click", function () {
                    handleAnyChange('onChange');
                });
            });

            var originalSelect = document.querySelector('#multiselect');
            var selectedOptions = Array.from(originalSelect.selectedOptions);
            var selectedValues = selectedOptions.map(option => option.value);

            if (eventType === 'onChange') {
                resetPersona();
                getPersonas(selectedValues);
            } else if (eventType === 'selectAll') {
                resetPersona();
                getPersonas([]);
            }
        }

        var select2 = new MSFmultiSelect(document.querySelector('#multiselect2'), {
            theme: 'theme2',
            selectAll: true,
            searchBox: true,
            width: "100%",
            height: 47
        });

        var select3 = new MSFmultiSelect(document.querySelector('#select-persona'), {
            theme: 'theme2',
            selectAll: true,
            searchBox: true,
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
    const select_search = document.getElementById("search-type");

    const check_select = document.getElementById("check-select");
    const mult_select = document.getElementById("mult-select");
    const verify_select = document.getElementById("verify-select");

    const check_select2 = document.getElementById("check-select2");
    const mult_select2 = document.getElementById("mult-select2");
    const verify_select2 = document.getElementById("verify-select2");

    const check_persona = document.getElementById("check-persona");

    const check_responsible = document.getElementById("check-responsible");
    const select_responsible = document.getElementById("select-responsible");

    const check_situation = document.getElementById("check-situation");
    const select_situation = document.getElementById("select-situation");

    const dt_end = document.getElementById("dt-end");
    const dt_start = document.getElementById("dt-start");
    const check_date = document.getElementById("check-date");

    const btn_search = document.getElementById("btn-search");
    const logger = document.querySelectorAll(".logger");

    check_search.checked = true;
    input_search.style.display = "block";
    select_search.style.display = "block";

    check_search.addEventListener("click", () => {
        if (check_search.checked) {
            input_search.style.display = "block";
            select_search.style.display = "block";
        } else {
            input_search.value = "";
            input_search.style.display = "none";
            select_search.style.display = "none";
        }
    });

    check_select.addEventListener("click", () => {
        if (check_select.checked) {
            resetMultiselect(select);
            mult_select.style.display = "block";
            verify_select.value = "1";
        } else {
            mult_select.style.display = "none";
            verify_select.value = "2";
        }
    });

    check_select2.addEventListener("click", () => {
        if (check_select2.checked) {
            resetMultiselect(select2);
            mult_select2.style.display = "block";
            verify_select2.value = "1";
        } else {
            mult_select2.style.display = "none";
            verify_select2.value = "2";
        }
    });

    check_persona.addEventListener("click", () => {
        if (check_persona.checked) {
            resetMultiselect(select3);
            document.getElementById("mult-select-persona").style.display = "block";
        } else {
            resetMultiselect(select3);
            $('#select-persona').val([]).trigger('change');

            document.getElementById("mult-select-persona").style.display = "none";

            if (window.Filters && window.Filters.contact) delete Filters.contact.persona;

            const saved = JSON.parse(localStorage.getItem('filters')) || {};
            if (saved.contact) {
                delete saved.contact.persona;
                localStorage.setItem('filters', JSON.stringify(saved));
            }
        }
    });

    check_responsible.addEventListener("click", () => {
        if (check_responsible.checked) {
            select_responsible.style.display = "block";
        } else {
            select_responsible.value = "";
            select_responsible.style.display = "none";
        }
    });

    check_situation.addEventListener("click", () => {
        if (check_situation.checked) {
            select_situation.style.display = "block";
        } else {
            select_situation.value = "";
            select_situation.style.display = "none";
        }
    });

    check_date.addEventListener("click", () => {
        if (check_date.checked) {
            dt_start.value = "";
            dt_start.type = "text";
            dt_start.style.display = "block";
            dt_start.placeholder = GLOBAL_LANG.contact_filter_period_placeholder_date_start;

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

    select_search.addEventListener("change", () => {
        document.getElementById("input-search").focus();
    });

    btn_search.addEventListener("click", () => {
        search.value = input_search.value;

        if (Table.helpers?.removeModal) {
            Table.helpers.removeModal();
        }

        Table.hasFilters = true;
        Contact.filteredFlag = true;
        find();
    });

    for (elm of logger) {
        elm.style.paddingLeft = "15px";
    }
}

function resetPersona() {
    resetMultiselect(select3);
    $('#select-persona').val([]).trigger('change');

    if (window.Filters?.persona) delete Filters.persona;

    const saved = JSON.parse(localStorage.getItem('filters')) || {};
    delete saved.persona;
    localStorage.setItem('filters', JSON.stringify(saved));
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
            column = "contact";
            break;

        case 2:
            column = "channel";
            break;

        case 3:
            column = "creation";
            break;

        case 4:
            column = "verify";
            break;

        default:
            column = "contact";
            break;
    }

    $.get(`/export/xlsx?
    search=${$('#search').val() == "" ? $('#input-search').val() : $('#search').val()}
    &search_type=${$('#search-type').val()}
    &channel=${$('#verify-select').val() == '2' ? '' : ($('#multiselect').val()?.length == 0 ? '' : $('#multiselect').val())}
    &label=${$('#verify-select2').val() == '2' ? '' : ($('#multiselect2').val()?.length == 0 ? '' : $('#multiselect2').val())}
    &responsible=${$('#select-responsible').val()}
    &situation=${$('#select-situation').val()}
    &persona=${$('#check-persona').is(':checked') ? ($('#select-persona').val()?.length ? $('#select-persona').val() : '') : ''}
    &column=${column}
    &order=${order}
    &dt_start=${$('#dt-start').val()}
    &dt_end=${$('#dt-end').val()}
    &type=contact`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.contact_alert_export_title,
                text: GLOBAL_LANG.contact_alert_export_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.contact_alert_export_confirmButtonText
            });
        }
    });
}

if (document.getElementById("multiselect_label")) {
    new MSFmultiSelect(
        document.querySelector('#multiselect_label'), {
        theme: 'theme1',
        selectAll: true,
        searchBox: true,
        width: "100%",
        height: 47,
        placeholder: GLOBAL_LANG.contact_label_placeholder
    }
    );
}

function saveLabels(id_contact, btn) {
    btn.disabled = true;

    const form = document.getElementById("form-labels-contact");
    const formData = new FormData(form);

    formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

    fetch(`${document.location.origin}/contact/save/labels/${id_contact}`, {
        method: "POST",
        body: formData
    })
        .then(r => r.json())
        .then(data => {
            if (data.status === "success") {
                document.getElementById("label-name").value = data.names.join(",");
                document.getElementById("label-color").value = data.colors.join(",");
                localStorage.removeItem("filters");
                createLabels();
                $("#modal-label").modal("hide");
            } else {
                console.error(data.message || "Erro desconhecido");
                $("#modal-label").modal("hide");
            }
        })
        .catch(err => {
            console.error("Erro interno:", err);
            $("#modal-label").modal("hide");
        })
        .finally(() => {
            $("[name='csrf_token_talkall']").val(Cookies.get("csrf_cookie_talkall"));
            btn.disabled = false;
        });
}

document.getElementById("btn-save-labels").addEventListener("click", function () {
    const id_contact = document.querySelector(".unblock-contact").dataset.id;
    saveLabels(id_contact, this);
});