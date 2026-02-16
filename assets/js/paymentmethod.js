function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: window.location.origin + "/payment/method/find",
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
        ],
        columnDefs: [
            {
                targets: 1,
                render: function (data, type, full, meta) {
                    return "<a href='#' id='" + full.id_payment_method + "' class='table-action table-action-edit' data-toggle='tooltip' data-original-title='Editar'>" +
                        "<i class='fas fa-user-edit'></i></a>" +
                        "<a id='" + full.id_payment_method + "' href='#' class='table-action table-action-delete' data-toggle='tooltip' data-original-title='Deletar'>" +
                        "<i class='fas fa-trash'></i>" +
                        "</a>";
                }
            },
            {
                orderable: true,
                targets: [0]
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


    $("#datatable-basic").on("click", ".table-action-edit", function () {
        window.location.href = window.location.origin + "/payment/method/edit/" + this.id;
    });


    $("#datatable-basic").on("click", ".table-action-delete", function () {
        swal({
            title: GLOBAL_LANG.order_payment_method_alert_delete_title,
            text: GLOBAL_LANG.order_payment_method_alert_delete_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.order_payment_method_alert_delete_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.order_payment_method_alert_delete_cancelButtonText,
        }).then(t => {
            if (t.value == true) {
                $.post(window.location.origin + "/payment/method/delete/" + this.id, function (data) {
                    t.value && swal({
                        title: GLOBAL_LANG.order_payment_method_alert_delete_two_title,
                        text: GLOBAL_LANG.order_payment_method_alert_delete_two_text,
                        type: "success",
                        buttonsStyling: !1,
                        confirmButtonClass: "btn btn-success"
                    });
                    $("#datatable-basic").DataTable().ajax.reload(null, false);
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
            column = "name";
            break;
        default:
            break;
    }

    $.get(`/export/xlsx?
       search=${$('#search').val()}
       &column=${column}
       &order=${order}
       &type=payment`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.order_payment_alert_export_title,
                text: GLOBAL_LANG.order_payment_alert_export_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.order_payment_alert_export_two_confirmButtonText
            });
        }
    });
}