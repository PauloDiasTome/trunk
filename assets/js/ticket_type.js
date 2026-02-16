const Filters = JSON.parse(localStorage.getItem("filters")) || null;


function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.ticket_type.search) {
            document.getElementById("search").value = Filters.ticket_type.search;
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
            url: "type/find",
            type: "POST",
            data: {
                "text": $("#search").val(),
                "csrf_talkall": Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'ticket_type_name'
            },
            {
                mData: 'ticket_group_name'
            },
            {
                mData: 'ticket_sla_name'
            },
        ],
        columnDefs: [
            {
                orderable: true,
                targets: 2,
                render: function (data, type, full, meta) {
                    return "<span class='badge badge-pill badge-success' style='color: black; background-color:" + full.ticket_sla_color + ";'>" + full.ticket_sla_name + "</span>";
                }
            },
            {
                targets: 3,
                render: function (data, type, full, meta) {

                    return `<div class="dropleft">
                                    <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                        <i class="fa fa-ellipsis-v"></i>
                                    <div class="ripple-container"></div>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="multiDropdownMenu">
                                        <a id="` + full.id_ticket_type + `" href='#' class="dropdown-item table-btn-access" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block">     
                                                <i class="far fa-hand-pointer"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.setting_bot_trainer_dt_columndefs_target2_title_access}</span>
                                        </a>
                                        <a id="` + full.id_ticket_type + `" href='#' class="dropdown-item table-btn-edit" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block"> 
                                                <i class="far fa-edit"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.ticket_type_dt_columndefs_target3_title_edit}</span>
                                        </a>
                                        <a id="` + full.id_ticket_type + `" href='#' class="dropdown-item table-btn-delete" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block"> 
                                                <i class="far fa-trash-alt"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.ticket_type_dt_columndefs_target3_title_delete}</span>
                                        </a>
                                    </div>
                                </div>`
                }
            },
            {
                orderable: true,
                targets: [0, 1, 2]
            }
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

            const ticket_type = {
                search: $("#search").val(),
            }

            let filter = localStorage.getItem("filters");

            filter = filter ? JSON.parse(filter) : {};
            filter.ticket_type = ticket_type;

            localStorage.setItem("filters", JSON.stringify(filter));
        }
    });
}

$(document).ready(function () {

    $("#search").on("keyup", function (event) {
        if (event.which == 13) {
            find()
        }
    });


    if ($("#search").val() !== undefined) {
        find();
    }


    $("#datatable-basic").on("click", ".table-btn-access", function () {
        window.location.href = "../ticket/type/" + this.id;
    });


    $("#datatable-basic").on("click", ".table-btn-edit", function () {
        window.location.href = "type/edit/" + this.id;
    });


    $("#datatable-basic").on("click", ".table-btn-delete", function () {
        swal({
            title: GLOBAL_LANG.ticket_type_alert_delete_title,
            text: GLOBAL_LANG.ticket_type_alert_delete_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.ticket_type_alert_delete_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.ticket_type_alert_delete_cancelButtonText
        }).then(t => {
            if (t.value == true) {
                $.post("type/delete/" + this.id, function (data) {
                    if (data.errors?.code == "TA-001") {
                        t.value && swal({
                            title: GLOBAL_LANG.ticket_type_alert_delete_four_title,
                            text: `${GLOBAL_LANG.ticket_type_alert_delete_four_text} (${data.errors.code})`,
                            type: "error",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-danger"
                        });
                    }

                    if (data.errors?.code == "TA-002") {
                        t.value && swal({
                            title: GLOBAL_LANG.ticket_type_alert_delete_three_title,
                            text: `${GLOBAL_LANG.ticket_type_alert_delete_three_text} (${data.errors.code})`,
                            type: "warning",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-success"
                        });
                    }

                    if (data.success?.status == true) {
                        t.value && swal({
                            title: GLOBAL_LANG.ticket_type_alert_delete_two_title,
                            text: GLOBAL_LANG.ticket_type_alert_delete_two_text,
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

    $("body").on("click", "#return_page", () => window.history.back());

    $('#sendEmailExport').on('click', () => modalExport());

    $("form").append(`<input type='hidden' name='route' value='${window.location.href}'>`);
    $("form").submit(event => event.preventDefault());
    if (document.querySelector(".btn-success")) {
        document.querySelector(".btn-success").addEventListener("click", submit);
    }

});


function submit() {
    const user_group = document.getElementById("user_group");

    const input_name = formValidation({ field: document.getElementById("input-name"), text: GLOBAL_LANG.ticket_type_name, required: true, min: 3, max: 100, alpha_numeric_spaces: true });
    const input_user_group = user_group ? formValidation({ field: user_group, text: GLOBAL_LANG.ticket_type_sector, required: true }) : true;
    const input_ticket_sla = formValidation({ field: document.getElementById("ticket_sla"), text: GLOBAL_LANG.ticket_type_ticket_sla, required: true });

    if (input_name && input_user_group && input_ticket_sla) {
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
            column = "Name";
            break;

        case 1:
            column = "Sector";
            break;

        case 2:
            column = "Sla";
            break;

        default:
            break;
    }

    $.get(`/export/xlsx?
        search=${$('#search').val()}
        &column=${column}
        &order=${order}
        &type=ticketType`, function (response) {
        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.ticket_type_alert_export_title,
                text: GLOBAL_LANG.ticket_type_alert_export_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.ticket_type_alert_export_confirmButtonText
            });
        }
    });
}