function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "modelo/find",
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
                targets: 0,
                render: function (data, type, full, meta) {
                    return "<span class='badge badge-pill badge-success' style='color: black; background-color:" + full.color + ";'>" + full.name + "</span>";
                }
            },
            {
                targets: 1,
                render: function (data, type, full, meta) {
                    return "<a href='#' id='" + full.id_ticket_status + "' class='table-action table-action-edit' data-toggle='tooltip' data-original-title='Editar'>" +
                        "<i class='fas fa-user-edit'></i></a>" +
                        "<a id='" + full.id_ticket_status + "' href='#' class='table-action table-action-delete' data-toggle='tooltip' data-original-title='Deletar'>" +
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
        }
    });
}

$(document).ready(function () {

    $("#search").on("keyup", function (event) {
        if (event.which == 13) {
            find();
        }
    });
    find();


    $("#datatable-basic").on("click", ".table-action-edit", function () {
        window.location.href = "status/edit/" + this.id;
    });


    $("#datatable-basic").on("click", ".table-action-delete", function () {
        swal({
            title: GLOBAL_LANG.modelo_alert_delete_title,
            text: GLOBAL_LANG.modelo_alert_delete_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.modelo_alert_delete_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.modelo_alert_delete_cancelButtonText,
        }).then(t => {
            if (t.value == true) {
                $.post("status/delete/" + this.id, function (data) {
                    if (data.length < 1) {
                        t.value && swal({
                            title: GLOBAL_LANG.modelo_alert_delete_two_title,
                            text: GLOBAL_LANG.modelo_alert_delete_two_text,
                            type: "success",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-success"
                        });
                    } else {
                        t.value && swal({
                            title: GLOBAL_LANGmodelo_alert_delete_three_title,
                            text: GLOBAL_LANG.modelo_alert_delete_three_text,
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


    $("#is_open").on("click", function () {
        $('#is_close').prop('checked', false);
    });


    $("#is_close").on("click", function () {
        $('#is_open').prop('checked', false);
    });


    $('#sendEmailExport').on('click', () => modalExport());

});


function modalExport() {

    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!re.test(String($('#emailExport').val()).toLowerCase())) {
        Swal.fire({
            title: GLOBAL_LANG.modelo_alert_export_title,
            text: GLOBAL_LANG.modelo_alert_export_text,
            type: 'warning',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: GLOBAL_LANG.modelo_alert_export_confirmButtonText
        });
        $('.swal2-container').css('z-index', 10000);
        return false;
    }

    $.get(`/csv/export?search=${$('#search').val()}&email=${$('#emailExport').val()}&type=ticketStatus`, function (response) {
        Swal.fire({
            title: GLOBAL_LANG.modelo_alert_export_two_title,
            text: GLOBAL_LANG.modelo_alert_export_two_text,
            type: 'success',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: GLOBAL_LANG.modelo_alert_export_two_confirmButtonText
        });
    });
}