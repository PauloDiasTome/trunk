function chatbot() {
    $('#datatable-chatbot').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "synthetic/chatbot",
            type: "POST",
            data: {
                situation: $('#select-situation').val(),
                dt_end: $("#check-date").val() == 1 ? $('#dt-end').val() : "",
                dt_start: $("#check-date").val() == 1 ? $('#dt-start').val() : "",
                id_channel: $('#select-channel').val() == '' ? 0 : $('#select-channel').val(),
                channel: typeof ($('#select-channel option:selected').data('id')) == 'undefined' ? 0 : $('#select-channel option:selected').data('id'),
                csrf_talkall: Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'title'
            },
        ],
        columnDefs: [
            {
                targets: 1,
                render: function (data, type, full, meta) {
                    $(".thead-light").find("tr").find(".sorting_asc").removeClass("sorting_asc");
                    return `<span style='font-weight: 500;font-family: roboto;'>${full.qtde}</span>`;
                }

            },
        ],
        pagingType: "numbers",
        destroy: true,
        responsive: true,
        lengthChange: false,
        searching: false,
        paginate: false,
        info: false,
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

function waitingService(time) {
    $('#datatable-waiting-service').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "synthetic/waiting_service",
            type: "POST",
            data: {
                situation: $('#select-situation').val(),
                dt_end: $('#dt-end').val(),
                dt_start: $('#dt-start').val(),
                time: time,
                id_channel: $('#select-channel').val() == '' ? 0 : $('#select-channel').val(),
                channel: typeof ($('#select-channel option:selected').data('id')) == 'undefined' ? 0 : $('#select-channel option:selected').data('id'),
                csrf_talkall: Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'title'
            },
        ],
        columnDefs: [
            {
                targets: 1,
                render: function (data, type, full, meta) {
                    $(".thead-light").find("tr").find(".sorting_asc").removeClass("sorting_asc");
                    return `<span style='font-weight: 500;font-family: roboto;'>${full.qtde}</span>`;
                }

            },
            {
                orderable: false,
                targets: [0]
            }
        ],
        pagingType: "numbers",
        destroy: true,
        responsive: true,
        lengthChange: false,
        searching: false,
        paginate: false,
        info: false,
        language: {
            url: `${document.location.origin}/assets/lang/${localStorage.getItem("language")}.json`
        },

        drawCallback: function (settings) {
            if (settings.json.recordsTotal == 0) {
                $("#modalExport").attr('disabled', true);
            }
        }
    });
}


function attendance(time) {
    $('#datatable-attendance').DataTable({
        processing: true,
        serverSide: true,
        paging: false,
        ajax: {
            url: "synthetic/attendance",
            type: "POST",
            data: {
                situation: $('#select-situation').val(),
                dt_end: $('#dt-end').val(),
                dt_start: $('#dt-start').val(),
                time: time,
                id_channel: $('#select-channel').val() == '' ? 0 : $('#select-channel').val(),
                channel: typeof ($('#select-channel option:selected').data('id')) == 'undefined' ? 0 : $('#select-channel option:selected').data('id'),
                csrf_talkall: Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'title'
            },
        ],
        columnDefs: [
            {
                targets: 1,
                render: function (data, type, full, meta) {
                    $(".thead-light").find("tr").find(".sorting_asc").removeClass("sorting_asc");
                    return `<span style='font-weight: 500;font-family: roboto;'>${full.qtde}</span>`;
                }
            },
            {
                orderable: false,
                targets: [0]
            }
        ],
        pagingType: "numbers",
        destroy: true,
        responsive: true,
        lengthChange: false,
        searching: false,
        paginate: false,
        info: false,
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

    $(".header.pb-6.d-flex.align-items-center").remove();
    $("#search").on("keyup", function (event) {
        if (event.which == 13) {
            find();
        }
    });

    if (window.getComputedStyle(document.getElementById('container-chatbot')).getPropertyValue('display') == "block") {
        chatbot();
    }

    attendance(4);
    waitingService(1);

    $("#modalFilter").one("click", () => modalFilter());
    $('#sendEmailExport').on('click', () => modalExport());

});

function modalFilter() {

    const check_situation = document.getElementById("check-situation");
    const check_channel = document.getElementById("check-channel");
    const select_situation = document.getElementById("select-situation");
    const select_channel = document.getElementById("select-channel");

    check_situation.checked = true;
    check_channel.checked = false;
    select_channel.style.display = 'none';

    const dt_start = document.getElementById("dt-start");
    const dt_end = document.getElementById("dt-end");

    const check_date = document.getElementById("check-date");
    const btn_search = document.getElementById("btn-search");
    const alert_filter_period = document.getElementById("alert-filter-period");

    const localDate = new Date();
    const dt_max = localDate.toISOString().split("T")[0];
    const millisecond_per_day = 1000 * 60 * 60 * 24;
    const difDate = new Date(localDate.getTime() - 90 * millisecond_per_day)

    const dt_min = difDate.toISOString().split("T")[0];

    check_situation.addEventListener("click", () => {
        if (check_situation.checked) {
            select_situation.style.display = "block";
        } else {
            select_situation.style.display = "none";
            select_situation.value = 0;
        }
    });

    check_channel.addEventListener("click", () => {
        if (check_channel.checked) {
            select_channel.style.display = "block";
        } else {
            select_channel.style.display = "none";
            select_channel.value = 0;
        }
    });

    check_date.addEventListener("click", () => {
        if (check_date.checked) {

            dt_start.value = "";
            dt_start.type = "text";
            dt_start.style.display = "block";
            dt_start.placeholder = GLOBAL_LANG.report_interaction_synthetic_modal_filter_date_start_placeholder;

            dt_end.type = "text";
            dt_end.value = "";
            // dt_end.disabled = true;
            dt_end.style.display = "block";

            check_date.value = "1";

        } else {
            dt_start.style.display = "none";
            dt_end.style.display = "none";

            check_date.value = "2";
            alert_filter_period.style.display = "none";
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
        if (check_date.checked && dt_end.value == '') {
            btn_search.disabled = true;
        }
    });

    dt_start.addEventListener("focus", () => {

        dt_start.type = "date";
        btn_search.disabled = true;
    });

    dt_start.addEventListener("change", () => {

        const get_date = new Date(dt_start.value);
        const get_date_end = new Date(dt_end.value);

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        if (get_date > today) {
            dt_start.value = getToday();
        }

        if (get_date_end > today) {
            dt_end.value = getToday();
        }

        dt_end.disabled = false;
        dt_end.type = "date";
        btn_search.disabled = false;

        dt_end.value = getToday();
    });

    dt_end.addEventListener("change", () => {
        const get_date_end = new Date(dt_end.value);

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        if (get_date_end > today) {
            dt_end.value = getToday();
        }

    });

    btn_search.addEventListener("click", () => {

        const select_situation = document.getElementById("select-situation");
        let dt_end = $('#dt-end').val();
        let dt_start = $('#dt-start').val();

        switch (select_situation.value) {
            case "0":
                chatbot();
                attendance(6);
                waitingService(3);
                $("#container-chatbot").show();
                $("#container-attendance").show();
                $("#container-waiting-service").show();
                break;
            case "1":
                chatbot();
                $("#container-chatbot").show();
                $("#container-attendance").hide();
                $("#container-waiting-service").hide();
                break;
            case "2":
                waitingService(0);
                $("#container-chatbot").hide();
                $("#container-attendance").hide();
                $("#container-waiting-service").show();
                break;
            case "3":
                attendance(0);
                $("#container-chatbot").hide();
                $("#container-attendance").show();
                $("#container-waiting-service").hide();
                break;
            default:
                break;
        }

        const period_report = document.querySelectorAll(".period-report");

        if (dt_start != "" && check_date.value != 2) {
            let year_st = dt_start.split("-")[0];
            let month_st = dt_start.split("-")[1];
            let day_st = dt_start.split("-")[2];

            let year_end = dt_end.split("-")[0];
            let month_end = dt_end.split("-")[1];
            let day_end = dt_end.split("-")[2];

            dt_start = day_st + "/" + month_st + "/" + year_st;
            dt_end = day_end + "/" + month_end + "/" + year_end;

            for (elm of period_report) {
                elm.innerHTML = dt_start + " - " + dt_end;
            }
        } else {
            for (elm of period_report) {
                elm.innerHTML = GLOBAL_LANG.report_interaction_synthetic_day;
            }
        }

    });
}

function modalExport() {

    $.get(`/export/xlsx?
        situation=${$('#select-situation').val() == 0 ? "" : $('#select-situation').val()}
        &dt_start=${$('#dt-start').val()}
        &dt_end=${$('#dt-end').val()}
        &type=reportInteractionSynthetic`, function (response) {

        Swal.fire({
            title: GLOBAL_LANG.report_interaciton_synthetic_alert_export_title,
            text: GLOBAL_LANG.report_interaciton_synthetic_alert_export_text,
            type: 'success',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: GLOBAL_LANG.report_interaciton_synthetic_alert_export_confirmButtonText
        });
    });
}

function getToday() {
    let date = new Date();
    let day = String(date.getDate()).padStart(2, '0');
    let month = String(date.getMonth() + 1).padStart(2, '0');
    let year = date.getFullYear();

    current = year + '-' + month + '-' + day;

    return current;
}


