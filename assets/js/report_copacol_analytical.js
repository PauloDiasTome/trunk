function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "analytical/find",
            type: "POST",
            data: {
                text: $("#search").val(),
                clerk: $('#multiselect2').val() == "" ? "" : $('#multiselect2').val(),
                situation: $('#select-situation').val(),
                dt_start: $('#dt-start').val(),
                dt_end: $('#dt-end').val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall"),
            }
        },
        columns: [
            {
                mData: 'nameTicketType'
            },
            {
                mData: 'nameTicketType'
            },
            {
                mData: 'nameUser'
            },
            {
                mData: 'full_name'
            },
            {
                mData: 'key_remote_id'
            },
            {
                mData: 'creation'
            },
            {
                mData: 'timestamp_close'
            },
            {
                mData: 'minutes'
            },
            {
                mData: 'ticketStatusName'
            }
        ],
        columnDefs: [
            {
                targets: 0,
                render: (data, type, full, meta) => {
                    let value = full.nameTicketType.substr(0, 4);

                    if (value == 'bot_') {
                        return 'Bot';
                    }

                    return 'Ticket';
                }
            },
            {
                targets: 1,
                render: (data, type, full, meta) => {
                    let value = full.nameTicketType;

                    switch (value) {
                        case 'bot_PoliticaPrivacidade':
                            return value.replace("bot_PoliticaPrivacidade", GLOBAL_LANG.report_copacol_analytic_dt_columndefs_target1_case_bot_privacy_policy);

                        case 'bot_TemRota':
                            return value.replace("bot_TemRota", GLOBAL_LANG.report_copacol_analytic_dt_columndefs_target1_case_bot_with_route);

                        case 'bot_OutrasInfo':
                            return value.replace("bot_OutrasInfo", GLOBAL_LANG.report_copacol_analytic_dt_columndefs_target1_case_bot_another_info);

                        case 'bot_Finalizado':
                            return value.replace("bot_Finalizado", GLOBAL_LANG.report_copacol_analytic_dt_columndefs_target1_case_finish_bot);
                            return value.replace("bot_MenuInicio", GLOBAL_LANG.report_copacol_analytic_dt_columndefs_target1_case_finish_bot_02);

                        case 'bot_Start':
                            return value.replace("bot_Start", GLOBAL_LANG.report_copacol_analytic_dt_columndefs_target1_case_bot_start);

                        case 'bot_Trabalhou':
                            return value.replace("bot_Trabalhou", GLOBAL_LANG.report_copacol_analytic_dt_columndefs_target1_case_bot_worked);

                        case 'bot_Cidade':
                            return value.replace("bot_Cidade", GLOBAL_LANG.report_copacol_analytic_dt_columndefs_target1_case_bot_city);

                        case 'bot_Rota':
                            return value.replace("bot_Rota", GLOBAL_LANG.report_copacol_analytic_dt_columndefs_target1_case_route_bot);

                        case 'bot_Cpf':
                            return value.replace("bot_Cpf", GLOBAL_LANG.report_copacol_analytic_dt_columndefs_target1_case_cpf_bot);

                        case 'bot_DataNasc':
                            return value.replace("bot_DataNasc", GLOBAL_LANG.report_copacol_analytic_dt_columndefs_target1_case_born_date_bot);

                        default:
                            return full.nameTicketType;
                    }
                }
            },
            {
                targets: 2,
                render: (data, type, full, meta) => {
                    let value = $("#select-type").val();

                    if (value == 'Bot' || full.nameTicketType.substr(0, 4) == 'bot_') {
                        return "";
                    }

                    return full.nameUser;
                }
            },
            {
                targets: 6,
                render: (data, type, full, meta) => {
                    if (full.timestamp_close != null) {
                        return full.timestamp_close;
                    } else {
                        return GLOBAL_LANG.report_copacol_analytic_dt_columndefs_target6_in_attendance;
                    }
                }
            },
            {
                targets: 7,
                render: (data, type, full, meta) => {
                    return convertDate(full.minutes);
                }
            },
            {
                targets: 8,
                render: (data, type, full, meta) => {
                    return "<span class='badge badge-pill badge-success' style='color: black; background-color:" + full.colorTicketType + ";'>" + full.ticketStatusName + "</span>";
                }
            },
            {
                orderable: true,
                targets: [0, 1, 2, 3, 4, 5, 6, 7, 8]
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
        }

    });
}


$(document).ready(() => {

    $("#search").on("keyup", function (event) {
        if (event.which == 13) {
            find();
        }
    });
    find();

    $('#sendEmailExport').on('click', () => modalExport());
    $("#modalFilter").one("click", () => modalFilter());

});


function convertDate(date) {
    let num = date;
    let hours = (num / 60);
    let rhours = Math.floor(hours);
    let minutes = (hours - rhours) * 60;
    let rminutes = Math.round(minutes);

    return `${rhours}h:${rminutes}m`;
}


function modalFilter() {

    const search = document.getElementById("search");

    const check_search = document.getElementById("check-search");
    const input_search = document.getElementById("input-search");

    const check_type = document.getElementById("check-type");
    const select_type = document.getElementById("select-type");

    const check_situation = document.getElementById("check-situation");
    const select_situation = document.getElementById("select-situation");

    const dt_end = document.getElementById("dt-end");
    const dt_start = document.getElementById("dt-start");

    const check_date = document.getElementById("check-date");
    const btn_search = document.getElementById("btn-search");

    const logger = document.querySelectorAll(".logger");

    check_search.checked = true;
    input_search.style.display = "block";

    check_search.addEventListener("click", () => {
        if (check_search.checked) input_search.style.display = "block"; else input_search.style.display = "none";
    });

    check_situation.addEventListener("click", () => {
        if (check_situation.checked) select_situation.style.display = "block"; else select_situation.style.display = "none";
    });

    check_type.addEventListener("click", () => {
        if (check_type.checked) select_type.style.display = "block"; else select_type.style.display = "none";
    });

    check_date.addEventListener("click", () => {
        if (check_date.checked) {

            dt_start.value = "";
            dt_start.type = "text";
            dt_start.style.display = "block";
            dt_start.placeholder = GLOBAL_LANG.report_copacol_analytic_modal_filter_dt_start_placeholder;

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

    });;

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

        const contact = document.getElementById("input-search");
        search.value = contact.value;

        find();
        search.value = "";
    });

    for (elm of logger) {
        elm.style.paddingLeft = "15px";
    }

}


function modalExport() {

    $.get(`/export/xlsx?
        &type=reportCopacolAnalytical&dt_start=${$('#dt-start').val()}
        &dt_end=${$('#dt-end').val()}
        &select_situation=${$("#select-type").val()}
        &select_clerk=${$('#select-situation').val()}
        &filter=${$('#filter').val()}`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.report_analytic_alert_export_title,
                text: GLOBAL_LANG.report_analytic_alert_export_title,
                type: 'success',
                reverseButtons: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.report_copacol_analytic_alert_export_confirmButtonText,
            });
        }
    });

}