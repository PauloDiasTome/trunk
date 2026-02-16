"use strict";

const Filters = JSON.parse(localStorage.getItem("filters")) || null;


function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.sms.search) {
            document.getElementById("search").value = Filters.sms.search;
        }

        if (Filters.sms.input_search) {
            document.getElementById("input-search").value = Filters.sms.input_search;
        }

        if (Filters.sms.status) {
            modalFilter();
            document.getElementById("check-status").click();
            document.getElementById("select-status").value = Filters.sms.status;
        }

        if (Filters.sms.dt_start) {
            modalFilter();

            document.getElementById("check-date").click();

            document.getElementById("dt-end").disabled = false;
            document.getElementById("dt-end").type = "date";
            document.getElementById("dt-start").type = "date";

            document.getElementById("dt-start").value = Filters.sms.dt_start;
            document.getElementById("dt-end").value = Filters.sms.dt_end;
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
            url: "sms/find",
            type: "POST",
            data: {
                text: $("#search").val(),
                status: $('#select-status').val(),
                dt_start: $('#dt-start').val(),
                dt_end: $('#dt-end').val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall")
            },
        },
        columns: [
            {
                mData: 'title'
            },
            {
                mData: 'schedule'
            },
            {
                mData: 'status'
            },
        ],
        columnDefs: [
            {
                targets: 1,
                render: function (data, type, full, meta) {
                    return data;
                }
            },
            {
                targets: 2,
                render: function (data, type, full, meta) {

                    let ret = `<span class="badge badge-sm badge-secondary">${GLOBAL_LANG.sms_broadcast_datatable_column_status_processing}</span>`
                    switch (full.status) {
                        case '2':
                            ret = `<span class="badge badge-sm badge-success">${GLOBAL_LANG.sms_broadcast_datatable_column_status_send}</span>`
                            break;
                        case '1':
                        case '3':
                            ret = `<span class="badge badge-sm badge-secondary">${GLOBAL_LANG.sms_broadcast_datatable_column_status_processing}</span>`
                            break;
                        case '4':
                            ret = `<span class="badge badge-sm badge-danger">${GLOBAL_LANG.sms_broadcast_datatable_column_status_canceling}</span>`
                            break;
                        case '5':
                            ret = `<span class="badge badge-sm badge-danger">${GLOBAL_LANG.sms_broadcast_datatable_column_status_called_off}</span>`
                            break;
                        case '6':
                            switch (full.is_paused) {
                                case "1":
                                    ret = `<span class="badge badge-sm badge-warning">${GLOBAL_LANG.sms_broadcast_datatable_column_status_paused}</span>`
                                    break;
                                case "2":
                                    ret = `<span class="badge badge-sm badge-info">${GLOBAL_LANG.sms_broadcast_datatable_column_status_sending}</span>`
                                    break;
                            }
                            break;
                        case '7':
                            ret = `<span class="badge badge-sm badge-warning">${GLOBAL_LANG.sms_broadcast_datatable_column_status_paused}</span>`
                            break;
                        default:
                            ret = ret
                            break;
                    }
                    return `<b>${ret}</b>`;
                }
            },
            {
                targets: 3,
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
                                                <span>${GLOBAL_LANG.sms_broadcast_datatable_column_action_view}</span>
                                            </a>`

                    switch (full.status) {
                        case "6":
                        case "7":
                            switch (full.is_paused) {
                                case "1":
                                    res += `<a id="${full.token}" class="dropdown-item table-action-resume action-resume" style="cursor: pointer" data-toggle='tooltip' data-placement='right' title='${GLOBAL_LANG.sms_broadcast_datatable_column_action_resume_distribution}'> 
                                                <div style="width: 24px; display: inline-block"> 
                                                    <i class="far fa-play-circle" style="font-size: 13pt"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.sms_broadcast_datatable_column_action_resume}</span>
                                            </a>`
                                    break;
                                case "2":
                                    res += `<a id="${full.token}" class="dropdown-item table-action-pause action-pause" style="cursor: pointer" data-toggle='tooltip' data-placement='right' title='${GLOBAL_LANG.sms_broadcast_datatable_column_action_pause_distribution}'> 
                                                <div style="width: 24px; display: inline-block"> 
                                                    <i class='far fa-pause-circle' style="font-size: 13pt"></i>
                                                </div>
                                                <span>${GLOBAL_LANG.sms_broadcast_datatable_column_action_pause}</span>
                                            </a>`
                                    break;
                            }
                            break;
                    }

                    res += `<a id="${full.token}" class="dropdown-item ${full.status == 1 || full.status == 3 || full.status == 6 || full.status == 7 ? 'table-action-delete' : 'table-action-deleted'} action" style="cursor: pointer; color: ${full.status == 1 || full.status == 3 || full.status == 6 || full.status == 7 ? '' : '#d6d6d6'}">
                                <div style="width: 24px; display: inline-block"> 
                                    <i class="fas fa-times" style="font-size: 12pt; margin-left: 3px"></i>
                                </div>
                                <span>${GLOBAL_LANG.sms_broadcast_datatable_column_action_cancel}</span>
                            </a>
                        </div>
                     </div>
                  </div>`

                    return res;
                }
            },
        ],
        order: [[1, 'desc']],
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

        drawCallback: function () {
            const sms = {
                search: $("#search").val(),
                input_search: $("#input-search").val(),
                status: $("#select-status").val(),
                dt_start: $("#dt-start").val(),
                dt_end: $("#dt-end").val(),
            }

            let filter = localStorage.getItem("filters");

            filter = filter ? JSON.parse(filter) : {};
            filter.sms = sms;

            localStorage.setItem("filters", JSON.stringify(filter));
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

    $("#datatable-basic").on("click", ".table-action-view", function () {
        window.location.href = "sms/view/" + this.id;
    });

    $("#datatable-basic").on("click", ".table-action-delete", function () {
        swal({
            title: GLOBAL_LANG.sms_broadcast_alert_delete_title,
            text: GLOBAL_LANG.sms_broadcast_alert_delete_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.sms_broadcast_alert_delete_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.sms_broadcast_alert_delete_cancelButtonText,
        }).then(t => {
            if (t.value == true) {
                $.post("sms/cancel/" + this.id, function (data) {

                    if (data.errors?.code === "TA-022") {
                        t.value && swal({
                            title: GLOBAL_LANG.sms_broadcast_validation_cancel_title,
                            text: GLOBAL_LANG.sms_broadcast_validation_cancel_and_send,
                            type: "error",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-success"
                        });
                    }

                    if (data.success?.status === true) {
                        t.value && swal({
                            title: GLOBAL_LANG.sms_broadcast_alert_delete_two_title,
                            text: GLOBAL_LANG.sms_broadcast_alert_delete_two_text,
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

    $("#datatable-basic").on("click", ".table-action-pause", function () {
        swal({
            title: GLOBAL_LANG.sms_broadcast_alert_broadcast_title,
            text: GLOBAL_LANG.sms_broadcast_alert_broadcast_pause_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.sms_broadcast_alert_broadcast_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.sms_broadcast_alert_broadcast_cancelButtonText,
        }).then(t => {
            if (t.value == true) {
                $.post("sms/pause/" + this.id, function (data) {
                    if (data.success?.status === true) {
                        t.value && swal({
                            title: GLOBAL_LANG.sms_broadcast_alert_broadcast_two_title,
                            text: GLOBAL_LANG.sms_broadcast_alert_broadcast_pause_two_text,
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

    $("#datatable-basic").on("click", ".table-action-resume", function () {
        swal({
            title: GLOBAL_LANG.sms_broadcast_alert_broadcast_title,
            text: GLOBAL_LANG.sms_broadcast_alert_broadcast_resume_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.sms_broadcast_alert_broadcast_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.sms_broadcast_alert_broadcast_cancelButtonText,
        }).then(t => {
            if (t.value == true) {
                $.post("sms/resume/" + this.id, function (data) {
                    if (data.success?.status === true) {
                        t.value && swal({
                            title: GLOBAL_LANG.sms_broadcast_alert_broadcast_two_title,
                            text: GLOBAL_LANG.sms_broadcast_alert_broadcast_resume_two_text,
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

    $(document).on("input", "#input_data", function () {
        let inputElement = $(this);
        let countElement = $("#count_caracter");

        let characters = inputElement.val();
        let countEnters = (characters.match(/\n/g) || []).length;

        let maxLength = /[^A-Za-z0-9\s]/.test(characters) ? 70 - countEnters : 140 - countEnters;

        if (characters.length + countEnters > maxLength) {
            inputElement.val(characters.slice(0, maxLength));
            maxLength = characters.length + countEnters;
        }

        inputElement.prop("maxLength", maxLength);

        let remainingCharacters = maxLength - characters.length - countEnters;
        countElement.text(remainingCharacters);

        countElement.css({
            color: remainingCharacters = "red",
        });
    });

    maskDate($("#date_start"));
    maskTime($("#time_start"));
    textareaResponse();

    $('#sendEmailExport').on('click', () => modalExport());
    $("#modalFilter").one("click", () => modalFilter());
    $("form").submit(event => event.preventDefault());
    document.querySelector(".btn-success")?.addEventListener("click", submit);
});

function textareaResponse() {
    const tex_area = $("#tex_area").val();
    const initial_size = $("#tex_area").prop("scrollHeight");

    $('#tex_area').each(function () {
        $("#tex_area").val(tex_area)
        this.setAttribute('style', 'height:' + initial_size + 'px');
    });
}

function maskDate(input) {
    input.on("input", function () {
        let value = input.val().replace(/\D/g, '');
        if (value.length > 8) {
            value = value.slice(0, 8);
        }
        if (value.length > 4) {
            value = value.replace(/^(\d{2})(\d{2})(\d{0,4})/, '$1/$2/$3');
        } else if (value.length > 2) {
            value = value.replace(/^(\d{2})(\d{0,2})/, '$1/$2');
        }
        input.val(value);
    });
}

function maskTime(input) {
    input.on("input", function () {
        let value = input.val().replace(/\D/g, '');
        if (value.length > 4) {
            value = value.slice(0, 4);
        }
        if (value.length > 2) {
            value = value.replace(/^(\d{2})(\d{0,2})/, '$1:$2');
        }
        input.val(value);
    });
}

function check_hour(date_start, time_start) {
    let date = date_start;
    date = date.split("/")[2] + "-" + date.split("/")[1] + "-" + date.split("/")[0] + " " + time_start;

    let date_current = new Date();

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

    const date__start = new Date(date);
    const date__current = new Date(date_current);

    if (date__start >= date__current) {
        return true;
    } else {
        return false;
    }
}

function submit() {
    const input_title = formValidation({ field: document.getElementById("input_title"), text: GLOBAL_LANG.sms_broadcast_title, required: true, min: 1, max: 100 });
    const date_start = formValidation({ field: document.getElementById("date_start"), text: GLOBAL_LANG.sms_broadcast_date_scheduling, required: true, numeric_length: 8 });
    const time_start = formValidation({ field: document.getElementById("time_start"), text: GLOBAL_LANG.sms_broadcast_hour_scheduling, required: true, numeric_length: 5 });
    const select_channel = formValidation({ field: document.getElementById("select_channel"), text: GLOBAL_LANG.sms_broadcast_select_channel, required: true });
    const input_data = formValidation({ field: document.getElementById("input_data"), text: GLOBAL_LANG.sms_broadcast_validation_message, required: true, max: 140 });

    if (input_title && date_start && time_start && select_channel && input_data) {
        document.querySelector(".btn-success").disabled = true;
        $("form").unbind('submit').submit();
    }
}

function modalFilter() {
    const search = document.getElementById("search");

    const check_search = document.getElementById("check-search");
    const input_search = document.getElementById("input-search");

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
            dt_start.placeholder = GLOBAL_LANG.sms_broadcast_filter_period_placeholder_date_start;

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
            dt_start.placeholder = GLOBAL_LANG.sms_broadcast_filter_period_placeholder_date_start;

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
        let current = year + '-' + month + '-' + day;

        dt_end.value = current;
    });

    dt_end.addEventListener("change", () => {
        if (dt_end.value != "") btn_search.disabled = false; else btn_search.disabled = true;
    });

    btn_search.addEventListener("click", () => {
        search.value = input_search.value;
        find();
    });

    btn_search.addEventListener("click", () => {

        const contact = document.getElementById("input-search");
        search.value = contact.value;

        find();
        search.value = "";
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
        case 0:
            column = "title";
            break;
        case 1:
            column = "schedule";
            break;
        case 2:
            column = "status";
            break;
    }

    $.get(`/export/xlsx?
        search=${$('#search').val() == "" ? $('#input-search').val() : $('#search').val()}
        &status=${$('#select-status').val()}
        &column=${column}
        &order=${order}
        &dt_start=${$('#dt-start').val()}
        &dt_end=${$('#dt-end').val()}
        &type=broadcastSMS`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.sms_broadcast_alert_export_title,
                text: GLOBAL_LANG.sms_broadcast_alert_export_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.sms_broadcast_alert_export_confirmButtonText
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