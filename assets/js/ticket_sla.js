const Filters = JSON.parse(localStorage.getItem("filters")) || null;


function checkFilter() {
    if (Filters) {

        if (!Filters.btn_back) return

        if (Filters.ticket_sla.search) {
            document.getElementById("search").value = Filters.ticket_sla.search;
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
            url: "sla/find",
            type: "POST",
            data: {
                "text": $("#search").val(),
                "csrf_talkall": Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'name'
            },
            {
                mData: 'time_sla'
            },
        ],
        columnDefs: [
            {
                targets: 0,
                render: function (data, type, full, meta) {
                    return "<span class='badge badge-pill badge-success' style='color: black; background-color:" + full.color + ";'>" + full.name + "</span>";
                }
            },
            {
                targets: 2,
                render: function (data, type, full, meta) {

                    return `<div class="dropleft">
                                    <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                        <i class="fa fa-ellipsis-v"></i>
                                    <div class="ripple-container"></div>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="multiDropdownMenu">
                                        <a id="` + full.id_ticket_sla + `" href='#' class="dropdown-item table-btn-edit" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block">     
                                                <i class="far fa-edit"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.ticket_sla_dt_columndefs_target2_title_edit}</span>
                                        </a>
                                        <a id="` + full.id_ticket_sla + `" href='#' class="dropdown-item table-btn-delete" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block"> 
                                                <i class="far fa-trash-alt"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.ticket_sla_dt_columndefs_target2_title_delete}</span>
                                        </a>
                                    </div>
                                </div>`
                }
            },
            {
                orderable: false,
                targets: [1, 2]
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

            const ticket_sla = {
                search: $("#search").val(),
            }

            let filter = localStorage.getItem("filters");

            filter = filter ? JSON.parse(filter) : {};
            filter.ticket_sla = ticket_sla;

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
    find();


    $("#datatable-basic").on("click", ".table-btn-edit", function () {
        window.location.href = "sla/edit/" + this.id;
    });


    $("#datatable-basic").on("click", ".table-btn-delete", function () {
        swal({
            title: GLOBAL_LANG.ticket_sla_alert_delete_title,
            text: GLOBAL_LANG.ticket_sla_alert_delete_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.ticket_sla_alert_delete_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.ticket_sla_alert_delete_cancelButtonText,
        }).then(t => {
            if (t.value == true) {
                $.post("sla/delete/" + this.id, function (data) {
                    t.value && swal({
                        title: GLOBAL_LANG.ticket_sla_alert_delete_two_title,
                        text: GLOBAL_LANG.ticket_sla_alert_delete_two_text,
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });
                    $("#datatable-basic").DataTable().ajax.reload(null, false);
                });
            }
        });
    });


    $("#is_open").on("click", function () {
        $('#is_close').prop('checked', false);
    });


    $("#is_close").on("click", function () {
        $('#is_open').prop('checked', false);
    });


    var maskBehavior = function (val) {
        val = val.split(":");
        return (parseInt(val[0]) > 19) ? "HZ:M0" : "H0:M0";
    }


    spOptions = {
        onKeyPress: function (val, e, field, options) {
            field.mask(maskBehavior.apply({}, arguments), options);
        },
        translation: {
            'H': { pattern: /[0-2]/, optional: false },
            'Z': { pattern: /[0-3]/, optional: false },
            'M': { pattern: /[0-5]/, optional: false }
        }
    };


    $('#input-tempo').mask(maskBehavior, spOptions);
    $('#sendEmailExport').on('click', () => modalExport());

    $("form").submit(event => event.preventDefault());
    if (document.querySelector(".btn-success")) {
        document.querySelector(".btn-success").addEventListener("click", submit);
    }

});


function submit() {

    const input_name = formValidation({ field: document.getElementById("input-name"), text: GLOBAL_LANG.ticket_sla_name, required: true, min: 3, max: 100 });
    const input_time = formValidation({ field: document.getElementById("input-tempo"), text: GLOBAL_LANG.ticket_sla_clock, required: true, numeric_length: 4 });

    if (input_name && input_time) {
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

        default:
            break;
    }

    $.get(`/export/xlsx?
        search=${$('#search').val()}
        &column=${column}
        &order=${order}
        &type=ticketSLA`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.ticket_sla_alert_export_title,
                text: GLOBAL_LANG.ticket_sla_alert_export_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.ticket_sla_alert_export_confirmButtonText
            });
        }
    });
}