function find() {
    $('#datatable-basic').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "status/find",
            type: "POST",
            data: {
                "text": $("#search").val(),
                "csrf_talkall": Cookies.get("csrf_cookie_talkall")
            }
        },
        columns: [
            {
                mData: 'id_order_status'
            },
        ],
        columnDefs: [
            {
                targets: 0,
                render: function (data, type, full, meta) {
                    return "<div class='kt-user-card-v2'>" + "<div class='kt-user-card-v2__color'>" +
                        "<div class='kt-user-card-v2__details'>" +
                        "<div class='kt-user-card-v2__tag' style='text-align:center; margin-top:10px; position:absolute; margin-left:40px;'>" + full.name + "</div>" +
                        "</div>" +

                        "<div style='width: 32px; height:32px;'><svg xmlns='http://www.w3.org/2000/svg' style='fill:" + full.color + "' class='color' viewBox='0 0 18 12'><path d='M11.208,0.925H2.236C1.556,0.925,1,1.565,1,2.357V9.57C1,10.362,1.556,11,2.236,11h8.972 c0.414,0,0.785-0.237,1.007-0.604l2.701-4.433L12.215,1.53C11.993,1.162,11.622,0.925,11.208,0.925z' rounded-circle mr-3 m-img-rounded kt-marginless'>" +
                        "</div>" +
                        "</svg>" +

                        "</div>";
                }
            },
            {
                targets: 1,
                render: function (data, type, full, meta) {
                    return "<a href='#' id='" + full.id_order_status + "' class='table-action table-action-edit' data-toggle='tooltip' data-original-title='" + GLOBAL_LANG.order_status_dt_columndefs_target2_title_edit + "'>" +
                        "<i class='fas fa-user-edit'></i></a>" +
                        "<a id='" + full.id_order_status + "' href='#' class='table-action table-action-delete' data-toggle='tooltip' data-original-title='" + GLOBAL_LANG.order_status_dt_columndefs_target2_title_delete + "'>" +
                        "<i class='fas fa-trash'></i>" +
                        "</a>";
                }
            },
            {
                orderable: true,
                targets: [0],
            }
        ],
        order: [[1, "asc"]],
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


    if ($("#search").val() !== undefined) {
        find();
    }


    $("#datatable-basic").on("click", ".table-action-edit", function () {
        window.location.href = "status/edit/" + this.id;
    });


    $("#datatable-basic").on("click", ".table-action-delete", function () {
        swal({
            title: GLOBAL_LANG.order_status_alert_delete_title,
            text: GLOBAL_LANG.order_status_alert_delete_text,
            type: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            reverseButtons: true,
            confirmButtonClass: "btn btn-success",
            confirmButtonText: GLOBAL_LANG.order_status_alert_delete_confirmButtonText,
            cancelButtonClass: "btn btn-danger",
            cancelButtonText: GLOBAL_LANG.order_status_alert_delete_cancelButtonText,
        }).then(t => {
            if (t.value == true) {
                $.post("order/status/delete/" + this.id, function (data) {
                    t.value && swal({
                        title: GLOBAL_LANG.order_status_alert_delete_two_title,
                        text: GLOBAL_LANG.order_status_alert_delete_two_text,
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
        &type=orderStatus`, function (response) {

        if (response != "Error") {
            Swal.fire({
                title: GLOBAL_LANG.order_status_alert_export_title,
                text: GLOBAL_LANG.order_status_alert_export_text,
                type: 'success',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: GLOBAL_LANG.order_status_alert_export_confirmButtonText
            });
        }
    });
}