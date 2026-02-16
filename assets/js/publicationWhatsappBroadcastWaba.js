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


// Validação do horário de agendamento
function check_hour(date_start, time_start) {

    let date = date_start;
    date = date.split("/")[2] + "-" + date.split("/")[1] + "-" + date.split("/")[0] + " " + time_start;

    let date_current = new Date();
    date_current.setMinutes(date_current.getMinutes() + 30);

    let year = date_current.getFullYear();
    let month = date_current.getMonth() + 1;
    let day = date_current.getDate();
    let hour = date_current.getHours();
    let minutes = date_current.getMinutes();

    date_current = year + "-" + month + "-" + day + " " + hour + ":" + minutes;

    const date__start = new Date(date);
    const date__current = new Date(date_current);

    if (date__start > date__current) {
        return true;
    } else {
        return false;
    }
}


function getCurrentTime() {
    const now = new Date();
    const hours = now.getHours().toString().padStart(2, '0');
    const minutes = now.getMinutes().toString().padStart(2, '0');
    return `${hours}:${minutes}`;
}


function check_date(date_start) {

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
            message = GLOBAL_LANG.whatsapp_broadcast_waba_datatables_edit_status_2
            break;
        case '5':
            message = GLOBAL_LANG.whatsapp_broadcast_waba_datatables_edit_status_5
            break;
        case '1':
        case '6':
            message = GLOBAL_LANG.whatsapp_broadcast_waba_datatables_edit_status_6
            break;
        default:
            message = GLOBAL_LANG.whatsapp_broadcast_waba_datatables_edit_default
            break;
    }

    return message;
}


function advanceMin() {

    // let minutes = 30, date_current = new Date();
    // let timestamp = date_current.setMinutes(date_current.getMinutes() + minutes);

    // let tamp = timestamp, date = new Date(tamp);
    // let hour = date.getHours(), min = date.getMinutes();

    // if (min < 10) min = "0" + min; if (hour < 10) hour = "0" + hour;

    // let early_date = hour + ":" + min;

    // document.getElementById("time_start").value = early_date;
}


function getGroups() {

    let option = document.getElementById("select_channel");
    id_channel = option.value

    $("#select_segmented_group").find("option").remove();
    $("#select_segmented_group").append(`<option value="0">${GLOBAL_LANG.whatsapp_broadcast_waba_segments_select_group_placeholder}</option>`);

    let id_group = $("#groups").val();


    fetch(document.location.origin + "/publication/whatsapp/broadcast/waba/listGroups/" + id_channel)
        .then(response => response.json()
            .then(function (response) {

                for (let i = 0; i < response.length; i++) {

                    const option = document.createElement("option");
                    option.value = response[i]['id_group_contact'];
                    option.innerHTML = response[i]['name'];
                    if (id_group == response[i]['id_group_contact']) {
                        option.selected = "selected";
                    }

                    const select_segmented_group = document.getElementById("select_segmented_group");
                    select_segmented_group.appendChild(option);

                }

            }));

}


function addInfoBroadcast(data) {

    const detro_da_janela_24h = data[0].detro_da_janela_24h == null ? 0 : data[0].detro_da_janela_24h;
    const fora_da_janela_24h = data[0].fora_da_janela_24h == null ? 0 : data[0].fora_da_janela_24h;

    const total_contacts = parseInt(detro_da_janela_24h) + parseInt(fora_da_janela_24h);
    const price = data[0].total == 0 ? " 00,00" : data[0].total;

    document.getElementById("info_contacts").value = total_contacts;
    document.getElementById("info_fora_24h").value = fora_da_janela_24h;
    document.getElementById("info_dentro_24h").value = detro_da_janela_24h;
    document.getElementById("info_price").value = price;
}


function calculateBalance() {

    let option = document.getElementById("select_channel");

    if (option.value == 0) {

        document.getElementById("info_contacts").value = "0";
        document.getElementById("info_fora_24h").value = "0";
        document.getElementById("info_dentro_24h").value = "0";
        document.getElementById("info_price").value = "00,00";

        $("#select_segmented_group").find("option").remove();
        $("#select_segmented_group").append(`<option value="0">Selecionar</option>`);

        return;
    }

    fetch(document.location.origin + "/publication/whatsapp/broadcast/waba/calculatebalance/" + option.value)
        .then(response => response.json()
            .then(response => addInfoBroadcast(response)));

    getGroups(option.value);
}


async function validation() {

    return new Promise(async (resolve, reject) => {

        let form_validation = true;

        const url = window.location.href;
        if (url.includes("/edit/")) {
            const token = url.split('/')[url.split('/').length - 1];
            const result = await fetch(`${document.location.origin}/publication/whatsapp/broadcast/waba/checktime/edit/${token}`);
            const data = await result.json();

            if (data.errors) {
                alertErrors(data);
                form_validation = false;
                resolve(form_validation);
                return;
            }
        }

        const input_title = document.getElementById("input_title").value;
        const date_start = document.getElementById("date_start").value;
        const time_start = document.getElementById("time_start").value;
        const select_channel = document.getElementById("select_channel").value;
        const selectTemplate = document.getElementById("select_template").value;
        const toggle_segmented_group = document.getElementById("toggle-segmented-group");
        const parameter_button_url = document.getElementById("parameter_button_url") != null ? document.getElementById("parameter_button_url").value : false;
        const header_parameter = document.getElementById("img_header") != null ? document.getElementById("img_header").src : false;

        for (var i = 0; i < bodyParameters.length; i++) {
            const select_parameter_body = document.querySelector(`#select_type_parameters${i + 1}`) != null ? document.querySelector(`#select_type_parameters${i + 1}`).value : false;
            const input_parameter_body = document.querySelector(`#parametro${i + 1}`) != null ? document.querySelector(`#parametro${i + 1}`).value : false;

            if (select_parameter_body == 'clientsData' && input_parameter_body == '0') {
                const div_parameter = document.getElementById(`part-${i + 1}`);

                const alert_field = document.createElement("div");
                alert_field.id = `alert_field${i + 1}`;
                alert_field.className = "alert-field-validation"
                alert_field.innerHTML = GLOBAL_LANG.whatsapp_broadcast_waba_validation_parameter;

                div_parameter.appendChild(alert_field)

                form_validation = false;
                resolve(form_validation);
                return;
            }

            if (select_parameter_body === '0' || input_parameter_body === "") {
                form_validation = false;

                if (document.getElementById(`alert_field${i + 1}`) != null) {
                    document.getElementById(`alert_field${i + 1}`).remove();
                }

                const div_select = document.getElementById(`element${i + 1}`);
                const div_parameter = document.getElementById(`part-${i + 1}`);

                const alert_field = document.createElement("div");
                alert_field.id = `alert_field${i + 1}`;
                alert_field.className = "alert-field-validation";

                if (select_parameter_body === '0') {
                    alert_field.innerHTML = GLOBAL_LANG.whatsapp_broadcast_waba_validation_parameter_type;
                    div_select.appendChild(alert_field);

                    form_validation = false;
                    resolve(form_validation);

                } else if (input_parameter_body === "") {
                    alert_field.innerHTML = GLOBAL_LANG.whatsapp_broadcast_waba_validation_parameter;
                    div_parameter.appendChild(alert_field);

                    form_validation = false;
                    resolve(form_validation);
                }
            } else {
                form_validation = true;

                if (document.getElementById(`alert_field${i + 1}`) != null) {
                    document.getElementById(`alert_field${i + 1}`).remove();
                }
            }
        }

        if (parameter_button_url === "") {
            form_validation = false;

            if (document.getElementById("alert_input_button") != null) {
                document.getElementById("alert_input_button").remove();
            }

            const div_url_parameter = document.getElementById("buttons_temp_param");

            const alert_div = document.createElement("div");
            alert_div.className = "alert-field-validation";
            alert_div.id = "alert_input_button";
            alert_div.innerHTML = GLOBAL_LANG.whatsapp_broadcast_waba_validation_url_parameter;

            div_url_parameter.appendChild(alert_div);

            resolve(form_validation);
        } else {
            form_validation = true;

            if (document.getElementById("alert_input_button") != null) {
                document.getElementById("alert_input_button").remove();
            }
        }

        if (header_parameter == document.location) {
            form_validation = false;

            if (document.getElementById("alert_header") != null) {
                document.getElementById("alert_header").remove();
            }

            const header_parameter_type = document.getElementById("header_temp_param");

            const alert_header = document.createElement("div");
            alert_header.id = "alert_header";
            alert_header.style.marginTop = "-48px";
            alert_header.className = "alert-field-validation";
            alert_header.innerHTML = GLOBAL_LANG.whatsapp_broadcast_waba_validation_file;

            header_parameter_type.appendChild(alert_header);
            resolve(form_validation);
        } else {
            form_validation = true;

            if (document.getElementById("alert_header") != null) {
                document.getElementById("alert_header").remove();
            }
        }

        if (toggle_segmented_group.checked) {

            if (document.getElementById("select_segmented_group").value == 0) {
                form_validation = false;
                document.getElementById("alert_segmented_group").style.display = "block";
                document.getElementById("alert_segmented_group").innerHTML = GLOBAL_LANG.whatsapp_broadcast_waba_validation_group;
                window.scroll(0, document.getElementById("alert_segmented_group"));
                resolve(form_validation);
            }
        } else {
            form_validation = true;
            document.getElementById("alert_segmented_group").style.display = "none";
        }

        if (!input_title) {
            form_validation = false;
            document.getElementById("alert_input_title").style.display = "block";
            document.getElementById("alert_input_title").innerHTML = GLOBAL_LANG.whatsapp_broadcast_waba_validation_title;
            window.scroll(0, document.getElementById("alert_input_title"));
            resolve(form_validation);
        } else {
            document.getElementById("alert_input_title").style.display = "none";
        }

        if (!check_date(date_start)) {
            form_validation = false;
            document.getElementById("alert_date_start").style.display = "block";
            document.getElementById("alert_date_start").innerHTML = GLOBAL_LANG.whatsapp_broadcast_waba_validation_date;
            window.scroll(0, document.getElementById("alert_date_start"));
            resolve(form_validation);
        } else {
            document.getElementById("alert_date_start").style.display = "none";
        }

        if (time_start == "") {
            form_validation = false;
            document.getElementById("alert_time_start").style.display = "block";
            document.getElementById("alert_time_start").innerHTML = GLOBAL_LANG.whatsapp_broadcast_waba_validation_hour;
            window.scroll(0, document.getElementById("alert_time_start"));
            resolve(form_validation);
        } else {
            document.getElementById("alert_time_start").style.display = "none";
        }

        if (select_channel == 0) {
            form_validation = false;
            document.getElementById("alert_multiselect").style.display = "block";
            document.getElementById("alert_multiselect").innerHTML = GLOBAL_LANG.whatsapp_broadcast_waba_validation_channel;
            window.scroll(0, document.getElementById("alert_multiselect"));
            resolve(form_validation);
        } else {
            document.getElementById("alert_multiselect").style.display = "none";
        }

        if (selectTemplate == 0) {
            form_validation = false;
            document.getElementById("alert_template").style.display = "block";
            document.getElementById("alert_template").innerHTML = GLOBAL_LANG.whatsapp_broadcast_waba_validation_template;
            window.scroll(0, document.getElementById("alert_template"));
            resolve(form_validation);
        } else {
            document.getElementById("alert_template").style.display = "none";
        }

        const header_placeholder = document.getElementById('parametro1');

        if (header_placeholder && header_placeholder.value.trim() === '') {
            form_validation = false;
            document.getElementById("header-placeholder").style.display = "block";
            window.scroll(0, document.getElementById("header-placeholder"));
            resolve(form_validation);
        } else {
            const header = document.getElementById("header-placeholder");
            if (header) {
                header.style.display = "none";
            }
        }

        resolve(form_validation);
    });
}


async function submit() {

    const validate_field = await validation();

    if (validate_field) {
        document.querySelector(".btn-success").disabled = true;
        $("form").unbind('submit').submit();
    }

}



function resize() {

    let height = 0;

    this.style.height = "auto";

    height = this.scrollHeight + 10;

    this.style.height = height + "px";

    this.disabled = true;
}


window.textTemplateSelectedHeader;
window.textTemplateSelectedHeaderType;
window.textTemplateSelectedBody;
window.textTemplateSelectedFooter;
window.textTemplateSelectedButtons;



function viewTemplate() {

    const options = document.querySelectorAll("#select_template option");


    for (option of options) {

        if (option.selected) {

            if (option.value != 0) {

                const message_template_header = document.getElementById("message-template-header");
                window.textTemplateSelectedHeader = option.dataset.header;
                window.textTemplateSelectedBody = option.dataset.text;
                window.textTemplateSelectedFooter = option.dataset.footer;
                window.textTemplateSelectedHeaderType = option.dataset.headerType;
                window.textTemplateSelectedButtons = option.dataset.buttons;
                document.querySelector("#bodyText").value = option.dataset.text;

                if (option.dataset.headerType != "1") {

                    $(".header-file").removeAttr("hidden");
                    $("#view-template").removeAttr("hidden")

                } else {
                    $(".header-file").attr("hidden", "");
                    $("#view-template").removeAttr("hidden");
                }

                return;

            }

        }
    }
}


function viewBroadcast(e) {
    window.location.href = "waba/view/" + e.id;
}

function editBroadcast(e) {
    window.location.href = "waba/edit/" + e.id;
}

function resend(e) {
    swal({
        title: GLOBAL_LANG.whatsapp_broadcast_waba_alert_resend_title,
        text: GLOBAL_LANG.whatsapp_broadcast_waba_alert_resend_text,
        type: "warning",
        showCancelButton: !0,
        buttonsStyling: !1,
        reverseButtons: true,
        confirmButtonClass: "btn btn-success",
        confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_waba_alert_resend_confirmButtonText,
        cancelButtonClass: "btn btn-danger",
        cancelButtonText: GLOBAL_LANG.whatsapp_broadcast_waba_alert_resend_cancelButtonText,
    }).then(t => {
        if (t.value == true) {
            $.post(document.location.origin + "/publication/whatsapp/broadcast/waba/resend/" + e.id, function (data) {
                if (data.success?.status == true) {
                    t.value && swal({
                        title: GLOBAL_LANG.whatsapp_broadcast_waba_alert_resend_two_title,
                        text: GLOBAL_LANG.whatsapp_broadcast_waba_alert_resend_two_text,
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });
                    $("#datatable-basic").DataTable().ajax.reload(null, false);
                }
            });
        }
    });
}

function cancelAllBroadcast() {
    let tokens = new FormData();

    Campaign.cancel_list.forEach(element => {
        tokens.append("tokens[]", element.token);
    });

    swal({
        title: GLOBAL_LANG.whatsapp_broadcast_waba_alert_group_delete_title,
        text: GLOBAL_LANG.whatsapp_broadcast_waba_alert_group_delete_text,
        type: "warning",
        showCancelButton: !0,
        buttonsStyling: !1,
        reverseButtons: true,
        cancelButtonClass: "btn btn-danger",
        cancelButtonText: GLOBAL_LANG.whatsapp_broadcast_waba_alert_group_delete_cancelButtonText,
        confirmButtonClass: "btn btn-success",
        confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_waba_alert_group_delete_confirmButtonText,
    }).then(t => {
        if (t.value == true) {

            $.ajax({
                type: "POST",
                url: document.location.origin + "/publication/whatsapp/broadcast/waba/cancelgroup",
                data: tokens,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    t.value && swal({
                        title: GLOBAL_LANG.whatsapp_broadcast_waba_alert_group_delete_two_title,
                        text: GLOBAL_LANG.whatsapp_broadcast_waba_alert_group_delete_two_text,
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });

                    Campaign.clear();
                    document.getElementById("btn-cancel").style.display = "none";
                    $("#datatable-basic").DataTable().ajax.reload(null, false);
                },
                error: function (data) {
                    t.value && swal({
                        title: GLOBAL_LANG.whatsapp_broadcast_waba_validation_cancel_title,
                        text: json.msg,
                        type: "error",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });
                }

            });

        }
    });
}


function cancelBroadcast(e) {

    swal({
        title: GLOBAL_LANG.whatsapp_broadcast_waba_alert_delete_title,
        text: GLOBAL_LANG.whatsapp_broadcast_waba_alert_delete_text,
        type: "warning",
        showCancelButton: !0,
        buttonsStyling: !1,
        reverseButtons: true,
        confirmButtonClass: "btn btn-success",
        confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_waba_alert_delete_confirmButtonText,
        cancelButtonClass: "btn btn-danger",
        cancelButtonText: GLOBAL_LANG.whatsapp_broadcast_waba_alert_delete_cancelButtonText,
    }).then(t => {
        if (t.value == true) {
            $.post("waba/cancel/" + e.id, function (data) {
                if (data.success?.status === true) {
                    t.value && swal({
                        title: GLOBAL_LANG.whatsapp_broadcast_waba_alert_delete_two_title,
                        text: GLOBAL_LANG.whatsapp_broadcast_waba_alert_delete_two_text,
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });

                    Campaign.clear();
                    document.getElementById("btn-cancel").style.display = "none";
                    $("#datatable-basic").DataTable().ajax.reload(null, false);
                } else {
                    t.value && swal({
                        title: GLOBAL_LANG.whatsapp_broadcast_waba_validation_cancel_title,
                        text: json.msg,
                        type: "error",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });
                }
            });
        }
    });
}


function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.waba.search) {
            document.getElementById("search").value = Filters.waba.search;
        }

        if (Filters.waba.input_search) {
            document.getElementById("input-search").value = Filters.waba.input_search;
        }

        if (Filters.waba.channel.length !== 0) {
            modalFilter();
            document.getElementById("check-select2").click();

            document.querySelectorAll("#mult-select2 .cust_").forEach((element, index) => {

                if (Filters.waba.channel.includes(element.value))
                    document.querySelectorAll("#mult-select2 .cust_")[index].click();
            })
        }

        if (Filters.waba.status) {
            modalFilter();
            document.getElementById("check-status").click();
            document.getElementById("select-status").value = Filters.waba.status;
        }

        if (Filters.waba.dt_start) {
            modalFilter();

            document.getElementById("check-date").click();

            document.getElementById("dt-end").disabled = false;
            document.getElementById("dt-end").type = "date";
            document.getElementById("dt-start").type = "date";

            document.getElementById("dt-start").value = Filters.waba.dt_start;
            document.getElementById("dt-end").value = Filters.waba.dt_end;
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
            url: "waba/find",
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
                mData: 'schedule'
            },
            {
                mData: 'name'
            },
            {
                mData: 'title'
            }
        ],
        columnDefs: [
            {
                targets: 0,
                render: function (data, type, full, meta) {
                    return `<div class='${full.status == 2 || full.status == 4 || full.status == 5 ? "checkbox-table-disabled" : "checkbox-table"}'><input type='checkbox' id='${full.token}' name='verify_check_box[]' class='check-box' ${full.status == 2 || full.status == 4 || full.status == 5 ? "disabled" : ""}></div>`;
                }
            },
            {
                targets: 4,
                render: function (data, type, full, meta) {
                    let ret = `<span class="badge badge-sm badge-secondary">${GLOBAL_LANG.whatsapp_broadcast_waba_datatable_column_status_processing}</span>`
                    switch (full.status) {
                        case '1':
                        case '6':
                            ret = `<span class="badge badge-sm badge-info">${GLOBAL_LANG.whatsapp_broadcast_waba_filter_status_sending}</span>`
                            break;
                        case '2':
                            ret = `<span class="badge badge-sm badge-success">${GLOBAL_LANG.whatsapp_broadcast_waba_datatable_column_status_send}</span>`
                            break;
                        case '3':
                            ret = `<span class="badge badge-sm badge-secondary">${GLOBAL_LANG.whatsapp_broadcast_waba_datatable_column_status_processing}</span>`
                            break;
                        case '5':
                            ret = `<span class="badge badge-sm badge-danger">${GLOBAL_LANG.whatsapp_broadcast_waba_datatable_column_status_called_off}</span>`
                            break;
                        case '7':
                            ret = `<span class="badge badge-sm badge-secondary">${GLOBAL_LANG.whatsapp_broadcast_waba_datatable_column_status_processing}</span>`
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
                                            <span>${GLOBAL_LANG.whatsapp_broadcast_waba_datatable_column_action_view}</span>
                                        </a>
                                        <a id="` + full.token + `" class="dropdown-item ${full.status == 4 ? 'table-action-deleted' : (full.status == 5 ? 'table-action-deleted' : (full.status == 2 ? 'table-action-deleted disabled' : 'table-action-delete'))}" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block"> 
                                                <i class="fas fa-times" style="font-size: 12pt; margin-left: 3px"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.whatsapp_broadcast_waba_datatable_column_action_cancel}</span>
                                        </a>
                                        <a id="${full.token}" class="dropdown-item table-action-resend"
                                                style="cursor: pointer; display:${(full.status != 3) ? containsSupport(USER_EMAIL) == true ? 'block' : 'none' : 'none'}">
                                            <div style="width: 24px; display: inline-block"> 
                                                <i class="fas fa-redo-alt" style="font-size: 12pt; margin-left: 3px"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.whatsapp_broadcast_waba_alert_resend}</span>
                                       </a>
                                        <a id="${full.token}" class="dropdown-item ${(full.status == 1 || full.status == 2 || full.status == 5 || full.status == 6 || checkTimeToEdit(full.schedule)) ? 'table-action-disabled disabled' : 'table-action-edit'}"  
                                            style="cursor: pointer;  display:none" title="${(full.status == 1 || full.status == 2 || full.status == 5 || full.status == 6) ? alertEditDatatable(full.status) : checkTimeToEdit(full.schedule) ? GLOBAL_LANG.whatsapp_broadcast_waba_datatables_edit_less_than_one_hour : GLOBAL_LANG.whatsapp_broadcast_waba_datatables_edit_column_action}">
                                            <div style="width: 24px; display: inline-block"> 
                                                <i class="far fa-edit" style="font-size: 12pt; margin-left: 3px"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.whatsapp_broadcast_waba_datatables_edit_column_action}</span>
                                        </a>
                                    </div>
                                </div>`
                }
            },
            {
                orderable: false, targets: [0]
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

            const waba = {
                search: $("#search").val(),
                input_search: $("#input-search").val(),
                channel: $("#multiselect2").val(),
                status: $("#select-status").val(),
                dt_start: $("#dt-start").val(),
                dt_end: $("#dt-end").val(),
            }

            let filter = localStorage.getItem("filters");

            filter = filter ? JSON.parse(filter) : {};
            filter.waba = waba;

            localStorage.setItem("filters", JSON.stringify(filter));
        }
    });
}

function containsSupport(email) {
    const emailLowerCase = email.toLowerCase();
    return emailLowerCase.startsWith('suporte') && emailLowerCase.endsWith('@talkall.com.br');
}

function callAlerts(alert) {

    switch (alert) {
        case "maxSize":
            swal({
                title: GLOBAL_LANG.config_alert_dropzone_maxSize_title,
                text: GLOBAL_LANG.config_alert_dropzone_maxSize_text,
                type: "warning",
                confirmButtonColor: '#3085d6',
                confirmButtonText: GLOBAL_LANG.config_alert_dropzone_maxSize_confirmButtonText,
                cancelButtonClass: "btn btn-secondary"
            });
            break;

        default:
            break;
    }
}

$("body").on("click", "#addProfile", () => {
    $("#inputFile").click();
});



$("body").on("change", "#inputFile", function () {

    cont += 1;

    if (this.files.length > 0) {
        document.querySelector("#gif_load").removeAttribute("hidden");

        const maxFileSize = 10 * 1024 * 1024;
        var type = "";

        if (this.files[0].size > maxFileSize) {
            callAlerts("maxSize");
        } else {

            const formData = new FormData();
            type = this.files[0].type;

            document.querySelector("#media-name").value = this.files[0].name;

            formData.append("filetoupload", this.files[0]);
            formData.append("media_id", Math.floor(Math.random() * 100000));
            formData.append("media_mime_type", type);

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

                previewProfile(JSON.parse(response), type)

            });

        }
    }
});


async function previewProfile(json, type) {
    if (type == "application/jpg" || type == "application/png" || type.includes("image")) {

        $("#url_file").val(json.url);
        document.querySelector("#img_header").setAttribute("src", json.url);
        document.querySelector("#gif_load").setAttribute("hidden", "");

    } else {

        switch (type) {
            case "application/pdf":
                document.querySelector("#img_header").setAttribute("src", "https://app.talkall.com.br/assets/img/panel/pdf_example.png");
                document.querySelector("#img_header").textContent = json.media_name;

                break;
            case "application/vnd.oasis.opendocument.text":
                document.querySelector("#img_header").setAttribute("src", "https://files.talkall.com.br:3000/f/A2AL8arg1W3kCdUuTFRpt0udmQ8pgNstPKPMGeTbBOB2MggNKPq3qwlSy75NGxpc.png");

                break;
            case "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet":
                document.querySelector("#img_header").setAttribute("src", "https://files.talkall.com.br:3000/f/2sDN75Ps8usP1ppFuyK3mYMe2D7gvNg2hY9mi1TCVf1CMAKZklYx0cjsUiCR7BDw.png");

                break;
            case "video/mp4":
                $("#url_file").val(json.url);
                document.querySelector("#img_header").setAttribute("src", json.url);
                document.querySelector("#gif_load").setAttribute("hidden", "");

                break;
            default:
                document.querySelector("#img_header").setAttribute("src", "https://files.talkall.com.br:3000/f/3u1czADfscaOC33v1hTJrxiu4uxfh6uW9cUU1pRRTXsKn3PoEk7gvJkzdIvFb0IW.jpg");

                break;
        }

        $("#url_file").val(json.url);
        document.querySelector("#gif_load").setAttribute("hidden", "");

    }


}


var textoComParatros;
var cont = 0;
var bodyParameters = 0;

////////////// ALTERA A MENSSAGEM DA CAMPANHA CONFORME AS VARIAVEIS INSERIDAS
async function alterParametros(preview) {
    var headerText = document.querySelector("#tex_area_view_templateHeader");
    var headerImg = document.querySelector("#imgHeaderText");
    var headerVideo = document.querySelector("#video_header");
    var headerDoc = document.querySelector("#doc_header");
    var bodyText = document.querySelector("#tex_area_view_template");
    var footerText = document.querySelector("#tex_area_view_templateFooter");
    var selectTemplate = document.querySelector("#select_template").value;
    var imgFixedHeader = document.querySelector("#img_fixed_header");

    if (preview && selectTemplate != 0) {
        $("#preViewClose").removeAttr("hidden");
        $("#preViewShow").attr("hidden", "");
        $(".template-whatsapp-broadcast-view").removeAttr("hidden");
        textoComParatros = window.textTemplateSelectedBody;
    } else {
        $("#preViewClose").attr("hidden", "");
        $("#preViewShow").removeAttr("hidden");
        $(".template-whatsapp-broadcast-view").attr("hidden", "");
        textoComParatros = window.textTemplateSelectedBody;
    }

    for (var i = 1; i <= bodyParameters.length; i++) {
        if ($(`#parametro${i}`).val() != "") {
            textoComParatros = textoComParatros.replace(`{{${i}}}`, $("#parametro" + i).val());
        }
    }

    if (window.textTemplateSelectedHeaderType == "1") {

        if (headerText != null) {
            imgFixedHeader.setAttribute("hidden", "");
            headerImg.setAttribute("hidden", "");
            headerVideo.setAttribute("hidden", "");
            headerDoc.setAttribute("hidden", "");
            headerText.removeAttribute("hidden");
            headerText.innerText = window.textTemplateSelectedHeader;
        }

    } else {

        if (headerImg != null) {

            if (document.querySelector("#url_file") != null) {

                if (document.querySelector("#url_file").value == "") {
                    imgFixedHeader.removeAttribute("hidden");
                    headerText.setAttribute("hidden", "");
                    headerImg.setAttribute("hidden", "");
                    headerVideo.setAttribute("hidden", "");
                    headerDoc.setAttribute("hidden", "");
                    headerImg.setAttribute("src", "");
                    headerVideo.setAttribute("src", "");
                    headerDoc.setAttribute("src", "");
                    headerDoc.setAttribute("href", "");

                } else {
                    imgFixedHeader.setAttribute("hidden", "");
                    headerText.setAttribute("hidden", "");

                    if (document.querySelector("#type_file").innerHTML == 'PDF') {

                        const pdfjsLib = window['pdfjs-dist/build/pdf'];
                        const media_url = document.querySelector("#url_file").value;
                        pdfjsLib.GlobalWorkerOptions.workerSrc = document.location.origin + "/assets/lib/build_pdf/pdf_build_pdf.worker.js";

                        const loadingTask = pdfjsLib.getDocument(media_url);
                        const pdf = await loadingTask.promise

                        const pageNumber = 1;
                        const page = await pdf.getPage(pageNumber)

                        let desiredWidth = 275;
                        let viewport = page.getViewport({ scale: 1, });
                        let scale = desiredWidth / viewport.width;
                        let scaledViewport = page.getViewport({ scale: scale, });

                        let canvas = document.getElementById("pdf-canva");

                        let context = canvas.getContext('2d');
                        canvas.height = 180;
                        canvas.width = 275;

                        const renderContext = {
                            canvasContext: context,
                            viewport: scaledViewport
                        };

                        const renderTask = page.render(renderContext);
                        await renderTask.promise

                        let pdf_link = document.getElementById("doc_header");
                        pdf_link.href = media_url;
                        pdf_link.setAttribute("target", "_blank");
                        pdf_link.appendChild(canvas);

                        headerImg.setAttribute("hidden", "");
                        headerVideo.setAttribute("hidden", "");
                        headerImg.setAttribute("src", "");
                        headerVideo.setAttribute("src", "");
                        headerDoc.setAttribute("src", "");
                        headerDoc.setAttribute("src", document.querySelector("#url_file").value);
                        headerDoc.setAttribute("href", document.querySelector("#url_file").value);
                        headerDoc.removeAttribute("hidden");

                    } else if (document.querySelector("#type_file").innerHTML == 'JPG') {
                        headerVideo.setAttribute("hidden", "");
                        headerDoc.setAttribute("hidden", "");
                        headerImg.setAttribute("src", "");
                        headerVideo.setAttribute("src", "");
                        headerDoc.setAttribute("src", "");
                        headerImg.removeAttribute("hidden");
                        headerImg.setAttribute("src", document.querySelector("#url_file").value);

                    } else {
                        headerImg.setAttribute("hidden", "");
                        headerDoc.setAttribute("hidden", "");
                        headerImg.setAttribute("src", "");
                        headerVideo.setAttribute("src", "");
                        headerDoc.setAttribute("src", "");
                        headerVideo.removeAttribute("hidden");
                        headerVideo.setAttribute("src", document.querySelector("#url_file").value);
                    }
                }
            }
        }
    }

    bodyText.innerText = textoComParatros;

    if (window.textTemplateSelectedFooter != "") {
        footerText.removeAttribute("hidden");
        footerText.innerText = window.textTemplateSelectedFooter;
    } else {
        footerText.setAttribute("hidden", "");
        footerText.innerText = "";
    }

    jsonButtonsView = window.textTemplateSelectedButtons;

    $("html, body").animate({
        scrollTop: $(
            'html, body').get(0).scrollHeight
    }, 0);

    $('.wa-body').scrollTop($("#tex_area_view_template").height());

    cont = 1;
}

var first = true;
///////// VALIDA O TIPO DO PARAMETRO PARA RENDERIZAR O COMPONETE
function ValidationType(id) {
    let idComponet = document.querySelector(`#select_type_parameters${id}`).value

    if (document.getElementById(`alert_field${id}`) != null) {
        document.getElementById(`alert_field${id}`).remove();
    }

    if (idComponet != '0') {
        switch (idComponet) {
            case "text":
                document.querySelector(`#parametro${id}`).remove();

                document.querySelector(`#part-${id}`).innerHTML += `
                <input type="text" class="form-control " name="parametro${(id)}" id="parametro${(id)}" maxlength="50" value="">`;

                document.querySelector(`#parametro${id}`).setAttribute("type", "text");
                document.querySelector(`#parametro${id}`).setAttribute("placeholder", `${GLOBAL_LANG.whatsapp_broadcast_waba_pl_text}`);
                document.querySelector(`#parametro${id}`).value = "";
                $(`#parametro${id}`).unmask("");
                break;
            case "currency":
                document.querySelector(`#parametro${id}`).remove();

                document.querySelector(`#part-${id}`).innerHTML += `
                <input class="form-control " name="parametro${(id)}${(id)}" id="parametro${(id)}" maxlength="50" value="" onkeyup="FormatCurrency(${(id)})">`;

                document.querySelector(`#parametro${id}`).setAttribute("type", "");
                document.querySelector(`#parametro${id}`).setAttribute("placeholder", GLOBAL_LANG.whatsapp_broadcast_waba_add_currency_placeholder);
                document.querySelector(`#parametro${id}`).value = "";
                break;
            case "date":
                document.querySelector(`#parametro${id}`).remove();

                document.querySelector(`#part-${id}`).innerHTML += `
                <input type="text" class="form-control " name="parametro${(id)}" id="parametro${(id)}" maxlength="50" value="">`;

                document.querySelector(`#parametro${id}`).setAttribute("type", "text");
                document.querySelector(`#parametro${id}`).setAttribute("placeholder", "DD/MM/YYYY");
                document.querySelector(`#parametro${id}`).value = "";
                $(`#parametro${id}`).mask("00/00/0000");
                break;
            case "clientsData":
                document.querySelector(`#parametro${id}`).remove();

                document.querySelector(`#part-${id}`).innerHTML += `                     
                <select class="form-control" name="parametro${id}" id="parametro${id}">    
                                <option value="0">${GLOBAL_LANG.whatsapp_broadcast_waba_add_select}</option>
                                <option value="%numero%">${GLOBAL_LANG.whatsapp_broadcast_waba_add_telephone}</option>
                                <option value="%nome%">${GLOBAL_LANG.whatsapp_broadcast_waba_add_Name}</option>
                                <option value="%email%">${GLOBAL_LANG.whatsapp_broadcast_waba_add_email}</option>
                </select>`;
                break;
            default:
                break;
        }


    } else {
        document.querySelector(`#parametro${id}`).setAttribute("disabled", "");
    }
}

function createTemplateHeaderParameters(header_type) {
    const header_parameter_type = document.getElementById("header_temp_param");

    const form_group = document.createElement("div");
    form_group.className = "form-group";

    const input_file = document.createElement("input");
    input_file.type = "file";
    input_file.id = "inputFile";
    input_file.style.display = "none";

    if (header_type == 3) input_file.accept = ".jpg";
    if (header_type == 5) input_file.accept = ".mp4";
    if (header_type == 10) input_file.accept = ".pdf";

    input_file.addEventListener("change", validationFormat);

    const btn_import = document.createElement("button");
    btn_import.id = "btn_import";
    btn_import.className = "btn-import";
    btn_import.textContent = GLOBAL_LANG.input_file;

    btn_import.addEventListener("click", function () {
        $("#inputFile").click();
        if (document.getElementById("alert_header") != null) {
            document.getElementById("alert_header").remove();
        }
    });

    const type_file_label = document.createElement("label");
    type_file_label.id = "type_file";
    type_file_label.className = "form-control-label type-file-label";

    const view_files_image = document.createElement("div");
    view_files_image.id = "view_files_image";
    view_files_image.style.marginBottom = "-15px";
    view_files_image.className = "col-12 header-file";

    if (header_type == 3) {
        type_file_label.textContent = "JPG";

        var img_header = document.createElement("img");
        img_header.src = "";
        img_header.id = "img_header";
        img_header.className = "figure-img img-fluid rounded img-header";
    }

    if (header_type == 5) {
        type_file_label.textContent = "MP4";

        var img_header = document.createElement("video");
        img_header.src = "";
        img_header.id = "img_header";
        img_header.controls = true;
        img_header.className = "figure-img img-fluid rounded img-header";

    }

    if (header_type == 10) {
        type_file_label.textContent = "PDF";

        var img_header = document.createElement("a");
        img_header.src = "";
        img_header.id = "img_header";
        img_header.className = "figure-img img-fluid rounded img-header";
    }


    const url_file_input = document.createElement("input");
    url_file_input.value = "";
    url_file_input.id = "url_file";
    url_file_input.name = "url_file";
    url_file_input.style.display = "none";

    form_group.appendChild(img_header);
    view_files_image.appendChild(form_group);
    header_parameter_type.appendChild(input_file);
    header_parameter_type.appendChild(url_file_input);
    header_parameter_type.appendChild(btn_import);
    header_parameter_type.appendChild(type_file_label);
    header_parameter_type.appendChild(view_files_image);

    document.querySelector("#header").removeAttribute('hidden');
    document.querySelector("#label_header").removeAttribute("hidden");
}

function createTemplateBodyParameters(count_body_parameters) {

    for (let i = 0; i < count_body_parameters; i++) {
        const div_parameter_type = document.querySelector("#body_temp_param");

        const select_parameter_type = document.createElement("div");
        select_parameter_type.id = `element${i + 1}`;

        const select_title = document.createElement("label");
        select_title.style.marginTop = "30px";
        select_title.className = "form-control-label";
        select_title.textContent = GLOBAL_LANG.whatsapp_broadcast_waba_add_type_parameter;

        const select_type = document.createElement("select");
        select_type.className = "form-control";
        select_type.name = `select_type_parameters${i + 1}`;
        select_type.id = `select_type_parameters${i + 1}`;
        select_type.setAttribute("data-id", i + 1);

        select_type.addEventListener("change", function () {
            ValidationType(select_type.getAttribute("data-id"));
        });

        const default_option = document.createElement("option");
        default_option.value = "0";
        default_option.textContent = GLOBAL_LANG.whatsapp_broadcast_waba_add_select;

        const text_option = document.createElement("option");
        text_option.value = "text";
        text_option.textContent = GLOBAL_LANG.whatsapp_broadcast_waba_add_text;

        const currency_option = document.createElement("option");
        currency_option.value = "currency";
        currency_option.textContent = GLOBAL_LANG.whatsapp_broadcast_waba_add_currency;

        const date_option = document.createElement("option");
        date_option.value = "date";
        date_option.textContent = GLOBAL_LANG.whatsapp_broadcast_waba_add_date;

        const clients_data_option = document.createElement("option");
        clients_data_option.value = "clientsData";
        clients_data_option.textContent = GLOBAL_LANG.whatsapp_broadcast_waba_add_clientsData;

        select_type.appendChild(default_option);
        select_type.appendChild(text_option);
        select_type.appendChild(currency_option);
        select_type.appendChild(date_option);
        select_type.appendChild(clients_data_option);
        select_parameter_type.appendChild(select_title);
        select_parameter_type.appendChild(select_type);
        div_parameter_type.appendChild(select_parameter_type);

        const body_template_parameters = document.querySelector("#body_template_parameters");

        const parameter_field = document.createElement("div");
        parameter_field.id = `part-${i + 1}`;

        const parameter_field_title = document.createElement("label");
        parameter_field_title.style.marginTop = "30px";
        parameter_field_title.className = "form-control-label";
        parameter_field_title.textContent = `${GLOBAL_LANG.whatsapp_broadcast_waba_add_parameter} {{${i + 1}}}`;

        const parameter_input = document.createElement("input");
        parameter_input.value = "";
        parameter_input.type = "text";
        parameter_input.maxLength = 50;
        parameter_input.disabled = true;
        parameter_input.id = `parametro${i + 1}`;
        parameter_input.name = `parametro${i + 1}`;
        parameter_input.className = "form-control";

        parameter_field.appendChild(parameter_field_title);
        parameter_field.appendChild(parameter_input);
        body_template_parameters.appendChild(parameter_field);
    }
    document.querySelector("#header_template_parameters").removeAttribute("hidden");
    document.querySelector("#label_body").removeAttribute("hidden");
}

function createTemplateButtonPreview(buttons) {

    document.querySelector("#btn_preview")?.remove();
    document.querySelector(".button-hr")?.remove();
    document.querySelector(".buttons-phone-url")?.remove();

    if (buttons != null) {
        const preview_template_body = document.querySelector(".align-self-end");
        const input_parameters = document.querySelector("#parameters");

        const btn_preview = document.createElement("div");
        btn_preview.id = "btn_preview";
        btn_preview.style = buttons.length == 2 ? "display: inline-flex;" : "display: inline-block";

        if (buttons[0]["type"] == "QUICK_REPLY") {
            for (let i = 0; i < buttons.length; i++) {
                let only_one_button = buttons.length == 1 ? true : false;
                let last_button = i == 2 ? true : false;

                const preview_button = document.createElement("button");
                preview_button.className = "preview-button";
                preview_button.textContent = buttons[i]["text"];

                if (only_one_button) {
                    preview_button.style.width = "285px";
                    preview_button.disabled = true;
                    preview_button.className = `preview-button2`;
                } else if (last_button) {
                    preview_button.style.width = "285px";
                    preview_button.disabled = true;
                    preview_button.className = `preview-button${i}`;
                } else {
                    preview_button.style.width = "140px";
                    preview_button.disabled = true;
                    preview_button.className = `preview-button${i}`;
                }

                const tex_button = document.createElement("input");
                tex_button.hidden = true;
                tex_button.name = `tex_button${i}`;
                tex_button.value = buttons[i]["text"];

                const sub_type_button = document.createElement("input");
                sub_type_button.hidden = true;
                sub_type_button.name = `button_sub_type${i}`;
                sub_type_button.value = buttons[i]["type"];

                btn_preview.appendChild(preview_button);
                input_parameters.appendChild(sub_type_button);
                input_parameters.appendChild(tex_button);
            }

        } else {
            const button_hr = document.createElement("hr");
            button_hr.className = "button-hr";
            const box_phone_url = document.createElement("div");
            box_phone_url.className = "buttons-phone-url";

            for (var i = 0; i < buttons.length; i++) {
                switch (buttons[i].type) {
                    case "PHONE_NUMBER":
                        const button_phone = document.createElement("span");
                        button_phone.innerText = buttons[i].text;
                        const element_phone = document.createElement("i");
                        element_phone.className = "fas fa-phone";
                        button_phone.prepend(element_phone);
                        box_phone_url.appendChild(button_phone);
                        break;
                    case "URL":
                        const button_url = document.createElement("span");
                        button_url.innerText = buttons[i].text;
                        const element_url = document.createElement("i");
                        element_url.className = "fas fa-external-link-alt";
                        button_url.prepend(element_url);
                        box_phone_url.appendChild(button_url);
                        break;
                    default:
                        break;
                }
            }
        }
        preview_template_body.append(btn_preview);
    }
}

function createTemplateUrlParameter(buttons) {
    const div_url = document.getElementById("buttons_temp_url");
    const div_url_parameter = document.getElementById("buttons_temp_param");

    const url_input_title = document.createElement("label");
    url_input_title.style.marginTop = "30px";
    url_input_title.id = "url_input_title";
    url_input_title.className = "form-control-label";
    url_input_title.innerHTML = GLOBAL_LANG.whatsapp_broadcast_waba_add_url;

    const url_input = document.createElement("input");
    url_input.disabled = true;
    url_input.value = buttons;
    url_input.className = "form-control btnParameters";

    const url_parameter_input_title = document.createElement("label");
    url_parameter_input_title.style.marginTop = "30px";
    url_parameter_input_title.id = "url_parameter_input_title";
    url_parameter_input_title.className = "form-control-label";
    url_parameter_input_title.innerHTML = GLOBAL_LANG.whatsapp_broadcast_waba_add_url_parameter;

    const url_parameter = document.createElement("input");
    url_parameter.maxLength = 50;
    url_parameter.placeholder = "{{}}";
    url_parameter.id = "parameter_button_url";
    url_parameter.name = "parameter_button_url";
    url_parameter.className = "form-control btnParameters";

    const url = document.createElement("input");
    url.hidden = true;
    url.name = "url_input";
    url.value = buttons.split('{{').shift();

    div_url.appendChild(url_input_title);
    div_url.appendChild(url_input);
    div_url.appendChild(url);

    div_url_parameter.appendChild(url_parameter_input_title);
    div_url_parameter.appendChild(url_parameter);

    document.querySelector("#label_buttons").removeAttribute("hidden");
    document.querySelector("#buttons_template_parameters").removeAttribute("hidden");
}

function alertErrors(data) {
    if (data.errors?.code == "TA-023") {
        swal({
            title: GLOBAL_LANG.whatsapp_broadcast_waba_error_title,
            text: `${GLOBAL_LANG.whatsapp_broadcast_waba_error_ta023} (${data.errors.code})`,
            type: "error",
            buttonsStyling: !1,
            confirmButtonClass: "btn btn-danger"
        });
    }

    if (data.errors?.code == "TA-024") {
        swal({
            title: GLOBAL_LANG.whatsapp_broadcast_waba_error_title,
            text: `${GLOBAL_LANG.whatsapp_broadcast_waba_error_ta024} (${data.errors.code})`,
            type: "error",
            buttonsStyling: !1,
            confirmButtonClass: "btn btn-danger"
        });
    }
}

$(document).ready(function () {

    var telaPreView = window.location.href.includes("view");
    var telaEdit = window.location.href.includes("edit");

    if (telaPreView || telaEdit) {

        var id_template = document.querySelector("#id_template").value;
        var parametrosJson = JSON.parse(document.querySelector("#json_parameters").value);

        $.ajax({
            type: "GET",
            url: "/publication/whatsapp/broadcast/waba/listtemplate/" + id_template,
            success: async function (data) {

                const parametrosView = data[0]["text_body"].includes('{{');
                const buttonsView = JSON.parse(data[0].buttons);
                const headerTypeView = data[0]["header_type"];
                const headerView = data[0]["header"];
                const footerView = data[0]["text_footer"];
                const qtdParametrosView = data[0]["text_body"].split('{{').length - 1;

                // tenho q ver pra que isso é usado
                const box_template = document.querySelector(".box-broadcast-approval");
                const text_body_div = document.createElement("div");
                text_body_div.className = "textarea-status";

                // Cabeçalho
                if (headerTypeView != null && headerTypeView != "1") {

                    var link = '';
                    var parametrosHeader = [];
                    JSON.parse(parametrosJson).forEach(c => { c["type"] == "header" ? parametrosHeader.push(c["parameters"]) : null });

                    if (parametrosHeader.length > 0) {
                        switch (parametrosHeader[0][0]["type"]) {
                            case "image":
                                link = parametrosHeader[0][0]["image"]["link"];
                                break;
                            case "document":
                                link = parametrosHeader[0][0]["document"]["link"];
                                break;
                            case "video":
                                link = parametrosHeader[0][0]["video"]["link"];
                                break;
                            default:
                                break;
                        }
                    }

                    switch (headerTypeView) {
                        case "3":
                            const img_header = document.createElement("img");
                            img_header.id = "imgHeaderText";
                            img_header.src = link;
                            box_template.appendChild(img_header);

                            break;
                        case "5":
                            const video_header = document.createElement("video");
                            const source_video = document.createElement("source");
                            source_video.src = link;
                            video_header.id = "video_header";
                            video_header.controls = true;
                            video_header.appendChild(source_video);
                            box_template.appendChild(video_header);

                            break;
                        case "10":
                            const pdfjsLib = window['pdfjs-dist/build/pdf'];
                            const media_url = parametrosHeader[0][0]["document"]["link"];
                            pdfjsLib.GlobalWorkerOptions.workerSrc = document.location.origin + "/assets/lib/build_pdf/pdf_build_pdf.worker.js";

                            const loadingTask = pdfjsLib.getDocument(media_url);
                            const pdf = await loadingTask.promise

                            const pageNumber = 1;
                            const page = await pdf.getPage(pageNumber)

                            let desiredWidth = 275;
                            let viewport = page.getViewport({ scale: 1, });
                            let scale = desiredWidth / viewport.width;
                            let scaledViewport = page.getViewport({ scale: scale, });

                            let canvas = document.createElement("canvas");
                            canvas.id = "pdf-canva";

                            let context = canvas.getContext('2d');
                            canvas.height = 180;
                            canvas.width = 275;

                            const renderContext = {
                                canvasContext: context,
                                viewport: scaledViewport
                            };

                            const renderTask = page.render(renderContext);
                            await renderTask.promise

                            let pdf_link = document.createElement("a");
                            pdf_link.href = media_url;
                            pdf_link.setAttribute("target", "_blank");
                            pdf_link.appendChild(canvas);
                            box_template.appendChild(pdf_link);

                            break;
                        default:
                            break;
                    }
                } else if (headerTypeView != null) {
                    const text_header = document.createElement("span");
                    text_header.className = "tex-area";
                    text_header.id = "tex_area_view_templateHeader";
                    text_header.innerHTML = headerView;
                    box_template.appendChild(text_header);
                }

                // Corpo com Parâmetros
                if (parametrosView == true) {

                    const text_body = document.createElement("p");
                    text_body.className = "tex-area";
                    text_body.id = "tex_area_view_template"
                    text_body.innerHTML = data[0]["text_body"];

                    var parametrosBody = [];
                    JSON.parse(parametrosJson).forEach(c => { c["type"] == "body" ? parametrosBody.push(c["parameters"]) : null });

                    for (var i = 0; i < qtdParametrosView; i++) {
                        if (parametrosBody[0][i]["type"] == "date")
                            text_body.innerHTML = text_body.innerHTML.replace(`{{${(i + 1)}}}`, parametrosBody[0][i]["date"]);
                        else if (parametrosBody[0][i]["type"] == "currency")
                            text_body.innerHTML = text_body.innerHTML.replace(`{{${(i + 1)}}}`, parametrosBody[0][i]["currency"]["amount_1000"]);
                        else
                            text_body.innerHTML = text_body.innerHTML.replace(`{{${(i + 1)}}}`, parametrosBody[0][i]["text"]);
                    }

                    text_body_div.appendChild(text_body);
                    box_template.appendChild(text_body_div);
                } else {
                    const text_body = document.createElement("p");
                    text_body.className = "tex-area";
                    text_body.id = "tex_area_view_template"
                    text_body.innerHTML = data[0]["text_body"];
                    text_body_div.appendChild(text_body);
                    box_template.appendChild(text_body_div);
                }

                // Rodapé
                if (footerView !== null && footerView !== "") {
                    const text_footer = document.createElement("span");
                    text_footer.className = "tex-area";
                    text_footer.id = "tex_area_view_templateFooter";
                    text_footer.innerHTML = footerView;
                    text_body_div.appendChild(text_footer);
                }

                // Botões
                if (buttonsView != null && buttonsView != "") {
                    if (buttonsView[0].type == "QUICK_REPLY") {

                        const buttons_qty = buttonsView.length;
                        const box_buttons = document.querySelector(".buttons-quick-reply");
                        const button_reply_a = document.createElement("button");
                        const button_reply_b = document.createElement("button");
                        const button_reply_c = document.createElement("button");
                        button_reply_a.disabled = true;
                        button_reply_b.disabled = true;
                        button_reply_c.disabled = true;

                        switch (buttons_qty) {
                            case 1:
                                button_reply_a.className = "btn-quick-reply-c";
                                button_reply_a.innerHTML = buttonsView[0].text;
                                box_buttons.appendChild(button_reply_a);

                                break;
                            case 2:
                                button_reply_a.className = "btn-quick-reply-a";
                                button_reply_b.className = "btn-quick-reply-b";
                                button_reply_a.innerHTML = buttonsView[0].text;
                                button_reply_b.innerHTML = buttonsView[1].text;
                                box_buttons.appendChild(button_reply_a);
                                box_buttons.appendChild(button_reply_b);

                                break;
                            case 3:
                                button_reply_a.className = "btn-quick-reply-a";
                                button_reply_b.className = "btn-quick-reply-b";
                                button_reply_c.className = "btn-quick-reply-c";
                                button_reply_a.innerHTML = buttonsView[0].text;
                                button_reply_b.innerHTML = buttonsView[1].text;
                                button_reply_c.innerHTML = buttonsView[2].text;
                                box_buttons.appendChild(button_reply_a);
                                box_buttons.appendChild(button_reply_b);
                                box_buttons.appendChild(button_reply_c);

                                break;
                            default:
                                break;
                        }
                    } else {
                        const button_hr = document.createElement("hr");
                        button_hr.className = "button-hr";
                        const box_phone_url = document.createElement("div");
                        box_phone_url.className = "buttons-phone-url";

                        for (var i = 0; i < buttonsView.length; i++) {
                            switch (buttonsView[i].type) {
                                case "PHONE_NUMBER":
                                    const button_phone = document.createElement("span");
                                    button_phone.innerText = buttonsView[i].text;
                                    const element_phone = document.createElement("i");
                                    element_phone.className = "fas fa-phone";
                                    button_phone.prepend(element_phone);
                                    box_phone_url.appendChild(button_phone);
                                    break;
                                case "URL":
                                    const button_url = document.createElement("span");
                                    button_url.innerText = buttonsView[i].text;
                                    const element_url = document.createElement("i");
                                    element_url.className = "fas fa-external-link-alt";
                                    button_url.prepend(element_url);
                                    box_phone_url.appendChild(button_url);
                                    break;
                                default:
                                    break;
                            }
                        }
                        box_template.appendChild(button_hr);
                        box_template.appendChild(box_phone_url);
                    }
                }
                $('.wa-body').scrollTop($("#tex_area_view_template").height());
            }
        });
    }

    const multiselectAdd = document.getElementById("multiselect");
    const edit = document.getElementById("edit");
    const block = document.getElementById("block");

    if (multiselectAdd != null && !edit) {

        //** ADD **//

        let select = new MSFmultiSelect(
            document.querySelector('#multiselect'), {
            theme: 'theme1',
            selectAll: true,
            searchBox: true,
            width: "100%",
            height: 47,
            placeholder: "Selecionar",
            onChange: function (checked, value, instance) {
                if (select == "") select = value;
            },
        });


        $('.time').mask(maskBehavior, spOptions);


        $("#select_template").on("change", function () {
            generatorTemplate(this.value);
        });


        $("#toggle-segmented-group").on("click", function () {
            if ($("#toggle-segmented-group").is(":checked")) {
                $("#segmented_group").show();
            } else {
                $("#segmented_group").hide();
                $("#select_segmented_group").val(0)
            };
        });


        advanceMin();

        $("form").submit(event => event.preventDefault());

        document.querySelector(".btn-success").addEventListener("click", submit);
        document.getElementById("select_template").addEventListener("change", viewTemplate);
        document.getElementById("select_channel").addEventListener("change", getGroups);



    } else if (edit) {

        $('.time').mask(maskBehavior, spOptions);

        $("form").submit(event => event.preventDefault());
        document.querySelector(".btn-success").addEventListener("click", submit);

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

            $("#count_row").text(cancel_list);
        });

        $("#btn-cancel").on("click", function () {
            cancelAllBroadcast();
        });

        $("#datatable-basic").on("click", ".table-action-delete", function () {
            cancelBroadcast(this);
        });

        $("#datatable-basic").on("click", ".table-action-view", function () {
            viewBroadcast(this);
        });

        $("#datatable-basic").on("click", ".table-action-resend", function () {
            resend(this);
        });

        $("#datatable-basic").on("click", ".table-action-edit", function () {
            editBroadcast(this);
        });

        $('#sendEmailExport').on('click', () => modalExport());
        $("#modalFilter").one("click", () => modalFilter());

    }

    getTemplate();
    $('#select_channel').click(function () {
        getTemplate();
        $('#select_template option[value=0]').prop('selected', 'selected').change();
    });

    if (block != null && block.value == 1) {
        $("#modal-no-registers").modal({ backdrop: 'static', keyboard: false });
    }

    viewTemplate();

    $("#preview-campaign").on("click", () => modalPreviewCampaign());


});

function generatorTemplate(id_template) {

    window.textTemplateSelectedButtons = "";
    document.querySelector("#body_temp_param").innerHTML = "";
    document.querySelector("#header_temp_param").innerHTML = "";
    document.querySelector("#label_body").setAttribute("hidden", "");
    document.querySelector("#label_header").setAttribute("hidden", "");
    document.querySelector("#label_buttons").setAttribute("hidden", "");
    document.querySelector("#body_template_parameters").innerHTML = "";
    document.querySelector("#header_template_parameters").setAttribute("hidden", "");
    document.querySelector("#buttons_template_parameters").setAttribute("hidden", "");
    if (document.getElementById("btn_preview") != null) document.getElementById("btn_preview").remove();
    if (document.querySelectorAll(".btnParameters") != null) document.querySelectorAll(".btnParameters").forEach(c => c.remove());
    if (document.querySelector("#url_input_title") != null) document.querySelector("#url_input_title").remove();
    if (document.querySelector("#url_parameter_input_title") != null) document.querySelector("#url_parameter_input_title").remove();

    if (id_template != 0) {
        $.get(`${document.location.origin}/publication/whatsapp/broadcast/waba/listtemplate/${id_template}`, (data) => {

            let header_type = data[0]["header_type"];
            let header = data[0]["header"];
            let footer = data[0]["text_footer"];
            let buttons = JSON.parse(data[0]["buttons"]);
            bodyParameters = data[0]["text_body"].split('{{');
            bodyParameters.shift();

            if (header_type != "1" && header_type != null) {
                createTemplateHeaderParameters(header_type);
            } else if (header_type == null) {
                document.querySelector("#img_fixed_header").setAttribute("hidden", "");
                document.querySelector("#imgHeaderText").setAttribute("hidden", "");
                document.querySelector("#video_header").setAttribute("hidden", "");
                document.querySelector("#doc_header").setAttribute("hidden", "");
                document.querySelector("#tex_area_view_templateHeader").setAttribute("hidden", "");
            }

            if (header != null && header.includes("{{")) {
                createHandlebarsHeader(header);
            }

            if (bodyParameters.length != 0) {
                createTemplateBodyParameters(bodyParameters.length);
            }

            if (buttons) {
                for (let i = 0; i < buttons.length; i++) {
                    if (buttons[i].url) {
                        let exist_url_parameter = buttons[i].url.includes("{{1}}");
                        if (exist_url_parameter) createTemplateUrlParameter(buttons[i].url);
                    }
                }
                createTemplateButtonPreview(buttons);
            } else {
                createTemplateButtonPreview(buttons);
            }

            document.querySelector("#headerType").value = header_type;
            document.querySelector("#headerText").value = header;
            document.querySelector("#footerText").value = footer;
            document.querySelector("#bodyParameters").value = bodyParameters.length;
            document.querySelector("#buttonsParameters").value = buttons ? buttons.length : 0;
        });
    }

    alterParametros(false);

    $("#body_template_parameters, #header_temp_param").click(function () {
        alterParametros(false);
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
        case 1:
            column = "schedule";
            break;
        case 2:
            column = "channel";
            break;
        case 3:
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
        &type=publicationWhatsappBroadcastWaba`, function (response) {
        Swal.fire({
            title: GLOBAL_LANG.whatsapp_broadcast_waba_alert_export_title,
            text: GLOBAL_LANG.whatsapp_broadcast_waba_alert_export_text,
            type: 'success',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: GLOBAL_LANG.whatsapp_broadcast_waba_alert_export_confirmButtonText
        });
    });
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
    timeline_text.innerText = GLOBAL_LANG.whatsapp_broadcast_waba_resent_broadcast_timeline_view;

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


function getTemplate() {

    let templateCLasse = $('[name=select_template]').find('option').find('class')['prevObject'];
    let idchannel = $('option:selected', 'select[name=select_channel]').attr('channel');

    $('[name=select_template]').find('option').hide()
    templateCLasse.each(function (index, element) {
        if ($(this).attr('class') == 'channel_' + idchannel) {
            $(".channel_" + idchannel).show();
        }
        $(".channel_0").show();
    });

}

function FormatCurrency(id) {

    if (localStorage.getItem("language") == "pt_br") {
        $(`#parametro${id}`).maskMoney({
            prefix: "R$ ",
            decimal: ",",
            thousands: "."
        });
    } else {
        $(`#parametro${id}`).maskMoney({
            prefix: "$ ",
            decimal: ".",
            thousands: ","
        });
    }

}


// function ValidationCurrency(id) {

//     if ($(`#parametro${id}`).val().split('.').length <= 1) {

//         $(`#parametro${id}`).val($(`#parametro${id}`).val() + ".00")

//     }

// }



if (document.querySelector("#date_start") != null) {
    document.querySelector("#date_start").addEventListener("click", c => {

        var calendar = document.querySelector(".datepicker-days");

        calendar.addEventListener("click", c => {
            document.querySelector(".datepicker-dropdown").remove();
        });
    });
}


function validationFormat() {
    let type = document.querySelector("#inputFile").accept.split('.');
    let file = document.querySelector("#inputFile").value.split('.').pop();

    if (!type.includes(file) && file) {

        document.querySelector("#inputFile").value = "";
        document.querySelector("#img_header").src = "";


        let title = GLOBAL_LANG.whatsapp_broadcast_waba_validation_cancel_title;
        let message = GLOBAL_LANG.whatsapp_broadcast_waba_validation_cancel_message;

        Swal.fire(
            title,
            message,
            'Error'
        )
    }
}


async function modalPreviewCampaign() {
    const validate = await validation();

    if (!validate) {
        $('#modal-preview-campaign').modal('hide');
        return;
    } else {
        $('#inputFone').val('');
        $('#modal-preview-campaign').modal('show');
        $('#alert_number_fone').hide();
        document.getElementById("btn-send-preview").disabled = false;
        $('#inputFone').mask('(99)99999-9999');
    }
}


$('#btn-send-preview').on('click', function () {

    let foneNumber = $('#inputFone').val();

    if (foneNumber.length != 0) {
        document.getElementById("btn-send-preview").disabled = true;
        checkPreview();
    } else {
        $('#alert_number_fone').show();
    }
});


async function checkPreview() {

    const validate = await validateContactOptinChannel();

    if (validate == false) return;

    fetchCheckPreview(validate);
}


async function validateContactOptinChannel() {

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
    let selectedChannel = document.getElementById('select_channel');
    let idChannel = selectedChannel.options[selectedChannel.selectedIndex].getAttribute('channel');

    const formData = new FormData();
    formData.append("key_remote_id", destinationPhone);
    formData.append("id_channel", idChannel);
    formData.append("csrf_token_talkall", Cookies.get("csrf_cookie_talkall"));

    const response = await fetch(document.location.origin + "/publication/whatsapp/broadcast/waba/validateContact", {
        method: 'POST',
        body: formData
    });

    const result = await response.json();

    return result;
}


function notifyPhoneNumberWithoutOptin() {

    $('#modal-preview-campaign').modal('hide');

    let getElementIdChannel = document.getElementById('select_channel');
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


function fetchCheckPreview(validate) {

    let dataCampaign = getCampaignPreviewData();

    $.ajax({
        type: "POST",
        url: document.location.origin + "/publication/whatsapp/broadcast/waba/save/preview",
        data: {
            id_channel: $("#select_channel").val(),
            channel: validate.id_channel,
            key_remote_id: validate.key_remote_id,
            contacts: validate.contact,
            email: validate.email,
            id_template: $("#select_template").val(),
            url_file: $("#url_file").val(),
            parameters: dataCampaign.parameters,
            selects: dataCampaign.selectedTemplatePameters,
            buttons: dataCampaign.buttonsParameters
        },
        success: function (data) {
            $('#modal-preview-campaign-success').modal('show');
            $('#modal-preview-campaign').modal('hide');
            $("[name='csrf_token_talkall']").val(Cookies.get("csrf_cookie_talkall"));
        }
    })
}


function getCampaignPreviewData() {

    let templateParameters = [...$("input[name*='parametro']")];
    let parametersSelected = [...$("select[name*='parametro']")];
    let buttons = [...$("input[name*='parameter_button_url']")];
    let buttonsParameters = [], selectedTemplatePameters = [], parameters = [];

    templateParameters.forEach(element => {
        parameters.push(element.value);
    });

    parametersSelected.forEach(element => {
        selectedTemplatePameters.push(element.value);
    });

    buttons.forEach(element => {
        buttonsParameters.push(element.value);
    });

    let contentParameters = {
        parameters,
        selectedTemplatePameters,
        buttonsParameters,
    }

    return contentParameters;
}

function createHandlebarsHeader(header) {
    document.getElementById('header').hidden = false;
    document.getElementById('label_header').hidden = false;

    createHeaderParameters();
}

function createHeaderParameters() {
    const div_header = document.getElementById('header_temp_param');

    const div_main = document.createElement('div');
    div_main.style.display = 'flex';

    const _div = document.createElement('div');
    _div.className = 'col-lg-4 px-0';

    const select_parameter_type = document.createElement("div");
    select_parameter_type.id = `element1`;

    const select_title = document.createElement("label");
    select_title.className = "form-control-label";
    select_title.textContent = GLOBAL_LANG.whatsapp_broadcast_waba_add_type_parameter;

    const select_type = document.createElement("select");
    select_type.className = "form-control";
    select_type.name = `select_type_parameters1`;
    select_type.id = `select_type_parameters1`;
    select_type.setAttribute("data-id", 1);

    select_type.addEventListener("change", function () {
        ValidationType(select_type.getAttribute("data-id"));
    });

    const default_option = document.createElement("option");
    default_option.value = "0";
    default_option.textContent = GLOBAL_LANG.whatsapp_broadcast_waba_add_select;

    const text_option = document.createElement("option");
    text_option.value = "text";
    text_option.textContent = GLOBAL_LANG.whatsapp_broadcast_waba_add_text;

    const currency_option = document.createElement("option");
    currency_option.value = "currency";
    currency_option.textContent = GLOBAL_LANG.whatsapp_broadcast_waba_add_currency;

    const date_option = document.createElement("option");
    date_option.value = "date";
    date_option.textContent = GLOBAL_LANG.whatsapp_broadcast_waba_add_date;

    const clients_data_option = document.createElement("option");
    clients_data_option.value = "clientsData";
    clients_data_option.textContent = GLOBAL_LANG.whatsapp_broadcast_waba_add_clientsData;

    select_type.appendChild(default_option);
    select_type.appendChild(text_option);
    select_type.appendChild(currency_option);
    select_type.appendChild(date_option);
    select_type.appendChild(clients_data_option);
    select_parameter_type.appendChild(select_title);
    select_parameter_type.appendChild(select_type);

    _div.appendChild(select_parameter_type);
    div_main.appendChild(_div);

    const _div_lg_8 = document.createElement("div");
    _div_lg_8.className = "col-lg-8 pr-0";

    const parameter_field = document.createElement("div");
    parameter_field.id = `part-1`;

    const parameter_field_title = document.createElement("label");
    parameter_field_title.className = "form-control-label";
    parameter_field_title.textContent = `${GLOBAL_LANG.whatsapp_broadcast_waba_add_parameter} {{1}}}`;

    const parameter_input = document.createElement("input");
    parameter_input.value = "";
    parameter_input.type = "text";
    parameter_input.maxLength = 50;
    parameter_input.disabled = true;
    parameter_input.id = `parametro1`;
    parameter_input.name = `parametro1`;
    parameter_input.className = "form-control";

    parameter_field.appendChild(parameter_field_title);
    parameter_field.appendChild(parameter_input);

    _div_lg_8.appendChild(parameter_field);

    const div_alert = document.createElement('div');
    div_alert.className = 'alert-field-validation';
    div_alert.innerText = GLOBAL_LANG.whatsapp_broadcast_waba_validation_default;
    div_alert.id = 'header-placeholder';
    div_alert.style.display = 'none';

    _div_lg_8.appendChild(div_alert);

    div_main.appendChild(_div_lg_8);

    div_header.appendChild(div_main);

    document.querySelector("#header_template_parameters").removeAttribute("hidden");
}
