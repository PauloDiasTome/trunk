function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "myinvoice/find",
            type: "POST",
            data: {
                text: $("#search").val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [{
            mData: 'corporate_name'
        },
        {
            mData: 'description'
        },
        {
            mData: 'expire'
        },
        {
            mData: 'value',
        },
        {
            mData: 'status'
        }
        ],
        columnDefs: [
            {
                targets: 3,
                render: function (data, type, full, meta) {
                    return parseInt(full.value).toLocaleString('pt-BR', {
                        style: 'currency',
                        currency: 'BRL'
                    });
                }
            },
            {
                targets: 4,
                render: function (data, type, full, meta) {
                    var status = "";
                    switch (full.status) {
                        case "1":
                            status = "<span class='badge badge-success badge-pill' style='color: black; background-color:#1e90ff;'>" + GLOBAL_LANG.open_status + "</span>";
                            break;
                        case "2":
                            status = "<span class='badge badge-danger badge-pill' style='color: black; background-color:#00ff40;'>" + GLOBAL_LANG.closed_status + "</span>";
                            break;
                        case "3":
                            status = "<span class='badge badge-info badge-pill' style='color: black; background-color:#ff0000;'>" + GLOBAL_LANG.canceled_status + "</span>";
                            break;
                        case "4":
                            status = "<span class='badge badge-warning badge-pill' style='color: black; background-color:#ffff00;'>" + GLOBAL_LANG.review_status + "</span>";
                            break;
                    }
                    return status;
                }
            },
            {
                targets: 5,
                render: function (data, type, full, meta) {
                    var element = "";
                    if (full.payment_document) {
                        element += "<a href='" + full.payment_document + "' target='_blank' class='table-action' data-toggle='tooltip' title='" + GLOBAL_LANG.ticket + "'>" +
                            "<i class='fas fa-file-invoice'></i></a>";
                    } else {
                        element += "<a href='#' class='table-action' data-toggle='tooltip' title='" + GLOBAL_LANG.ticket_unavailable + "'>" +
                            "<i class='far fa-file'></i></a>"
                    }

                    if (full.fiscal_document) {
                        element += "<a href='" + full.fiscal_document + "' target='_blank' class='table-action' data-toggle='tooltip' title='" + GLOBAL_LANG.invoice + "'>" +
                            "<i class='fas fa-file-invoice'></i></a>";
                    } else {
                        element += "<a href='#' class='table-action' data-toggle='tooltip' title='" + GLOBAL_LANG.invoice_unavailable + "'>" +
                            "<i class='far fa-file'></i></a>"
                    }

                    if (full.proof_of_payment) {
                        element += "<a href='" + full.proof_of_payment + "'  target='_blank' class='table-action' data-toggle='tooltip' title='" + GLOBAL_LANG.payment_voucher + "'>" +
                            "<i class='fas fa-file-invoice'></i></a>";
                    } else {
                        element += "<a href='#' class='table-action' data-toggle='tooltip' title='" + GLOBAL_LANG.proof_of_payment_unavailable + "'>" +
                            "<i class='far fa-file'></i></a>"
                    }
                    return element;
                }
            },
            {
                targets: 6,
                render: function (data, type, full, meta) {
                    if (full.proof_of_payment) {
                        return null;
                    }
                    return "<a href='#' id='" + full.id_invoice + "' class='table-action table-action-add-file' data-toggle='tooltip' title='Inserir Comprovante de Pagamento' data-original-title='Editar'>" +
                        "<i class='fas fa-edit'></i></a>";
                }
            },
            {
                orderable: false,
                targets: [0]
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


$(document).ready(function () {

    $("#search").on("keyup", function (event) {
        if (event.which == 13) {
            find();
        }
    });


    if ($("#search").val() !== undefined) {
        find();
    }


    $('#file').on('change', function (e) {
        $(this).next('.custom-file-label').html(e.target.files[0].name);
    });


    $("#datatable-basic").on("click", ".table-action-add-file", function () {
        window.location.href = "myinvoice/addFile/" + this.id;
    });


    $("form").submit(function (e) {
        e.preventDefault();
        var url = $(this).attr("action");
        var formData = new FormData(this);

        swal({
            title: GLOBAL_LANG.myinvoice_alert_invoice_title,
            text: GLOBAL_LANG.myinvoice_alert_invoice_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.myinvoice_alert_invoice_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.myinvoice_alert_invoice_cancelButtonText,
            keydownListenerCapture: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
        }).then(t => {
            if (t.value == true) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function (data) {
                        t.value && swal({
                            title: GLOBAL_LANG.myinvoice_alert_invoice_two_title,
                            text: GLOBAL_LANG.myinvoice_alert_invoice_two_text,
                            type: "success",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-success",
                            onAfterClose: () => {
                                window.location.href = window.location.origin + "/myinvoice";
                            }
                        });
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            }
        });
    });

    $('#sendEmailExport').on('click', () => modalExport());

});


function modalExport() {

    let column = $("#datatable-basic").DataTable().order()[0][0];
    let order = $("#datatable-basic").DataTable().order()[0][1];

    switch (column) {
        case 0:
            column = "corporate_name";
            break;

        case 1:
            column = "description";
            break;

        case 2:
            column = "expires";
            break;

        case 3:
            column = "value";
            break;

        case 4:
            column = "status";
            break;

        default:
            break;
    }

    $.get(`/export/xlsx?
        search=${$('#search').val()}
        &column=${column}
        &order=${order}
        &type=myInvoice`, function (response) {
        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.myinvoice_alert_export_two_title,
                text: GLOBAL_LANG.myinvoice_alert_export_two_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.myinvoice_alert_export_confirmButtonText,
            });
        }
    });
}