function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "company/find",
            type: "POST",
            data: {
                "text": $("#search").val(),
                "csrf_talkall": Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'fantasy_name'
            },
            {
                mData: 'cnpj'
            }
        ],
        columnDefs: [
            {
                orderable: false,
                targets: 2,
                render: function (data, type, full, meta) {

                    return `<div class="dropleft">
                                    <button class="btn btn-link mb-0 btn-dropleft" type="button" id="multiDropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #525f7f">
                                        <i class="fa fa-ellipsis-v"></i>
                                    <div class="ripple-container"></div>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="multiDropdownMenu">
                                        <a id="` + full.id_company + `" href='#' class="dropdown-item table-btn-edit" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block">    
                                                <i class="far fa-edit"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.client_company_dt_columndefs_edit}</span>
                                        </a>
                                        <a id="` + full.id_company + `" href='#' class="dropdown-item table-btn-delete" style="cursor: pointer">
                                            <div style="width: 24px; display: inline-block">
                                                <i class="far fa-trash-alt"></i>
                                            </div>
                                            <span>${GLOBAL_LANG.client_company_dt_columndefs_delete}</span>
                                        </a>
                                    </div>
                                </div>`
                }
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


    $("#datatable-basic").on("click", ".table-btn-edit", function () {
        window.location.href = "/client/company/edit/" + this.id;
    });


    $("#datatable-basic").on("click", ".table-btn-delete", function () {
        swal({
            title: GLOBAL_LANG.client_company_alert_delete_title,
            text: GLOBAL_LANG.client_company_alert_delete_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.client_company_alert_delete_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.client_company_alert_delete_cancelButtonText,
        }).then(t => {
            if (t.value == true) {
                $.post(document.location.origin + "/client/company/delete/" + this.id, function (data) {
                    if (data.length < 1) {
                        t.value && swal({
                            title: GLOBAL_LANG.client_company_alert_delete_two_title,
                            text: GLOBAL_LANG.client_company_alert_delete_two_text,
                            type: "success",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-success"
                        });
                    } else {
                        t.value && swal({
                            title: GLOBAL_LANG.client_company_alert_delete_three_title,
                            text: GLOBAL_LANG.client_company_alert_delete_three_text,
                            type: "warning",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-primary"
                        });
                    }
                    $("#datatable-basic").DataTable().ajax.reload(null, false);
                });
            }
        });
    });


    $("#input-cnpj").mask("99.999.999/9999-99");

    $("#sendEmailExport").on("click", modalExport);
    $("form").submit(event => event.preventDefault());
    if (document.querySelector(".btn-success")) {
        document.querySelector(".btn-success").addEventListener("click", submit);
    }

});


function submit() {
    document.querySelector(".btn-success").disabled = true;
    $("form").unbind('submit').submit();
}

function modalExport() {
    let column = $("#datatable-basic").DataTable().order()[0][0];
    let order = $("#datatable-basic").DataTable().order()[0][1];

    switch (column) {
        case 0:
            column = "fantasy_name";
            break;
        case 1:
            column = "cnpj";
            break;

        default:
            break;
    }

    $.get(`/export/xlsx?
        search=${$('#search').val()}
        &column=${column}
        &order=${order}
        &type=clientCompany`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.client_company_alert_export_title,
                text: GLOBAL_LANG.client_company_alert_export_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.client_company_alert_export_confirmButtonText
            });

        }

    });
}


function validNumber(evt) {
    if (evt.which != 8 && evt.which != 0 && evt.which < 48 || evt.which > 57) {
        evt.preventDefault();
    }
}

if (document.querySelector("#input-cnpj") != null) {
    document.querySelector("#input-cnpj").addEventListener("keypress", validNumber);
}
