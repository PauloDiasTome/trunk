var json;

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
                mData: 'schedule'
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
                targets: 1,
                render: function (data, type, full, meta) {
                    return "<b>" + data + "</b>";
                }
            },
            {
                targets: 2,
                render: function (data, type, full, meta) {

                    let status;

                    switch (data) {
                        case '1':
                            status = `<i class="fas fa-adjust" style="color:#ffa500; margin-right: 10px;"></i> ${GLOBAL_LANG.sms_broadcast_status_waiting}`
                            break;
                        case '2':
                            status = `<i class="fas fa-check" style="color:#1abc9c; margin-right: 10px;"></i> ${GLOBAL_LANG.sms_broadcast_status_sent}`
                            break;
                        case '3':
                            status = `<i class="fas fa-times" style="color:#ff0000; margin-right: 10px;"></i> ${GLOBAL_LANG.sms_broadcast_status_canceled}`
                            break;
                    }
                    return status;
                }
            },
            {
                targets: 3,
                render: function (data, type, full, meta) {
                    return `<a href='#' id='${full.id_broadcast_sms_schedule}' class='table-action table-action-view' data-toggle='tooltip' data-original-title='View'>
                                <i class='fas fa-eye'></i></a>
                            <a id='${full.id_broadcast_sms_schedule}' href='#' class='table-action table-action-delete' data-toggle='tooltip' data-original-title='Remove' style='display: ${full.status != '1' ? 'none' : ''}'>
                                <i class='fas fa-trash'></i>
                            </a>`;
                }
            },
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
        }
    });
}

$(document).ready(function () {

    const verifyAdd = document.getElementById("verify-add");
    const verifyView = document.getElementById("verify-view");

    $("#search").on("keyup", function (event) {
        if (event.which == 13) {
            find();
        }
    });


    if ($("#search").val() !== undefined) {
        find();
    }


    $(".group").on("click", function () {
        if (this.id == "Group0" && this.checked == true) {
            for (var i = 0; i < $(".group").length; i++) {
                $(".group")[i].checked = false;
                $('#groups').tagsinput('remove', $(".group")[i].dataset.id);
            }
            $('#groups').tagsinput('add', "0");
            document.getElementById("Group0").checked = true;
        } else {
            $('#groups').tagsinput('remove', "0");
            document.getElementById("Group0").checked = false;
            if (this.checked == true) {
                $('#groups').tagsinput('add', this.dataset.id);
            } else {
                $('#groups').tagsinput('remove', this.dataset.id);
            }
        }
    });


    $("#datatable-basic").on("click", ".table-action-view", function () {
        window.location.href = "sms/view/" + this.id;
    });


    $("#datatable-basic").on("click", ".table-action-delete", function () {
        swal({
            title: GLOBAL_LANG.sms_broadcast_modal_delete_title,
            text: GLOBAL_LANG.sms_broadcast_modal_delete_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.sms_broadcast_btn_yes,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.sms_broadcast_btn_no,
            reverseButtons: true,
        }).then(t => {
            if (t.value == true) {
                $.post("sms/delete/" + this.id, function (data) {
                    t.value && swal({
                        title: GLOBAL_LANG.sms_broadcast_modal_delete_confirm_title,
                        text: GLOBAL_LANG.sms_broadcast_modal_delete_confirm_text,
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-primary"
                    });
                    $("#datatable-basic").DataTable().ajax.reload(null, false);
                });
            }
        });
    });

    $("#modalFilter").one("click", () => modalFilter());
    $("#sendEmailExport").on("click", () => modalExport());

    if (verifyAdd) {
        $("form").submit(event => event.preventDefault());
        document.querySelector(".btn-success").addEventListener("click", submit);
    }


});

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

    const date_start = document.getElementById("date_start").value;
    const time_start = document.getElementById("time_start").value;
    const input_title = document.getElementById("input-title").value;
    const checkboxes = document.querySelectorAll('input[type="checkbox"]:checked')
    const input_data = document.getElementById("input-data").value;

    let form_validation_date = true;
    let form_validation_time = true;
    let form_validation_title = true;
    let form_validation_checkboxes = true;
    let form_validation_data = true;

    if (!check_date(date_start)) {
        form_validation_date = false;
        document.getElementById("alert_date_start").style.display = "block";
        document.getElementById("alert_date_start").innerHTML = GLOBAL_LANG.sms_broadcast_validation_date;
    } else {
        document.getElementById("alert_date_start").style.display = "none";
    }

    if (!check_hour(date_start, time_start)) {
        form_validation_time = false;
        document.getElementById("alert_time_start").style.display = "block";
        document.getElementById("alert_time_start").innerHTML = GLOBAL_LANG.sms_broadcast_validation_time;
    } else {
        document.getElementById("alert_time_start").style.display = "none";
    }

    if (!input_title) {
        form_validation_title = false;
        document.getElementById("alert_input_title").style.display = "block";
        document.getElementById("alert_input_title").innerHTML = GLOBAL_LANG.sms_broadcast_validation_title;
    } else {
        document.getElementById("alert_input_title").style.display = "none";
    }

    if (checkboxes.length == 0) {
        form_validation_checkboxes = false;
        document.getElementById("alert_input_checkboxes").style.display = "block";
        document.getElementById("alert_input_checkboxes").innerHTML = GLOBAL_LANG.sms_broadcast_validation_checkboxes;
    } else {
        document.getElementById("alert_input_checkboxes").style.display = "none";
    }

    if (!input_data) {
        form_validation_data = false;
        document.getElementById("alert_input_data").style.display = "block";
        document.getElementById("alert_input_data").innerHTML = GLOBAL_LANG.sms_broadcast_validation_data;
    } else {
        document.getElementById("alert_input_data").style.display = "none";
    }

    if (form_validation_date && form_validation_time && form_validation_title && form_validation_checkboxes && form_validation_data) {
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
            console.log("OPA: ", select_status[2].value);
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
    let column = $("#datatable-basic").DataTable().order()[0][0];
    let order = $("#datatable-basic").DataTable().order()[0][1];

    switch (column) {
        case 0:
            column = "schedule";
            break;

        case 1:
            column = "title";
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
                title: GLOBAL_LANG.sms_broadcast_contact_alert_export_title,
                text: GLOBAL_LANG.sms_broadcast_contact_alert_export_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.sms_broadcast_contact_alert_export_confirmButtonText
            });

        }

    });
}