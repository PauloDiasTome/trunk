function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "evaluate/find",
            type: "POST",
            data: {
                text: $("#search").val(),
                assessment: $('#select-assessment').val(),
                dt_start: $('#dt-start').val(),
                dt_end: $('#dt-end').val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall"),
            },
        },
        columns: [
            {
                mData: 'user_name'
            },
            {
                mData: 'avarage'
            },
            {
                mData: 'qtda'
            },
        ],
        columnDefs: [
            {
                targets: 1,
                render: function (data, type, full, meta) {
                    return `<div class="progress-wrapper" style="padding: 5px 60px 10px 0px;">
                    <div class="progress-info">
                        <span>${GLOBAL_LANG.report_evaluate_filter_assessment_bad} (${full.evaluate_level_1})</span>
                        <span style="margin-left: 25px">${GLOBAL_LANG.report_evaluate_filter_assessment_good} (${full.evaluate_level_2})</span>
                        <span>${GLOBAL_LANG.report_evaluate_filter_assessment_excellent} (${full.evaluate_level_3})</span>
                    </div>
                    <div class="progress" style="width: 100%; height: 4px">
                      <div class="progress-bar bg-${full.avarage <= '66.66' ? (full.avarage <= '33.33' ? 'danger' : 'primary') : 'success'}" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: ${full.avarage != 0 ? full.avarage : 2.75}%;"></div>
                    </div>
                  </div>`;
                },
                orderable: false,
            },
        ],
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
            if (settings.json.recordsTotal == 0) {
                $("#modalExport").attr('disabled', true);
            } else {
                $("#modalExport").attr('disabled', false);
            }
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
    $("#sendEmailExport").on("click", () => modalExport());

});


function modalFilter() {

    const search = document.getElementById("search");

    const check_search = document.getElementById("check-search");
    const input_search = document.getElementById("input-search");

    const check_assessment = document.getElementById("check-assessment");
    const select_assessment = document.getElementById("select-assessment");

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

    check_assessment.addEventListener("click", () => {
        if (check_assessment.checked) {
            select_assessment.style.display = "block";
        } else {
            select_assessment.value = "";
            select_assessment.style.display = "none";
        }
    });

    check_date.addEventListener("click", () => {
        if (check_date.checked) {

            dt_start.value = "";
            dt_start.type = "text";
            dt_start.style.display = "block";
            dt_start.placeholder = GLOBAL_LANG.report_evaluate_filter_period_placeholder_date_start;

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
            dt_start.placeholder = GLOBAL_LANG.report_evaluate_filter_period_placeholder_date_start;

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


function modalExport() {
    let column = $("#datatable-basic").DataTable().order()[0][0];
    let order = $("#datatable-basic").DataTable().order()[0][1];

    switch (column) {
        case 0:
            column = "last_name";
            break;

        case 2:
            column = "qtd";
            break;

        case 3:
            column = "creation";
            break;

        default:
            column = "creation";
            break;
    }

    $.get(`/export/xlsx?
        search=${$('#search').val() == "" ? $('#input-search').val() : $('#search').val()}
        &assessment=${($('#select-assessment').val())}
        &column=${column}
        &order=${order}
        &dt_start=${$('#dt-start').val()}
        &dt_end=${$('#dt-end').val()}
        &type=reportEvaluate`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.report_evaluate_alert_export_title,
                text: GLOBAL_LANG.report_evaluate_alert_export_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.report_evaluate_alert_export_confirmButtonText
            });
        }
    });
}

