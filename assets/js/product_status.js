function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "status/find",
            type: "POST",
            data: {
                text: $("#search").val(),
                csrf_talkall: Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'name'
            },
        ],
        columns: [
            {
                mData: 'message'
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
                    if (full.message.length > 90) {
                        return "<span>" + full.message.substring(0, 90) + "..." + "</span>";
                    } else {
                        return "<span>" + full.message + "</span>";
                    }

                }
            },
            {
                targets: 2,
                render: function (data, type, full, meta) {
                    return "<a href='#' id='" + full.id_order_status + "' class='table-action table-action-edit' data-toggle='tooltip' data-original-title='Editar'>" +
                        "<i class='fas fa-user-edit'></i></a>" +
                        "<a id='" + full.id_order_status + "' href='#' class='table-action table-action-delete' data-toggle='tooltip' data-original-title='Deletar'>" +
                        "<i class='fas fa-trash'></i>" +
                        "</a>";
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
    find();


    $("#datatable-basic").on("click", ".table-action-edit", function () {
        window.location.href = "status/edit/" + this.id;
    });


    $("#datatable-basic").on("click", ".table-action-delete", function () {
        swal({
            title: "Você tem certeza?",
            text: "Que deseja remover esse status de ticket?",
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            confirmButtonClass: "btn btn-danger",
            confirmButtonText: "Sim",
            cancelButtonClass: "btn btn-secondary",
            cancelButtonText: "Não",
        }).then(t => {
            if (t.value == true) {
                $.post("status/delete/" + this.id, function (data) {
                    if (data.length < 1) {
                        t.value && swal({
                            title: "Deletado!",
                            text: "Status de ticket removido com sucesso!",
                            type: "success",
                            buttonsStyling: !1,
                            confirmButtonClass: "btn btn-primary"
                        });
                    } else {
                        t.value && swal({
                            title: "Status não removido!",
                            text: "Há tickets com esse Status!",
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
            title: 'Atenção',
            text: 'Informe um e-mail válido para exportação do arquivo.',
            type: 'warning',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ok!'
        });
        $('.swal2-container').css('z-index', 10000);
        return false;
    }

    $.get(`/csv/export?
        type=ticketStatus`, function (response) {
        Swal.fire({
            title: 'Lista enviada!',
            text: 'O e-mail pode demorar até uma hora para chegar.',
            type: 'success',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ok!'
        });
    });
}