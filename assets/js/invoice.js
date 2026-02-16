$(document).ready(function () {
    $('#input-value').mask('000.000.000.000.000,00', { reverse: true });

    $('#file1').on('change', function (e) {
        $(this).next('.custom-file-label').html(e.target.files[0].name);
    });

    $('#file2').on('change', function (e) {
        $(this).next('.custom-file-label').html(e.target.files[0].name);
    });

    $('#file3').on('change', function (e) {
        $(this).next('.custom-file-label').html(e.target.files[0].name);
    });

    $("#datatable-basic").on("click", ".table-action-edit", function () {
        window.location.href = "invoice/edit/" + this.id;
    });

    $("#datatable-basic").on("click", ".table-action-delete", function () {
        swal({
            title: "Você tem certeza?",
            text: "Que deseja remover essa fatura",
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            confirmButtonClass: "btn btn-danger",
            confirmButtonText: "Sim",
            cancelButtonClass: "btn btn-secondary",
            cancelButtonText: "Não",
            keydownListenerCapture: false,
            allowOutsideClick: false,
            allowEscapeKey: false,
        }).then(t => {
            if (t.value == true) {
                $.post("invoice/delete/" + this.id, function (data) {
                    t.value && swal({
                        title: "Deletado!",
                        text: "Fatura removida com sucesso!",
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-primary"
                    });
                    $("#datatable-basic").DataTable().ajax.reload(null, false);
                });
            }
        });
    });

    $("#search").on("keyup", function (event) {
        if (event.which == 13) {
            find();
        }
    });


    if ($("#search").val() !== undefined) {
        find();
    }

});

function find() {
    $('#datatable-basic').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "invoice/find",
            "type": "POST",
            "data": {
                "text": $("#search").val(),
                "csrf_talkall": Cookies.get("csrf_cookie_talkall")
            }
        },
        "columns": [{
            mData: 'corporate_name'
        },
        {
            mData: 'description'
        },
        {
            mData: 'expire'
        },
        {
            mData: 'value'
        },
        {
            mData: 'status'
        }
        ],
        "columnDefs": [
            {
                "targets": 4,
                "render": function (data, type, full, meta) {
                    var status = "";
                    switch (full.status) {
                        case "1":
                            status = "<span class='badge badge-success badge-pill'style='color: black; background-color:#1e90ff;'>Aberto</span>";
                            break;
                        case "2":
                            status = "<span class='badge badge-danger badge-pill' style='color: black; background-color:#00ff40;'>Fechado</span>";
                            break;
                        case "3":
                            status = "<span class='badge badge-info badge-pill' style='color: black; background-color:#ff0000;'>Cancelado</span>";
                            break;
                        case "4":
                            status = "<span class='badge badge-warning badge-pill' style='color: black; background-color:#ffff00;'>Revisão</span>";
                            break;
                    }

                    return status;
                }
            },
            {
                "targets": 5,
                "render": function (data, type, full, meta) {
                    var element = "";
                    if (full.payment_document) {
                        element += "<a href='" + full.payment_document + "' target='_blank' class='table-action' data-toggle='tooltip' title='Boleto'>" +
                            "<i class='fas fa-file-invoice'></i></a>";
                    } else {
                        element += "<a href='#' class='table-action' data-toggle='tooltip' title='Boleto Indisponivel' >" +
                            "<i class='far fa-file'></i></a>"
                    }

                    if (full.fiscal_document) {
                        element += "<a href='" + full.fiscal_document + "' target='_blank' class='table-action' data-toggle='tooltip' title='Nota Fiscal'>" +
                            "<i class='fas fa-file-invoice'></i></a>";
                    } else {
                        element += "<a href='#' class='table-action' data-toggle='tooltip' title='Nota Fiscal Indisponivel' >" +
                            "<i class='far fa-file'></i></a>"
                    }

                    if (full.proof_of_payment) {
                        element += "<a href='" + full.proof_of_payment + "'  target='_blank' class='table-action' data-toggle='tooltip' title='Comprovante de Pagamento'>" +
                            "<i class='fas fa-file-invoice'></i></a>";
                    } else {
                        element += "<a href='#' class='table-action' data-toggle='tooltip' title='Comprovante de Pagamento Indisponivel' >" +
                            "<i class='far fa-file'></i></a>"
                    }
                    return element;
                }
            },
            {
                "targets": 6,
                "render": function (data, type, full, meta) {

                    return "<a href='#' id='" + full.id_invoice + "' class='table-action table-action-edit' data-toggle='tooltip' data-original-title='Editar'>" +
                        "<i class='fas fa-edit'></i></a>" +
                        "<a id='" + full.id_invoice + "' href='#' class='table-action table-action-delete' data-toggle='tooltip' data-original-title='Deletar'>" +
                        "<i class='fas fa-trash'></i>" +
                        "</a>";
                }
            },
            {
                orderable: false,
                targets: [0]
            }
        ],
        "pagingType": "numbers",
        "pageLength": 5,
        "destroy": true,
        "fixedHeader": true,
        "responsive": true,
        "lengthChange": false,
        "searching": false,
        "paginate": true,
        "info": true,
        "language": {
            "url": `${document.location.origin}/assets/lang/${localStorage.getItem("language")}.json`
        },

        drawCallback: function (settings) {
            if (settings.json.recordsTotal == 0) {
                $("#modalExport").attr('disabled', true);
            }
        }

    });
}