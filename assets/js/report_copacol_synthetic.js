function report_bot() {
    $('#datatable-basic-bot').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "synthetic/find_bot",
            type: "POST",
            data: {
                "dt-start": $('#dt-start').val(),
                "dt-end": $('#dt-end').val(),
                "select-situation": $("#select-situation").val(),
                "select-clerk": $("#select-clerk").val(),
                "csrf_talkall": Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'etapas'
            },
            {
                mData: 'finalizados'
            },
            {
                mData: 'qtda_s_rota_lgpd'
            },
            {
                mData: 'abandonado'
            },
            {
                mData: 'media_efetiva'
            },
            {
                mData: 'total'
            },
        ],
        columnDefs: [
            {
                targets: 4,
                render: function (data, type, full, meta) {
                    return `<span>${full.media_efetiva == null ? 0 : full.media_efetiva} %</span>`;
                }
            },
            {
                targets: 0,
                render: function (data, type, full, meta) {
                    return `<span>${full.name.split("bot_")[1]}</span>`;
                }
            },
            {
                orderable: false,
                targets: [1, 2, 3, 4, 5]
            }
        ],
        pagingType: "numbers",
        pageLength: 5,
        destroy: true,
        fixedHeader: true,
        responsive: true,
        lengthChange: false,
        searching: false,
        paginate: false,
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

function report_ticket() {
    $('#datatable-basic-ticket').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "synthetic/find_ticket",
            type: "POST",
            data: {
                "dt-start": $('#dt-start').val(),
                "dt-end": $('#dt-end').val(),
                "select-situation": $("#select-situation").val(),
                "select-clerk": $("#select-clerk").val(),
                "csrf_talkall": Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'name'
            },
            {
                mData: 'aberto'
            },
            {
                mData: 'finalizados'
            },
            {
                mData: 'pendentes'
            },
            {
                mData: 'media_de_tempo'
            },
            {
                mData: 'total'
            },

        ],
        columnDefs: [
            {
                targets: 4,
                render: function (data, type, full, meta) {
                    return `<span>${full.media_de_tempo == null ? 0 : full.media_de_tempo.replace(".", "h:")}m</span>`;
                }
            },
            {
                orderable: false,
                targets: [1, 2, 3, 4, 5]
            }
        ],

        pagingType: "numbers",
        pageLength: 5,
        destroy: true,
        fixedHeader: true,
        responsive: true,
        lengthChange: false,
        searching: false,
        paginate: true,
        info: true,
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


$(document).ready(function () {

    $("#search").on("keyup", function (event) {
        if (event.which == 13) {
            find()
        }
    });
    find();


    $("#modalFilter").one("click", () => modalFilter());
    $('#sendEmailExport').on('click', () => modalExport());

});


function modalFilter() {

    const search = document.getElementById("search");

    const check_search = document.getElementById("check-search");
    const input_search = document.getElementById("input-search");

    const check_situation = document.getElementById("check-situation");
    const select_situation = document.getElementById("select-situation");

    const check_type = document.getElementById("check-type");
    const select_type = document.getElementById("select-type");

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
            dt_start.placeholder = GLOBAL_LANG.report_copacol_synthetic_modal_filter_date_start_placeholder;

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

    $('#sendEmailExport').on('click', function () {

        type = $("#select-type").val();

        if (type == 1) {

            $.get(`/export/xlsx?
            type=reportCopacolSyntheticBot&dt_start=${$('#dt-start').val()}
            &dt_end=${$('#dt-end').val()}
            &select_situation=${$("#select-situation").val()}
            &select_clerk=${$("#select-clerk").val()}
            &filter=${$('#filter').val()}`, function (response) {
                Swal.fire({
                    title: GLOBAL_LANG.report_copacol_synthetic_export_model_title,
                    text: GLOBAL_LANG.report_copacol_synthetic_export_model_text,
                    type: 'success',
                    reverseButtons: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: GLOBAL_LANG.report_copacol_synthetic_export_model_confirmbuttontext
                });
            });

        } else {

            $.get(`/export/xlsx?
            type=reportCopacolSyntheticTicket&dt_start=${$('#dt-start').val()}
            &dt_end=${$('#dt-end').val()}
            &select_situation=${$("#select-situation").val()}
            &select_clerk=${$("#select-clerk").val()}`, function (response) {

                if (response != "Error") {
                    Swal.fire({
                        title: GLOBAL_LANG.report_copacol_synthetic_export_model_title,
                        text: GLOBAL_LANG.report_copacol_synthetic_export_model_text,
                        type: 'success',
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ok!'
                    });

                }
            });

        }
    });
}